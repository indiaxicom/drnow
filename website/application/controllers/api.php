<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   8 July, 2014
 * Last
 * Decription   -   Api for web services.
 * */
require(APPPATH . 'libraries/OpenTok/vendor/autoload.php');
use OpenTok\OpenTok;
use OpenTok\Session;
use OpenTok\MediaMode;
use OpenTok\Role;
 
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->post_api_data = file_get_contents('php://input');
        $this->api_data = urldecode($this->post_api_data);
        $this->api_data = json_decode($this->api_data);
        $this->opentok = new OpenTok($this->config->item('tokbox_api_key'), $this->config->item('tokbox_client_secret'));

        /*Saving API Responses*/
        //$this->common_model->save('response', array('response' => $this->post_api_data));

        /*Validating API KEY*/
        if (empty($this->api_data->auth_key) || (!empty($this->api_data->auth_key) && $this->api_data->auth_key != $this->config->item('api_auth_key')) )
        {
            exit (json_encode(array('success' => FALSE, 'message' =>  'Pass valid authentication key')));
        }
    }

    public function index()
    {
        /*
         * $this->api_data->action - this is the function name which we recieve through
         * api coreesponding to which function will be selected
        */
        if (!empty($this->api_data->action))
        {
            $api_func = $this->api_data->action;
            $output = $this->$api_func();
            exit(json_encode($output));
        }
    }

    /*
     * Description  =>  For inserting and updating user records
    */
    private function save_user()
    {
        $table_data = array();

        //Checking for errors
        $required_fields = array(
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                        'email' => 'Email',
                        'password' => 'Password'
                    );
        foreach ($required_fields as $key => $val)
        {
            if (empty($this->api_data->$key))
            {
                return array('error' => $val . ' should not be empty');
            }
        }
        /*Check if email exists or not existing user*/
        $check_existing_user = $this->users_model->check_existing_user($this->api_data->email, USER_PATIENT);
        
        if ($check_existing_user)
        {
            return array('error' => $this->api_data->email . ' :  Email Already registered');
        }

        $table_data['first_name'] = trim($this->api_data->first_name);
        $table_data['last_name'] = trim($this->api_data->last_name);
        $table_data['user_type'] = USER_PATIENT;
        $table_data['email'] = trim($this->api_data->email);
        $table_data['password'] = md5($this->api_data->password);
        $table_data['status'] = ACTIVE;
        $user_id = $this->users_model->save($table_data);

        return array('success' => 'User registered successfully', 'user_id' => $user_id);
    }
    /*-------------------------save_user Ends-------------------------*/




    /*
     * Description  =>  Authenticating patients
     * Author       =>  Dave Brown
     * Created      =>  16 November
     * Input        =>  email, password
    */
    private function authenticate_user()
    {
        //Checking for errors
        $required_feilds = array(
                        'email'         =>  'Email',
                        'password'      =>  'Password'
                    );
        foreach ($required_feilds as $key => $val)
        {
            if (empty($this->api_data->$key))
            {
                return array('error' => $val . ' should not be empty');
            }
        }
        $this->api_data->email = trim($this->api_data->email);
        $this->api_data->password = md5($this->api_data->password);
        $user_details = $this->users_model->authenticate_user($this->api_data->email, $this->api_data->password, USER_PATIENT);

        if (!empty($user_details))
        {
            $details = array(
                'success'      => 'Login Successfull',
                'user_details' =>   array (
                    'user_id'       => $user_details->id,
                    'first_name'    => $user_details->first_name,
                    'last_name'     => $user_details->last_name,
                    'email'         => $user_details->email,
                    'password'      => $user_details->password
                )
            );
            return $details;
        }
        else
        {
            return array('error' =>  'Wrong Email or password');
        }
    }
    /*---------------------authenticate_user Ends----------------------*/



    /*
     * Description  =>  Get User Details
     * Author       =>  Dave Brown
     * Created      =>  16 November
     * Input        =>  user_id
    */
    private function get_user_details()
    {
        //Checking for errors
        $required_feilds = array('user_id'  =>  'User Id');

        foreach ($required_feilds as $key => $val)
        {
            if (empty($this->api_data->$key))
            {
                return array('error' => $val . ' should not be empty');
            }
        }
        $conditions = $params = array();
        $conditions['id']  = $this->api_data->user_id;
        $conditions['status'] = ACTIVE;
        $conditions['user_type'] = USER_PATIENT;
        $params['single'] = TRUE;
        $user_details = $this->users_model->get_all($conditions, $params);

        if (!empty($user_details))
        {
            $details = array(
                'success'      => 'Record Fetched',
                'user_details' =>   array (
                    'user_id'       => $user_details->id,
                    'first_name'    => $user_details->first_name,
                    'last_name'     => $user_details->last_name,
                    'email'         => $user_details->email,
                    'password'      => $user_details->password
                )
            );
            return $details;
        }
        else
        {
            return array('error' =>  'No details available corresponding to this user id');
        }
    }
    /*---------------------get_user_details Ends----------------------*/


    /*
     * Description  =>  Appointment Scheduling
     * Author       =>  Dave Brown
     * Created      =>  16 November 2014
    */
    private function get_appointment_tokens()
    {
        $this->load->model('appointment_model');

        //Checking for errors
        $required_fields = array(
            'appointment_id' => 'Appointment Id',
            'doctor_id' => 'Doctor Id',
            'patient_id' => 'Patient Id',
            'start_time' => 'Start Time',
            'end_time' => 'End Time'
        );

        /* Fetch doctor's Original Id */
        $this->api_data->doctor_id = $this->users_model->get_original_doctor_id($this->api_data->doctor_id);

        foreach ($required_fields as $key => $val)
        {
            if (empty($this->api_data->$key))
            {
                return array('error' => $val . ' should not be empty');
            }
        }

        /*Check If Appointment Already Exists in our database*/
        $conditions = $params = array();
        $conditions['appointments.id'] = $this->api_data->appointment_id;
        $conditions['appointments.status'] = ACTIVE;

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id'
        );
        $params['fields'] = 'appointments.id, appointments.start_time, appointments.end_time,
            appointments.tokbox_session_key, appointment_details.token, appointment_details.user_type,
            appointment_details.user_id';
        $params['return_field'] = 'user_type';
        $get_apppointment_details = $this->appointment_model->get_all($conditions, $params);
        /*Checking Ends*/

        /*--Returning necassary details if appointmrnt already exists---*/
        if (!empty($get_apppointment_details))
        {
            $details = array(
                'error'      => '1',
                'success'      => 'Appointment Already exists.',
                'details' =>   array (
                    'appointment_id'    => (string)$this->api_data->appointment_id,
                    'tokbox_session_key'=> current($get_apppointment_details)->tokbox_session_key,
                    'doctor_id'         => $get_apppointment_details[USER_DOCTOR]->user_id,
                    'doctor_token'      => $get_apppointment_details[USER_DOCTOR]->token,
                    'patient_id'        => $get_apppointment_details[USER_PATIENT]->user_id,
                    'patient_token'     => $get_apppointment_details[USER_PATIENT]->token,
                    'start_time'        => current($get_apppointment_details)->start_time,    
                    'end_time'          => current($get_apppointment_details)->end_time,    
                )
            );
            return $details;
        }

        /*Fetching Doctors details and session key if previous session id is present or not*/
        $doc_details = $this->users_model->get_user_details($this->api_data->doctor_id);
        $doc_details = current($doc_details);

        if (empty($doc_details) ||  (!empty($doc_details) && empty($doc_details['tokbox_session_key'])))
        {
            /*Creating Opentok Session*/
            $opentok_session_key = $this->opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ))->getSessionId();

            $table_data = array();
            $table_data['user_id'] = $this->api_data->doctor_id;
            $table_data['key'] = 'tokbox_session_key';
            $table_data['value'] = $opentok_session_key;
            $this->users_model->save_user_details($table_data, 'insert');
        }
        else
        {
            $opentok_session_key = $doc_details['tokbox_session_key'];
        }
        /*----------------Fetching session key ends-------------------*/

        $table_data = array();
        $table_data['id'] = $this->api_data->appointment_id;
        $table_data['start_time'] = $this->api_data->start_time;
        $table_data['end_time'] = $this->api_data->end_time;
        $table_data['tokbox_session_key'] = $opentok_session_key;
        $table_data['status'] = ACTIVE;

        /*Creating Appointment in the database*/
        try 
        {
            $this->appointment_model->save($table_data, 'save');
        } 
        catch (Exception $e)
        {
            return array('error' => $e->getMessage());
        } 


        /*Creating token for doctor from opental API*/
        $doc_token = $this->generate_tokbox_token(array('user_role' => 'MODERATOR', "expireTime" => (time() + 30 * 3600), 'session_key' => $opentok_session_key));

        /*Now Saving Appointment details to database for doctor */
        $table_data = array();
        $table_data[] = array (
            'appointment_id' => $this->api_data->appointment_id,
            'user_id' => $this->api_data->doctor_id,
            'user_type' => USER_DOCTOR,
            'token' => $doc_token,
            'token_created' => convert_to_sql_time(time()),
            'token_validity' => (30 * 24),
        );

        /*Creating token for patient*/
        $pat_token = $this->generate_tokbox_token(array('user_role' => 'PUBLISHER', "expireTime" => (time() + 7 * 3600), 'session_key' => $opentok_session_key));
        $table_data[] = array (
            'appointment_id' => $this->api_data->appointment_id,
            'user_id' => $this->api_data->patient_id,
            'user_type' => USER_PATIENT,
            'token' => $pat_token,
            'token_created' => convert_to_sql_time(time()),
            'token_validity' => (7 * 24),
        );
        
        /* Saving tokens to database */
        $this->appointment_model->save_appointment_details($table_data, TRUE);

        /* Returning data */
        $details = array(
            'success'      => 'Appointment Created Successfully',
            'details' =>   array (
                'appointment_id'    => (string)$this->api_data->appointment_id,
                'tokbox_session_key'=> $opentok_session_key,
                'patient_id'        => $this->api_data->patient_id,
                'patient_token'     => $pat_token,
                'start_time'        => $this->api_data->start_time,    
                'end_time'          => $this->api_data->end_time,    
            )
        );
        return $details;
    }

    /*
     * Description  =>  Generate tokens at the time of appointment
     * Author       =>  Dave Brown
     * Created      =>  16 November 2014
    */
    private function generate_tokbox_token($params)
    {
        $user_role = $params['user_role'];
        $expireTime = $params['expireTime'];
        $session_key = $params['session_key']; 

        /* Generating Token and updating records */
        $properties = array(
            'role' => $user_role == 'MODERATOR' ? Role::MODERATOR : Role::PUBLISHER,
            'expireTime' => $expireTime
        );

        return $this->opentok->generateToken($session_key, $properties);
    }
}
