<?php

    require 'conn.php';
    require 'onconn.php';

    $selectUsers = "SELECT * FROM `tbl_qratt` a WHERE a.uploaded = '0'";
    $selectUsersq = mysqli_query($con, $selectUsers);

    $count = 0;

    while($selectUsersrow = mysqli_fetch_array($selectUsersq)){

        $userid = mysqli_escape_string($con, $selectUsersrow['userid']);
        $date = mysqli_escape_string($con, $selectUsersrow['date']);
        $time = mysqli_escape_string($con, $selectUsersrow['time']);
        $type = mysqli_escape_string($con, $selectUsersrow['type']);
        $status = mysqli_escape_string($con, $selectUsersrow['status']);
        $uploaded = mysqli_escape_string($con, $selectUsersrow['uploaded']);
        
        $insertOnline = "INSERT INTO `tbl_qratt` (`qaid`, `userid`, `date`, `time`, `type`, `status`, `uploaded`) VALUES (NULL, '$userid', '$date', '$time', '$type', '$status', '1')";
        
        if(mysqli_query($onlineAtt, $insertOnline)){
            $count++;
        }

    }

    echo $count;

    $updatealloffile = "UPDATE tbl_qratt SET uploaded = '1' WHERE uploaded = '0' ";
    if(mysqli_query($con, $updatealloffile)){
        echo "Offline Updated! "+$count;
    }

?>