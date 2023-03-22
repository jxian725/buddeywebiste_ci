<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pendingpayoutmodel extends CI_Model {

	public $primaryKey      = 'jny_guider_id';
    public $column_order    = array('jny_guider_id','jny_guider_id'); 
    public $column_search   = array('jny_guider_id','jny_guider_id');
    public $allcolumn_search= array('jny_guider_id','jny_guider_id');
    public $order           = array('jny_guider_id' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }

	function get_datatables($where=false){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_datatables_query();
        $this->db->where('guider_payout', 'N' );
        $this->db->where('jny_status',3,FALSE);
        $this->db->group_by('jny_guider_id');
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_datatables_query();
        $this->db->where('guider_payout', 'N' );
        $this->db->where('jny_status',3,FALSE);
        $this->db->group_by('jny_guider_id');
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
    	$this->db->select('journey_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,
                    senangpay_transaction.sub_total as guiderPayment,percentage_amount,transaction_amount
                    ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id AND (service_price_type_id != 3)');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->where('guider_payout', 'Y' );
        $this->db->where('jny_status',3,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('journey_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName, COUNT(jny_service_id) AS noTrip, SUM(sub_total) AS payoutAmt,
                    SUM(percentage_amount) AS percentageAmt,SUM(transaction_amount) AS transactionAmt
                    ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id AND (service_price_type_id != 3)');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
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
    private function guiderPendingPayout($guider_id){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select(' COUNT(jny_service_id) AS noTrip, 
                            SUM(sub_total) AS payoutAmt,
                            SUM(percentage_amount) AS percentageAmt,
                            SUM(transaction_amount) AS transactionAmt
                        ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where('guider_payout', 'N' );
        $this->db->where('jny_status',3,FALSE);
        $this->db->where('jny_guider_id',"$guider_id");
        $this->db->group_by('jny_guider_id');
        $query = $this->db->get();
    }
}
?>