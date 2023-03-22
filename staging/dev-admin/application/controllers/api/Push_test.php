<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Push_test extends CI_Controller{

    private $error = array();

    function __construct(){
        
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    
    public function index() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if ($input != '') {
            $user_input = get_object_vars($input);
            if (($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validate($user_input)) {
                $device_id      = trim( $user_input['device_id'] );
                $message        = trim( $user_input['message'] );
                $type           = '';
                //TEST
                $push_data      = array(
                                    'title'          => 'Host',
                                    'body'           => $message,
                                    'action'         => 'complete_payment',
                                    'notificationId' => 4,
                                    'sound'          => 'notification',
                                    'icon'           => 'icon'
                                    );
                if($type == 'G'){
                    $device_tokenA = array($device_id);
                    if (!empty($device_tokenA)) {
                        $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'G');
                        echo 'success.';
                    }
                }else{
                    $device_tokenA = array($device_id);
                    if (!empty($device_tokenA)) {
                        $this->pushNotificationmodel->sendPushNotification_ios($device_tokenA, $message);
                        echo 'success.';
                    }
                }
                $result   = array('message' => 'Test response.', 'result' => 'success', 'data' => '');
            }else{
                
            }   
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }

    private function validate($user_input) {
        return !$this->error;
    }
}
?> 