<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Buskerspodmodel extends CI_Model {
    public $primaryKey      = 'id';
    public $column_order    = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status'); 
    public $column_search   = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $allcolumn_search= array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $order           = array('events.end' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }

    function get_datatables($where=false, $startDate=false, $endDate=false){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.status != ',5,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false, $startDate=false, $endDate=false){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.status != ',5,FALSE);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false, $startDate=false, $endDate=false){
        $this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.status != ',5,FALSE);
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
                    // if($_POST['columns'][$j]['search']['value']==1){
                    //     $this->db->where( 'host_id !=', '' );
                    // }else{
                    //     $this->db->where( 'host_id', 0 );
                    // }
                    $this->db->where( $search, $_POST['columns'][$j]['search']['value'] );
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
    public function buskerspodLists( $user_search = '', $order_by = '' ){

        $this->db->select('*');
        $this->db->where( 'status', 1 );
        $this->db->where('events.status != ',5,FALSE);
        //If Condition user search
        if( $user_search ) {
            $this->db->where( "( full_name LIKE '%". $user_search ."%' OR other_name LIKE '%". $user_search ."%' OR email LIKE '%". $user_search ."%' )" );
        }
        //If Condition for order by
        if( $order_by == 1 ) {
            $this->db->order_by( 'full_name', 'asc' );
        } else if( $order_by == 2 ) {
            $this->db->order_by( 'full_name', 'desc' );
        } else if( $order_by == 3 ) {
            $this->db->order_by( 'email', 'asc' );
        } else if( $order_by == 4 ) {
            $this->db->order_by( 'email', 'desc' );
        } else {
            $this->db->order_by( 'id', 'desc' );
        }
        $query = $this->db->get( 'events' );
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }
    public function buskerspodInfo($id){
        $this->db->select('*');
        $this->db->where('id', $id );
        $query = $this->db->get('events');
        $row = $query->row();
        return $row;
    }
    public function addBuskerspod($data){
        $table  = $this->db->dbprefix('events');
        $this->db->insert( $table, $data );
        return true; 
    }
    public function updateBuskerspod( $id, $data ){
        $table2 = $this->db->dbprefix('events');
        $this->db->where( 'id', $id );
        $this->db->update( $table2, $data );
    }
    function deleteBuskerspod( $id ) {
        $this->db->where( 'id', $id );
        $this->db->delete( 'events' );
        return true;
    }
    public function event_list($id=false){
        $this->db->select('*');
        $this->db->from('event');
        $this->db->where( 'id', $id );
        $this->db->where('event.status != ',5,FALSE);
        $this->db->order_by( 'start_date', 'asc' );
        $query = $this->db->get();
        return $query->result();
    }
    public function updateEvent( $id, $data ){
        $table2 = $this->db->dbprefix('event');
        $this->db->where( 'id', $id );
        $this->db->update( $table2, $data );
    }
    function deleteEvent( $id ) {
        $this->db->where( 'id', $id );
        $this->db->delete( 'event' );
        return true;
    }
    public function checkExistPackageInfo($partner_id, $city_id, $start, $end){
        $this->db->select('*');
        $this->db->where('partner_id', $partner_id );
        $this->db->where('city_id', $city_id );
        $this->db->where('start', $start );
        $this->db->where('end', $end );
        $query = $this->db->get('events');
        $row = $query->row();
        return $row;
    }
}