<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Completedbookingsmodel extends CI_Model {

    public $column_order    = array('journey_id','pay_createdon','guiding_speciality','service_price_type_id','transaction_id','traveller.first_name','traveller.gender','traveller.email','additional_information','guider_charged','journey_id','journey_id','journey_id'); 
    public $column_search   = array('journey_id','pay_createdon','guiding_speciality','service_price_type_id','transaction_id','traveller.first_name','traveller.gender','traveller.email','additional_information','guider_charged','journey_id','journey_id','journey_id');
    public $allcolumn_search= array('journey_id','pay_createdon','guiding_speciality','service_price_type_id','transaction_id','traveller.first_name','traveller.gender','traveller.email','additional_information','guider_charged','journey_id','journey_id','journey_id');
    public $order           = array('journey_id' => 'DESC');

    public $column_order2    = array('journey_id','jny_service_id','journey_list.createdon','jny_traveller_id','service_date','pickup_time','number_of_person','jny_guider_id','service_region_id','jny_status'); 
    public $column_search2   = array('journey_id','jny_service_id','journey_list.createdon','jny_traveller_id','service_date','pickup_time','number_of_person','jny_guider_id','service_region_id','jny_status');
    public $allcolumn_search2= array('journey_id','jny_service_id','journey_list.createdon','jny_traveller_id','service_date','pickup_time','number_of_person','jny_guider_id','service_region_id','jny_status');
    public $order2           = array('journey_id' => 'DESC');
    public function __construct(){
        parent::__construct();
    }

	function get_datatables($where=false){
        $this->_get_datatables_query();
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id != ',3,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->group_by('journey_id');
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false){
        $this->_get_datatables_query();
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id != ',3,FALSE);
        $this->db->group_by('journey_id');
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
    	$this->db->select('journey_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id != ',3,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_id,service_region_id,number_of_person,service_price_type_id,additional_information,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName, traveller.email as temail, traveller.gender as tgender,
                    paid_to_guider, service_fees, sub_total, order_id,
                    pay_createdon,transaction_id,
                    guider_activity_list.activity_id, guiding_speciality
                    ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('guider_activity_list', 'journey_list.jny_activity_id = guider_activity_list.activity_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
		/* Individual column filtering */
	    $j = 0;
        foreach ($this->column_search as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
	            $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
	        }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables2($where=false){
        $this->_get_datatables_query2();
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id',3,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered2($where=false){
        $this->_get_datatables_query();
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id',3,FALSE);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all2($where=false){
        $this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName
                    ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id',3,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query2(){

        $this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName
                    ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search2 as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
            }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search2 as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search2) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order2)){
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function serviceInfo($service_id){
        
        $this->db->select('*');
        $this->db->where('service_id', $service_id );
        $query = $this->db->get('service_list');
        $row = $query->row();
        return $row;
    }
    public function updateService($service_id, $data){
        
        $table  = $this->db->dbprefix('service_list');
        $this->db->where( 'service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function completedBookings_export($data){
        
        $this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName,
                    paid_to_guider, service_fees, sub_total, order_id
                    ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('jny_status != ',4,FALSE);
        $this->db->where('service_price_type_id != ',3,FALSE);
        if($data['trip_id']){ $this->db->where( 'jny_service_id', $data['trip_id'] ); }
        if($data['traveller_id']){ $this->db->where( 'jny_traveller_id', $data['traveller_id'] ); }
        if($data['guider_id']){ $this->db->where( 'jny_guider_id', $data['guider_id'] ); }
        $query = $this->db->get();
        return $query->result();
    }
}
?>