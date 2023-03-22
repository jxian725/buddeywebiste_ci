<?php
class Commonmodel extends CI_Model {  
	
	function journey_list(){
		$this->db->where( 'status', 1 );
		$this->db->order_by( 'journey_id' , 'desc' );
		$query 		= $this->db->get( 'journeys' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; } 
	}
    public function updateGuiderInfoById($guider_id, $data){
        $table  = $this->db->dbprefix('guider');
        $this->db->where( 'guider_id', $guider_id );
        $this->db->update( $table, $data );
    }
    //INSERT GUIDER ACTIVITY
    function insertActivity($data){
		$table3 = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->insert( $table3, $data );
	}
    //Guider Activity Lists
    function get_guider_activity_lists( $skill_id=false, $city_id=false ) {
    	$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$this->db->select('guider_activity_list.*,guider_id,first_name,last_name,phone_number,
			countryCode,email,profile_image,rating,languages_known,about_me,states.name,
			country_currency_symbol,country_name');
	    $this->db->from('guider_activity_list');
	    $this->db->join('guider', 'guider.guider_id = guider_activity_list.activity_guider_id','left');
	    $this->db->join('states', 'states.id = guider_activity_list.service_providing_region','left');
	    $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
	    $this->db->where( 'guider_activity_list.activity_status = 1');
		$this->db->where( 'guider.status', 1 );
		/*if($search_val){
	    	$parts 		= explode(" ",trim($search_val));
			foreach ($parts as $part){
			    $clauses[] = "(first_name LIKE '%".$part."%' OR email LIKE '%".$part."%' OR about_me LIKE '%".$part."%' OR name LIKE '%".$part."%')";
			}
			$clause 	= implode(' OR ' ,$clauses);
            $this->db->where($clause);
        }*/
        if($skill_id){ $this->db->where('FIND_IN_SET('.$skill_id.', guiding_speciality)'); }
        if($city_id){ $this->db->where( 'guider_activity_list.service_providing_region', $city_id ); }
		$this->db->group_by( 'activity_id' );
		$this->db->order_by( 'activity_guider_id' , 'desc' );
		$query 	= $this->db->get();
		$numRows = $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	//Get guider list
	function get_guider_data( $search_val ) {
		$this->db->select('guider.*,states.name,country_currency_symbol,country_name');
	    $this->db->from('guider');
	    $this->db->join('states', 'states.id = guider.service_providing_region','left');
	    $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
		if($search_val){
	    	$parts 		= explode(" ",trim($search_val));
			foreach ($parts as $part){
			    $clauses[] = "first_name LIKE '%".$part."%' OR email LIKE '%".$part."%' OR about_me LIKE '%".$part."%' OR name LIKE '%".$part."%'";
			}
			$clause 	= implode(' OR ' ,$clauses);
            $this->db->where($clause);
        }
		$this->db->where( 'guider.status', 1 );
		$this->db->order_by( 'guider_id' , 'desc' );
		$query 	= $this->db->get();
		$numRows = $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	function get_guiderFilter_data( $skill_id, $city_id ) {
		$this->db->select('guider_activity_list.*,guider_id,first_name,last_name,phone_number,
			countryCode,email,profile_image,rating,languages_known,about_me,states.name,
			country_currency_symbol,country_name');
	    $this->db->from('guider_activity_list');
	    $this->db->join('guider', 'guider.guider_id = guider_activity_list.activity_guider_id','left');
	    $this->db->join('states', 'states.id = guider_activity_list.service_providing_region','left');
	    $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
		/*if($search_val){
	    	$parts 		= explode(" ",trim($search_val));
			foreach ($parts as $part){
			    $clauses[] = "(first_name LIKE '%".$part."%' OR email LIKE '%".$part."%' OR about_me LIKE '%".$part."%')";
			}
			$clause 	= implode(' OR ' ,$clauses);
            $this->db->where($clause);
        }*/
        if($skill_id){ $this->db->where('FIND_IN_SET('.$skill_id.', guiding_speciality)'); }
        if($city_id){ $this->db->where( 'guider_activity_list.service_providing_region', $city_id ); }
		$this->db->where( 'guider.status', 1 );
		$this->db->where( 'activity_status', 1 );
		
		$this->db->order_by( 'guider_id' , 'desc' );
		$query 	= $this->db->get();
		$numRows = $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	public function guiderSpecialityInfo( $specialization_id ) {
        $this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
    }
    public function guiderLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'guider_language' );
        $row    = $query->row();
        return $row;
    }
    function get_guiderlanguage_lists() {
    	$this->db->select( '*' );
        $this->db->order_by("language", "asc");
        $query 		= $this->db->get( 'guider_language' );
        $numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
    }
	//Get State List
	function get_state_list( $term=false ) {
		if($term){ $this->db->like( 'states.name', $term ); }
		$query 		= $this->db->get( 'states' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	function cityInfo( $id ) {
		$this->db->select( '*' );
        $this->db->where( 'id', $id );
        $query  = $this->db->get( 'states' );
        $row    = $query->row();
        return $row;
	}
	function skillInfo( $specialization_id ) {
		$this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
	}
	//Guider Info 
	function get_result_guider( $service_providing_region ) {
		$this->db->where( 'service_providing_region', $service_providing_region );
		$query 			= $this->db->get( 'guider' );
		$numRows 		= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	//Get Guider View Info
	function get_guider_view( $activity_id ) {
		$this->db->select('guider_activity_list.*,guider_id,first_name,last_name,phone_number,
			countryCode,email,profile_image,rating,languages_known,about_me,states.name,
			country_currency_symbol,country_name');
	    $this->db->from('guider_activity_list');
	    $this->db->join('guider', 'guider.guider_id = guider_activity_list.activity_guider_id','left');
	    $this->db->join('states', 'states.id = guider_activity_list.service_providing_region','left');
	    $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
		$this->db->where( 'activity_id', $activity_id );
		$this->db->where( 'activity_status', 1 );
		$query 			= $this->db->get();
		$numRows 		= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	//Get Journey View Info
	function get_journey_view( $j_id ) {
		$this->db->where( 'journey_id', $j_id );
		$query 			= $this->db->get( 'journeys' );
		$numRows 		= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	//Category List
	function category_list($order=false){
		$this->db->where( 'status', 1 );
		if($order=='name'){
			$this->db->order_by( 'specialization' , 'asc' );
		}else{
			$this->db->order_by( 'specialization_id' , 'desc' );
		}
		$query 		= $this->db->get( 'specialization' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; } 
	}
	//Category List
	function searchCategoryLists($search=''){
		$this->db->where( 'status', 1 );
		if($search){ $this->db->like( 'specialization', $search ); }
		$this->db->order_by( 'specialization' , 'asc' );
		$query 		= $this->db->get( 'specialization' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
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
	//PARTNER LIST
	public function partner_Lists($search='', $city_id=''){
        $this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        if($search){ $this->db->like( 'partner_name', $search ); }
        if($city_id){ $this->db->where( 'partner_list.city_id', $city_id ); }
        $this->db->where( 'partner_list.status', 1 );
        $this->db->order_by("partner_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
	//Get service city
	function get_service_city() {
		$query 			= $this->db->get( 'states' );
		$numRows 		= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	//Mail function 
	function send_mail_to_guider( $toEmail, $message ) {
                
        $adminEmail = 'support@buddeyapp.com';
        $subject    = 'Welcome to Buddey';
        // Always set content-type when sending HTML email
        $headers    = "From: Buddey Admin <admin@buddeyapp.com>\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($toEmail, $subject, $message, $headers);
    }
    function phonenoVerify( $mobile_no ) {
		$this->db->where( 'phone_number', $mobile_no );
		$query 			= $this->db->get( 'guider' );
		$numRows 		= $query->num_rows();
		if( $numRows > 0 ) {
			echo 1;
		} else { echo 0; }
	}
	function phoneno2Verify( $mobile_no ) {
		$this->db->select( '*' );
        $this->db->where( 'phone_number', $mobile_no );
        $query  = $this->db->get( 'guider' );
        $row    = $query->row();
        return $row;
	}
	function postComment($data){
		$table3 = $this->db->dbprefix( 'web_comments' );
        $this->db->insert( $table3, $data );
	}
	function get_guider_comment_list( $activity_id=false ) {
		$this->db->select('*');
	    $this->db->where('activity_id', $activity_id );
	    $this->db->order_by( 'web_cmt_id' , 'desc' );
	    $query 		= $this->db->get( 'web_comments' );
	    $numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->result();
		} else { return false; }
	}
	//FAQ LISTS
	public function faq_lists($search_val=''){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    if($search_val){
	    	$parts 		= explode(" ",trim($search_val));
			foreach ($parts as $part){
			    $clauses[] = "title LIKE '%".$part."%' OR content LIKE '%".$part."%' ";
			}
			$clause 	= implode(' OR ' ,$clauses);
            $this->db->where($clause);
        }
	    $this->db->order_by("faq_id", "asc");
	    $query = $this->db->get( 'faq_list' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	function feedbackTotalCount($type=false,$user_id=false) {
        $this->db->select('*');
        if($type == 'T'){
            $this->db->where( 'traveller_feedback !=', '' );
            $this->db->where( 'jny_traveller_id', $user_id );
        }else{
            $this->db->where( 'guider_feedback !=', '' );
            $this->db->where( 'jny_guider_id', $user_id );
        }
        $query = $this->db->get( 'journey_list' );
        $rowcount = $query->num_rows();
        return $rowcount;
    }
    function commentTotalCount($type=false,$user_id=false) {
        $this->db->select('*');
        if($type == 'T'){
            $this->db->where( 'receiver_type', 2 );
            $this->db->where( 'cmt_traveller_id', $user_id );
        }else{
            $this->db->where( 'receiver_type', 1 );
            $this->db->where( 'cmt_guider_id', $user_id );
        }
        $query = $this->db->get( 'comments' );
        $rowcount = $query->num_rows();
        return $rowcount;
    }
    function webcommentTotalCount($type=false, $user_id=false) {
        $this->db->select('*');
        if($type == 'G'){
            $this->db->where( 'guider_id', $user_id );
        }else{
            $this->db->where( 'activity_id', $user_id );
        }
        $query = $this->db->get( 'web_comments' );
        $rowcount = $query->num_rows();
        return $rowcount;
    }
    public function siteInfo( $key ){
        $this->db->select( '*' );
        $this->db->where( 's_key', "$key" );
        $query  = $this->db->get( 'site_setting' );
        $row    = $query->row();
        return $row;
    }
    //. New About list 7-2-2020
    public function pageInfo( $page_key ) {
		$this->db->where( 'page_key', "$page_key" );
		$query   = $this->db->get( 'cms_pages' );
		$numRows = $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	function venuePartnerImgLists() {
		$this->db->select('*');
	    $this->db->where('status', 1 );
	    $this->db->order_by( 'cvp_id' , 'desc' );
	    $query = $this->db->get( 'cms_venue_partner_images' );
		if( $query->num_rows() > 0 ) {
			return $query->result();
		} else { return false; }
	}
}
?>    