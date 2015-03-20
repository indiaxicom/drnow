<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 Dec, 2014
 * Decription   -   Shifts Model - Handle all shifts related functionalities
 */
class Shift_Model extends CI_Model
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
            $stst = $this->db->insert('shifts', $data);
            return $this->db->insert_id();
        }
        else
        {
            unset($data['created']);
            $this->db->where('id', $data['id']);
            $this->db->update('shifts', $data);
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
        $order_by = !empty($params['order_by']) ? $params['order_by'] : 'shifts.id DESC';
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
                foreach ($params['join'] as $val)
                {
                    $val['type'] = !empty($val['type']) ? $val['type'] : 'full';
                    $this->db->join($val['table'], $val['conditions'], $val['type']);
                }
            }
        }
        if (!empty($params['cnt']))
        {
            $res = $this->db->count_all_results('shifts');
            return $res;
        }
        $query = $this->db->get('shifts', $limit, $start);

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
            else if (!empty($params['return_field_2d']) && !empty($params['return_field_1d']))
            {
                $return[$row->$params['return_field_2d']][$row->$params['return_field_1d']] = $row;
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
     * Function updates the Status of the shifts
     * */

    public function change_status($data)
    {
        if (empty($data['id']) || empty($data['status']))
        {
            return;
        }

        $this->db->where('shifts.id', $data['id']);

        if ($data['status'] == DELETED)
        {
            $this->db->delete('shifts');
            $this->after_delete($data['id']);
        }
        else
        {
            $this->db->update('shifts', array('status' => $data['status']));
            $this->after_update($data['id']);
        }
    }

    /*Function to run after deleting a record*/
    private function after_delete($id)
    {
        
    }

    private function after_insert($id)
    {
    }

    /* Description - Check for doctor shift if exist between the time */

    public function check_shift_between_time($doctor_id, $start_time, $end_time)
    {
        if (in_array(NULL, array($doctor_id, $start_time, $end_time)))
        {
            return;
        }
        $this->db->where('app_id <> ', 0);
        $this->db->where('doctor_id', $doctor_id);
        $this->db->where('((start_time > "' . $start_time . 
            '" AND start_time < "' . $end_time . '") OR (end_time > "' . $start_time . 
            '" AND end_time < "' . $end_time . '") OR (start_time < "' . $start_time . 
            '" AND end_time > "' . $start_time . '") OR (start_time < "' . $start_time . 
            '" AND end_time > "' . $start_time . '"))');
        $res = $this->db->count_all_results('shifts');
        return $res > 0 ? TRUE : FALSE;
    }
}
