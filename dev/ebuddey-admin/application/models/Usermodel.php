<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usermodel extends CI_Model {
    //User lists
    public function user_lists( $user_search = '', $order_by = '' ){
        $this->db->select('*');
        $this->db->where( 'status', 1 );
        //If Condition user search
        if( $user_search ) {
            $this->db->where( "( full_name LIKE '%". $user_search ."%' OR contact_number LIKE '%". $user_search ."%' OR user_email LIKE '%". $user_search ."%' )" );
        }
        //If Condition for order by
        if( $order_by == 1 ) {
            $this->db->order_by( 'full_name', 'asc' );
        } else if( $order_by == 2 ) {
            $this->db->order_by( 'full_name', 'desc' );
        } else if( $order_by == 3 ) {
            $this->db->order_by( 'user_email', 'asc' );
        } else if( $order_by == 4 ) {
            $this->db->order_by( 'user_email', 'desc' );
        } else {
            $this->db->order_by( 'user_id', 'desc' );
        }
        $query = $this->db->get( 'user' );
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }
    public function permission_list( $role_id ) {
        $this->db->where( 'role_id', $role_id );
        $this->db->where('status', 1 );
        $this->db->select('module');
        $list = $this->db->get( 'permission' );
        $val = $list->result();
        return $val;
    }
    public function userInfo($user_id){
        
        $this->db->select('*');
        $this->db->where('user_id', $user_id );
        $query = $this->db->get('user');
        $row = $query->row();
        return $row;
    }
    public function updateUserInfo($data, $user_id){
        $table  = $this->db->dbprefix('user');
        $this->db->where( 'user_id', $user_id );
        $this->db->update( $table, $data );
        return true; 
    }
}