<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Created By   -   Sumit Kohli
 * Date         -   12 May, 2014
 * Last Modified-   1 July, 2014
 * Decription   -   Website's Files Model. All file uploading is callable from this model.
 */
class Files_Model extends CI_Model
{

    public function __construct() 
    {
        $this->created = date('Y-m-d H:i:s');
    }
    
    /*
     * Function Saves the file data
     * */
    public function save_file($data)
    {
        $data['created'] = $this->created;
        $this->db->insert('files', $data);
        return $this->db->insert_id();
    }

    /*
     * Function Updates the file data
     * */
    public function update_file($data)
    {
        if (empty($data['id'])) 
        {
            return;
        }
        $this->db->where('id', $data['id']);
        $this->db->update('files', $data);
        return $data['file_id'];
    }

    /*
     * Function fetches the data from the database
     * Inputs   :   $conditions                 -   array   -   set of conditions for where clause in query
     *              //Example
     *              $conditions['blog_id']  = value(string) 
     * 
     *              $params                     -   array   -   parmaters to pass for paging, limit, grouping   
     *              $params['limit']            -   string  -   Limit the no of records
     *              $params['group_by']         -   string  -   'fieldname' (sql group by) 
     *              $params['fields']           -   string  -   'fields of table to select'
     *              $params['cnt']              -   BOOLEAN -   'Set To TRUE If you Want to count the table records with where conditions
     * 
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
        $order_by = !empty($params['order_by']) ? $params['order_by'] : 'files.id DESC';
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
            $res = $this->db->count_all_results('files');
            return $res;
        }
        $query = $this->db->get('files', $limit, $start);

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
     * Delete a file record from database
     * input    -   section     -   Property of the class   -   (Refer from CONSTANTS File)
     *              section_id  -   Property of the class   -   (module_id)
    */ 
    public function delete_file ($unlink = TRUE, $file_id = NULL) 
    {
        if (empty($file_id)) 
        {
            return;
        }
        $old_file_name = $this->get_all(array('file_id' => $file_id), array('fields' => 'name, section'));

        $this->section = $old_file_name[0]->section;
        $file_path = $this->get_section_path();

        if (file_exists($file_path . $old_file_name[0]->file_name) && $unlink == TRUE)
        {
            unlink($file_path . $old_file_name[0]->file_name);
        }

        $this->db->where('file_id', $file_id);
        $this->db->delete('lp_files');
    }

    public function delete_file_by_section ($section, $section_id, $user_id = NULL) 
    {
        if (empty($section_id) || empty($section)) 
        {
            return;
        }
        $conditions = array('section_id' => $section_id, 'section' => $section);

        if (!empty($user_id))
        {
            $conditions['user_id'] = $user_id;
        }
        $old_file_names = $this->get_all($conditions, array('fields' => 'name'));

        $this->section = $section;
        $file_path = $this->get_section_path();

        if (!empty($old_file_names))
        {
            foreach ($old_file_names as $val)
            {
                if (file_exists($file_path . $val->name))
                {
                    unlink($file_path . $val->name);
                }
            }
        }
        $this->db->where($conditions);
        $this->db->delete('files');
    }

    public  function get_section_path()
    {
        if (empty($this->section))
        {
            return;
        }
        switch($this->section)
        {
            case PROFILE_IMAGE :
                return DIRPATH . PROFILE_IMAGE_PATH;
                break;
        }
    }
    
}
