<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR User Viewer</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .body2 {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 1200px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            display: flex;
            overflow: hidden;
        }

        .left {
            width: 40%;
            background: linear-gradient(135deg, #00c853, #43a047);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            color: #fff;
        }

        .profile-img {
            width: 230px;
            height: 230px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.4);
            margin-bottom: 15px;
        }

        .left h3 {
            font-weight: 600;
            text-align: center;
        }

        .status {
            margin-top: 10px;
            font-size: 13px;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .right {
            width: 60%;
            padding: 30px;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #222;
        }

        .info {
            margin-bottom: 15px;
        }

        .label {
            font-size: 12px;
            color: #888;
        }

        .value {
            font-size: 15px;
            font-weight: 600;
            color: #222;
            margin-top: 2px;
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 15px 0;
        }

        .verified {
            margin-top: 20px;
            padding: 12px;
            background: #00c853;
            color: #ffffff;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            transition: 0.5s;
            cursor: pointer;
        }

        .unverified {
            margin-top: 20px;
            padding: 12px;
            background: #ba2828;
            color: #ffffff;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            transition: 0.5s;
            cursor: pointer;
        }

        .driverified {
            margin-top: 20px;
            padding: 12px;
            background: #f5ede8;
            color: #7d482e;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            transition: 0.5s;
            cursor: pointer;
        }

        .verified:hover,
        .unverified:hover,
        .driverified:hover {
            scale: 105%;
        }

        .sucverified {
            margin-top: 20px;
            padding: 12px;
            background: #e8f4f5;
            color: #2e5d7d;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            transition: 0.5s;
            cursor: pointer;
        }

        @media(max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
            }

            .left,
            .right {
                width: 100%;
            }
        }

        /* FIXED MODAL STYLES */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.show {
            display: flex !important;
        }

        .modal-content {
            background: white;
            width: 400px;
            max-width: 90%;
            padding: 20px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: popIn 0.2s ease;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 22px;
            cursor: pointer;
            border: none;
            background: none;
        }

        #scanBox {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
        }

        #statusText {
            margin-top: 12px;
            font-size: 14px;
            color: #666;
        }

        @keyframes popIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

</head>

<body>

    <?php



    require 'conn.php';
    $id = $_GET['id'];

    $getUserInfo = "SELECT b.displayName, b.jobTitle, b.company, a.date, a.time, a.type FROM `tbl_qratt` a INNER JOIN tbl_user b ON a.userid = b.userid WHERE b.userid = '$id'";
    $getUserInfoq = mysqli_query($con, $getUserInfo);

    $getUserInfoRow = mysqli_fetch_assoc($getUserInfoq);

    $company = "";

    if ($getUserInfoRow['company'] == "1") {
        $company = "Stellar Seeds Corp.";
    } else if ($getUserInfoRow['company'] == "2") {
        $company = "Syngenta";
    } else if ($getUserInfoRow['company'] == "3") {
        $company = "VFI";
    } else if ($getUserInfoRow['company'] == "4") {
        $company = "JENTEC";
    } else if ($getUserInfoRow['company'] == "5") {
        $company = "Synergy";
    } else if ($getUserInfoRow['company'] == "6") {
        $company = "Canteen";
    }

    $type = "";

    if ($_GET['type'] == "1") {
        $type = "TIME IN";
    } else {
        $type = "TIME OUT";
    }

    ?>

    <div class="body">
        <div class="container">

            <div class="left">
                <img src="uploads/<?php echo $id; ?>.png" class="profile-img">
                <h3 id="name"><?php echo $getUserInfoRow['displayName']; ?></h3>
                <div class="status"><?php echo $type; ?></div>
            </div>
            <div class="right">
                <div
                    style="
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            ">
                    <div class="title">User Information</div>


                    <a
                        href="javascript:void(0);"
                        onclick="window.location.href = 'sync_users.php'"
                        class="verified"
                        style="padding: 8px 8px; font-size: 13px; text-decoration: none">
                        Sync User Data
                    </a>
                </div>

                <div class="info">
                    <div class="label">User ID</div>
                    <div class="value" id="userid">SSC-001</div>
                </div>

                <div class="divider"></div>

                <!-- <div class="info">
                <div class="label">Department</div>
                <div class="value" id="department">MIS Department</div>
            </div> -->

                <div class="info">
                    <div class="label">Position</div>
                    <div class="value" id="position"><?php echo $getUserInfoRow['jobTitle']; ?></div>
                </div>

                <div class="info">
                    <div class="label">Company</div>
                    <div class="value" id="company"><?php echo $company; ?></div>
                </div>

                <!-- <div class="sucverified">✔ QR Verified Successfully</div> -->

                <!-- <div class="driverified">Download QR for Driver</div> -->

                <div
                    class="driverified"
                    id="driverQrBtn"
                    onclick="updatePrintContent()">
                    Print QR for Driver
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
                    <div class="verified" id="openTimeIn">TIME IN</div>
                    <div class="unverified" id="openTimeOut">TIME OUT</div>
                </div>
            </div>

        </div>
    </div>

    <!-- FIXED MODAL - Changed close button ID -->
    <div class="modal" id="myModal">
        <div class="modal-content">
            <button class="close-btn" id="closeModalBtn">&times;</button>
            <h2 id="modalTitle">TIME IN</h2>
            <input type="text" id="scanBox" placeholder="Scan here..." autocomplete="off">
            <p id="statusText">Ready to scan...</p>
        </div>
    </div>


    <div
        id="syncModal"
        style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
      ">
        <div
            style="
          background: white;
          padding: 30px;
          border-radius: 20px;
          text-align: center;
          min-width: 600px;
          max-width: 700px;
          width: 90%;
        ">
            <div style="font-size: 50px" id="modalIcon">✅</div>
            <div
                style="font-size: 24px; font-weight: 600; margin: 10px 0"
                id="modalTitle">
                Sync Complete!
            </div>
            <div style="color: #666" id="modalMessage">New users added:</div>
            <div
                style="
            font-size: 36px;
            font-weight: bold;
            color: #4caf50;
            margin: 10px 0;
          "
                id="modalCount"></div>
            <div
                id="modalNames"
                style="
            max-height: 400px;
            overflow-y: auto;
            text-align: left;
            margin: 10px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
          "></div>
            <button
                onclick="closeSyncModal()"
                style="
            margin-top: 20px;
            padding: 10px 30px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
          ">
                OK
            </button>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const syncDone = urlParams.get("sync");
        const count = urlParams.get("count");
        const namesParam = urlParams.get("names");

        // FIXED: Renamed function to avoid conflict
        function closeSyncModal() {
            document.getElementById("syncModal").style.display = "none";
            window.history.replaceState({},
                document.title,
                window.location.pathname,
            );
        }

        if (syncDone === "done") {
            const modal = document.getElementById("syncModal");
            const modalCount = document.getElementById("modalCount");
            const modalMessage = document.getElementById("modalMessage");
            const modalIcon = document.getElementById("modalIcon");
            const modalTitle = document.getElementById("modalTitle");
            const modalNames = document.getElementById("modalNames");

            modal.style.display = "flex";

            if (count && parseInt(count) > 0) {
                modalCount.innerHTML = count;
                modalMessage.innerHTML =
                    "New user" + (parseInt(count) !== 1 ? "s" : "") + " added:";
                modalIcon.innerHTML = "✅";
                modalTitle.innerHTML = "Sync Complete!";

                // Display names if available
                if (namesParam) {
                    const names = JSON.parse(decodeURIComponent(namesParam));
                    if (names.length > 0) {
                        let namesHtml =
                            '<div style="font-weight: 600; margin-bottom: 12px; font-size: 14px;">📋 Added users:</div>';
                        namesHtml +=
                            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">';
                        names.forEach((name) => {
                            namesHtml +=
                                '<div style="padding: 6px 8px; background: white; border-radius: 6px; font-size: 13px; border: 1px solid #e0e0e0;">• ' +
                                name +
                                "</div>";
                        });
                        namesHtml += "</div>";
                        modalNames.innerHTML = namesHtml;
                    } else {
                        modalNames.innerHTML = "";
                    }
                }
            } else {
                modalCount.innerHTML = "";
                modalMessage.innerHTML = "All user data is already up to date";
                modalIcon.innerHTML = "ℹ️";
                modalTitle.innerHTML = "No New Users";
                modalNames.innerHTML = "";
            }
        }
    </script>


    <script>
        const idx = "<?php echo $id ?>";
        const masked = idx.substring(0, 4) + "*".repeat(idx.length - 7) + idx.substring(idx.length - 3);


        const user = {
            userid: masked,
            name: "<?php echo $getUserInfoRow['displayName']; ?>",
            department: "<?php echo $getUserInfoRow['jobTitle']; ?>",
            position: "<?php echo $getUserInfoRow['jobTitle']; ?>",
            company: "<?php echo $company; ?>",
            profile: "<?php echo $id; ?>.png"
        };

        document.getElementById("name").textContent = user.name;
        document.getElementById("userid").textContent = user.userid;
        document.getElementById("department").textContent = user.department;
        document.getElementById("position").textContent = user.position;
        document.getElementById("company").textContent = user.company;
        document.getElementById("profile").src = user.profile;
    </script>

    <!-- FIXED MODAL JAVASCRIPT -->
    <script>
        const openTimeIn = document.getElementById("openTimeIn");
        const openTimeOut = document.getElementById("openTimeOut");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const modal = document.getElementById("myModal");
        const modalTitle = document.getElementById("modalTitle");
        const scanBox = document.getElementById("scanBox");
        const statusText = document.getElementById("statusText");

        let scanTimer = null;
        let scanType = 1;

        function focusScanBox() {
            setTimeout(() => {
                if (scanBox) scanBox.focus();
            }, 100);
        }

        function openScanner(type) {
            scanType = type;
            modalTitle.textContent = type === 1 ? "TIME IN" : "TIME OUT";
            statusText.textContent = "Ready to scan...";
            if (scanBox) scanBox.value = "";
            modal.classList.add("show");
            focusScanBox();
        }

        if (openTimeIn) {
            openTimeIn.addEventListener("click", () => {
                openScanner(1);
            });
        }

        if (openTimeOut) {
            openTimeOut.addEventListener("click", () => {
                openScanner(2);
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener("click", () => {
                modal.classList.remove("show");
                if (scanBox) scanBox.value = "";
            });
        }

        if (modal) {
            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.classList.remove("show");
                    if (scanBox) scanBox.value = "";
                }
            });
        }

        function processScan(value) {
            value = value.trim();

            if (value === "") return;

            if (statusText) statusText.textContent = "Scanned: " + value;

            window.location.href = "save-att.php?type=" + encodeURIComponent(scanType) +
                "&id=" + encodeURIComponent(value);
        }

        if (scanBox) {
            scanBox.addEventListener("input", function() {
                if (scanTimer) clearTimeout(scanTimer);

                scanTimer = setTimeout(() => {
                    processScan(scanBox.value);
                }, 100);
            });

            scanBox.addEventListener("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    processScan(scanBox.value);
                }
            });
        }
    </script>

    <script>
        function syncAttendance() {
            fetch('sync-att.php')
                .then(response => response.text())
                .then(data => {
                    console.log("Sync success:", data);
                })
                .catch(error => {
                    console.log("Sync failed:", error);
                });

        }

        function syncDriver() {
            fetch('sync-driver.php')
                .then(response => response.text())
                .then(data => {
                    console.log("Sync success driver:", data);
                })
                .catch(error => {
                    console.log("Sync failed driver:", error);
                });

        }


        // run once pag load sa page
        syncAttendance();
        syncDriver();

        // run every 5 minutes
        setInterval(() => {
            syncAttendance();
            syncDriver();
        }, 300000);
    </script>


    <script>
        function generateDriverQRString() {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            let result = "";
            for (let i = 0; i < 5; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }

        function updatePrintContent() {
            const qrString = generateDriverQRString();

            fetch("save-driver.php?id=" + encodeURIComponent(qrString))
                .then((res) => res.text())
                .then((response) => {
                    console.log("Server:", response);

                    // window.location.href = "/qr/index.html";
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Error saving/printing QR");
                });
        }
    </script>

</body>

</html>