<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Qrscandonatemodel extends CI_Model {

    public $column_order    = array('payment_id','pay_createdon','guiderID','transaction_id','guider.first_name','fullName','phoneNumber','qrscan_donate_users.email','sub_total','service_fees','paid_to_guider'); 
    public $column_search   = array('payment_id','pay_createdon','guiderID','transaction_id','guider.first_name','fullName','phoneNumber','qrscan_donate_users.email','sub_total','service_fees','paid_to_guider');
    public $allcolumn_search= array('payment_id','pay_createdon','guiderID','transaction_id','guider.first_name','fullName','phoneNumber','qrscan_donate_users.email','sub_total','service_fees','paid_to_guider');
    public $order           = array('payment_id' => 'DESC');
    public function __construct(){
        parent::__construct();
    }

	function get_datatables($where=false){
        $this->_get_datatables_query();
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType','scan_payment');
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->group_by('payment_id');
        $query = $this->db->get();
        return $query->result();
    }
	function count_filtered($where=false){
        $this->_get_datatables_query();
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType','scan_payment');
        $this->db->group_by('payment_id');
        $query 	= $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
    	$this->db->select('payment_id');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id');
        $this->db->join('guider', 'guider.guider_id = senangpay_transaction.guiderID', 'left');
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType','scan_payment');
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query(){

        $this->db->select('senangpay_transaction.*,fullName,qrscan_donate_users.email,phoneNumber,anonymous,
                    guider.first_name AS guiderName,
                    paid_to_guider, service_fees, sub_total, order_id,
                    pay_createdon,transaction_id
                    ');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id');
        $this->db->join('guider', 'guider.guider_id = senangpay_transaction.guiderID', 'left');
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
    public function completedBookings_export($data){
        
        $this->db->select('senangpay_transaction.*,fullName,qrscan_donate_users.email,phoneNumber,anonymous,
                    guider.first_name AS guiderName,
                    paid_to_guider, service_fees, sub_total, order_id,
                    pay_createdon,transaction_id
                    ');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id');
        $this->db->join('guider', 'guider.guider_id = senangpay_transaction.guiderID', 'left');
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType','scan_payment');
        if($data['start_date'] && $data['end_date']){
            $this->db->where('DATE(pay_createdon) >=', date('Y-m-d', strtotime($data['start_date'])));
            $this->db->where('DATE(pay_createdon) <=', date('Y-m-d', strtotime($data['end_date'])));
        }
        if($data['guider_id']){ $this->db->where( 'guiderID', $data['guider_id'] ); }
        $this->db->group_by('payment_id');
        $query = $this->db->get();
        return $query->result();
    }
}
?>