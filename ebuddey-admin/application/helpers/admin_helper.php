<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sessionset() {
    $CI = get_instance();
    if( $CI->session->userdata( 'USER_ID' ) ) {}
    else { redirect( $CI->config->item( 'admin_url' ) . 'login' ); }
    return true;
}
function adminaccess(){
    $CI = get_instance();
    $user_id    = $CI->session->userdata( 'USER_ID' );
    $userInfo   = $CI->Loginmodel->userInfo( $user_id );
    if( $userInfo->account_type != 1 ){ redirect( $CI->config->item( 'admin_url' ) ); }
}
//Login
function loginNow( $username, $pass ) {
    $CI     = get_instance();
    $query  = "SELECT user_id, username, password, user_email, account_type, status
              FROM user
              WHERE 
                ( user_email = ? OR username = ? )
             ";
    $data   = array( $username, $username );
    $result = $CI->db->query( $query, $data );
    $rows   = $result->num_rows();   
    if( $rows > 0 ) {
        $adminInfo   =  $result->row();
        $oldPass = $adminInfo->password;
        if( $adminInfo->status == 1 ) {
            //password_hash("demo10", PASSWORD_DEFAULT);
            $currentPass = $CI->encryption->decrypt($oldPass);
            if ($currentPass == trim($pass)) {
              $sessionVal = array( 
                    'USER_ID'       => $adminInfo->user_id,
                    'USER_NAME'     => $adminInfo->username,
                    'USER_ROLE_ID'  => $adminInfo->account_type,
                    'USER_EMAIL'    => $adminInfo->user_email,
                    'SHOW_NOTIFICATIONS' => 'TRUE' );
              $CI->session->set_userdata( $sessionVal );
              return 1;
            } else {
              $CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
              return '<p>Invalid credential details.</p>';
            }
        }else{
            $CI->session->set_flashdata('err_msg', '<p>Your account not Activated.</p>');
            return '<p>Your account not Activated.</p>';
        }
    }else{ 
        $CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
        return '<p>Invalid credential details.</p>'; 
    }       
}

function randomRequestID() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 7; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function gen_otp() {
    $alphabet = "012345678901234567890123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 5; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function gen_beacon_uuid() {
    return sprintf( '%04x-%04x-%04x-%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function gen_client_id() {
    return sprintf( '%02x%02x%02x%02x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function forgotpassword( $name, $password, $useremail, $type=false ) {
    $CI     = get_instance();
    
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
                            <span style="color:#fff;height: 50px;font-size: 20px;line-height: 50px;text-align: center;width: 230px;"><b>MIDASCOM</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;" width="330" align="left" valign="top" bgcolor="#f9f9f9"
                            class="mainbar">
                          <h2 style="text-align: center;">Forgot Password</h2>
                          <p class="custom-p">Hi, ' . $name . '</p><br>
                          <p class="custom-p">Your Password ' . $password . '</p>
                          <br>
                            Regards,<br>
                            Admin<br>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#3c8dbc" style="color:#fff; padding: 15px 0 0px 0;">
                            <p class="custom-p">2017 © midascom.com.my</p>
                        </td>
                    </tr>
                </table>
            </body>';
            
    $adminEmail = 'support@midascom.com.my';
    $to      = $useremail;
    $subject = 'Forgot Password';
    // Always set content-type when sending HTML email
    $headers  = "From: contactus@midascom.com.my\r\n";
    $headers .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    if(mail($to, $subject, $message, $headers)){
        
    }else{
        
    }
}
function geocode($lat, $long){
    // url encode the address
    $address = urlencode(isset( $address ) );
     
    // google map geocode api url
    //$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&key=AIzaSyBJpQy6CdeOaY6edAJcIl_Kmc56QxoyVeU";
    
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}
function getDateFormat(){
    $CI = get_instance();
    return 'jS F Y';
}
function getTimeFormat(){
    $CI = get_instance();
    return 'h:i:s A';
}
function bookingTicketFormat($service_id){
    return 'BT'.str_pad($service_id, 5, '0', STR_PAD_LEFT);
}
function compress_image($source_url, $destination_url, $quality) {
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg')
    $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
    $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
    $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}
function completed_booking_export($data = null){
    ob_start();
    $CI = get_instance();

    $CI->excel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");


    $main_title_style = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '31849b'),
            'size'  => 20,
        ));
    $smalltitle_style = array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        ),
        'alignment' => array(
                'wrap'       => true
        )
    );
    $exportArr    = $data;
    //INVENTORY LIST EXPORTS
    if($exportArr){
        $CI->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $totalStockIn = '';
        $s = 4;
        $title = 'Completed Booking List-'. date('d-m-Y');
        $CI->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $CI->excel->setActiveSheetIndex(0)->mergeCells('B2:L2');
        $CI->excel->getActiveSheet()->getCell('B2')->setValue($title);
        $CI->excel->getActiveSheet()->getCell('B3')->setValue('Date');
        $CI->excel->getActiveSheet()->getCell('C3')->setValue('Category Name');
        $CI->excel->getActiveSheet()->getCell('D3')->setValue('Host Name');
        $CI->excel->getActiveSheet()->getCell('E3')->setValue('Transaction ID');
        $CI->excel->getActiveSheet()->getCell('F3')->setValue('User ID');
        $CI->excel->getActiveSheet()->getCell('G3')->setValue('Guest Name');
        $CI->excel->getActiveSheet()->getCell('H3')->setValue('Guest Gender');
        $CI->excel->getActiveSheet()->getCell('I3')->setValue('Guest Email');
        $CI->excel->getActiveSheet()->getCell('J3')->setValue('ADDITIONAL INFORMATION');
        $CI->excel->getActiveSheet()->getCell('K3')->setValue('AMOUNT PAID');
        $CI->excel->getActiveSheet()->getCell('L3')->setValue('BUDDEY FEE');
        $CI->excel->getActiveSheet()->getCell('M3')->setValue('BALANCE');
        
        $CI->excel->getActiveSheet()->getStyle('B3:M3')->applyFromArray($smalltitle_style);
        
        $CI->excel->getActiveSheet()->getStyle('B2')->applyFromArray($main_title_style);
        
        $newUserID  = '';
        foreach ($exportArr['booking_lists'] as $lists) {
            $createdon       = date('d M Y', strtotime($lists->pay_createdon));
            $order_id        = $lists->order_id;
            $user_random_id  = $exportArr['user_random_id'];
            if($user_random_id){
                $newUserID = $user_random_id.substr($lists->order_id, -6);
            }
            if($lists->tgender == 1){
                $tgender = 'Male';
            }elseif ($lists->tgender == 2) {
                $tgender = 'Female';
            }else{
                $tgender = '';
            }
            $spec = [];
            if($lists->guiding_speciality){
                $array  = explode(',', $lists->guiding_speciality);
                foreach ($array as $item) {
                    $specInfo = $CI->Guidermodel->guiderSpecialityInfo($item);
                    if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                }
            }
            $CI->excel->getActiveSheet()->getCell('B'.$s)->setValue($createdon);
            $CI->excel->getActiveSheet()->getCell('C'.$s)->setValue(implode(',', $spec));
            $CI->excel->getActiveSheet()->getCell('D'.$s)->setValue($lists->guiderName);
            $CI->excel->getActiveSheet()->getCell('E'.$s)->setValue($order_id);
            $CI->excel->getActiveSheet()->getCell('F'.$s)->setValue($newUserID);
            $CI->excel->getActiveSheet()->getCell('G'.$s)->setValue(rawurldecode($lists->travellerName));
            $CI->excel->getActiveSheet()->getCell('H'.$s)->setValue($tgender);
            $CI->excel->getActiveSheet()->getCell('I'.$s)->setValue($lists->temail);
            $CI->excel->getActiveSheet()->getCell('J'.$s)->setValue($lists->additional_information);
            $CI->excel->getActiveSheet()->getCell('K'.$s)->setValue(number_format((float)$lists->sub_total, 2, '.', ''));
            $CI->excel->getActiveSheet()->getCell('L'.$s)->setValue(number_format((float)$lists->service_fees, 2, '.', ''));
            $CI->excel->getActiveSheet()->getCell('M'.$s)->setValue(number_format((float)$lists->paid_to_guider, 2, '.', ''));
            $s++;
        }
    }
    if($exportArr['booking_lists']){
        // Set active sheet index to the first sheet, so Excel opens CI as the first sheet
        $CI->excel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="completed_booking_export_'. date('dmY h_i_s ').'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        //$objWriter = PHPExcel_IOFactory::createWriter($CI->excel, 'Excel5');
        //$objWriter->save('php://output');
        $CI->excel->getWriter('Excel5');
        $CI->excel->save('php://output');
        exit;
    }
}
function qrscan_donate_export($data = null){
    ob_start();
    $CI = get_instance();

    $CI->excel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");


    $main_title_style = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => '31849b'),
            'size'  => 20,
        ));
    $smalltitle_style = array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        ),
        'alignment' => array(
                'wrap'       => true
        )
    );
    $exportArr    = $data;
    //INVENTORY LIST EXPORTS
    if($exportArr){
        $CI->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $CI->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $totalStockIn = '';
        $s = 4;
        $title = 'QR Scan Donate List-'. date('d-m-Y');
        $CI->excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $CI->excel->setActiveSheetIndex(0)->mergeCells('B2:K2');
        $CI->excel->getActiveSheet()->getCell('B2')->setValue($title);
        $CI->excel->getActiveSheet()->getCell('B3')->setValue('Date');
        $CI->excel->getActiveSheet()->getCell('C3')->setValue('Host Name');
        $CI->excel->getActiveSheet()->getCell('D3')->setValue('Transaction ID');
        $CI->excel->getActiveSheet()->getCell('E3')->setValue('User ID');
        $CI->excel->getActiveSheet()->getCell('F3')->setValue('Donor Name');
        $CI->excel->getActiveSheet()->getCell('G3')->setValue('Donor Phone');
        $CI->excel->getActiveSheet()->getCell('H3')->setValue('Donor Email');
        $CI->excel->getActiveSheet()->getCell('I3')->setValue('AMOUNT PAID');
        $CI->excel->getActiveSheet()->getCell('J3')->setValue('BUDDEY FEE');
        $CI->excel->getActiveSheet()->getCell('K3')->setValue('BALANCE');
        
        $CI->excel->getActiveSheet()->getStyle('B3:K3')->applyFromArray($smalltitle_style);
        
        $CI->excel->getActiveSheet()->getStyle('B2')->applyFromArray($main_title_style);
        
        $newUserID  = '';
        foreach ($exportArr['booking_lists'] as $lists) {
            $createdon       = date('d M Y', strtotime($lists->pay_createdon));
            $order_id        = $lists->order_id;
            $user_random_id  = $exportArr['user_random_id'];
            if($user_random_id){
                $newUserID = $user_random_id.substr($lists->order_id, -6);
            }
            if($lists->anonymous == 1){
                $fullName    = '';
                $email       = '';
                $phoneNumber = '';
            }else{
                $fullName    = $lists->fullName;
                $email       = $lists->email;
                $phoneNumber = $lists->phoneNumber;
            }
            
            $CI->excel->getActiveSheet()->getCell('B'.$s)->setValue($createdon);
            $CI->excel->getActiveSheet()->getCell('C'.$s)->setValue($lists->talentName);
            $CI->excel->getActiveSheet()->getCell('D'.$s)->setValue($order_id);
            $CI->excel->getActiveSheet()->getCell('E'.$s)->setValue($newUserID);
            $CI->excel->getActiveSheet()->getCell('F'.$s)->setValue($fullName);
            $CI->excel->getActiveSheet()->getCell('G'.$s)->setValue($email);
            $CI->excel->getActiveSheet()->getCell('H'.$s)->setValue($phoneNumber);
            $CI->excel->getActiveSheet()->getCell('I'.$s)->setValue(number_format((float)$lists->sub_total, 2, '.', ''));
            $CI->excel->getActiveSheet()->getCell('J'.$s)->setValue(number_format((float)$lists->service_fees, 2, '.', ''));
            $CI->excel->getActiveSheet()->getCell('K'.$s)->setValue(number_format((float)$lists->paid_to_guider, 2, '.', ''));
            $s++;
        }
    }
    if($exportArr['booking_lists']){
        // Set active sheet index to the first sheet, so Excel opens CI as the first sheet
        $CI->excel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="qrscan_donor_export_'. date('dmY h_i_s ').'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        //$objWriter = PHPExcel_IOFactory::createWriter($CI->excel, 'Excel5');
        //$objWriter->save('php://output');
        $CI->excel->getWriter('Excel5');
        $CI->excel->save('php://output');
        exit;
    }
}
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function hostServicesFees($price_type_id,$amount,$number_of_person,$processingFeesType,$processingFeesValue){
  if($processingFeesValue == ''){ $processingFeesValue = PROCESSING_FEES_VALUE; }
  $hostFees   = 0;
  if($price_type_id == 1){ //price per person
    $total   = $amount * $number_of_person;
    if($processingFeesType == 1){ //default
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 2) { //percentage
      $hostFees = ($total / 100) * $processingFeesValue;
    }elseif ($processingFeesType == 3) { //fixed
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 0) {
      $hostFees = $processingFeesValue;
    }
    return number_format((float)$hostFees, 2, '.', '');
  }elseif ($price_type_id == 2) { //price per booking
    $total   = $amount;
    if($processingFeesType == 1){ //default
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 2) { //percentage
      $hostFees = ($total / 100) * $processingFeesValue;
    }elseif ($processingFeesType == 3) { //fixed
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 0) {
      $hostFees = $processingFeesValue;
    }
    return number_format((float)$hostFees, 2, '.', '');
  }elseif ($price_type_id == 3) { //free
    return '';
  }
}