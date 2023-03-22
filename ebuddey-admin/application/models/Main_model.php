<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Main_model extends CI_Model {


	public function __construct() {
		parent::__construct(); 
	}

	// Fetch records
	public function getData($rowno,$rowperpage,$startDate,$endDate,$partnerid){
		
		$this->db->select('events.*, partner_name, address, states.name as cityName');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
		if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
		if($partnerid)
		{
			 $this->db->where('events.partner_id',$partnerid,FALSE);
		}
		$this->db->where('events.status != ',5,FALSE);
		$this->db->order_by( 'events.id', 'desc' );
        $this->db->limit($rowperpage, $rowno);  
		$query = $this->db->get();
       	
		return $query->result_array();
	}

	// Select total records
    public function getrecordCount($startDate,$endDate,$partnerid) {

    	$this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
		if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
		if($partnerid)
		{
			 $this->db->where('events.partner_id',$partnerid,FALSE);
		}
        $this->db->where('events.status != ',5,FALSE);
		$this->db->order_by( 'events.id', 'desc' );
        $query = $this->db->get();
        return $query->num_rows();
      
    }

}