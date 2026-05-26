<?php

    require 'conn.php';

    date_default_timezone_set('Asia/Manila');

    $id = $_GET['id'];
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $type = $_GET['type'];

    $data = [];

    $insertAtt = "INSERT INTO `tbl_qratt` (`qaid`, `userid`, `date`, `time`, `status`, `type`, `uploaded`) VALUES (NULL, '$id', '$date', '$time', '1', '$type', '0')";

    if(mysqli_query($con, $insertAtt)){

        header("location: index.php?id=$id&type=$type");

    }else{
        echo "Error Saving QR";
    }

?>