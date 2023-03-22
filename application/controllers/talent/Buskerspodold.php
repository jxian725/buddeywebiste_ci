<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Buskerspod extends CI_Controller {  

    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    public function index() {
        error_reporting(0);
        //$this->session->unset_userdata('booking_packages');
        //$booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        //print_r($booking_packages);
        $partner_id  = $this->input->get('partner_id');
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData     = array();
        $date        = date('Y-m-d');
        
        $tplData [ 'cityLists' ]  = $this->Talentmodel->state_list( $country_id=132, 1 );
        $tplData ['partnerInfo']  = $this->Talentmodel->partnerInfo($partner_id);
        $tplData ['partner_List'] = $this->Commonmodel->partner_Lists();
        $tplData ['eventList']    = $this->Talentmodel->get_available_hrs_list($partner_id, $date);
        $tplData ['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData ['booking_packages'] = json_decode($this->session->userdata('booking_packages'), true);
        $content     = $this->load->view( 'talent/buskerspod/buskers_pod', $tplData, true );
        $data[ 'navigation' ]                   = 'buskerspod';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Buskers Pod</li>'; 
        $this->template( $data );
        return true;
    }
    public function get_events()
    {
         // Our Start and End Dates
         $start      = $this->input->get("start");
         $end        = $this->input->get("end");
         $partner_id = $this->input->get("partner_id");

         $startdt = new DateTime('now'); // setup a local datetime
         $startdt->setTimestamp($start); // Set the date based on timestamp
         $start_format = $startdt->format('Y-m-d H:i:s');

         $enddt = new DateTime('now'); // setup a local datetime
         $enddt->setTimestamp($end); // Set the date based on timestamp
         $end_format = $enddt->format('Y-m-d H:i:s');

         $events = $this->Talentmodel->get_events($partner_id, $start_format, $end_format);

         $data_events = array();

         foreach($events->result() as $r) {
            $partnerInfo = $this->Talentmodel->partnerInfo( $r->partner_id );
            if($partnerInfo){
                $partner_name = rawurldecode( $partnerInfo->partner_name );
            }else{
                $partner_name = '';
            }
            
            $start_date = date('Y-m-d', strtotime($r->start));
            $end_date   = date('Y-m-d', strtotime($r->end));
            $start_time = date('H:i', strtotime($r->start));
            $end_time   = date('H:i', strtotime($r->end));
            if($r->host_id){ 
                $guiderInfo = $this->Talentmodel->talentInfo($r->host_id);
                if($guiderInfo){
                    $hostName = ' - '.$guiderInfo->first_name;
                }else{ $hostName = ''; }
            }else{ 
                $hostName = '';
            }
            $data_events[] = array(
                 "id"        => $r->id,
                 "partner_id"=> $r->partner_id,
                 "host_id"   => $r->host_id,
                 "startDate" => $start_date,
                 "endDate"   => $end_date,
                 "startTime" => $start_time,
                 "endTime"   => $end_time,
                 "title"     => '',
                 "message"   => $r->message,
                 "start"     => $r->start,
                 "end"       => $r->end,
                 "color"     => 'purple'
             );
         }

         echo json_encode(array("events" => $data_events));
         exit();
    }
    function showAvailableTime(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        error_reporting(0);
        $str = '';
        $partner_id = $this->input->post('partner_id');
        $date       = $this->input->post('date');
        $hrs_list   = $this->Talentmodel->get_available_hrs_list($partner_id, $date);
        if($hrs_list){
            foreach ($hrs_list as $event) {
                if($event->status == 1){
                    $status = 'Available';
                    $color  = 'green';
                }else if($event->status == 2){ //Progress
                    $status = 'Unavailable';
                    $color  = 'red';
                }else if($event->status == 3){ //Booked
                    $status = 'Unavailable';
                    $color  = 'red';
                }else if($event->status == 4){ //Locked
                    $status = 'Available';
                    $color  = 'green';
                }else{
                    $status = '';
                    $color  = '';
                }
                $current_select   = 0;
                $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
                if($booking_packages){
                    if(count($booking_packages[$partner_id]) != 0){
                        $booking_date = date('Y-m-d', strtotime($event->start));
                        if(count($booking_packages[$partner_id][$booking_date]) != 0){
                            $package_id = $event->id;
                            if(count($booking_packages[$partner_id][$booking_date][$package_id]) != 0){
                                $current_select = 1;
                            }
                        }
                    }
                }
                if($current_select == 1){
                    $status = 'Selected';
                    $color  = 'orange';
                }
                $start  = date('H:i', strtotime($event->start));
                $end    = date('H:i', strtotime($event->end));
                $amount = ((is_numeric($event->partnerFees))? number_format($event->partnerFees, 2) : '');
        $str .= '
                <tr>
                  <td style="color: '. $color .';">'. $start .'</td>
                  <td style="color: '. $color .';">'. $end .'</td>
                  <td style="color: '. $color .';">'. $status .'</td>
                  <td style="color: '. $color .';">'. $amount .'</td>
                  <td>';
                if(($event->status == 1 || $event->status == 4) && $current_select == 0){
                    $str .= '<input type="checkbox" class="select_time" name="event_id[]" value="'. $event->id .'" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="'. $amount .'">';
                }else if($current_select == 1){
                    $str .= '<input type="checkbox" checked="" disabled class="select_time" name="event_id[]" value="'. $event->id .'" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="'. $amount .'">';
                }else{
                    $str .= '<input type="checkbox" disabled class="select_time" name="event_id[]" value="'. $event->id .'" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="'. $amount .'">';
                }
            $str .= '</td>
                </tr>';
            }
        }else{
          $str .= '<tr><td colspan="5"><center>Not Available</center></td></tr>';
        }
        echo $str;
    }

    function addToBooking(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $talent_id  = $this->session->userdata['TALENT_ID'];
        $partner_id = $this->input->post('partner_id');
        $eventids   = $this->input->post('eventids');


        $partnerInfo = $this->Talentmodel->partnerInfo($partner_id);
        if(!$partnerInfo){
            echo json_encode(array('status' => 'error','msg' => 'The partner not available.'));
            exit;
        }
        //CHECK SPACE BLACKLIST
        $this->db->select('talent_id,space_id');
        $this->db->where('talent_id', $talent_id );
        $this->db->where('space_id', $partner_id );
        $query = $this->db->get('space_blacklist');
        if($query->row()){
            echo json_encode(array('status' => 'error','msg' => 'The partner space not available.'));
            exit;
        }
        //END CHECK SPACE BLACKLIST
        //Validate uploaded all required verification
        if($partnerInfo->required_license){
            $licenseArr  = explode(',', $partnerInfo->required_license);
            foreach ($licenseArr as $license_id) {
                $licenseInfo = $this->Talentmodel->licenseInfo($license_id, 1);
                if(!$licenseInfo){ continue; }
                if(!$this->Talentmodel->talentLicenseInfo($talent_id, $license_id, 1)){
                    echo json_encode(array('status' => 'error','msg' => 'Please upload all required verification.'));
                    exit;
                }
            }
        }

        //CHECK
        $isLocked    = 0;
        $booked_time = '';
        if($eventids){
            foreach ($eventids as $key => $package_id) {
                $packageInfo = $this->Talentmodel->checkEventLocked($talent_id, $package_id);
                //print_r($packageInfo);
                if($packageInfo){
                    $convertedTime = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($packageInfo->lockedDateTime)));
                    $currentTime   = date('Y-m-d H:i:s');
                    if($currentTime > $convertedTime){ //above 5min
                        // $data    = array("lockedBy" => $talent_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                        // $data    = array("lockedBy" => $talent_id, "lockedDateTime" => NULL, 'status' => 4 );
                        // $update  = $this->Talentmodel->updatePackageInfo($data, $package_id);
                    }else{ //less than 5 min
                        $isLocked   = 1;
                        $start_time = date('H:i', strtotime($packageInfo->start));
                        $end_time   = date('H:i', strtotime($packageInfo->end));
                        $booked_time .= ' '.$start_time.' - '.$end_time.',';
                    }
                }
            }
        }
        if($isLocked == 1){
            echo json_encode(array('status' => 'error', 'msg' => 'The following time are already booked.'.$booked_time));
            exit;
        }
        $sessionData = array();
        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        if($booking_packages){ $sessionData = $booking_packages; }
        if($eventids){
            foreach ($eventids as $key => $package_id) {
                if($package_id){
                    $eventInfo  = $this->Talentmodel->packageInfo($package_id);
                    $partner_id = $eventInfo->partner_id;

                    $data    = array("lockedBy" => $talent_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                    $update  = $this->Talentmodel->updatePackageInfo($data, $package_id);
                    $startdt = date('Y-m-d', strtotime($eventInfo->start));
                    $sessionData[$partner_id][$startdt][$package_id] = array(
                                                                            "package_id"=> $eventInfo->id,
                                                                            "partner_id"=> $eventInfo->partner_id,
                                                                            "amount"    => $eventInfo->partnerFees,
                                                                            "startDate" => date('Y-m-d', strtotime($eventInfo->start)),
                                                                            "start"     => date('H:i', strtotime($eventInfo->start)),
                                                                            "end"       =>  date('H:i', strtotime($eventInfo->end))
                                                                        );
                    $booking_qty = $this->session->userdata('booking_qty');
                    $this->session->set_userdata('booking_qty', $booking_qty + 1);
                }
            }
            $this->session->set_userdata('booking_packages', json_encode($sessionData));
            echo json_encode(array('status' => 'success','msg' => 'Add to booking successfully.'));
            exit;
        }
    }

    public function review_booking() {

        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);

        $tplData     = array();
        $guider_id   = $this->session->userdata['TALENT_ID'];
        $tplData ['booking_packages'] = $booking_packages;
        $content     = $this->load->view( 'talent/buskerspod/review_booking', $tplData, true );
        $data[ 'navigation' ]                   = 'review_booking';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Review Booking';
        $data[ 'header' ][ 'metakeyword' ]      = 'Review Booking';
        $data[ 'header' ][ 'metadescription' ]  = 'Review Booking';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Buskers pod</li>'; 
        $this->template( $data );
        return true;
    }

    function removeToBooking(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $talent_id    = $this->session->userdata['TALENT_ID'];
        $partner_id = $this->input->post('partner_id');
        $booking_date = $this->input->post('booking_date');
        $package_id = $this->input->post('package_id');
        
        if($partner_id && $booking_date && $package_id){
            $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
            if($booking_packages){
                unset($booking_packages[$partner_id][$booking_date][$package_id]);
                
                if(count($booking_packages[$partner_id][$booking_date]) == 0){ unset($booking_packages[$partner_id][$booking_date]); }
                if(count($booking_packages[$partner_id]) == 0){ unset($booking_packages[$partner_id]); }
                $this->session->set_userdata('booking_packages', json_encode($booking_packages));
                $data    = array("lockedBy" => 0, "lockedDateTime" => NULL, 'status' => 1 );
                $update  = $this->Talentmodel->updateLockedPackageInfo($data, $package_id, $talent_id);

                $booking_qty = $this->session->userdata('booking_qty');
                $this->session->set_userdata('booking_qty', $booking_qty - 1);
            }
        }
    }

    function reviewBookingModal(){
        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        $tplData     = array();
        $guider_id   = $this->session->userdata['TALENT_ID'];
        $tplData ['booking_packages'] = $booking_packages;
        echo $this->load->view( 'talent/buskerspod/review_booking_modal', $tplData, true );
    }
    function checkCartIsEmpty(){
        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        if($booking_packages){
            echo 2;
        }else{
            $this->session->unset_userdata('booking_packages');
            $this->session->unset_userdata('booking_qty');
            $this->session->unset_userdata('booking_price');
            $this->session->set_flashdata('successMSG', 'Booking session expired.');
            echo 1;
        }
    }

    function getPartnerLists(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $str = '';
        $searchVal    = $this->input->post('search');
        $city_id      = $this->input->post('city_id');
        $partnerLists = $this->Commonmodel->partner_Lists(rawurlencode($searchVal), $city_id);
        if($partnerLists){
            foreach ($partnerLists as $key => $partner) {
                $str .= '<li class="list-group-item"><a href="javascript:;" onclick=\'return showPartnerInfo("'.$partner->partner_id.'")\' class="" value="'.$partner->partner_id.'">'.rawurldecode($partner->partner_name).'</a></li>';
            }
        }
        echo $str;
    }

    function checkSpaceBfPayment(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $talent_id   = $this->session->userdata['TALENT_ID'];
        //CHECK
        $isLocked    = 0;
        $booked_time = '';

        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        if($booking_packages){
            foreach ($booking_packages as $key => $packagedatelist) {
                $partner_id = $key;
                if($packagedatelist){
                    foreach ($packagedatelist as $key2 => $packagelist) {
                        foreach ($packagelist as $key3 => $package) {
                            $package_id = $key3;
                            $packageInfo = $this->Talentmodel->checkEventLocked($talent_id, $package_id);
                            if($packageInfo){
                                $isLocked   = 1;
                                $start_date = date('d-m-Y', strtotime($packageInfo->start));
                                $start_time = date('H:i', strtotime($packageInfo->start));
                                $end_time   = date('H:i', strtotime($packageInfo->end));
                                $booked_time .= ' '.$start_date.' ('.$start_time.' - '.$end_time.'),';
                            }
                        }
                    }
                }
            }
        }
        if($isLocked == 1){
            echo json_encode(array('status' => 'error', 'msg' => 'The following time are already booked.'.$booked_time));
        }else{
            echo json_encode(array('status' => 'success', 'msg' => 'Success'));
        }
        exit;
    }

    function paynow(){

        $guider_id      = $this->session->userdata['TALENT_ID'];
        $guiderInfo     = $this->Talentmodel->talentInfo($guider_id);
        $package_ids    = $this->input->post('package_id');
        $detail         = 'BuddeySenangpay_Space_'.$guiderInfo->first_name;
        $packageID2     = implode("",$package_ids);
        $order_id       = sprintf("TXN%s%05d", $guider_id.date("YmdHis"), $guider_id);
        $createdon      = date("Y-m-d H:i:s");
        
        $totalAmount    = 0;
        $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
        if($booking_packages){
            foreach ($booking_packages as $key => $packagedatelist) {
              $partner_id = $key;
              if($packagedatelist){
                foreach ($packagedatelist as $key2 => $packagelist) {
                  $booking_date  = $key2;
                  foreach ($packagelist as $key3 => $package) {
                    $totalAmount += $package['amount'];
                  }
                }
              }
            }
        }
        $packageIDs = implode(",",$package_ids);
        if($totalAmount > 0){
            $data       = array(
                                "order_id"          => $order_id,
                                "order_detail"      => $detail,
                                "guiderID"          => $guider_id,
                                "transaction_amount"=> $totalAmount,
                                "sub_total"         => $totalAmount,
                                "paymentAppType"    => 'web_space_booking',
                                "packageID"         => $packageIDs,
                                "pay_createdon"     => $createdon
                            );
            $insert   = $this->Talentmodel->InitiateSenangpay($data);
            $data2    = array("orderID" => $order_id, "host_id" => $guider_id, 'paidStatus' => 2, 'status' => 2 );
            $update   = $this->Talentmodel->updateSpaceBookingOrder($data2, $package_ids);

            $this->session->unset_userdata('booking_packages');
            $this->session->unset_userdata('booking_qty');
            $this->session->unset_userdata('booking_price');

            $payinfo['amount']         = $totalAmount;
            $payinfo['name']           = $guiderInfo->first_name;
            $payinfo['email']          = $guiderInfo->email;
            $payinfo['phone']          = $guiderInfo->phone_number;
            $payinfo['order_id']       = $order_id;
            $payinfo['detail']         = $detail;
            $payinfo['secretkey']      = SENANGPAY_SECRETKEY;
            $payinfo['merchant_id']    = SENANGPAY_MERCHANTID;

            $this->load->view( 'talent/buskerspod/submit_paymentform', $payinfo );
        }
    }
    function bookingtimeout(){

        $this->session->unset_userdata('booking_packages');
        $this->session->unset_userdata('booking_qty');
        $this->session->unset_userdata('booking_price');
        $this->session->set_flashdata('successMSG', 'Booking session expired.');
        //redirect( $_SERVER['HTTP_REFERER'] );
    }
    function forcebookingtimeout(){

        $this->session->unset_userdata('booking_packages');
        $this->session->unset_userdata('booking_qty');
        $this->session->unset_userdata('booking_price');
        $this->session->set_flashdata('successMSG', 'Booking session expired.');
        redirect( $_SERVER['HTTP_REFERER'] );
    }

    function checkSessionExpiry(){
        $response = 2;
        if($this->session->userdata( 'TOKEN' )){
            $this->db->select('guider_id,token');
            $this->db->where('guider_id', $this->session->userdata( 'TALENT_ID' ) );
            $query = $this->db->get('guider');
            $info  = $query->row();
            if($info){
                if($info->token != $this->session->userdata( 'TOKEN' )){
                    $response = 1;
                }
            }
        }
        echo $response;
    }
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   