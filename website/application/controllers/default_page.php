<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By 	- 	Dave Brown
 * Date			-	9 July, 2014
 * Decription	-	Dshboard, login, logout
 * */
 
class Default_Page extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$url_arrays = $this->config->item('customHardRedirectsArr');
		$current_uri = $this->uri->uri_string();
		$current_uri = strtolower($current_uri);

		if (isset($url_arrays[$current_uri]))
		{
			redirect(base_url() . $url_arrays[$current_uri]);
		}

		/*Default to 404*/
		show_404();
	}
}
