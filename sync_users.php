<?php
require 'conn.php';
require 'onconn.php';

$imgFolder = __DIR__ . "/uploads/";

if (!file_exists($imgFolder)) mkdir($imgFolder, 0777, true);

// Get users from online
$result = mysqli_query($onlineAtt, "
    SELECT 
        userid, displayName, givenName, jobTitle, mail, surname,
        userPrincipalName, password, dept, userlvl, phonenum,
        company, status, image, driverInfoId
    FROM tbl_user
    WHERE 1
");

// Get existing local user IDs
$localIds = [];
$res = mysqli_query($con, "SELECT userid, displayName FROM tbl_user");
$localUsers = [];
while ($row = mysqli_fetch_assoc($res)) {
    $localIds[$row['userid']] = true;
    $localUsers[$row['userid']] = $row['displayName'];
}

$newCount = 0;
$newNames = []; // Store names of new users

while ($u = mysqli_fetch_assoc($result)) {
    $userid = $u['userid'];

    if (isset($localIds[$userid])) continue;

    // Insert new user to LOCAL database
    $displayName = mysqli_real_escape_string($con, $u['displayName']);
    $givenName = mysqli_real_escape_string($con, $u['givenName']);
    $jobTitle = mysqli_real_escape_string($con, $u['jobTitle']);
    $mail = mysqli_real_escape_string($con, $u['mail']);
    $surname = mysqli_real_escape_string($con, $u['surname']);
    $userPrincipalName = mysqli_real_escape_string($con, $u['userPrincipalName']);
    $password = mysqli_real_escape_string($con, $u['password']);
    $dept = mysqli_real_escape_string($con, $u['dept']);
    $userlvl = $u['userlvl'];
    $phonenum = $u['phonenum'];
    $company = mysqli_real_escape_string($con, $u['company']);
    $status = $u['status'];
    $image = $u['image'];
    $driverInfoId = $u['driverInfoId'];

    mysqli_query($con, "
        INSERT INTO tbl_user (
            userid, displayName, givenName, jobTitle, mail, surname,
            userPrincipalName, password, dept, userlvl, phonenum,
            company, status, image, driverInfoId
        )
        VALUES (
            '$userid', '$displayName', '$givenName', '$jobTitle', '$mail', '$surname',
            '$userPrincipalName', '$password', '$dept', '$userlvl', '$phonenum',
            '$company', '$status', '$image', '$driverInfoId'
        )
    ");

    // Download image 
    if (!empty($image)) {
        $image_url = "https://qr.stellarseedscorp.org/" . $image;
        $local_path = $imgFolder . basename($image);

        if (!file_exists($local_path)) {
            $imgData = @file_get_contents($image_url);
            if ($imgData !== false) {
                file_put_contents($local_path, $imgData);
            }
        }
    }

    $newCount++;
    $newNames[] = $displayName;
}

mysqli_close($onlineAtt);
mysqli_close($con);

$namesJson = urlencode(json_encode($newNames));
header("Location: " . $_SERVER['HTTP_REFERER'] . "?sync=done&count=" . $newCount . "&names=" . $namesJson);
exit;
