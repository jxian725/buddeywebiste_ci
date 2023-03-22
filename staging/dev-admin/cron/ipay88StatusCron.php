<?php
//include("connection.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "";
$dbname      = "buddeybnb";

/*$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "mysql123";
$dbname      = "buddey_admin";*/
$conn = @mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

$today  = date('Y-m-d');
$date1  = date("Y-m-d H:i:s");
$time   = strtotime($date1);
$time   = $time - (20 * 60);
$date2  = date("Y-m-d H:i:s", $time);
$service_date   = '';
$createdon      = date("Y-m-d H:i:s");
// Some Query
$select = 'SELECT payment_id, TransactionRefId, serviceID, guiderID, travellerID, transactionAmount, iPay88TaransactionID, ipay_createdon, cronUpdated, Status 
           FROM ipay88_transaction 
           WHERE cronUpdated = 0 
           AND ipay_createdon < "'.$date2.'" AND (Status=0 OR Status=3 OR Status=4)';
$query  = mysqli_query($conn, $select);
while ($row = mysqli_fetch_array($query)){
    $RefNo      = $row['TransactionRefId'];
    $Amount     = $row['transactionAmount'];
    $transid    = $row['iPay88TaransactionID'];
    $requestRes = Requery($RefNo,$Amount); //$requestRes == '00'
    $serviceID  = $row['serviceID'];
    if($requestRes == '00'){
        /******UPDATE IPAY88 SUCCESS********/
        $update    = 'UPDATE ipay88_transaction 
                      SET Status = 1, cronUpdated = 1, requeryRes = "00"
                      WHERE payment_id = '.$row['payment_id'];
        $query1    = mysqli_query($conn, $update);
        /******UPDATE SERVICE PAYMENT COMPLETED********/
        $update11  = 'UPDATE service_list 
                      SET status = 4, transactionID = "'. $transid .'"
                      WHERE service_id = '.$serviceID;
        $query2    = mysqli_query($conn, $update11);
        /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
        $select21  = 'SELECT service_id, service_traveller_id, service_guider_id, service_date
                      FROM service_list 
                      WHERE service_id = '.$serviceID;
        $query21   = mysqli_query($conn, $select21);
        while ($row2 = mysqli_fetch_row($query21)){
            $traveller_id = $row2[1];
            $guider_id    = $row2[2];
            $service_date = $row2[3];
        }
        if($today == $service_date){
            $jny_status = 2; //ONGOING
        }else{
            $jny_status = 1; //UPCOMING
        }
        /******SELECT JOURNEY ALREADY EXISTS********/
        $select11   = ' SELECT journey_id
                        FROM journey_list 
                        WHERE jny_service_id = '.$serviceID;
        $query11    = mysqli_query($conn, $select11);
        $rows       = mysqli_num_rows($query11);
        /******CREATE NEW JOURNEY********/
        if($rows > 0){
            $update15   = 'UPDATE journey_list 
                           SET status = "'. $jny_status .'"
                           WHERE jny_service_id = '.$serviceID;
            $query2     = mysqli_query($conn, $update15);
        }else{
            $insert     = 'INSERT INTO journey_list (jny_traveller_id,jny_guider_id,jny_service_id,createdon,payment_status,jny_transactionID,jny_status) 
                            VALUES 
                            ("'.$traveller_id.'","'.$guider_id.'","'.$serviceID.'","'.$createdon.'","paid","'.$transid.'","'.$jny_status.'")';
            $query16    = mysqli_query($conn, $insert);
        }
    }elseif ($requestRes == 'Invalid parameters' || $requestRes == 'Record not found' || $requestRes == 'Incorrect amount' || $requestRes == 'Payment fail' || $requestRes == 'M88Admin') {
        $update2    = 'UPDATE ipay88_transaction 
                        SET Status = 3, cronUpdated = 1, requeryRes = "'.$requestRes.'"
                        WHERE payment_id = '.$row['payment_id'];
        $query3     = mysqli_query($conn, $update2);
        /******UPDATE SERVICE PAYMENT PP********/
        $update44   = 'UPDATE service_list 
                       SET status = 2
                       WHERE service_id = '.$serviceID;
        $query44    = mysqli_query($conn, $update44);
    }else{
        $update3    = 'UPDATE ipay88_transaction 
                        SET Status = 4, requeryRes = "'.$requestRes.'"
                        WHERE payment_id = '.$row['payment_id'];
        $query4     = mysqli_query($conn, $update3);
        /******UPDATE SERVICE PAYMENT PROCESSING********/
        $update55   = 'UPDATE service_list 
                       SET status = 5
                       WHERE service_id = '.$serviceID;
        $query55    = mysqli_query($conn, $update55);
    }
}

function Requery($RefNo,$Amount){
    $MerchantCode   = 'M10845';
    //$RefNo          = 'TXN1920180527234506048111';
    //$Amount         = '1';
    $query      = "https://www.mobile88.com/epayment/enquiry.asp?MerchantCode=" . $MerchantCode . "&RefNo=" . str_replace(" ","%20",$RefNo) . "&Amount=" . $Amount;
    $url        = parse_url($query);
    $host       = $url["host"];
    $sslhost    = "ssl://".$host;
    $path       = $url["path"] . "?" . $url["query"];
    $timeout    = 1;
    $fp         = fsockopen ($sslhost, 443, $errno, $errstr, $timeout);
    $buf = '';
    if ($fp) {
        fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
        while (!feof($fp)) {
            $buf .= fgets($fp, 128);
        }
        $lines  = preg_split("/\n/", $buf);
        $Result = $lines[count($lines)-1];
        fclose($fp);
    } else {
        # enter error handing code here
    }
    return $Result;
}