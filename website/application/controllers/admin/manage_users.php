<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   7 July, 2014
 * Last 
 * Decription   -   Users Management Controller for Admin.
 * */
class Manage_Users extends CI_Controller 
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
        $this->current_module = $this->uri->segment(2);
        $this->load->model(array('Email_Templates_Model'));
        
        if ('manage_doctors' == $this->current_module)
        {
            $this->user_type = USER_DOCTOR;
            $this->user_text = 'Doctor';
        }
        else
        {
            $this->user_type = USER_PATIENT;
            $this->user_text = 'User';
        }
    }

    public function index() 
    {
        $page_data = NULL;

        $conditions = $params = array();
        $conditions['user_type'] = $this->user_type;
        $params['cnt'] = TRUE;
        $numrows = $this->users_model->get_all($conditions, $params); //GETTING TOTAL ROWS FOR PAGINATION
        $config['base_url'] = base_url() . 'admin/' . $this->current_module;
        $config['total_rows'] = $numrows;
        $config['per_page'] = 20; //No of records per page
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page_data = $this->pagination->create_links();
        $page_num = $this->uri->segment(3);
        $params['page'] = $this->uri->segment(3) != NULL ? $this->uri->segment(3) + 1 : 1;
        $params['show'] = $config['per_page'];
        unset($params['cnt']);
        $get_all_doctors = $this->users_model->get_all($conditions, $params);

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_doctors'] = $get_all_doctors;
        $page_variables['container']['page_data'] = $page_data;
        $data['page_view']['container'] = 'admin/manage_users_module/listing';

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
        $page_view = $this->user_type == USER_DOCTOR ? 'add_edit_doctor' : 'add_edit';
        
        $get_all_doctors = $user_info = NULL;

        if ($this->input->post('submit'))
        {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
            $this->form_validation->set_rules('gender', 'Gender', 'required|trim');

            $password = post_data('password');

            if (empty($id))
            {
                $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|callback_check_unique[' . post_data('user_type') . ']');
            }

            $this->form_validation->set_rules('password', 'Password', 'xss_clean|matches[confirm_password]|md5');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'xss_clean|matches[confirm_password]|md5');
            
            if ($this->form_validation->run() == TRUE)
            {
                $table_data = array();
                $edit = FALSE;

                if (post_data('id') != NULL)
                {
                    $table_data['id'] = post_data('id');
                    $edit = TRUE;
                }

                /* Saving to third Partie's database via curl */
                if ($this->user_type == USER_DOCTOR)
                {
                    if ($edit == FALSE)
                    {
                        
                        $fields = array(
                            'action' => 'registernewdoctor',
                            'doctortitle' => 'Dr.',
                            'doctorforename' => post_data('first_name'),
                            'doctorsurname' => post_data('last_name'),
                            'doctoremail' => post_data('email'),
                            'doctorpassword' => post_data('password'),
                            'doctorsex' => post_data('gender')
                        );
                        
                        if (post_data('extra_details')['salutation'] != NULL)
                        {
                            $fields['doctorsalutation'] = post_data('extra_details')['salutation'];
                        }
                        
                        if (post_data('extra_details')['gmc_no'] != NULL)
                        {
                            $fields['doctorgovbody'] = post_data('extra_details')['gmc_no'];
                        }
                        
                        if (post_data('extra_details')['bio'] != NULL)
                        {
                            $fields['doctorbio'] = post_data('extra_details')['bio'];
                        }

                        $response = post_curl_request(THIRD_PARTY_API_URL, json_encode($fields));
                        
                        if (json_decode($response))
                        {
                            $table_data['app_user_id'] = current(json_decode($response))->doctorid;

                            /* Sending mail to doctor */
                            $email_template = $this->Email_Templates_Model->get_template_by_type(DOCTOR_NEW_REGISTRATION);

                            if (!empty($email_template))
                            {
                                $to = post_data('email');
                                $subject = $email_template->subject;
                                $from = $email_template->from_email;
                                $from_name = $email_template->from_name;
                                $body = htmlspecialchars_decode($email_template->body);
                                $body = str_replace(array('{NAME}', '{EMAIL}', '{PASSWORD}', '{ReturnUrl}'),
                                    array(post_data('first_name'), post_data('email'), $password, base_url('login')), $body);
                                send_externel_mail ($to, $subject, $body, $from, $from_name);
                            }
                        }
                    }
                    else
                    {
                        $fields = array(
                            array('fieldname' => 'doctortitle', 'newvalue' => 'Dr.'),
                            array('fieldname' => 'doctorforename', 'newvalue' => post_data('last_name')),
                            array('fieldname' => 'doctorsurname', 'newvalue' => post_data('first_name')),
                            array('fieldname' => 'doctoremail', 'newvalue' => post_data('email')),
                            array('fieldname' => 'doctorsex', 'newvalue' => post_data('gender')),
                        );

                        if (post_data('extra_details')['salutation'] != NULL)
                        {
                            $fields[] = array('fieldname' => 'doctorsalutation', 'newvalue' => post_data('extra_details')['salutation']);
                        }
                        
                        if (post_data('extra_details')['gmc_no'] != NULL)
                        {
                            $fields[] = array('fieldname' => 'doctorgovbody', 'newvalue' => post_data('extra_details')['gmc_no']);
                        }
                        
                        if (post_data('extra_details')['bio'] != NULL)
                        {
                            $fields[] = array('fieldname' => 'doctorbio', 'newvalue' => post_data('extra_details')['bio']);
                        }

                        $response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                            'action' => 'updatemultipledoctorfield',
                            'doctorid' => post_data('app_user_id'),
                            'fields' => $fields
                        )));
                    }
                }
                /*----------Saving Ends ------------------*/

                $table_data['first_name'] = post_data('first_name');
                $table_data['last_name'] = post_data('last_name');
                $table_data['email'] = post_data('email');
                $table_data['gender'] = post_data('gender');

                if (post_data('password') != NULL)
                {
                    $table_data['password'] = post_data('password');
                }
                $table_data['status'] = post_data('status');
                $table_data['user_type'] = $this->user_type;
                $user_id = $this->users_model->save($table_data);

                /* Saving Users Extra Details */
                if (post_data('extra_details') != NULL)
                {
                    $table_data = array();
                    /* First delete details */
                    $this->users_model->delete_user_details($user_id, array_keys(post_data('extra_details')));
                    
                    foreach (post_data('extra_details') as $key => $val)
                    {
                        $table_data[] = array('user_id' => $user_id, 'key' => $key, 'value' => $val);
                    }

                    $this->users_model->save_user_details($table_data, 'insert_batch');
                }
                $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1001]);
                redirect(base_url() . 'admin/' . $this->current_module);
            }
        }

        if (!empty($id))
        {
            $conditions = $params = array();
            $conditions['users.id'] = $id;
            $get_all_doctors = $this->users_model->get_all($conditions, $params);
            
            /*Getting Extra Info*/
            $user_info = $this->users_model->get_user_details(current($get_all_doctors)->id);
        }

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_doctors'] = $get_all_doctors;
        $page_variables['container']['user_info'] = $user_info;
        $data['page_view']['container'] = 'admin/manage_users_module/' . $page_view;

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
            $this->users_model->change_status($data);
        }
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1002]);
        redirect(base_url() . 'admin/' . $this->current_module);
    }

    public function check_unique ($email, $user_type)
    {
        $this->form_validation->set_message('check_unique', 'The %s email already exists');
        $result = $this->users_model->check_existing_user($email, $user_type);
        return !$result;
    }
}

