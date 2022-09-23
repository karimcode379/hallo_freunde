<?php
ob_start(); //turn on output buffering
session_start();

ini_set('display_errors', 1);
error_reporting(~0);

$timezone = date_default_timezone_set("Europe/Berlin");
$con = mysqli_connect("localhost", "root", "", "social");

if (mysqli_connect_errno()) {
    echo "Connection failed: " . mysqli_connect_errno();
}
