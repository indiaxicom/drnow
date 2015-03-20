<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   25 November, 2014
 * Last 
 * Decription   -   Scheduling functionalities.
 * */

require(APPPATH . 'libraries/OpenTok/vendor/autoload.php');
use OpenTok\OpenTok;
use OpenTok\Session;
use OpenTok\Archive;

class Appointments extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if (is_doctor() == FALSE) {
            $this->session->set_flashdata('flash_message', $this->config->item('messagesArr')[1005]);
            redirect(base_url() . 'login');
        }
        
        $this->current_module = $this->uri->segment(1);
        $this->opentok = new OpenTok($this->config->item('tokbox_api_key'), $this->config->item('tokbox_client_secret'));
    }

    public function index()
    {
        /* Template description */
        $page_variables = array();
        $data['page_view']['container'] = 'appointments_module/no_appointment';

        $data['title'] = 'Doctor\'s Schedule | ' . SITE_NAME;

        /* Extra css classes to add in template */
        $data['extra_wrapper_class'] = 'live-appointment';
        //$data['extra_container_class'] = 'c-full';
        /* -------Ends----- */
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate($data);
        /* Template Description Ends */
    }

    /* Description - Function is to display video conferencing container
     * Created - 25 November 2014
     * Created By - Dave Brown
     */
    public function conference ($appointment_hash = NULL)
    {
        /* - - - - -Avoid page from caching -*/
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');
        /* - - - - -Avoid page from caching Ends - - - - -  - */

        $history_appointments = NULL;

        if (empty($appointment_hash))
        {
            show_404();
        }

        $get_current_appointment = $this->get_current_appointment();

        if (!empty($get_current_appointment) && encryptor('encrypt', $get_current_appointment->id) != $appointment_hash)
        {
            redirect(base_url('appointments/conference/' . encryptor('encrypt', $get_current_appointment->id)));
        }

        /* Fetching all Appointment Details*/
        $conditions = $params = array();
        $conditions['appointments.id'] = encryptor('decrypt', $appointment_hash);
        $conditions['appointments.status'] = ACTIVE;
        $conditions['appointments.end_time >= '] = date('Y-m-d H:i:s');

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id'
        );
        /*Getting prescription Details if exists or not*/
        $params['join'][] = array(
            'table' => 'prescriptions',
            'conditions' => 'prescriptions.appointment_id = appointments.id',
            'type' => 'left'
        );
        $params['fields'] = 'appointments.id, appointments.call_status, 
			appointments.start_time, appointments.end_time,
            appointments.tokbox_session_key, appointment_details.token,
            appointment_details.user_id, appointment_details.user_type, 
            prescriptions.id AS prescription_id, history, examination, 
            diagnosis, management, prescriptions.app_id AS prescription_app_id, 
            outcome_description';
        $params['return_field'] = 'user_type';
        $get_apppointment_details = $this->appointment_model->get_all($conditions, $params);

        /* Fetching appointment details ends */

        if (empty($get_apppointment_details))
        {
            redirect(base_url($this->current_module));
        }

        /* Fetching Previous Appointments of the Patient with doctornow doctors */
        $conditions = $params = array();
        $conditions['appointments.status'] = ACTIVE;
        $conditions['appointments.id <> '] = current($get_apppointment_details)->id;
        $conditions['appointment_details.user_id'] = $get_apppointment_details[USER_PATIENT]->user_id;
        $conditions['appointments.start_time < '] = date('Y-m-d h:i:s');

        $params['join'][] = array(
            'table' => 'appointment_details',
            'conditions' => 'appointments.id = appointment_details.appointment_id AND appointment_details.user_type = ' . USER_PATIENT
        );
        $params['join'][] = array(
            'table' => 'appointment_details AS ADDOC',
            'conditions' => 'appointments.id = ADDOC.appointment_id AND ADDOC.user_type = ' . USER_DOCTOR
        );
        /*Getting doctor Details*/
        $params['join'][] = array(
            'table' => 'users',
            'conditions' => 'users.id = ADDOC.user_id',
        );
        
        $params['fields'] = 'appointments.id, appointments.start_time, users.first_name, users.last_name';
        $history_appointments = $this->appointment_model->get_all($conditions, $params);
        /*-----------------History Appointments Ends------------------*/

        /*Fetching Patient details from third party database via curl request*/
        $patient_details = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'reviewpatient',
            'patientid' => $get_apppointment_details[USER_PATIENT]->user_id,
        )));
                /*Fetching patient Notes details from third party database via curl request*/
        $patient_notes = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'getappointmentinformation',
            'patientid' => $get_apppointment_details[USER_PATIENT]->user_id,
            'appointmentid' => $get_apppointment_details[USER_PATIENT]->id
        )));
		if (json_decode($patient_notes) && json_decode($patient_notes) != NULL)
        {
			$patient_notes_str = current(json_decode($patient_notes))->PatientNote;
		}

        /* Saving tokbox data into session for call, if call is pending */
        if (current($get_apppointment_details)->call_status == CALL_PENDING)
        {
            $tokbox_data = array(
                'tokbox_session_key' => $get_apppointment_details[USER_DOCTOR]->tokbox_session_key,
                'tokbox_token' => $get_apppointment_details[USER_DOCTOR]->token
            );
            $this->session->set_userdata('tokbox_data', $tokbox_data);
        }

        /* Template description */
        $page_variables['container']['patient_notes'] = !empty($patient_notes_str) ? $patient_notes_str : NULL;
        $page_variables['container']['get_apppointment_details'] = $get_apppointment_details;
        $page_variables['container']['appointment_hash'] = $appointment_hash;
        $page_variables['container']['history_appointments'] = $history_appointments;
        $page_variables['container']['patient_details'] = !empty($patient_details) ? current(json_decode($patient_details)) : array();
        $page_variables['container']['patient_id'] = !empty($get_apppointment_details[USER_PATIENT]->user_id) ? $get_apppointment_details[USER_PATIENT]->user_id : 0;
        $data['page_view']['container'] = 'appointments_module/conference';

        $page_variables['right_container']['get_apppointment_details'] = $get_apppointment_details;
        $data['page_view']['right_container'] = 'appointments_module/right_conference';

        //Variables For Footer
        $data['title'] = 'Doctor\'s Schedule | ' . SITE_NAME;

        /* Extra css classes to add in template */
        $data['extra_wrapper_class'] = 'live-appointment';
        //$data['extra_container_class'] = 'c-full';
        /* -------Ends----- */
        $data['js_script'] = 'appointments_module/js_script';
        $data['get_apppointment_details'] = $get_apppointment_details;
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate($data);
        /* Template Description Ends */
    }

    /*
     * Description - Starting a call of conference on pressing start call button
     * Author - Dave Brown
     * Created - 27 Nov 2014
    */
    public function start_call()
    {
        if (post_data('tokbox_session_key') == NULL || post_data('tokbox_token') == NULL)
        {
            exit(json_encode(array('status' => '0')));
        }

        $this->session->set_userdata('tokbox_data', post_data());
        exit(json_encode(array('status' => '1')));
    }

    /*
     * Description - Ending a call of conference on pressing end call button
     * Author - Dave Brown
     * Created - 27 Nov 2014
    */
    public function end_call()
    {
        if (session_data('tokbox_data') == NULL)
        {
            return;
        }

        $this->appointment_model->save(array('call_status' => CALL_COMPLETED, 'id' => post_data('appointment_id')), 'update');
        $this->session->unset_userdata('tokbox_data');
        exit(json_encode(array('status' => '1', )));
    }

    /*
     * Description - Function is called whn call is ended a result page gets open
     * Author - Dave Brown
     * Created - 28 Nov 2014
    */
    public function conclusion ($appointment_hash = NULL)
    {
        $page_data = NULL;

        if (empty($appointment_hash))
        {
            show_404();
        }

        /* Hash coming from url is nothing but an encrypted appointment id */
        $apppointment_id = encryptor('decrypt', $appointment_hash);

        /* Template description */
        $page_variables['container'] = array();
        $data['page_view']['container'] = 'appointments_module/conclusion';

        $page_variables['right_container'] = array();
        $data['page_view']['right_container'] = 'appointments_module/right_conclusion';

        //Variables For Footer
        $data['title'] = 'Doctor\'s Schedule | ' . SITE_NAME;

        /* Extra css classes to add in template */
        $data['extra_wrapper_class'] = 'live-appointment la-conclusn';
        $data['extra_container_class'] = NULL;
        /* -------Ends----- */
        $data['js_script'] = 'appointments_module/js_script';
        $data['current_module'] = $this->current_module;
        $data['page_variables'] = $page_variables;
        parent::fronttemplate($data);
        /* Template Description Ends */
    }

    /*
     * Description - Function is fetch prescriptions from api
     * Author - Dave Brown
     * Created - 11 December 2014
    */
    public function get_prescription ($appointment_hash = NULL)
    {
        $page_data = NULL;
        $prescriptions_arr = array();

        if (get_data('term') == NULL)
        {
            return;
        }
        /*Fetching Patient details from third party database via curl request*/
        $prescriptions = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'getformulary',
            'lookfor' => get_data('term'),
        )));

        $prescriptions = !empty($prescriptions) ? json_decode($prescriptions) : array();

        /*Converting to autocomplete understandable format*/
        if (!empty($prescriptions))
        {
            foreach ($prescriptions as $val)
            {
                $prescriptions_arr[] = array('id' => current($val)->vpid, 'value' => current($val)->nm, 'unit_price' => current($val)->UnitPrice);
            }
        }
        exit(json_encode($prescriptions_arr));
    }

    /*
     * Description - Function is to save notes while the doctor's call is going on
     * Author - Dave Brown
     * Created - 24 December 2014
    */
    public function save_appointment_notes ()
    {
        if (post_data('appointment_id') == NULL)
        {
            return;
        }
        $table_data = array();
        $presc_status = FALSE;
        $app_response = $app_return_response = $app_status = array();

        $table_data['id'] = post_data('id') != NULL ? post_data('id') : 0;
        $table_data['appointment_id'] = post_data('appointment_id');
        $table_data['history'] = post_data('history') != NULL ? post_data('history') : NULL;
        $table_data['examination'] = post_data('examination') != NULL ? post_data('examination') : NULL;
        $table_data['diagnosis'] = post_data('diagnosis') != NULL ? post_data('diagnosis') : NULL;
        $table_data['management'] = post_data('management') != NULL ? post_data('management') : NULL;
        $table_data['medicine_prescription'] = post_data('prescription') != NULL ? json_encode(post_data('prescription')) : NULL;
        $table_data['icd_description'] = post_data('icd_list') != NULL ? json_encode(post_data('icd_list')) : NULL;
        $table_data['repeat_prescription'] = post_data('repeat_prescription') != NULL ? post_data('repeat_prescription') : 0;
		
        /*--------------------Updating third party's API----------------------------------------*/
        if (post_data('create_prescription') == TRUE)
        {
            $prescription = make_prescription_format(post_data('prescription'));

            /*Creating Prescription  at Third Party's database*/
            $app_response = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                "action" => "postcreateprescription",
                "patientid"  => post_data('patient_id'),
                "appointmentid" => post_data('appointment_id'),
                "doctorid" => session_data('app_user_id'),
                "endorsements" => count(post_data('prescription')),
                "patientname" => post_data('PatientForename') . ' ' . post_data('PatientSurname'),
                "deliveryaddress" => post_data('PatientAddress1') . ' ' .
                    post_data('PatientAddress2') . ' ' . post_data('PatientAddress3') . ' ' .
                    post_data('PatientAddress3') . '' .  post_data('PatientAddress4') . ' ' .
                    post_data('PatientAddress5'),
                "DOB" => post_data('PatientDOB'),
                "Age" => post_data('PatientDOB') != NULL ? date('Y') - date('Y', uk_date_to_stamp(post_data('PatientDOB'))) : NULL,
                "telephone" => post_data('PatientMobile'),
                "deliverynote" => "New Prescription",
                "repeatprescription" => post_data('repeat_prescription'),
                "endorsement" => $prescription
            )));

            /* Updating Icd at Third Party's database */
            if (post_data('icd_list') != NULL)
            {
                $app_return_response['icd_response'] = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                    "action" => "postICDtopatient",
                    "patientid"  => post_data('patient_id'),
                    "appointmentid" => post_data('appointment_id'),
                    "icdcodeselected" => post_data('icd_list')['key'],
                    "icdtitleselected" => post_data('icd_list')['value'],
                )));
            }

            /*Creating patient notes at Third Party's database*/
            $app_return_response['patient_notes_response'] = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                "action" => "postpatientnotes",
                "patientid"  => post_data('patient_id'),
                "appointmentid" => post_data('appointment_id'),
                "doctorid" => session_data('app_user_id'),
                "history" => post_data('history'),
                "examination" => post_data('examination'),
                "diagnosis" => post_data('diagnosis'),
                "management" => post_data('management'),
            )));
            
            if (!empty($app_response) && json_decode($app_response))
            {
                $presc_status = TRUE;
                $table_data['app_id'] = json_decode($app_response);

                /* Signing Prescription and updating Third Party's database*/
                $app_return_response['sign_response'] = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
                    "action" => "prescriptionsign",
                    "prescriptionid"  => $table_data['app_id'],
                    "doctorid" => session_data('app_user_id')
                )));

            }
			/*-------------------------Updating third party's Database Ends-------------------------*/
            if (!empty($app_return_response))
            {
				foreach ($app_return_response as $key => $val)
				{
					if (!empty($val) && json_decode($val) && !empty(json_decode($val)->Status) && json_decode($val)->Status == 1)
					{
						$app_status[$key] = TRUE;
					}
					else
					{
						$app_status[$key] = FALSE;
					}
				}
			}
       
        }
        
        $presc_id = $this->appointment_model->save_prescription($table_data);
        
        exit(json_encode(array(
            'presc_id' => $presc_id,
            'presc_status' => $presc_status,
            'app_status' => $app_status,
        )));
    }

    /*
     * Description - Function is to start Archieving of meetings
     * Author - Dave Brown
     * Created - 26 December 2014
    */
    public function start_archieve ()
    {
        if (post_data('appointment_id') == NULL)
        {
            return;
        }

        $archieve_name = post_data('appointment_id') . '-' . date('d_M_Y');
        $archive_result = $this->opentok->startArchive(post_data('tokbox_session_key'), $archieve_name);

        $table_data = array(
            'archieve_id' => $archive_result->id,
            'name' => $archive_result->name,
            'appointment_id' => post_data('appointment_id'),
            'duration' => $archive_result->duration,
            'size' => $archive_result->size,
            'status' => $archive_result->status
        );

        $this->appointment_model->save_archieve($table_data);
        exit(json_encode($table_data));
    }

    /*
     * Description - Function is to stop Archieving of meetings
     * when archieving is complete it automatically gets saved to Amazon S3 bucket.
     * Author - Dave Brown
     * Created - 26 December 2014
    */
    public function stop_archieve ()
    {
        if (post_data('appointment_id') == NULL || post_data('archieve_id') == NULL)
        {
            return;
        }

        $archive_result = $this->opentok->stopArchive(post_data('archieve_id'));

        $table_data = array(
            'archieve_id' => $archive_result->id,
            'duration' => $archive_result->duration,
            'size' => $archive_result->size,
            'status' => $archive_result->status
        );
        $this->appointment_model->save_archieve($table_data, 'update', array(
            'archieve_id' => $table_data['archieve_id'],
            'appointment_id' => post_data('appointment_id'),
        ));
    }

    /*
     * Description - Function is fetch icd list from api
     * Author - Dave Brown
     * Created - 13 February 2015
    */
    public function get_icd_list ()
    {
        $listicdcodes_arr = array();

        if (get_data('term') == NULL)
        {
            return;
        }
        /*Fetching Patient details from third party database via curl request*/
        $listicdcodes = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'listicdcodes',
            'lookfor' => get_data('term'),
        )));

        $listicdcodes = !empty($listicdcodes) ? json_decode($listicdcodes) : array();

        /*Converting to autocomplete understandable format*/
        if (!empty($listicdcodes))
        {
            foreach ($listicdcodes as $val)
            {
                $listicdcodes_arr[] = array('id' => current($val)->ICDCode, 'value' => current($val)->ICDTitle);
            }
        }
        exit(json_encode($listicdcodes_arr));
    }
    
    /*
     * Description - Function is fetch icd list from api
     * Author - Dave Brown
     * Created - 5 March 2015
    */
    public function get_outcome_list ($outcome_type)
    {
        $list_outcome_arr = array();

        /*Fetching Patient details from third party database via curl request*/
        $listouctcome_codes = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
            'action' => 'getoutcomes',
            'outcomemaincode' => $outcome_type,
        )));

        $listouctcome_codes = !empty($listouctcome_codes) ? json_decode($listouctcome_codes) : array();

        /*Converting to autocomplete understandable format*/
        if (!empty($listouctcome_codes))
        {
            foreach ($listouctcome_codes as $val)
            {
                $list_outcome_arr[] = array(
					'idoutcomedefinitions' => current($val)->idoutcomedefinitions, 
					'outcomemaincode' => current($val)->outcomemaincode,
					'outcome' => current($val)->outcome,
					'outcomestate' => current($val)->outcomestate,
				);
            }
        }
        exit(json_encode($list_outcome_arr));
    }
    
    /*
     * Description - Function is to save notes while the doctor's call is going on
     * Author - Dave Brown
     * Created - 05 March 2015
    */
    public function save_appointment_outcomes ()
    {
        if (post_data('appointment_id') == NULL)
        {
            return;
        }
        $table_data = array();

        $app_return_response = $app_status = array();

        $table_data['id'] = post_data('id') != NULL ? post_data('id') : 0;
        $table_data['appointment_id'] = post_data('appointment_id');
		
        /*--------------------Updating third party's API----------------------------------------*/
        /*Updating Refferal conclusion*/
		if (post_data('patient_reference') != NULL)
		{
			$table_data['outcome_description']['patient_reference'] = post_data('patient_reference') != NULL ? post_data('patient_reference') : NULL;
			$app_return_response['referal_response'] = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
				"action" => "postoutcome",
				"patientid"  => post_data('patient_id'),
				"appointmentid" => post_data('appointment_id'),
				"outcomemaincode" => post_data('patient_reference'),
			)));
		}
		
		/*Updating appointment conclusion*/
		if (post_data('appointment_conclusion') != NULL)
		{
			$table_data['outcome_description']['appointment_conclusion'] = post_data('appointment_conclusion') != NULL ? post_data('appointment_conclusion') : NULL;
			$app_return_response['outcome_response'] = post_curl_request(THIRD_PARTY_API_URL, json_encode(array(
				"action" => "postoutcome",
				"patientid"  => post_data('patient_id'),
				"appointmentid" => post_data('appointment_id'),
				"outcomemaincode" => post_data('appointment_conclusion'),
			)));
		}
		/*-------------------------Updating third party's Database Ends-------------------------*/
		if (!empty($app_return_response))
		{
			foreach ($app_return_response as $key => $val)
			{
				if (!empty($val) && json_decode($val) && !empty(json_decode($val)->Status) && json_decode($val)->Status == 1)
				{
					$app_status[$key] = TRUE;
				}
				else
				{
					$app_status[$key] = FALSE;
				}
			}
		}
		
		$table_data['outcome_description'] = !empty($table_data['outcome_description']) ? json_encode($table_data['outcome_description']) : NULL;
		$presc_id = $this->appointment_model->save_prescription($table_data);
        
        exit(json_encode(array(
            'presc_id' => $presc_id,
            'app_status' => $app_status,
        )));
    }
}
