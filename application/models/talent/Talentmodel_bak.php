<?php
class Talentmodel extends CI_Model {   
 public $primaryKey      = 'id';
    public $column_order    = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status'); 
    public $column_search   = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $allcolumn_search= array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $order           = array('events.end' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct(); 
    }  
	// New Task insert 
	function insertRegister( $data ) { 
        $this->db->insert( 'guider', $data );
        return $this->db->insert_id();
    }
    public function updateTalent($guider_id, $data){
        $this->db->where('guider_id', $guider_id );
        $this->db->update( 'guider', $data );
        return true; 
    }
    public function guiderInfo($guider_id){
        
        $this->db->select('*');
        $this->db->where('guider_id', $guider_id );
        $query = $this->db->get('guider');
        return $query->row();
    }
    function imageLists( $guider_id ){ 
        $this->db->select('*');
        $this->db->where('guider_id', $guider_id );
        $query = $this->db->get('guider');
        return $query->result();
    }
    function urlLists( $guider_id ){
        $this->db->select('*');
        $this->db->where('talent_id', $guider_id );
        $query = $this->db->get('talent_video_list');
        return $query->result();
    }
    function talentMobileExists($phone_number) {
        $this->db->select('guider_id');
        $this->db->where('phone_number', $phone_number );
        $query = $this->db->get('guider');
        return $query->row();
    }
    function talentInfo($guider_id) {
        $this->db->select('guider.*, states.name as cityName, specialization.specialization as categoryName');
        $this->db->from('guider');
        $this->db->join('states', 'states.id = guider.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = guider.skills_category','left');
        $this->db->where('guider.guider_id', $guider_id ); 
        $query = $this->db->get();
        return $query->row();
    }
    //Rating and Reviews
    function reviewInfo( $status, $guider_id){
        $this->db->select('buskerpod_review.*,venue_partners.company_name,partner_list.partner_name');
        $this->db->from('buskerpod_review');
        $this->db->join('partner_list', 'partner_list.partner_id = buskerpod_review.partner_id', 'left');
        $this->db->join('venue_partners', 'venue_partners.venuepartnerId = buskerpod_review.venuepartner_id','left');
        $this->db->where('buskerpod_review.review_status', $status );
        $this->db->where('buskerpod_review.talent_id', $guider_id);
        $query = $this->db->get();
        return $query->result();
    }
    //Rating and Reviews
    function ratingInfo($guider_id) {
        $this->db->select('SUM(is_like) AS is_Like,SUM(is_dislike) AS dis_Like');
        $this->db->from('buskerpod_review');
        $this->db->where( 'buskerpod_review.talent_id', $guider_id);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else {
            return 0;
        }
    } 
    public function newsletter_lists(){
        $this->db->select('*');
        $this->db->where( 'status !=', 4 );
        $this->db->order_by("newsletter_id", "desc");
        $this->db->limit(6);
        $query = $this->db->get( 'newsletter' );
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    } 
    
    public function addUrl($data){
        $table  = $this->db->dbprefix('talent_video_list');
        $this->db->insert( $table, $data );
        return true; 
    }
    function profileInfo($venuepartnerId) { 
        $this->db->select('venue_partners.*,states.name as cityName');
        $this->db->from('venue_partners');
        $this->db->join('states', 'states.id = venue_partners.city','left');
        $this->db->where('venue_partners.venuepartnerId', $venuepartnerId );
        $query = $this->db->get();
        return $query->row();
    }   
    public function EmailExists($email){
        $this->db->where('email', trim($email));
        $this->db->where('status != ',4,FALSE);
        $query = $this->db->get('guider');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function ExistsPartnerEmail($email){
        $this->db->where('email', trim($email));
        $this->db->where('status != ',4,FALSE);
        $query = $this->db->get('guider');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    function updatePartnerInfo( $venuepartnerId, $data ) {
        $table  = $this->db->dbprefix( 'venue_partners' );
        $this->db->where( 'venuepartnerId', $venuepartnerId );
        $this->db->update( $table, $data );
        return true;
    }
    function partnerInfo($partner_id) { 
        $this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        $this->db->where('partner_id', $partner_id);
        $query = $this->db->get();
        return $query->row();
    }

    //Buskerspod tabel data
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
        $this->db->join('buskerpod_review', 'buskerpod_review.event_id = events.id', 'left');
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

     public function packageInfo($id){
        $this->db->select('*');
        $this->db->where('id', $id );
        $query = $this->db->get('events');
        return $query->row();
    }
     function state_list($country_id=false, $status=false) {

        $this->db->select('*');
        $this->db->from('states');
        $this->db->where( 'country_id', $country_id );
        if($status){ $this->db->where( 'status', $status ); }
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }else {
            return false; 
        }
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
    //Add Feedback
    function addFeedback( $data ) {
        $this->db->insert( 'guider_feedback', $data );
        return $this->db->insert_id();
    }
     //Feedback Info
    public function talentFeedbackLists($fb_guider_id, $status){
        $this->db->select('guider_feedback.*, first_name, last_name, phone_number');
        $this->db->from('guider_feedback');
        $this->db->join('guider', 'guider.guider_id = guider_feedback.fb_guider_id', 'left');
        $this->db->where('fb_guider_id', $fb_guider_id );
        $this->db->where_in('guider_feedback.feedback_status', $status);
        $query = $this->db->get();
        return $query->result();
    }
    //specialization lists
    public function specialization_lists(){
        $this->db->select('*');
        $this->db->where( 'status', 1 );
        $this->db->order_by("specialization_id", "desc");
        $query = $this->db->get( 'specialization' );
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }


    //GET AVAILABILITY HOURS
    function get_available_hrs_list($partner_id, $date=false) {

        $this->db->select('events.*, name, partner_name, partner_list.fees, guider.first_name');
        $this->db->from('events');
        $this->db->join('states', 'states.id = events.city_id','left');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id','left');
        $this->db->join('guider', 'guider.guider_id = events.host_id','left');
        $this->db->where( 'events.partner_id', $partner_id );
        if($date){ 
            $start = date('Y-m-d', strtotime( $date ));
            $this->db->where( 'DATE(start)', $start );
        }
        $this->db->where("start >=", date('Y-m-d'));
        $this->db->where('events.status != ',5,FALSE);
        $this->db->order_by("start", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }else {
            return false;
        }
    }
    public function get_events($partner_id, $start, $end){
        return $this->db->where("partner_id", $partner_id)
                    ->where("status !=", 5)
                    ->where("start >=", $start)
                    ->where("end <=", $end)
                    ->where("start >=", date('Y-m-d'))
                    ->get("events");
    }
    public function updatePackageInfo($data, $packageId){
        $this->db->where('id', $packageId);
        $this->db->update( 'events', $data );
    }
    public function updateSpaceBookingOrder($data, $ids){
        $table  = $this->db->dbprefix('events');
        $this->db->where_in('id', $ids);
        $this->db->where( 'space_uuid !=', '' );
        $this->db->update( $table, $data );
    }
    function InitiateSenangpay( $data ){
        $this->db->insert( 'senangpay_transaction', $data );
    }
}
?>    