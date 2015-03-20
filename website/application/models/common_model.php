<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By 	- 	Dave Brown
 * Date			-	12 Nov, 2014
 * Decription	-	Email Templates Model - Handle all email_templates related functionalities
 */
class Common_Model extends CI_Model
{

	public function save($table, $data, $where = array())
	{
		if (empty($data['id'])) 
		{
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		} 
		else
		{
			$this->db->where($where);
			$this->db->update($table, $data);
			return $data['id'];
		}
	}
}
