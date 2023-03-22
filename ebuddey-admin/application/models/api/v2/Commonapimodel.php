<?php 
//V2
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Commonapimodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    
    public function packageInfo( $packageId ){
        $this->db->select( '*' );
        $this->db->where( 'id', "$packageId" );
        $query  = $this->db->get( 'events' );
        return $query->row();
    }

    public function updatePackageInfo($data, $packageId){
        $table  = $this->db->dbprefix('events');
        $this->db->where('id', $packageId);
        $this->db->update( $table, $data );
    }
}