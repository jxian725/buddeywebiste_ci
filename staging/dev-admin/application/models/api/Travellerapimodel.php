<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Travellerapimodel extends CI_Model {
    public function __construct(){
        parent::__construct();
    }
    public function insertTraveller( $data ){
        $table = $this->db->dbprefix( 'traveller' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        $this->updateTravellerId($id);
        return $id;
    }
    public function insertDeviceInfo( $deviceInfo, $traveller_id ){
        $device_token   = $deviceInfo['HTTP_DEVICE_TOKEN'];
        $device_id      = $deviceInfo['HTTP_DEVICE_ID'];
        $app_version    = $deviceInfo['HTTP_APP_VERSION'];
        $device_type    = $deviceInfo['HTTP_DEVICE_TYPE'];
        $build_no       = $deviceInfo['HTTP_BUILD_NO'];
        if($traveller_id && $device_id && $device_token){
            $data   = array(
                        'device_token'  => $device_token,
                        'device_id'     => $device_id,
                        'traveller_id'  => $traveller_id
                        );
            if($device_type){ $data['device_type']   = $device_type; }
            if($app_version){ $data['app_version']   = $app_version; }
            if($build_no){ $data['build_no'] = $build_no; }
            $table = $this->db->dbprefix( 'traveller_device_info' );
            $this->db->insert( $table, $data );
        }
    }
    public function updateDeviceInfo( $deviceInfo, $traveller_id ){
        $device_token   = $deviceInfo['HTTP_DEVICE_TOKEN'];
        $device_id      = $deviceInfo['HTTP_DEVICE_ID'];
        $app_version    = $deviceInfo['HTTP_APP_VERSION'];
        $device_type    = $deviceInfo['HTTP_DEVICE_TYPE'];
        $build_no       = $deviceInfo['HTTP_BUILD_NO'];
        if($traveller_id && $device_id){
            $table  = $this->db->dbprefix( 'traveller_device_info' );
            $data   = array(
                        'device_id'     => $device_id,
                        'traveller_id'  => $traveller_id,
                        'createdon'     => date("Y-m-d H:i:s")
                        );
            if($device_token){ $data['device_token']    = $device_token; }
            if($device_type){ $data['device_type']      = $device_type; }
            if($app_version){ $data['app_version']      = $app_version; }
            if($build_no){ $data['build_no'] = $build_no; }

            $this->db->where( 'device_id', $device_id );
            $this->db->where( 'traveller_id', $traveller_id );
            $query = $this->db->get('traveller_device_info');
            if( $query->num_rows() > 0 ){
                $row = $query->row();
                $this->db->where( 'tdi_id', $row->tdi_id );
                $this->db->update( $table, $data );
            }else{ 
                if($device_token){ 
                    $this->db->insert( $table, $data ); 
                }
            }
            
        }
    }
    public function updateTravellerId($traveller_id){
        
        $t_id   = 'T-'.str_pad($traveller_id, 5, '0', STR_PAD_LEFT);
        $data   = array( 't_id' => $t_id );
        $table  = $this->db->dbprefix('traveller');
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
    }
    public function travellerEmailExists( $email ){
        $this->db->where( 'email', $email );
        $this->db->where( 'traveller.status !=', 4 );
        $query = $this->db->get('traveller');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        }else{ return false; }
    }
    public function travellerPhoneExists( $phone_number ){
        
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('traveller');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function travellerPhoneEmailExists( $phone_number, $email ){
        $this->db->where( 'email', $email );
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('traveller ');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function travellerPhoneDeviceIdExists( $phone_number, $device_id ){
        $this->db->where( 'device_id', $device_id );
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('traveller ');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    public function requestorMobileNoExists( $phone_number ){
        $this->db->where( 'phone_number', $phone_number );
        $query = $this->db->get('traveller');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Requestor Info
    public function travellerInfoByUuid( $traveller_id ){
        $this->db->select( 'traveller.*,country_short_code,country_name,country_currency_code,country_currency_symbol,country_time_zone' );
        $this->db->from( 'traveller' );
        $this->db->join( 'countries', 'countries.phonecode = traveller.countryCode', 'left' );
        $this->db->where('traveller_id', $traveller_id);
        $this->db->where( 'traveller.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    //Update query
    public function updateTravellerByUuid( $data, $traveller_id ){
        $table  = $this->db->dbprefix( 'traveller' );
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
        return true; 
    }
    //Update validation check email 
    function updaterequestorInfoByEmailExists( $email, $traveller_id ) {
        $this->db->where( 'email', $email );
        $this->db->where( 'traveller_id != ', $traveller_id );
        $query = $this->db->get( 'traveller' );
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Update validation for phone number
    public function updaterequestorInfoByPhoneExists( $contact, $traveller_id ){
        $this->db->where( 'phone_number', $contact );
        $this->db->where( 'traveller_id !=', $traveller_id );
        $query = $this->db->get( 'traveller' );
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Requestor Login
    function login( $phone_number ) {
        $table = $this->db->dbprefix( 'traveller' );
        $this->db->where("(phone_number='$phone_number')", NULL, FALSE);
        $this->db->where( 'traveller.status !=', 4 );
        $query = $this->db->get( $table );
        $rows  = $query->num_rows();
        if( $rows > 0 ) {
            $userInfo   =  $query->row();
            $exist_no   = $userInfo->phone_number;
            $upload_path_url    = $this->config->item( 'upload_path_url' );
            $profileImgPath     = $upload_path_url.'t_profile/';
            $activityImgPath    = $upload_path_url.'t_activity/';
            $profile_image      = ($userInfo->profile_image) ? $profileImgPath.$userInfo->profile_image : '';
            $photo              = ($userInfo->photo) ? $activityImgPath.$userInfo->photo : '';
            $photo1             = ($userInfo->photo1) ? $activityImgPath.$userInfo->photo1 : '';
            $photo2             = ($userInfo->photo2) ? $activityImgPath.$userInfo->photo2 : '';
            //If Condition
            if( $exist_no == $phone_number ) {
                $data = array(
                                'traveller_id'  => $userInfo->traveller_id,
                                't_id'          => $userInfo->t_id,
                                'first_name'    => $userInfo->first_name,
                                'last_name'     => $userInfo->last_name, 
                                'email'         => $userInfo->email,
                                'city'          => $userInfo->city,
                                'mobile'        => $userInfo->mobile,
                                'phone_number'  => $userInfo->phone_number,
                                'photo'         => $photo,
                                'photo1'        => $photo1,
                                'photo2'        => $photo2,
                                'profile_image' => $profile_image,
                                'languages'     => $userInfo->languages_known,
                                'about_me'      => $userInfo->about_me
                            );
                //IF Condition
                if( $userInfo->status == 0 ) {
                    $result = array('response_code'=> SUCCESS_CODE,'response_description' => 'Your account is not active yet. Please contact your administrator.', 'result' => 'success', 'data' => $data);
                } else if( $userInfo->status == 2 ) {
                    $result = array('response_code'=> SUCCESS_CODE,'response_description' => 'Your account has been temporarily disabled by an administrator.', 'result' => 'success', 'data' => $data);
                } else {
                    $result = array('response_code'=> SUCCESS_CODE,'response_description' => 'Login successfully.', 'result' => 'success', 'data' => $data);
                }
                return $result;
            } else {
                $result = array( 'response_code'=> ERROR_CODE,'response_description' => 'Please enter Registered mobile number.', 'result' => 'error', 'data' => array('mobile' => $mobile) );
                return $result;
            }
        } else {
            $result = array( 'response_code'=> ERROR_CODE,'response_description' => 'Please enter Registered mobile number.', 'result' => 'error', 'data' => array('error' => 1) );
            return $result; 
        }
    }
    //Get nearby city based guider list for status 1 active 
    function getGuiderActivityLists( $limit=false, $start=false, $regionsearch=false, $speciality_category=false, $what_i_offer=false, $country_code=false, $regionArr=false ) {
        $data = array();
        
        $this->db->select('
                        guider_activity_list.*,
                        guider_id,g_id,first_name,last_name,phone_number,age,acc_no,acc_name,bank_name,dbkl_lic,dbkl_lic_no,dbkl_status,nric_number,
                        countryCode,email,profile_image,rating,languages_known,about_me,states.name,
                        country_currency_symbol,country_name,country_time_zone,country_short_code,country_currency_code
                        ');
        $this->db->from('guider_activity_list');
        $this->db->join('guider', 'guider.guider_id = guider_activity_list.activity_guider_id','left');
        $this->db->join('states', 'states.id = guider_activity_list.service_providing_region','left');
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        if($speciality_category){
            foreach ($speciality_category as $category){
                $clauses[] = "guiding_speciality LIKE '%".$category."%'";
            }
            $clause     = implode(' OR ' ,$clauses);
            $this->db->where("($clause)");
        }
        if($what_i_offer){ 
            $whatIDos  = explode(" ",trim($what_i_offer));
            if(count($whatIDos) > 1){
                foreach ($whatIDos as $wio){
                    $WIOclauses[] = "what_i_offer LIKE '%".$wio."%'";
                }
                $WIOclause  = implode(' OR ' ,$WIOclauses);
                $this->db->where("($WIOclause)");
            }else{
                $this->db->where("(what_i_offer LIKE '%".$what_i_offer."%')");
            }
        }
        if($country_code){ $this->db->where( 'countryCode', "$country_code" ); }
        if($regionArr){ $this->db->where_in( 'service_providing_region', $regionArr ); }
        $this->db->where( 'guider.status', 1 );
        $this->db->where( 'guider_activity_list.activity_status = 1');
        $this->db->order_by( "guider.rating", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $guiderLists   = $query->result();
            foreach ( $guiderLists as $guider ) {
                $commentCount1   = $this->feedbackTotalCount('G',$guider->guider_id);
                $commentCount2   = $this->commentTotalCount('G',$guider->guider_id);
                $commentCount3   = $this->webcommentTotalCount('G',$guider->guider_id);
                $commentCount    = $commentCount1 + $commentCount2 + $commentCount3;
                $dirUrl          = $this->config->item( 'dir_url' );
                $noImage         = $dirUrl.'uploads/guider_prof_icon.png';
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $profileImgPath  = $upload_path_url.'g_profile/';
                $activityImgPath = $upload_path_url.'g_activity/';
                $profile_image   = ($guider->profile_image) ? $profileImgPath.$guider->profile_image : $noImage;
                $photo           = ($guider->photo_1) ? $guider->photo_1 : '';
                $photo1          = ($guider->photo_2) ? $guider->photo_2 : '';
                $photo2          = ($guider->photo_3) ? $guider->photo_3 : '';
                $img_id_proof    = ($guider->id_proof) ? $upload_path_url.'identity/'.$guider->id_proof : '';
                $dbkl_lic        = ($guider->dbkl_lic) ? $upload_path_url.'dbkl/'.$guider->dbkl_lic : '';
                if($guider->age == '0000-00-00'){
                    $age    = 0;
                }else{
                    $age    = date_diff(date_create($guider->age), date_create('today'))->y;
                }
                $lang = [];
                if($guider->languages_known){
                    $array =  explode(',', $guider->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Guiderapimodel->guiderLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $spec = [];
                if($guider->guiding_speciality){
                    $array =  explode(',', $guider->guiding_speciality);
                    foreach ($array as $item) {
                        $specInfo = $this->Guiderapimodel->guiderSpecialityInfo($item);
                        if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                    }
                }
                $regionInfo     = $this->Guiderapimodel->stateInfoByid($guider->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                if(!$photo && !$photo1 && !$photo2){
                    $photo  = $upload_path_url.'default_service.png';
                }
                $profile_link   = $this->config->item( 'front_url' ).'host/view/'.$guider->activity_id;
                $data[] = array(
                                    'activity_id'   => intval($guider->activity_id),
                                    'guider_id'     => intval($guider->guider_id),
                                    'g_id'          => $guider->g_id,
                                    'first_name'    => $guider->first_name,
                                    'last_name'     => $guider->last_name,
                                    'email'         => $guider->email,
                                    'profile_link'  => $profile_link,
                                    'phone_number'  => $guider->phone_number,
                                    'country_code'  => $guider->countryCode,
                                    'about_me'      => $guider->about_me,
                                    'age'           => $age,
                                    'DOB'           => $guider->age,
                                    'languages_known'=> $lang,
                                    'acc_no'        => $guider->acc_no,
                                    'acc_name'      => $guider->acc_name,
                                    'bank_name'     => $guider->bank_name,
                                    'service_providing_region' => $regionName,
                                    'guiding_speciality' => $spec,
                                    'what_i_offer'    => $guider->what_i_offer,
                                    'rate_per_person' => floatval($guider->rate_per_person),
                                    'price_type_id'   => intval($guider->price_type_id),
                                    'id_proof'        => $img_id_proof,
                                    'activity_photo_1'=> $photo,
                                    'activity_photo_2'=> $photo1,
                                    'activity_photo_3'=> $photo2,
                                    'profile_pic'     => $profile_image,
                                    'dbkl_lic'        => $dbkl_lic,
                                    'dbkl_lic_no'     => $guider->dbkl_lic_no,
                                    'is_dbkl_uploaded'=> intval($guider->dbkl_status),
                                    'nric_number'     => $guider->nric_number,
                                    'device_id'       => '',
                                    'created_on'      => $guider->createdon,
                                    'rating'          => floatval($guider->rating),
                                    'cancellation_policy' => $guider->cancellation_policy,
                                    'status'          => intval($guider->status),
                                    'comment_count'   => intval($commentCount),
                                    'country_name'           => $guider->country_name,
                                    'country_short_code'     => $guider->country_short_code,
                                    'country_currency_code'  => $guider->country_currency_code,
                                    'country_currency_symbol'=> $guider->country_currency_symbol,
                                    'country_time_zone'      => $guider->country_time_zone,
                                    'maximum_booking'        => intval($guider->maximum_booking),
                                    'additional_info_label'  => $guider->additional_info_label,
                                    'date_time_needed'       => intval($guider->date_time_needed)
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get '.HOST_NAME.' list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No '.HOST_NAME.' found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    function getTravellerRequest( $limit=false, $start=false, $traveller_id=false, $filter_type=false ) {
        $data = array();
        $this->db->reset_query();
        $this->db->select('sl.status AS reqStatus, sl.*, g.guider_id,g.first_name,g.last_name,
                            g.profile_image, g.rating,gal.maximum_booking,gal.additional_info_label,gal.date_time_needed');
        $this->db->from( 'service_list sl' );
        $this->db->join( 'traveller t', 't.traveller_id = sl.service_traveller_id', 'left' );
        $this->db->join( 'guider g', 'g.guider_id = sl.service_guider_id', 'left' );
        $this->db->join( 'guider_activity_list gal', 'gal.activity_id = sl.activity_id', 'left' );
        $this->db->where( 'sl.service_traveller_id', $traveller_id );
        if($filter_type){
            if($filter_type == 2){//PP AND PROCESSING
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
                $activityInfo    = $this->Guiderapimodel->guiderActivtyInfoByUuid( $service->activity_id );
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $profileImgPath  = $upload_path_url.'g_profile/';
                $activityImgPath = $upload_path_url.'g_activity/';
                $photo           = ($activityInfo) ? $activityInfo->photo_1 : '';
                $photo1          = ($activityInfo) ? $activityInfo->photo_2 : '';
                $photo2          = ($activityInfo) ? $activityInfo->photo_3 : '';
                $profile_image   = ($service->profile_image) ? $profileImgPath.$service->profile_image : '';
                $commentCount1   = $this->feedbackTotalCount('G',$service->service_guider_id);
                $commentCount2   = $this->commentTotalCount('G',$service->service_guider_id);
                $commentCount3   = $this->webcommentTotalCount('G',$service->service_guider_id);
                $commentCount    = $commentCount1 + $commentCount2 + $commentCount3;
                if(!$photo && !$photo1 && !$photo2){
                    $photo  = $upload_path_url.'default_service.png';
                }
                $data[] = array(
                        "request_primary_id"     => intval($service->service_id),
                        "booking_request_id"     => $service->booking_request_id,
                        "guider_id"              => intval($service->service_guider_id),
                        "first_name"             => $service->first_name,
                        "last_name"              => $service->last_name,
                        "what_i_offer"           => $service->activity_desc,
                        "activity_photo_1"       => $photo,
                        "activity_photo_2"       => $photo1,
                        "activity_photo_3"       => $photo2,
                        "profile_pic"            => $profile_image,
                        "rating"                 => floatval($service->rating),
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
            $messageCount    = $this->travellerMessageCount($traveller_id);
            $requestCount1   = $this->travellerRequestCount($traveller_id,1);
            $requestCount2   = $this->travellerRequestCount($traveller_id,2);
            $requestCount3   = $this->travellerRequestCount($traveller_id,3);
            $jnyCount1       = $this->travellerJnyCount($traveller_id,1);
            $jnyCount2       = $this->travellerJnyCount($traveller_id,2);
            $jnyCount3       = $this->travellerJnyCount($traveller_id,3);
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
                        'result'    => 'success',
                        'data'      => $data
                        );
            return $result;
        } else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Request found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    public function autoCompletedExpiryRequest( $traveller_id ){
        $today    = date('Y-m-d');

        $this->db->select('service_id,service_date');
        $this->db->from('service_list');
        $this->db->where( 'service_traveller_id', $traveller_id );
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
    public function guiderInfoById( $guider_id ){
        $this->db->select( 'guider.*,country_short_code,country_name,country_currency_code,country_currency_symbol,country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
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
    public function travellerDeviceTokenList($traveller_id){
        $this->db->reset_query();
        $this->db->select('*');
        $this->db->from('traveller_device_info');
        $this->db->where( 'traveller_id', $traveller_id );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ){
            $result = $query->result();
            return $result;
        }
    }
    //Insert Request Service
    function insertService( $data ) {
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
    //Accept status 2
    function traveller_update( $data, $guider_id, $traveller_id ) {
        $table  = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'guider_id', $guider_id );
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
        return true;
    }
    //Get Laguage List
    function get_language_lists() {
        $data = array();
        $this->db->select('*');
        $this->db->from('traveller_languages');
        $this->db->where( 'lang_status', 1 );
        $this->db->order_by("language", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $languageLists   = $query->result();
            foreach ( $languageLists as $language ) {
                $data[] = array(
                        "lang_id"   => intval($language->lang_id),
                        "language"  => $language->language,
                        "status"    => intval($language->lang_status)
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
    public function travellerLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'traveller_languages' );
        $row    = $query->row();
        return $row;
    }
    function getMyJourneyList( $limit=false, $start=false, $traveller_id=false, $filter_type=false ) {
        $data = array();

        $this->db->select('journey_list.*,guider.first_name as guiderFName, guider.last_name as guiderLName, 
                        guider.rating as guiderRating, guider.profile_image as guiderImage,number_of_person,
                        service_list.activity_desc, service_list.activity_id,activity_desc,service_price_type_id,
                        traveller.profile_image as requestorImage,guider_charged,service_date,pickup_time,
                        additional_information,feedback,guider_currency_symbol,current_processing_fee,
                        booking_request_id,order_id,service_price_type_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id','left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id','left');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->where( 'jny_traveller_id', "$traveller_id" );
        if($filter_type){ $this->db->where( 'jny_status', $filter_type ); }
        $this->db->order_by( "journey_id", "desc" );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $journeyLists   = $query->result();
            foreach ( $journeyLists as $journey ) {
                $g_rating     = floatval($journey->guiderRating);
                if($journey->guider_charged){
                    if($journey->service_price_type_id == 1){
                        $rate    = $journey->guider_charged * $journey->number_of_person;
                    }else{
                        $rate    = $journey->guider_charged;
                    }
                }else{ $rate = ''; }
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $profileImgPath  = $upload_path_url.'g_profile/';
                $activityInfo    = $this->Guiderapimodel->guiderActivtyInfoByUuid( $journey->activity_id );
                $photo           = ($activityInfo) ? $activityInfo->photo_1 : '';
                $photo1          = ($activityInfo) ? $activityInfo->photo_2 : '';
                $photo2          = ($activityInfo) ? $activityInfo->photo_3 : '';
                $profile_image   = ($journey->guiderImage) ? $profileImgPath.$journey->guiderImage : '';
                $commentCount1   = $this->feedbackTotalCount('G',$journey->jny_guider_id);
                $commentCount2   = $this->commentTotalCount('G',$journey->jny_guider_id);
                $commentCount3   = $this->webcommentTotalCount('G',$journey->jny_guider_id);
                $commentCount    = $commentCount1 + $commentCount2 + $commentCount3;
                //0 - Not given,1- Terrible,2- Wonderful
                if($journey->guider_rating == 0){
                    $ratings_type = 0; 
                }elseif($journey->guider_rating == 1){ 
                    $ratings_type = 1; 
                }elseif($journey->guider_rating == 5){
                    $ratings_type = 2;
                }else{
                    $ratings_type = 0;
                }
                if($journey->order_id){
                    $order_id   = $journey->order_id;
                }else{
                    $order_id   = '';
                }
                if(!$photo && !$photo1 && !$photo2){
                    $photo  = $upload_path_url.'default_service.png';
                }
                $data[] = array(
                        "request_primary_id"     => intval($journey->jny_service_id),
                        "booking_request_id"     => $journey->booking_request_id,
                        "guider_id"              => intval($journey->jny_guider_id),
                        "first_name"             => $journey->guiderFName,
                        "last_name"              => $journey->guiderLName,
                        "what_i_offer"           => $journey->activity_desc,
                        "activity_photo_1"       => $photo,
                        "activity_photo_2"       => $photo1,
                        "activity_photo_3"       => $photo2,
                        "profile_pic"            => $profile_image,
                        "rating"                 => $g_rating,
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
                        "ratings_comment_msg"    => $journey->guider_feedback,
                        "transaction_ref_id"     => $order_id,
                        "status"                 => intval($journey->jny_status),
                        "maximum_booking"        => intval($activityInfo->maximum_booking),
                        "additional_info_label"  => $activityInfo->additional_info_label,
                        "date_time_needed"       => intval($activityInfo->date_time_needed),
                        "is_space_booking"       => 0,
                        "space_info"             => array(),
                        );        
            }
            $messageCount    = $this->travellerMessageCount($traveller_id);
            $requestCount1   = $this->travellerRequestCount($traveller_id,1);
            $requestCount2   = $this->travellerRequestCount($traveller_id,2);
            $requestCount3   = $this->travellerRequestCount($traveller_id,3);
            $jnyCount1       = $this->travellerJnyCount($traveller_id,1);
            $jnyCount2       = $this->travellerJnyCount($traveller_id,2);
            $jnyCount3       = $this->travellerJnyCount($traveller_id,3);
            $result = array(
                        'response_code' => SUCCESS_CODE,
                        'response_description' => 'Get Journey list Successfully.',
                        'new_request_count'         => $requestCount1,
                        'pending_request_count'     => $requestCount2,
                        'cancelled_request_count'   => $requestCount3,
                        'upcoming_journey_count'    => $jnyCount1,
                        'inprogress_journey_count'  => $jnyCount2,
                        'completed_journey_count'   => $jnyCount3,
                        'message_count'             => $messageCount,
                        'result'    => 'success',
                        'data'      => $data
                        );
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Journey list found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    public function autoCompletedExpiryJourney( $traveller_id ){
        $today    = date('Y-m-d');

        $this->db->select('journey_id,service_date');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where( 'jny_traveller_id', $traveller_id );
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
    function getCommentsList( $limit=false, $start=false, $traveller_id=false ) {
        $data = array();

        $sql     = "SELECT cmt_id,guider_id,traveller_id,cmt_email,comments,createdon,comments_type 
                    FROM ( 
                    SELECT jny_service_id as cmt_id,jny_guider_id as guider_id,jny_traveller_id as traveller_id,cmt_email,
                    traveller_feedback as comments,traveller_feedbackon as createdon ,comments_type 
                    FROM journey_list 
                    WHERE jny_traveller_id = $traveller_id AND traveller_feedback != ''
                    UNION 
                    SELECT cmt_id,cmt_guider_id as guider_id,cmt_traveller_id,cmt_email,comments,createdon,comments_type 
                    FROM comments 
                    WHERE cmt_traveller_id = $traveller_id AND sender_type = 1 AND comments != ''
                    ) A 
                    ORDER BY createdon DESC 
                    LIMIT $start,$limit";
        $query    = $this->db->query($sql);
        if( $query->num_rows() > 0 ) {
            $commentLists   = $query->result();
            foreach ( $commentLists as $comment ) {
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $comment->guider_id );
                $profileImgPath = $this->config->item( 'upload_path_url' ).'g_profile/';
                $profile_image  = ($guiderInfo->profile_image) ? $profileImgPath.$guiderInfo->profile_image : '';
                $regionInfo     = $this->Guiderapimodel->stateInfoByid($guiderInfo->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                if($comment->rating){ $is_rated = 1; }else{ $is_rated = 0; }

                $data[]         = array(
                                    'comment_id'     => intval($comment->cmt_id),
                                    'guider_id'      => intval($comment->guider_id),
                                    'traveller_id'   => intval($comment->traveller_id),
                                    'comments'       => $comment->comments,
                                    'first_name'     => $guiderInfo->first_name,
                                    'last_name'      => $guiderInfo->last_name,
                                    'email_address'  => $guiderInfo->email,
                                    'profile_pic'    => $profile_image,
                                    'country'        => $guiderInfo->country_name,
                                    'city'           => $regionName,
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
    function updateMessageList( $ruser_id=false, $puser_id=false, $ruser_type='T', $puser_type='G' ) {
        $msg_ids = array();

        $this->db->select('*');
        $this->db->from( 'messages' );
        $this->db->where( 'traveller_seen', 'N' );
        
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
            $data11 = array('traveller_seen' => 'Y');
            $this->db->where_in('msg_id', $msg_ids);
            $this->db->update( 'messages', $data11 );
            
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Updated Messages list Successfully.', 'result' => 'success', 'data' => array('error' => 1));
            return $result;
        }else {
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Messages found.', 'result' => 'error', 'data' => array('error' => 1));
            return $result; 
        }
    }
    function travellerRequestCount( $traveller_id, $status ) {
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function travellerRequestUnreadCount( $traveller_id, $status ) {
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'view_by_traveller', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function travellerJnyCount( $traveller_id, $status ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_traveller_id', $traveller_id );
        $this->db->where( 'jny_status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function travellerJnyUnreadCount( $traveller_id, $status ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_traveller_id', $traveller_id );
        $this->db->where( 'jny_view_by_traveller', 'N' );
        $this->db->where( 'jny_status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function get_traveller_count($traveller_id){
        $messageCount    = $this->travellerMessageCount($traveller_id);
        $requestCount1   = $this->travellerRequestCount($traveller_id,1);
        $requestCount2   = $this->travellerRequestCount($traveller_id,2);
        $requestCount3   = $this->travellerRequestCount($traveller_id,3);
        $jnyCount1       = $this->travellerJnyCount($traveller_id,1);
        $jnyCount2       = $this->travellerJnyCount($traveller_id,2);
        $jnyCount3       = $this->travellerJnyCount($traveller_id,3);

        $requestURCount1 = $this->travellerRequestUnreadCount($traveller_id,1);
        $requestURCount2 = $this->travellerRequestUnreadCount($traveller_id,2);
        $requestURCount3 = $this->travellerRequestUnreadCount($traveller_id,3);
        $jnyURCount1     = $this->travellerJnyUnreadCount($traveller_id,1);
        $jnyURCount2     = $this->travellerJnyUnreadCount($traveller_id,2);
        $jnyURCount3     = $this->travellerJnyUnreadCount($traveller_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Get '.GUEST_NAME.' Count list Successfully.',
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








    
	function updateGuiderJourneyStatusCancel( $traveller_id=false, $service_id=false ) {
        $status 	= array(1,2,3);
		$date 		= date("Y-m-d H:i:s");
        $this->db->select('*');
        $this->db->from('journey_list');
        $this->db->where( 'jny_traveller_id', "$traveller_id" );
		$this->db->where( 'jny_service_id', "$service_id" );
		$this->db->where_in( 'status', $status );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
			$journey    = $query->row();
			$journey_id = $journey->journey_id;
			$data2      = array('jny_status' => 4,'cancelled_on' => $date);
			$table2     = $this->db->dbprefix( 'journey_list' );
			$this->db->where( 'journey_id', $journey_id );
			$this->db->update( $table2, $data2 );
			$result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Journey Cancelled Successfully.', 'result' => 'success', 'data' => $data2);
            return $result;
        }else{
			$data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Journey found.', 'result' => 'error', 'data' => $data);
            return $result;
		}
    }
    function travellerMessageCount( $traveller_id=false ) {
        $data = array();
        $this->db->select('msg_id');
        $this->db->from( 'messages' );
        $this->db->where( 'traveller_seen', 'N' );
        $this->db->where('
                    ((msg_post_user_type = "T" AND msg_post_user_id = '.$traveller_id.')
                    OR 
                    ( msg_receive_user_type = "T" AND msg_receive_user_id = '.$traveller_id.'))
                    ', NULL, FALSE);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function travellerServiceCount( $traveller_id, $status=false ) {
        if(!$status){ $status = 1; }
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'view_by_traveller', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    function travellerInprgsJnyCount( $traveller_id ) {
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_traveller_id', $traveller_id );
        $this->db->where( 'jny_status', 2 );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }

    function travellerServiceIDs( $traveller_id, $status=false ) {
        $ids    = array();
        if(!$status){ $status = 1; }
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'view_by_traveller', 'N' );
        $this->db->where( 'status', $status );
        $query = $this->db->get( $table );
        $rows  = $query->num_rows();
        if( $rows > 0 ) {
            $servicelists   = $query->result();
            foreach ( $servicelists as $service ) {
                //$ids = ($ids == '' ? '' : $ids . '-') . $service->service_id;
                $ids[] = $service->service_id;
            }
        }
        return $ids;
    }
    function travellerinfo( $traveller_id ) {
        $table = $this->db->dbprefix( 'traveller' );
        $this->db->where( 'traveller_id', $traveller_id );
        $query = $this->db->get( $table );
        $rows  = $query->num_rows();
        if( $rows > 0 ) {
            $userInfo           = $query->row();
            $upload_path_url    = $this->config->item( 'upload_path_url' );
            $profileImgPath     = $upload_path_url.'t_profile/';
            $activityImgPath    = $upload_path_url.'t_activity/';
            $profile_image      = ($userInfo->profile_image) ? $profileImgPath.$userInfo->profile_image : '';
            $photo              = ($userInfo->photo) ? $activityImgPath.$userInfo->photo : '';
            $photo1             = ($userInfo->photo1) ? $activityImgPath.$userInfo->photo1 : '';
            $photo2             = ($userInfo->photo2) ? $activityImgPath.$userInfo->photo2 : '';
            if($userInfo->age == '0000-00-00'){
                $age    = 0;
            }else{
                $age    = date_diff(date_create($userInfo->age), date_create('today'))->y;
            }
            $lang = [];
            if($userInfo->languages_known){
                $array =  explode(',', $userInfo->languages_known);
                foreach ($array as $item) {
                    $langInfo = $this->travellerLangInfo($item);
                    if($langInfo){ $lang[] = $langInfo->language; }
                }
            }
            $profilePer     = 0;
            $is_new_user    = 0;
            if( $userInfo->first_name == '' || $userInfo->last_name == '' || $userInfo->gender == 0 || $userInfo->age == '0000-00-00' ||
             $userInfo->about_me == '' || $userInfo->languages_known == '' || $userInfo->city == '' ) {
                $profile_updated    = 0;
            } else {
                $profile_updated    = 1;
            }
            if( $userInfo->first_name == '' || $userInfo->last_name == '' || $userInfo->gender == 0 || $userInfo->age == '0000-00-00') {
                $is_bpu    = 0;
            } else {
                $is_bpu    = 1;
                $profilePer += 35;
            }
            if( $userInfo->profile_image == '') {
                $is_profile_pic_updated = 0;
            } else {
                $is_profile_pic_updated = 1;
                $profilePer             += 35;
            }
            if($userInfo->languages_known) { $profilePer += 15; }
            if($userInfo->about_me) { $profilePer += 10; }
            if($userInfo->city) { $profilePer += 5; }
            $serviceCount   = $this->travellerServiceCount($userInfo->traveller_id);
            $inprgsJnyCount = $this->travellerInprgsJnyCount($userInfo->traveller_id);
            $serviceIDs     = $this->travellerServiceIDs($userInfo->traveller_id);
            $messageCount   = $this->travellerMessageCount($userInfo->traveller_id);
            $commentCount1  = $this->feedbackTotalCount('T',$userInfo->traveller_id);
            $commentCount2  = $this->commentTotalCount('T',$userInfo->traveller_id);
            $commentCount3  = $this->webcommentTotalCount('T',$userInfo->traveller_id);
            $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
            $paymentCount   = $this->travellerPendingPaymentCount($userInfo->traveller_id);
            $res_data       = array(
                                'traveller_id'  => intval($userInfo->traveller_id),
                                't_id'          => $userInfo->t_id,
                                'first_name'    => $userInfo->first_name,
                                'last_name'     => $userInfo->last_name,
                                'email'         => $userInfo->email,
                                'phone_number'  => $userInfo->phone_number,
                                'country_code'  => $userInfo->countryCode,
                                'city'          => $userInfo->city,
                                'languages_known'=> $lang,
                                "gender"        => intval($userInfo->gender),
                                'age'           => $age,
                                'DOB'           => $userInfo->age,
                                'about_me'      => $userInfo->about_me,
                                'rating_strength' => floatval($userInfo->ratings),
                                'profile_strength'=> $profilePer,
                                'photo'         => $photo,
                                'photo1'        => $photo1,
                                'photo2'        => $photo2,
                                'profile_pic'   => $profile_image,
                                'service_count' => $serviceCount,
                                'inProgress'    => $inprgsJnyCount,
                                'message_count' => $messageCount,
                                'service_ids'   => $serviceIDs,
                                'device_type'   => '',
                                'app_version'   => '',
                                'device_brand'  => '',
                                'device_model'  => '',
                                'device_OS_version' => '',
                                'device_id'     => '',
                                'created_on'    => $userInfo->created_on,
                                'status'        => $userInfo->status,
                                'comment_count' => intval($commentCount),
                                'pending_payment_count' => intval($paymentCount)
                                );
            $result     = array(
                                'response_code'     => SUCCESS_CODE, 
                                'response_description' => 'Get '.GUEST_NAME.' information successfully.', 
                                'result'            => 'success',
                                "is_basic_profile_updated"  => $is_bpu,
                                "is_full_profile_updated"   => $profile_updated,
                                "is_profile_pic_updated"    => $is_profile_pic_updated,
                                "is_new_user"               => $is_new_user,
                                'data'                      => $res_data
                                );
            return $result;
        } else {
            $result = array( 'response_code'=> ERROR_CODE,'response_description' => 'Please Enter valid '.GUEST_NAME.' id.', 'result' => 'error', 'data' => array('error' => 1) );
            return $result; 
        }
    }
    function travellerPendingPaymentCount( $traveller_id ) {
        
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'status', 2 );
        $query = $this->db->get( $table );
        return $query->num_rows();
    }
    public function updateReadServiceCount($traveller_id, $service_id, $filter_type){
        $data   = array('view_by_traveller' => 'Y');
        $table  = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'service_id', $service_id );
        $this->db->where( 'status', $filter_type );
        $this->db->update( $table, $data );
        //GET TRAVELLER UPDATED COUNT LISTS
        $messageCount    = $this->travellerMessageCount($traveller_id);
        $requestCount1   = $this->travellerRequestCount($traveller_id,1);
        $requestCount2   = $this->travellerRequestCount($traveller_id,2);
        $requestCount3   = $this->travellerRequestCount($traveller_id,3);
        $jnyCount1       = $this->travellerJnyCount($traveller_id,1);
        $jnyCount2       = $this->travellerJnyCount($traveller_id,2);
        $jnyCount3       = $this->travellerJnyCount($traveller_id,3);

        $requestURCount1 = $this->travellerRequestUnreadCount($traveller_id,1);
        $requestURCount2 = $this->travellerRequestUnreadCount($traveller_id,2);
        $requestURCount3 = $this->travellerRequestUnreadCount($traveller_id,3);
        $jnyURCount1     = $this->travellerJnyUnreadCount($traveller_id,1);
        $jnyURCount2     = $this->travellerJnyUnreadCount($traveller_id,2);
        $jnyURCount3     = $this->travellerJnyUnreadCount($traveller_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Updated '.GUEST_NAME.' Service Count Successfully.',
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
    public function updateReadJourneyCount($traveller_id, $service_id, $filter_type){
        $data   = array('jny_view_by_traveller' => 'Y');
        $table  = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_traveller_id', $traveller_id );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->where( 'jny_status', $filter_type );
        $this->db->update( $table, $data );
        //GET TRAVELLER UPDATED COUNT LISTS
        $messageCount    = $this->travellerMessageCount($traveller_id);
        $requestCount1   = $this->travellerRequestCount($traveller_id,1);
        $requestCount2   = $this->travellerRequestCount($traveller_id,2);
        $requestCount3   = $this->travellerRequestCount($traveller_id,3);
        $jnyCount1       = $this->travellerJnyCount($traveller_id,1);
        $jnyCount2       = $this->travellerJnyCount($traveller_id,2);
        $jnyCount3       = $this->travellerJnyCount($traveller_id,3);

        $requestURCount1 = $this->travellerRequestUnreadCount($traveller_id,1);
        $requestURCount2 = $this->travellerRequestUnreadCount($traveller_id,2);
        $requestURCount3 = $this->travellerRequestUnreadCount($traveller_id,3);
        $jnyURCount1     = $this->travellerJnyUnreadCount($traveller_id,1);
        $jnyURCount2     = $this->travellerJnyUnreadCount($traveller_id,2);
        $jnyURCount3     = $this->travellerJnyUnreadCount($traveller_id,3);
        $result = array(
            'response_code' => SUCCESS_CODE,
            'response_description'      => 'Updated '.GUEST_NAME.' Journey Count Successfully.',
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
}