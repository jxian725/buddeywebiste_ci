<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Profile extends CI_Controller {  
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->library('phpqrcode/qrlib');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    public function index() {

        //$this->db->query("ALTER TABLE senangpay_transaction MODIFY sub_total DECIMAL(10,2)");
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData['imageLists']   = $this->Talentmodel->imageLists($talent_id);
        $tplData['urlLists']     = $this->Talentmodel->urlLists($talent_id);
        $tplData['talentInfo']   = $this->Talentmodel->talentInfo($talent_id);
        $tplData['reviewList']   = $this->Talentmodel->talentReviewLists(1, $talent_id);
        $tplData['totalLike']    = $this->Talentmodel->ratingInfo($talent_id);
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'comments_list' ] = $this->Talentmodel->donation_lists($talent_id, 20);
        $tplData['socialLinkInfo']  = $this->Talentmodel->talentSocialLinkInfo($talent_id);
        $content     = $this->load->view( 'talent/profile/view_profile', $tplData, true );
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = 'profile';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Profile';
        $data[ 'header' ][ 'metakeyword' ]      = 'Profile';
        $data[ 'header' ][ 'metadescription' ]  = 'Profile';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Profile</li>'; 
        $this->template( $data );
    }
    public function edit() {
        //$this->db->query("ALTER TABLE senangpay_transaction MODIFY sub_total DECIMAL(10,2)");
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData['imageLists']   = $this->Talentmodel->imageLists($talent_id);
        $tplData['urlLists']     = $this->Talentmodel->urlLists($talent_id);
        $tplData['talentInfo']   = $this->Talentmodel->talentInfo($talent_id);
        $tplData['reviewList']   = $this->Talentmodel->talentReviewLists(1, $talent_id);
        $tplData['totalLike']    = $this->Talentmodel->ratingInfo($talent_id);
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'cityLists' ]  = $this->Talentmodel->state_list( $country_id=132, 0 );
        $tplData['socialLinkInfo']  = $this->Talentmodel->talentSocialLinkInfo($talent_id);
        $content     = $this->load->view( 'talent/profile/edit_profile', $tplData, true );
        $data[ 'navigation' ]                   = 'profile';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit Profile';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit Profile';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit Profile';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Edit Profile</li>'; 
        $this->template( $data );
    }
    //UPDATE HOST INFO
    function updateGuiderForm() {
        $talent_id  = $this->input->post( 'talent_id' );
        $tplData[ 'talent_id' ]  = $talent_id;
        $tplData[ 'field' ]      = $this->input->post( 'field' );
        $tplData[ 'talentInfo' ] = $this->Talentmodel->talentInfo( $talent_id );
        $tplData[ 'cityLists' ]  = $this->Commonmodel->searchCityLists();
        //$tplData[ 'serviceRegionLists' ] = $this->Guidermodel->serviceRegionLists();
        //$tplData[ 'specializationLists' ] = $this->Guidermodel->getSpecializationLists();
        echo $this->load->view( 'talent/profile/update_info_form', $tplData, true );
    }
    function updateGuiderField() {
        $talent_id  = $this->input->post( 'talent_id' );
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
        }elseif ($field == 'last_name') {
            $data['last_name'] = $this->input->post( 'last_name' );
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
        }elseif ($field == 'dbkl_lic_no') {
            $data['dbkl_lic_no'] = $this->input->post( 'dbkl_lic_no' );
            $data['dbkl_status'] = 2;
        }elseif ($field == 'nric_number') {
            $data['nric_number'] = $this->input->post( 'nric_number' );
        }
        if(count($data) > 0){
            $this->Talentmodel->updateTalent($talent_id, $data);
        }
        $data[ 'status' ]  = 1;
        echo json_encode( $data );
        return true;
    }

    public function validYURL($url){
        $rx = '~
              ^(?:https?://)?                           # Optional protocol
               (?:www[.])?                              # Optional sub-domain
               (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
               ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
            //com/embed/
        $has_match = preg_match($rx, $url, $matches);
        return $has_match;
    }
    // Command Response
    public function addProfile() {
        $data['id']   = $this->input->post( 'id' );
        $data['field']= $this->input->post('field');
        $id = $this->input->post( 'id' );
        $data['talentInfo']  = $this->Talentmodel->talentInfo($id);
        echo  $this->load->view( 'talent/profile/add', $data, true ); 
    }
    function updateAjaxProfile(){
        $upload_path_url = $this->config->item( 'admin_upload_url' );

        $talent_id   = $this->session->userdata['TALENT_ID'];
        $talentInfo  = $this->Talentmodel->talentInfo($talent_id);
        $rename     = $talent_id.'_member_profile'.time();
        $uploadData = $this->fileUpload( 'profile_image', 'g_profile', $rename );
        if( $uploadData['status'] == 'error'){
            $this->session->set_flashdata('errorMSG', $uploadData['msg']);
            $res = array('error' => $uploadData['msg']);
        }
        if( $uploadData['status'] == 'success'){
            $data = array('profile_image' => $uploadData['file_name']);
            $this->Talentmodel->updateTalent($talent_id, $data);
            $ProfilePic = $upload_path_url.'g_profile/'.$uploadData['file_name'];
            $res = array('success' => 1, 'ProfilePic' => $ProfilePic);
        }
        if($talentInfo->profile_image){
            if(file_exists('ebuddey-admin/uploads/g_profile/'.$talentInfo->profile_image)){
                unlink('ebuddey-admin/uploads/g_profile/'.$talentInfo->profile_image);
            }
        }
        echo json_encode($res);
    }
    function getMystory(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $talent_id  = $this->session->userdata['TALENT_ID'];
        $talentInfo = $this->Talentmodel->talentInfo($talent_id);
        $str = '';
        $str .= '<div id="update_mystory_form" class="box-body">
                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="my_story" class="control-label">My Story<span class="text-danger">*</span></label>
                      <div class="">
                        <textarea rows="5" class="form-control" name="my_story" id="my_story">'.$talentInfo->about_me.'</textarea>
                      </div>
                    </div>
                    </div>
                    </div>
                </div>';
        echo $str;

    }
    function updateMystory(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $my_story  = $this->input->post( 'my_story' );
        $talent_id = $this->session->userdata['TALENT_ID'];
        if($my_story){
            $data = array( 'about_me' => $my_story );
            $this->Talentmodel->updateTalent($talent_id, $data);
            $this->session->set_flashdata('successMSG2', 'My Story Update successfully.');
            echo json_encode(array('status' => 'success','msg' => 'User status Updated successfully.'));
        }else{
            $this->session->set_flashdata('errorMSG2', 'Some error has occurred. Please try again later.');
            echo json_encode(array('status' => 'failed', 'msg' => 'Some error has occurred. Please try again later.'));
        }
    }
    function updateProfile(){
        
        $field = $this->input->post( 'field' );
        if($field == 'profile_image' || $field == 'id_proof' || $field == 'dbkl_lic') {
            $talent_id = $this->input->post('id');
            $talentInfo = $this->Talentmodel->talentInfo($talent_id);
            $data3      = array();
            if ($field == 'id_proof') {
                $rename     = $talent_id.'_identity'.time();
                $uploadData = $this->fileUpload( 'id_proof', 'identity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data3['id_proof'] = $uploadData['file_name'];
                }
                if($talentInfo->id_proof){
                    unlink('ebuddey-admin/uploads/identity/'.$talentInfo->id_proof);
                }
            }
            if ($field == 'profile_image') {
                $rename     = $talent_id.'_member_profile'.time();
                $uploadData2= $this->fileUpload( 'profile_image', 'g_profile', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data3['profile_image'] = $uploadData2['file_name'];
                }
                if($talentInfo->profile_image){
                    unlink('ebuddey-admin/uploads/g_profile/'.$talentInfo->profile_image);
                }
            }
            if ($field == 'dbkl_lic') {
                $rename3     = $talent_id.'_dbkl_lic_'.time();
                $uploadData3 = $this->fileUpload( 'dbkl_lic', 'dbkl', $rename3 );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $data3['dbkl_lic'] = $uploadData3['file_name'];
                    $data3['dbkl_status'] = 2;
                }
                if($talentInfo->dbkl_lic){
                    unlink('ebuddey-admin/uploads/dbkl/'.$talentInfo->dbkl_lic);
                }
            }
            if(count($data3) > 0){
                $this->Talentmodel->updateTalent($talent_id, $data3);
                $this->session->set_flashdata('successMSG', 'Profile Image Update successfully.');
                $data[ 'status' ]  = 1;
                echo json_encode( $data );
            }else{
                $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
            }
        }
    }
    public function addSocialLink() {

        $talent_id = $this->session->userdata['TALENT_ID'];
        $data['socialLinkInfo']  = $this->Talentmodel->talentSocialLinkInfo($talent_id);
        echo  $this->load->view( 'talent/profile/add_social_link', $data, true ); 
    }
    function updateSocialLink(){
        $socialId = $this->input->post( 'id' );
        if($socialId){
            $data   = array(
                        'website_link'  => trim($this->input->post('website_link')),
                        'fb_link'       => trim($this->input->post('fb_link')),
                        'twitter_link'  => trim($this->input->post('twitter_link')),
                        'gplus_link'    => trim($this->input->post('gplus_link')),
                        'behance_link'  => trim($this->input->post('behance_link')),
                        'pinterest_link'=> trim($this->input->post('pinterest_link')),
                        'instagram_link'=> trim($this->input->post('instagram_link')),
                        'youtube_link'  => trim($this->input->post('youtube_link'))
                    );
            $this->Talentmodel->updateTalentSocialLink($socialId, $data);
            $this->session->set_flashdata('successMSG', 'Social Link Update successfully.');
            echo json_encode( array('status' => 1) );
        }else{
            $data   = array(
                        'talent_id'     => $this->session->userdata['TALENT_ID'],
                        'website_link'  => trim($this->input->post('website_link')),
                        'fb_link'       => trim($this->input->post('fb_link')),
                        'twitter_link'  => trim($this->input->post('twitter_link')),
                        'gplus_link'    => trim($this->input->post('gplus_link')),
                        'behance_link'  => trim($this->input->post('behance_link')),
                        'pinterest_link'=> trim($this->input->post('pinterest_link')),
                        'instagram_link'=> trim($this->input->post('instagram_link')),
                        'youtube_link'  => trim($this->input->post('youtube_link')),
                        'createdon'     => date( 'Y-m-d H:i:s' ),
                        'status'        => 1
                    );
            $this->Talentmodel->addTalentSocialLink($data);
            $this->session->set_flashdata('successMSG', 'Social Link added successfully.');
            echo json_encode( array('status' => 1) );
        }
    }
    public function addUrl() {
        $data['id']   = $this->input->post( 'id' );
        $data['field']= $this->input->post('field');
        $id = $this->input->post( 'id' );
        $data['talentInfo']  = $this->Talentmodel->talentInfo($id);
        echo  $this->load->view( 'talent/profile/add_url', $data, true ); 
    }
    function updateUrl(){
        $id          = $this->input->post( 'id' );
        $url_link    = $this->input->post('url_link');
        $description = $this->input->post('description');
        if($url_link && !$this->validYURL($url_link)){
            $this->session->set_flashdata('errorMSG', 'Please Enter valid URL.');
            echo json_encode( array('status' => 0) );
        }else{
            $talentInfo  = $this->Talentmodel->talentInfo($id);
            if($talentInfo){
                $video_id = explode("?v=", $url_link);
                $video_id = $video_id[1];
                $embed_url= 'https://www.youtube.com/embed/'.$video_id;
                $data   = array(
                            'talent_id'   => $id,
                            'url_link'    => $embed_url,
                            'description' => $description, 
                            'createdon'   => date( 'Y-m-d H:i:s' ),
                            'status'      => 0
                        );
                $this->Talentmodel->addUrl($data);
                $this->session->set_flashdata('successMSG', 'Youtube Url Update successfully.');
                echo json_encode( array('status' => 1) );
            }else{
                $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
                echo json_encode( array('status' => 0) );
            }
        }
    }
    function deleteUrl() {
        $id = $this->input->post( 'id' );
        if($id){
            $this->Talentmodel->deleteUrl( $id );
        }
        return true;
    }
    function updateGigsInfo(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        
        $this->form_validation->set_rules( 'nric_number', 'Bank Name', 'required' );
        $this->form_validation->set_rules('skills_category', 'Skills Category', 'required');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $talent_id = $this->session->userdata['TALENT_ID'];
            $updateData= array(
                            'nric_number'=> trim($this->input->post('nric_number')),
                            'city'       => trim($this->input->post('city')),
                            'area'       => trim($this->input->post('area')),
                            'skills_category'=> trim($this->input->post('skills_category')),
                            'sub_skills' => trim($this->input->post('sub_skills'))
                        );
            $update  = $this->Talentmodel->updateTalent($talent_id, $updateData);
            if($update){
                echo 1;
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        }
    }
    function updateBankInfo(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        
        $this->form_validation->set_rules( 'bank_name', 'Bank Name', 'required' );
        $this->form_validation->set_rules('acc_no', 'Account Number', 'required');
        $this->form_validation->set_rules('acc_name', 'Account Name', 'required');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $talent_id = $this->session->userdata['TALENT_ID'];
            $updateData= array(
                            'bank_name' => trim($this->input->post('bank_name')),
                            'acc_no'    => trim($this->input->post('acc_no')),
                            'acc_name'  => trim($this->input->post('acc_name'))
                        );
                        //'activate_donation_code' => trim($this->input->post('activate_donation'))
            $update  = $this->Talentmodel->updateTalent($talent_id, $updateData);
            if($update){
                //$this->session->set_flashdata('successMSG', 'Feedback Added successfully.');
                echo 1;
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        }
    }
    function uploadAttachmentFile(){
        $upload_path_url = $this->config->item( 'admin_upload_url' );

        $talent_id  = $this->session->userdata['TALENT_ID'];
        $type       = $this->input->post('type');
        $talentInfo = $this->Talentmodel->talentInfo($talent_id);
        $rename     = $talent_id.'_member_identity'.time();
        $uploadData = $this->fileUpload( 'attachment_file', 'identity', $rename, $size='', $width='', $height='', $allowed_types='pdf' );
        if( $uploadData['status'] == 'error'){
            $this->session->set_flashdata('errorMSG', $uploadData['msg']);
            $res = array('error' => $uploadData['msg']);
        }
        if( $uploadData['status'] == 'success'){
            if($type == 1){
                $data = array('dbkl_lic' => $uploadData['file_name']);
            }else{
                $data = array('id_proof' => $uploadData['file_name']);
            }
            $this->Talentmodel->updateTalent($talent_id, $data);
            $attachment_url = $upload_path_url.'identity/'.$uploadData['file_name'];
            $res = array('success' => 1, 'attachment_url' => $attachment_url, 'attachment_name' => $uploadData['file_name']);
        }
        if($type == 1){
            if($talentInfo->dbkl_lic){
                unlink('ebuddey-admin/uploads/identity/'.$talentInfo->dbkl_lic);
            }
        }
        if($type == 2){
            if($talentInfo->id_proof){
                unlink('ebuddey-admin/uploads/identity/'.$talentInfo->id_proof);
            }
        }
        echo json_encode($res);
    }

    function get_qr_code(){
        $talent_id      = $this->session->userdata['TALENT_ID'];
        $talentInfo     = $this->Talentmodel->talentInfo($talent_id);
        $donation_type  = $this->input->post('donation_type');
        if($donation_type == 1){
            if(!$talentInfo->qr_image){
                $folder     = 'ebuddey-admin/uploads/qrscan/';
                $rename     = $talent_id.".png";
                $file_name  = $folder.$rename;
                $donateurl  = $this->config->item( 'donate_url' ).'Makepayment/form/host_'.$talentInfo->host_uuid;
                QRcode::png($donateurl,$file_name,'H',8,2);
                $data   = array('qr_image' => $rename);
                $this->Talentmodel->updateTalent( $talent_id, $data );
            }
        }elseif ($donation_type == 2) {
            $tplData[ 'donateurl' ] = $this->config->item( 'donate_url' ).'Makepayment/form/host_'.$talentInfo->host_uuid;
        }
        $tplData[ 'donation_type' ] = $donation_type;
        $tplData[ 'talent_id' ] = $talent_id;
        $tplData['talentInfo']  = $this->Talentmodel->talentInfo($talent_id);
        echo  $this->load->view( 'talent/profile/qr_code', $tplData, true ); 
    }
    // END
    function fileUpload( $filename, $folder, $rename='', $size='', $width='', $height='', $allowed_types='' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './ebuddey-admin/uploads/'.$folder;
          $config['allowed_types']      = (($allowed_types)? $allowed_types : 'gif|jpg|jpeg|png');
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
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   