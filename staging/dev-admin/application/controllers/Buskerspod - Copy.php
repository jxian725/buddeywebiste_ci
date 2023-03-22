<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buskerspod extends CI_Controller {
    private $genderArr = array('M' => 'M','F' => 'F');
    private $skillsArr = array('1'=>'Musician', '2'=>'Singer', '3'=>'Emcee', '4'=>'Clown', '5'=>'Magician', '6'=>'Statue', '7'=>'Performing arts', '8'=>'Drummer', '9'=>'Comedian');

    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model( 'Buskerspodmodel' );
        $this->load->model( 'Partnermodel' );
        $this->load->model('eventcalendar_model', 'em');
        sessionset();
        error_reporting(E_ALL);
    }

    public function index() {

        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        $tplData['skillsList']   = $this->skillsArr;
        $content    = $this->load->view( 'buskerspod/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskerspod Lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskerspod Lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskerspod Lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskerspod Lists</li>';
        $this->template( $data );
    }
    public function buskerspodTableResponse(){

        $data       = array();
        $results    = array();
        $where      = array();
        $podLists   = $this->Buskerspodmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        if($podLists){
            foreach ($podLists as $podInfo) {
                $editbtn = '<a href="'.base_url().'buskerspod/edit/'.$podInfo->pod_id.'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
                $deletebtn = '<a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteBuskerspod( '.$podInfo->pod_id.' );"><i class="glyphicon glyphicon-trash"></i></a>';
                if ($podInfo->status == 1) {
                    $status = 'Active';
                }elseif($podInfo->status == 2){
                    $status = 'Deactive';
                }elseif($podInfo->status == 3){
                    $status = '';
                }
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $podInfo->full_name;
                $row[]  = rawurldecode($podInfo->partner_name);
                $row[]  = $podInfo->cityName;
                $row[]  = $podInfo->skills;
                $row[]  = $status;
                $row[]  = $editbtn.$deletebtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Buskerspodmodel->count_all($where),
                        "recordsFiltered"   => $this->Buskerspodmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function add() {
        $tplData['genderLists']  = $this->genderArr;
        $tplData['skillsLists']  = $this->skillsArr;
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $content    = $this->load->view( 'buskerspod/add', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add Buskerspod';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add Buskerspod';
        $data[ 'header' ][ 'metadescription' ]  = 'Add Buskerspod';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'buskerspod">Manage Buskerspod</a></li>
                                              <li class="active">Add Buskerspod</li>';
        $this->template( $data );
    }

    function buskerspodValidate() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        
        $this->form_validation->set_rules( 'full_name', 'Full name', 'required' );
        $this->form_validation->set_rules( 'partner_id', 'Partner', 'required' );
        $this->form_validation->set_rules( 'status', 'Status', 'required' );
        
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            //INSERT
            $partner_id  = $this->input->post( 'partner_id' );
            $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
            $insertData[ 'full_name' ]      = $this->input->post( 'full_name' );
            $insertData[ 'contact_no' ]     = $this->input->post( 'contact_number' );
            $insertData[ 'other_name' ]     = $this->input->post( 'other_name' );
            $insertData[ 'email' ]          = $this->input->post( 'email' );
            $insertData[ 'identification' ] = $this->input->post( 'identification' );
            $insertData[ 'gender' ]         = $this->input->post( 'gender' );
            $insertData[ 'skills' ]         = $this->input->post( 'skills' );
            $insertData[ 'partner_id' ]     = $this->input->post( 'partner_id' );
            $insertData[ 'city_id' ]        = $partnerInfo->city_id;
            $insertData[ 'status' ]         = $this->input->post( 'status' );
            $insertData[ 'created_at' ]     = date("Y-m-d H:i:s");
            $this->Buskerspodmodel->addBuskerspod($insertData);
            echo 1;
        }
    }
    //EDIT
    function edit() {
        
        $pod_id    = $this->uri->segment(3);
        $buskerspodInfo   = $this->Buskerspodmodel->buskerspodInfo($pod_id);
        if(!$buskerspodInfo){ redirect( $this->config->item( 'admin_url' ) . 'buskerspod' ); }
        $tplData['genderLists']  = $this->genderArr;
        $tplData['skillsLists']  = $this->skillsArr;
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData['eventList']    = $this->Buskerspodmodel->event_list($pod_id);
        $tplData['buskerspodInfo']= $buskerspodInfo;
        $tplData['pod_id']= $pod_id;
        $content    = $this->load->view( 'buskerspod/edit', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit Buskerspod';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit Buskerspod';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit Buskerspod';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'buskerspod">Manage Buskerspod</a></li>
                                              <li class="active">Edit Buskerspod</li>';
        $this->template( $data );
    }
    function editBuskerspodValidate() {

        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $pod_id  = $this->input->post( 'pod_id' );
        $this->form_validation->set_rules( 'full_name', 'Full name', 'required' );
        $this->form_validation->set_rules( 'partner_id', 'Partner', 'required' );
        $this->form_validation->set_rules( 'status', 'Status', 'required' );
        $this->form_validation->set_rules( 'pod_id', 'Pod ID', 'required' );
        
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            $partner_id  = $this->input->post( 'partner_id' );
            $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
            $updateData[ 'full_name' ]      = $this->input->post( 'full_name' );
            $updateData[ 'contact_no' ]     = $this->input->post( 'contact_number' );
            $updateData[ 'other_name' ]     = $this->input->post( 'other_name' );
            $updateData[ 'email' ]          = $this->input->post( 'email' );
            $updateData[ 'identification' ] = $this->input->post( 'identification' );
            $updateData[ 'gender' ]         = $this->input->post( 'gender' );
            $updateData[ 'skills' ]         = $this->input->post( 'skills' );
            $updateData[ 'partner_id' ]     = $this->input->post( 'partner_id' );
            $updateData[ 'city_id' ]        = $partnerInfo->city_id;
            $updateData[ 'status' ]         = $this->input->post( 'status' );
            $updateData[ 'updated_at' ]     = date("Y-m-d H:i:s");
            $this->Buskerspodmodel->updateBuskerspod($pod_id, $updateData);
            echo 1;
        }
    }
    function deleteBuskerspod() {
        $pod_id = $this->input->post( 'pod_id' );
        if($pod_id){
            $this->Buskerspodmodel->deleteBuskerspod( $pod_id );
        }
        return true;
    }
    function addEventForm(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $pod_id = $this->input->post( 'pod_id' );
        $buskerspodInfo = $this->Buskerspodmodel->buskerspodInfo($pod_id);
        if($buskerspodInfo){
            $tplData['pod_id'] = $pod_id;
            echo $this->load->view( 'buskerspod/add_event_form', $tplData, true );
        }
        return true;
    }
    function addEvent(){
        $data = array();
        $pod_id  = $this->input->post( 'pod_id' );
        $start_date = date('Y-m-d', strtotime($this->input->post( 'start_datetime' )));
        $start_time = date('H:i:s', strtotime($this->input->post( 'start_datetime' )));
        $start_date = strtotime($start_date);
        $end_date   = date('Y-m-d', strtotime($this->input->post( 'end_datetime' )));
        $end_time   = date('H:i:s', strtotime($this->input->post( 'end_datetime' )));
        $end_date   = strtotime($end_date);
        if($start_date && $pod_id){
            for ($i=$start_date; $i<=$end_date; $i+=86400) {
                $cdata  = date("Y-m-d", $i);
                $data[] = array(
                                'pod_id'     => $pod_id,
                                'start_date' => $cdata.' '.$start_time,
                                'end_date'   => $cdata.' '.$end_time,
                                'message'    => $this->input->post( 'message' ),
                                'status'     => 1
                            );
            }
            if($data){
                $this->db->insert_batch('event', $data);
                echo 1;
            }else{ echo 'Some Problem found. Please try again'; }
        }else{ echo 'Please select from and to date'; }
    }
    function eventStatus(){
        $id    = $this->input->post( 'id' );
        $status= $this->input->post( 'status' );
        $data  = array('status' => $status);
        if($id){ $this->Buskerspodmodel->updateEvent( $id, $data ); }
    }
    function deleteEvent() {
        $id = $this->input->post( 'id' );
        if($id){
            $this->Buskerspodmodel->deleteEvent( $id );
        }
        return true;
    }
    public function calendar() {
        $tplData  = array();
        $pod_id   = $this->uri->segment(3);
        $tplData['pod_id']  = $pod_id;
        $content  = $this->load->view( 'calendar_event', $tplData, true );
        $data[ 'content' ] = $content;
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'BUSKERS POD MANAGEMENT LISTS';
        $data[ 'header' ][ 'metakeyword' ]      = 'BUSKERS POD MANAGEMENT LISTS';
        $data[ 'header' ][ 'metadescription' ]  = 'BUSKERS POD MANAGEMENT LISTS';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'breadcrumb' ]                   = '';
        $this->template( $data );
    }
    public function load_data() {
        $id     = $this->uri->segment(3);
        $events = $this->em->get_event_list($id);
        if($events !== NULL) {
            echo json_encode(array('success' => 1, 'result' => $events));
        } else {
            echo json_encode(array('success' => 0, 'error' => 'Event not found'));
        }
    }

    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}