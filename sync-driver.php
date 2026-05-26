<?php

require 'conn.php';
require 'onconn.php';

$selectDrivers = "SELECT * FROM `tbl_qrdriver` WHERE uploaded = '0'";
$selectDriversq = mysqli_query($con, $selectDrivers);

$count = 0;

while ($selectDriversrow = mysqli_fetch_array($selectDriversq)) {

    $did = mysqli_escape_string($con, $selectDriversrow['did']);
    $userid = mysqli_escape_string($con, $selectDriversrow['userid']);
    $plateno = mysqli_escape_string($con, $selectDriversrow['plateno']);
    $datetimein = mysqli_escape_string($con, $selectDriversrow['datetimein']);
    $datetimeout = mysqli_escape_string($con, $selectDriversrow['datetimeout']);
    $status = mysqli_escape_string($con, $selectDriversrow['status']);
    $uploaded = mysqli_escape_string($con, $selectDriversrow['uploaded']);
    $rhid = mysqli_escape_string($con, $selectDriversrow['rhid']);
    $qnumber = mysqli_escape_string($con, $selectDriversrow['qnumber']);
    $ucode = mysqli_escape_string($con, $selectDriversrow['ucode']);

    $insertOnline = "INSERT INTO `tbl_qrdriver` (`did`, `userid`, `plateno`, `datetimein`, `datetimeout`, `status`, `uploaded`, `rhid`, `qnumber`, `ucode`) 
                     VALUES (NULL, '$userid', '$plateno', '$datetimein', '$datetimeout', '$status', '1', '$rhid', '$qnumber', '$ucode')";

    if (mysqli_query($onlineAtt, $insertOnline)) {
        $count++;
    }
}

echo $count;

$updateAllOffline = "UPDATE tbl_qrdriver SET uploaded = '1' WHERE uploaded = '0'";
if (mysqli_query($con, $updateAllOffline)) {
    echo " Offline Updated! " . $count;
}
