<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Dave Brown
 * Date         -   12 May, 2014
 * Decription   -   Users Model - Handle all users related functionalities
 */
class Users_Model extends CI_Model
{
    public function __construct()
    {
        $this->created = $this->modified = date('Y-m-d H:i:s');
    }

    /*
     *  input       -   $email      -   string  -   email
     *                  $password   -   string  -   password
     *  return      -   user details-   object
     *  description -   authenticate for users on login
     */
    public function authenticate_user($email, $password, $user_type)
    {
        $result = array();

        if (empty($email) || empty($password))
        {
            return FALSE;
        }
        $this->db->where(array('users.status' => ACTIVE, 'users.email' => $email, 'users.password' => $password));

        if (is_array($user_type))
        {
            $this->db->where_in('users.user_type', $user_type);
        }
        else
        {
            $this->db->where('users.user_type', $user_type);
        }
        $this->db->select('users.*, files.name AS profile_image, FS.name AS signature_image');
        $this->db->join('files', 'files.user_id = users.id AND files.section = ' . PROFILE_IMAGE, 'left');
        $this->db->join('files AS FS', 'FS.user_id = users.id AND FS.section = ' . SIGNATURE_IMAGE, 'left');
        return $this->db->get('users')->row();
    }

    /*
     *  input       -   $email      -   string  -   admin email
     *                  $password   -   string  -   admin password
     *  description -   updates the password
     */
    public function update_password($email, $password, $old_password = NULL)
    {
        $result = array();

        if (empty($email) || empty($password))
        {
            return FALSE;
        }
        if (!empty($old_password))
        {
            $this->db->where('password', $old_password);
        }
        $this->db->where('email', $email);
        $this->db->update('users', array('password' => $password));
    }

    /*
     *  description -   Saves and updates te user information
     */

    public function save($data = NULL)
    {
        $data['created'] = $this->created;
        $data['modified'] = $this->modified;

        if (empty($data['id']))
        {
            $this->db->insert('users', $data);
            return $this->db->insert_id();
        }
        else
        {
            unset($data['created']);
            $this->db->where('id', $data['id']);
            $this->db->update('users', $data);
            return $data['id'];
        }
    }

    /*
     *  description -   Saves and updates te user information
     */

    public function check_existing_user($email, $user_type)
    {
        if (empty($email) || empty($user_type))
        {
            return;
        }
        $this->db->where(array('email' => $email, 'user_type' => $user_type));

        if ($this->db->count_all_results('users') > 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    public function activate_user($email)
    {
        if (empty($email))
        {
            return;
        }
        $this->db->where('email', $email);
        $this->db->update('users', array('is_active' => ACTIVE));
    }

    /*
     * Function fetches the data from the database
     * Inputs   :   $conditions  -  array   -   set of conditions for where clause in query
     *              $params      -  array   -   parmaters to pass for paging, limit, grouping
     * return   :   data based on conditions & params
     * */

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
        $order_by = !empty($params['order_by']) ? $params['order_by'] : 'users.id DESC';
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
            $res = $this->db->count_all_results('users');
            return $res;
        }
        $query = $this->db->get('users', $limit, $start);

        /*If single row is wanted*/
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
     * Function updates the Status of the users
     */

    public function change_status($data)
    {
        if (empty($data['id']) || empty($data['status']))
        {
            return;
        }

        $this->db->where('users.id', $data['id']);

        if ($data['status'] == DELETED)
        {
            $this->db->delete('users');
            $this->after_delete($data['id']);
        }
        else
        {
            $this->db->update('users', array('status' => $data['status']));
            $this->after_update($data['id']);
        }
    }
    
    /*
     * Saving Doctor's details
     * Note : Apply conditions in only update type
     */
    public function save_user_details($data, $action, $conditions = array())
    {
        if (!empty($conditions))
        {
            $this->db->where($conditions);
        }
        $this->db->$action('user_details', $data);
    }
    
    public function get_user_details($user_id = NULL)
    {
        $return = array();

        if (!empty($user_id))
        {
            if (is_array($user_id))
            {
                $this->db->where_in('user_id', $user_id);
            }
            else
            {
                $this->db->where('user_id', $user_id);
            }
        }

        $query = $this->db->get('user_details');
        
        foreach ($query->result_object() as $row)
        {
            $return[$row->user_id][$row->key] = $row->value;
        }
        return $return;
    }

    /*Fetch Xicom's doctor database id*/
    public function get_original_doctor_id($doctor_app_id = NULL)
    {
        if (empty($doctor_app_id))
        {
            return;
        }
        $this->db->select('id');
        $this->db->where('status', ACTIVE);
        $this->db->where('app_user_id', $doctor_app_id);
        $this->db->where('user_type', USER_DOCTOR);
        $query = $this->db->get('users')->row();
        return !empty($query->id) ? $query->id : FALSE;
    }

    /*
     * Fetch Thirdparty's doctor id
     * Input - Xicom App Id
     * Output- Third Party's App Id
     */
    public function get_app_doctor_id($doctor_id = NULL)
    {
        if (empty($doctor_id))
        {
            return;
        }
        $this->db->select('app_user_id');
        $this->db->where('status', ACTIVE);
        $this->db->where('id', $doctor_id);
        $this->db->where('user_type', USER_DOCTOR);
        $query = $this->db->get('users')->row();
        return $query->app_user_id;
    } 
    
    public function delete_user_details($user_id, $keys = NULL)
    {
        if (empty($user_id))
        {
            return;
        }
        if (!empty($keys))
        {
            if (is_array($keys))
            {
                $this->db->where_in('key', $keys);
            }
            else
            {
                $this->db->where('key', $keys);
            }
        }
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_details');
    }

    private function after_delete($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete(array('user_details'));
    }

    private function after_update($id)
    {
    }
}
