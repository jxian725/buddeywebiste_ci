<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BuskerspodReportmodel extends CI_Model {
    public $primaryKey      = 'id';
    public $column_order    = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'transactionID', 'host_id'); 
    public $column_search   = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'transactionID', 'host_id');
    public $allcolumn_search= array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'transactionID', 'host_id');
    public $order           = array('events.end' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }

    function get_datatables($where=false){
        $this->_get_datatables_query();
        
        $this->db->where('events.status',3,FALSE);
        $this->db->where( 'events.status !=', 5 );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false){
        $this->_get_datatables_query();
        
        $this->db->where('events.status',3,FALSE);
        $this->db->where( 'events.status !=', 5 );
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
        $this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        
        $this->db->where('events.status',3,FALSE);
        $this->db->where( 'events.status !=', 5 );
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query(){

        $this->db->select('events.*, partner_name, address, states.name as cityName');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                if($j == 8){
                    if($_POST['columns'][$j]['search']['value']==1){
                        $this->db->where( 'host_id !=', '' );
                    }else{
                        $this->db->where( 'host_id', 0 );
                    }
                }else{
                    $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
                }
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
}