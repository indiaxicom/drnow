<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   13 Nov, 2014
 * Decription   -   Admin dashboard, login, logout
 * */
 
class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (FALSE === is_admin())
        {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'admin/login');
        }
        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container'] = array();
        $data['page_view']['container'] = 'admin/common/dashboard';

        //Variables For Footer
        $data['page_variables'] = $page_variables;
        $this->load->view('admin/common/template', $data);
    }

    public function login()
    {
        $errors_list = NULL;

        if ($this->input->post('submit'))
        {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

            if ($this->form_validation->run() == TRUE)
            {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $user_details = $this->users_model->authenticate_user($email, $password, USER_ADMIN);

                if (!empty($user_details)) 
                {
                    //Register session after succsessful authentication
                    $this->register_session($user_details);
                    redirect(base_url() . 'admin');
                } 
                else 
                {
                    $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1003]);
                    redirect(base_url() . 'admin/login');
                }
            }
        }
        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container'] = array();
        $data['page_view']['container'] = 'admin/login_module/login_form';
        
        //Variables For Header
        $page_variables['header'] = array();
        $data['page_view']['header'] = NULL;

        //Variables For Footer
        $data['page_variables'] = $page_variables;
        $this->load->view('admin/common/template', $data);
    }

    private function register_session($user_details)
    {
        if(empty($user_details))
        {
            return;
        }
        $user_details = (array)$user_details;
        $this->session->set_userdata($user_details);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1004]);
        redirect(base_url() . 'admin/login');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
