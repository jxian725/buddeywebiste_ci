<?php
class Partnermodel extends CI_Model {   
 public $primaryKey      = 'id';
    public $column_order    = array('events.id','partner_list.partner_id','states.name','start','start','end', 'partnerFees', 'events.status'); 
    public $column_search   = array('events.id','partner_list.partner_id','states.name','start','start','end', 'partnerFees', 'events.status');
    public $allcolumn_search= array('events.id','partner_list.partner_id','states.name','start','start','end', 'partnerFees', 'events.status');
    public $order           = array('events.end' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }  
	// New Task insert 
	function insertRegister( $data ) { 
        $this->db->insert( 'venue_partners', $data );
        return $this->db->insert_id();
    }
    function partnerInfo($venuepartnerId) { 
        $this->db->select('*');
        $this->db->where('venuepartnerId', $venuepartnerId );
        $query = $this->db->get('venue_partners');
        return $query->row();
    }
    function profileInfo($venuepartnerId) { 
        $this->db->select('venue_partners.*,states.name as cityName');
        $this->db->from('venue_partners');
        $this->db->join('states', 'states.id = venue_partners.city','left');
        $this->db->where('venue_partners.venuepartnerId', $venuepartnerId );
        $query = $this->db->get();
        return $query->row();
    }   
    public function updatePartner($user_id, $data){
    	$table  = $this->db->dbprefix('venue_partners');
    	$this->db->where( 'venuepartnerId', $user_id );
		$this->db->update( $table, $data );
		return true; 
	}
    public function EmailExists($email){
        $this->db->where('email', trim($email));
        $this->db->where('status != ',4,FALSE);
        $query = $this->db->get('venue_partners');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function ExistsPartnerEmail($email){
        $this->db->where('email', trim($email));
        $this->db->where('status != ',4,FALSE);
        $query = $this->db->get('venue_partners');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    function updatePartnerInfo( $venuepartnerId, $data ) {
        $table  = $this->db->dbprefix( 'venue_partners' );
        $this->db->where( 'venuepartnerId', $venuepartnerId );
        $this->db->update( $table, $data );
        return true;
    }
    //Buskerspod tabel data
    function get_datatables($partnerID, $where=false, $startDate=false, $endDate=false){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where_in('events.partner_id', $partnerID);
        $this->db->where('events.status != ',5,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($partnerID, $where=false, $startDate=false, $endDate=false){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where_in('events.partner_id', $partnerID);
        $this->db->where('events.status != ',5,FALSE);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($partnerID, $where=false, $startDate=false, $endDate=false){
        $this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('buskerpod_review', 'buskerpod_review.event_id = events.id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }

        $this->db->where_in('events.partner_id',$partnerID);
        $this->db->where('events.status != ',5,FALSE);
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query(){

        $this->db->select('events.*, partner_name, address, is_like, is_dislike, review_status, states.name as cityName');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('buskerpod_review', 'buskerpod_review.event_id = events.id', 'left');
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
    public function guiderInfo($guider_id){
        
        $this->db->select('*');
        $this->db->where('guider_id', $guider_id );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
     public function buskerspodInfo($id){
        $this->db->select('*');
        $this->db->where('id', $id );
        $query = $this->db->get('events');
        $row = $query->row();
        return $row;
    }
     function state_list($country_id=false, $status=false) {

        $this->db->select('*');
        $this->db->from('states');
        $this->db->where( 'country_id', $country_id );
        if($status){ $this->db->where( 'status', $status ); }
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $stateLists = $query->result();
            return $stateLists;
        }else {
            return false; 
        }
    }
    public function partnerList(){
        $this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        $this->db->where( 'partner_list.status', 1 );
        $this->db->order_by("partner_id", "desc");
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }
    public function partner_List($partnerID){
        $this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        $this->db->where( 'partner_list.status', 1 );
        $this->db->where_in( 'partner_list.partner_id', $partnerID);
        $this->db->order_by("partner_id", "desc");
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }
    function partner_Info( $partnerID ) {
        $this->db->where( 'partner_id', $partnerID );
        $query      = $this->db->get( 'partner_list' );
        $numRows    = $query->num_rows();
        if( $numRows > 0 ) {
            return $query->row();
        } else { return false; }
    }    
    function searchCityLists($search=''){
        $this->db->where( 'status', 1 );
        $this->db->where( 'country_id', 132 );
        if($search){ $this->db->like( 'name', $search ); }
        $this->db->order_by( 'name' , 'asc' );
        $query = $this->db->get( 'states' );
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else { return false; } 
    }
    //Ratings and Reviews
     function updateReviews( $id, $data ) { 
        $table  = $this->db->dbprefix( 'buskerpod_review' );
        $this->db->where( 'event_id', $id );
        $this->db->update( $table, $data );
        return true;
    }
    function addReviews( $data ) {
        $table  = $this->db->dbprefix( 'buskerpod_review' );
        $this->db->insert( $table, $data );
        $this->db->insert_id();
        return true;
    }
    //ReviewInfo
    public function reviewInfo($id){
        $this->db->select('*');
        $this->db->where('event_id', $id );
        $query = $this->db->get('buskerpod_review');
        $row = $query->row();
        return $row;
    }
    //Add Feedback
    function addFeedback( $data ) {
        $table  = $this->db->dbprefix( 'venuepartner_feedback' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
     //Feedback Info
    public function venuepartnerFeedbackInfo($venuepartnerId, $status){
        $this->db->select('venuepartner_feedback.*, venue_partners.company_name AS venuepartner_name');
        $this->db->from('venuepartner_feedback');
        $this->db->where('venuepartner_id', $venuepartnerId );
        $this->db->where_in('venuepartner_feedback.feedback_status', $status);
        $this->db->join('venue_partners', 'venue_partners.venuepartnerId = venuepartner_feedback.venuepartner_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }
}
?>    