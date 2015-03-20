<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   10 July, 2014
 * Last 
 * Decription   -   Locations Controller. used as event management for Store owners 
 * */
require(APPPATH . 'libraries/OpenTok/vendor/autoload.php');
use OpenTok\OpenTok;
use OpenTok\Session;

class Appointment extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        if (is_admin() == FALSE)
        {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'admin/login');
        }
        $this->current_module = 'appointment';
    }

    public function index() 
    {
        //$opentok = new OpenTok(45080892, '7446f822a2b931312249e22b386b18ccc81f1875');
        $opentok = new OpenTok($this->config->item('tokbox_api_key'), $this->config->item('tokbox_client_secret'));

        $sessionId = NULL;

        if (!empty($_POST['token_id']) && !empty($_POST['session_id']))
        {
            $token = $_POST['token_id'];
            $sessionId = $_POST['session_id'];
            $this->session->set_flashdata('tokbox_session_id', $sessionId);
            $this->session->set_flashdata('tokbox_token', $token);
            $this->session->set_flashdata('name', $_POST['name']);
            redirect(current_url());
        }


        $data['page_view']['container'] = 'admin/conference_module/conference';

        //Variables For Header
        $page_variables['header'] = array();
        $data['page_view']['header'] = NULL;

        $data['title']  = 'Add New Event';
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        $this->load->view('admin/common/template', $data);
    }

    public function create_tok_box_session($opentok)
    {
        return $opentok->createSession();
    }

    public function create_token($opentok, $session_id)
    {
        if (empty($opentok) || empty($session_id))
        {
            return;
        }
        return $opentok->generateToken($sessionId);
    }
}

