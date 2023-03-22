<?php
//include("connection.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

/*$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "";
$dbname      = "buddey_admin";*/

$dbserver    = "localhost";
$dbuser      = "root";
$dbpass      = "mysql123";
$dbname      = "buddey_admin";
$con = @mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
// Some Query
/*$sql    = 'SELECT full_name, username, user_email, contact_number FROM user WHERE status = 1';
$query  = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($query))
{
    print_r($row);
}*/
$date   = date('Y-m-d h:i:s');
$sql    = 'INSERT INTO test_cron (cron_time) VALUES ("'.$date.'")';
if (mysqli_query($con, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error:: " . $sql . "<br>" . mysqli_error($conn);
}

