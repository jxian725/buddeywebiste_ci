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
$time1          = $timestr - (2 * 60); //2 minutes
$time2          = $timestr - (4 * 60); //4 minutes
$time3          = $timestr - (6 * 60); //6 minutes
$time4          = $timestr - (8 * 60); //8 minutes
$time5          = $timestr - (10 * 60); //10 minutes
$time6          = $timestr - (12 * 60); //12 minutes
$time7          = $timestr - (14 * 60); //14 minutes
$time8          = $timestr - (16 * 60); //16 minutes
$time9          = $timestr - (18 * 60); //18 minutes
$time10         = $timestr - (20 * 60); //20 minutes
$time11         = $timestr - (22 * 60); //22 minutes
$time12         = $timestr - (24 * 60); //24 minutes
$time13         = $timestr - (26 * 60); //26 minutes
$time14         = $timestr - (28 * 60); //28 minutes
$time15         = $timestr - (30 * 60); //30 minutes
$cron_datetime_1 = date("Y-m-d H:i:s", $time1);
$cron_datetime_2 = date("Y-m-d H:i:s", $time2);
$cron_datetime_3 = date("Y-m-d H:i:s", $time3);
$cron_datetime_4 = date("Y-m-d H:i:s", $time4);
$cron_datetime_5 = date("Y-m-d H:i:s", $time5);
$cron_datetime_6 = date("Y-m-d H:i:s", $time6);
$cron_datetime_7 = date("Y-m-d H:i:s", $time7);
$cron_datetime_8 = date("Y-m-d H:i:s", $time8);
$cron_datetime_9 = date("Y-m-d H:i:s", $time9);
$cron_datetime_10 = date("Y-m-d H:i:s", $time10);
$cron_datetime_11 = date("Y-m-d H:i:s", $time11);
$cron_datetime_12 = date("Y-m-d H:i:s", $time12);
$cron_datetime_13 = date("Y-m-d H:i:s", $time13);
$cron_datetime_14 = date("Y-m-d H:i:s", $time14);
$cron_datetime_15 = date("Y-m-d H:i:s", $time15);
$service_date   = '';
$createdon      = date("Y-m-d H:i:s");
error_reporting(E_ALL);
// Status Query 1 CRON RUN FIRST TIME
$select1 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=0 AND pay_createdon < "'.$cron_datetime_1.'" AND pay_status=2';
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=1, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
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

// Status Query 2 CRON RUN FIRST TIME
$select2 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=1 AND pay_createdon < "'.$cron_datetime_2.'" AND pay_status=2';
$query2  = mysqli_query($conn, $select2);
while ($row2 = mysqli_fetch_array($query2)){
    $order_id       = $row2['order_id'];
    $payment_id     = $row2['payment_id'];
    $paymentAppType = $row2['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=2
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

                $guiderID    = $row2['guiderID'];
                $travellerID = $row2['travellerID'];
                $serviceID   = $row2['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=2, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=2, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=2
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=2
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 3 CRON RUN FIRST TIME
$select3 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=2 AND pay_createdon < "'.$cron_datetime_3.'" AND pay_status=2';
$query3  = mysqli_query($conn, $select3);
while ($row3 = mysqli_fetch_array($query3)){
    $order_id       = $row3['order_id'];
    $payment_id     = $row3['payment_id'];
    $paymentAppType = $row3['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=3
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

                $guiderID    = $row3['guiderID'];
                $travellerID = $row3['travellerID'];
                $serviceID   = $row3['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=3, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                                   SET pay_status=0, cron_number=3, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=3
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=3
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 4 CRON RUN
$select4 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=3 AND pay_createdon < "'.$cron_datetime_4.'" AND pay_status=2';
$query4  = mysqli_query($conn, $select4);
while ($row4 = mysqli_fetch_array($query4)){
    $order_id       = $row4['order_id'];
    $payment_id     = $row4['payment_id'];
    $paymentAppType = $row4['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=4
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

                $guiderID    = $row4['guiderID'];
                $travellerID = $row4['travellerID'];
                $serviceID   = $row4['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=4, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=4, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=4
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=4
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 5 CRON RUN
$select5 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=4 AND pay_createdon < "'.$cron_datetime_5.'" AND pay_status=2';
$query5  = mysqli_query($conn, $select5);
while ($row5 = mysqli_fetch_array($query5)){
    $order_id       = $row5['order_id'];
    $payment_id     = $row5['payment_id'];
    $paymentAppType = $row5['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=5
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

                $guiderID    = $row5['guiderID'];
                $travellerID = $row5['travellerID'];
                $serviceID   = $row5['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=5, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=5, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=5
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=5
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 6 CRON RUN
$select6 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=5 AND pay_createdon < "'.$cron_datetime_6.'" AND pay_status=2';
$query6  = mysqli_query($conn, $select6);
while ($row6 = mysqli_fetch_array($query6)){
    $order_id       = $row6['order_id'];
    $payment_id     = $row6['payment_id'];
    $paymentAppType = $row6['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=6
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

                $guiderID    = $row6['guiderID'];
                $travellerID = $row6['travellerID'];
                $serviceID   = $row6['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=6, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=6, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=6
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=6
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 7 CRON RUN
$select7 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=6 AND pay_createdon < "'.$cron_datetime_7.'" AND pay_status=2';
$query7  = mysqli_query($conn, $select7);
while ($row7 = mysqli_fetch_array($query7)){
    $order_id       = $row7['order_id'];
    $payment_id     = $row7['payment_id'];
    $paymentAppType = $row7['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=7
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

                $guiderID    = $row7['guiderID'];
                $travellerID = $row7['travellerID'];
                $serviceID   = $row7['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=7, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=7, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=7
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=7
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 8 CRON RUN
$select8 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=7 AND pay_createdon < "'.$cron_datetime_8.'" AND pay_status=2';
$query8  = mysqli_query($conn, $select8);
while ($row8 = mysqli_fetch_array($query8)){
    $order_id       = $row8['order_id'];
    $payment_id     = $row8['payment_id'];
    $paymentAppType = $row8['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=8
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

                $guiderID    = $row8['guiderID'];
                $travellerID = $row8['travellerID'];
                $serviceID   = $row8['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=8, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=8, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=8
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=8
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 9 CRON RUN
$select9 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=8 AND pay_createdon < "'.$cron_datetime_9.'" AND pay_status=2';
$query9  = mysqli_query($conn, $select9);
while ($row9 = mysqli_fetch_array($query9)){
    $order_id       = $row9['order_id'];
    $payment_id     = $row9['payment_id'];
    $paymentAppType = $row9['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=9
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

                $guiderID    = $row9['guiderID'];
                $travellerID = $row9['travellerID'];
                $serviceID   = $row9['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=9, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=9, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=9
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=9
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 10 CRON RUN
$select10 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=9 AND pay_createdon < "'.$cron_datetime_10.'" AND pay_status=2';
$query10  = mysqli_query($conn, $select10);
while ($row10 = mysqli_fetch_array($query10)){
    $order_id       = $row10['order_id'];
    $payment_id     = $row10['payment_id'];
    $paymentAppType = $row10['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=10
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

                $guiderID    = $row10['guiderID'];
                $travellerID = $row10['travellerID'];
                $serviceID   = $row10['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=10, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=10, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=10
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=10
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 11 CRON RUN
$select11 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=10 AND pay_createdon < "'.$cron_datetime_11.'" AND pay_status=2';
$query11  = mysqli_query($conn, $select11);
while ($row11 = mysqli_fetch_array($query11)){
    $order_id       = $row11['order_id'];
    $payment_id     = $row11['payment_id'];
    $paymentAppType = $row11['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=11
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

                $guiderID    = $row11['guiderID'];
                $travellerID = $row11['travellerID'];
                $serviceID   = $row11['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=11, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=11, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=11
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=11
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 12 CRON RUN
$select12 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=11 AND pay_createdon < "'.$cron_datetime_12.'" AND pay_status=2';
$query12  = mysqli_query($conn, $select12);
while ($row12 = mysqli_fetch_array($query12)){
    $order_id       = $row12['order_id'];
    $payment_id     = $row12['payment_id'];
    $paymentAppType = $row12['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=12
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

                $guiderID    = $row12['guiderID'];
                $travellerID = $row12['travellerID'];
                $serviceID   = $row12['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=12, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=12, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=12
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=12
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 13 CRON RUN
$select13 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=12 AND pay_createdon < "'.$cron_datetime_13.'" AND pay_status=2';
$query13  = mysqli_query($conn, $select13);
while ($row13 = mysqli_fetch_array($query13)){
    $order_id       = $row13['order_id'];
    $payment_id     = $row13['payment_id'];
    $paymentAppType = $row13['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=13
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

                $guiderID    = $row13['guiderID'];
                $travellerID = $row13['travellerID'];
                $serviceID   = $row13['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=13, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=13, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=13
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=13
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}

// Status Query 14 CRON RUN
$select14 = 'SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
           FROM senangpay_transaction 
           WHERE cron_number=13 AND pay_createdon < "'.$cron_datetime_14.'" AND pay_status=2';
$query14  = mysqli_query($conn, $select14);
while ($row14 = mysqli_fetch_array($query14)){
    $order_id       = $row14['order_id'];
    $payment_id     = $row14['payment_id'];
    $paymentAppType = $row14['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse    = httpGet($getURL);
    $response       = json_decode($getResponse);
    if($response->status == 1){
        if(!$response->data){
            $update1   = 'UPDATE senangpay_transaction 
                          SET cron_number=14
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

                $guiderID    = $row14['guiderID'];
                $travellerID = $row14['travellerID'];
                $serviceID   = $row14['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update2    = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=14, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                    
                }elseif($status == 'failed'){
                    /******UPDATE SENANG FAILED********/
                    $update3    = 'UPDATE senangpay_transaction 
                                   SET pay_status=0, cron_number=14, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query7     = mysqli_query($conn, $update3);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update4   = 'UPDATE senangpay_transaction 
                                  SET cron_number=14
                                  WHERE payment_id = '.$payment_id;
                    $query8    = mysqli_query($conn, $update4);
                }
            }else{
                //NOT START
                $update5    = 'UPDATE senangpay_transaction 
                              SET cron_number=14
                              WHERE payment_id = '.$payment_id;
                $query9     = mysqli_query($conn, $update5);
            }
        }
    }
}


// Status Query 15 CRON RUN LAST TIME
$select15   = ' SELECT payment_id, order_id, serviceID, guiderID, travellerID, pay_createdon, pay_updated, requestID, paymentAppType, pay_status 
                FROM senangpay_transaction 
                WHERE cron_number = 14
                AND pay_createdon < "'.$cron_datetime_15.'" AND pay_status=2';
$query15    = mysqli_query($conn, $select15);
while ($row15 = mysqli_fetch_array($query15)){
    $order_id       = $row15['order_id'];
    $payment_id     = $row15['payment_id'];
    $paymentAppType = $row15['paymentAppType'];
    $hashed         = md5($merchant_id.$secretkey.$order_id);
    $getURL2        = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
    $getResponse2   = httpGet($getURL2);
    $response2      = json_decode($getResponse2);
    if($response2->status == 1){
        if(!$response2->data){ //Failed
            $update21   = 'UPDATE senangpay_transaction 
                           SET pay_status = 0, cron_number = 15, pay_updated = "'.$createdon.'"
                           WHERE payment_id ='.$payment_id.'';
            $query22    = mysqli_query($conn, $update21);
            //UPDATE SPACE BOOKING
            if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                $update55   = 'UPDATE events 
                               SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
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

                $guiderID       = $row15['guiderID'];
                $travellerID    = $row15['travellerID'];
                $serviceID      = $row15['serviceID'];
                if($status == 'paid'){
                    /******UPDATE SENANG SUCCESS********/
                    $update22   = 'UPDATE senangpay_transaction 
                                   SET pay_status=1, cron_number=15, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
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
                                   SET pay_status=0, cron_number=15, transaction_id = "'.$transaction_id.'", pay_updated = "'.$createdon.'"
                                   WHERE payment_id = '.$payment_id;
                    $query27    = mysqli_query($conn, $update23);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }else{
                    $update24   = 'UPDATE senangpay_transaction 
                                  SET pay_status = 0, cron_number = 15, pay_updated = "'.$createdon.'"
                                  WHERE payment_id = '.$payment_id;
                    $query28    = mysqli_query($conn, $update24);
                    //UPDATE SPACE BOOKING
                    if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                        $update55   = 'UPDATE events 
                                       SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
                                       WHERE orderID = "'.$order_id.'" AND status != 3';
                        $query33    = mysqli_query($conn, $update55);
                    }
                }
            }else{
                //NOT START
                $update25   = 'UPDATE senangpay_transaction 
                              SET pay_status = 0, cron_number = 15, pay_updated = "'.$createdon.'"
                              WHERE payment_id = '.$payment_id;
                $query29    = mysqli_query($conn, $update25);
                //UPDATE SPACE BOOKING
                if($paymentAppType == 'space_booking' || $paymentAppType == 'web_space_booking'){
                    $update55   = 'UPDATE events 
                                   SET host_id = 0, orderID = "", paidStatus = 0, lockedBy = 0, lockedDateTime = NULL, status = 1, color = "#398439", updated_at = "'.$createdon.'"
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