<?php

if($_SERVER["SERVER_NAME"] == 'localhost'){
    $dbserver    = "localhost";
    $dbuser      = "root";
    $dbpass      = "";
    $dbname      = "buddey_admin";
} else {
    $dbserver    = "localhost";
    $dbuser      = "root";
    $dbpass      = "$2y$12$3IJeJ6";
    $dbname      = "buddeydb_live_2019";
}

//connection to the database
$con            = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname);
?>