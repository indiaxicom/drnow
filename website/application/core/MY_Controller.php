<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By 	- 	Dave Brown
 * Date			-	9 July, 2014
 * Decription	-	Dshboard, login, logout
 * */
 
class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('appointment_model');
    }

    protected function get_next_appointment()
    {
        /* Fetching all Appointment Details*/
        $conditions = $params = array();
        $conditions['appointment_details.user_id'] = session_data('id');
        $conditions['appointments.status'] = ACTIVE;
        $conditions['appointments.start_time >= '] = date('Y-m-d H:i:s');

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id AND appointment_details.user_type = ' . USER_DOCTOR
        );
        $params['fields'] = 'appointments.id, appointments.start_time';
        $params['single'] = TRUE;
        $params['order_by'] = 'appointments.start_time ASC';
        $params['limit'] = '1';
        return $this->appointment_model->get_all($conditions, $params);
    }

    /* Current Appointment */
    protected function get_current_appointment()
    {
        /* Fetching all Appointment Details*/
        $conditions = $params = array();
        $conditions['appointment_details.user_id'] = session_data('id');
        $conditions['appointments.status'] = ACTIVE;
        $conditions['appointments.end_time > '] = date('Y-m-d H:i:s');

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id AND appointment_details.user_type = ' . USER_DOCTOR
        );
        $params['fields'] = 'appointments.id, appointments.start_time';
        $params['single'] = TRUE;
        $params['order_by'] = 'appointments.start_time ASC';
        $params['limit'] = '1';
        return $this->appointment_model->get_all($conditions, $params);
    }

    protected function fronttemplate($data)
    {
        $get_next_appointment = $this->get_next_appointment();
        $get_current_appointment = $this->get_current_appointment();
        $data['get_next_appointment'] = $get_next_appointment != NULL ? $get_next_appointment : array();
        $data['get_current_appointment'] = $get_current_appointment != NULL ? $get_current_appointment : array();

        $this->load->view('common/template', $data);
    }

}
