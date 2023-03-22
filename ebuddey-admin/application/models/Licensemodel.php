<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Licensemodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//license Lists
	public function licenseLists(){
		$this->db->select('*');
	    $this->db->where( 'status', 1 );
	    $this->db->order_by("license_id", "desc");
	    $query = $this->db->get( 'master_license' );
	    if( $query->num_rows() > 0 ) {
	    	return $query->result();
	    } else {
	    	return false;
	    }
	}
	//license Info
	public function licenseInfo($license_id, $status=null){
        $this->db->select('*');
        $this->db->where('license_id', $license_id );
        if($status){ $this->db->where('status', $status ); }
        $query = $this->db->get('master_license');
        return $query->row();
    }
	public function addLicense($data){
		$this->db->insert( 'master_license', $data );
		return true; 
	}
	public function updateLicense($data, $license_id){
        $this->db->where( 'license_id', $license_id );
        $this->db->update( 'master_license', $data );
	}
	public function talentLicenseList($talent_id){
        $this->db->select('talent_license_list.*,license_name');
        $this->db->from('talent_license_list');
        $this->db->join('master_license', 'master_license.license_id = talent_license_list.license_id', 'left');
        $this->db->where('talent_license_list.talent_id', $talent_id );
        $this->db->where('master_license.status', 1);
        $this->db->where('talent_license_list.status', 1);
        $this->db->order_by("tl_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
}