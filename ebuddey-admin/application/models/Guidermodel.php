<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );   
class Guidermodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    //INSERT NEW HOST
    public function insertHost( $data ){
        $table  = $this->db->dbprefix( 'guider' );
        $this->db->insert( $table, $data );
        return $this->db->insert_id();
    }
	//Guider lists
	public function guider_lists( $guider_search = '', $order_by = '' ){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->where( 'status !=', 0 );
	    //If Condition guider search
	    if( $guider_search ) {
	    	$this->db->where( "( first_name LIKE '%". $guider_search ."%' OR phone_number LIKE '%". $guider_search ."%' OR email LIKE '%". $guider_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'first_name', 'desc' );
	    } else if( $order_by == 3 ) {
	    	$this->db->order_by( 'email', 'asc' );
	    } else if( $order_by == 4 ) {
	    	$this->db->order_by( 'email', 'desc' );
	    } else {
	    	$this->db->order_by( 'guider_id', 'desc' );
	    }
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	public function pendingGuiderLists( $guider_search = '', $order_by = '' ){
		$this->db->select('*');
	    $this->db->where( 'status', 0 );
	    //If Condition guider search
	    if( $guider_search ) {
	    	$this->db->where( "( first_name LIKE '%". $guider_search ."%' OR phone_number LIKE '%". $guider_search ."%' OR email LIKE '%". $guider_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'first_name', 'desc' );
	    } else if( $order_by == 3 ) {
	    	$this->db->order_by( 'email', 'asc' );
	    } else if( $order_by == 4 ) {
	    	$this->db->order_by( 'email', 'desc' );
	    } else {
	    	$this->db->order_by( 'guider_id', 'desc' );
	    }
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	function guiderActivityLists( $guider_id ) {
        $this->db->select('*');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function activityInfo($activity_id){
		
		$this->db->select('*');
	    $this->db->where('activity_id', $activity_id );
        $this->db->where( 'activity_status !=', 4 );
	    $query = $this->db->get('guider_activity_list');
		$row = $query->row();
	    return $row;
	}
	function updateActivityInfo( $activity_id, $data ) {
    	$table  = $this->db->dbprefix( 'guider_activity_list' );
    	$this->db->where( 'activity_id', $activity_id );
		$this->db->update( $table, $data );
    	return true;
    }
    public function insertGuiderActivity( $data ){
        $table  = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->insert( $table, $data );
        $id     = $this->db->insert_id();
        return $id;
    }
    function getGuiderActiveServiceRegionLists($guider_id) {
    	$serviceLists = array();
        $this->db->select('activity_id,service_providing_region');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $results = $query->result();
            if($results){
            	foreach ($results as $key => $value) {
            		$serviceLists[] = $value->service_providing_region;
            	}
            }
        }
        return $serviceLists;
    }
	public function guiderInfo($guider_id){ 
		
		$this->db->select('guider.*, states.name as cityName, specialization.specialization as categoryName');
        $this->db->from('guider');
        $this->db->join('states', 'states.id = guider.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = guider.skills_category','left');
        $this->db->where('guider.guider_id', $guider_id ); 
        $query = $this->db->get();
        return $query->row();
    }
    public function guiderInfoByUID($host_uuid){
        
        $this->db->select('guider_id,first_name');
        $this->db->where('host_uuid', "$host_uuid" );
        $this->db->where( 'host_uuid !=', '' );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
    public function guiderInfoByPhone($phone_number){
        
        $this->db->select('guider_id');
        $this->db->where('phone_number', $phone_number );
        $this->db->where( 'status !=', 4 );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
    public function existingGuidePhoneNo($guider_id, $phone_number){
        
        $this->db->select('guider_id');
        $this->db->where( 'guider_id !=', $guider_id );
        $this->db->where('phone_number', $phone_number );
        $this->db->where( 'status !=', 4 );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
	//UPDATE GUIDER STATUS
    function guiderStatus( $guider_id, $status ) {
    	$data 	= array( 'status' => $status );
    	$table  = $this->db->dbprefix( 'guider' );
    	$this->db->where( 'guider_id', $guider_id );
		$this->db->update( $table, $data );
    	return true;
    }
    function deleteGuider( $guider_id, $data ) {
        $table  = $this->db->dbprefix( 'guider' );
        $this->db->where( 'guider_id', $guider_id );
        $this->db->update( $table, $data );
        return true;
    }
    //Check password
    function check_password( $user_id, $password ) {
    	$this->db->select('*');
	    $this->db->where('user_id', $user_id );
	    $query = $this->db->get('user');
		$adminInfo   = $query->row();
        if($adminInfo){
            $oldPass     = $adminInfo->password;
            $currentPass = $this->encryption->decrypt($oldPass);
            if ($currentPass == trim($password)) {
                return true;
            }else{
                return false;
            }
        }else{
           return false;
        }
		
		
	    
    }
    public function stateInfoByid( $id ){
        $this->db->select( '*' );
        $this->db->where('id', $id);
        $query = $this->db->get('states');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function guiderLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'guider_language' );
        $row    = $query->row();
        return $row;
    }
    function getHostLangLists() {
        $this->db->select('*');
        $this->db->from('guider_language');
        $this->db->where('status', 1 );
        $this->db->order_by("language", "ASC");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function guiderSpecialityInfo( $specialization_id ) {
        $this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
    }
    function updateGuiderInfo( $guider_id, $data ) {

    	$table  = $this->db->dbprefix( 'guider' );
    	$this->db->where( 'guider_id', $guider_id );
		$this->db->update( $table, $data );
    	return true;
    }
    function serviceRegionLists($country_id=132) {

        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('status', 1 );
        $this->db->where( 'country_id', $country_id );
        $this->db->order_by("name", "asc"); 
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    function getSpecializationLists() {
        $this->db->select('*');
        $this->db->from('specialization');
        $this->db->where('status', 1 );
        $this->db->order_by("specialization_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function guiderDeviceTokenList($guider_id){
        
        $this->db->select('*');
        $this->db->from('guider_device_info');
        $this->db->where( 'guider_id', $guider_id );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ){
            $result = $query->result();
            return $result;
        }
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
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            $row = $query->row();
            return $row;
        } else {
            return 0;
        }
    }
	//UPDATE GUIDER Strength
    function guiderStrength( $guider_id, $strength,$comment ) {
    	$data 	= array( 'strength' => $strength,'strength_comment' => $comment );
    	$table  = $this->db->dbprefix( 'guider' );
    	$this->db->where( 'guider_id', $guider_id );
		$this->db->update( $table, $data );
    	return true;
    }
	//Add Message
    function addMessage( $data ) {
		
		
        $this->db->insert( 'inbox', $data );
        return $this->db->insert_id();
    }
	//Get Message info
	public function talentInboxinfo($guider_id){
		
        $this->db->select('inbox.*, first_name, last_name, phone_number');
        $this->db->from('inbox');
        $this->db->join('guider', 'guider.guider_id = inbox.talent_id', 'left');
        $this->db->where('talent_id', $guider_id );
		$this->db->where('is_admin_delete', 0 );
        $this->db->order_by("id", "asc");
         $query = $this->db->limit(10)->get();
        return $query->result();
    }
	//Delete Message
	 public function deleteMessage($msgid,$data){
        $this->db->where('id', $msgid );
        $this->db->update( 'inbox', $data );
        return true;
    }
	function loadMoreMessage($msgid,$guider_id){
        $this->db->select('inbox.*, first_name, last_name, phone_number');
        $this->db->from('inbox');
        $this->db->join('guider', 'guider.guider_id = inbox.talent_id', 'left');
        $this->db->where('talent_id', $guider_id );
		$this->db->where('is_admin_delete', 0 );
		$this->db->where('id < ',$msgid);		
        $this->db->order_by("id", "desc");
        $query = $this->db->limit(10)->get();
        return $query->result();
 
       // $dbQuery = $db->select('*')->limit($limit)->get();
        //return $dbQuery->getResult();
   }
   function talentInboxReadinfo($talent_id){
		
        $this->db->select('COUNT(*) AS unreadmsg');
        $this->db->from('inbox');
        $this->db->where('talent_id', $talent_id);
        $this->db->where('isadmin_readstatus', 1); 
		$this->db->where( 'istalent_message', 1);
		$query = $this->db->get();
       
        $result=$query->result_array();
		$msgcount=$result[0]['unreadmsg'];
		
        return $msgcount;
    }
	function updateadminreadStatus( $guider_id, $data ) {
    	$table  = $this->db->dbprefix( 'inbox' );
    	$this->db->where( 'talent_id', $guider_id );
		$this->db->where( 'istalent_message', 1);
		$this->db->update( $table, $data );
    	return true;
    }
}