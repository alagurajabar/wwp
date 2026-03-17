<?php
session_start();

$con = mysqli_connect('localhost','root','');

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}

// select the actual application database
mysqli_select_db($con, 'grocerry');

define('D', "/backend_projects/grocerry");
?>
