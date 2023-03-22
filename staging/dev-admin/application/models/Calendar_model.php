<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {
	public function get_events($start, $end)
	{
	    return $this->db->where("start >=", $start)->where("end <=", $end)->get("events");
	}

	public function add_event($data)
	{
	    $this->db->insert("events", $data);
	}

	public function get_event($id)
	{
	    return $this->db->where("id", $id)->get("events");
	}

	public function update_event($id, $data)
	{
	    $this->db->where("id", $id)->update("events", $data);
	}

	public function delete_event($id)
	{
	    $this->db->where("id", $id)->delete("events");
	}
	public function currentDateEventLists($startDate=false) {

        $this->db->select('events.*,partner_name,address,city_id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        if($startDate){
        	$start_date = date('Y-m-d',strtotime($startDate));
        	$this->db->where('DATE(start)', "$start_date");
        }
        $this->db->order_by("start", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }else {
            return false; 
        }
    }
}