<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   13 Nov, 2014
 * Decription   -   Admin dashboard, login, logout
 * */

class Operations extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->messages = $this->config->item('messagesArr');        
    }

    public function index() {
        
    }

    public function login() {

        if (is_doctor())
        {
            redirect(base_url('profile'));
        }

        if (post_data('login')) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

            if ($this->form_validation->run() == TRUE) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $user_details = $this->users_model->authenticate_user($email, $password, USER_DOCTOR);

                if (!empty($user_details)) {
                    //Register session after succsessful authentication
                    $this->register_session($user_details);
                    redirect(base_url('schedule'));
                } else {
                    $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1003]);
                    redirect(base_url() . 'login');
                }
            }
        }        
        $this->load->view('login_module/login');
    }

    private function register_session($user_details) {
        if (empty($user_details)) {
            return;
        }
        $user_details = (array) $user_details;
        $this->session->set_userdata($user_details);
    }

    /*
     * Description - To Logout from Doctor's Dashboard
     * Author : Dave Brown
     * Created : 24 Nov 2014
     */
    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1004]);
        redirect(base_url() . 'login');
    }
    
    /*
     * Description - Changes the password request from Doctor's Dashboard
     * Author : Dave Brown
     * Created : 24 Nov 2014
     */
    public function change_password()
    {
        /*Check User is doctor or not*/
        if (is_doctor() == FALSE)
        {
            return;
        }
        
        if (post_data('password') != NULL)
        {
            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|md5|xss_clean');
            $this->form_validation->set_rules('password', 'New Password', 'trim|required|matches[confirm_password]|md5|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Confirm New Password', 'trim|required|md5|xss_clean');

            if ($this->form_validation->run() == TRUE)
            {
                if (session_data('password') != post_data('old_password'))
                {
                    $status = array('status' => '0', 'message' => $this->messages[1007]);
                    exit(json_encode($status));
                }

                $this->users_model->update_password(session_data('email'), post_data('password'), session_data('password'));
                $this->register_session(array('password' => post_data('password')));

                /* Now updating Third Party's Db on update*/
                post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                    'action' => 'updatemultiplepdoctorfield',
                    'doctorid' => session_data('app_user_id'),
                    'fields' => array(
                        $this->config->item('docFieldArr')['password'] => post_data('password')
                    )
                )));
                
                $status = array('status' => '1', 'message' => $this->messages[1006]);
                exit(json_encode($status));
            }
            else 
            {
                $status = array('status' => '0', 'message' => validation_errors());
                exit(json_encode($status));
            }
        }
    }
    
    /*
     * Function used to upload profile image on frontend and dashboard cms
     */
    public function profile_image_upload ()
    {
        if (empty($_FILES) && empty($_FILES['userfile']['name']))
        {
            return;
        }

        if (post_data('image_type') == SIGNATURE_IMAGE)
        {
            $path = SIGNATURE_IMAGE_PATH;
            $min_w = 100;
            $min_h = 50;
            $resize_w = 150;
            $resize_h = 150;
        }
        else
        {
            $path = PROFILE_IMAGE_PATH;
            $min_w = 150;
            $min_h = 150;
            $resize_w = 300;
            $resize_h = 300;
        }
        $_FILES['userfile']['name'] = sanitize_file_name($_FILES['userfile']['name']);

        $image_data = upload_file(DIRPATH . $path, 'jpg|png|jpeg|gif',  $min_w,  $min_h);

        if (!empty($image_data['file_name']))
        {
            //First resize the image
            $h = $resize_h;
            $w = $resize_w;
            resize_image($image_data['full_path'], $w, $h);

            $this->session->set_userdata('edit_image', $image_data['file_name']);
            $this->session->set_userdata('image_type', post_data('image_type'));
            exit(json_encode(array('image_name' => $image_data['file_name'], 'success' => TRUE)));
        }
        else
        {
            exit(json_encode(array('success' => FALSE, 'message' => $image_data)));
        }
    }
    
    public function load_image_popup ($image_name = NULL)
    {
        if (empty($image_name))
        {
            exit(json_encode(array('content' => '0')));
        }
        if (session_data('image_type') == SIGNATURE_IMAGE)
        {
            $path = SIGNATURE_IMAGE_PATH;
            $width = 150;
            $height = 100;
        }
        else
        {
            $width = 150;
            $height = 150;
            $path = PROFILE_IMAGE_PATH;
        }
        $content = $this->load->view('profile/doc_image_popup', array(
            'image_name' => $image_name,
            'path' => $path,
            'width' => $width,
            'height' => $height,
            'image_type' => session_data('image_type')
        ), TRUE);
        exit(json_encode(array('content' => $content)));
    }

    public function crop_image ($image_name = NULL)
    {
        $this->load->model('files_model');
        $w = (int)post_data('x_axis_2') - (int)post_data('x_axis_1');
        $h = (int)post_data('y_axis_2') - (int)post_data('y_axis_1');

        if (session_data('image_type') == SIGNATURE_IMAGE)
        {
            $path = SIGNATURE_IMAGE_PATH;
        }
        else
        {
            $path = PROFILE_IMAGE_PATH;
        }
        $source = DIRPATH . $path . session_data('edit_image');
        $final_img_name = session_data('edit_image');
        $dest = DIRPATH . $path . 'cropped/' . $final_img_name;
        
        $x = post_data('x_axis_1');
        $y = post_data('y_axis_1');

        crop_image($source, $dest, $w, $h, $x, $y);

        $table_data = array();
        $table_data['name'] = $final_img_name;
        $table_data['section'] = session_data('image_type');

        if (session_data('id') != NULL)
        {
            $table_data['section_id'] = $table_data['user_id'] = session_data('id');
            $this->files_model->delete_file_by_section($table_data['section'], $table_data['section_id'], session_data('id'));
            $file_id = $this->files_model->save_file($table_data);

            if (session_data('image_type') == SIGNATURE_IMAGE)
            {
                $this->session->set_userdata('signature_image', $final_img_name);
            }
            else
            {
                $this->session->set_userdata('profile_image', $final_img_name);

                /* Updating Third Party's Db on update*/
                $response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                    'action' => 'updatemultipledoctorfield',
                    'doctorid' => session_data('app_user_id'),
                    'fields' => array (
                        array('fieldname' => $this->config->item('docFieldArr')['doctoravatar'], 'newvalue' => base_url() . PROFILE_IMAGE_PATH . $final_img_name)
                    )
                )));

                /*---------- Updating Third party's db ends-------- */
            }

        }
        redirect(base_url());
    }

    /**/
    public function get_next_appointment()
    {
        /*The sleep is required to get the the next appointment
         * and not the ongoing one */
        sleep(2);
        
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
        $appointment = $this->appointment_model->get_all($conditions, $params);

        /* This is to convert next appointment in json.*/
        if (!empty($appointment))
        {
            exit(json_encode(array('start_time' => convert_from_sql_time('d M Y H:i:s', $appointment->start_time)))); 
        }
        else
        {
            exit(json_encode(array('start_time' => '0'))); 
        }
    }
    
    /*
     * Author - Dave Brown
     * Created - 28 November 2014
     * Description - This is to save image created from tokbox
     * Request Type - Ajax
     */
    public function save_image_from_data()
    {
		if ($this->input->is_ajax_request() == FALSE || post_data('image_data') == NULL)
		{
			return FALSE;
		}
		
		$image_data = post_data('image_data');
		$appointment_id = post_data('appointment_id');
		$appointment_directory = APPOINTMENT_IMAGE_PATH . create_folder_name($appointment_id);
		
		if (file_exists($appointment_directory) == FALSE)
		{
			mkdir($appointment_directory, 0777);
		}
		
		/*Saving and uploading image to server*/
		$image_name = time() . '.png';
		$image_path = DIRPATH . $appointment_directory . '/' . $image_name;
		
		if (file_put_contents($image_path, base64_decode($image_data)))
		{
			$this->load->model('files_model');
			$file_id = $this->files_model->save_file( array(
				'name' => $image_name,
				'user_id' => session_data('id'),
				'section' => APPOINTMENT_IMAGE,
				'section_id' => $appointment_id
			));
			
			/* Updating Thirdparty database */
			post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
				"action" => "addsnapshot",
				"passkey"  => 'da051368702a11e4beba745f7e594550',
				"base64img" => $image_data,
				"filetype" => 'png',
				"patientid" => post_data('patient_id'),
			)));
			exit(json_encode(array('url' => base_url($appointment_directory . '/' . $image_name), 'id' => $file_id, 'success' => TRUE)));
		}
		
		exit(json_encode(array('success' => FALSE)));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
