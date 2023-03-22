<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );     
class Dashboardmodel extends CI_Model {

    public $primaryKey      = 'id';  
    public $column_order    = array('id','email','total','partnerFees'); 
    public $column_search   = array('id','email','total','partnerFees');
    public $allcolumn_search= array('id','email','total','partnerFees');
    public $order           = array('id' => 'DESC');

    public function __construct(){
        parent::__construct();
    }
    //get_datatables by events (20-02-2020)
    function get_datatables($where=false){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_datatables_query();
        $this->db->select('COUNT(guider.guider_id) AS total');
        $this->db->select_sum('events.partnerFees');
        $this->db->where('guider.status !=',4,FALSE);
        $this->db->where( 'events.status', 3 );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->group_by('guider.guider_id');
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_datatables_query();
        $this->db->select('COUNT(guider_id) AS total');
        $this->db->select_sum('events.partnerFees');
        $this->db->where('guider.status !=',4,FALSE);
        $this->db->where( 'events.status', 3 );
        $this->db->group_by('guider_id');
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
        $this->db->select('guider_id');
        $this->db->from('guider');
        $this->db->where('guider.status !=',4,FALSE);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query(){
        $this->db->select('guider.email, events.*');
        $this->db->from('guider');
        $this->db->join('events', 'events.host_id = guider.guider_id','left');
        // $talent_filter = $_POST['talent_filter'];
        // if($talent_filter == 1){
        //     $from = date('Y-m-d');
        //     $to   = date('Y-m-d');
        //     $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        // } elseif ($talent_filter == 2) {
        //     $from = date('Y-m').'-01';
        //     $to   = date('Y-m-d');
        //     $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        // } elseif ($talent_filter == 3) {
        //     $from = date('Y').'-01-01';
        //     $to   = date('Y-m-d');
        //     $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        // }

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
    function talentBookedInfo($host_id, $talent_filter) {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('COUNT(host_id) AS total, start, end');
        $this->db->select_sum('events.partnerFees');
        $this->db->where( 'host_id', $host_id );
        $this->db->where( 'events.status', 3 );
        if($talent_filter == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($talent_filter == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($talent_filter == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.paidDatetime) BETWEEN '".$from."' AND '".$to."'");
        }
        $this->db->group_by('host_id');
        $query = $this->db->get( 'events' );
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else { return false; }
    }
    //patner_total count (19-02-2020)
    function patner_total_count() {
        $this->db->select('*');
        $this->db->where( 'status !=', 4 );
        $query = $this->db->get( 'partner_list' );
        return $query->num_rows();
    }
    //web Request pending total count (20-02-2020)
    function webRequest_pending($status) {
        $this->db->select('*');
        $this->db->where( 'status', $status );
        $query = $this->db->get( 'activity_request' );
        return $query->num_rows();
    }
    
    //Feedback pending total count (20-02-2020)
    function talent_feedback($status) {
        $this->db->select('*');
        $this->db->where( 'is_read', $status );
        $query = $this->db->get( 'guider_feedback' );
        return $query->num_rows(); 
    }
    function fans_feedback($status) {
        $this->db->select('*');
        $this->db->where( 'is_read', $status );
        $query = $this->db->get( 'traveller_feedback' );
        return $query->num_rows();
    }
    //Latest patnerInfo (20-02-2020)
    function latest_patnerInfo() {
        $this->db->select( '*' );
        $this->db->order_by("partner_id", "desc");
        $query = $this->db->get( 'partner_list' );
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else { return false; }
    }
    //Latest guestInfo (20-02-2020)
    function latest_guestInfo() {
        $this->db->select( '*' );
        $this->db->order_by("traveller_id", "desc");
        $query = $this->db->get( 'traveller' );
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else { return false; }
    }
    //Latest hostInfo (20-02-2020)
    function latest_hostInfo() {
        $this->db->select( '*' );
        $this->db->order_by("guider_id", "desc");
        $query = $this->db->get( 'guider' );
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else { return false; }
    }

    function total_amount($filter_type='') {
        $this->db->select('SUM(partnerFees) AS availabelAmt');
        $this->db->from('events');
        $this->db->where( 'status !=', 0 );
        $this->db->where( 'status !=', 5 );
       if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $row = $query->row();
            return $row->availabelAmt;
        } else {
            return 0;
        }
    }
    function booked_amount($filter_type='') {
        $this->db->select('SUM(partnerFees) AS bookedAmt');
        $this->db->from('events');
        $this->db->where( 'events.status', 3 );
        if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $row = $query->row();
            return $row->bookedAmt;
        } else {
            return 0;
        }
    }
    function available_amount($filter_type='') {
        $this->db->select('SUM(partnerFees) AS availabelAmt');
        $this->db->from('events');
        $this->db->where( 'status !=', 0 );
        $this->db->where( 'status !=', 3 );
        $this->db->where( 'status !=', 5 );
       if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $row = $query->row();
            return $row->availabelAmt;
        } else {
            return 0;
        }
    }
    function total_hours($filter_type='') {
        $totalAvailableHours = '00:00:00';
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('TIMEDIFF(end,start) as totalHours');
        $this->db->where( 'status !=', 0 );
        $this->db->where( 'status !=', 5 );
        if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $this->db->group_by('id');
        $query = $this->db->get('events');
        if($query->num_rows()){
            $results = $query->result();
            foreach ($results as $key => $value) {
                $totalAvailableHours += $this->convertMinutes($value->totalHours);
            }
            return $this->hoursandmins($totalAvailableHours);
        }else{
            return 0;
        }
    }
    function booked_hours($filter_type='') {
        $totalBookedHours = '00:00:00';
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('TIMEDIFF(end,start) as totalHours');
        $this->db->where( 'status', 3 );
        if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $this->db->group_by('id');
        $query = $this->db->get('events');
        if($query->num_rows()){
            $results = $query->result();
            foreach ($results as $key => $value) {
                $totalBookedHours += $this->convertMinutes($value->totalHours);
            }
            return $this->hoursandmins($totalBookedHours);
        }else{
            return 0;
        }
    }
    
    function available_hours($filter_type='') {
        $totalAvailableHours = '00:00:00';
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('TIMEDIFF(end,start) as totalHours');
        $this->db->where( 'status !=', 0 );
        $this->db->where( 'status !=', 3 );
        $this->db->where( 'status !=', 5 );
        if($filter_type == 1){
            $from = date('Y-m-d');
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 2) {
            $from = date('Y-m').'-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        } elseif ($filter_type == 3) {
            $from = date('Y').'-01-01';
            $to   = date('Y-m-d');
            $this->db->where("DATE(events.start) BETWEEN '".$from."' AND '".$to."'");
        }
        $this->db->group_by('id');
        $query = $this->db->get('events');
        if($query->num_rows()){
            $results = $query->result();
            foreach ($results as $key => $value) {
                $totalAvailableHours += $this->convertMinutes($value->totalHours);
            }
            return $this->hoursandmins($totalAvailableHours);
        }else{
            return 0;
        }
    }

   // Latest Activity  
    function guider_activity(){
        $from = date("Y-m-d");
        $to   = date('Y-m-d', strtotime('-10 days'));

        $this->db->select('*');
        $this->db->where('DATE(created_on) <=', $from);
        $this->db->where('DATE(created_on) >=', $to);
        $this->db->order_by("guider_id", "desc");
        $this->db->limit(2);
        $query = $this->db->get('guider');
        return $query->result();
    }
    function talent_latest_booking( $status ){
        $this->db->select('events.*,guider.first_name,partner_list.partner_name');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('guider', 'guider.guider_id = events.host_id','left');
        $this->db->where('events.status', $status );
        //$this->db->where('DATE(paidDatetime) <=', date("Y-m-d"));
        $this->db->order_by("paidDatetime", "desc");
        $this->db->limit(2);
       $query = $this->db->get();
        return $query->result();
    }
    function fans_activity(){
        $from = date("Y-m-d");
        $to   = date('Y-m-d', strtotime('-10 days'));

        $this->db->select('*');
        $this->db->where('DATE(created_on) <=', $from);
        $this->db->where('DATE(created_on) >=', $to);
        $this->db->order_by("traveller_id", "desc");
        $this->db->limit(2);
        $query = $this->db->get('traveller');
        return $query->result();
    }
    function fans_feedback_joinned(){
        $this->db->select('*');
        $this->db->order_by("support_id", "desc");
        $this->db->limit(2);
        $query = $this->db->get('traveller_feedback');
        return $query->result();
    }
    // bar Chart :- (23-02-2020)
    function totalSaleForChart($monthYear) {
        $amount = array();
        
        foreach ($monthYear as $key => $value) {
            $this->db->select('SUM(partnerFees) as amount');
            $this->db->where('MONTH(start)', $key);
            $this->db->where('YEAR(start)', $value);
            $this->db->where('status', 3);
            $query = $this->db->get('events');
            if($query->num_rows() > 0){
                $value = $query->row();
                $amount[$key] = ($value->amount)? $value->amount : '';
            }else{
                $amount[$key] = '';
            }
        }
        return array_values($amount);
    }
    function totalBookedHoursForChart($monthYear) {
        $amount = array();
        
        foreach ($monthYear as $key => $value) {
            $this->db->select('*');
            $this->db->where('MONTH(start)', $key);
            $this->db->where('YEAR(start)', $value);
            $this->db->where('status', 3);
            $query = $this->db->get('events');
            $amount[$key] = $query->num_rows();
        }
        return array_values($amount);
    }
    // bar Chart :- (26-02-2020)
    function oneMonthBookedHoursForChart($oneMonth) {
        $amount = array();

        foreach ($oneMonth as $key => $value) {
            $this->db->select('*');
            $this->db->where('DATE(start)', $value);
            $this->db->where('status', 3);
            $query = $this->db->get('events');
            $amount[$key] = $query->num_rows();
        }
        return array_values($amount);
    }
    function oneMonthSaleForChart($oneMonth) {
        $amount = array();
        
        foreach ($oneMonth as $key => $value) {
            $this->db->select('SUM(partnerFees) as Amount');
            $this->db->where('DATE(start)', $value);
            $this->db->where('status', 3);
            $query = $this->db->get('events');
            if($query->num_rows() > 0){
                $value = $query->row();
                $amount[$key] = ($value->Amount)? $value->Amount : '';
            }else{
                $amount[$key] = '';
            }
        }
        return array_values($amount);
    }
    function oneDayBookedHoursForChart($oneDay){
      $amount = array();

      foreach ($oneDay as $key => $value) {
        $this->db->select('*');
        $this->db->where('DATE(start)', $value);
        $this->db->where('status', 3);
        $query = $this->db->get('events');
        $amount[$key] = $query->num_rows();
      }  
        return array_values($amount);
    }
    function oneDaySaleForChart($oneDay) {
        $amount = array();
        
        foreach ($oneDay as $key => $value) {
            $this->db->select('SUM(partnerFees) as Amount');
            $this->db->where('DATE(start)', $value);
            $this->db->where('status', 3);
            $query = $this->db->get('events');
            if($query->num_rows() > 0){
                $value = $query->row();
                $amount[$key] = ($value->Amount)? $value->Amount : '';
            }else{
                $amount[$key] = '';
            }
        }
        return array_values($amount);
    }
    function convertMinutes($time){
        $time = explode(':', $time);
        return ($time[0]*60) + ($time[1]) + ($time[2]/60);
    }
    function hoursandmins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return 0;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
    
 } 

 