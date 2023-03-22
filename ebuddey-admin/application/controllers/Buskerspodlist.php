<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buskerspodlist extends CI_Controller {
 private $genderArr = array('M' => 'M','F' => 'F');
    private $skillsArr = array('1'=>'Musician', '2'=>'Singer', '3'=>'Emcee', '4'=>'Clown', '5'=>'Magician', '6'=>'Statue', '7'=>'Performing arts', '8'=>'Drummer', '9'=>'Comedian');
	public function __construct(){
		
        parent::__construct();
        $this->load->helper('url');
		$this->load->model( 'Partnermodel' );
        $this->load->model( 'Guidermodel' );
        sessionset();
        error_reporting(E_ALL);
        // Load Pagination library
		$this->load->library('pagination');

		// Load model
		$this->load->model('Main_model');
	}

	public function index(){
		$start   = '';
        $end     = '';
        $filter  = $this->input->get( 'filter' );
		$partnerid  = $this->input->get( 'partnerid' );
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
		$tplData['partnerid']       = $partnerid;
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        $tplData['skillsList']   = $this->skillsArr;
	
		       
		$tplData[ 'navigation' ]                   = '';
        $tplData[ 'Emessage' ]                     = '';
        $tplData[ 'Smessage' ]                     = '';
        $tplData[ 'header' ][ 'title' ]            = 'Buskers Pod Tracker';
        $tplData[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Tracker';
        $tplData[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Tracker';
        $tplData[ 'footer' ][ 'script' ]           = '';
		$tplData[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Tracker</li>';
		$content=$this->load->view('buskerspodlist/user_view', $tplData, true );
        $tplData[ 'content' ]                      = $content;
      
		 $this->template( $tplData );
	}

	public function loadRecord($rowno=0){

		// Row per page
		$rowperpage = 100;
		// Row position
		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
      	$startDate  = $this->input->post( 'startDate' );
        $endDate    = $this->input->post( 'endDate' );
        $filterDate = $this->input->post( 'filterDate' ); 
		$partnerid = $this->input->post( 'partnerid' ); 
      	// All records count
      	$allcount = $this->Main_model->getrecordCount($startDate,$endDate,$partnerid);
			
      	// Get  records
      	$users_record = $this->Main_model->getData($rowno,$rowperpage,$startDate,$endDate,$partnerid);
		
		$status    = '';
        $statusbtn = '';
		 $array    = array();
		 $i=0;
      	foreach($users_record as $podInfo) {
		 
		   $revokebtn = '';
                if ($podInfo['status'] == 0) { //0-Unavailable
                    $status = '<div style="padding-left: 5px;background: #dd4b39;" class="un_available">Disabled</div>';
                    $statusbtn   = '<a href="javascript:;" data-toggle="tooltip" title="Activate" onClick="return updateBuskerspodStatus('.$podInfo['id'].',1);" class="btn btn-warning btn-xs"><i class="fa fa-toggle-off"></i></a>';
                }elseif ($podInfo['status'] == 1) { //1-Available
                    $status     = '<div style="padding-left: 5px;background: #398439;" class="available">Available</div>';
                    $statusbtn  = '<a href="javascript:;" data-toggle="tooltip" title="Inactive" onClick="return updateBuskerspodStatus('.$podInfo['id'].',0);" class="btn btn-success btn-xs"><i class="fa fa-toggle-on"></i></a>';
                }elseif($podInfo['status'] == 2){ //2-Progress
                    $status = '<div style="padding-left: 5px;background:#00c0ef;" class="available">Progress</div>';
                }elseif($podInfo['status'] == 3){ //3-Booked
                    $guiderInfo = $this->Guidermodel->guiderInfo($podInfo['host_id']);
                    if($guiderInfo){
                        $status = '<div style="padding-left: 5px;background: #9c27b0;" class="booked">'.$guiderInfo->email.'</div>';
                    }else{
                    	$status = '';
                    }
                    $revokebtn  = '<a class="btn btn-warning btn-xs" title="Click to Revoke" href="javascript:;" onclick="return revokeEvent( '.$podInfo['id'].' );"><i class="fa fa-repeat" aria-hidden="true"></i></a>';
                }elseif($podInfo['status'] == 4){ //4-Locked
                	$hostInfo = $this->Guidermodel->guiderInfo($podInfo['lockedBy']);
                    if($hostInfo){
                        $status = '<div style="padding-left: 5px;background:#398439;" class="locked">Available</div>';
                    }else{
                    	$status = '';
                    }
                }

                $editbtn    = '<a href="javascript:;" onclick="return editEventFormModal('.$podInfo['id'].');" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
                $deletebtn  = '<a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteBuskerspod( '.$podInfo['id'].' );"><i class="glyphicon glyphicon-trash"></i></a>';
                $hostbtn    = '<a class="btn btn-success btn-xs" title="Add '.HOST_NAME.'" href="javascript:;" onclick="return addHostForm( '.$podInfo['id'].' );"><i class="glyphicon glyphicon-plus"></i></a>';
				
			
				$array[$i]['id'] = $podInfo['id'];
				$array[$i]['partner_name'] = rawurldecode($podInfo['partner_name']);
				$array[$i]['cityName'] =$podInfo['cityName'];
				
                $array[$i]['startdate']  = date('d M Y', strtotime($podInfo['start']));
                $array[$i]['start']  = date('H:i', strtotime($podInfo['start']));
                $array[$i]['end'] = date('H:i', strtotime($podInfo['end']));
                $array[$i]['partnerFees'] = ((is_numeric($podInfo['partnerFees']))? number_format($podInfo['partnerFees'], 2) : '');
				if ($podInfo['status'] == 1 || $podInfo['status'] == 0) {
                    $array[$i]['action']  = $statusbtn.$hostbtn.$editbtn.$revokebtn.$deletebtn;
                }else{
                    $array[$i]['action']  = $hostbtn.$editbtn.$revokebtn.$deletebtn;
                }
				$array[$i]['status']=$status;
				$i++;
		}

      	// Pagination Configuration
      	$config['base_url'] = base_url().'index.php/buskerspodlist/loadRecord';
      	$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		// Initialize
		$this->pagination->initialize($config);

		// Initialize $data Array
		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $array;
		$data['row'] = $rowno;
		
		echo json_encode($data);
		
	}
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }

}