<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pendingrequestmodel extends CI_Model {

	public $primaryKey 		= 'service_id';
    public $column_order 	= array('service_id','service_id','createdon','service_traveller_id','service_date','pickup_time','number_of_person','service_region_id','guider_charged','service_id','service_id','service_id','service_list.status'); 
    public $column_search 	= array('service_id','service_id','createdon','service_traveller_id','service_date','pickup_time','number_of_person','service_region_id','guider_charged','service_id','service_id','service_id','service_list.status');
    public $allcolumn_search= array('service_id','service_id','createdon','service_traveller_id','service_date','pickup_time','number_of_person','service_region_id','guider_charged','service_id','service_id','service_id','service_list.status');
    public $order 			= array('service_id' => 'DESC'); // default order 
	public function __construct(){
		parent::__construct();
	}

	function get_datatables($where=false, $host_id){
        $this->_get_datatables_query();
        $this->db->where('service_list.status != ',4,FALSE);
        $this->db->where('service_list.status != ',3,FALSE);
        $this->db->where('service_guider_id', $host_id );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false, $host_id){
        $this->_get_datatables_query();
        $this->db->where('service_list.status != ',4,FALSE);
        $this->db->where('service_list.status != ',3,FALSE);
        $this->db->where('service_guider_id', $host_id );
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false, $host_id){
    	$this->db->select('service_id');
        $this->db->from('service_list');
        $this->db->join('guider', 'guider.guider_id = service_list.service_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = service_list.service_traveller_id', 'left');
        $this->db->where('service_list.status != ',4,FALSE);
        $this->db->where('service_list.status != ',3,FALSE);
        $this->db->where('service_guider_id', $host_id );
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('
                    service_list.*,
                    guider.first_name AS guiderName,traveller.first_name AS travellerName
                    ');
        $this->db->from('service_list');
        $this->db->join('guider', 'guider.guider_id = service_list.service_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = service_list.service_traveller_id', 'left');
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
    public function serviceInfo($service_id){
        $this->db->select('*');
        $this->db->where('service_id', $service_id );
        $query = $this->db->get('service_list');
        $row = $query->row();
        return $row;
    }
    public function travellerDeviceTokenList($traveller_id){
        $this->db->reset_query();
        $this->db->select('*');
        $this->db->from('traveller_device_info');
        $this->db->where( 'traveller_id', $traveller_id );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ){
            $result = $query->result();
            return $result;
        }
    }
    public function updateService($service_id, $data){
        
        $table  = $this->db->dbprefix('service_list');
        $this->db->where( 'service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function insertJourney( $data ){
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->insert( $table, $data );
    }
    function updateJourney( $data, $service_id ) {
        $table  = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function journeyInfo( $service_id ){
        $this->db->select( '*' );
        $this->db->where( 'jny_service_id', "$service_id" );
        $query  = $this->db->get( 'journey_list' );
        $row    = $query->row();
        return $row;
    }
}
?>