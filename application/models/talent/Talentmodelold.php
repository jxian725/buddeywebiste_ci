<?php
class Talentmodel extends CI_Model {

    public $column_order    = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status'); 
    public $column_search   = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $allcolumn_search= array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $order           = array('events.end' => 'DESC'); // default order 

    //Payment
    public $column_order2    = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status'); 
    public $column_search2   = array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $allcolumn_search2= array('events.id','partner_list.partner_id','partner_list.address','states.name','start','start','end', 'partnerFees', 'events.status');
    public $order2           = array('events.end' => 'DESC'); // default order 

    //Donation
    public $column_order3    = array('payment_id', 'pay_updated', 'fullName', 'transaction_id', 'transaction_amount', 'sub_total', 'pay_status'); 
    public $column_search3   = array('payment_id', 'pay_updated', 'fullName', 'transaction_id', 'transaction_amount', 'sub_total', 'pay_status');
    public $allcolumn_search3= array('payment_id', 'pay_updated', 'fullName', 'transaction_id', 'transaction_amount', 'sub_total', 'pay_status');
    public $order3           = array('payment_id' => 'DESC'); // default order 
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
    function talentReviewLists( $status, $guider_id){
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
    
    public function talentSocialLinkInfo($talent_id){
        $this->db->select('*');
        $this->db->from('talent_social_links');
        $this->db->where('talent_id', $talent_id ); 
        $query = $this->db->get();
        return $query->row();
    }
    public function addTalentSocialLink($data){
        $this->db->insert( 'talent_social_links', $data );
        return true; 
    }
    public function updateTalentSocialLink( $id, $data ) {
        $this->db->where( 'id', $id );
        $this->db->update( 'talent_social_links', $data );
        return true;
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
    public function PhoneExists($mobile){
        $this->db->where('phone_number', trim($mobile));
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
    function get_datatables($where=false, $startDate=false, $endDate=false, $talent_id){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false, $startDate=false, $endDate=false, $talent_id){
        $this->_get_datatables_query();
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false, $startDate=false, $endDate=false, $talent_id){
        $this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('buskerpod_review', 'buskerpod_review.event_id = events.id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        if($startDate && $endDate){
            $this->db->where( 'DATE(events.start) >=', $startDate );
            $this->db->where( 'DATE(events.end) <=', $endDate );
        }
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
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
                    $this->db->where("(".$search." LIKE '%".trim($_POST['columns'][$j]['search']['value'])."%')");
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
    //Payment tabel data
    function get_payment_datatables($where=false, $talent_id){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_payment_datatables_query();
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
        $this->db->group_by( 'transactionID' );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_payment_filtered($where=false, $talent_id){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->_get_payment_datatables_query();
        
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
        $this->db->group_by( 'transactionID' );
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_payment_all($where=false, $talent_id){
        $this->db->select('events.id');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        
        $this->db->where('events.host_id', $talent_id );
        //$this->db->where('events.status',3,FALSE);
        $this->db->where("(events.status=3 OR (events.status=5 AND events.paidStatus=1))");
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_payment_datatables_query(){

        $this->db->select('events.*, SUM(partnerFees) AS totalAmt, partner_name');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->join('states', 'states.id = events.city_id','left');
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search2 as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                if($j == 8){
                    $this->db->where( $search, $_POST['columns'][$j]['search']['value'] );
                }else{
                    $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
                }
            }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search2 as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search2) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order2)){
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    //Donation tabel data
    function get_donation_datatables($where=false, $talent_id){
        $this->_get_donation_datatables_query();
        $this->db->where('guiderID', $talent_id );
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType', 'scan_payment' );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_donation_filtered($where=false, $talent_id){
        $this->_get_donation_datatables_query();
        
        $this->db->where('guiderID', $talent_id );
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType', 'scan_payment' );
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_donation_all($where=false, $talent_id){
        $this->db->select('payment_id');
        $this->db->from('senangpay_transaction');
        
        $this->db->where('guiderID', $talent_id );
        $this->db->where('pay_status',1);
        $this->db->where('paymentAppType', 'scan_payment' );
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_donation_datatables_query(){

        $this->db->select('senangpay_transaction.*, fullName, email, phoneNumber, message');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id','left');
        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];
        if($date_from && $date_to){
            $date_from2 = date('Y-m-d', strtotime($date_from));
            $date_to2   = date('Y-m-d', strtotime($date_to));
            $this->db->where("DATE(pay_updated) BETWEEN '".$date_from2."' AND '".$date_to2."'");
        }
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search3 as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                if($j == 8){
                    $this->db->where( $search, $_POST['columns'][$j]['search']['value'] );
                }else{
                    $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
                }
            }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search3 as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search3) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order3[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order3)){
            $order = $this->order3;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function paymentInfo($payment_id) {
        $this->db->select('senangpay_transaction.*, fullName, email, phoneNumber, message');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id','left');
        $this->db->where('payment_id', $payment_id ); 
        $query = $this->db->get();
        return $query->row();
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
    public function stateInfo($id){
        $this->db->select('*');
        $this->db->where('id', $id );
        $query = $this->db->get('states');
        return $query->row();
    }
    //Ratings and Reviews
     function updateReviews( $id, $data ) { 
        $this->db->where( 'event_id', $id );
        $this->db->update( 'buskerpod_review', $data );
        return true;
    }
    function addReviews( $data ) {
        $this->db->insert( 'buskerpod_review', $data );
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
        $this->db->order_by("support_id", "desc");
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
    public function specializationInfo($specialization_id){
        $this->db->select('*');
        $this->db->where('specialization_id', $specialization_id );
        $query = $this->db->get('specialization');
        return $query->row();
    }
    //GET TALENT LANGUAGES
    function getTalentLangLists() {
        $this->db->select('*');
        $this->db->from('guider_language');
        $this->db->where('status', 1 );
        $this->db->order_by("language", "ASC");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function langInfo($lang_id){
        $this->db->select('*');
        $this->db->where('lang_id', $lang_id );
        $query = $this->db->get('guider_language');
        return $query->row();
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
    public function updateLockedPackageInfo($data, $packageId, $lockedBy){
        $this->db->where('id', $packageId);
        $this->db->where('lockedBy', $lockedBy);
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
    //NEW ADDED
    public function deleteUrl($id){
        $this->db->where( 'id', $id );
        $this->db->delete( 'talent_video_list' );
        return true;
    }
    public function partnerList($host_id=''){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('partner_list.*');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id','left');
        $this->db->where('events.host_id', $host_id );
        $this->db->group_by( 'events.partner_id' );
        $this->db->order_by("partner_name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function donation_lists($guider_id, $limit=false, $order=false){

        $this->db->select('senangpay_transaction.*, fullName, email, phoneNumber, message');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id');
        $this->db->where( 'pay_status', 1 );
        $this->db->where('paymentAppType', 'scan_payment' );
        $this->db->where('guiderID', $guider_id );
        
        if($order){ $this->db->order_by("sub_total", "desc"); }else{ $this->db->order_by("payment_id", "desc"); }
        if($limit){ $this->db->limit($limit); }else{ $this->db->limit(10); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }else {
            return false; 
        }
    }
    public function checkEventLocked($user_id, $package_id){
        $this->db->select('*');
        $this->db->where("(events.status=2 OR events.status=3 OR events.status=4)");
        $this->db->where('lockedBy !=', $user_id );
        $this->db->where('id', $package_id );
        $query = $this->db->get('events');
        return $query->row();
    }

    public function transactionInfoByTxn($transaction_id){
        $this->db->select('*');
        $this->db->where('transaction_id', $transaction_id );
        $query = $this->db->get('senangpay_transaction');
        return $query->row();
    }
    public function bookedOrderSummaryByPartner($transactionID){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('events.*,partner_name, COUNT(id) AS totalBooked');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->where('transactionID', $transactionID );
        $this->db->group_by( 'events.partner_id' );
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function bookedOrderSummaryByDate($partner_id, $transactionID){
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('events.*,partner_name, COUNT(id) AS totalBooked');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->where('transactionID', $transactionID );
        $this->db->where('events.partner_id', $partner_id );
        $this->db->group_by('DATE(start)');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function bookedOrderSummary($start, $partner_id, $transactionID){
        $this->db->select('events.*,partner_name');
        $this->db->from('events');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id', 'left');
        $this->db->where('events.partner_id', $partner_id );
        $this->db->where('transactionID', $transactionID );
        $this->db->like('start', $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function revokeBookedList($orderID){
        $this->db->select('revoked_event_booking.*,partner_id, start, end');
        $this->db->from('revoked_event_booking');
        $this->db->join('events', 'events.id = revoked_event_booking.event_id', 'left');
        $this->db->where('revoked_event_booking.orderID', $orderID );
        $this->db->order_by("revoke_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function getLicenseList(){
        $this->db->select('*');
        $this->db->from('master_license');
        $this->db->where( 'status !=', 4 );
        $this->db->order_by("license_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function getSelectedLicense($license_ids){
        $this->db->select('license_id, license_name');
        $this->db->from('master_license');
        $this->db->where_in('license_id', $license_ids);
        $this->db->where( 'status !=', 4 );
        $this->db->order_by("license_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function licenseInfo($license_id, $status=null){
        $this->db->select('*');
        $this->db->where('license_id', $license_id );
        if($status){ $this->db->where('status', $status ); }
        $query = $this->db->get('master_license');
        return $query->row();
    }
    public function talentLicenseInfo($talent_id, $license_id, $status=null){
        $this->db->select('*');
        $this->db->where('talent_id', $talent_id );
        $this->db->where('license_id', $license_id );
        if($status){ $this->db->where('status', $status ); }
        $query = $this->db->get('talent_license_list');
        return $query->row();
    }
    public function updateTalentLicense($talent_id, $license_id, $data){
        $this->db->where('talent_id', $talent_id );
        $this->db->where('license_id', $license_id );
        $this->db->update( 'talent_license_list', $data );
        return true;
    }
    function addTalentLicense( $data ) { 
        $this->db->insert( 'talent_license_list', $data );
        return true;
    }
    function addTalentExperience( $data ) { 
        $this->db->insert( 'talent_experience_list', $data );
        return true;
    }
    public function updateTalentExperience($talent_id, $te_id, $data){
        $this->db->where('talent_id', $talent_id );
        $this->db->where('te_id', $te_id );
        $this->db->update( 'talent_experience_list', $data );
        return true;
    }
    public function talentExperienceInfo($te_id){
        $this->db->select('*');
        $this->db->where('te_id', $te_id );
        $this->db->order_by("te_id", "desc");
        $query = $this->db->get('talent_experience_list');
        return $query->row();
    }
    public function ltalentExperienceInfo(){
        $this->db->select('*');
        $this->db->order_by("te_id", "desc");
        $query = $this->db->get('talent_experience_list');
        return $query->row();
    }
    
}
?>    