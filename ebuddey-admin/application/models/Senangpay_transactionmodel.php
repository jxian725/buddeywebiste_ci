<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Senangpay_transactionmodel extends CI_Model {

	public $primaryKey      = 'payment_id';
    public $column_order    = array('payment_id','serviceID','order_id','guiderID','transaction_id','transaction_amount','pay_status'); 
    public $column_search   = array('payment_id','serviceID','order_id','guiderID','transaction_id','transaction_amount','pay_status');
    public $allcolumn_search= array('payment_id','serviceID','order_id','guiderID','transaction_id','transaction_amount','pay_status');
    public $order           = array('payment_id' => 'DESC'); // default order 
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
    	$this->db->select('senangpay_transaction.*,
                    service_date,pickup_time,transactionID,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName
                    ');
        $this->db->from('senangpay_transaction');
        $this->db->join('service_list', 'service_list.service_id = senangpay_transaction.serviceID');
        $this->db->join('guider', 'guider.guider_id = senangpay_transaction.guiderID', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = senangpay_transaction.travellerID', 'left');
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('senangpay_transaction.*,
                    service_date,pickup_time,transactionID,
                    guider.first_name AS guiderName,guider_charged,service_region_id,
                    traveller.first_name AS travellerName
                    ');
        $this->db->from('senangpay_transaction');
        $this->db->join('service_list', 'service_list.service_id = senangpay_transaction.serviceID');
        $this->db->join('guider', 'guider.guider_id = senangpay_transaction.guiderID', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = senangpay_transaction.travellerID', 'left');
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
    public function updateService($service_id, $data){
        
        $table  = $this->db->dbprefix('service_list');
        $this->db->where( 'service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function senangpayPaymentInfo($payment_id){
        
        $this->db->select('senangpay_transaction.*,service_list.*,traveller.first_name as requestorName, guider.first_name as guiderName,
                            service_region_id');
        $this->db->from('senangpay_transaction');
        $this->db->join('service_list', 'service_list.service_id = senangpay_transaction.serviceID','left');
        $this->db->join('traveller', 'traveller.traveller_id = service_list.service_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = service_list.service_guider_id','left');
        $this->db->where('payment_id', $payment_id );
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }
}
?>