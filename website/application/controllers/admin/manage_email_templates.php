<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 Nov, 2014
 * Last 
 * Decription   -   Email Templates Management Controller for Admin.
 * */
class Manage_Email_Templates extends CI_Controller 
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
        $this->load->model('email_templates_model');
        $this->current_module = $this->uri->segment(2);
    }

    public function index() 
    {
        $page_data = NULL;

        $conditions = $params = array();
        $params['cnt'] = TRUE;
        $numrows = $this->email_templates_model->get_all($conditions, $params); /*GETTING TOTAL ROWS FOR PAGINATION*/
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
        $get_all_records = $this->email_templates_model->get_all($conditions, $params);

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_records'] = $get_all_records;
        $page_variables['container']['page_data'] = $page_data;
        $data['page_view']['container'] = 'admin/manage_email_templates_module/listing';

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
        $this->load->library('ckeditor'); // Add CK editor library
        $get_all_records = NULL;

        if ($this->input->post('submit'))
        {
            $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
            $this->form_validation->set_rules('body', 'Body', 'required|trim|htmlspecialchars');
            
            if ($this->form_validation->run() == TRUE)
            {
                $table_data = array();

                if (post_data('id') != NULL)
                {
                    $table_data['id'] = post_data('id');
                }

                $table_data['template_type'] = post_data('template_type');
                $table_data['subject'] = post_data('subject');
                $table_data['body'] = post_data('body');
                $table_data['from_name'] = post_data('from_name');
                $table_data['from_email'] = post_data('from_email');
                $table_data['status'] = post_data('status');
                $this->email_templates_model->save($table_data);
                
                $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1001]);
                redirect(base_url() . 'admin/' . $this->current_module);
            }
        }

        if (!empty($id))
        {
            $conditions = $params = array();
            $conditions['email_templates.id'] = $id;
            $get_all_records = $this->email_templates_model->get_all($conditions, $params);
        }

        //Page Variables are the variables that will be used in differnt views 
        $page_variables['container']['current_module'] = $this->current_module;
        $page_variables['container']['get_all_records'] = $get_all_records;
        $data['page_view']['container'] = 'admin/manage_email_templates_module/add_edit';

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
            $this->email_templates_model->change_status($data);
        }
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1002]);
        redirect(base_url() . 'admin/' . $this->current_module);
    }
}

