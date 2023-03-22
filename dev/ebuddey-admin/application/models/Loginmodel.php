<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Loginmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//User Info
	public function userInfo( $user_id ){
		$this->db->select('*');
	    $this->db->where( 'user_id', $user_id );
	    $query = $this->db->get( 'user' );
		$row = $query->row();
	    return $row;
	}
}