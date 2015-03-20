<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   26 December, 2014
 * Last 
 * Decription   -   Crons and .
 * */

class Crons extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function tokbox_response()
    {
        $response_data = file_get_contents('php://input');

        $response_data = json_decode($response_data);

        if (empty($response_data))
        {
            exit('No input data');
        }

        $table_data = array(
            'archieve_id' => $response_data->id,
            'duration' => $response_data->duration,
            'size' => $response_data->size,
            'status' => $response_data->status
        );
        $this->appointment_model->save_archieve($table_data, 'update', array(
            'archieve_id' => $table_data['archieve_id']
        ));
    }

}
