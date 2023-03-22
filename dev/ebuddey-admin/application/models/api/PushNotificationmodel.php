<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class PushNotificationmodel extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }
    
    public function android_push_notification($target, $data, $type=false){

        //FCM api URL
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        if(strtoupper($type) == 'G'){
            $server_key = GUIDER_SERVER_KEY;
        }else{
            $server_key = TRAVELLER_SERVER_KEY;
        }
        $fields = [
                    'registration_ids' => $target,
                    'priority'         => "high",
                    'data'             => $data
                ];
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
          'Authorization:key='.$server_key
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        /*print_r('ANDR');
        print_r($result);*/
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //return $result;
    }
    public function android_push_notification_test($target, $data, $type=false){

        //FCM api URL
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        if($type == 'guider'){
            $server_key = GUIDER_SERVER_KEY;
        }else{
            $server_key = TRAVELLER_SERVER_KEY;
        }
        $fields = [
                    'registration_ids'  => $target,
                    'priority'          => "high",
                    'data'              => $data
                ];
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
          'Authorization:key='.$server_key
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        print_r($result);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    public function sendPushNotification_ios($target, $data, $type=false){

        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        if(strtoupper($type) == 'G'){
            $server_key = HOST_SERVER_KEY;
        }else{
            $server_key = GUEST_SERVER_KEY;
        }
        $fields_ios = [
                        'registration_ids'=> $target,
                        'priority'        => "high",
                        'notification'    => $data,
                        'data'            => $data
                      ];
        $headers = array(
            'Content-Type:application/json',
          'Authorization:key='.$server_key
        );
        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields_ios));
        $result = curl_exec($ch);
        /*print_r('IOS');
        print_r($result);*/
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
?>