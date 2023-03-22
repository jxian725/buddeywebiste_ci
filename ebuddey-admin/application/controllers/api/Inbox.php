<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Inboxapimodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
	public function addmessageadmin() {
        //error_reporting(E_ALL);
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
                $message   = trim( $user_input['message'] );
               
                    $uuid   = gen_uuid();
                    $data   = array( 
                        'talent_id'      => $guiderid,
                        'message'           => trim($message),
                        'istalent_message'      => 0,
                        'is_admin_message'      => 1,
                        'istalent_readstatus'   => 1,
                        'isadmin_readstatus'=> 0,
						'istalent_delete'   => 0,
                        'is_admin_delete'   => 0,
                        'created_at'         => date("Y-m-d H:i:s"),
						'updated_at'         => date("Y-m-d H:i:s")
                         );
                    $guider_id      = $this->Inboxapimodel->insertAdminMessage( $data );
                    
                    $res_msg        = 'Message has been sent';
                
               $result = array('message' =>$res_msg, 'result' => 'success');
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
	public function addmessagetalent() {
        //error_reporting(E_ALL);
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
                $message   = trim( $user_input['message'] );
               
                    $uuid   = gen_uuid();
                    $data   = array( 
                        'talent_id'      => $guiderid,
                        'message'           => trim($message),
                        'istalent_message'      => 1,
                        'is_admin_message'      => 0,
                        'istalent_readstatus'   => 0,
                        'isadmin_readstatus'=> 1,
						'istalent_delete'   => 0,
                        'is_admin_delete'   => 0,
                        'created_at'         => date("Y-m-d H:i:s"),
						'updated_at'         => date("Y-m-d H:i:s")
                         );
                    $guider_id      = $this->Inboxapimodel->insertTalentMessage( $data );
                    
                    $res_msg        = 'Message has been sent';
                
               $result = array('message' =>$res_msg, 'result' => 'success');
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
	public function getmessageadmin() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
				$pageno          = trim( $user_input['pagenumber'] );
				$result  = $this->Inboxapimodel->admin_talentInboxinfo($guiderid,$pageno);
				
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
	public function getmessagetalent() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
				$pageno          = trim( $user_input['pagenumber'] );
				$result  = $this->Inboxapimodel->talent_talentInboxinfo($guiderid,$pageno);
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
	public function deletemessageadmin() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $msgid          = trim( $user_input['msgid'] );
				$data       = array();
				$data['is_admin_delete'] = 1;
				$results  = $this->Inboxapimodel->admin_deleteMessage($msgid,$data);
				if($results)
				{
				$result = array('message' => 'Message Deleted Successfully', 'result' => 'success');
				}
				
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
	public function deletemessagetalent() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $msgid          = trim( $user_input['msgid'] );
				$data       = array();
				$data['istalent_delete'] = 1;
				$results  = $this->Inboxapimodel->talent_deleteMessage($msgid,$data);
				if($results)
				{
				$result = array('message' => 'Message Deleted Successfully', 'result' => 'success');
				}
				
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
	public function unreadmsgcountadmin() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
				
				$results  = $this->Inboxapimodel->adminInboxReadinfo($guiderid);
				if($results)
				{
					$result = array('unreadmessagecount' => $results, 'result' => 'success');
				}
				else
				{
					$result = array('unreadmessagecount' => 0, 'result' => 'success');
				}
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
	public function unreadmsgcounttalent() {
		$input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
			
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $user_input  ) {
                $guiderid          = trim( $user_input['guiderid'] );
				
				$results  = $this->Inboxapimodel->talentInboxReadinfo($guiderid);
				if($results)
				{
					$result = array('unreadmessagecount' => $results, 'result' => 'success');
				}
				else
				{
					$result = array('unreadmessagecount' => 0, 'result' => 'success');
				}
			}
			else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
		
		echo json_encode($result);
	}
}
?>	
	