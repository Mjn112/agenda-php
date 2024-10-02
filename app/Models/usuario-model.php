<?php
class UserModel extends CI_Model {

    public function register($data) {
        return $this->db->insert('users', $data);
    }

    public function get_user_by_login($login) {
        $query = $this->db->get_where('users', ['login' => $login]);
        return $query->row_array(); // Retorna um único resultado como array
    }
}
?>
