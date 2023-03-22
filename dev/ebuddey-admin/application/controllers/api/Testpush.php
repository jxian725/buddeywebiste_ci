<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testpush extends CI_Controller{

    private $error = array();

    function __construct(){
        
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/pushNotificationmodel');
        header("content-type:application/json");
    }
    
    public function index() {
        
        //TEST
        //$device_token   = 'djtZWiHw_Fk:APA91bG71Nu1W2rbpLCLTKJ0aULI_YsnR9levlvkA3kZC1Al5GLpO60WPxLMeD6DKuTzFTWYLFAB0kgD90SWTAFTRm2E-QjsYkME5uauab0SSpIhAlStoUR3i3oyrgoQyPIlKE0mcwD-';
        $device_token   = trim('dBg-YSynpyI:APA91bGrxxKFpWTN_EQ6IC5au-l05UB2fQfmVwJSyJ8R6R8rrEXxKNxQTZYPpfDkVXmPdZRgdJKJyi0mHH2AgmWfAJn0AXzkY-kyP5K7Z-HADuVDn3c-4zcg0YBjZAf6dzV4i-8SEJd1vhjiuW3PCq-uhtu7gTuudw');
        $message        = "This is test message.";
        $push_data      = array(
                                'title'     => 'EBuddy Test',
                                'message'   => $message
                                );
        if (strlen($device_token) > 10){
            $device_token = array($device_token);
            $aaa= $this->pushNotificationmodel->android_push_notification_test($device_token, $push_data, 'traveller');
        }
        $result   = array('message' => 'Test response.', 'result' => 'success', 'data' => '');
               
        echo json_encode($result);
    }

    private function validate($user_input) {
        return !$this->error;
    }
}
?> 