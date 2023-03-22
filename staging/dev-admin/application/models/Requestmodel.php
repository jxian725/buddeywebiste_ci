<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requestmodel extends CI_Model {

    public $column_order 	= array('activity_request_id','full_name','mobile_no','email','specialization.specialization','states.name','budget','confirm_budget','occasion','venue','time_hour','other_info','payment_type','status','status'); 
    public $column_search 	= array('activity_request_id','full_name','mobile_no','email','specialization.specialization','states.name','budget','confirm_budget','occasion','venue','time_hour','other_info','payment_type','status','status');
    public $allcolumn_search= array('activity_request_id','full_name','mobile_no','email','specialization.specialization','states.name','budget','confirm_budget','occasion','venue','time_hour','other_info','payment_type','status','status');
    public $order 			= array('activity_request_id' => 'DESC'); // default order 
	public function __construct(){
		parent::__construct();
	}

	function get_datatables($where=false){
        $this->_get_datatables_query();
        $this->db->where('activity_request.status != ',4,FALSE);
        $this->db->where('activity_request.status != ',1,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false){
        $this->_get_datatables_query();
        $this->db->where('activity_request.status != ',4,FALSE);
        $this->db->where('activity_request.status != ',1,FALSE);
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
    	$this->db->select('activity_request_id');
        $this->db->from('activity_request');
        $this->db->join('states', 'states.id = activity_request.city_id', 'left');
        $this->db->join('specialization', 'specialization.specialization_id = activity_request.skill_id', 'left');
        $this->db->where('activity_request.status != ',4,FALSE);
        $this->db->where('activity_request.status != ',1,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('
                    activity_request.*,
                    specialization.specialization AS skillName,
                    states.name AS cityName
                    ');
        $this->db->from('activity_request');
        $this->db->join('states', 'states.id = activity_request.city_id', 'left');
        $this->db->join('specialization', 'specialization.specialization_id = activity_request.skill_id', 'left');
        $this->db->where('activity_request.status != ',4,FALSE);
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
    public function requestInfo($activity_request_id){
        
        $this->db->select('*');
        $this->db->where('activity_request_id', $activity_request_id );
        $query = $this->db->get('activity_request');
        $row = $query->row();
        return $row;
    }
    public function updateRequest($request_id, $data){
        
        $table  = $this->db->dbprefix('activity_request');
        $this->db->where( 'activity_request_id', $request_id );
        $this->db->update( $table, $data );
        return true;
    }
}
?>