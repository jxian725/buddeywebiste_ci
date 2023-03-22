<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Inbox extends CI_Controller {  

    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->library('phpqrcode/qrlib');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    // Feedback
    function index(){

		$talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData['imageLists']   = $this->Talentmodel->imageLists($talent_id);
        $tplData['urlLists']     = $this->Talentmodel->urlLists($talent_id);
        $tplData['talentInfo']   = $this->Talentmodel->talentInfo($talent_id);
        $tplData['reviewList']   = $this->Talentmodel->talentReviewLists(1, $talent_id);
        $tplData['totalLike']    = $this->Talentmodel->ratingInfo($talent_id);
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'comments_list' ] = $this->Talentmodel->donation_lists($talent_id, 20);
        $tplData['socialLinkInfo']  = $this->Talentmodel->talentSocialLinkInfo($talent_id);
		$tplData['inboxinfo']  = $this->Talentmodel->talentInboxinfo($talent_id);
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $content     = $this->load->view( 'talent/inbox/index', $tplData, true );
        $data[ 'navigation' ]                   = 'Inbox';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Inbox';
        $data[ 'header' ][ 'metakeyword' ]      = 'Inbox';
        $data[ 'header' ][ 'metadescription' ]  = 'Inbox';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Inbox</li>'; 
        $this->template( $data );
    }
	function loadMessage(){
		
		$talent_id   = $this->session->userdata['TALENT_ID'];
		$tplData['imageLists']   = $this->Talentmodel->imageLists($talent_id);
        $tplData['urlLists']     = $this->Talentmodel->urlLists($talent_id);
        $tplData['talentInfo']   = $this->Talentmodel->talentInfo($talent_id);
        $tplData['reviewList']   = $this->Talentmodel->talentReviewLists(1, $talent_id);
        $tplData['totalLike']    = $this->Talentmodel->ratingInfo($talent_id);
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'comments_list' ] = $this->Talentmodel->donation_lists($talent_id, 20);
        $tplData['socialLinkInfo']  = $this->Talentmodel->talentSocialLinkInfo($talent_id);
		$msgid  = $this->input->post( 'msgid' );
		$datearr  = $this->input->post( 'datearr' );
		
		$tplData['datearr']=$datearr;
		$tplData['inboxinfo']  = $this->Talentmodel->loadMoreMessage($msgid,$talent_id);
		$this->load->view('talent/inbox/load-more-data', $tplData);
		//print_r($result);
		//return $result;
		
	}
    function addMessage(){
       
            $talent_id = $this->session->userdata['TALENT_ID'];
            $data   = array(
                        'talent_id'      => $talent_id,
                        'message'           => trim($this->input->post( 'message' )),
                        'istalent_message'      => 1,
                        'is_admin_message'      => 0,
                        'istalent_readstatus'   => 0,
                        'isadmin_readstatus'=> 1,
						'istalent_delete'   => 0,
                        'is_admin_delete'   => 0,
                        'created_at'         => date("Y-m-d H:i:s"),
						'updated_at'         => date("Y-m-d H:i:s")
                    );
            $result  = $this->Talentmodel->addMessage($data);
            if($result){             
                echo 1;
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        
    }
	function deleteMessage(){
		$msgid  = $this->input->post( 'msgid' );
		$data       = array();
        $data['istalent_delete'] = 1;
		
		$result  = $this->Talentmodel->deleteMessage($msgid,$data);
		if($result){             
			echo 1;
		}else{
			echo 'Some error has occurred. Please try again later.';
		}
	}
	function updatereadStatus()
	{
		$guider_id  =$this->session->userdata['TALENT_ID'];
		$data['istalent_readstatus'] = 0;
		$this->Talentmodel->updatetalentreadStatus( $guider_id, $data );
	}
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   