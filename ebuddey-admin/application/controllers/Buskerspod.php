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
        $this->load->model( 'Guidermodel' );
        sessionset();
        error_reporting(E_ALL);
    }

    public function index() {

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
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        $tplData['skillsList']   = $this->skillsArr;
        $content    = $this->load->view( 'buskerspod/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod Tracker';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Tracker';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Tracker';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Tracker</li>';
        $this->template( $data );
    }
    public function buskerspodTableResponse(){

        $data       = array();
        $results    = array();
        $where      = array();
        $startDate  = $this->input->post( 'startDate' );
        $endDate    = $this->input->post( 'endDate' );
        $filterDate = $this->input->post( 'filterDate' ); 
        $podLists   = $this->Buskerspodmodel->get_datatables($where, $startDate, $endDate);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status    = '';
        $statusbtn = '';
        if($podLists){
            foreach ($podLists as $podInfo) {
                $revokebtn = '';
                if ($podInfo->status == 0) { //0-Unavailable
                    $status = '<div style="padding-left: 5px;background: #dd4b39;" class="un_available">Disabled</div>';
                    $statusbtn   = '<a href="javascript:;" data-toggle="tooltip" title="Activate" onClick="return updateBuskerspodStatus('.$podInfo->id.',1);" class="btn btn-warning btn-xs"><i class="fa fa-toggle-off"></i></a>';
                }elseif ($podInfo->status == 1) { //1-Available
                    $status     = '<div style="padding-left: 5px;background: #398439;" class="available">Available</div>';
                    $statusbtn  = '<a href="javascript:;" data-toggle="tooltip" title="Inactive" onClick="return updateBuskerspodStatus('.$podInfo->id.',0);" class="btn btn-success btn-xs"><i class="fa fa-toggle-on"></i></a>';
                }elseif($podInfo->status == 2){ //2-Progress
                    $status = '<div style="padding-left: 5px;background:#00c0ef;" class="available">Progress</div>';
                }elseif($podInfo->status == 3){ //3-Booked
                    $guiderInfo = $this->Guidermodel->guiderInfo($podInfo->host_id);
                    if($guiderInfo){
                        $status = '<div style="padding-left: 5px;background: #9c27b0;" class="booked">'.$guiderInfo->email.'</div>';
                    }else{
                    	$status = '';
                    }
                    $revokebtn  = '<a class="btn btn-warning btn-xs" title="Click to Revoke" href="javascript:;" onclick="return revokeEvent( '.$podInfo->id.' );"><i class="fa fa-repeat" aria-hidden="true"></i></a>';
                }elseif($podInfo->status == 4){ //4-Locked
                	$hostInfo = $this->Guidermodel->guiderInfo($podInfo->lockedBy);
                    if($hostInfo){
                        $status = '<div style="padding-left: 5px;background:#398439;" class="locked">Available</div>';
                    }else{
                    	$status = '';
                    }
                }

                $editbtn    = '<a href="javascript:;" onclick="return editEventFormModal('.$podInfo->id.');" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
                $deletebtn  = '<a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteBuskerspod( '.$podInfo->id.' );"><i class="glyphicon glyphicon-trash"></i></a>';
                $hostbtn    = '<a class="btn btn-success btn-xs" title="Add '.HOST_NAME.'" href="javascript:;" onclick="return addHostForm( '.$podInfo->id.' );"><i class="glyphicon glyphicon-plus"></i></a>';
                
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = rawurldecode($podInfo->partner_name);
                //$row[]  = $podInfo->address;
                $row[]  = $podInfo->cityName;
                $row[]  = date('d M Y', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->end));
                $row[]  = ((is_numeric($podInfo->partnerFees))? number_format($podInfo->partnerFees, 2) : '');
                $row[]  = $status;
                if ($podInfo->status == 1 || $podInfo->status == 0) {
                    $row[]  = $statusbtn.$hostbtn.$editbtn.$revokebtn.$deletebtn;
                }else{
                    $row[]  = $hostbtn.$editbtn.$revokebtn.$deletebtn;
                }
                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Buskerspodmodel->count_all($where, $startDate, $endDate),
                        "recordsFiltered"   => $this->Buskerspodmodel->count_filtered($where, $startDate, $endDate),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    function editBuskerspodValidate() {

        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $id  = $this->input->post( 'id' );
        $this->form_validation->set_rules( 'full_name', 'Full name', 'required' );
        $this->form_validation->set_rules( 'partner_id', 'Partner', 'required' );
        $this->form_validation->set_rules( 'status', 'Status', 'required' );
        $this->form_validation->set_rules( 'id', 'Pod ID', 'required' );
        
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
            $this->Buskerspodmodel->updateBuskerspod($id, $updateData);
            echo 1;
        }
    }

    function deleteBuskerspod() {
        $id = $this->input->post( 'id' );
        if($id){
            $data = array('status' => 5, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => $this->session->userdata( 'USER_ID' ));
            $this->Buskerspodmodel->updateBuskerspod( $id, $data );
        }
        return true;
    }
    function addEventFormModal(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        echo $this->load->view( 'buskerspod/add_event_form', $tplData, true );
    }
    function addEventForm() {
        $data       = array();
        $partner_id = $this->input->post( 'partner_id' );
        $split_time = $this->input->post( 'split_time' );
        $startDate  = date('Y-m-d', strtotime($this->input->post( 'startDate' )));
        $start_time = date('H:i:s', strtotime($this->input->post( 'startTime' )));
        $start_date = strtotime($startDate);
        $endDate    = date('Y-m-d', strtotime($this->input->post( 'endDate' )));
        $end_time   = date('H:i:s', strtotime($this->input->post( 'endTime' )));
        $end_date   = strtotime($endDate);
        $partnerInfo= $this->Partnermodel->partnerInfo( $partner_id );
        if($startDate == '1970-01-01' || $endDate == '1970-01-01'){
            $res[ 'success' ]  = 0;
            $res[ 'msg' ]  = 'Please select the valid date format.';
        }else{
            if($start_date && $partner_id){
                for ($i=$start_date; $i<=$end_date; $i+=86400) {
                    $cdata  = date("Y-m-d", $i);
                    if($split_time == 1){
                        $interval = date_interval_create_from_date_string('1 hour');
                        $begin    = date_create($start_time);
                        $end      = date_create($end_time)->add($interval);
                        foreach (new DatePeriod($begin, $interval, $end) as $dt) {
                            
                            $startTime = $dt->format('H:i:s');
                            $endTimeAr = date_create($startTime)->add($interval);
                            $endTime   = $endTimeAr->format('H:i:s');
                            if($end_time < $endTime){ continue; }
                            $start = $cdata.' '.$startTime;
                            $end   = $cdata.' '.$endTime;
                            if($this->Buskerspodmodel->checkExistPackageInfo($partner_id, $partnerInfo->city_id, $start, $end)){
                            	continue;
                            }
                            //echo $startTime .'=='.$endTime ."\n";
                            $data[] = array(
                                        'partner_id'=> $partner_id,
                                        'partnerFees'=> $this->input->post( 'partnerFees' ),
                                        'city_id'   => $partnerInfo->city_id,
                                        'start'     => $cdata.' '.$startTime,
                                        'end'       => $cdata.' '.$endTime,
                                        'space_uuid'=> gen_uuid(),
                                        'message'   => $this->input->post( 'message' ),
                                        'created_on'=> date('Y-m-d H:i:s'),
                                        'status'    => 1
                                    );
                        }
                    }else{
                        $data[] = array(
                                        'partner_id'=> $partner_id,
                                        'partnerFees'=> $this->input->post( 'partnerFees' ),
                                        'city_id'   => $partnerInfo->city_id,
                                        'start'     => $startDate.' '.$start_time,
                                        'end'       => $endDate.' '.$end_time,
                                        'space_uuid'=> gen_uuid(),
                                        'message'   => $this->input->post( 'message' ),
                                        'created_on'=> date('Y-m-d H:i:s'),
                                        'status'    => 1
                                    );
                    }
                }
                if($data){
                    $this->db->insert_batch('events', $data);
                    $res[ 'success' ]  = 1;
                    $res[ 'msg' ]  = 'Event Information added successfully.';
                }else{
                    $res[ 'success' ]  = 0;
                    $res[ 'msg' ]  = 'Some Problem found. Please try again.';
                }
            }else{
                $res[ 'success' ]  = 0;
                $res[ 'msg' ]  = 'Please select from and to date.';
            }
        }
        echo json_encode( $res );
    }
    function editEventFormModal(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $partnerFees = 0;
        $event_id   = $this->input->post( 'event_id' );
        $partner_id = $this->input->post( 'partner_id' );
        $eventInfo  = $this->Buskerspodmodel->buskerspodInfo($event_id);
        if(isset($eventInfo->partnerFees)){
            $partnerFees = $eventInfo->partnerFees;
        }else{
            $partnerInfo = $this->Partnermodel->partnerInfo( $eventInfo->partner_id );
            if($partnerInfo){
                $partnerFees = $partnerInfo->fees;
            }
        }
        $tplData['event_id']     = $event_id;
        $tplData['eventInfo']    = $eventInfo;
        $tplData['partnerFees']  = $partnerFees;
        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        echo $this->load->view( 'buskerspod/edit_event_form', $tplData, true );
    }
    function addHostForm(){
        $event_id   = $this->input->post( 'event_id' );
        $hostLists  = $this->Guidermodel->guider_lists( false, 1 );
        $buskerspodInfo = $this->Buskerspodmodel->buskerspodInfo($event_id);
        $str ='<form method="post" id="addhostform">
                <div class="row">
                <div class="form-group">
                <label for="host_id" class="col-sm-4 control-label">'.HOST_NAME.'</label>
                <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Select '.HOST_NAME.'" name="host_id" id="host_id">
                        <option value="0">Select</option>';
                        if( $hostLists ) { 
                            foreach ( $hostLists as $key => $value ) {
                                if($buskerspodInfo->host_id==$value->guider_id){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }
                              $str .='<option '.$selected.' value="'. intval($value->guider_id) .'">'. $value->first_name.' '.$value->last_name .' ('.$value->email .')</option>';
                            }
                        }
            $str .='</select>
                </div>
            </div>
            </div>
            <div class="row">
                <p></p>
                <div class="col-md-12">
                  <div class="pull-right">
                    <button class="btn btn-primary" id="addhosttbtn" value="Update '.HOST_NAME.'" onclick="return addHost('.$event_id.');">Update '.HOST_NAME.'</button>
                    <button type="button" id="defaultOpen" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
            </div>
            <script>
            $(document).ready(function(){
              $(".select2").select2();
            });
            </script>
            </form>';
            echo $str;
    }
    //OLD 
    function addEvent(){
        $data = array();
        $id  = $this->input->post( 'id' );
        $split_time = $this->input->post( 'split_time' );
        $start_date = date('Y-m-d', strtotime($this->input->post( 'start_datetime' )));
        $start_time = date('H:i:s', strtotime($this->input->post( 'start_datetime' )));
        $start_date = strtotime($start_date);
        $end_date   = date('Y-m-d', strtotime($this->input->post( 'end_datetime' )));
        $end_time   = date('H:i:s', strtotime($this->input->post( 'end_datetime' )));
        $end_date   = strtotime($end_date);
        if($start_date && $id){
            for ($i=$start_date; $i<=$end_date; $i+=86400) {
                $cdata  = date("Y-m-d", $i);
                $data[] = array(
                                'id'         => $id,
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
    function addHost(){
        $createdon = date("Y-m-d H:i:s");
        $id    = $this->input->post( 'event_id' );
        $host_id = $this->input->post( 'host_id' );
        if($id && $host_id){
            $order_id       = sprintf("TXN%s%05d", $host_id.date("YmdHis"), $id);
            $transactionID  = 'CMS'.str_replace(".","",microtime(true)).rand(000,999);
            $orderID        = 'CMS'.$order_id;
            $USER_ID        = $this->session->userdata( 'USER_ID' );
            $updateData = array( 
                            'host_id'       => $host_id,
                            "orderID"       => $orderID,
                            "transactionID" => $transactionID,
                            "bookedBy"      => $USER_ID,
                            "bookedType"    => 'CMS',
                            "paidDatetime"  => $createdon,
                            "paidStatus"    => 1,
                            "status"        => 3
                            );
            $this->Buskerspodmodel->updateBuskerspod($id, $updateData);
            echo 1;
        }else{
            echo 'Some problem found. Please try again.';
        }
    }
    function editEventForm() {
        $data       = array();
        $partner_id = $this->input->post( 'partner_id' );
        $event_id   = $this->input->post( 'event_id' );
        $split_time = $this->input->post( 'split_time' ); 
        $startDate  = date('Y-m-d', strtotime($this->input->post( 'startDate' )));
        $start_time = date('H:i:s', strtotime($this->input->post( 'startTime' )));
        $endDate    = date('Y-m-d', strtotime($this->input->post( 'endDate' )));
        $end_time   = date('H:i:s', strtotime($this->input->post( 'endTime' )));
        $partnerInfo= $this->Partnermodel->partnerInfo( $partner_id );
        if($startDate == '1970-01-01' || $endDate == '1970-01-01'){
            $res[ 'success' ]  = 0;
            $res[ 'msg' ]  = 'Please select the valid date format.';
        }else{
            if($startDate && $partner_id){
                $data = array(
                                'partner_id'=> $partner_id,
                                'partnerFees'=> $this->input->post( 'partnerFees' ),
                                'city_id'   => $partnerInfo->city_id,
                                'start'     => $startDate.' '.$start_time,
                                'end'       => $startDate.' '.$end_time,
                                'message'   => $this->input->post( 'message' )
                            );
                if($data){
                    $this->Buskerspodmodel->updateBuskerspod($event_id, $data);
                    $res[ 'success' ]  = 1;
                    $res[ 'msg' ]  = 'Event Information updated successfully.';
                }else{
                    $res[ 'success' ]  = 0;
                    $res[ 'msg' ]  = 'Some Problem found. Please try again.';
                }
            }else{
                $res[ 'success' ]  = 0;
                $res[ 'msg' ]  = 'Please select from and to date.';
            }
        }
        echo json_encode( $res );
    }
    function eventStatus(){
        $id    = $this->input->post( 'id' );
        $status= $this->input->post( 'status' );
        $data  = array('status' => $status);
        if($id){ $this->Buskerspodmodel->updateBuskerspod( $id, $data ); }
    }
    function deleteEvent() {
        $id   = $this->input->post( 'id' );
        $data = array('status' => 5);
        if($id){ $this->Buskerspodmodel->updateBuskerspod( $id, $data ); }
        return true;
    }
    public function getPartnerFees(){
        $partner_id  = $this->input->post( 'partner_id' );
        $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
        if($partnerInfo){
            echo $partnerInfo->fees;
        }else{ echo 0; }
    }
    //Status update blocked (18-03-2020)
    function updateBuskerspodStatus(){ 
        $id     = $this->input->post( 'id' );
        $status = $this->input->post( 'status' );
        $buskerspodInfo  = $this->Buskerspodmodel->buskerspodInfo($id);
        if($buskerspodInfo){
            $data   = array('status' => $status);
            $this->Buskerspodmodel->updateBuskerspod($id, $data);
            if($status==1){ $moduleAction = 'ACTIVE'; }else{ $moduleAction = 'DEACTIVE'; }
            $this->session->set_flashdata('successMSG', 'Buskers pod Status updated successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later'); 
        }
    }
    function revokeEvent() {
        $event_id = $this->input->post( 'id' );
        $eventInfo= $this->Buskerspodmodel->buskerspodInfo($event_id);
        if($eventInfo){
            //ADD REVOKE DATA
            $addData  = array(
                            'amount'        => $eventInfo->partnerFees,
                            'bookedType'    => $eventInfo->bookedType,
                            'orderID'       => $eventInfo->orderID,
                            'event_id'      => $event_id,
                            'created_by'    => $this->session->userdata( 'USER_ID' ),
                            'created_at'    => date("Y-m-d H:i:s")
                        );
            $this->Buskerspodmodel->addRevokeBooking( $addData );
            if($eventInfo->bookedType != 'CMS'){
                $paymentInfo = $this->Buskerspodmodel->senangpayPaymentInfo($eventInfo->orderID);
                if($paymentInfo){
                    $totalAmount  = $paymentInfo->transaction_amount - $eventInfo->partnerFees;

                    if($paymentInfo->packageID == $event_id){
                        $payData = array('pay_status' => 4);
                        $this->Buskerspodmodel->updateSenangpayPayment($payData, $eventInfo->orderID);
                    }else{
                        $packageIDList = explode(',', $paymentInfo->packageID);
                        $packageIDArr  = array();
                        if($packageIDList){
                            foreach ($packageIDList as $key => $value) {
                                if($value == $event_id){ continue; }
                                $packageIDArr[] = $value;
                            }
                        }
                        $packageIDs   = implode(",",$packageIDArr);
                        $payData = array(
                                        'transaction_amount' => $totalAmount,
                                        'sub_total' => $totalAmount,
                                        'packageID' => $packageIDs
                                    );
                        $this->Buskerspodmodel->updateSenangpayPayment($payData, $eventInfo->orderID);
                    }
                }
            }
            //UPDATE EVENT STATUS
            $data  = array(
                            'host_id'       => 0,
                            'orderID'       => '',
                            'transactionID' => '',
                            'bookedType'    => '',
                            'paidStatus'    => 0,
                            'lockedBy'      => 0,
                            'paidDatetime'  => NULL,
                            'lockedDateTime'=> NULL,
                            'status'        => 1,
                            'updated_at'    => date("Y-m-d H:i:s")
                        );
            $this->Buskerspodmodel->updateBuskerspod( $event_id, $data );
        }
        return true;
    }
    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}