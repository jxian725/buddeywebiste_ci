<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller { 

    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model( 'Feedbackmodel' );
        $this->load->model( 'Guidermodel' );
        $this->load->model( 'Dashboardmodel' );
        $this->load->model('Travellermodel');
        $this->load->model( 'Settingsmodel' );
        sessionset();
        error_reporting(E_ALL);
    }

    public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]    = $this->Guidermodel->guider_lists( false,false );
        $data1[ 'traveller_lists' ] = $this->Travellermodel->traveller_lists( false,false );
        $content    = $this->load->view( 'feedback/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Feedback lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Feedback lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Feedback lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Feedback lists</li>';
        $this->template( $data );
    }
    public function guiderFeedbackTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $feedbackLists   = $this->Feedbackmodel->get_guider_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($feedbackLists){
            foreach ($feedbackLists as $feedback) {
                $viewbtn    = '<a href="'.base_url().'feedback/guider_view/'.$feedback->support_id.'" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search"></i></a>';
                $deletebtn  = '<a href="javascript:;" onclick="return deleteGuiderFeedback('.$feedback->support_id.');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a>';
                if($feedback->device_type == 1){
                    $device_type    = 'Web';
                }elseif($feedback->device_type == 2){
                    $device_type    = 'iOS';
                }else if($feedback->device_type == 3){
                    $device_type    = 'Android';
                }else{
                    $device_type    = '';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $feedback->guiderName;
                $row[]  = $feedback->subject;
                $row[]  = $feedback->description;
                $row[]  = $feedback->app_version;
                $row[]  = $device_type;
                $row[]  = date('d M Y H:i A', strtotime($feedback->createdon));
                $row[]  = $viewbtn.$deletebtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Feedbackmodel->count_guider_all($where),
                        "recordsFiltered"   => $this->Feedbackmodel->count_guider_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function travellerFeedbackTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $feedbackLists   = $this->Feedbackmodel->get_traveller_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($feedbackLists){
            foreach ($feedbackLists as $feedback) {
                $viewbtn    = '<a href="'.base_url().'feedback/traveller_view/'.$feedback->support_id.'" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search"></i></a>';
                $deletebtn  = '<a href="javascript:;" onclick="return deleteTravellerFeedback('.$feedback->support_id.');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a>';
                if($feedback->device_type == 1){
                    $device_type    = 'Web';
                }elseif($feedback->device_type == 2){
                    $device_type    = 'iOS';
                }else if($feedback->device_type == 3){
                    $device_type    = 'Android';
                }else{
                    $device_type    = '';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $feedback->travellerName;
                $row[]  = $feedback->subject;
                $row[]  = $feedback->description;
                $row[]  = $feedback->app_version;
                $row[]  = $device_type;
                $row[]  = date('d M Y H:i A', strtotime($feedback->createdon));
                $row[]  = $viewbtn.$deletebtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Feedbackmodel->count_traveller_all($where),
                        "recordsFiltered"   => $this->Feedbackmodel->count_traveller_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function traveller_view() {
        $support_id     = $this->uri->segment(3);
        $feedbackInfo   = $this->Feedbackmodel->travellerFeedbackInfo($support_id);
        if(!$feedbackInfo){ redirect( $this->config->item( 'admin_url' ) . 'feedback' ); }
        if($feedbackInfo->is_read == 0){
           $updateData = array('support_id' => $support_id, 'is_read' => 1);
            $this->Feedbackmodel->updateReadTravellerFeedback($support_id, $updateData);
        }
        
        $data1[ 'feedbackInfo' ]  = $feedbackInfo;
        $content  = $this->load->view( 'feedback/traveller_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'View '.GUEST_NAME.' Feedback Info';
        $data[ 'header' ][ 'metakeyword' ]      = 'View '.GUEST_NAME.' Feedback Info';
        $data[ 'header' ][ 'metadescription' ]  = 'View '.GUEST_NAME.' Feedback Info';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'feedback">Manage Feedback</a></li>
                                              <li class="active">View '.GUEST_NAME.' Feedback Info</li>';
        $this->template( $data );
    }
    function guider_view() {
        $support_id   = $this->uri->segment(3);
        $feedbackInfo = $this->Feedbackmodel->guiderFeedbackInfo($support_id);
        if(!$feedbackInfo){ redirect( $this->config->item( 'admin_url' ) . 'feedback' ); }
        if($feedbackInfo->is_read == 0){
           $updateData = array('support_id' => $support_id,'is_read' => 1);
            $this->Feedbackmodel->updateReadGuiderFeedback($support_id, $updateData);
        }
        $data1[ 'feedbackInfo' ]  = $feedbackInfo;
        $content        = $this->load->view( 'feedback/guider_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'View '.HOST_NAME.' Feedback Info';
        $data[ 'header' ][ 'metakeyword' ]      = 'View '.HOST_NAME.' Feedback Info';
        $data[ 'header' ][ 'metadescription' ]  = 'View '.HOST_NAME.' Feedback Info';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'feedback">Manage Feedback</a></li>
                                              <li class="active">View '.HOST_NAME.' Feedback Info</li>';
        $this->template( $data );
    }
    function deleteGuiderFeedback() {
        $support_id  = $this->input->post( 'support_id' );
        echo $this->Feedbackmodel->deleteGuiderFeedbackInfo( $support_id );
    }
    function deleteTravellerFeedback() {
        $support_id  = $this->input->post( 'support_id' );
        echo $this->Feedbackmodel->deleteTravellerFeedbackInfo( $support_id );
    }
    // Venuepartner Feedback
    public function venuepartnerFeedbackTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $venuepartnerLists   = $this->Feedbackmodel->get_venuepartner_datatables($where,1);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($venuepartnerLists){
            foreach ($venuepartnerLists as $feedback) {
                $viewbtn    = '<a href="'.base_url().'feedback/venuepartner_view/'.$feedback->support_id.'" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search"></i></a>';
                // Add comment button
                $addbtn ='<a class="btn btn-primary btn-xs" onClick="return addFeedback('.$feedback->support_id.',0);" data-toggle="tooltip" title="Replay Feedback"><i class="fa fa-plus"></i></a>';
                $deletebtn  = '<a href="javascript:;" onclick="return deletevenuepartnerFeedback('.$feedback->support_id.');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a>';
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $feedback->venuepartner_name;
                $row[]  = $feedback->subject;
                $row[]  = $feedback->description;
                $row[]  = date('d M Y H:i A', strtotime($feedback->createdon));
                $row[]  = $viewbtn.$addbtn.$deletebtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Feedbackmodel->count_traveller_all($where,1),
                        "recordsFiltered"   => $this->Feedbackmodel->count_traveller_filtered($where,1),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function venuepartner_view() {
        $support_id     = $this->uri->segment(3);
        $venuepartnerInfo   = $this->Feedbackmodel->venuepartnerFeedbackInfo($support_id);
        if(!$venuepartnerInfo){ redirect( $this->config->item( 'admin_url' ) . 'feedback' ); }
        if($venuepartnerInfo->is_read == 0){
           $updateData = array('support_id' => $support_id, 'is_read' => 1);
            $this->Feedbackmodel->updateReadvenuepartnerFeedback($support_id, $updateData);
        }
        
        $data1[ 'venuepartnerInfo' ]  = $venuepartnerInfo;
        $content  = $this->load->view( 'feedback/venuepartner_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'View Venuepartner Feedback Info';
        $data[ 'header' ][ 'metakeyword' ]      = 'View Venuepartner Feedback Info';
        $data[ 'header' ][ 'metadescription' ]  = 'View Venuepartner Feedback Info';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'feedback">Manage Feedback</a></li>
                                              <li class="active">View Venuepartner Feedback Info</li>';
        $this->template( $data );
    }
        // Command Response
    public function commandFeedback() {

        $data['support_id']   = $this->input->post( 'support_id' );
        $data['field']= $this->input->post('field');
        $support_id = $this->input->post( 'support_id' );
        $data['responseInfo']  = $this->Feedbackmodel->venuepartnerFeedbackInfo($support_id);
        echo  $this->load->view( 'feedback/comment_partner', $data, true ); 
    }
    function updateFeedback(){
        $support_id        = $this->input->post( 'support_id' );
        $subject           = $this->input->post('subject');
        $description       = $this->input->post('description');
        $venuepartner_id   = $this->input->post('venuepartner_id');
        $responseInfo      = $this->Feedbackmodel->venuepartnerFeedbackInfo($support_id);
        if($responseInfo){
            $data   = array(
                'venuepartner_id' => $venuepartner_id,
                'subject'         => $subject,
                'is_read'         => 1,
                'description'     => $description,
                'feedback_status' => 2,
                'createdon'       => date("Y-m-d H:i:s")
                
            );
            $this->Feedbackmodel->addResponse($data); 
            $this->session->set_flashdata('successMSG', 'Feedback Response Update successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
        }
    }
    function deletevenuepartnerFeedback() {
        $support_id  = $this->input->post( 'support_id' );
        echo $this->Feedbackmodel->deletevenuepartnerFeedbackInfo( $support_id );
    }
    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}