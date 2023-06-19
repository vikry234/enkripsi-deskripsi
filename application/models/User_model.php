<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public $username;
    public $email;
    public $password;
    public $insert_by;
    public $update_by;
    public $active;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserByPassword($username, $password)
    {
        $password_sha1 = sha1($password);

        log_message('error', 'username ' . $username);
        log_message('error', 'password' . $password);

        $this->db->select("*");
        $this->db->from('user_login AS ul');
        $this->db->where('ul.username', $username);
        $this->db->where('ul.password', $password_sha1);
        $this->db->where('ul.active', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();

        if ($query->num_rows() == 1) {
            $user = $result[0];

            // date declaration

            return $return;
        } else {
            return false;
        }
    }

    public function insert()
    {
        $data = [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'insert_by' => $this->insert_by,
            'update_by' => $this->update_by,
            'active' => $this->active
        ];
        $this->db->insert("user_login", $data);
    }
}
?>