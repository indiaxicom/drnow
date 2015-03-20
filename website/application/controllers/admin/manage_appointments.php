<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   7 July, 2014
 * Last 
 * Decription   -   Business Categories Management Controller for Admin.
 * */
class Manage_Appointments extends CI_Controller 
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
        $this->load->model('appointment_model');
        $this->current_module = $this->uri->segment(2);
    }

    public function index() 
    {
        $page_data = NULL;

        $conditions = $params = array();
        $params['cnt'] = TRUE;
        $params['return_field_2d'] = 'id';
        $params['return_field_1d'] = 'user_type';
        $params['join'][] = array('table' => 'appointment_details', 'conditions' => 'appointment_details.appointment_id = appointments.id');
        $params['join'][] = array('table' => 'users', 'conditions' => 'users.id = appointment_details.user_id', 'left');
        $params['fields'] = 'appointments.*, appointment_details.user_id, appointment_details.token, users.first_name, users.last_name, users.user_type';
        $numrows = $this->appointment_model->get_all($conditions, $params); //GETTING TOTAL ROWS FOR PAGINATION
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
        $get_all_appointments = $this->appointment_model->get_all($conditions, $params);

        /* Page Variables are the variables that will be used in differnt views */ 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_appointments'] = $get_all_appointments;
        $page_variables['container']['page_data'] = $page_data;
        $data['page_view']['container'] = 'admin/manage_appointments_module/listing';

        //Variables For Header
        $page_variables['header'] = array();
        $data['page_view']['header'] = NULL;

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
            $this->appointment_model->change_status($data);
        }
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1002]);
        redirect(base_url() . 'admin/' . $this->current_module);
    }
}

