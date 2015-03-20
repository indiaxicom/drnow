<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   25 November, 2014
 * Last 
 * Decription   -   Scheduling functionalities.
 * */

class Schedule extends MY_Controller {

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
        $page_data = $patient_ids = NULL;
        $appointments_calendar_events = $patient_details = array();

        /* ----Getting All apointments of the doctor in session ------*/

        $conditions = $params = array();
        $conditions['appointment_details.user_id'] = session_data('id');
        $conditions['appointments.status'] = ACTIVE;
        //$conditions['appointments.start_time >= '] = date('Y-m-d H:i:s');

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id AND appointment_details.user_type = ' . USER_DOCTOR
        );
        $params['join'][] = array(
            'table' => 'appointment_details AS ADP',
            'conditions' => 'appointments.id = ADP.appointment_id AND ADP.user_type = ' . USER_PATIENT
        );
        $params['fields'] = 'appointments.id, appointments.start_time, appointments.end_time, ADP.user_id AS patient_id';

        $get_all_appointments = $this->appointment_model->get_all($conditions, $params);

        /* Calendar Format of schdeules */

        if (!empty($get_all_appointments))
        {
            foreach($get_all_appointments as $val)
            {
                if (convert_from_sql_time('Y-m-d', $val->start_time) == date('Y-m-d'))
                {
                    $patient_ids[] = $val->patient_id;
                }

                $appointments_calendar_events[] = array(
                    'id' => $val->id,
                    'title' => 'Booked',
                    'start' => str_replace('~', 'T', convert_from_sql_time('Y-m-d~H:i:s', $val->start_time)),
                    'end' => str_replace('~', 'T', convert_from_sql_time('Y-m-d~H:i:s', $val->end_time)),
                    'backgroundColor' => '#FF7868',
                );
            }

            /*Fetching Patient details from third party database via curl request*/
            $patient_details = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                'action' => 'getpatientlist',
                'patientid' => $patient_ids,
            )));

            $patient_details = combine_keys_values(json_decode($patient_details, TRUE));
        }

        /*fetch Available Shifts Dates of the doctor*/
        $conditions = $params = array();
        $params['fields'] = 'DATE(start_time) AS shift_date';
        $params['return_field'] = 'shift_date';
        $conditions['doctor_id'] = session_data('id');
        $available_shift_dates = $this->shift_model->get_all($conditions, $params);

        /*-----------------Appointments Fetching Ends ----------------*/

        $page_variables['container']['get_all_appointments'] = $get_all_appointments;
        $data['page_view']['container'] = 'schedule_module/calendar';

        /* Variables For Header */
        $page_variables['right_container'] = array();
        $page_variables['right_container']['patient_details'] = $patient_details;
        $data['page_view']['right_container'] = 'schedule_module/right_schedule';

        /* Variables to register on whole page*/
        $data['title'] = 'Doctor\'s Schedule | ' . SITE_NAME;
        $data['js_script'] = 'schedule_module/js_script';
        $data['appointments_calendar_events'] = $appointments_calendar_events;
        $data['available_shift_dates'] = !empty($available_shift_dates) ? $available_shift_dates : array();
        $data['hide_notification_bar'] = TRUE;
        /* Extra css classes to add in template */
        //$data['extra_wrapper_class'] = 'live-appointment';
        $data['extra_container_class'] = 'calendar-page-outer-con';
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate( $data);
    }

    /*
     * Author - Dave Brown
     * Created - 28 November 2014
     * Description - This is the popup screen for schedule details
     * Request Type - Ajax
     */
    public function schedule_details($appointments_id = NULL)
    {
        if (empty($appointments_id) || $this->input->is_ajax_request() == FALSE)
        {
           show_404();
        }
        $patient_notes_str = NULL;

        /* Fetching all Appointment Details*/
        $conditions = $params = array();
        $conditions['appointments.id'] = $appointments_id;
        $conditions['appointments.status'] = ACTIVE;
        $conditions['appointment_details.user_type'] = USER_PATIENT;

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id'
        );
        $params['join'][] = array(
            'table' => 'prescriptions',
            'conditions' => 'appointments.id = prescriptions.appointment_id',
            'type' => 'left'
        );
        $params['fields'] = 'appointments.id, appointments.start_time,
            appointments.end_time, appointment_details.user_id, appointment_details.user_type, 
            prescriptions.history, prescriptions.examination, prescriptions.diagnosis,
            prescriptions.management, prescriptions.app_id';
        $params['single'] = TRUE;
        $get_apppointment_details = $this->appointment_model->get_all($conditions, $params);

        /*-------- Fetching appointment details ends------------ */

        /*Fetching Patient details from third party database via curl request*/
        $patient_details = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'reviewpatient',
            'patientid' => $get_apppointment_details->user_id,
        )));
        
        /*Fetching patient Notes details from third party database via curl request*/
        $patient_notes = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'getappointmentinformation',
            'patientid' => $get_apppointment_details->user_id,
            'appointmentid' => $get_apppointment_details->id
        )));
        
        if (json_decode($patient_notes) && json_decode($patient_notes) != NULL)
        {
			$patient_notes_str = current(json_decode($patient_notes))->PatientNote;
		}
        

        $data = array();
        $data['patient_details'] = !empty($patient_details) ? json_decode($patient_details) : array();
        $data['patient_notes_str'] = $patient_notes_str;
        $data['get_apppointment_details'] = !empty($get_apppointment_details) ? $get_apppointment_details : array();

        $content = $this->load->view('schedule_module/popup_schedule_details', $data, TRUE);
        exit(json_encode(array('content' => $content)));
    }
    
    /*
     * Author - Dave Brown
     * Created - 08 December 2014
     * Description - This function is used to list appointments of a particular date
     * Used on date click in calendar :-)
     */
    public function list_appointments($date)
    {
        if (empty($date) || $this->input->is_ajax_request() == FALSE)
        {
            return;
        }

        $patient_ids = $patient_details = array();

        $conditions = $params = array();
        $conditions['appointment_details.user_id'] = session_data('id');
        $conditions['appointments.status'] = ACTIVE;
        $conditions['DATE(appointments.start_time)'] = $date;

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id AND appointment_details.user_type = ' . USER_DOCTOR
        );
        $params['join'][] = array(
            'table' => 'appointment_details AS ADP',
            'conditions' => 'appointments.id = ADP.appointment_id AND ADP.user_type = ' . USER_PATIENT
        );
        $params['fields'] = 'appointments.id, appointments.start_time, appointments.end_time, ADP.user_id AS patient_id';

        $get_all_appointments = $this->appointment_model->get_all($conditions, $params);

        if (!empty($get_all_appointments))
        {
            foreach ($get_all_appointments as $val)
            {
                $patient_ids[] = $val->patient_id;
            }

            /*Fetching Patient details from third party database via curl request*/
            $patient_details = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                'action' => 'getpatientlist',
                'patientid' => $patient_ids,
            )));
            $patient_details = combine_keys_values(json_decode($patient_details, TRUE));
        }

        $content = $this->load->view('schedule_module/right_schedule', array(
                'get_all_appointments' => $get_all_appointments,
                'selected_date' => $date,
                'patient_details' => $patient_details,
            ), TRUE);

        exit(json_encode($content));
    }
}
