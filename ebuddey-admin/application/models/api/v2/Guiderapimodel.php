<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guiderapimodel extends CI_Model {
     public function __construct(){
        parent::__construct();
     }

     public function partnerInfo( $partner_id ){
        $this->db->select( '*' );
        $this->db->where( 'partner_id', $partner_id );
        $query  = $this->db->get( 'partner_list' );
        return $query->row();
    }

    public function licenseInfo($license_id){
        $this->db->select('*');
        $this->db->where('license_id', $license_id );
        $this->db->where('status', 1 );
        $query = $this->db->get('master_license');
        return $query->row();
    }

    public function talentLicenseInfo($talent_id, $license_id){
        $this->db->select('*');
        $this->db->where('talent_id', $talent_id );
        $this->db->where('license_id', $license_id );
        $this->db->where('status', 1 );
        $query = $this->db->get('talent_license_list');
        return $query->row();
    }

    //Guider Info
    public function guiderInfoByUuid( $guider_id ){
        $this->db->select( 'guider.*,states.name as cityName, specialization.specialization as categoryName, country_short_code, country_name, country_currency_code, country_currency_symbol, country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        $this->db->join('states', 'states.id = guider.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = guider.skills_category','left');
        $this->db->where('guider_id', $guider_id);
        $this->db->where( 'guider.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            return $query->row();
        }else{
            return false; 
        }
    }
}