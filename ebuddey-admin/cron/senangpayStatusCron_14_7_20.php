<?php
//include("connection.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

/*$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "root";
$dbname      = "buddeybnb";*/

$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "$2y$12$3IJeJ6";
$dbname      = "buddeydb_live_2019";
$conn = @mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

$secretkey      = '9700-518';
$merchant_id    = '700152830620181';
$today          = date('Y-m-d');
$current_datetime = date("Y-m-d H:i:s");
$timestr        = strtotime($current_datetime);
$time5          = $timestr - (7 * 60); //7 minutes
$time10         = $timestr - (14 * 60); //14 minutes
$update5_datetime = date("Y-m-d H:i:s", $time5);
$update10_datetime= date("Y-m-d H:i:s", $time10);
$service_date   = '';
$createdon      = date("Y-m-d H:i:s");
error_reporting(E_ALL);
// Status Query 1 CRON RUN FIRST TIME
$select1 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=0 AND pay_createdon < "'.$update5_datetime.'" AND pay_status=2';
$query1  = mysqli_query($conn, $select1);
while ($row1 = mysqli_fetch_array($query1)){
    $order_id       = $row1['order_id'];
    $payment_id     = $row1['payment_id'];
    $paymentAppType = $row1['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number = 1
                          WHERE payment_id = '.$payment_id;
            $query2    = mysqli_query($conn, $update1);
        }else{
            $payment_info = $response->data[0]->payment_info;
            if($payment_info){
                $transaction_id   = $payment_info->transaction_reference;
                $transaction_date = $payment_info->transaction_date;
                $pay_updated      = date('Y-m-d',$transaction_date);
                $payment_mode     = $payment_info->payment_mode;
                $status           = $payment_info->status;

                $guiderID    = $row1['guiderID'];
                $travellerID = $row1['travellerID'];
                $serviceID   = $row1['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=1, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query3     = mysqli_query($conn, $update2);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $bookedType = 'APP';
                        if($paymentAppType == 'web_space_booking'){ $bookedType = 'WEB'; }
                        $update33   = 'UPDATE events 
                                       SET host_id = "'.$guiderID.'", paidStatus = 1, status = 3, bookedType = "'.$bookedType.'", color = "#9c27b0", transactionID = "'.$transaction_id.'", paidDatetime = "'.$createdon.'", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'"';
                        $query33    = mysqli_query($conn, $update33);
                    }
                    //OLD PROCESS
                    if($serviceID){
                        /******UPDATE SERVICE PAYMENT COMPLETED********/
                        $update3    = 'UPDATE service_list 
                                       SET status = 4, transactionID = "'.$transaction_id.'"
                                       WHERE service_id = '.$serviceID;
                        $query4     = mysqli_query($conn, $update3);
                        /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                        $select2    = 'SELECT service_id, service_traveller_id, service_guider_id, service_date
                                       FROM service_list 
                                       WHERE service_id = '.$serviceID;
                        $query5     = mysqli_query($conn, $select2);
                        while ($row2 = mysqli_fetch_row($query5)){
                            $traveller_id = $row2[1];
                            $guider_id    = $row2[2];
                            $service_date = $row2[3];
                        }
                        if($today == $service_date){
                            $jny_status = 2; //ONGOING
                        }else{
                            $jny_status = 1; //UPCOMING
                        }
                        $insert1    = 'INSERT INTO journey_list (jny_traveller_id,jny_guider_id,jny_service_id,createdon,payment_status,jny_transactionID,jny_status) 
                                        VALUES 
                                        ("'.$traveller_id.'","'.$guider_id.'","'.$serviceID.'","'.$createdon.'","paid","'.$transaction_id.'","'.$jny_status.'")';
                        $query6     = mysqli_query($conn, $insert1);
                    }
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=1, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number = 1
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number = 1
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 2 CRON RUN SECOND TIME
$select21   = ' SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
                FROM senangpay_transaction 
                WHERE cron_number = 1 
                AND pay_createdon < "'.$update10_datetime.'" AND pay_status=2';
$query21    = mysqli_query($conn, $select21);
while ($row21 = mysqli_fetch_array($query21)){
    $order_id       = $row21['order_id'];
    $payment_id     = $row21['payment_id'];
    $paymentAppType = $row21['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL2        = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse2   = httpGet($getURL2);
    $response2      = json_decode($getResponse2);
    if($response2->status == 1){
        if(!$response2->data){ //Failed
            $update21   = 'UPDATE senangpay_transaction 
                           SET pay_status = 0, cron_number = 2, pay_updated = "'.$createdon.'"
                           WHERE payment_id ='.$payment_id.'';
            $query22    = mysqli_query($conn, $update21);
            //UPDATE SPACE BOOKING
            if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                $update55   = 'UPDATE events 
                               SET host_id = 0, paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                               WHERE orderID = "'.$order_id.'" AND status != 3';
                $query33    = mysqli_query($conn, $update55);
            }
        }else{
            $payment_info = $response2->data[0]->payment_info;
            if($payment_info){
                $transaction_id     = $payment_info->transaction_reference;
                $transaction_date   = $payment_info->transaction_date;
                $pay_updated        = date('Y-m-d',$transaction_date);
                $payment_mode       = $payment_info->payment_mode;
                $status             = $payment_info->status;

                $guiderID       = $row21['guiderID'];
                $travellerID    = $row21['travellerID'];
                $serviceID      = $row21['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update22   = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=2, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query23    = mysqli_query($conn, $update22);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $bookedType = 'APP';
                        if($paymentAppType == 'web_space_booking'){ $bookedType = 'WEB'; }
                        $update33   = 'UPDATE events 
                                       SET host_id = "'.$guiderID.'", paidStatus = 1, status = 3, bookedType = "'.$bookedType.'", color = "#9c27b0", transactionID = "'.$transaction_id.'", paidDatetime = "'.$createdon.'", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'"';
                        $query33    = mysqli_query($conn, $update33);
                    }
                    //OLD PROCESS
                    if($serviceID){
                        /******UPDATE SERVICE PAYMENT COMPLETED********/
                        $update23   = 'UPDATE service_list 
                                       SET status = 4, transactionID = "'. $transaction_id .'"
                                       WHERE service_id = '.$serviceID;
                        $query24    = mysqli_query($conn, $update23);
                        /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                        $select22   = 'SELECT service_id, service_traveller_id, service_guider_id, service_date
                                       FROM service_list 
                                       WHERE service_id = '.$serviceID;
                        $query25    = mysqli_query($conn, $select22);
                        while ($row22 = mysqli_fetch_row($query25)){
                            $traveller_id = $row22[1];
                            $guider_id    = $row22[2];
                            $service_date = $row22[3];
                        }
                        if($today == $service_date){
                            $jny_status = 2; //ONGOING
                        }else{
                            $jny_status = 1; //UPCOMING
                        }
                        $insert21   = 'INSERT INTO journey_list (jny_traveller_id,jny_guider_id,jny_service_id,createdon,payment_status,jny_transactionID,jny_status) 
                                        VALUES 
                                        ("'.$traveller_id.'","'.$guider_id.'","'.$serviceID.'","'.$createdon.'","paid","'.$transaction_id.'","'.$jny_status.'")';
                        $query26    = mysqli_query($conn, $insert21);
                    }
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update23   = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=2, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query27    = mysqli_query($conn, $update23);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update24   = 'UPDATE senangpay_transaction 
                                  SET pay_status = 0, cron_number = 2, pay_updated = "'.$createdon.'"
                                  WHERE payment_id = '.$payment_id;
                    $query28    = mysqli_query($conn, $update24);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }
            }else{
                //NOT START
                $update25   = 'UPDATE senangpay_transaction 
                              SET pay_status = 0, cron_number = 2, pay_updated = "'.$createdon.'"
                              WHERE payment_id = '.$payment_id;
                $query29    = mysqli_query($conn, $update25);
                //UPDATE SPACE BOOKING
                if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                    $update55   = 'UPDATE events 
                                   SET host_id = 0, paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                   WHERE orderID = "'.$order_id.'" AND status != 3';
                    $query33    = mysqli_query($conn, $update55);
                }
            }
        }
    }
}

function httpGet($url)
{
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch,CURLOPT_HEADER, false); 
    $output = curl_exec($ch);
    //$result = json_decode($response);
    //echo curl_errno($ch);
    //echo curl_error($ch);
    curl_close($ch);
    return $output;
}