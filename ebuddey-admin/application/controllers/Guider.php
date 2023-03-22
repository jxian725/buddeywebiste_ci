<?php  
defined('BASEPATH') OR exit('No direct script access allowed');   
                                                                    
class Guider extends CI_Controller { 
public $perPage = 3;
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Licensemodel');
        $this->load->model('Servicemodel');
        $this->load->model('Guiderpayoutmodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->library('phpqrcode/qrlib');
        sessionset();
        error_reporting(E_ALL);
        $this->load->library('encryption');
        // guider_id = talent_id
    }
	public function index() {
        $guider_search  = $this->input->get('guider_search'); 
        $order_by       = $this->input->get('order_by');
        $guider_lists   = $this->Guidermodel->guider_lists( $guider_search, $order_by );
        $data1[ 'guider_lists' ]     = $guider_lists;
        $content    = $this->load->view( 'guider/guider', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' lists';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' lists';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">'.HOST_NAME.' lists</li>';
        $this->template( $data );
	}
	function fetch()
	{
	  $output = '';
	 
	  $data = $this->Guidermodel->fetch_data($this->input->post('limit'), $this->input->post('start'));
	  if($data->num_rows() > 0)
	  {
	   foreach($data->result() as $row)
	   {
		$output .= '
		<div class="post_data">
		 <h3 class="text-danger">'.$row->post_title.'</h3>
		 <p>'.$row->post_description.'</p>
		</div>
		';
	   }
	  }
  echo $output;
 }
    public function view()
    {
        global $permission_arr;
        if( !in_array( 'guider/index', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        if($this->input->post( 'activity_value' ) == 1){
            $activity_id    = $this->input->post( 'activity_id' );
            $guider_id      = $this->input->post( 'guider_id' );
            $upload_path_url= $this->config->item( 'upload_path_url' );
            $activityInfo   = $this->Guidermodel->activityInfo( $activity_id );
            $data      = array();
            $data['price_type_id'] = $this->input->post( 'price_type_id' );
            if($this->input->post( 'price_type_id' ) == 3){
                $data['rate_per_person']    = '';
                $data['processingFeesType'] = 0;
                $data['processingFeesValue'] = 0; 
            }else{
                $data['rate_per_person'] = $this->input->post( 'rate_per_person' );
                if($this->input->post('processingFeesType') == 0){
                    $data['processingFeesType']  = 1; //DEFAULT PROCESSING FEES
                    $data['processingFeesValue'] = 0;
                }
            }
            $data['cancellation_policy'] = $this->input->post( 'cancellation_policy' );
            $data['service_providing_region'] = $this->input->post( 'service_providing_region' );
            $data['additional_info_label'] = $this->input->post( 'additional_info_label' );
            $data['maximum_booking']    = $this->input->post( 'maximum_booking' );
            $data['date_time_needed']   = $this->input->post( 'date_time_needed' );
            $guiding_speciality         = $this->input->post( 'guiding_speciality' );
            if($guiding_speciality){ $data['guiding_speciality'] = implode(',', $this->input->post( 'guiding_speciality' )); }
            $data['what_i_offer'] = $this->input->post( 'what_i_offer' );
            if ($_FILES['photo']['name']) {
                $rename     = $guider_id.'_guider_activity'.time() .'_1';
                $uploadData = $this->fileUpload( 'photo', 'g_activity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $photo_1         = $upload_path_url.'g_activity/'.$uploadData['file_name'];
                    $data['photo_1'] = $photo_1;
                    if($activityInfo->photo_1){
                        $existing_file   = end((explode("/", $activityInfo->photo_1)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if ($_FILES['photo1']['name']) {
                $rename     = $guider_id.'_guider_activity'.time() .'_2';
                $uploadData2= $this->fileUpload( 'photo1', 'g_activity', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $photo_2         = $upload_path_url.'g_activity/'.$uploadData2['file_name'];
                    $data['photo_2'] = $photo_2;
                    if($activityInfo->photo_2){
                        $existing_file   = end((explode("/", $activityInfo->photo_2)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if ($_FILES['photo2']['name']) {
                $rename     = $guider_id.'_guider_activity'.time() .'_3';
                $uploadData3= $this->fileUpload( 'photo2', 'g_activity', $rename );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $photo_3         = $upload_path_url.'g_activity/'.$uploadData3['file_name'];
                    $data['photo_3'] = $photo_3;
                    if($activityInfo->photo_3){
                        $existing_file   = end((explode("/", $activityInfo->photo_3)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if($activity_id){
                $this->Guidermodel->updateActivityInfo( $activity_id, $data );
            }else{
                $data['activity_guider_id']   = $guider_id;
                $this->Guidermodel->insertGuiderActivity( $data );
            }
            $this->session->set_flashdata('successMSG', 'Host Activity updated successfully.');
            redirect(base_url('guider/view/'.$guider_id));
        }

        $field = $this->input->post( 'field' );
        if($field == 'profile_image' || $field == 'id_proof' || $field == 'dbkl_lic') {
            $guider_id  = $this->input->post( 'guider_id' );
            $guiderInfo2= $this->Guidermodel->guiderInfo($guider_id);
            $data3      = array();
            if ($field == 'id_proof') {
                $rename     = $guider_id.'_identity'.time();
                $uploadData = $this->fileUpload( 'id_proof', 'identity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data3['id_proof'] = $uploadData['file_name'];
                }
                if($guiderInfo2->id_proof){
                    unlink('uploads/identity/'.$guiderInfo2->id_proof);
                }
            }
            if ($field == 'profile_image') {
                $rename     = $guider_id.'_member_profile'.time();
                $uploadData2= $this->fileUpload( 'profile_image', 'g_profile', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data3['profile_image'] = $uploadData2['file_name'];
                }
                if($guiderInfo2->profile_image){
                    unlink('uploads/g_profile/'.$guiderInfo2->profile_image);
                }
            }
            if ($field == 'dbkl_lic') {
                $rename3     = $guider_id.'_dbkl_lic_'.time();
                $uploadData3 = $this->fileUpload( 'dbkl_lic', 'dbkl', $rename3 );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $data3['dbkl_lic'] = $uploadData3['file_name'];
                    $data3['dbkl_status'] = 2;
                }
                if($guiderInfo2->dbkl_lic){
                    unlink('uploads/dbkl/'.$guiderInfo2->dbkl_lic);
                }
            }
            if(count($data3) > 0){
                $this->Guidermodel->updateGuiderInfo( $guider_id, $data3 );
            }
            redirect(base_url('guider/view/'.$guider_id));
        }

        $guider_id     = $this->uri->segment(3);
        $guiderInfo    = $this->Guidermodel->guiderInfo($guider_id);
        $activityLists = $this->Guidermodel->guiderActivityLists($guider_id);
        $reviewList    = $this->Guidermodel->reviewInfo(1,$guider_id);
        $totalLike     = $this->Guidermodel->ratingInfo($guider_id);
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $tplData[ 'guiderInfo' ]                = $guiderInfo;
        $tplData[ 'guider_id' ]                 = $guider_id;
        $tplData[ 'activityLists' ]             = $activityLists;
        $tplData[ 'reviewList' ]                = $reviewList;
        $tplData[ 'totalLike' ]                 = $totalLike;
        $tplData[ 'talentLicenseList' ]         = $this->Licensemodel->talentLicenseList($guider_id);
        $content    = $this->load->view( 'guider/guider_info', $tplData, true );
		
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Profile';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              <li class="active">'.HOST_NAME.' Profile</li>';
        $this->template( $data );
    }
    
    public function edit()
    {
        $guider_id    = $this->uri->segment(3);
        $guiderInfo   = $this->Guidermodel->guiderInfo($guider_id);
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'guiderInfo' ]                  = $guiderInfo;
        $data1[ 'guider_id' ]                   = $guider_id;
        $script     = '';
        $content    = $this->load->view( 'guider/edit_guider', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit '.HOST_NAME.'';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit '.HOST_NAME.'';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit '.HOST_NAME.'';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              <li class="active">Edit '.HOST_NAME.'</li>';
        $this->template( $data );
    }
    public function payout_info()
    {
		
        $guider_id    = $this->uri->segment(3);
        $guiderInfo   = $this->Guidermodel->guiderInfo($guider_id);
		
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'settledPayment' ]  = $this->Guiderpayoutmodel->guiderSettledPayment( $guider_id );
        $data1[ 'payoutAmt' ]       = $this->Guiderpayoutmodel->guiderPendingPayoutAmt( $guider_id );
        $data1[ 'percentageAmt' ]   = $this->Guiderpayoutmodel->guiderPendingPercentageAmt( $guider_id );
        $data1[ 'transactionAmt' ]  = $this->Guiderpayoutmodel->guiderPendingTransactionAmt( $guider_id );
        $data1[ 'pendingPaymentLists' ]  = $this->Guiderpayoutmodel->guiderPendingPaymentLists( $guider_id );
        $data1[ 'guiderInfo' ]      = $guiderInfo;
        $data1[ 'guider_id' ]       = $guider_id;
		$data1['inboxinfo']  = $this->Guidermodel->talentInboxinfo($guider_id);
		$data1['inboxreadinfo']  = $this->Guidermodel->talentInboxReadinfo($guider_id);
        $script     = '';
        $content    = $this->load->view( 'guider/payout_info', $data1, true );
		
        $data[ 'navigation' ]                   = '12';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' Payout';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Payout';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Payout';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              <li class="active">'.HOST_NAME.' Payout</li>';
        $this->template( $data );
    }
	public function loadMessage()
    {
        $guider_id    = $this->input->post( 'guider_id' );
        $guiderInfo   = $this->Guidermodel->guiderInfo($guider_id);
		
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'settledPayment' ]  = $this->Guiderpayoutmodel->guiderSettledPayment( $guider_id );
        $data1[ 'payoutAmt' ]       = $this->Guiderpayoutmodel->guiderPendingPayoutAmt( $guider_id );
        $data1[ 'percentageAmt' ]   = $this->Guiderpayoutmodel->guiderPendingPercentageAmt( $guider_id );
        $data1[ 'transactionAmt' ]  = $this->Guiderpayoutmodel->guiderPendingTransactionAmt( $guider_id );
        $data1[ 'pendingPaymentLists' ]  = $this->Guiderpayoutmodel->guiderPendingPaymentLists( $guider_id );
        $data1[ 'guiderInfo' ]      = $guiderInfo;
        $data1[ 'guider_id' ]       = $guider_id;
		$msgid  = $this->input->post( 'msgid' );
		$datearr  = $this->input->post( 'datearr' );
		$data1['datearr']=$datearr;
		$data1['inboxinfo']  = $this->Guidermodel->loadMoreMessage($msgid,$guider_id);
        // $script     = '';
        // $content    = $this->load->view( 'guider/payout_info', $data1, true );
        // $data[ 'navigation' ]                   = '';
        // $data[ 'Emessage' ]                     = '';
        // $data[ 'Smessage' ]                     = '';
        // $data[ 'header' ][ 'title' ]            = HOST_NAME.' Payout';
        // $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Payout';
        // $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Payout';
        // $data[ 'footer' ][ 'script' ]           = $script;
        // $data[ 'content' ]                      = $content;
        // $data[ 'breadcrumb' ]                   = '
                                              // <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              // <li class="active">'.HOST_NAME.' Payout</li>';
	$this->load->view('guider/load-more-data', $data1);
        //$this->template( $data );
    }
    function excutePayout(){
        $guider_id      = $this->input->post( 'guider_id' );
        $payoutAmt      = $this->input->post( 'payoutAmt' );
        $transactionAmt = $this->input->post( 'transactionAmt' );
        $percentageAmt  = $this->input->post( 'percentageAmt' );
        $totalTrip      = $this->input->post( 'totalTrip' );
        $this->Guiderpayoutmodel->updateExcutePayout( $guider_id, $payoutAmt, $transactionAmt, $percentageAmt, $totalTrip );
    }
    public function add()
    {
        global $permission_arr;
        if( !in_array( 'guider/add', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $phone_number = $this->input->post( 'phone_number' );
        $phone_number = ltrim($phone_number, '0');
        if($phone_number && $this->input->post( 'submit' ) == 'new_host'){
            $this->form_validation->set_rules( 'full_name', 'Full Name', 'required|min_length[3]' );
            $this->form_validation->set_rules( 'phone_number', 'Mobile Number', 'required|min_length[6]' );
            $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );
            if( $this->form_validation->run() == FALSE ) {
                $this->session->set_flashdata('errorMSG', validation_errors());
            }elseif($this->Guidermodel->guiderInfoByPhone($phone_number)){
                $this->session->set_flashdata('errorMSG', 'Phone Number Already Exists. Please enter different phone number.');
            }else {
                $hostdata = array();
                $user_id        = $this->session->userdata( 'USER_ID' );
                $first_name     = trim($this->input->post( 'full_name' ));
                $last_name      = trim($this->input->post( 'other_name' ));
                $email          = trim($this->input->post( 'email' ));
                $countryCode    = $this->input->post( 'countryCode' );
                $languages_known= $this->input->post( 'languages_known' );
                $dob           = $this->input->post( 'dob' );
                $acc_name      = trim($this->input->post( 'acc_name' ));
                $bank_name     = trim($this->input->post( 'bank_name' ));
                $acc_no        = trim($this->input->post( 'acc_no' ));
                $about_me      = $this->input->post( 'about_me' );
                $password      = trim($this->input->post( 'password' ));
                $hostdata      = array(
                                    'first_name'       => $first_name,
                                    'last_name'        => $last_name,
                                    'email'            => $email,
                                    'countryCode'      => $countryCode,
                                    'phone_number'     => $phone_number,
                                    'created_type'     => 'admin',
                                    'createded_by'     => $user_id,
                                    'mobilenoVerified' => 0,
                                    'created_on'       => date( 'Y-m-d H:i:s' ),
                                    'dbkl_lic_no'      => trim($this->input->post('dbkl_lic_no')),
                                    'nric_number'      => trim($this->input->post('nric_number')),
                                    'reg_device_type'  => 4,
                                    'status'           => 1
                                );
                if($dob){ $hostdata['age'] = $dob; }
                if($acc_name){ $hostdata['acc_name'] = $acc_name; }
                if($bank_name){ $hostdata['bank_name'] = $bank_name; }
                if($acc_no){ $hostdata['acc_no']     = $acc_no; }
                if($about_me){ $hostdata['about_me'] = $about_me; }
                if($password){
                    $new_password = $this->encryption->encrypt($password);
                    $hostdata['password'] = $new_password;
                }
                if($languages_known){
                    $languages     = implode(',', $languages_known);
                    $hostdata['languages_known'] = $languages;
                }
                if ($_FILES['id_proof']['name']) {
                    $rename1    = 'new_identity'.time();
                    $uploadData = $this->fileUpload( 'id_proof', 'identity', $rename1 );
                    if( $uploadData['status'] == 'error'){
                        $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                    }
                    if( $uploadData['status'] == 'success'){
                        $hostdata['id_proof'] = $uploadData['file_name'];
                    }
                }
                if ($_FILES['profile_image']['name']) {
                    $rename2    = 'new_member_profile'.time();
                    $uploadData2= $this->fileUpload( 'profile_image', 'g_profile', $rename2 );
                    if( $uploadData2['status'] == 'error'){
                        $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                    }
                    if( $uploadData2['status'] == 'success'){
                        $hostdata['profile_image'] = $uploadData2['file_name'];
                    }
                }
                if ($_FILES['dbkl_lic']['name']) {
                    $rename3    = 'dbkl_lic_img_'.time();
                    $uploadData3= $this->fileUpload( 'dbkl_lic', 'dbkl', $rename3 );
                    if( $uploadData3['status'] == 'error'){
                        $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                    }
                    if( $uploadData3['status'] == 'success'){
                        $hostdata['dbkl_lic'] = $uploadData3['file_name'];
                        $hostdata['dbkl_status'] = 2;
                    }
                }
                $hostdata['host_uuid'] = gen_uuid();
                $host_id = $this->Guidermodel->insertHost($hostdata);
                $this->session->set_flashdata('successMSG', ''.HOST_NAME.' added successfully.');
                redirect(base_url('guider/view/'.$host_id));
            }
        }
        $data1      = array();
        $data1[ 'getHostLangLists' ]  = $this->Guidermodel->getHostLangLists();
        $content    = $this->load->view( 'guider/add_guider', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add '.HOST_NAME.'';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add '.HOST_NAME.'';
        $data[ 'header' ][ 'metadescription' ]  = 'Add '.HOST_NAME.'';
        $data[ 'content' ]                      = $content;
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'breadcrumb' ]                   = '
                                                   <li><a href="'.$this->config->item( 'admin_url' ).'guider">Manage '.HOST_NAME.'</a></li>
                                                   <li class="active">Add '.HOST_NAME.'</li>';
        $this->template( $data );
    }
    function guiderStatus(){
        $guider_id  = $this->input->post( 'guider_id' );
        $status     = $this->input->post( 'status' );
        $this->Guidermodel->guiderStatus( $guider_id, $status );
        if($status == 1){
            //PUSH NOTIFICATION
            $deviceTokenList  = $this->Guidermodel->guiderDeviceTokenList( $guider_id );
            if($deviceTokenList){
                $push_data  = array(
                                    'title'          => ''.HOST_NAME.'',
                                    'body'           => 'Your account has been activated.',
                                    'action'         => 'active_account',
                                    'notificationId' => 10,
                                    'sound'          => 'notification',
                                    'icon'           => 'icon'
                                    );
                $device_tokenA = array();
                $device_tokenI = array();
                foreach ($deviceTokenList as $tokenList) {
                    $device_token   = trim($tokenList->device_token);
                    $device_type    = trim($tokenList->device_type);
                    if ($device_type == 3) {
                        if (strlen($device_token) > 10){
                            $device_tokenA[] = $device_token;
                        }
                    }else if ($device_type == 2) {
                        if (strlen($device_token) > 10){
                            $device_tokenI[] = $device_token;
                        }
                    }
                }
                if (!empty($device_tokenA)) {
                  $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'G');
                }
                if (!empty($device_tokenI)) {
                    $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'G');
                }
            }
            $this->session->set_flashdata('successMSG', ''.HOST_NAME.' Activate successfully.');
        }else{
            $this->session->set_flashdata('successMSG', ''.HOST_NAME.' Deactivate successfully.');
        }
    }
    //Password Update
    function passwordConfirm() {
        $guider_id  = $this->input->post( 'guider_id' );
        $data1[ 'guider_id' ] = $guider_id;
        $guider_info = $this->Guidermodel->guiderInfo( $guider_id );
        $data1[ 'guider_info' ] = $guider_info;
        echo $this->load->view( 'guider/password_update_form', $data1, true );
        return true;
    }
    //Check Password Exists
    function update_password_info() {
        $guider_id  = $this->input->post( 'guider_id' );
        $user_id    = $this->session->userdata( 'USER_ID' );
        $password   = $this->input->post( 'password' );
        $this->form_validation->set_rules( 'password', 'Password', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $data[ 'Jmsg' ]     = validation_errors();
            $data[ 'Jerror' ]   = 1;
            echo json_encode( $data );
            return false;
        } else {
            if( !$this->Guidermodel->check_password( $user_id, $password ) ) {
                $data[ 'Jmsg' ]     = 'Enter password not matched.';
                $data[ 'Jerror' ]   = 3;
                echo json_encode( $data );
                return false;
            } else {
                $data[ 'Jerror' ]   = 2;
                echo json_encode( $data );
                return true;
            }
        }
    }
    function deleteGuider(){
        $guider_id  = $this->input->post( 'guider_id' );
        $status     = $this->input->post( 'status' );
        $guiderInfo = $this->Guidermodel->guiderInfo($guider_id);
        $del_phone_number = $guiderInfo->phone_number.'_RM'.$guider_id;
        $data       = array('status' => $status, 'phone_number' => $del_phone_number);
        $delete     = $this->Guidermodel->deleteGuider( $guider_id, $data );
        return true;
    }
    //UPDATE HOST INFO
    function updateGuiderForm() {
        $guider_id  = $this->input->post( 'guider_id' );
        $tplData[ 'guider_id' ]  = $guider_id;
        $tplData[ 'field' ]      = $this->input->post( 'field' );
        $tplData[ 'guiderInfo' ] = $this->Guidermodel->guiderInfo( $guider_id );
        $tplData[ 'cityLists' ]  = $this->Commonmodel->state_list( $country_id=132, 0 );
        $tplData[ 'serviceRegionLists' ] = $this->Guidermodel->serviceRegionLists();
        $tplData[ 'specializationLists' ] = $this->Guidermodel->getSpecializationLists();
        $tplData[ 'getHostLangLists' ]  = $this->Guidermodel->getHostLangLists();
        echo $this->load->view( 'guider/update_info_form', $tplData, true );
    }
    //UPDATE HOST PRICE
    function hostActivityPricing() {
        $activity_id  = $this->input->post( 'activity_id' );
        $data1[ 'activity_id' ]  = $this->input->post( 'activity_id' );
        $data1[ 'activityInfo' ] = $this->Guidermodel->activityInfo( $activity_id );
        echo $this->load->view( 'guider/host_price_form', $data1, true );
    }
    function updateProcessingFee(){
        $activity_id  = $this->input->post( 'activity_id' );
        $processingFeesType = $this->input->post( 'processingFeesType' );
        $data['processingFeesType']   = $processingFeesType;
        if($processingFeesType == 2){
            $percentage_value   = $this->input->post( 'percentage_value' );
            $data['processingFeesValue']   = $percentage_value;
        }elseif ($processingFeesType == 3) {
            $fixed_rate_value   = $this->input->post( 'fixed_rate_value' );
            $data['processingFeesValue']   = $fixed_rate_value;
        }
        echo $this->Guidermodel->updateActivityInfo( $activity_id, $data );
    }
    function updateHostActivityForm() {
        $activity_id  = $this->input->post( 'activity_id' );
        $guider_id    = $this->input->post( 'guider_id' );
        $data1[ 'activity_id' ]  = $this->input->post( 'activity_id' );
        $data1[ 'guider_id' ]    = $guider_id;
        $data1[ 'serviceLists' ] = $this->Guidermodel->getGuiderActiveServiceRegionLists( $guider_id );
        $data1[ 'activityInfo' ] = $this->Guidermodel->activityInfo( $activity_id );
        $data1[ 'serviceRegionLists' ] = $this->Guidermodel->serviceRegionLists();
        $data1[ 'specializationLists' ] = $this->Guidermodel->getSpecializationLists();
        echo $this->load->view( 'guider/guider_activity_form', $data1, true );
    }
    function updateGuiderField() {
        $guider_id  = $this->input->post( 'guider_id' );
        $field      = $this->input->post( 'field' );
        $imageType  = '';
        $data       = array();
        if($field == 'about'){
            $data['about_me'] = $this->input->post( 'about_me' );
        }elseif ($field == 'policy') {
            $data['cancellation_policy'] = $this->input->post( 'cancellation_policy' );
        }elseif ($field == 'area') {
            $data['area'] = $this->input->post( 'area' );
        }elseif ($field == 'city') {
            $data['city'] = $this->input->post( 'city' );
        }elseif ($field == 'gigs_amount') {
            $data['gigs_amount'] = $this->input->post( 'gigs_amount' );
        }elseif ($field == 'acc_no') {
            $data['acc_no'] = $this->input->post( 'acc_no' );
        }elseif ($field == 'acc_name') {
            $data['acc_name'] = $this->input->post( 'acc_name' ); 
        }elseif ($field == 'bank_name') {
            $data['bank_name'] = $this->input->post( 'bank_name' );  
        }elseif ($field == 'gender') {
            $data['gender'] = $this->input->post( 'gender' );
        }elseif ($field == 'sub_skills') {
            $data['sub_skills'] = $this->input->post( 'sub_skills' );
        }elseif ($field == 'skills_category') {
            $data['skills_category'] = $this->input->post( 'skills_category' );                                    
        }elseif ($field == 'first_name') {
            $data['first_name'] = $this->input->post( 'first_name' );
        }elseif ($field == 'last_name') {
            $data['last_name'] = $this->input->post( 'last_name' );
        }elseif ($field == 'age') {
            $data['age'] = date('Y-m-d', strtotime($this->input->post('dob')));
        }elseif ($field == 'region') {
            $data['service_providing_region'] = $this->input->post( 'service_providing_region' );
        }elseif ($field == 'category') {
            $guiding_speciality     = implode(',', $this->input->post( 'guiding_speciality' ));
            $data['guiding_speciality'] = $guiding_speciality;
        }elseif ($field == 'offer') {
            $data['what_i_offer'] = $this->input->post( 'what_i_offer' );
        }elseif ($field == 'password') {
            $new_password = $this->encryption->encrypt($this->input->post( 'password' ));
            $data['password'] = $new_password;
        }elseif ($field == 'language') {
            $languages_known = implode(',', $this->input->post( 'languages_known' ));
            $data['languages_known'] = $languages_known;
        }elseif ($field == 'dbkl_lic_no') {
            $data['dbkl_lic_no'] = $this->input->post( 'dbkl_lic_no' );
            $data['dbkl_status'] = 2;
        }elseif ($field == 'nric_number') {
            $data['nric_number'] = $this->input->post( 'nric_number' );
        }elseif ($field == 'phone_number') {
            if($this->Guidermodel->existingGuidePhoneNo( $guider_id, $this->input->post( 'phone_number' ) )){
                $data[ 'status' ]  = 2;
                $data[ 'message' ] = 'Phone number already reegistred. please try new number.';
                echo json_encode( $data );
                return true;
            }else{
                $data['phone_number'] = $this->input->post( 'phone_number' );
            }
        }
        if(count($data) > 0){
            $this->Guidermodel->updateGuiderInfo( $guider_id, $data );
        }
        $data[ 'status' ]  = 1;
        echo json_encode( $data );
        return true;
    }
    function guiderActivityStatus(){
        $activity_id    = $this->input->post( 'activity_id' );
        $status         = $this->input->post( 'status' );
        $data['activity_status'] = $status;
        $this->Guidermodel->updateActivityInfo( $activity_id, $data );
        if($status == 4){
            $data2      = array('status' => 3);
            $this->Servicemodel->cancelPendingRequests($activity_id, $data2);
            $USER_ID    = $this->session->userdata( 'USER_ID' );
            $data3      = array(
                            'log_type'      => 'ACTIVITY',
                            'log_action'    => 'DELETE',
                            'activity_id'   => $activity_id,
                            'user_id'       => $USER_ID,
                            'user_type'     => 'ADMIN',
                            'log_createdon' => date( 'Y-m-d H:i:s' )
                            );
            $this->Commonmodel->insertServiceLog( $data3 );
        }
    }
    function generate_qr_code($value='')
    {
        $guider_id  = $this->input->post( 'guider_id' );
        $guiderInfo = $this->Guidermodel->guiderInfo( $guider_id );
        if($guiderInfo){
            $folder     = 'uploads/qrscan/';
            $rename     = $guider_id.".png";
            $file_name  = $folder.$rename;
            $donateurl  = $this->config->item( 'donate_url' ).'Makepayment/form/host_'.$guiderInfo->host_uuid;
            QRcode::png($donateurl,$file_name,'H',8,2);
            $data   = array('qr_image' => $rename);
            $this->Guidermodel->updateGuiderInfo( $guider_id, $data );
        }
    }
    //Get QR Code
    function get_qr_code() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $guider_id   = $this->input->post( 'guider_id' );
        $data_content[ 'guider_id' ]   = $guider_id;
        $data_content[ 'guiderInfo' ] = $this->Guidermodel->guiderInfo( $guider_id );
        echo $this->load->view( 'guider/qr_code', $data_content, true );
        return true;
    }
    //Get QR Code
    function updatedbklStatusForm() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $guider_id   = $this->input->post( 'guider_id' );
        $data_content[ 'guider_id' ]   = $guider_id;
        $data_content[ 'guiderInfo' ] = $this->Guidermodel->guiderInfo( $guider_id );
        echo $this->load->view( 'guider/update_dbkl_status_form', $data_content, true );
        return true;
    }
    function updateDbklStatus(){

        $guider_id  = $this->input->post( 'guider_id' );
        $status     = $this->input->post( 'status' );
        $guiderInfo = $this->Guidermodel->guiderInfo( $guider_id );
        if($guiderInfo){
            $data   = array('dbkl_status' => $status);
            $update = $this->Guidermodel->updateGuiderInfo( $guider_id, $data );
            if($update){
                if($status==1){ $msg = 'DBKL License has been approved successfully'; }else{ $msg = 'DBKL License has been rejected successfully'; }
                $this->session->set_flashdata('successMSG', $msg);
            }else{
                $this->session->set_flashdata('errorMSG', 'An error occurred, please try again later');
            }
        }
    }
    function fileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './uploads/'.$folder;
          $config['allowed_types']      = 'gif|jpg|jpeg|png';
          $config['max_size']           = 15360; //15MB = 15*1024
          $config['max_width']          = $width;
          $config['max_height']         = $height;

          $filetype                     = $_FILES[ "$filename" ][ 'type' ];
          $expfiletype                  = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
          $config['file_name']          = $rename.'.'.$expfiletype[ 1 ];

          $this->upload->initialize( $config );
          $this->load->library( 'upload', $config );

          if ( ! $this->upload->do_upload( $filename ) ) {
              $res = array( 'status' => 'error','msg' => $this->upload->display_errors() );
              return $res;
          } else {
              $data = array( 'upload_data' => $this->upload->data() );
              $res  = array( 'status' => 'success','file_name' => $config['file_name'],'msg' => 'file upload successfully.' );
              return $res;
          }
    }
	function guiderStrength(){
        $guider_id  = $this->input->post( 'guider_id' );
        $strength     = trim($this->input->post( 'strength' ));
		$comment     = trim($this->input->post( 'comment' ));
        $this->Guidermodel->guiderStrength( $guider_id, $strength,$comment );
        return true;
	 }
	function addMessage(){
       
            $guider_id  = $this->input->post( 'guiderid' );
            $data   = array(
                        'talent_id'      => $guider_id,
                        'message'           => trim($this->input->post( 'message' )),
                        'istalent_message'      => 0,
                        'is_admin_message'      => 1,
                        'istalent_readstatus'   => 1,
                        'isadmin_readstatus'=> 0,
						'istalent_delete'   => 0,
                        'is_admin_delete'   => 0,
                        'created_at'         => date("Y-m-d H:i:s"),
						'updated_at'         => date("Y-m-d H:i:s")
                    );
            $result  = $this->Guidermodel->addMessage($data);
            if($result){             
                echo 1;
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        
    }
	function deleteMessage(){
		$msgid  = $this->input->post( 'msgid' );
		$data       = array();
        $data['is_admin_delete'] = 1;
		
		$result  = $this->Guidermodel->deleteMessage($msgid,$data);
		if($result){             
			echo 1;
		}else{
			echo 'Some error has occurred. Please try again later.';
		}
	}
	function updatereadStatus()
	{
		$guider_id  = $this->input->post('guider_id');
		$data['isadmin_readstatus'] = 0;
		$this->Guidermodel->updateadminreadStatus( $guider_id, $data );
	}		
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
