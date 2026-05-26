<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conn.php';

date_default_timezone_set('Asia/Manila');

$ucode = $_GET['id'] ?? '';

if (!$ucode) {
    die("Error: No ID provided");
}

$datetimein = date('Y-m-d H:i:s');

$insertDriver = "
INSERT INTO tbl_qrdriver 
(
    did,
    userid,
    plateno,
    datetimein,
    datetimeout,
    status,
    uploaded,
    rhid,
    qnumber,
    ucode
)
VALUES
(
    NULL,
    NULL,
    NULL,
    '$datetimein',
    NULL,
    '1',
    '0',
    NULL,
    NULL,
    '$ucode'
)
";

if (!mysqli_query($con, $insertDriver)) {
    die("DB Error: " . mysqli_error($con));
}

$data = "";

$data .= "\x1B\x40";                    // Initialize printer
$data .= "\x1B\x61\x01";                // Center alignment

$data .= "\n";
$data .= "====================\n";
$data .= "    DRIVER QR\n";
$data .= "====================\n\n";

// QR Code commands - FIXED sequence
$data .= "\x1D\x28\x6B\x04\x00\x31\x41\x32\x00";  // Model 2
$data .= "\x1D\x28\x6B\x03\x00\x31\x43\x08";      // Size: 8
$data .= "\x1D\x28\x6B\x03\x00\x31\x45\x33";      // Error correction: H

$len = strlen($ucode) + 3;
$lh = $len % 256;
$hh = floor($len / 256);

$data .= "\x1D\x28\x6B" . chr($lh) . chr($hh) . "\x31\x50\x30" . $ucode;
$data .= "\x1D\x28\x6B\x03\x00\x31\x51\x30";      // Print QR

$data .= "\n\n";
$data .= $ucode . "\n";
$data .= $datetimein . "\n\n";

$data .= "--------------------\n";
$data .= "    THANK YOU\n";
$data .= "--------------------\n";

$data .= "\x1B\x64\x05";    // Feed 5 lines
$data .= "\x1D\x56\x00";    // Partial cut

$tempDir = sys_get_temp_dir();
$file = $tempDir . DIRECTORY_SEPARATOR . "print_" . uniqid() . ".bin";

if (!file_put_contents($file, $data)) {
    die("Error: Failed to create print file");
}

// FIXED: Use COPY /B for raw binary printing (NOT PowerShell)
$printer = "\\\\localhost\\XP_80C";  // UNC path
$command = "copy /B \"$file\" \"$printer\" 2>&1";

exec($command, $output, $status);

usleep(300000);

if (file_exists($file)) {
    unlink($file);
}

echo "<pre>";
echo "TEMP FILE:\n$file\n\n";
echo "PRINTER:\n$printer\n\n";
echo "COMMAND:\n$command\n\n";
echo "OUTPUT:\n";
print_r($output);
echo "\nSTATUS: $status\n";
echo "</pre>";

if ($status === 0) {
    echo "<h3 style='color:green;'>✅ Success: QR Code Printed Correctly!</h3>";
} else {
    echo "<h3 style='color:red;'>❌ Error: Need to share printer first</h3>";
    echo "<h4>🔧 Quick Fix (Run CMD as Administrator):</h4>";
    echo "<pre>";
    echo "net share XP_80C=USB001 /GRANT:Everyone,FULL\n";
    echo "OR\n";
    echo "Right-click XP_80C printer → Properties → Sharing → Share this printer\n";
    echo "</pre>";
}
?>