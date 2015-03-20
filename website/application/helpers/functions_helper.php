<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

function css_url_path ($file_name)
{
    return base_url() . CSS_URL_PATH . $file_name; 
}

function js_url_path ($file_name)
{
    return base_url() . JS_URL_PATH . $file_name; 
}

function image_url_path ($file_name)
{
    return base_url() . IMAGES_URL_PATH . $file_name; 
}

function send_externel_mail ($to, $subject, $body, $from, $from_name = NULL, $cc = NULL, $bcc = NULL, $attachment = NULL)
{
    $CI = & get_instance();
    $CI->load->library('email');
    
    $config['wordwrap'] = TRUE;
    $config['mailtype'] = 'html';
    $CI->email->initialize($config);

    if (!empty($from))
    {
        $from_name = !empty($from_name) ? $from_name : 'ThIS Liverpool App';
        $from = !empty($from) ? $from : 'info@wex24.com';        
        $CI->email->from($from, $from_name);
    }
    else
    {
        $CI->email->from('noreply@thisliverpool.com', $from_name);
    }
    if (!empty($to))
    {
        $CI->email->to($to);
    }
    if (!empty($cc))
    {
        $CI->email->cc($cc);
    }
    if (!empty($bcc))
    {
        $CI->email->cc($bcc);
    }
    $CI->email->subject($subject);
    $CI->email->message($body);

    if (!empty($attachment))
    {
        if (is_array($attachment))
        {
            foreach ($attachment as $val) 
            {
                $CI->email->attach($val);
            }
        }
        else
        {
            $CI->email->attach($val);
        }
    }

    if ($CI->email->send())
    {
        return TRUE;
    }
}

function p($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit();
}

function upload_file($path = NULL, $allowed_types = NULL, $min_width = NULL, $min_height = NULL, $max_width = NULL, $max_height = NULL, $file_name = NULL)
{
    $CI = & get_instance();
    $config = array (
            'upload_path'   =>  !empty($path) ? $path : 'assets/files/',
            'max_size'      =>  '10000',
            'remove_spaces' =>  TRUE,
        );
    
    if (!empty($allowed_types))
    {
        $config['allowed_types'] = $allowed_types;
    }
    if (!empty($max_width) && !empty($max_height))
    {
        $config['max_width'] = $max_width;
        $config['max_height'] = $max_height;
    }
    
    $CI->load->library('upload', $config);

    if (!empty($file_name))
    {
        $file = $CI->upload->do_upload($file_name);
    }
    else {
        $file = $CI->upload->do_upload();
    }

    if (! $file)
    {
        return $CI->upload->display_errors();
    }
    else
    {
        $data = $CI->upload->data();
        $img_details = getimagesize($data['full_path']);

        if ((!empty($min_width) && $img_details[0] < $min_width) || (!empty($min_height) && $img_details[1] < $min_height))
        {
            unlink($data['full_path']);
            return "Image Should Be atleast $min_width x $min_height";
        }
        return $data;
    }
}

function resize_image($s, $w, $h, $d = NULL, $new_image = FALSE, $maintain_ratio = TRUE)
{
    if (file_exists($d) || file_exists($s) == FALSE)
    {
        return;
    }

    $img_details = getimagesize($s);
    $master_dim = 'height';
        
    if ($img_details[1] < $h)
    {
        //return;
    }
    if ($img_details[1] > $img_details[0])
    {
        $master_dim = 'width';
    }
    $CI = & get_instance();
    $config = array();
    $config['image_library'] = 'gd2';
    $config['source_image'] = $s;
    if ($new_image == TRUE) {
        $config['new_image'] = $d;
    }
    $config['height'] = $h;
    $config['width'] = $w;
    $config['maintain_ratio'] = !empty($maintain_ratio) ? $maintain_ratio : TRUE;
    $config['master_dim'] = $master_dim;
    $CI->load->library('image_lib', $config); 
    
    if ( ! $CI->image_lib->resize())
    {
        //echo $CI->image_lib->display_errors();
    }
    $CI->image_lib->clear();
    return $d;
}

function crop_image($s, $d, $w, $h, $x = NULL, $y = NULL)
{
    if ((file_exists($s) == FALSE))
    {
        return;
    }
    $img_details = getimagesize($s);
    $CI = & get_instance();
    $config = array();
    $config['image_library'] = 'gd2';
    $config['source_image'] = $s;
    $config['new_image'] = $d;
    $config['x_axis'] = $x;
    $config['y_axis'] = $y;
    $config['height'] = $h;
    $config['width'] = $w;
    $config['maintain_ratio'] = FALSE;
    $CI->load->library('image_lib', $config); 

    if ( ! $CI->image_lib->crop())
    {
        //echo $CI->image_lib->display_errors();
    }
}

function crop_image_for_website($s, $d, $w, $h)
{
    if (file_exists($d) || file_exists($s) == FALSE)
    {
        return;
    }
    $CI = & get_instance();

    $resized_image = resize_image($s, $w, $h, $d, TRUE, TRUE);

    if (!empty($resized_image))
    {
        if(file_exists($resized_image))
        {
            $img_details = getimagesize();
            $w_h_ratio = $img_details[0] / $img_details[1];
    
            if ($img_details[0] < $w)
            {
                $w = $img_details[0];
            }
            $image_config = array();
            $image_config['image_library'] = 'gd2';
            $image_config['source_image'] = $d;
            $image_config['new_image'] = $d;
            $image_config['create_thumb'] = true;
            $image_config['maintain_ratio'] = FALSE;
            $image_config['width'] = $w;
            $image_config['height'] = $h;
            $image_config['x_axis'] = '0';
            $image_config['y_axis'] = '0';
    
            $CI->load->library('image_lib');
            $CI->image_lib->clear();
            $CI->image_lib->initialize($image_config); 
            $CI->image_lib->crop();
        }
    }
}


function create_nicename($str, $unique_id = NULL)
{
    $str = preg_replace("/[^A-Za-z0-9 ]/", '', $str);
    $str = preg_replace( "/\s+/", "-", $str);
    $str = strtolower($str);
    if (!empty($unique_id))
    {
        $str = $str . '-' . $unique_id;
    }
    return $str;
}

function save_values_to_str($arr)
{
    return '|'.implode('|', $arr).'|';
}

function retrieve_values_to_array($str)
{
    $str = substr($str, 1);
    $str = substr($str, 0, -1);
    
    //convert to array
    return explode('|', $str);
}

function convert_from_sql_time($format, $date_time)
{
    $ts = strtotime($date_time);
    return date($format, $ts);
}

function convert_to_sql_time($date_timestamp)
{
    return date('Y-m-d h:i:s', $date_timestamp);
}

function get_dates_between_timestamps($start_date, $end_date)
{
    $return = array();
    $numDays = abs($end_date - $start_date)/60/60/24;
    $return[] = $start_date;
    for ($i = 1; $i <= $numDays; $i++) {
        $return[] = strtotime("+{$i} day", $start_date);
    }
    return $return;
}

function convert_cat_for_search($str)
{
    return '|' . $str . '|';
}

//average_type = all    For all ratings in one value


function customize_roundoff($number)
{
    $round_value = floor($number);
    
    $round_val_diff = $number - $round_value;
    
    if ($round_val_diff == 0)
    {
        return $number;
    }
    else if ($round_val_diff < ($round_value + 0.5))
    {
        return $round_value;
    }
    else if ($round_val_diff > ($round_value + 0.5))
    {
        return $round_value + 0.5;
    }
}

function sanitize_file_name($str)
{
    $str = preg_replace("/[^A-Za-z0-9 .]/", '', $str);
    $str = preg_replace( "/\s+/", "-", $str);
    $str = strtolower($str);
    $str = time() . '-' . $str;
    return $str;
}

function no_permission()
{
    $CI = & get_instance();
    $CI->session->set_flashdata('flashdata', 'You do not have permission to access this operation');
    redirect(base_url() . 'admin');
}

function custom_format_number($number)
{
    return number_format($number, 2, '.', '');
}

function get_permissions_for_view()
{
    $CI = & get_instance();

    return $CI->roles_model->get_roles_permissions(array('role_id' => $CI->session->userdata('role_id')), array('by_module' => TRUE));
}

function is_super_admin()
{
    $CI = & get_instance();

    if ($CI->session->userdata('user_id') != NULL && $CI->session->userdata('user_id') == SUPER_ADMIN)
    {
        return TRUE;
    }
    return FALSE;
}

function is_doctor()
{
    $CI = & get_instance();

    if ($CI->session->userdata('id') != NULL && $CI->session->userdata('user_type') == USER_DOCTOR)
    {
        return TRUE;
    }
    return FALSE;
}

function is_admin()
{
    $CI = & get_instance();

    if ($CI->session->userdata('user_type') == USER_ADMIN)
    {
        return TRUE;
    }
    return FALSE;
}

function session_data($param = NULL)
{
    $CI = & get_instance();

    if (!empty($param))
    {
        return $CI->session->userdata($param);
    }
    else
    {
        return $CI->session->all_userdata();
    }
    return FALSE;
}

//Check for business users
function check_page_permission($user_types = array())
{
    if ((session_data('user_type') != NULL && in_array(session_data('user_type'), $user_types)) || is_admin())
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function zip_files($file_paths)
{
    $CI = & get_instance();
    $CI->load->library('zip');

    foreach ($file_paths as $path)
    {
        if (is_file($path) && file_exists($path))
        {
            $CI->zip->read_file($path);
        }
    }
    return $CI->zip->download('download.zip'); 
}


/*
 * Send notifications to android devices
 * $registatoin_ids = array of ids
 * $message = array 
 */ 
function send_android_notification($registatoin_ids, $message)
{
    if (empty($registatoin_ids) || empty($message))
    {
        return;
    }

    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $msg = array
            (
            'message' => $message,
            'vibrate'   => 1,
            'sound' => 1
            );

    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $msg,
    );

    $headers = array(
        'Authorization: key=' . GOOGLE_PUSH_API_KEY,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
    return $result;
}

/*
 * Apple Notifications
 */
function send_ios_notification($device_details, $message)
{
    if (empty($device_details) || empty($message))
    {
        return;
    }

    $body = array();
    $body['aps'] = array('alert' => $message);
    $body['aps']['notifurl'] = 'http://www.liverpool.com';

    $err = $errstr = $errstrn = NULL;
    //certificate file path
    $apnsCert = APPPATH . 'libraries/api/Liverpool-BID.p12.pem';
    $passPhrase = '';
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passPhrase);

    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
    //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 120, STREAM_CLIENT_CONNECT, $ctx);

    stream_set_blocking ($fp, 0);

    if (!$fp)
    {
        echo $errstr."Failed to connect (stream_socket_client): $err $errstrn";
    } 
    else 
    {
        $apple_expiry = time() + (90 * 24 * 60 * 60);

        foreach ($device_details as $val)
        {
            $apple_identifier = $val['user_id'];
            $deviceToken = '<4cab9c58 064e1235 7c081197 d8ba28de 9369b3c4 40ba166e a71cf790 8ece6b98>';//$val['device_id'];
            $deviceToken = str_replace("<", '', $deviceToken);
            $deviceToken = str_replace(">", '', $deviceToken);
            $payload = json_encode($body);
            $msg = pack("C", 1) . pack("N", $apple_identifier) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;
            
            $file_write = fwrite($fp, $msg, strlen ($msg));

            $err_response = checkAppleErrorResponse($fp);
            fclose($fp);
            return $err_response;
        }
    }
}


/*
 * FUNCTION to check if there is an error response from Apple
 * Returns error response if there is an error and success if there is not any error
*/
function checkAppleErrorResponse($fp)
{

    $apple_error_response = fread($fp, 6);

    if ($apple_error_response) {

        // unpack the error response (first byte 'command" should always be 8)
        $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response); 

        if ($error_response['status_code'] == '0')
        {
            $error_response['status_code'] = '0-No errors encountered';
        }
        else if ($error_response['status_code'] == '1')
        {
            $error_response['status_code'] = '1-Processing error';
        } 
        else if ($error_response['status_code'] == '2')
        {
            $error_response['status_code'] = '2-Missing device token';
        }
        else if ($error_response['status_code'] == '3')
        {
            $error_response['status_code'] = '3-Missing topic';
        }
        else if ($error_response['status_code'] == '4')
        {
            $error_response['status_code'] = '4-Missing payload';
        }
        else if ($error_response['status_code'] == '5')
        {
            $error_response['status_code'] = '5-Invalid token size';
        }
        else if ($error_response['status_code'] == '6')
        {
            $error_response['status_code'] = '6-Invalid topic size';
        }
        else if ($error_response['status_code'] == '7')
        {
            $error_response['status_code'] = '7-Invalid payload size';
        }
        else if ($error_response['status_code'] == '8')
        {
            $error_response['status_code'] = '8-Invalid token';
        }
        else if ($error_response['status_code'] == '255') {
            $error_response['status_code'] = '255-None (unknown)';

        }
        else
        {
            $error_response['status_code'] = $error_response['status_code'].'-Not listed';
        }

        return $error_response;
    }
    else
    {
        return 'success';
    }
}

function base64_to_image($base64_string, $output_file)
{
    $ifp = fopen($output_file, "wb"); 
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 
    return $output_file; 
}

function post_curl_request($url, $json)
{
    $CI = & get_instance();

    if ($CI->config->item('allow_third_party_api') == FALSE)
    {
        return;
    }
    $response = array();
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array (
        "Content-Type: application/json",
        'Content-Length: ' . strlen($json),
    ));

    $response = curl_exec($ch);
    
    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200')
    {
        curl_close ($ch);
        return $response;
    }
    curl_close ($ch);
    return array();
}

function uk_date_to_stamp($str, $format = 'd/m/Y')
{
    return DateTime::createFromFormat($format, $str)->getTimestamp();
}

function post_data($param = NULL)
{
    $CI = & get_instance();

    if (!empty($param))
    {
        return $CI->input->post($param);
    }
    else
    {
        return $CI->input->post();
    }
    return FALSE;
}

function get_data($param = NULL)
{
    $CI = & get_instance();

    if (!empty($param))
    {
        return $CI->input->get($param);
    }
    else
    {
        return $CI->input->get();
    }
    return FALSE;
}

function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    /* pls set your unique hashing key*/
    $secret_key = md5('doctornow');
    $secret_iv = md5('doctornowadmin');

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/* This function is made to convert response from third party API to our Compatible format*/
function combine_keys_values($array)
{
    if (empty($array))
    {
        return;
    }

    $odd = array();
    $even = array();
    $keys_values = array(&$even, &$odd);
    array_walk($array, function($v, $k) use ($keys_values) { $keys_values[$k % 2][] = $v; });

    return array_combine($keys_values[0], $keys_values[1]);
}

/*Used to make API format for third parties API*/
function make_prescription_format($presc_array)
{
    if (empty($presc_array))
    {
        return;
    }

	$i = 1;

    foreach($presc_array as $val)
    {
		$return[$i]['VPID'] = $val['vpid'];
		$return[$i]['NM'] = $val['nm'];
		$return[$i]['Supply'] = $val['dose'];
		$return[$i]['Units'] = $val['total'];
		$return[$i]['Directions1'] = $val['frequency'];
		$return[$i]['Directions2'] = $val['frequency'];
		$i++;
    }
    return $return;
}

/*Used to make ICD list API format for third parties API*/
function make_icd_format($icd_array = array())
{
    if (empty($icd_array))
    {
        return;
    }

    foreach($icd_array as $val)
    {
        $return[] = current($val);
    }
    return $return;
}

/*Find Age*/
function find_age($dob)
{
	$date_arr = explode('/', $dob);

	if (checkdate($date_arr[1], $date_arr[0], $date_arr[2]) == FALSE)
	{
		return FALSE;
	}
    $dob = uk_date_to_stamp($dob);
    $from = new DateTime(date('Y-m-d', $dob));
    $to = new DateTime('today');
    return $from->diff($to)->y;
}

/*Create Folder Name*/
function create_folder_name($str)
{
	return str_pad($str, 10, 0, STR_PAD_LEFT);
}
