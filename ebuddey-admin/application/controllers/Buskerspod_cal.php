<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buskerspod_cal extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model( 'Buskerspodmodel' );
        $this->load->model( 'Partnermodel' );
        $this->load->model( 'Guidermodel' );
        $this->load->model('Calendar_model');
        sessionset();
        error_reporting(E_ALL);
    }

    public function index() {

        $tplData['partnerList']  = $this->Partnermodel->partnerList();
        $tplData[ 'hostLists' ]  = $this->Guidermodel->guider_lists( false, 1 );
        $tplData[ 'stateList' ]  = $this->Commonmodel->state_list( $country_id=132 );
        $content    = $this->load->view( 'buskerspod/buskerspod_cal', $tplData, true );
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
    /*Get all Events */

    public function get_events()
    {
         // Our Start and End Dates
         $start = $this->input->get("start");
         $end = $this->input->get("end");

         $startdt = new DateTime('now'); // setup a local datetime
         $startdt->setTimestamp($start); // Set the date based on timestamp
         $start_format = $startdt->format('Y-m-d H:i:s');

         $enddt = new DateTime('now'); // setup a local datetime
         $enddt->setTimestamp($end); // Set the date based on timestamp
         $end_format = $enddt->format('Y-m-d H:i:s');

         $events = $this->Calendar_model->get_events($start_format, $end_format);

         $data_events = array();

         foreach($events->result() as $r) {
            $partnerInfo = $this->Partnermodel->partnerInfo( $r->partner_id );
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
                $guiderInfo = $this->Guidermodel->guiderInfo($r->host_id);
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
                 "title"     => $partner_name.$hostName,
                 "message"   => $r->message,
                 "startDate" => $start_date,
                 "endDate"   => $end_date,
                 "startTime" => $start_time,
                 "endTime"   => $end_time,
                 "end"       => $r->end,
                 "start"     => $r->start
             );
         }

         echo json_encode(array("events" => $data_events));
         exit();
    }
    /*Add new event */
    Public function addEvent()
    {
        $data = array();
        $partner_id = $this->input->post( 'partner_id' );
        $host_id    = $this->input->post( 'host_id' );
        $start_date = date('Y-m-d', strtotime($this->input->post( 'startDate' )));
        $start_time = date('H:i:s', strtotime($this->input->post( 'startTime' )));
        $start_date = strtotime($start_date);
        $end_date   = date('Y-m-d', strtotime($this->input->post( 'endDate' )));
        $end_time   = date('H:i:s', strtotime($this->input->post( 'endTime' )));
        $end_date   = strtotime($end_date);
        if($start_date && $partner_id){
            for ($i=$start_date; $i<=$end_date; $i+=86400) {
                $cdata  = date("Y-m-d", $i);
                $data[] = array(
                                'partner_id'=> $partner_id,
                                'host_id'   => $host_id,
                                'start'     => $cdata.' '.$start_time,
                                'end'       => $cdata.' '.$end_time,
                                'message'   => $this->input->post( 'message' ),
                                'status'    => 1
                            );
            }
            if($data){
                $this->db->insert_batch('events', $data);
                echo 1;
            }else{ echo 'Some Problem found. Please try again'; }
        }else{ echo 'Please select from and to date'; }
        /*$result=$this->Calendar_model->addEvent();
        echo $result;*/
        redirect(site_url("buskerspod_cal"));
    }
    public function updateEvent()
    {
        $eventid = intval($this->input->post("eventid"));

        $event = $this->Calendar_model->get_event($eventid);
        if($event->num_rows() == 0) {
            echo"Invalid Event";
            exit();
        }
        /*$delete = intval($this->input->post("delete"));
        if(!$delete) {*/
            $partner_id = $this->input->post( 'partner_id' );
            $host_id    = $this->input->post( 'host_id' );
            $start_date = date('Y-m-d', strtotime($this->input->post( 'startDate' )));
            $start_time = date('H:i:s', strtotime($this->input->post( 'startTime' )));
            $end_date   = date('Y-m-d', strtotime($this->input->post( 'endDate' )));
            $end_time   = date('H:i:s', strtotime($this->input->post( 'endTime' )));
            if($start_date && $partner_id){
                    $cdata  = date("Y-m-d", $i);
                    $data = array(
                                    'partner_id'=> $partner_id,
                                    'host_id'   => $host_id,
                                    'start'     => $start_date.' '.$start_time,
                                    'end'       => $start_date.' '.$end_time,
                                    'message'   => $this->input->post( 'message' )
                                );
                    $this->Calendar_model->update_event($eventid, $data);
            }
        /*}else{
            $this->Calendar_model->delete_event($eventid);
        }*/
        redirect(site_url("buskerspod_cal"));
    }
    //DELETE EVENT
    function deleteEvent() {
        $id = $this->input->post( 'eventID' );
        $this->db->where( 'id', $id );
        $this->db->delete( 'events' );
        echo 1;
    }
    function currentDateEvent(){
        $startDate  = $this->input->post( 'startDate' );
        $tplData['eventLists']  = $this->Calendar_model->currentDateEventLists($startDate);
        echo $this->load->view( 'buskerspod/current_date_event', $tplData, true );
    }

    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}