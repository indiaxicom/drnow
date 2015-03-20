<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Sumit Kohli
 * Date         -   7 July, 2014
 * Last 
 * Decription   -   Business Categories Management Controller for Admin.
 * */
class Manage_Doctors extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        if (is_admin() == FALSE)
        {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1003]);
            redirect(base_url() . 'admin/login');
        }
        $this->load->library('pagination');
        $this->current_module = $this->uri->segment(2);
    }

    public function index() 
    {
        $page_data = NULL;

        $conditions = $params = array();
        $conditions['user_type'] = USER_DOCTOR;
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
        $data['page_view']['container'] = 'admin/manage_doctors_module/doctors_listing';

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
        $get_all_doctors = NULL;

        if ($this->input->post('submit'))
        {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');

            if (empty($id))
            {
                $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|is_unique[users.email]');
            }

            $this->form_validation->set_rules('password', 'Password', 'xss_clean|matches[confirm_password]|md5');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'xss_clean|matches[confirm_password]|md5');
            
            if ($this->form_validation->run() == TRUE)
            {
                $table_data = array();

                if (post_data('id') != NULL)
                {
                    $table_data['id'] = post_data('id');
                }

                $table_data['first_name'] = post_data('first_name');
                $table_data['last_name'] = post_data('last_name');
                $table_data['email'] = post_data('email');

                if (post_data('password') != NULL)
                {
                    $table_data['password'] = post_data('password');
                }
                $table_data['status'] = post_data('status');
                $table_data['user_type'] = USER_DOCTOR;
                $this->users_model->save($table_data);
                
                $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1001]);
                redirect(base_url() . 'admin/' . $this->current_module);
            }
        }

        if (!empty($id))
        {
            $conditions = $params = array();
            $conditions['users.id'] = $id;
            $get_all_doctors = $this->users_model->get_all($conditions, $params);
        }

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_doctors'] = $get_all_doctors;
        $data['page_view']['container'] = 'admin/manage_doctors_module/add_edit';

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
}

