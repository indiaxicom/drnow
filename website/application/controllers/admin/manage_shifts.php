<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 Nov, 2014
 * Last 
 * Decription   -   Email Templates Management Controller for Admin.
 * */
class Manage_Shifts extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        if (is_admin() == FALSE)
        {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'admin/login');
        }
        $this->load->library('pagination');
        $this->load->model('shift_model');
        $this->current_module = $this->uri->segment(2);
    }

    public function index() 
    {
        $page_data = NULL;

        $conditions = $params = array();
        $params['cnt'] = TRUE;
        $params['join'][] = array('type' => 'left', 'table' => 'users', 'conditions' => 'shifts.doctor_id = users.id AND users.user_type = ' . USER_DOCTOR);
        $params['fields'] = 'shifts.*, users.first_name, users.last_name';
        $numrows = $this->shift_model->get_all($conditions, $params); /*GETTING TOTAL ROWS FOR PAGINATION*/
        $config['base_url'] = base_url() . 'admin/' . $this->current_module;
        $config['total_rows'] = $numrows;
        $config['per_page'] = 20; /*No of records per page*/
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page_data = $this->pagination->create_links();
        $page_num = $this->uri->segment(3);
        $params['page'] = $this->uri->segment(3) != NULL ? $this->uri->segment(3) + 1 : 1;
        $params['show'] = $config['per_page'];
        unset($params['cnt']);
        $get_all_records = $this->shift_model->get_all($conditions, $params);

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_records'] = $get_all_records;
        $page_variables['container']['page_data'] = $page_data;
        $data['page_view']['container'] = 'admin/manage_shifts_module/listing';

        //Variables For Header
        $page_variables['header'] = array();
        $data['page_view']['header'] = NULL;

        //Variables For Footer
        $data['title'] = 'Add New News';
        $data['page_variables'] = $page_variables;
        $this->load->view('admin/common/template', $data);
    }
    
    public function add ($id = NULL)
    {
        $get_all_records = NULL;

        if (!empty($id))
        {
            $conditions = $params = array();
            $conditions['shifts.id'] = $id;
            $params['single'] = TRUE;
            $get_all_records = $this->shift_model->get_all($conditions, $params);
        }

        if (post_data('submit') || post_data('submit_new'))
        {
            $this->form_validation->set_rules('start_time', 'Start Time', 'required|trim');
            $this->form_validation->set_rules('end_time', 'End Time', 'required|trim|htmlspecialchars');
            
            if ($this->form_validation->run() == TRUE)
            {
                $table_data = array();
                $app_id = NULL;
                $start_time = date('Y-m-d', uk_date_to_stamp(post_data('shift_date'), 'd/m/Y')) . ' ' . post_data('start_time');
                $end_time = date('Y-m-d', uk_date_to_stamp(post_data('shift_date'), 'd/m/Y')) . ' ' . post_data('end_time');


                /* Checking if doctor's shift is already available between the time */
                if (post_data('doctor_id') <> NULL)
                {
                    $doc_shift_exist = $this->shift_model->check_shift_between_time(post_data('doctor_id'), $start_time, $end_time);

                    if ($doc_shift_exist)
                    {
                        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1009]);
                        redirect(current_url());
                    }
                }
                /*--------------------Checking Ends------------------- */
                

                if (post_data('id') != NULL)
                {
                    $table_data['id'] = post_data('id');
                    $table_data['app_id'] = !empty($get_all_records->app_id) ? $get_all_records->app_id : 0;
                    
                    /* Creating record in third Party's Database in case of new record */
                    if (!empty($table_data['app_id']))
                    {
						$edit_response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
							'action' => 'editscheduleslot',
							'slotstart' => $start_time,
							'Appointments' => ceil((strtotime($end_time) - strtotime($start_time)) / (60 * APPOINTMENT_INTERVAL)),
							'Interval' => APPOINTMENT_INTERVAL,
							'ScheduleId' => $table_data['app_id']
						)));
					}

                }
                else
                {
                    /* Creating record in third Party's Database in case of new record */
                    $response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                        'action' => 'createscheduleslot',
                        'slotstart' => $start_time,
                        'Appointments' => ceil((strtotime($end_time) - strtotime($start_time)) / (60 * APPOINTMENT_INTERVAL)),
                        'Interval' => APPOINTMENT_INTERVAL
                    )));

                    $table_data['app_id'] = current(json_decode($response))->ScheduleId;
                }

                /* Assigning to doctor in case of doctor id is coming and schedule id is genereated from the third parties database*/
                if (post_data('doctor_id') != NULL && !empty($table_data['app_id']))
                {
                    /* Updating Third Party's database with doctor id */
                    $app_response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                        'action' => 'reservescheduleslot',
                        'scheduleid' => $table_data['app_id'],
                        'doctorid' => $this->users_model->get_app_doctor_id(post_data('doctor_id')),
                    )));

                    if (json_decode($app_response) && json_decode($app_response)->Status == 1)
                    {
                        $table_data['doctor_id'] = post_data('doctor_id');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1008]);
                        redirect(current_url());
                    }
                }

                $table_data['start_time'] = $start_time;
                $table_data['end_time'] = $end_time;
                $this->shift_model->save($table_data);
                $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1001]);

                if (post_data('submit_new') != NULL)
                {
                    redirect(base_url() . 'admin/' . $this->current_module . '/add');
                }
                redirect(base_url() . 'admin/' . $this->current_module);
            }
        }

        /*Fetching Doctors*/
        $conditions = $params = array();
        $conditions['status'] = ACTIVE;
        $conditions['user_type'] = USER_DOCTOR;

        $params['fields'] = 'users.id, users.first_name, users.last_name';
        $get_all_doctors = $this->users_model->get_all($conditions, $params);

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_records'] = $get_all_records;
        $page_variables['container']['get_all_doctors'] = $get_all_doctors;
        $data['page_view']['container'] = 'admin/manage_shifts_module/add_edit';

        //Variables For Footer
        $data['title'] = 'Add New News';
        $data['page_variables'] = $page_variables;
        $this->load->view('admin/common/template', $data);
    }
    
    public function change_status()
    {
        if (post_data('update_ids') != NULL)
        {
            foreach (post_data('update_ids') as $val)
            {
                $data['id'] = $val;
            }
            $data['status'] = post_data('status');
            $this->shift_model->change_status($data);
        }
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1002]);
        redirect(base_url() . 'admin/' . $this->current_module);
    }
}

