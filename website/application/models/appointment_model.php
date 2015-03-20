<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 Nov, 2014
 * Decription   -   Email Templates Model - Handle all appointments related functionalities
 */
class Appointment_Model extends CI_Model
{
    public function __construct()
    {
        $this->created = $this->modified = date('Y-m-d H:i:s');
    }

    public function save($data = NULL, $action = 'save')
    {
        $data['created'] = $this->created;
        $data['modified'] = $this->modified;

        if ($action == 'save')
        {
            $stst = $this->db->insert('appointments', $data);
            return $data['id'];
        }
        else
        {
            unset($data['created']);
            $this->db->where('id', $data['id']);
            $this->db->update('appointments', $data);
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
        $order_by = !empty($params['order_by']) ? $params['order_by'] : 'appointments.id DESC';
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
            $res = $this->db->count_all_results('appointments');
            return $res;
        }
        $query = $this->db->get('appointments', $limit, $start);

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
     * Function updates the Status of the appointments
     * */

    public function change_status($data)
    {
        if (empty($data['id']) || empty($data['status']))
        {
            return;
        }

        $this->db->where('appointments.id', $data['id']);

        if ($data['status'] == DELETED)
        {
            $this->db->delete('appointments');
            $this->after_delete($data['id']);
        }
        else
        {
            $this->db->update('appointments', array('status' => $data['status']));
            $this->after_update($data['id']);
        }
    }

    /*Function to run after deleting a record*/
    private function after_delete($id)
    {
        $this->db->where('appointment_id', $id);
        $this->db->delete('appointment_details');
    }

    private function after_insert($id)
    {
    }

    public function save_appointment_details($data, $batch = FALSE)
    {
        ($batch == TRUE) ? $this->db->insert_batch('appointment_details', $data) : $this->db->insert('appointment_details', $data);
        return TRUE;
    }

    public function update_appointment_details($appointment_id, $user_id, $data)
    {
        if (empty($appointment_id) || empty($user_id))
        {
            return;
        }
        $this->db->where(array('appointment_id' => $appointment_id, 'user_id' => $user_id));
        $this->db->update('appointment_details', $data);
    }

    /*
     * Description - This function will save the prescription
     * Author - Dave Brown
     * Created - 24 December 2014
     */
    public function save_prescription($data = NULL)
    {
        $data['created'] = $this->created;

        if (empty($data['id']))
        {
            $this->db->insert('prescriptions', $data);
            return $this->db->insert_id();
        }
        else
        {
            $this->db->where('id', $data['id']);
            $this->db->update('prescriptions', $data);
            return $data['id'];
        }
    }

    /*
     * Description - This function will save or update the archieve details
     * Author - Dave Brown
     * Created - 26 December 2014
     */
    public function save_archieve($data = NULL, $action = 'save', $where = NULL)
    {
        $data['created'] = $this->created;
        $data['modified'] = $this->modified;

        if ($action == 'save')
        {
            $this->db->insert('tokbox_archieves', $data);
        }
        else
        {
            unset($data['created']);

            if (!empty($where))
            {
                $this->db->where($where);
            }
            $this->db->update('tokbox_archieves', $data);
        }
    }
}
