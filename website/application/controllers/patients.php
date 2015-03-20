<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   1 December, 2014
 * Last 
 * Decription   -   Patients.
 * */

class Patients extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if (is_doctor() == FALSE) {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'login');
        }
        $this->current_module = $this->uri->segment(1);
    }

    public function index() {
       
    }

    public function profile($id)
    {
        if (empty($id))
        {
            show_404();
        }

        /*Fetching Patient details from third party database via curl request*/
        $patient_details = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'reviewpatient',
            'patientid' => $id,
        )));

        $page_variables['container']['patient_details'] = !empty($patient_details) ? current(json_decode($patient_details)) : array();

        $data['page_view']['container'] = 'profile/patient_info';

        //Variables For Page
        $data['title'] = 'Patient\'s Profile | ' . SITE_NAME;
        $data['js_script'] = 'profile/js_script';
        
        /* Extra css classes to add in template */
        $data['extra_container_class'] = 'c-full patient-profile';
        /* -------Ends----- */

        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate($data);
    }

}
