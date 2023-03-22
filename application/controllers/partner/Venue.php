<?php
defined('BASEPATH') OR exit('No direct script access allowed');            

class Venue extends CI_Controller {  
    private $skillsArr = array('1'=>'Musician', '2'=>'Singer', '3'=>'Emcee', '4'=>'Clown', '5'=>'Magician', '6'=>'Statue', '7'=>'Performing arts', '8'=>'Drummer', '9'=>'Comedian');
    function __construct() {
        parent::__construct();
        $this->load->model( 'partner/Partnermodel' ); 
        $this->load->helper('partner');
        $this->load->helper('timezone');
        partner_sessionset();
    }
	public function index() {

        $venuepartnerId = $this->session->userdata['PARTNER_ID'];
        $data_db[ 'partnerInfo' ]  = $this->Partnermodel->profileInfo($venuepartnerId);
        $content     = $this->load->view( 'partner/common/profile', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Venue Partner Profile';
        $data[ 'header' ][ 'metakeyword' ]      = 'Partner Profile';
        $data[ 'header' ][ 'metadescription' ]  = 'Partner Profile';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Partner Profile</li>'; 
        $this->template( $data );
        return true;
	}
    //Buskerspod Tabel data
    public function buskerspod(){
        $start   = '';
        $end     = '';
        $filter  = $this->input->get( 'filter' );
        if($filter=='month'){
            $start = date("Y-m").'-01';
            $end   = date("Y-m-t", strtotime($start));
        }elseif($filter=='week'){
            $start = date("Y-m-d");
            $end   = date('Y-m-d', strtotime($start. ' + 6 days'));
        }elseif($filter=='custom'){
            $start = date("Y-m-d", strtotime($this->input->get( 'start' )));
            $end   = date("Y-m-d", strtotime($this->input->get( 'end' )));
        }
        $tplData['start']        = $start;
        $tplData['end']          = $end;
        $tplData['filter']       = $filter;
        $venuepartnerId  = $this->session->userdata['PARTNER_ID'];
        $partnerInfo = $this->Partnermodel->profileInfo($venuepartnerId);
        $partner_ID  = ($partnerInfo->partner_id);
        $partnerID   = explode(',',$partner_ID);
        $tplData['partnerList']  = $this->Partnermodel->partner_List($partnerID);
        $tplData[ 'stateList' ]  = $this->Partnermodel->state_list( $country_id=132 );
        $tplData['skillsList']   = $this->skillsArr;
        $content    = $this->load->view( 'partner/venue/buskerspod', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod Lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Lists</li>';
        $this->template( $data );
    }
    public function buskerspodTableResponse(){ 

        $data        = array();
        $results     = array();
        $where       = array();
        $today       = date('Y-m-d');
        $startDate   = $this->input->post( 'startDate' );
        $endDate     = $this->input->post( 'endDate' );
        $filterDate  = $this->input->post( 'filterDate' );
        $venuepartnerId  = $this->session->userdata['PARTNER_ID'];
        $partnerInfo = $this->Partnermodel->profileInfo($venuepartnerId);
        $partner_ID  = ($partnerInfo->partner_id);
        $partnerID   = explode(',',$partner_ID);
        $podLists    = $this->Partnermodel->get_datatables($partnerID, $where, $startDate, $endDate);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status = '';
        if($podLists){
            foreach ($podLists as $podInfo) {
                if ($podInfo->status == 0) {
                    $unLikebtn = '<button type="button" class="btn btn-danger btn-xs" title="Dislike" disabled="disabled"><i class="fa fa-thumbs-down"></i></button>';
                    $likebtn = '<button type="button" class="btn btn-success btn-xs" title="Like" disabled="disabled"><i class="fa fa-thumbs-up"></i></button>';
                    $commandbtn = '<button type="button"class="btn btn-primary btn-xs" title="Comment" disabled="disabled"><i class="fa fa-file-text"></i></button>';
                }
                if ($podInfo->status == 1) {
                    $unLikebtn = '<button type="button" class="btn btn-danger btn-xs" title="Dislike" disabled="disabled"><i class="fa fa-thumbs-down"></i></button>';
                    $likebtn = '<button type="button" class="btn btn-success btn-xs" title="Like" disabled="disabled"><i class="fa fa-thumbs-up"></i></button>';
                    $commandbtn = '<button type="button"class="btn btn-primary btn-xs" title="Comment" disabled="disabled"><i class="fa fa-file-text"></i></button>';
                }
                if ($podInfo->status == 2) {
                    $unLikebtn = '<button type="button" class="btn btn-danger btn-xs" title="Dislike" disabled="disabled"><i class="fa fa-thumbs-down"></i></button>';
                    $likebtn = '<button type="button" class="btn btn-success btn-xs" title="Like" disabled="disabled"><i class="fa fa-thumbs-up"></i></button>';
                    $commandbtn = '<button type="button"class="btn btn-primary btn-xs" title="Comment" disabled="disabled"><i class="fa fa-file-text"></i></button>';
                }
                if($podInfo->status == 3){
                     $date = date("Y-m-d", strtotime($podInfo->start));
                    if ($today < $date) {
                        $unLikebtn = '<button type="button" class="btn btn-danger btn-xs" title="Dislike" disabled="disabled"><i class="fa fa-thumbs-down"></i></button>';
                        $likebtn = '<button type="button" class="btn btn-success btn-xs" title="Like" disabled="disabled"><i class="fa fa-thumbs-up"></i></button>';
                        $commandbtn = '<button type="button"class="btn btn-primary btn-xs" title="Comment" disabled="disabled"><i class="fa fa-file-text"></i></button>';
                    }elseif($podInfo->is_dislike == 1 && $podInfo->review_status == 1){
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );"  disable="disable"><i class="fa fa-thumbs-up"></i></a>';
                        $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike" ><i class="fa fa-thumbs-down"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);"><i class="fa fa-file-text"></i></a>';
                    }elseif($podInfo->is_like == 1 && $podInfo->review_status == 1){    
                        $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike"  disable="disable"><i class="fa fa-thumbs-down"></i></a>';
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );"><i class="fa fa-thumbs-up"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);"><i class="fa fa-file-text"></i></a>';
                     }elseif($podInfo->is_like == 1 && $podInfo->review_status == 0){    
                        $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike"  disable="disable"><i class="fa fa-thumbs-down"></i></a>';
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );"><i class="fa fa-thumbs-up"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);" disable="disable"><i class="fa fa-file-text"></i></a>';
                    }elseif($podInfo->is_dislike == 1 && $podInfo->review_status == 0){
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );" disable="disable"><i class="fa fa-thumbs-up"></i></a>';
                        $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike" ><i class="fa fa-thumbs-down"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);" disable="disable"><i class="fa fa-file-text"></i></a>';        
                    }elseif ($podInfo->is_like == 0 && $podInfo->is_dislike == 0 && $podInfo->review_status == 1) {
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );" disable="disable"><i class="fa fa-thumbs-up"></i></a>';
                         $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike" disable="disable"><i class="fa fa-thumbs-down"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);"><i class="fa fa-file-text"></i></a>';
                    }elseif ($podInfo->is_like == 0 && $podInfo->is_dislike == 0 && $podInfo->review_status == 0) {
                        $likebtn = '<a class="btn btn-success btn-xs" title="Like" href="javascript:;" onclick="return likePartner( '.$podInfo->id.',1 );" disable="disable"><i class="fa fa-thumbs-up"></i></a>';
                         $unLikebtn = '<a href="javascript:;" onclick="return unLikePartner('.$podInfo->id.',1);" class="btn btn-danger btn-xs" title="Dislike" disable="disable"><i class="fa fa-thumbs-down"></i></a>';
                        $commandbtn = '<a class="btn btn-primary btn-xs" title="Comment" href="javascript:;" onclick="return commandPartner( '.$podInfo->id.',0);" disable="disable"><i class="fa fa-file-text"></i></a>';    
                    }    
                }
                if ($podInfo->status == 4) {
                    $unLikebtn = '<button type="button" class="btn btn-danger btn-xs" title="Dislike" disabled="disabled"><i class="fa fa-thumbs-down"></i></button>';
                    $likebtn = '<button type="button" class="btn btn-success btn-xs" title="Like" disabled="disabled"><i class="fa fa-thumbs-up"></i></button>';
                    $commandbtn = '<button type="button"class="btn btn-primary btn-xs" title="Comment" disabled="disabled"><i class="fa fa-file-text"></i></button>';
                }
                if ($podInfo->status == 0) {
                    $status = '<div style="padding-left: 5px;background: #d9534f" class="un_available">Disabled</div>';
                }elseif($podInfo->status == 1){
                     $status = '<div style="padding-left: 5px;background: #398439;" class="available">Available</div>';    
                }elseif($podInfo->status == 2){
                    $status = '<div style="padding-left: 5px;background:#398439;" class="progress">Available</div>';
                }elseif($podInfo->status == 3){
                    $guiderInfo = $this->Partnermodel->guiderInfo($podInfo->host_id);
                    if($guiderInfo){
                        $status = '<div style="padding-left: 5px;background: #9c27b0;" class="booked">'.$guiderInfo->first_name.'</div>';
                    }else{
                        $status = '';
                    }
                }elseif($podInfo->status == 4){
                    $hostInfo = $this->Partnermodel->guiderInfo($podInfo->lockedBy);
                    if($hostInfo){
                        $status = '<div style="padding-left: 5px;background:#398439;" class="locked">Available</div>';
                    }else{
                        $status = '';
                    }
                }
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = rawurldecode($podInfo->partner_name);
                $row[]  = $podInfo->cityName;
                $row[]  = date('d M Y', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->end));
                $row[]  = ((is_numeric($podInfo->partnerFees))? number_format($podInfo->partnerFees, 2) : '');
                $row[]  = $status;
                $row[]  = $likebtn.$unLikebtn.$commandbtn;

                $data[] = $row;
                $si_no++;
            }
        }

        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Partnermodel->count_all($partnerID, $where, $startDate, $endDate),
                        "recordsFiltered"   => $this->Partnermodel->count_filtered($partnerID, $where, $startDate, $endDate),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    // Command Response
    public function commandPartner() {

        $data['id']   = $this->input->post( 'id' );
        $data['field']= $this->input->post('field');
        $id = $this->input->post( 'id' );
        $data['buskerspodInfo']  = $this->Partnermodel->buskerspodInfo($id);
        echo  $this->load->view( 'partner/venue/command_partner', $data, true ); 
    }
    function updatePartner(){
        $ids     = $this->input->post( 'id' );
        $command = $this->input->post('command');
        $buskerspodInfo  = $this->Partnermodel->buskerspodInfo($ids);
        if($buskerspodInfo){
            $partner_id     = ($buskerspodInfo->partner_id);
            $event_id       = ($buskerspodInfo->id);
            $venuepartnerId = $this->session->userdata['PARTNER_ID'];
            if($this->Partnermodel->reviewInfo($ids)){
                $data   = array(
                    'partner_id'      => $partner_id,
                    'event_id'        => $event_id,
                    'command'         => $command,
                    'review_status'   => 1,
                    'command_date'    => date("Y-m-d H:i:s"),
                    'venuepartner_id' => $venuepartnerId
                );
            $this->Partnermodel->updateReviews($event_id,$data);
            }else{  
                $data   = array(
                    'partner_id'      => $partner_id,
                    'event_id'        => $event_id,
                    'command'         => $command,
                    'review_status'   => 1,
                    'command_date'    => date("Y-m-d H:i:s"),
                    'venuepartner_id' => $venuepartnerId
                );
            $this->Partnermodel->addReviews($data);
            } 
            $this->session->set_flashdata('successMSG', 'Command Update successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
        }
    }
    // END 
    // Like Response
    function updatePartnerLike(){
        $ids    = $this->input->post( 'id' );
        $like   = $this->input->post( 'status' );
        $buskerspodInfo  = $this->Partnermodel->buskerspodInfo($ids);
        if($buskerspodInfo){
            $partner_id    = ($buskerspodInfo->partner_id);
            $talent_id     = ($buskerspodInfo->host_id);
            $event_id      = ($buskerspodInfo->id);
            if($this->Partnermodel->reviewInfo($ids)){
                $reviewInfoInfo  =  $this->Partnermodel->reviewInfo($ids);
                $likes    = ($reviewInfoInfo->is_dislike);
                $dislike  = ($like - $likes);
                $data     = array(
                            'partner_id'  => $partner_id,
                            'talent_id'   => $talent_id,
                            'event_id'    => $event_id,
                            'is_like'     => $like,
                            'is_dislike'  => $is_dislike,
                            'like_date'   => date("Y-m-d H:i:s")
                          );
                $this->Partnermodel->updateReviews($event_id,$data);
            }else{  
                $data   = array(
                        'partner_id'  => $partner_id,
                        'talent_id'   => $talent_id,
                        'event_id'    => $event_id,
                        'is_like'     => $like,
                        'like_date'   => date("Y-m-d H:i:s")
                    );
                $this->Partnermodel->addReviews($data);
            } 
            $this->session->set_flashdata('successMSG', '<p class="btn btn-success btn-xs"><i class="fa fa-thumbs-up"></i></p> Like.');
            echo json_encode( array('status' => 1) );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later'); 
        }
    }
    // Un Like Response
    function updatePartnerUnlike(){ 
        $ids    = $this->input->post( 'id' );
        $unlike = $this->input->post( 'status' );
        $buskerspodInfo  = $this->Partnermodel->buskerspodInfo($ids);
        if($buskerspodInfo){
            $partner_id    = ($buskerspodInfo->partner_id);
            $talent_id     = ($buskerspodInfo->host_id);
            $event_id      = ($buskerspodInfo->id);
            if($this->Partnermodel->reviewInfo($ids)){
                $reviewInfoInfo  =  $this->Partnermodel->reviewInfo($ids);
                $likes    = ($reviewInfoInfo->is_like);
                $un_likes = ($reviewInfoInfo->is_dislike);
                $like     = ($unlike - $likes);
                $data     = array(
                                'partner_id'   => $partner_id,
                                'talent_id'    => $talent_id,
                                'event_id'     => $event_id,
                                'is_dislike'   => $unlike,
                                'is_like'      => $like,
                                'disLike_date' => date("Y-m-d H:i:s")
                            );
                $this->Partnermodel->updateReviews($event_id,$data); 
            }else{  
                $data  = array(
                            'partner_id'   => $partner_id,
                            'talent_id'    => $talent_id,
                            'event_id'     => $event_id,
                            'is_dislike'   => $unlike,
                            'disLike_date' => date("Y-m-d H:i:s")
                        );
                $this->Partnermodel->addReviews($data);
            } 
            $this->session->set_flashdata('successMSG', '<p class="btn btn-danger btn-xs"><i class="fa fa-thumbs-down"></i></p> Dislike .');
            echo json_encode( array('status' => 1) );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later'); 
        }
    }
    // Feedback
    function feedback(){ 
        $tplData  = array();
        $venuepartnerId = $this->session->userdata['PARTNER_ID'];
        $feedback_status = ('1,2');
        $status   = explode(',',$feedback_status);
        $venuepartnerInfo    = $this->Partnermodel->venuepartnerFeedbackInfo($venuepartnerId,$status);
        $tplData['venuepartnerInfo']   = $venuepartnerInfo;
        $content = $this->load->view( 'partner/feedback/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Feedback';
        $data[ 'header' ][ 'metakeyword' ]      = 'Feedback';
        $data[ 'header' ][ 'metadescription' ]  = 'Feedback';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Feedback</li>';
        $this->template( $data );
    }
    function addFeedback(){
        error_reporting(0);
        $this->form_validation->set_rules( 'subject', 'Partner Name', 'required|min_length[3]' );
        $this->form_validation->set_rules('description', 'Description', 'required');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $venuepartnerId = $this->session->userdata['PARTNER_ID'];    
            $data   = array(
                        'venuepartner_id'   => $venuepartnerId,
                        'subject'           => trim($this->input->post( 'subject' )),
                        'description'       => trim($this->input->post( 'description' )),
                        'is_read'           => 0,
                        'feedback_status'   => 1,
                        'createdon'         => date("Y-m-d H:i:s")
                    );
            $feedback_id  = $this->Partnermodel->addFeedback($data);
            if($feedback_id){
                echo 1;
                $this->session->set_flashdata('successMSG', 'Feedback Added successfully.');
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        }
    }
	function template( $data ){
        $this->load->view( 'partner/common/partner_content', $data );
        return true;
    }
}    
   