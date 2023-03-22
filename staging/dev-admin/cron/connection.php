<?php

if($_SERVER["SERVER_NAME"] == 'localhost'){
    $dbserver    = "localhost";
    $dbuser      = "root";
    $dbpass      = "";
    $dbname      = "buddey_admin";
} else {
    $dbserver    = "localhost";
    $dbuser      = "root";
    $dbpass      = "mysql123";
    $dbname      = "buddey_admin";
}
//connection to the database
$con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
?>