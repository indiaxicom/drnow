<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 December, 2014
 * Last 
 * Decription   -   Shifting functionalities.
 * */
class Shifts extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if (is_doctor() == FALSE) {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'login');
        }
        $this->load->model('shift_model');
        $this->current_module = $this->uri->segment(1);
    }

    public function index()
    {
        $page_data = NULL;

        /* Fetching shifts which are vacant and not claimed by any doctor */
        $conditions = $params = array();
        $conditions['doctor_id'] = 0;
        $conditions['app_id <> '] = 0;
        $conditions['start_time > '] = date('Y-m-d H:i');
        $params['fields'] = 'shifts.id, shifts.start_time, shifts.end_time';
        $get_all_shifts = $this->shift_model->get_all($conditions, $params);

        $page_variables['container']['get_all_shifts'] = $get_all_shifts;
        $data['page_view']['container'] = 'shifts_module/container';

        /* Variables For Header */
        $page_variables['right_container'] = array();
        $data['page_view']['right_container'] = 'shifts_module/right_shifts';

        /* Variables to register on whole page*/
        $data['title'] = 'Doctor\'s Shifts | ' . SITE_NAME;
        $data['js_script'] = 'shifts_module/js_script';
        $data['hide_notification_bar'] = TRUE;
        /* Extra css classes to add in template */
        $data['extra_wrapper_class'] = 'shift-appointment';
        //$data['extra_container_class'] = 'calendar-page-outer-con';
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate( $data);
    }

    /*
     * Author - Dave Brown
     * Created - 28 November 2014
     * Description - This is the popup screen for schedule details
     */
    public function schedule_details()
    {
        $content = $this->load->view('schedule_module/popup_schedule_details', array(), TRUE);
        exit(json_encode(array('content' => $content)));
    }

    /*
     * Author - Dave Brown
     * Created - 16 December 2014
     * Description - This function is used to claim shift
     */
    public function claim_shift()
    {
        if (post_data('shift_id') == NULL || $this->input->is_ajax_request() == FALSE)
        {
            return;
        }

        /*Fetching Shift details. this is to check if is not already claimed by other doctor simultaneously */
        $conditions = $params = array();
        $params['fields'] = 'shifts.id, shifts.doctor_id, shifts.start_time, shifts.end_time, shifts.app_id';
        $params['single'] = TRUE;
        $conditions['id'] = post_data('shift_id');
        $get_all_shifts = $this->shift_model->get_all($conditions, $params);

        if ($get_all_shifts->doctor_id != 0)
        {
            $response = array('shift_id' => $get_all_shifts->id, 'success' => FALSE, 'message' => 'Already Claimed');
        }
        elseif ($get_all_shifts->start_time < date('Y-m-d H:i'))
        {
            $response = array('shift_id' => $get_all_shifts->id, 'success' => FALSE, 'message' => 'Shift Expired');
        }
        elseif ($this->shift_model->check_shift_between_time(session_data('id'), $get_all_shifts->start_time, $get_all_shifts->end_time))
        {
             $response = array('shift_id' => $get_all_shifts->id, 'success' => FALSE, 'message' => 'Shift time already claimed by you');
        }
        else
        {
            /* Saving Shift Schedule To Third Party's Database*/
            $app_response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                'action' => 'reservescheduleslot',
                'scheduleid' => $get_all_shifts->app_id,
                'doctorid' => session_data('app_user_id'),
            )));

            if (json_decode($app_response) && json_decode($app_response)->Status == 1)
            {
                $this->shift_model->save(array(
                    'id' => $get_all_shifts->id,
                    'doctor_id' => session_data('id')
                    )
                );
                $response = array('shift_id' => $get_all_shifts->id, 'success' => TRUE, 'message' => 'Claimed');
            }
            else
            {
                $response = array('shift_id' => $get_all_shifts->id, 'success' => FALSE, 'message' => 'Unexpected Error');
            }
        }
        exit(json_encode($response));
    }

    /*
     * Author - Dave Brown
     * Created - 16 December 2014
     * Description - This function is used to fetch shifts by date
     */
    public function get_shifts_by_date()
    {
        if (post_data('date') == NULL || $this->input->is_ajax_request() == FALSE)
        {
            return;
        }

        $shifts_arr = array();

        /*Fetching Shift details. this is to check if is not already claimed by other doctor simultaneously */
        $conditions = $params = array();
        $params['fields'] = 'shifts.id, doctor_id, start_time, end_time';
        $conditions['doctor_id'] = session_data('id');
        $conditions['DATE(start_time)'] = post_data('date');
        $get_all_shifts = $this->shift_model->get_all($conditions, $params);

        /*Format according to calendar*/
        if (!empty($get_all_shifts))
        {
            foreach($get_all_shifts as $val)
            {
                $shifts_arr[] = convert_from_sql_time('H:i', $val->start_time) . ' - ' . convert_from_sql_time('H:i', $val->end_time);
            }
        }
        exit(json_encode($shifts_arr));
    }
}
