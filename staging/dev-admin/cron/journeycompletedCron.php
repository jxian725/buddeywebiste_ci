<?php
//include("connection.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "";
$dbname      = "buddey_admin";

/*$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "mysql123";
$dbname      = "buddey_admin";*/
$conn = @mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
if (!$conn) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
$today    = date('Y-m-d');
// Some Query
$select = 'SELECT journey_id, jny_traveller_id, jny_guider_id, jny_service_id, service_date 
            FROM journey_list 
            JOIN service_list ON (service_list.service_id = journey_list.jny_service_id)
            WHERE service_date < "'.$today.'" AND jny_status != 3';
$query  = mysqli_query($conn, $select);
while ($row = mysqli_fetch_array($query)){
    print_r($row);
    echo '<br>';
    $update = 'UPDATE journey_list 
                SET jny_status = 3, completed_type = "cron" 
                WHERE journey_id = '.$row['journey_id'];
    $query2  = mysqli_query($conn, $update);
}