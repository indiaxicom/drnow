<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   24 November, 2014
 * Last 
 * Decription   -   Doctors related functionalities.
 * */

class Doctors extends MY_Controller {

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

    public function profile()
    {
        $get_user_details = $this->users_model->get_user_details(session_data('id'));

        $page_variables['container']['get_user_details'] = $get_user_details;
        $data['page_view']['container'] = 'profile/doc_info';

        //Variables For Header
        $page_variables['right_container'] = array();
        $data['page_view']['right_container'] = 'profile/right_account_settings';

        //Variables For Footer
        $data['title'] = 'Doctor\'s Profile | ' . SITE_NAME;
        $data['js_script'] = 'profile/js_script';
        $data['current_module'] = $this->current_module;
        $data['extra_container_class'] = 'profile-page-outer-con';
        $data['page_variables'] = $page_variables;
        parent::fronttemplate($data);
    }

    public function update_profile_detail()
    {
        if (post_data('field') == NULL || post_data('field_val') == NULL)
        {
            return;
        }
        $app_response = array();

        /* Updating Third Party's Db on update*/
        if (isset($this->config->item('docFieldArr')[post_data('field')]))
        {
            $app_response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                'action' => 'updatemultipledoctorfield',
                'doctorid' => session_data('app_user_id'),
                'fields' => array(
                    array('fieldname' => $this->config->item('docFieldArr')[post_data('field')], 'newvalue' => post_data('field_val'))
                )
            )));
        }
        /*---------- Updating Third party's db ends-------- */

        if (in_array(post_data('field'), array('gmc_no', 'salutation', 'bio')))
        {
            /* Delete record if already exist */
            $this->users_model->delete_user_details(session_data('id'), array(post_data('field')));

            /*Now save the details*/
            $table_data = array();
            $table_data['user_id'] = session_data('id');
            $table_data['key'] = post_data('field');
            $table_data['value'] = post_data('field_val');
            $this->users_model->save_user_details($table_data, 'insert');
            exit(json_encode($app_response));
        }

        $table_data = array();
        $table_data['id'] = session_data('id');
        $table_data[post_data('field')] = post_data('field_val');
        $this->users_model->save($table_data);
        $this->session->set_userdata(post_data('field'), post_data('field_val'));

        exit(json_encode($app_response));
    }
}
