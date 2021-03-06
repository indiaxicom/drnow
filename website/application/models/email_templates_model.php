<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 Nov, 2014
 * Decription   -   Email Templates Model - Handle all email_templates related functionalities
 */
class Email_Templates_Model extends CI_Model
{
    public function __construct() 
    {
        $this->created = $this->modified = date('Y-m-d H:i:s');
    }

    public function save($data = NULL)
    {
        $data['created'] = $this->created;
        $data['modified'] = $this->modified;

        if (empty($data['id'])) 
        {
            $this->db->insert('email_templates', $data);
            return $this->db->insert_id();
        } 
        else
        {
            unset($data['created']);
            $this->db->where('id', $data['id']);
            $this->db->update('email_templates', $data);
            return $data['id'];
        }
    }

    public function get_all($conditions = array(), $params = array()) 
    {   
        $return = array();   
        if (!empty($conditions)) {
            foreach ($conditions as $key => $val) 
            {
                if (!empty($val) && is_array($val)) 
                {
                    $this->db->where_in($key, $val);
                } 
                else 
                {
                    $this->db->where($key, $val);
                }
            }
        }

        $start = $limit = NULL;
        if (!empty($params['page']) && !empty($params['show']) && (!isset($params['cnt']))) 
        {
            $start = ($params['page'] -1) * $params['show'];
            $limit = $params['show'];
        } 
        else if(!empty($params['limit'])) 
        {
            $limit = $params['limit'];
            $start = 0;
        }
        $order_by = !empty($params['order_by']) ? $params['order_by'] : 'email_templates.id DESC';
        $this->db->order_by($order_by);

        if(!empty($params['group_by'])) 
        {
            $this->db->group_by($params['group_by']);
        }

        if (!empty($params['fields'])) 
        {
            $this->db->select($params['fields']);

            if (!empty($params['join']))
            {
                $this->db->join($params['join']['table'], $params['join']['conditions'], $params['join']['type']);
            }
        }
        if (!empty($params['cnt'])) 
        {
            $res = $this->db->count_all_results('email_templates');
            return $res;
        }
        $query = $this->db->get('email_templates', $limit, $start);

        /*If single row is wanted */
        if (!empty($params['single']))
        {
            return $query->row();
        }

        foreach ($query->result_object() as $row) 
        {
            if (!empty($params['return_field'])) 
            {
                $return[$row->$params['return_field']] = $row;
            } 
            else 
            {
                $return[] = $row;
            }
           
        }
        $query->free_result();
        return $return;
    }
    
    /*
     * pass user_id and the status in the function to update the record
     * Function updates the Status of the email_templates
     * */

    public function change_status($data)
    {
        if (empty($data['id']) || empty($data['status'])) 
        {
            return;
        }

        $this->db->where('email_templates.id', $data['id']);

        if ($data['status'] == DELETED) 
        {
            $this->db->delete('email_templates');
            $this->after_delete($data['id']);
        }
        else
        {
            $this->db->update('email_templates', array('status' => $data['status']));
            $this->after_update($data['id']);
        }
    }

    /* GET EMAIL TEMPLATE BY TYPE */
    public function get_template_by_type($template_type)
    {
        $conditions = $params = array();
        $conditions['template_type'] = $template_type;
        $conditions['status'] = ACTIVE;
        
        $params['fields'] = 'subject, from_name, from_email, body';
        $params['single'] = TRUE;
        return $this->get_all($conditions, $params);
    }
    

    private function after_delete($id)
    {
    }
    
    private function after_update($id)
    {
    }
}
