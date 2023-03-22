<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Completedpayoutmodel extends CI_Model {

	public $primaryKey      = 'pt_id';
    public $column_order    = array('guiderID','guiderID'); 
    public $column_search   = array('guiderID','guiderID');
    public $allcolumn_search= array('guiderID','guiderID');
    public $order           = array('pt_id' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }

	function get_datatables($where=false){
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false){
        $this->_get_datatables_query();
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
    	$this->db->select('payout_transaction.*, guider.first_name AS guiderName');
        $this->db->from('payout_transaction');
        $this->db->join('guider', 'guider.guider_id = payout_transaction.guiderID', 'left');
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('payout_transaction.*, guider.first_name AS guiderName');
        $this->db->from('payout_transaction');
        $this->db->join('guider', 'guider.guider_id = payout_transaction.guiderID', 'left');
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
    public function journeyAllInfo($journey_id){

        $this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,rate_per_person,service_providing_region,
                    traveller.first_name AS travellerName,
                    senangpay_transaction.sub_total as guiderPayment,percentage_amount
                    ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('journey_id', $journey_id );
        $query = $this->db->get();
        return $query->row();
    }
    public function payouttransInfo($pt_id){
        
        $this->db->select('*');
        $this->db->where('pt_id', $pt_id );
        $query = $this->db->get('payout_transaction');
        $row = $query->row();
        return $row;
    }
}
?>