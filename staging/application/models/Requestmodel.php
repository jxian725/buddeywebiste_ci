<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requestmodel extends CI_Model {
    public function __construct(){
        parent::__construct();
    }
    public function travellerPhoneExists( $phone_number ){
        
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('traveller');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row;
        }else{
            return false; 
        }
    }
    public function travellerInfoByUuid( $traveller_id ){
        $this->db->select( 'traveller.*,country_short_code,country_name,country_currency_code,country_currency_symbol,country_time_zone' );
        $this->db->from( 'traveller' );
        $this->db->join( 'countries', 'countries.phonecode = traveller.countryCode', 'left' );
        $this->db->where('traveller_id', $traveller_id);
        $this->db->where( 'traveller.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function guiderInfoById( $guider_id ){
        $this->db->select( 'guider.*,country_short_code,country_name,country_currency_code,country_currency_symbol,country_time_zone' );
        $this->db->from( 'guider' );
        $this->db->join( 'countries', 'countries.phonecode = guider.countryCode', 'left' );
        $this->db->where('guider_id', $guider_id);
        $this->db->where( 'guider.status !=', 4 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function guiderActivityInfo( $activity_id ){
        $this->db->select( 'guider_activity_list.*,first_name,phone_number,email' );
        $this->db->from( 'guider_activity_list' );
        $this->db->join( 'guider', 'guider.guider_id = guider_activity_list.activity_guider_id', 'left' );
        $this->db->where('activity_id', $activity_id);
        $this->db->where( 'activity_status', 1 );
        $query = $this->db->get();
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function guiderDeviceTokenList($guider_id){
        
        $this->db->select('*');
        $this->db->from('guider_device_info');
        $this->db->where( 'guider_id', $guider_id );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ){
            $result = $query->result();
            return $result;
        }
    }
    //Insert Request Service
    function insertService( $data ) { 
        $table = $this->db->dbprefix( 'service_list' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
    function insertRequest( $data ) { 
        $table = $this->db->dbprefix( 'activity_request' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        return $id;
    }
    public function insertTraveller( $data ){
        $table = $this->db->dbprefix( 'traveller' );
        $this->db->insert( $table, $data );
        $id = $this->db->insert_id();
        $this->updateTravellerId($id);
        return $id;
    }
    public function updateTravellerId($traveller_id){
        
        $t_id   = 'T-'.str_pad($traveller_id, 5, '0', STR_PAD_LEFT);
        $data   = array( 't_id' => $t_id );
        $table  = $this->db->dbprefix('traveller');
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
    }
    public function updateTravellerByUuid( $data, $traveller_id ){
        $table  = $this->db->dbprefix( 'traveller' );
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
        return true; 
    }

    
    public function android_push_notification($target, $data, $type=false){

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
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
    function requestToGuider( $useremail, $toname=false, $fromname=false ) {
        $message    = '<style type="text/css">
                        .custom-p {
                          line-height: 0.5em !important;
                          margin: 0px 0px 10px 0px;
                        }
                    </style>
                    <body style="margin: 0; padding: 0;">
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="750" style="border-collapse: collapse;">
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="padding: 40px 0 30px 0;">
                                <span style="color:#fff;height: 50px;font-size: 20px;line-height: 50px;text-align: center;width: 230px;"><b>BUDDEY</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;" width="330" align="left" valign="top" bgcolor="#f9f9f9"
                                class="mainbar">
                                <p class="custom-p">Hi '.$toname.',</p><br>
                                <h2 style="text-align: center;">You have a new booking request.</h2>
                                <p>Further information please check your Buddey Application for information.</p>
                                <p>Here are the download links for Buddey www.buddeyapp.com </p>
                                <p>Buddey Guest-Look up a hobby to join https://play.google.com/store/apps/details?id=com.buddeyapp.buddey</p>
                                <p>Buddey Host-List your hobby or services https://play.google.com/store/apps/details?id=com.buddeyapp.guider</p>
                                <br>
                                    Hope this informs,<br>
                                    Admin<br>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="color:#fff; padding: 15px 0 0px 0;">
                                <p class="custom-p">2017 © ebuddey</p>
                            </td>
                        </tr>
                    </table>
                </body>';
                
        $adminEmail = 'support@buddey.com.my';
        $to         = $useremail;
        $subject    = 'New Booking Request';
        // Always set content-type when sending HTML email
        $headers    = "From: admin@buddey.com.my\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $message, $headers);
    }
    function registerTraveller( $toEmail, $message ) {
                
        $adminEmail = 'support@buddeyapp.com';
        $subject    = 'Welcome to Buddey';
        // Always set content-type when sending HTML email
        $headers    = "From: Buddey Admin <admin@buddeyapp.com>\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($toEmail, $subject, $message, $headers);
    }
}