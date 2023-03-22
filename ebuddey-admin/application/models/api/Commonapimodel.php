<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Commonapimodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    //Get Specialization List
    function get_specialization_list() {
        $data = array();
        $this->db->select('*');
        $this->db->from('specialization');
        $this->db->where('status', 1 );
        $this->db->order_by("specialization_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $specializationLists   = $query->result();
            foreach ( $specializationLists as $specialization ) {
                if($specialization->category_img){
                    $category_img = $this->config->item( 'dir_url' ).'uploads/category/'.$specialization->category_img;
                }else{
                    $category_img = '';
                }
                $data[] = array(
                        "specialization_id" => intval($specialization->specialization_id),
                        "specialization"    => rawurldecode( $specialization->specialization ),
                        "category_img"      => $category_img,
                        "created_on"        => $specialization->created_on
                        );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Specialization list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Specialization found.', 'result' => 'error', 'data'=>$data);
        }
    }
	function get_place_state_list($country_id=false, $states=false) {
        $data = array();

        $this->db->select('*');
        $this->db->from('states');
        //$this->db->where('status', 1 );
        $this->db->where( 'country_id', $country_id );
        $this->db->order_by("name", "asc");
        //if($limit || $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $stateLists   = $query->result();
            foreach ($stateLists as $state) {
            	if($state->status == 1){
            		$status 	= 'active';
            	}else{
            		$status 	= 'inactive';
            	}
                $data[] = array(
                        "state_id" 	   => intval($state->id),
                        "name"         => "". $state->name ."",
                        "status"       => "". $status ."",
                        );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get States list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No States found.', 'result' => 'error', 'data' => $data);
        }
    }
    public function countryInfoByShortcode( $code ){
        $this->db->where('country_short_code', "$code");
        $query = $this->db->get('countries');
        if( $query->num_rows() > 0 ){
            return $query->row();
        }else{
            return false; 
        }
    }
    public function serviceInfo( $service_id ){
        $this->db->select( '*' );
        $this->db->where( 'service_id', $service_id );
        $query  = $this->db->get( 'service_list' );
        return $query->row();
    }
    
    public function messageInfo( $msg_id ){
        $this->db->select( '*' );
        $this->db->where( 'msg_id', $msg_id );
        $query  = $this->db->get( 'messages' );
        return $query->row();
    }
    public function insertMessage( $data ){
        $table = $this->db->dbprefix( 'messages' );
        $this->db->insert( $table, $data );
    }
    function retrieveMessageList( $limit=false, $start=false, $ruser_type=false, $ruser_id=false, $puser_type=false, $puser_id=false,$cuser_type=false ) {
        $data = array();
        $msg_ids = array();

        $this->db->select('*');
        $this->db->from( 'messages' );
        if(strtoupper($cuser_type) == 'T'){
            $this->db->where( 'traveller_trash', 'N' );
        } else if (strtoupper($cuser_type) == 'G') {
            $this->db->where( 'guider_trash', 'N' );
        }
        $this->db->where('
                    ((msg_post_user_type = "'.$puser_type.'" AND msg_post_user_id = '.$puser_id.' AND msg_receive_user_type = "'.$ruser_type.'" AND msg_receive_user_id = '.$ruser_id.')
                    OR (msg_post_user_type = "'.$ruser_type.'" AND msg_post_user_id = '.$ruser_id.' AND msg_receive_user_type = "'.$puser_type.'" AND msg_receive_user_id = '.$puser_id.'))', NULL, FALSE);
        $this->db->order_by( "msg_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $messageLists   = $query->result();
            foreach ( $messageLists as $message ) {
                $msg_ids[] = $message->msg_id;
                $data[] = array(
                            "msg_id"                => "". $message->msg_id ."",
                            "msg_post_user_type"    => "". $message->msg_post_user_type ."",
                            "msg_post_user_id"      => "". $message->msg_post_user_id ."",
                            "msg_receive_user_type" => "". $message->msg_receive_user_type ."",
                            "msg_receive_user_id"   => "". $message->msg_receive_user_id ."",
                            "message"               => "". $message->message ."",
                            "datetime"              => "". date('d-m-Y h:i A',strtotime($message->createdon)) .""
                            );        
            }

            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Messages list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => $data);
        }
    }
    function deleteMessageList( $ruser_type=false, $ruser_id=false, $puser_type=false, $puser_id=false, $cuser_type=false ) {
        $msg_ids = array();

        $this->db->select('*');
        $this->db->from( 'messages' );
        if(strtoupper($cuser_type) == 'T'){
            $this->db->where( 'traveller_trash', 'N' );
        } else if (strtoupper($cuser_type) == 'G') {
            $this->db->where( 'guider_trash', 'N' );
        }
        $this->db->where('
                    ((msg_post_user_type = "'.$puser_type.'" AND msg_post_user_id = '.$puser_id.' AND msg_receive_user_type = "'.$ruser_type.'" AND msg_receive_user_id = '.$ruser_id.')
                    OR (msg_post_user_type = "'.$ruser_type.'" AND msg_post_user_id = '.$ruser_id.' AND msg_receive_user_type = "'.$puser_type.'" AND msg_receive_user_id = '.$puser_id.'))', NULL, FALSE);
        $this->db->order_by( "msg_id", "desc" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $messageLists   = $query->result();
            foreach ( $messageLists as $message ) {
                $msg_ids[] = $message->msg_id;
            }
            //DELETE USER MESSAGE.
            if(strtoupper($cuser_type) == 'T'){
                $data11 = array('traveller_trash' => 'Y');
                $this->db->where_in('msg_id', $msg_ids);
                $this->db->update( 'messages', $data11 );
            } else if (strtoupper($cuser_type) == 'G') {
                $data22 = array('guider_trash' => 'Y');
                $this->db->where_in('msg_id', $msg_ids);
                $this->db->update( 'messages', $data22 );
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Delete Messages list Successfully.', 'result' => 'success', 'data' => array('error' => 1));
        }else {
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => array('error' => 1));
        }
    }
    function updateMessageList( $ruser_type=false, $ruser_id=false, $puser_type=false, $puser_id=false, $cuser_type=false ) {
        $msg_ids = array();

        $this->db->select('*');
        $this->db->from( 'messages' );
        if(strtoupper($cuser_type) == 'T'){
            $this->db->where( 'traveller_seen', 'N' );
        } else if (strtoupper($cuser_type) == 'G') {
            $this->db->where( 'guider_seen', 'N' );
        }
        $this->db->where('
                    ((msg_post_user_type = "'.$puser_type.'" AND msg_post_user_id = '.$puser_id.' AND msg_receive_user_type = "'.$ruser_type.'" AND msg_receive_user_id = '.$ruser_id.')
                    OR (msg_post_user_type = "'.$ruser_type.'" AND msg_post_user_id = '.$ruser_id.' AND msg_receive_user_type = "'.$puser_type.'" AND msg_receive_user_id = '.$puser_id.'))', NULL, FALSE);
        $this->db->order_by( "msg_id", "desc" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $messageLists   = $query->result();
            foreach ( $messageLists as $message ) {
                $msg_ids[] = $message->msg_id;
            }
            //UPDATE USER READ MESSAGE.
            if(strtoupper($cuser_type) == 'T'){
                $data11 = array('traveller_seen' => 'Y');
                $this->db->where_in('msg_id', $msg_ids);
                $this->db->update( 'messages', $data11 );
            } else if (strtoupper($cuser_type) == 'G') {
                $data22 = array('guider_seen' => 'Y');
                $this->db->where_in('msg_id', $msg_ids);
                $this->db->update( 'messages', $data22 );
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Updated Messages list Successfully.', 'result' => 'success', 'data' => array('error' => 1));
        }else {
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => array('error' => 1));
        }
    }
    function GetListOfMessagedUsers( $limit=false, $start=false, $user_type=false, $user_id=false ) {
        $data = array();
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('MAX(msg_id) AS message_id');
        $this->db->from( 'messages' );
        if(strtoupper($user_type) == 'T'){
            $this->db->where( 'traveller_trash', 'N' );
        } else if (strtoupper($user_type) == 'G') {
            $this->db->where( 'guider_trash', 'N' );
        }
        $this->db->where('
                    ((msg_post_user_type = "'.$user_type.'" AND msg_post_user_id = '.$user_id.')
                    OR 
                    ( msg_receive_user_type = "'.$user_type.'" AND msg_receive_user_id = '.$user_id.'))
                    ', NULL, FALSE);
        $this->db->group_by('GREATEST(msg_post_user_id, msg_receive_user_id), LEAST(msg_receive_user_id, msg_post_user_id)');
        $this->db->order_by( "MAX(msg_id)", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $messageLists   = $query->result();
            $user_id2       = 0;
            $profile_image  = '';
            $opp_user_type  = '';
            $upload_path_url    = $this->config->item( 'upload_path_url' );
            foreach ( $messageLists as $message ) {
                $messageInfo    = $this->messageInfo($message->message_id);
                if($messageInfo->msg_post_user_type == $user_type && $messageInfo->msg_post_user_id == $user_id){
                    $user_id2   = $messageInfo->msg_receive_user_id;
                }
                if($messageInfo->msg_receive_user_type == $user_type && $messageInfo->msg_receive_user_id == $user_id){
                    $user_id2   = $messageInfo->msg_post_user_id;
                }
                if($user_type == 'T'){
                    $profileImgPath = $upload_path_url.'g_profile/';
                    $userInfo       = $this->Guiderapimodel->guiderInfoByUuid( $user_id2 );
                    $profile_image  = ($userInfo->profile_image) ? $profileImgPath.$userInfo->profile_image : '';
                    $first_name     = $userInfo->first_name;
                    $last_name      = $userInfo->last_name;
                    $opp_user_type  = 'G';
                }elseif ($user_type == 'G') {
                    $profileImgPath = $upload_path_url.'t_profile/';
                    $userInfo       = $this->Travellerapimodel->travellerInfoByUuid( $user_id2 );
                    $profile_image  = ($userInfo->profile_image) ? $profileImgPath.$userInfo->profile_image : '';
                    $first_name     = $userInfo->first_name;
                    $last_name      = $userInfo->last_name;
                    $opp_user_type = 'T';
                }
                $data[] = array(
                        "msg_id"                => "". $message->message_id ."",
                        "msg_post_user_type"    => "". $messageInfo->msg_post_user_type ."",
                        "msg_post_user_id"      => "". $messageInfo->msg_post_user_id ."",
                        "msg_receive_user_type" => "". $messageInfo->msg_receive_user_type ."",
                        "msg_receive_user_id"   => "". $messageInfo->msg_receive_user_id ."",
                        "message"               => "". $messageInfo->message ."",
                        "user_id"               => "". $user_id2 ."",
                        "first_name"            => "". $first_name ."",
                        "last_name"             => "". $last_name ."",
                        "profile_image"         => "". $profile_image ."",
                        "opp_user_type"         => "". $opp_user_type ."",
                        "datetime"              => "". $messageInfo->createdon ."");        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Messages list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => $data);
        }
    }
    function getGuiderServiceRequest( $limit=false, $start=false, $guider_id=false, $filtertype=false ) {
        $upload_path_url    = $this->config->item( 'upload_path_url' );
        $data = array();
        $this->db->select('sl.*,t.traveller_id,t.first_name,t.first_name,t.phone_number,t.profile_image,
            t.ratings');
        $this->db->from( 'service_list sl' );
        $this->db->join( 'traveller t', 't.traveller_id = sl.service_traveller_id', 'left' );
        $this->db->where( 'sl.service_guider_id', $guider_id );
        if($filtertype){
            $this->db->where( 'sl.status', $filtertype ); 
        }
        $this->db->order_by( "service_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $requestLists   = $query->result();
            foreach ( $requestLists as $request ) {
                if($request->cancelled_by == 'T'){
                    $cancelled_by = 'Cancelled by '.$request->first_name.' (Traveller)';
                }else if($request->cancelled_by == 'G'){
                    $cancelled_by    = 'Cancelled by you';
                }else{ $cancelled_by = ''; }
                if($request->guider_charged){
                    if($request->service_price_type_id == 1){
                        $rate    = $request->guider_charged * $request->number_of_person;
                    }else{
                        $rate    = $request->guider_charged;
                    }
                }else{ $rate = ''; }
                $spec   = [];
                $activityInfo   = $this->Guiderapimodel->guiderActivtyInfoByUuid( $request->activity_id );
                if($activityInfo){
                    if($activityInfo->guiding_speciality){
                        $array  = explode(',', $activityInfo->guiding_speciality);
                        foreach ($array as $item) {
                            $specInfo = $this->Guiderapimodel->guiderSpecialityInfo($item);
                            if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                        }
                    }
                }
                $commentCount   = $this->feedbackTotalCount('T',$request->traveller_id);
                $profileImgPath = $upload_path_url.'t_profile/';
                $profile_image  = ($request->profile_image) ? $profileImgPath.$request->profile_image : '';
                $data[] = array(
                            "service_id"         => "". $request->service_id ."",
                            "service_date"       => "". $request->service_date ."",
                            "pickup_time"        => "". $request->pickup_time ."",
                            "total_hours"        => "". $request->total_hours ."",
                            "number_of_person"   => "". $request->number_of_person ."",
                            "rate_per_person"    => "". $request->guider_charged ."",
                            "additional_information" => "". $request->additional_information ."",
                            "guiding_speciality" => "". $spec ."",
                            "subtotal"           => "". $rate ."",
                            "rating"             => "". $request->ratings ."",
                            "pickup_location"    => "". $request->pickup_location ."",
                            "user_id"            => "". $request->traveller_id ."",
                            "first_name"         => "". $request->first_name ."",
                            "last_name"          => "". $request->last_name ."",
                            "profile_image"      => "". $profile_image ."",
                            "cancelled_by"       => "". $cancelled_by ."",
                            "status"             => "". $request->status ."",
                            "user_comment"       => intval($commentCount)
                        );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Request list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Request found.', 'result' => 'error', 'data' => $data);
        }
    }
    function getTravellerServiceRequest( $limit=false, $start=false, $traveller_id=false, $filtertype=false ) {
        $upload_path_url    = $this->config->item( 'upload_path_url' );
        $data = array();
        $this->db->select('sl.*,g.guider_id,g.first_name,g.last_name,g.phone_number,g.profile_image,
                            g.rating');
        $this->db->from( 'service_list sl' );
        $this->db->join( 'guider g', 'g.guider_id = sl.service_guider_id', 'left' );
        $this->db->where( 'sl.service_traveller_id', $traveller_id );
        if($filtertype){
            $this->db->where( 'sl.status', $filtertype ); 
        }
        $this->db->order_by( "service_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $requestLists   = $query->result();
            foreach ( $requestLists as $request ) {
                if($request->cancelled_by == 'G'){
                    $cancelled_by = 'Cancelled by '.$request->first_name.' (Guider)';
                }else if($request->cancelled_by == 'T'){
                    $cancelled_by    = 'Cancelled by you';
                }else{ $cancelled_by = ''; }
                if($request->guider_charged){
                    if($request->service_price_type_id == 1){
                        $rate   = $request->guider_charged * $request->number_of_person;
                    }else{
                        $rate   = $request->guider_charged;
                    }
                }else{ $rate    = ''; }
                $spec   = [];
                $activityInfo   = $this->Guiderapimodel->guiderActivtyInfoByUuid( $request->activity_id );
                if($activityInfo){
                    if($activityInfo->guiding_speciality){
                        $array  = explode(',', $activityInfo->guiding_speciality);
                        foreach ($array as $item) {
                            $specInfo = $this->Guiderapimodel->guiderSpecialityInfo($item);
                            if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                        }
                    }
                }
                $commentCount   = $this->feedbackTotalCount('G',$request->guider_id);
                $profileImgPath = $upload_path_url.'g_profile/';
                $profile_image  = ($request->profile_image) ? $profileImgPath.$request->profile_image : '';
                $data[] = array(
                            "service_id"        => "". $request->service_id ."",
                            "service_date"      => "". $request->service_date ."",
                            "pickup_time"       => "". $request->pickup_time ."",
                            "total_hours"       => "". $request->total_hours ."",
                            "number_of_person"  => "". $request->number_of_person ."",
                            "rate_per_person"   => "". $request->guider_charged ."",
                            "guiding_speciality"=> "". $spec ."",
                            "additional_information" => "". $request->additional_information ."",
                            "subtotal"          => "". $rate ."",
                            "rating"            => "". $request->rating ."",
                            "pickup_location"   => "". $request->pickup_location ."",
                            "user_id"           => "". $request->guider_id ."",
                            "first_name"        => "". $request->first_name ."",
                            "last_name"         => "". $request->last_name ."",
                            "profile_image"     => "". $profile_image ."",
                            "cancelled_by"      => "". $cancelled_by ."",
                            "status"            => "". $request->status ."",
                            "user_comment"      => intval($commentCount)
                        );
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Request list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Request found.', 'result' => 'error', 'data' => $data);
        }
    }
    function updateGuiderJourneyStatus( $guider_id=false ) {
        $this->updateGuiderJourneyStatusCompleted($guider_id);
        $this->updateGuiderJourneyStatusOngoing($guider_id);
    }
    function updateGuiderJourneyStatusCompleted( $guider_id=false ) {
        $now    = date('Y-m-d');
        $this->db->select('journey_list.*,total_hours,number_of_person,service_date,pickup_location,
                        pickup_time,number_of_person,additional_information');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->where( 'jny_guider_id', "$guider_id" );
        $this->db->where( 'service_date <', "$now" );
        $this->db->where( 'jny_status', 2 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $journey_id = $journey->journey_id;
                $data2      = array('jny_status' => 3,'completed_type' => 'auto');
                $table2     = $this->db->dbprefix( 'journey_list' );
                $this->db->where( 'journey_id', $journey_id );
                $this->db->update( $table2, $data2 );
            }
        }
    }
    function updateGuiderJourneyStatusOngoing( $guider_id=false ) {
        $now    = date('Y-m-d');
        $this->db->select('journey_list.*,total_hours,number_of_person,service_date,pickup_location,
                        pickup_time,number_of_person,additional_information');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->where( 'jny_guider_id', "$guider_id" );
        $this->db->where( 'service_date', "$now" );
        $this->db->where( 'jny_status', 1 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $journey_id = $journey->journey_id;
                $data2      = array('jny_status' => 2);
                $table2     = $this->db->dbprefix( 'journey_list' );
                $this->db->where( 'journey_id', $journey_id );
                $this->db->update( $table2, $data2 );
            }
        }
    }
    function updateTravellerJourneyStatus( $traveller_id=false ) {
        $this->updateTravellerJourneyStatusCompleted($traveller_id);
        $this->updateTravellerJourneyStatusOngoing($traveller_id);
    }
    function updateTravellerJourneyStatusCompleted( $traveller_id=false ) {
        $now    = date('Y-m-d');
        $this->db->select('journey_list.*,total_hours,number_of_person,service_date,pickup_location,
                        pickup_time,number_of_person,additional_information');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->where( 'jny_traveller_id', "$traveller_id" );
        $this->db->where( 'service_date <', "$now" );
        $this->db->where( 'jny_status', 2 ); 
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $journey_id = $journey->journey_id;
                $data2      = array('jny_status' => 3,'completed_type' => 'auto');
                $table2     = $this->db->dbprefix( 'journey_list' );
                $this->db->where( 'journey_id', $journey_id );
                $this->db->update( $table2, $data2 );
            } 
        }
    }
    function updateTravellerJourneyStatusOngoing( $traveller_id=false ) {
        $now    = date('Y-m-d');
        $this->db->select('journey_list.*,total_hours,number_of_person,service_date,pickup_location,
                        pickup_time,number_of_person,additional_information');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->where( 'jny_traveller_id', "$traveller_id" );
        $this->db->where( 'service_date', "$now" );
        $this->db->where( 'jny_status', 1 ); 
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $journey_id = $journey->journey_id;
                $data2      = array('jny_status' => 2);
                $table2     = $this->db->dbprefix( 'journey_list' );
                $this->db->where( 'journey_id', $journey_id );
                $this->db->update( $table2, $data2 );
            } 
        }
    }
    function getGuiderJourneyList( $limit=false, $start=false, $guider_id=false, $filtertype=false ) {
        $data = array();

        $this->db->select('journey_list.*,traveller.first_name as tfirstName, guider.first_name as guiderfName,
                        traveller.last_name as tlastName,guider.last_name as guiderlName, traveller.ratings,
                        guider.profile_image as guiderImage, traveller.profile_image as tuserImage,
                        total_hours,number_of_person,service_date,
                        pickup_location,pickup_time,guider_charged,service_price_type_id,
                        additional_information
                        ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id','left');
        $this->db->where( 'jny_guider_id', "$guider_id" );
        if($filtertype){
            $this->db->where( 'jny_status', $filtertype ); 
        }
        $this->db->order_by( "journey_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                if($journey->guider_charged){
                    if($journey->service_price_type_id == 1){
                        $rate = $journey->guider_charged * $journey->number_of_person;
                    }else{
                        $rate = $journey->guider_charged;
                    }
                }else{ $rate  = ''; }
                if($journey->guider_feedback != ''){ $feedback = 1; }else{ $feedback = 0; }
                $commentCount   = $this->feedbackTotalCount('T',$journey->jny_traveller_id);
                $upload_path_url= $this->config->item( 'upload_path_url' );
                $profileImgPath = $upload_path_url.'t_profile/';
                $tuserImage     = ($journey->tuserImage) ? $profileImgPath.$journey->tuserImage : '';
                $data[] = array(
                            "journey_id"        => "". $journey->journey_id ."",
                            "service_id"        => "". $journey->jny_service_id ."",
                            "user_id"           => "". $journey->jny_traveller_id ."",
                            "first_name"        => "". $journey->tfirstName ."",
                            "last_name"         => "". $journey->tlastName ."",
                            "profile_image"     => "". $tuserImage ."",
                            "additional_information" => "". $journey->additional_information ."",
                            "pickup_time"       => "". $journey->pickup_time ."",
                            "total_hours"       => "". $journey->total_hours ."",
                            "subtotal"          => "". $rate ."",
                            "base_rate"         => "". $journey->guider_charged ."",
                            "number_of_person"  => "". $journey->number_of_person ."",
                            "service_date"      => "". $journey->service_date ."",
                            "pickup_location"   => "". $journey->pickup_location ."",
                            "rating"            => "". $journey->ratings ."",
                            "comments"          => intval($commentCount),
                            "is_feedback_submitted" => "". $feedback ."",
                            "status"            => "". $journey->jny_status .""
                        );        
            }
            $earnedAmount = $this->guiderEarnedAmount($guider_id);
            $result = array(
                        'response_code' => SUCCESS_CODE,
                        'response_description' => 'Get Journey list Successfully.', 
                        'result'        => 'success',
                        'totalEarnings' => $earnedAmount->totalAmount,
                        'data'          => $data
                        );
            return $result;
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Journey list found.', 'result' => 'error', 'data' => $data);
        }
    }
    function getTravellerJourneyList( $limit=false, $start=false, $traveller_id=false, $filtertype=false ) {
        $data = array();

        $this->db->select('
                        journey_list.*,traveller.first_name as tfirstName, guider.first_name as gfirstName,
                        traveller.last_name as tlastName,guider.last_name as glastName, traveller.ratings,
                        guider.rating, guider.profile_image as guserImage, traveller.profile_image as tuserImage,
                        total_hours,number_of_person,service_date,pickup_location,
                        pickup_time,guider_charged, additional_information, service_price_type_id
                        ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id','left');
        $this->db->where( 'jny_traveller_id', "$traveller_id" );
        if($filtertype){
            $this->db->where( 'jny_status', $filtertype ); 
        }
        $this->db->order_by( "journey_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                if($journey->guider_charged){
                    if($journey->service_price_type_id == 1){
                        $rate = $journey->guider_charged * $journey->number_of_person;
                    }else{
                        $rate = $journey->guider_charged;
                    }
                }else{ $rate = ''; }
                if($journey->traveller_feedback != ''){ $feedback = 1; }else{ $feedback = 0; }
                $commentCount   = $this->feedbackTotalCount('G',$journey->jny_guider_id);
                $upload_path_url= $this->config->item( 'upload_path_url' );
                $profileImgPath = $upload_path_url.'g_profile/';
                $guserImage     = ($journey->guserImage) ? $profileImgPath.$journey->guserImage : '';
                $data[] = array(
                            "journey_id"        => "". $journey->journey_id ."",
                            "service_id"        => "". $journey->jny_service_id ."",
                            "user_id"           => "". $journey->jny_guider_id ."",
                            "first_name"        => "". $journey->gfirstName ."",
                            "last_name"         => "". $journey->glastName ."",
                            "profile_image"     => "". $guserImage ."",
                            "additional_information" => "". $journey->additional_information ."",
                            "pickup_time"       => "". $journey->pickup_time ."",
                            "total_hours"       => "". $journey->total_hours ."",
                            "subtotal"          => "". $rate ."",
                            "base_rate"         => "". $journey->guider_charged ."",
                            "number_of_person"  => "". $journey->number_of_person ."",
                            "service_date"      => "". $journey->service_date ."",
                            "pickup_location"   => "". $journey->pickup_location ."",
                            "rating"            => "". $journey->ratings ."",
                            "comments"          => intval($commentCount),
                            "is_feedback_submitted" => "". $feedback ."",
                            "status"            => "". $journey->jny_status .""
                        );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Journey list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Journey list found.', 'result' => 'error', 'data' => $data);
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
        return $query->num_rows();
    }
    function InitiateSenangpay( $data ){
        $table = $this->db->dbprefix( 'senangpay_transaction' );
        $this->db->insert( $table, $data );
    }
    public function senangpayOrderInfo( $order_id ){
        $this->db->select( '*' );
        $this->db->where( 'order_id', "$order_id" );
        $query  = $this->db->get( 'senangpay_transaction' );
        return $query->row();
    }
    public function getNotCompletedSenangpayInfoByORD( $order_id ){
        $this->db->select( '*' );
        $this->db->where( 'order_id', "$order_id" );
        $this->db->where( 'pay_status !=', 1 );
        $query  = $this->db->get( 'senangpay_transaction' );
        return $query->row();
    }
    function updateSenangpayPayment( $data, $order_id ) {
        $table  = $this->db->dbprefix( 'senangpay_transaction' );
        $this->db->where( 'order_id', "$order_id" );
        $this->db->update( $table, $data );
        return true;
    }
    function updateSenangpayPaymentCancel($serviceID) {
        $data = array();
        $this->db->select('payment_id');
        $this->db->from( 'senangpay_transaction' );
        $this->db->where( 'pay_status', 2 );
        $this->db->where( 'serviceID', "$serviceID" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $paymentLists   = $query->result();
            foreach ( $paymentLists as $pay ) {
                $payment_id = $pay->payment_id;
                $data2      = array('pay_status' => 3,'senangpay_msg' => 'auto');
                $table2     = $this->db->dbprefix( 'senangpay_transaction' );
                $this->db->where( 'payment_id', $payment_id );
                $this->db->update( $table2, $data2 );
            }
        }
    }

    function bankList() {
        $data = array();
        $this->db->select('*');
        $this->db->from( 'bank_list' );
        $this->db->where( 'BankStatus', 1 );
        $this->db->order_by('BankName','ASC');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $bankLists   = $query->result();
            foreach ( $bankLists as $bank ) {
                $data[] = array(
                            "payment_id" => intval($bank->PaymentId),
                            "bank_name"  => "". $bank->BankName ."",
                            "currency"  => "". $bank->Currency .""
                            );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Bank list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Bank found.', 'result' => 'error', 'data' => $data);
        }
    }
    function updateServiceViews( $user_type, $user_id, $service_ids=false ) {

        //UPDATE SERVICE VIEW.
        if(strtoupper($user_type) == 'T'){
            $data11 = array('view_by_traveller' => 'Y');
            $this->db->where( 'service_traveller_id', $user_id );
            $this->db->where_in('service_id', $service_ids);
            $this->db->update( 'service_list', $data11 );
        } else if (strtoupper($user_type) == 'G') {
            $data22 = array('view_by_guider' => 'Y');
            $this->db->where( 'service_guider_id', $user_id );
            $this->db->where_in('service_id', $service_ids);
            $this->db->update( 'service_list', $data22 );
        }
        return array('response_code' => SUCCESS_CODE, 'response_description' => 'Updated Service view status Successfully.', 'result' => 'success', 'data' => array('success' => 1));
    }
    public function bankInfo( $PaymentId ){
        $this->db->select( '*' );
        $this->db->where( 'PaymentId', "$PaymentId" );
        $query  = $this->db->get( 'bank_list' );
        return $query->row();
    }
    function InitiatedPayment( $data ){
        $table = $this->db->dbprefix( 'ipay88_transaction' );
        $this->db->insert( $table, $data );
    }
    function updatePayment( $data, $TransactionRefId ) {
        $table  = $this->db->dbprefix( 'ipay88_transaction' );
        $this->db->where( 'TransactionRefId', "$TransactionRefId" );
        $this->db->update( $table, $data );
        return true;
    }
    function updatePaymentCancel($serviceID) {
        $data = array();
        $this->db->select('payment_id');
        $this->db->from( 'ipay88_transaction' );
        $this->db->where( 'Status', 0 );
        $this->db->where( 'serviceID', "$serviceID" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $paymentLists   = $query->result();
            foreach ( $paymentLists as $pay ) {
                $payment_id = $pay->payment_id;
                $data2      = array('Status' => 3,'iPay88Remarks' => 'auto');
                $table2     = $this->db->dbprefix( 'ipay88_transaction' );
                $this->db->where( 'payment_id', $payment_id );
                $this->db->update( $table2, $data2 );
            }
        }
    }
    public function initiatedPaymentInfo( $serviceID ){
        $this->db->select( '*' );
        $this->db->where( 'serviceID', "$serviceID" );
        $this->db->where( 'Status', 0 );
        $query  = $this->db->get( 'ipay88_transaction' );
        return $query->row();
    }
    public function processingPaymentInfo( $serviceID ){
        $this->db->select( '*' );
        $this->db->where( 'serviceID', "$serviceID" );
        $this->db->where( 'Status', 4 );
        $query  = $this->db->get( 'ipay88_transaction' );
        return $query->row();
    }
    public function getPaymentInfoByTRI( $order_id ){
        $this->db->select( '*' );
        $this->db->where( 'order_id', "$order_id" );
        $query  = $this->db->get( 'senangpay_transaction' );
        return $query->row();
    }
    public function getNotCompletedIPay88InfoByTRI( $TransactionRefId ){
        $this->db->select( '*' );
        $this->db->where( 'TransactionRefId', "$TransactionRefId" );
        $this->db->where( 'Status !=', 1 );
        $query  = $this->db->get( 'ipay88_transaction' );
        return $query->row();
    }
    function guiderEarnedAmount( $guiderID=false ) {

        $this->db->select('SUM(sub_total) AS totalAmount');
        $this->db->from('service_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = service_list.transactionID');
        $this->db->where( 'service_guider_id', "$guiderID" );
        $this->db->where( 'service_list.status', 4 );
        $this->db->where( 'senangpay_transaction.pay_status', 1 );
        $query = $this->db->get();
        return $row = $query->row();
        //return $row->totalAmount;
    }
    public function commentInfo( $cmt_id ){
        $this->db->select( '*' );
        $this->db->where( 'cmt_id', $cmt_id );
        $query  = $this->db->get( 'comments' );
        return $query->row();
    }
    public function deleteComment( $comment_id ) {
        $this->db->where( 'cmt_id', $comment_id );
        $this->db->delete('comments');
    }
    public function insertTravellerFeedback( $data ){
        $table = $this->db->dbprefix( 'traveller_feedback' );
        $this->db->insert( $table, $data );
    }
    public function insertGuiderFeedback( $data ){
        $table = $this->db->dbprefix( 'guider_feedback' );
        $this->db->insert( $table, $data );
    }
    public function getGuiderAppversion( $device_type ){
        $this->db->select( '*' );
        $this->db->where( 'device_type', $device_type );
        $query  = $this->db->get( 'guider_latest_appversion' );
        return $query->row();
    }
    public function getTravellerAppversion( $device_type ){
        $this->db->select( '*' );
        $this->db->where( 'device_type', $device_type );
        $query  = $this->db->get( 'traveller_latest_appversion' );
        return $query->row();
    }
    public function updateRequest($data, $order_id){
        $table  = $this->db->dbprefix('activity_request');
        $this->db->where( 'senangpay_orderID', "$order_id" );
        $this->db->update( $table, $data );
    }
    //GET CITY SPACES
    function get_city_space_list($city_id=false) {
        $data = array();
        $upload_path_url = $this->config->item( 'upload_path_url' );

        $this->db->select('partner_list.*,name');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        if($city_id){ $this->db->where( 'partner_list.city_id', $city_id ); }
        $this->db->where('partner_list.status != ',4,FALSE);
        $this->db->order_by("partner_name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $spaceLists   = $query->result();
            foreach ($spaceLists as $space) {
                if($space->status == 1){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                if($space->photo){
                    $space_image = $upload_path_url.'partner/'.$space->photo;
                }else{
                    $space_image = '';
                }
                $license_id = array();
                if($space->required_license){ $license_id = explode(',', $space->required_license); }
                $data[] = array(
                                "space_id" => intval($space->partner_id),
                                "space_name" => rawurldecode($space->partner_name),
                                "space_image" => "$space_image",
                                "city_name" => "$space->name",
                                "city_id" => intval($space->city_id),
                                "fees" => floatval($space->fees),
                                "address" => strip_tags($space->address),
                                "is_dbkl_required" => intval($space->dbkl_lic_enable),
                                'license_ids' => $license_id,
                                "status" => "$status"
                            );        
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Spaces list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            $data[] = array('error' => 1);
            return array('response_code' => ERROR_CODE, 'response_description' => 'No Spaces found.', 'result' => 'error', 'data' => $data);
        }
    }
    //GET AVAILABILITY
    function get_availability_list($partner_id=false, $date=false) {
        $data = array();

        $this->db->select('events.*, name, partner_name, partner_list.fees, guider.first_name');
        $this->db->from('events');
        $this->db->join('states', 'states.id = events.city_id','left');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id','left');
        $this->db->join('guider', 'guider.guider_id = events.host_id','left');
        if($partner_id){ $this->db->where( 'events.partner_id', $partner_id ); }
        if($date){ 
            $start = date('Y-m-d', strtotime( $date ));
            $this->db->where( 'DATE(start)', $start );
        }
        //$this->db->where( 'events.status', 1 );
        $this->db->where( 'events.status !=', 5 );
        $this->db->order_by("start", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $spaceLists   = $query->result();
            foreach ($spaceLists as $package) {
                if($package->status == 1){
                    $status = 'Available';
                }else if($package->status == 2){ //Progress
                    $status = 'Unavailable';
                }else if($package->status == 3){ //Booked
                    $status = 'Unavailable';
                }else if($package->status == 4){
                    $status = 'Locked';
                }else{
                    $status = '';
                }
                $data[] = array(
                                "package_id" => intval($package->id),
                                "space_uuid" => "$package->space_uuid",
                                "date"       => date('Y-m-d', strtotime($package->start)),
                                "start_time" => date('H:i', strtotime($package->start)),
                                "end_time"  => date('H:i', strtotime($package->end)),
                                "message"   => "$package->message",
                                "fees"      => floatval($package->partnerFees),
                                "host_name" => "$package->first_name",
                                "status"    => $status
                            );
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get packages list Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            return array('response_code' => ERROR_CODE, 'response_description' => 'No packages found.', 'result' => 'error', 'data' => array());
        }
    }
    //GET AVAILABILITY DATE LISTS
    function get_availability_date_list($partner_id=false) {
        $data = array();

        $this->db->select('events.*, name, partner_name, partner_list.fees, guider.first_name');
        $this->db->from('events');
        $this->db->join('states', 'states.id = events.city_id','left');
        $this->db->join('partner_list', 'partner_list.partner_id = events.partner_id','left');
        $this->db->join('guider', 'guider.guider_id = events.host_id','left');
        if($partner_id){ $this->db->where( 'events.partner_id', $partner_id ); }
        //$this->db->where( 'events.status', 1 ); //available
        $this->db->where('events.status != ',5,FALSE);
        $this->db->order_by("start", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $spaceLists   = $query->result();
            foreach ($spaceLists as $package) {
                $data[] = date('Y-m-d', strtotime($package->start));
            }
            return array('response_code' => SUCCESS_CODE, 'response_description' => 'Get availability date Successfully.', 'result' => 'success', 'data' => $data);
        }else {
            return array('response_code' => ERROR_CODE, 'response_description' => 'No packages found.', 'result' => 'error', 'data' => array());
        }
    }
    public function packageInfo( $packageId ){
        $this->db->select( '*' );
        $this->db->where( 'id', "$packageId" );
        $query  = $this->db->get( 'events' );
        return $query->row();
    }
    public function updateSpaceBookingStatus($data, $orderID){
        $table  = $this->db->dbprefix('events');
        $this->db->where('orderID', "$orderID" );
        $this->db->where( 'space_uuid !=', '' );
        $this->db->update( $table, $data );
    }
    public function updatePackageInfo($data, $packageId){
        $table  = $this->db->dbprefix('events');
        $this->db->where('id', $packageId);
        $this->db->update( $table, $data );
    }
    public function updateSpaceBookingOrder($data, $ids){
        $table  = $this->db->dbprefix('events');
        $this->db->where_in('id', $ids);
        $this->db->where( 'space_uuid !=', '' );
        $this->db->update( $table, $data );
    }
    public function packageInfoByOrder( $orderID ){
        $this->db->select( '*' );
        $this->db->where( 'orderID', "$orderID" );
        $this->db->where( 'orderID !=', '' );
        $query  = $this->db->get( 'events' );
        return $query->row();
    }
    function updateSenangpaySpacePaymentCancel() {
        $data = array();
        $this->db->select('payment_id, order_id');
        $this->db->from( 'senangpay_transaction' );
        $this->db->where( 'pay_status', 2 );
        $this->db->where( 'paymentAppType', "space_booking" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $paymentLists   = $query->result();
            foreach ( $paymentLists as $pay ) {
                $payment_id = $pay->payment_id;
                $order_id = $pay->order_id;
                if(!$this->packageInfoByOrder($order_id)){
                    $data2  = array('pay_status' => 3,'senangpay_msg' => 'auto');
                    $table2 = $this->db->dbprefix( 'senangpay_transaction' );
                    $this->db->where( 'payment_id', $payment_id );
                    $this->db->update( $table2, $data2 );
                }
            }
        }
    }

}