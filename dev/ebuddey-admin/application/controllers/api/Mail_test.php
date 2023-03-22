<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_test extends CI_Controller{

    private $error = array();

    function __construct(){
        
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/MailNotificationmodel');
        header("content-type:application/json");
    }
    
    public function index() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if ($input != '') {
            $user_input = get_object_vars($input);
            if (($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validate($user_input)) {
                $toemail    = trim( $user_input['email'] );
                $aaa        = $this->MailNotificationmodel->requestToGuider($toemail);
                print_r($aaa);
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