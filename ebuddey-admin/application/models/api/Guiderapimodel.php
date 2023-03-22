<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guiderapimodel extends CI_Model {
     public function __construct(){
        parent::__construct();
     }
    public function insertGuider( $data ){
        $table = $this->db->dbprefix( 'guider' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
    public function insertDeviceInfo( $deviceInfo, $guider_id ){
        $device_token   = $deviceInfo['HTTP_DEVICE_TOKEN'];
        $device_id      = $deviceInfo['HTTP_DEVICE_ID'];
        $app_version    = $deviceInfo['HTTP_APP_VERSION'];
        $device_type    = $deviceInfo['HTTP_DEVICE_TYPE'];
        $build_no       = $deviceInfo['HTTP_BUILD_NO'];
        if($traveller_id && $device_id && $device_token){
            $data   = array(
                        'device_token'  => $device_token,
                        'device_id'     => $device_id,
                        'guider_id'     => $guider_id
                        );
            if($device_type){ $data['device_type']      = $device_type; }
            if($app_version){ $data['app_version']      = $app_version; }
            if($build_no){ $data['build_no'] = $build_no; }
            $table  = $this->db->dbprefix( 'guider_device_info' );
            $this->db->insert( $table, $data );
        }
    }
    public function updateDeviceInfo( $deviceInfo, $guider_id ){
        $device_token   = $deviceInfo['HTTP_DEVICE_TOKEN'];
        $device_id      = $deviceInfo['HTTP_DEVICE_ID'];
        $app_version    = $deviceInfo['HTTP_APP_VERSION'];
        $device_type    = $deviceInfo['HTTP_DEVICE_TYPE'];
        $build_no       = $deviceInfo['HTTP_BUILD_NO'];
        if($device_id && $guider_id){
            $table  = $this->db->dbprefix( 'guider_device_info' );
            $data   = array(
                        'device_id'     => $device_id,
                        'guider_id'     => $guider_id,
                        'createdon'     => date("Y-m-d H:i:s")
                        );
            if($device_token){ $data['device_token']    = $device_token; }
            if($device_type){ $data['device_type']      = $device_type; }
            if($app_version){ $data['app_version']      = $app_version; }
            if($build_no){ $data['build_no'] = $build_no; }

            $this->db->where( 'device_id', $device_id );
            $this->db->where( 'guider_id', $guider_id );
            $query = $this->db->get('guider_device_info');
            if( $query->num_rows() > 0 ){
                $row = $query->row();
                $this->db->where( 'gdi_id', $row->gdi_id );
                $this->db->update( $table, $data );
            }else{ 
                if($device_token){ 
                    $this->db->insert( $table, $data ); 
                }
            }
        }
    }
    public function guiderEmailExists( $email ){
        $this->db->where( 'email', $email );
        $this->db->where( 'guider.status !=', 4 );
        $query = $this->db->get('guider');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        }else{ return false; }
    }
    public function guiderPhoneEmailExists( $phone_number, $email ){
        $this->db->where( 'email', $email );
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('guider');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function guiderPhoneDeviceIdExists( $phone_number, $device_id ){
        $this->db->where( 'device_id', $device_id );
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('guider ');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function guiderPhoneExists( $phone_number ){
        
        $this->db->select( 'guider.*,country_short_code,country_name,country_currency_code,country_currency_symbol,country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        $this->db->where('phone_number', $phone_number);
        $this->db->where( 'guider.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    //Guider Info
    public function guiderInfoByUuid( $guider_id ){
        $this->db->select( 'guider.*,states.name as cityName, specialization.specialization as categoryName, country_short_code, country_name, country_currency_code, country_currency_symbol, country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        $this->db->join('states', 'states.id = guider.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = guider.skills_category','left');
        $this->db->where('guider_id', $guider_id);
        $this->db->where( 'guider.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
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
    public function guiderSpecialityInfo( $specialization_id ) {
        $this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
    }
    /*TRAVELLER INFO*/
    public function travellerInfoByUuid( $traveller_id ){
        $this->db->select( '*' );
        $this->db->where( 'traveller_id', $traveller_id );
        $query  = $this->db->get( 'traveller' );
        $row    = $query->row();
        return $row;
    }
    //Update query
    public function updateGuiderByUuid( $data, $guider_id ){
        $table  = $this->db->dbprefix( 'guider' );
        $this->db->where( 'guider_id', $guider_id );
        $this->db->update( $table, $data );
        return true; 
    }
    public function insertGuiderActivity( $data ){
        $table = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
    function guiderActivtyExistById( $guider_id ) {
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $query = $this->db->get( 'guider_activity_list' );
        return $query->num_rows();
    }
    function guiderActivtyExists( $guider_id, $service_region ) {
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'service_providing_region', $service_region );
        $this->db->where( 'activity_status !=', 4 );
        $query = $this->db->get( 'guider_activity_list' );
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function updateGuiderActivtyByUuid( $data, $activity_id ){
        $table  = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->where( 'activity_id', $activity_id );
        $this->db->update( $table, $data );
        return true; 
    }
    public function updateGuiderActivtyStatus( $data, $activity_id, $guider_id ){
        $table  = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->where( 'activity_id', $activity_id );
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->update( $table, $data );
        return true; 
    }
    public function updateGuiderActivtyByService( $data, $guider_id, $service_region ){
        $table  = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'service_providing_region', $service_region );
        $this->db->update( $table, $data );
        return true; 
    }
    public function guiderActivtyInfoByUuid( $activity_id ){
        $this->db->select( 'guider_activity_list.*' );
        $this->db->from( 'guider_activity_list' );
        $this->db->join( 'guider', 'guider.guider_id = activity_guider_id', 'left' );
        $this->db->where('activity_id', $activity_id);
        $this->db->where( 'activity_status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        }else{
            return false;
        }
    }
    //Update validation check email 
    function updateguiderInfoByEmailExists( $email, $guider_id ) {
        $this->db->where( 'email', $email );
        $this->db->where( 'guider_id != ', $guider_id );
        $query = $this->db->get( 'guider' );
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Update validation for phone number
    public function updateguiderInfoByPhoneExists( $contact, $guider_id ){
        $this->db->where( 'phone_no', $contact );
        $this->db->where( 'guider_id != ', $guider_id );
        $query = $this->db->get( 'guider' );
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Get Request Guider List for service list table
    function getGuiderRequest( $limit=false, $start=false, $guider_id=false, $filter_type=false ) {
        $data = array();
        $this->db->reset_query();
        $this->db->select('sl.status AS reqStatus, sl.*, t.traveller_id,t.first_name,t.last_name,t.profile_image,
                        t.ratings,gal.maximum_booking,gal.additional_info_label,gal.date_time_needed');
        $this->db->from( 'service_list sl' );
        $this->db->join( 'traveller t', 't.traveller_id = sl.service_traveller_id', 'left' );
        $this->db->join( 'guider g', 'g.guider_id = sl.service_guider_id', 'left' );
        $this->db->join( 'guider_activity_list gal', 'gal.activity_id = sl.activity_id', 'left' );
        $this->db->where( 'sl.service_guider_id', $guider_id );
        if($filter_type){
            if($filter_type == 2){
                $this->db->where("(sl.status=2 OR sl.status=5)", NULL, FALSE);
            }else{
                $this->db->where( 'sl.status', $filter_type ); 
            }
        }
        $this->db->where( 'sl.status !=', 4 );
        $this->db->order_by( "sl.service_id", "desc" );
        if($limit || $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $serviceLists   = $query->result();
            $status         = '';
            foreach ( $serviceLists as $service ) {
                if($service->guider_charged){
                    if($service->service_price_type_id == 1){
                        $rate    = $service->guider_charged * $service->number_of_person;
                    }else{
                        $rate    = $service->guider_charged;
                    }
                }else{ $rate = ''; }
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $profileImgPath  = $upload_path_url.'t_profile/';
                $profile_image   = ($service->profile_image) ? $profileImgPath.$service->profile_image : '';
                $commentCount1   = $this->feedbackTotalCount('T',$service->service_traveller_id);
                $commentCount2   = $this->commentTotalCount('T',$service->service_traveller_id);
                $commentCount    = $commentCount1 + $commentCount2;
                $data[] = array(
                        "request_primary_id"     => intval($service->service_id),
                        "booking_request_id"     => $service->booking_request_id,
                        "traveller_id"           => intval($service->service_traveller_id),
                        "first_name"             => $service->first_name,
                        "last_name"              => $service->last_name,
                        "what_i_offer"           => $service->activity_desc,
                        "profile_pic"            => $profile_image,
                        "rating"                 => floatval($service->ratings),
                        "comment_count"          => intval($commentCount),
                        "number_of_person"       => intval($service->number_of_person),
                        "pickup_date"            => $service->service_date,
                        "pickup_time"            => $service->pickup_time,
                        "additional_information" => $service->additional_information,
                        "service_charge_percentage" => floatval($service->current_processing_fee),
                        "rate_per_person"        => floatval($service->guider_charged),
                        "price_type_id"          => intval($service->service_price_type_id),
                        "country_currency_symbol"=> $service->guider_currency_symbol,
                        "subtotal"               => floatval($rate),
                        "feedback"               => $service->feedback,
                        "created_on"             => $service->createdon,
                        "status"                 => intval($service->reqStatus),
                        "maximum_booking"        => intval($service->maximum_booking),
                        "additional_info_label"  => $service->additional_info_label,
                        "date_time_needed"       => intval($service->date_time_needed)
                        );        
            }
            $messageCount    = $this->guiderMessageCount($guider_id);
            $requestCount1   = $this->guiderRequestCount($guider_id,1);
            $requestCount2   = $this->guiderRequestCount($guider_id,2);
            $requestCount3   = $this->guiderRequestCount($guider_id,3);
            $jnyCount1       = $this->guiderJnyCount($guider_id,1);
            $jnyCount2       = $this->guiderJnyCount($guider_id,2);
            $jnyCount3       = $this->guiderJnyCount($guider_id,3);
            $result = array(
                        'response_code' => SUCCESS_CODE, 
                        'response_description' => 'Get Request List Successfully.', 
                        'new_request_count'         => $requestCount1,
                        'pending_request_count'     => $requestCount2,
                        'cancelled_request_count'   => $requestCount3,
                        'upcoming_journey_count'    => $jnyCount1,
                        'inprogress_journey_count'  => $jnyCount2,
                        'completed_journey_count'   => $jnyCount3,
                        'message_count'             => $messageCount,
                        'result' => 'success',
                        'data' => $data
                        );
            return $result;
        } else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Request found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    public function autoCompletedExpiryRequest( $guider_id ){
        $today    = date('Y-m-d');

        $this->db->select('service_id,service_date');
        $this->db->from('service_list');
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'service_date <', $today );
        $this->db->where( 'service_date !=', '0000-00-00' );
        $this->db->where("(status=1 OR status=2)", NULL, FALSE);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $request_list   = $query->result();
            foreach ( $request_list as $request ) {
                $service_id = $request->service_id;
                $data2      = array('status' => 3, 'cancelled_by' => 'A');
                $table2     = $this->db->dbprefix( 'service_list' );
                $this->db->where( 'service_id', $service_id );
                $this->db->update( $table2, $data2 );
            }
        } 
    }
    function getMyJourneyList( $limit=1000, $start=false, $guider_id=false ) {
        $data = array();
        //OLD BOOKING METHOD
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('journey_list.*,traveller.first_name as travellerFName, traveller.last_name as travellerLName, 
                        traveller.ratings as travellerRating, traveller.profile_image as travellerImage, 
                        guider_charged,number_of_person,service_date,pickup_time,additional_information,
                        service_list.activity_id,activity_desc, service_price_type_id,feedback,guider_currency_symbol,
                        current_processing_fee,service_price_type_id,booking_request_id,order_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id','left');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->where( 'jny_guider_id', "$guider_id" );
        $this->db->where( 'jny_status', 3 );
        $this->db->group_by('journey_id');
        $this->db->order_by( "journey_id", "desc" );
        //if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $t_rating     = floatval($journey->travellerRating);
                if($journey->guider_charged){
                    if($journey->service_price_type_id == 1){
                        $rate    = $journey->guider_charged * $journey->number_of_person;
                    }else{
                        $rate    = $journey->guider_charged;
                    }
                }else{ $rate     = ''; }
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $profileImgPath  = $upload_path_url.'t_profile/';
                $profile_image   = ($journey->travellerImage) ? $profileImgPath.$journey->travellerImage : '';
                $commentCount1   = $this->feedbackTotalCount('G',$journey->jny_guider_id);
                $commentCount2   = $this->commentTotalCount('G',$journey->jny_guider_id);
                $commentCount3   = $this->webcommentTotalCount('G',$journey->jny_guider_id);
                $commentCount    = $commentCount1 + $commentCount2 + $commentCount3;
                //0 - Not given,1- Terrible,2- Wonderful
                if($journey->traveller_rating == 0){
                    $ratings_type = 0; 
                }elseif($journey->traveller_rating == 1){ 
                    $ratings_type = 1; 
                }elseif($journey->traveller_rating == 5){
                    $ratings_type = 2;
                }else{
                    $ratings_type = 0;
                }
                if($journey->order_id){
                    $order_id   = $journey->order_id;
                }else{
                    $order_id   = '';
                }
                $activityInfo = $this->Guiderapimodel->guiderActivtyInfoByUuid( $journey->activity_id );
                $data[] = array(
                            "request_primary_id"     => intval($journey->jny_service_id),
                            "booking_request_id"     => $journey->booking_request_id,
                            "traveller_id"           => intval($journey->jny_traveller_id),
                            "guider_id"              => intval($journey->jny_guider_id),
                            "first_name"             => $journey->travellerFName,
                            "last_name"              => $journey->travellerLName,
                            "what_i_offer"           => $journey->activity_desc,
                            "profile_pic"            => $profile_image,
                            "rating"                 => $t_rating,
                            "comment_count"          => intval($commentCount),
                            "number_of_person"       => intval($journey->number_of_person),
                            "pickup_date"            => $journey->service_date,
                            "pickup_time"            => $journey->pickup_time,
                            "additional_information" => $journey->additional_information,
                            "service_charge_percentage" => floatval($journey->current_processing_fee),
                            "rate_per_person"        => floatval($journey->guider_charged),
                            "price_type_id"          => intval($journey->service_price_type_id),
                            "country_currency_symbol"=> $journey->guider_currency_symbol,
                            "subtotal"               => floatval($rate),
                            "feedback"               => $journey->feedback,
                            "created_on"             => $journey->createdon,
                            "ratings_type"           => intval($ratings_type),
                            "ratings_comment_msg"    => $journey->traveller_feedback,
                            "transaction_ref_id"     => $order_id,
                            "status"                 => intval($journey->jny_status),
                            "maximum_booking"        => intval($activityInfo->maximum_booking),
                            "additional_info_label"  => $activityInfo->additional_info_label,
                            "date_time_needed"       => intval($activityInfo->date_time_needed),
                            "is_space_booking"       => 0,
                            "order_date"             => $journey->service_date,
                            "space_info"             => array(
                                                        "partner_name"  => '',
                                                        "partner_id"    => intval(0),
                                                        "partner_image" => '',
                                                        "city"          => '',
                                                        "type_of_transaction" => 0,
                                                        "city_id"       => intval(0),
                                                        "date"          => '',
                                                        "additional_info"=> '',
                                                        "total_price"   => floatval(0),
                                                        "currency"      => '',
                                                        "package_info"  => array()
                                                        )
                        );
            }
        }
        $data2  = $this->getMySpaceBookingList($guider_id);
        $data   = array_merge($data, $data2);
        usort($data, array('Guiderapimodel','date_compare'));

        if($data){
            $requestCount1  = $this->guiderRequestCount($guider_id,1);
            $requestCount2  = $this->guiderRequestCount($guider_id,2);
            $requestCount3  = $this->guiderRequestCount($guider_id,3);
            $jnyCount1      = $this->guiderJnyCount($guider_id,1);
            $jnyCount2      = $this->guiderJnyCount($guider_id,2);
            $jnyCount3      = $this->guiderJnyCount($guider_id,3);
            $messageCount   = $this->guiderMessageCount($guider_id);
            $result         = array(
                                'response_code' => SUCCESS_CODE,
                                'result'        => 'success', 
                                'response_description'      => 'Get Journey list Successfully.',
                                'new_request_count'         => $requestCount1,
                                'pending_request_count'     => $requestCount2,
                                'cancelled_request_count'   => $requestCount3,
                                'upcoming_journey_count'    => $jnyCount1,
                                'inprogress_journey_count'  => $jnyCount2,
                                'completed_journey_count'   => $jnyCount3,
                                'message_count'             => $messageCount,
                                'data'                      => $data
                            );
            return $result;
        }else{
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Journey list found.', 'result' => 'error', 'data' => $data);
            return $result;
        }
    }
    public function getMySpaceBookingList($guider_id){
        $data = array();
        $upload_path_url = $this->config->item( 'upload_path_url' );
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('events.*');
        $this->db->from('events');
        $this->db->join('guider', 'guider.guider_id = events.host_id');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = events.transactionID','left');
        $this->db->where( 'host_id', "$guider_id" );
        $this->db->where( 'events.status', 3 );
        //$this->db->group_by('DATE(start)');
        $this->db->group_by(array("DATE(start)", "orderID"));
        //$this->db->group_by('orderID');
        $this->db->order_by( "start", "desc" );
     	$query = $this->db->get();

        // $str = $this->db->last_query();
    	// echo "<pre>";
    	// print_r($str);
    	// exit;
        if( $query->num_rows() > 0 ) {
            $spaceLists = $query->result();
            foreach ( $spaceLists as $space ) {
                $partnerInfo = $this->partnerInfo($space->partner_id);
                $cityInfo    = $this->cityInfo( $space->city_id );
                if($cityInfo){ $cityName = $cityInfo->name; }else{ $cityName = ''; }
                $myPackageList = $this->myPackageListByDate($guider_id, $status, date('Y-m-d', strtotime($space->start)), $space->orderID);
                $packageArray  = array();
                $totalPrice    = 0;
                if($myPackageList){
                    foreach ($myPackageList as $key => $package) {
                        $packageArray[] = array(
                                            "package_id" => intval($package->id),
                                            "start_time" => date('H:i', strtotime($package->start)),
                                            "end_time"   => date('H:i', strtotime($package->end)),
                                            "price"      => floatval($package->partnerFees)
                                            );
                        $totalPrice += $package->partnerFees;
                    }
                }
                if($space->orderID){ 
                    /*(0 - SenangPay, 1 - Admin, 2 - Free)*/
                    if (substr($space->orderID, 0, 3) === 'TXN'){
                        $type_of_txn = 0;
                    }else if(substr($space->orderID, 0, 3) === 'CMS'){
                        $type_of_txn = 1;
                    }else if(substr($space->orderID, 0, 3) === 'FBT'){
                        $type_of_txn = 2;
                    }else{
                        $type_of_txn = 0;
                    }
                }else{
                    $type_of_txn = 0;
                }
                if($partnerInfo->photo){
                    $space_image = $upload_path_url.'partner/'.$partnerInfo->photo;
                }else{
                    $space_image = '';
                }
                $spaceInfo = array(
                                "partner_name"  => rawurldecode($partnerInfo->partner_name),
                                "partner_id"    => intval($partnerInfo->partner_id),
                                "partner_image" => $space_image,
                                "city"          => $cityName,
                                "type_of_transaction" => $type_of_txn,
                                "city_id"       => intval($partnerInfo->city_id),
                                "date"          => date('Y-m-d', strtotime($space->start)),
                                "space_booked_date" => date('Y-m-d', strtotime($space->start)),
                                "transaction_date" => (($space->paidDatetime != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($space->paidDatetime)) : ''), 
                                "additional_info"=> (($partnerInfo->address) ? strip_tags($partnerInfo->address) : ''),
                                "total_price"   => floatval($totalPrice),
                                "currency"      => 'RM',
                                "package_info"  => $packageArray
                                );
                $data[] = array(
                            "request_primary_id"     => 0,
                            "booking_request_id"     => '',
                            "traveller_id"           => 0,
                            "first_name"             => '',
                            "last_name"              => '',
                            "what_i_offer"           => '',
                            "profile_pic"            => '',
                            "rating"                 => floatval(0),
                            "comment_count"          => intval(0),
                            "number_of_person"       => intval(0),
                            "pickup_date"            => '',
                            "pickup_time"            => '',
                            "additional_information" => '',
                            "service_charge_percentage" => floatval(0),
                            "rate_per_person"        => floatval(0),
                            "price_type_id"          => intval(0),
                            "country_currency_symbol"=> '',
                            "subtotal"               => floatval(0),
                            "feedback"               => '',
                            "created_on"             => '',
                            "ratings_type"           => '',
                            "ratings_comment_msg"    => '',
                            "transaction_ref_id"     => '',
                            "status"                 => intval($space->status),
                            "maximum_booking"        => intval(0),
                            "additional_info_label"  => '',
                            "date_time_needed"       => intval(0),
                            "is_space_booking"       => 1,
                            "order_date"    => date('Y-m-d', strtotime($space->start)),
                            "package_id"    => intval($space->id),
                            "space_uuid"    => "$space->space_uuid",
                            "date"          => date('Y-m-d', strtotime($space->start)),
                            "start_time"    => date('H:i', strtotime($space->start)),
                            "end_time"      => date('H:i', strtotime($space->end)),
                            "message"       => "$space->message",
                            "fees"          => floatval($space->partnerFees),
                            "host_name"     => "$space->first_name",
                            "space_info"    => $spaceInfo
                        );
            }
        }
        return $data;
    }
    public function myPackageListByDate( $guider_id, $status='', $date, $orderID ){

        $this->db->select('events.*');
        $this->db->from('events');
        $this->db->join('guider', 'guider.guider_id = events.host_id','left');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = events.transactionID','left');
        $this->db->where( 'host_id', "$guider_id" );
        if($status){ $this->db->where( 'events.status', $status ); }
        $this->db->where( 'events.status !=', 5 );
        $this->db->where( 'DATE(start)', $date );
        $this->db->where( 'orderID', "$orderID" );
        $this->db->order_by( "start", "asc" );
        $query = $this->db->get();
        return $query->result();
    }
    function date_compare($a, $b)
    {
        $t1 = strtotime($a['order_date']);
        $t2 = strtotime($b['order_date']);
        //return ($t1 < $t2) ? 1 : (($t1 > $t2) ? -1 : 0); //desc
        return $t1 - $t2; //asc
    }

    public function partnerInfo( $partner_id ){
        $this->db->select( '*' );
        $this->db->where( 'partner_id', $partner_id );
        $query  = $this->db->get( 'partner_list' );
        return $query->row();
    }
    public function cityInfo( $city_id ){
        $this->db->select( '*' );
        $this->db->where( 'id', $city_id );
        $query  = $this->db->get( 'states' );
        $row    = $query->row();
        return $row;
    }
    public function autoCompletedExpiryJourney( $guider_id ){
        $today    = date('Y-m-d');

        $this->db->select('journey_id,service_date');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where( 'jny_guider_id', $guider_id );
        $this->db->where( 'service_date <=', $today );
        $this->db->where( 'service_date !=', '0000-00-00' );
        $this->db->where( 'jny_status !=', 3 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journey_list   = $query->result();
            foreach ( $journey_list as $journey ) {
                $journey_id = $journey->journey_id;
                if($journey->service_date == $today){
                    $data2  = array('jny_status' => 2);
                }else{
                    $data2  = array('jny_status' => 3, 'completed_type' => 'auto');
                }
                $table2     = $this->db->dbprefix( 'journey_list' );
                $this->db->where( 'journey_id', $journey_id );
                $this->db->update( $table2, $data2 );
            }
        } 
    }
    public function insertComments( $data ){
        $table = $this->db->dbprefix( 'comments' );
        $this->db->insert( $table, $data );
        return $this->db->insert_id();
    }
    function getCommentsList( $limit=false, $start=false, $guider_id=false ) {
        $data = array();
        $sql     = "SELECT cmt_id,guider_id,traveller_id,cmt_email,comments,createdon,comments_type 
                    FROM ( 
                    SELECT jny_service_id as cmt_id,jny_guider_id as guider_id,jny_traveller_id as traveller_id,cmt_email,
                    guider_feedback as comments,guider_feedbackon as createdon ,comments_type 
                    FROM journey_list 
                    WHERE jny_guider_id = $guider_id AND guider_feedback != ''
                    UNION 
                    SELECT cmt_id,cmt_guider_id as guider_id,cmt_traveller_id,cmt_email,comments,createdon,comments_type 
                    FROM comments 
                    WHERE cmt_guider_id = $guider_id AND sender_type = 2 AND comments != ''
                    UNION 
                    SELECT web_cmt_id,guider_id,cmt_name,cmt_email,cmt_messge,createdon,comments_type 
                    FROM web_comments 
                    WHERE guider_id = $guider_id AND cmt_messge != ''
                    ) A 
                    ORDER BY createdon DESC 
                    LIMIT $start,$limit";
        $query    = $this->db->query($sql);
        if( $query->num_rows() > 0 ) {
            $commentLists   = $query->result();
            foreach ( $commentLists as $comment ) {
                if($comment->comments_type != 3){
                    $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $comment->traveller_id );
                    $profileImgPath = $this->config->item( 'upload_path_url' ).'t_profile/';
                    $profile_image  = ($travellerInfo->profile_image) ? $profileImgPath.$travellerInfo->profile_image : '';
                    $first_name     = $travellerInfo->first_name;
                    $last_name      = $travellerInfo->last_name;
                    $email          = $travellerInfo->email;
                    $country_name   = $travellerInfo->country_name;
                    $city           = $travellerInfo->city;
                    $traveller_id   = $comment->traveller_id;
                }else{
                    $profile_image  = '';
                    $first_name     = $comment->traveller_id;
                    $last_name      = '';
                    $email          = $comment->cmt_email;
                    $country_name   = '';
                    $city           = '';
                    $traveller_id   = '';
                }
                if($comment->rating){ $is_rated = 1; }else{ $is_rated = 0; }
                $data[]         = array(
                                    'comment_id'     => intval($comment->cmt_id),
                                    'guider_id'      => intval($comment->guider_id),
                                    'traveller_id'   => intval($traveller_id),
                                    'comments'       => $comment->comments,
                                    'first_name'     => $first_name,
                                    'last_name'      => $last_name,
                                    'email_address'  => $email,
                                    'profile_pic'    => $profile_image,
                                    'country'        => $country_name,
                                    'city'           => $city,
                                    'comments_type'  => intval($comment->comments_type),
                                    'created_on'     => $comment->createdon
                                );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get comments List Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No comments list found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    function guiderServiceCount( $guider_id, $status=false ) {
        if(!$status){ $status = 1; }
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'view_by_guider', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function guiderInprgsJnyCount( $guider_id ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_guider_id', $guider_id );
        $this->db->where( 'jny_status', 2 );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function guiderServiceIDs( $guider_id, $status=false ) {
        $ids = '';
        if(!$status){ $status = 1; }
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'view_by_guider', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        $rows  = $query->num_rows();
        if( $rows > 0 ) {
            $servicelists   = $query->result();
            foreach ( $servicelists as $service ) {
                $ids = ($ids == '' ? '' : $ids . '-') . $service->service_id;
            }
        }
        return $ids;
    }
    function guiderMessageCount( $guider_id=false ) {
        $data = array();
        $this->db->select('msg_id');
        $this->db->from( 'messages' );
        $this->db->where( 'guider_seen', 'N' );
        $this->db->where('
                    ((msg_post_user_type = "G" AND msg_post_user_id = '.$guider_id.')
                    OR 
                    ( msg_receive_user_type = "G" AND msg_receive_user_id = '.$guider_id.'))
                    ', NULL, FALSE);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function guiderInfo( $guider_id ) {
        $this->db->select( 'guider.*,states.name as cityName, specialization.specialization as categoryName, country_short_code, country_name, country_currency_code, country_currency_symbol, country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        $this->db->join('states', 'states.id = guider.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = guider.skills_category','left');
        $this->db->where('guider_id', $guider_id);
        $query = $this->db->get();
        $rows  = $query->num_rows();
        if( $rows > 0 ) {
            $guiderInfo     = $query->row();
            $randActivityIfo  = $this->getGuiderRandomActivity($guiderInfo->guider_id);
            //print_r($getMyActivity);
            if(count($randActivityIfo) > 0){
                $what_i_offer        = $randActivityIfo['what_i_offer'];
                $cancellation_policy = $randActivityIfo['cancellation_policy'];
                $spec                = $randActivityIfo['guiding_speciality'];
                $regionName          = $randActivityIfo['service_providing_region'];
                $rate_per_person     = $randActivityIfo['rate_per_person'];
                $price_type_id       = $randActivityIfo['price_type_id'];
                $photo               = $randActivityIfo['photo_1'];
                $photo1              = $randActivityIfo['photo_2'];
                $photo2              = $randActivityIfo['photo_3'];
                
            }else{
                $what_i_offer           = '';
                $cancellation_policy    = '';
                $spec                   = [];
                $regionName             = '';
                $rate_per_person        = '';
                $price_type_id          = 0;
                $photo                  = '';
                $photo1                 = '';
                $photo2                 = '';
            }
            
            $serviceCount   = $this->guiderServiceCount($guiderInfo->guider_id);
            $inprgsJnyCount = $this->guiderInprgsJnyCount($guiderInfo->guider_id);
            $serviceIDs     = $this->guiderServiceIDs($guiderInfo->guider_id);
            $messageCount   = $this->guiderMessageCount($guiderInfo->guider_id);
            $commentCount1  = $this->feedbackTotalCount('G',$guiderInfo->guider_id);
            $commentCount2  = $this->commentTotalCount('G',$guiderInfo->guider_id);
            $commentCount3   = $this->webcommentTotalCount('G',$guiderInfo->guider_id);
            $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
            $processing_fee = $this->Serviceapimodel->siteInfo('_processing_fee');
            if(!$processing_fee){ $processing_fee = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value;}
            if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
            $upload_path_url    = $this->config->item( 'upload_path_url' );
            $profileImgPath     = $upload_path_url.'g_profile/';
            $activityImgPath    = $upload_path_url.'g_activity/';
            $profile_image      = ($guiderInfo->profile_image) ? $profileImgPath.$guiderInfo->profile_image : '';
            $img_id_proof       = ($guiderInfo->id_proof) ? $upload_path_url.'identity/'.$guiderInfo->id_proof : '';
            $dbkl_lic           = ($guiderInfo->dbkl_lic) ? $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic : '';
            if($guiderInfo->age == '0000-00-00'){
                $age    = 0;
            }else{
                $age    = date_diff(date_create($guiderInfo->age), date_create('today'))->y;
            }
            $lang = [];
            if($guiderInfo->languages_known){
                $array =  explode(',', $guiderInfo->languages_known);
                foreach ($array as $item) {
                    $langInfo = $this->Guiderapimodel->guiderLangInfo($item);
                    if($langInfo){ $lang[] = $langInfo->language; }
                }
            }
            if(!$photo && !$photo1 && !$photo2){
                $photo  = $upload_path_url.'default_service.png';
            }
            if(!$photo && !$photo1 && !$photo2){
                $photo  = $upload_path_url.'default_service.png';
            }
            $res_data       = array(
                                'guider_id'     => intval($guiderInfo->guider_id),
                                'g_id'          => $guiderInfo->guider_id,
                            	'device_id'     => "",
                                'first_name'    => $guiderInfo->first_name,
                                'last_name'     => $guiderInfo->last_name,
                                'email'         => $guiderInfo->email,
                                'phone_number'  => $guiderInfo->phone_number,
                                'country_code'  => $guiderInfo->countryCode,
                                'about_me'      => $guiderInfo->about_me,
                                'age'           => $age,
                                'DOB'           => $guiderInfo->age,
                                'languages_known'=> $lang,
                                'acc_no'        => $guiderInfo->acc_no,
                                'acc_name'      => $guiderInfo->acc_name,
                                'bank_name'     => $guiderInfo->bank_name,
                                'branch_name'   => $guiderInfo->branch_name,
                                'service_providing_region'=> $regionName,
                                'guiding_speciality' => $spec,
                                'what_i_offer'    => $what_i_offer,
                                'rate_per_person' => floatval($rate_per_person),
                                'price_type_id'   => intval($price_type_id),
                                'id_proof'        => $img_id_proof,
                                'activity_photo_1'=> $photo,
                                'activity_photo_2'=> $photo1,
                                'activity_photo_3'=> $photo2,
                                'profile_pic'     => $profile_image,
                                'dbkl_lic'        => $dbkl_lic,
                                'dbkl_lic_no'     => $guiderInfo->dbkl_lic_no,
                                'is_dbkl_uploaded'=> intval($guiderInfo->dbkl_status),
                                'nric_number'     => $guiderInfo->nric_number,
                                'city_id'         => intval($guiderInfo->city),
                                'city_name'       => ($guiderInfo->cityName)? $guiderInfo->cityName : '',
                                'category_id'     => intval($guiderInfo->skills_category),
                                'category_name'   => ($guiderInfo->categoryName)? rawurldecode($guiderInfo->categoryName) : '',
                                'skill_name'      => $guiderInfo->sub_skills,
                                'created_on'      => $guiderInfo->created_on,
                                'rating'          => floatval($guiderInfo->rating),
                                'cancellation_policy' => $cancellation_policy,
                                'status'          => intval($guiderInfo->status),
                                'comment_count'   => intval($commentCount),
                                'country_name'    => $guiderInfo->country_name,
                                'country_short_code'   => $guiderInfo->country_short_code,
                                'country_currency_code'=> $guiderInfo->country_currency_code,
                                'country_currency_symbol'=> $guiderInfo->country_currency_symbol,
                                'country_time_zone'    => $guiderInfo->country_time_zone,
                                'serviceCharge'   => $processing_fee,
                                'serviceCount'    => $serviceCount,
                                'inProgress'      => $inprgsJnyCount,
                                'serviceIDs'      => $serviceIDs,
                                'messageCount'    => $messageCount,
								'strength'    => $guiderInfo->strength,
                                'status'          => intval($guiderInfo->status)
                                );
            $profilePer = 0;
            if($guiderInfo->first_name == '' || $guiderInfo->last_name == '' || $guiderInfo->age == '0000-00-00' || 
                $guiderInfo->phone_number == '' || $guiderInfo->email == '' || $guiderInfo->about_me == '' ||
                $guiderInfo->languages_known == ''){
                $profile_updated    = 0;
            }else{
                $profilePer         += 17;
                $profile_updated    = 1;
            }
            if($guiderInfo->acc_no == '' || $guiderInfo->acc_name == '' || $guiderInfo->bank_name == ''){
                $bank_updated    = 0;
            }else{
                $profilePer     += 17;
                $bank_updated    = 1;
            }
            if( $rate_per_person == '' || $spec == '' || $what_i_offer == '' || $regionName == '' ){
                $service_updated = 0;
            }else{
                $profilePer     += 17;
                $service_updated = 1;
            }
            if($guiderInfo->id_proof){
                $profilePer          += 17;
                $is_identity_uploaded = 1;
            }else{ 
                $is_identity_uploaded = 0; 
            }
            if($photo == '' && $photo1 == '' && $photo2 == ''){
                $is_activity_pic_uploaded = 0;
            }else{
                $is_activity_pic_uploaded   = 1;
            }
            if($photo){ $profilePer += 5; }
            if($photo1){ $profilePer += 5; }
            if($photo2){ $profilePer += 5; }
            if($guiderInfo->profile_image == ''){
                $is_profile_pic_uploaded  = 0;
            }else{
                $profilePer                 += 17;
                $is_profile_pic_uploaded    = 1;
            }
            $profile_strength = $profilePer;
            $result     = array(
                            'response_code'     => SUCCESS_CODE, 
                            'response_description' => 'Get '.HOST_NAME.' information successfully.',
                            'result'            => 'success',
                            'is_profile_updated'=> $profile_updated,
                            'is_bank_updated'   => $bank_updated,
                            'is_service_updated'=> $service_updated,
                            'is_identity_uploaded'=> $is_identity_uploaded,
                            'is_activity_pic_uploaded' => $is_activity_pic_uploaded,
                            'is_profile_pic_uploaded'=> $is_profile_pic_uploaded,
                            'is_dbkl_uploaded'  => intval($guiderInfo->dbkl_status),
                            'profile_strength'  => $profile_strength,
                            'is_new_user'       => 0,
                            'data'              => $res_data
                            );
            return $result;
        } else {
            $result = array( 'response_code'=> ERROR_CODE,'response_description' => 'Please Enter valid '.HOST_NAME.' id.', 'result' => 'error', 'data' => array('error' => 1) );
            return $result; 
        }
    }
    function getCompletedJourneyList( $guider_id=false, $filtertype=false, $date=false ) {
        $data = array();
        if($date){
            $day    = date('Y-m-d',strtotime($date));
            $month  = date('m',strtotime($date));
            $year   = date('Y',strtotime($date));
        }
        $this->db->select('journey_list.*,traveller.first_name as tfirstName, guider.first_name as guiderfName,
                        traveller.last_name as tlastName,guider.last_name as guiderlName, traveller.ratings,
                        guider.profile_image as guiderImage, traveller.profile_image as tuserImage,
                        total_hours,number_of_person,service_date,pickup_location,pickup_time,guider_charged,
                        additional_information, service_price_type_id
                        ');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id','left');
        $this->db->where( 'jny_guider_id', "$guider_id" );
        $this->db->where( 'jny_status', 3 );
        if(strtoupper($filtertype) == 'D' && $date){
            $this->db->where('service_date', $day);
        }elseif (strtoupper($filtertype) == 'M' && $date){
            $this->db->where('MONTH(service_date)', $month);
            $this->db->where('YEAR(service_date)', $year);
        }elseif (strtoupper($filtertype) == 'Y' && $date){
            $this->db->where('YEAR(service_date)', $year);
        }
        $this->db->order_by( "journey_id", "desc" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                if($journey->guider_charged){
                    if($journey->service_price_type_id == 1){
                        $rate   = $journey->guider_charged * $journey->number_of_person;
                    }else{
                        $rate   = $journey->guider_charged;
                    }
                }else{ $rate    = ''; }
                if($journey->guider_feedback != ''){ $feedback = 1; }else{ $feedback = 0; }
                $commentCount1   = $this->feedbackTotalCount('T',$journey->jny_traveller_id);
                $commentCount2   = $this->commentTotalCount('T',$journey->jny_traveller_id);
                $commentCount    = $commentCount1 + $commentCount2;
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'t_profile/';
                $tuserImage         = ($journey->tuserImage) ? $profileImgPath.$journey->tuserImage : '';
                $data[] = array(
                            "journey_id"        => "". $journey->journey_id ."",
                            "service_id"        => "". $journey->jny_service_id ."",
                            "traveller_id"      => "". $journey->jny_traveller_id ."",
                            "first_name"        => "". $journey->tfirstName ."",
                            "last_name"         => "". $journey->tlastName ."",
                            "profile_image"     => "". $tuserImage ."",
                            "additional_information" => "". $journey->additional_information ."",
                            "pickup_time"       => "". $journey->pickup_time ."",
                            "total_hours"       => "". $journey->total_hours ."",
                            "subtotal"          => "". $rate ."",
                            "number_of_person"  => "". $journey->number_of_person ."",
                            "service_date"      => "". $journey->service_date ."",
                            "pickup_location"   => "". $journey->pickup_location ."",
                            "rating"            => floatval($journey->ratings),
                            "comments"          => intval($commentCount),
                            "is_feedback_submitted" => "". $feedback ."",
                            "status"            => intval($journey->jny_status)
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Journey list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Journey list found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    //Get Laguage List
    function get_language_lists() {
        $data = array();
        $this->db->select('*');
        $this->db->from('guider_language');
        $this->db->order_by("language", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $languageLists   = $query->result();
            foreach ( $languageLists as $language ) {
                $data[] = array(
                        "lang_id"   => intval($language->lang_id),
                        "language"  => $language->language,
                        "status"    => intval($language->status)
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Language list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Language found.', 'result' => 'error', 'data'=>$data);
            return $result; 
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
    function webcommentTotalCount($type=false,$user_id=false) {
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
    function updateMessageList( $ruser_id=false, $puser_id=false, $ruser_type='G', $puser_type='T' ) {
        $msg_ids = array();

        $this->db->select('*');
        $this->db->from( 'messages' );
        $this->db->where( 'guider_seen', 'N' );
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
            $data22 = array('guider_seen' => 'Y');
            $this->db->where_in('msg_id', $msg_ids);
            $this->db->update( 'messages', $data22 );
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Updated Messages list Successfully.', 'result' => 'success', 'data' => array('success' => 1));
            return $result;
        }else {
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => array('error' => 1));
            return $result; 
        }
    }
    function guiderRequestCount( $guider_id, $status ) {
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function guiderRequestUnreadCount( $guider_id, $status ) {
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'view_by_guider', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function guiderJnyCount( $guider_id, $status ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_guider_id', $guider_id );
        $this->db->where( 'jny_status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function guiderJnyUnreadCount( $guider_id, $status ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_guider_id', $guider_id );
        $this->db->where( 'jny_view_by_guider', 'N' );
        $this->db->where( 'jny_status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    
    function getGuiderRandomActivity( $guider_id ) {
        $data = array();
        $this->db->select('*');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "asc");
        $this->db->limit(1);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $activityLists   = $query->result();
            if( count($activityLists) > 0 ) {
                $randIndex  = array_rand($activityLists);
                $activity   = $activityLists[$randIndex];
                $spec   = [];
                $img    = [];
                if($activity->guiding_speciality){
                    $array  = explode(',', $activity->guiding_speciality);
                    foreach ($array as $item) {
                        $specInfo = $this->guiderSpecialityInfo($item);
                        if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                    }
                }
                if($activity->photo_1){
                    $img[]  = $activity->photo_1;
                }
                if($activity->photo_2){
                    $img[]  = $activity->photo_2;
                }
                if($activity->photo_3){
                    $img[]  = $activity->photo_3;
                }
                $regionInfo     = $this->stateInfoByid($activity->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                $data = array(
                        'activity_id'           => intval($activity->activity_id),
                        'rate_per_person'       => floatval($activity->rate_per_person),
                        'price_type_id'         => intval($activity->price_type_id),
                        'photo_1'               => $activity->photo_1,
                        'photo_2'               => $activity->photo_2,
                        'photo_3'               => $activity->photo_3,
                        'service_providing_region' => $regionName,
                        'guiding_speciality'    => $spec,
                        'guiding_speciality2'   => $activity->guiding_speciality,
                        'what_i_offer'          => $activity->what_i_offer,
                        'cancellation_policy'   => $activity->cancellation_policy,
                        'activity_img'          => $img,
                        'currency_preferrable'  => '',
                        );
            }
            return $data;
        }else {
            return $data; 
        }
    }
    function getMyActivity( $guider_id ) {
        $data   = array();
        $this->db->select('*');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $activityLists   = $query->result();
            foreach ( $activityLists as $activity ) {
                $spec   = [];
                if($activity->guiding_speciality){
                    $array  = explode(',', $activity->guiding_speciality);
                    foreach ($array as $item) {
                        $specInfo = $this->guiderSpecialityInfo($item);
                        if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                    }
                }
                $activity_photo_1   = $activity->photo_1;
                $activity_photo_2   = $activity->photo_2;
                $activity_photo_3   = $activity->photo_3;
                $regionInfo         = $this->stateInfoByid($activity->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                if($activity->activity_status == 1){
                    $status = 'active';
                }else{
                    $status = 'inactive';
                }
                $guiderInfo     = $this->guiderInfoByUuid( $activity->activity_guider_id );
                $profile_link   = $this->config->item( 'front_url' ).'host/view/'.$activity->activity_id;
                $data[] = array(
                        'activity_id'           => intval($activity->activity_id),
                        'rate_per_person'       => floatval($activity->rate_per_person),
                        'price_type_id'         => intval($activity->price_type_id),
                        'service_providing_region' => $regionName,
                        'guiding_speciality'    => $spec,
                        'profile_link'          => $profile_link,
                        'what_i_offer'          => $activity->what_i_offer,
                        'cancellation_policy'   => $activity->cancellation_policy,
                        'status'                => $status,
                        'activity_photo_1'      => $activity_photo_1,
                        'activity_photo_2'      => $activity_photo_2,
                        'activity_photo_3'      => $activity_photo_3,
                        'country_name'          => $guiderInfo->country_name,
                        'country_short_code'    => $guiderInfo->country_short_code,
                        'country_currency_code' => $guiderInfo->country_currency_code,
                        'country_currency_symbol'=> $guiderInfo->country_currency_symbol,
                        'country_time_zone'      => $guiderInfo->country_time_zone,
                        'maximum_booking'        => intval($activity->maximum_booking),
                        'additional_info_label'  => $activity->additional_info_label,
                        'date_time_needed'       => intval($activity->date_time_needed)
                        );
                $spec   = [];
                $img    = [];
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get activity list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No activity found.', 'result' => 'error', 'data'=>$data);
            return $result; 
        }
    }
    function get_guider_count($guider_id){
        $messageCount    = $this->guiderMessageCount($guider_id);
        $requestCount1   = $this->guiderRequestCount($guider_id,1);
        $requestCount2   = $this->guiderRequestCount($guider_id,2);
        $requestCount3   = $this->guiderRequestCount($guider_id,3);
        $jnyCount1       = $this->guiderJnyCount($guider_id,1);
        $jnyCount2       = $this->guiderJnyCount($guider_id,2);
        $jnyCount3       = $this->guiderJnyCount($guider_id,3);

        $requestURCount1 = $this->guiderRequestUnreadCount($guider_id,1);
        $requestURCount2 = $this->guiderRequestUnreadCount($guider_id,2);
        $requestURCount3 = $this->guiderRequestUnreadCount($guider_id,3);
        $jnyURCount1     = $this->guiderJnyUnreadCount($guider_id,1);
        $jnyURCount2     = $this->guiderJnyUnreadCount($guider_id,2);
        $jnyURCount3     = $this->guiderJnyUnreadCount($guider_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Get '.HOST_NAME.' Count list Successfully.',
            'result'                    => 'success',
            'new_request_count'         => $requestCount1,
            'pending_request_count'     => $requestCount2,
            'cancelled_request_count'   => $requestCount3,
            'upcoming_journey_count'    => $jnyCount1,
            'inprogress_journey_count'  => $jnyCount2,
            'completed_journey_count'   => $jnyCount3,
            'new_request_unread_count'         => $requestURCount1,
            'pending_request_unread_count'     => $requestURCount2,
            'cancelled_request_unread_count'   => $requestURCount3,
            'upcoming_journey_unread_count'    => $jnyURCount1,
            'inprogress_journey_unread_count'  => $jnyURCount2,
            'completed_journey_unread_count'   => $jnyURCount3,
            'message_count'             => $messageCount,
            'data'                      => array('success' => 1));
        return $result;
    }
    public function updateReadServiceCount($guider_id, $service_id, $filter_type){
        $data   = array('view_by_guider' => 'Y');
        $table  = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'service_id', $service_id );
        $this->db->where( 'status', $filter_type );
        $this->db->update( $table, $data );
        //GET UPDATED LISTS
        $messageCount    = $this->guiderMessageCount($guider_id);
        $requestCount1   = $this->guiderRequestCount($guider_id,1);
        $requestCount2   = $this->guiderRequestCount($guider_id,2);
        $requestCount3   = $this->guiderRequestCount($guider_id,3);
        $jnyCount1       = $this->guiderJnyCount($guider_id,1);
        $jnyCount2       = $this->guiderJnyCount($guider_id,2);
        $jnyCount3       = $this->guiderJnyCount($guider_id,3);

        $requestURCount1 = $this->guiderRequestUnreadCount($guider_id,1);
        $requestURCount2 = $this->guiderRequestUnreadCount($guider_id,2);
        $requestURCount3 = $this->guiderRequestUnreadCount($guider_id,3);
        $jnyURCount1     = $this->guiderJnyUnreadCount($guider_id,1);
        $jnyURCount2     = $this->guiderJnyUnreadCount($guider_id,2);
        $jnyURCount3     = $this->guiderJnyUnreadCount($guider_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Updated '.HOST_NAME.' Service Count Successfully.',
            'result'                    => 'success',
            'new_request_count'         => $requestCount1,
            'pending_request_count'     => $requestCount2,
            'cancelled_request_count'   => $requestCount3,
            'upcoming_journey_count'    => $jnyCount1,
            'inprogress_journey_count'  => $jnyCount2,
            'completed_journey_count'   => $jnyCount3,
            'new_request_unread_count'         => $requestURCount1,
            'pending_request_unread_count'     => $requestURCount2,
            'cancelled_request_unread_count'   => $requestURCount3,
            'upcoming_journey_unread_count'    => $jnyURCount1,
            'inprogress_journey_unread_count'  => $jnyURCount2,
            'completed_journey_unread_count'   => $jnyURCount3,
            'message_count'             => $messageCount,
            'data'                      => array('success' => 1));
        return $result;
    }

    public function updateReadJourneyCount($guider_id, $service_id, $filter_type){
        $data   = array('jny_view_by_guider' => 'Y');
        $table  = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_guider_id', $guider_id );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->where( 'jny_status', $filter_type );
        $this->db->update( $table, $data );
        
        //GET UPDATED LISTS
        $messageCount    = $this->guiderMessageCount($guider_id);
        $requestCount1   = $this->guiderRequestCount($guider_id,1);
        $requestCount2   = $this->guiderRequestCount($guider_id,2);
        $requestCount3   = $this->guiderRequestCount($guider_id,3);
        $jnyCount1       = $this->guiderJnyCount($guider_id,1);
        $jnyCount2       = $this->guiderJnyCount($guider_id,2);
        $jnyCount3       = $this->guiderJnyCount($guider_id,3);

        $requestURCount1 = $this->guiderRequestUnreadCount($guider_id,1);
        $requestURCount2 = $this->guiderRequestUnreadCount($guider_id,2);
        $requestURCount3 = $this->guiderRequestUnreadCount($guider_id,3);
        $jnyURCount1     = $this->guiderJnyUnreadCount($guider_id,1);
        $jnyURCount2     = $this->guiderJnyUnreadCount($guider_id,2);
        $jnyURCount3     = $this->guiderJnyUnreadCount($guider_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Updated '.HOST_NAME.' Journey Count Successfully.',
            'result'                    => 'success',
            'new_request_count'         => $requestCount1,
            'pending_request_count'     => $requestCount2,
            'cancelled_request_count'   => $requestCount3,
            'upcoming_journey_count'    => $jnyCount1,
            'inprogress_journey_count'  => $jnyCount2,
            'completed_journey_count'   => $jnyCount3,
            'new_request_unread_count'         => $requestURCount1,
            'pending_request_unread_count'     => $requestURCount2,
            'cancelled_request_unread_count'   => $requestURCount3,
            'upcoming_journey_unread_count'    => $jnyURCount1,
            'inprogress_journey_unread_count'  => $jnyURCount2,
            'completed_journey_unread_count'   => $jnyURCount3,
            'message_count'             => $messageCount,
            'data'                      => array('success' => 1));
        return $result;
    }
    public function guiderHistoryLists($guider_id, $report_type){
        
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Get '.HOST_NAME.' Journey history Successfully.',
            'result'                    => 'success',
            'guider_id'                 => intval($guider_id),
            'total_completed_journey'   => 0,
            'total_inprogress_journey'  => 0,
            'total_upcoming_journey'    => 0,
            'total_earnings'            => 00.00,
            'data'                      => array());
        return $result;
    }

    //GET GUIDER PROFILE INFO
    public function guiderProfileInfo($guider_id, $new, $res_msg='', $verify_email=''){

        $guiderInfo         = $this->guiderInfoByUuid( $guider_id );
        $randActivityIfo    = $this->getGuiderRandomActivity($guider_id);

        if($guiderInfo->verify_email == 0 && $verify_email == 1){
            $data1[ 'firstName' ]   = $guiderInfo->first_name;
            $data1[ 'guiderEmail' ] = $guiderInfo->email;
            $mailContent    = $this->load->view( 'mail/reg_guider', $data1, true );
            $mailData       = $this->MailNotificationmodel->registerGuider($guiderInfo->email,$mailContent);
            $data5          = array('verify_email' => 1);
            $this->updateGuiderByUuid( $data5, $guider_id );
        }

        $upload_path_url    = $this->config->item( 'upload_path_url' );
        $profileImgPath     = $upload_path_url.'g_profile/';
        $activityImgPath    = $upload_path_url.'g_activity/';
        $profile_image      = ($guiderInfo->profile_image)?(filter_var($guiderInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$guiderInfo->profile_image : $guiderInfo->profile_image : '';
        $img_id_proof       = ($guiderInfo->id_proof)?(filter_var($guiderInfo->id_proof, FILTER_VALIDATE_URL) === FALSE) ? $upload_path_url.'identity/'.$guiderInfo->id_proof : $guiderInfo->id_proof : '';
        $dbkl_lic           = ($guiderInfo->dbkl_lic)?(filter_var($guiderInfo->dbkl_lic, FILTER_VALIDATE_URL) === FALSE) ? $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic : $guiderInfo->dbkl_lic : '';
        $randActivityIfo    = $this->getGuiderRandomActivity($guiderInfo->guider_id);
        if(count($randActivityIfo) > 0){
            $photo          = ($randActivityIfo['photo_1'])?(filter_var($randActivityIfo['photo_1'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_1'] : $randActivityIfo['photo_1'] : '';
            $photo1         = ($randActivityIfo['photo_2'])?(filter_var($randActivityIfo['photo_2'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_2'] : $randActivityIfo['photo_2'] : '';
            $photo2         = ($randActivityIfo['photo_3'])?(filter_var($randActivityIfo['photo_3'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_3'] : $randActivityIfo['photo_3'] : '';
            $what_i_offer        = $randActivityIfo['what_i_offer'];
            $cancellation_policy = $randActivityIfo['cancellation_policy'];
            $spec                = $randActivityIfo['guiding_speciality'];
            $regionName          = $randActivityIfo['service_providing_region'];
            $rate_per_person     = $randActivityIfo['rate_per_person'];
            $price_type_id       = $randActivityIfo['price_type_id'];
        }else{
            $photo          = '';
            $photo1         = '';
            $photo2         = '';
            $what_i_offer           = '';
            $cancellation_policy    = '';
            $spec                   = [];
            $regionName             = '';
            $rate_per_person        = '';
            $price_type_id          = 0;
        }
        if(!$photo && !$photo1 && !$photo2){
            $photo  = $upload_path_url.'default_service.png';
        }
        if($guiderInfo->age == '0000-00-00'){
            $age = 0;
        }else{
            $age = date_diff(date_create($guiderInfo->age), date_create('today'))->y;
        }
        $lang = [];
        if($guiderInfo->languages_known){
            $array  = explode(',', $guiderInfo->languages_known);
            foreach ($array as $item) {
                $langInfo = $this->guiderLangInfo($item);
                if($langInfo){ $lang[] = $langInfo->language; }
            }
        }
        $commentCount1  = $this->feedbackTotalCount('G',$guiderInfo->guider_id);
        $commentCount2  = $this->commentTotalCount('G',$guiderInfo->guider_id);
        $commentCount3  = $this->webcommentTotalCount('G',$guiderInfo->guider_id);
        $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
        $res_data       = array(
                            'guider_id'     => intval($guiderInfo->guider_id),
                            'g_id'          => $guiderInfo->guider_id,
                            'device_id'     => "",
                            'first_name'    => $guiderInfo->first_name,
                            'last_name'     => $guiderInfo->last_name,
                            'email'         => $guiderInfo->email,
                            'phone_number'  => $guiderInfo->phone_number,
                            'country_code'  => $guiderInfo->countryCode,
                            'about_me'      => $guiderInfo->about_me,
                            'age'           => $age,
                            'DOB'           => $guiderInfo->age,
                            'languages_known'=> $lang,
                            'acc_no'        => $guiderInfo->acc_no,
                            'acc_name'      => $guiderInfo->acc_name,
                            'bank_name'     => $guiderInfo->bank_name,
                            'branch_name'   => $guiderInfo->branch_name,
                            'service_providing_region'=> $regionName,
                            'guiding_speciality' => $spec,
                            'what_i_offer'  => $what_i_offer,
                            'rate_per_person' => floatval($rate_per_person),
                            'price_type_id'   => intval($price_type_id),
                            'id_proof'        => $img_id_proof,
                            'activity_photo_1'=> $photo,
                            'activity_photo_2'=> $photo1,
                            'activity_photo_3'=> $photo2,
                            'profile_pic'     => $profile_image,
                            'dbkl_lic'        => $dbkl_lic,
                            'dbkl_lic_no'     => $guiderInfo->dbkl_lic_no,
                            'nric_number'     => $guiderInfo->nric_number,
                            'city_id'         => intval($guiderInfo->city),
                            'city_name'       => ($guiderInfo->cityName)? $guiderInfo->cityName : '',
                            'category_id'     => intval($guiderInfo->skills_category),
                            'category_name'   => ($guiderInfo->categoryName)? rawurldecode($guiderInfo->categoryName) : '',
                            'skill_name'      => $guiderInfo->sub_skills,
                            'created_on'      => $guiderInfo->created_on,
                            'rating'          => floatval($guiderInfo->rating),
                            'comment_count'   => intval($commentCount),
                            'cancellation_policy' => $cancellation_policy,
							'strength'            => $guiderInfo->strength,
                            'status'            => intval($guiderInfo->status),
                            'country_name'           => $guiderInfo->country_name,
                            'country_short_code'     => $guiderInfo->country_short_code,
                            'country_currency_code'  => $guiderInfo->country_currency_code,
                            'country_currency_symbol'=> $guiderInfo->country_currency_symbol,
                            'country_time_zone'      => $guiderInfo->country_time_zone
                            );
        $profilePer = 0;
        if($guiderInfo->first_name == '' || $guiderInfo->last_name == '' || $guiderInfo->age == '0000-00-00' || 
            $guiderInfo->phone_number == '' || $guiderInfo->email == '' || $guiderInfo->about_me == '' ||
            $guiderInfo->languages_known == ''){
            $profile_updated  = 0;
        }else{
            $profilePer      += 17;
            $profile_updated  = 1;
        }
        if($guiderInfo->acc_no == '' || $guiderInfo->acc_name == '' || $guiderInfo->bank_name == ''){
            $bank_updated    = 0;
        }else{
            $profilePer     += 17;
            $bank_updated    = 1;
        }
        if($this->guiderActivtyExistById($guider_id)){
            $profilePer     += 17;
            $service_updated = 1;
        }else{
            $service_updated = 1;
        }
        if($guiderInfo->id_proof){
            $profilePer          += 17;
            $is_identity_uploaded = 1;
        }else{ 
            $is_identity_uploaded = 1;
        }
        if($photo == '' && $photo1 == '' && $photo2 == ''){
            $is_activity_pic_uploaded = 0;
        }else{
            $is_activity_pic_uploaded   = 1;
        }
        if($photo){ $profilePer += 5; }
        if($photo1){ $profilePer += 5; }
        if($photo2){ $profilePer += 5; }
        if($guiderInfo->profile_image == ''){
            $is_profile_pic_uploaded  = 0;
        }else{
            $profilePer                 += 17;
            $is_profile_pic_uploaded    = 1;
        }
        $profile_strength = $profilePer;
        $result = array(
                        'response_code'     => SUCCESS_CODE, 
                        'response_description' => $res_msg,
                        'result'            => 'success',
                        'is_profile_updated'=> $profile_updated,
                        'is_bank_updated'   => $bank_updated,
                        'is_service_updated'=> $service_updated,
                        'is_identity_uploaded'=> $is_identity_uploaded,
                        'is_activity_pic_uploaded' => $is_activity_pic_uploaded,
                        'is_profile_pic_uploaded'=> $is_profile_pic_uploaded,
                        'is_dbkl_uploaded'  => intval($guiderInfo->dbkl_status),
                        'profile_strength'  => $profile_strength,
                        'is_new_user'       => intval($new),
                        'data'              => $res_data
                    );
        return $result;
    }
    //Get Request Talent Verification List
    function getVerificationList( $guider_id ) {
        $data = array();
        $upload_path_url = $this->config->item( 'upload_path_url' );

        $this->db->reset_query();
        $this->db->select('master_license.*, tll.license_image, tll.license_number');
        $this->db->from( 'master_license' );
        $this->db->join( 'talent_license_list tll', 'tll.license_id = master_license.license_id AND (talent_id = '.$guider_id.')', 'left' );
        //$this->db->where( 'tll.talent_id', $guider_id );
        $this->db->where( 'master_license.status',1);
        $this->db->order_by( "license_name", "asc" );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $licenseLists   = $query->result();
            foreach ( $licenseLists as $lic ) {
                if($lic->license_image){
                    $img_link = $upload_path_url.'license/'.$lic->license_image;
                }else{
                    $img_link = '';
                }
                $data[] = array(
                        "license_id"     => intval($lic->license_id),
                        "license_name"   => $lic->license_name,
                        "license_image"  => $img_link,
                        "license_number" => ($lic->license_number)? $lic->license_number : ''
                        );        
            }
            return array(
                        'response_code' => SUCCESS_CODE, 
                        'response_description' => 'Get verification list Successfully.', 
                        'result' => 'success',
                        'data' => $data
                        );
        } else {
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Request found.', 'result' => 'error', 'data' => array('error' => 1));
            return $result; 
        }
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
    public function licenseInfo($license_id){
        $this->db->select('*');
        $this->db->where('license_id', $license_id );
        $this->db->where('status', 1 );
        $query = $this->db->get('master_license');
        return $query->row();
    }
    public function talentLicenseInfo($talent_id, $license_id){
        $this->db->select('*');
        $this->db->where('talent_id', $talent_id );
        $this->db->where('license_id', $license_id );
        $this->db->where('status', 1 );
        $query = $this->db->get('talent_license_list');
        return $query->row();
    }
}