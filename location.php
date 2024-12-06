<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'supervisor') {
    header("location: preLogin.php");
    exit();
}

$supervisorId = json_encode($_SESSION['record']); // Encode supervisor ID for JavaScript usage
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <!-- Firebase scripts -->
    <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- External stylesheets -->
    <link rel="stylesheet" href="Css/VitalStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Css/commCss.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />



    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #283048, #859398); /* Updated background gradient */
            color: white;
        }
 .full_bg, .header {
            background: linear-gradient(to right, #283048, #859398); /* Matches body background */
            color: white;
            padding-bottom: 15px;
        }

        .table-container {
            
    width: 80%; 
    margin: 70px auto; /* Add margin-top to create space from the header */
    padding: 20px; 
    box-shadow: 0 0 20px rgba(0,0,0,0.1); 
    border-radius: 10px; 
    background: #ffffff; /* White background for the table container */

        }

        table {
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px; 
            text-align: center; 
            vertical-align: middle;
        }

        th {
            background-color: #005b9a;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody tr.red {
            background-color: #ffcccc !important;
        }

        tbody tr.yellow {
            background-color: #ffff99 !important;
        }
        td {
    color: black !important; /* Forces the text color to black */
}

          #searchInput {
        width: 50%;
        margin-bottom: 20px;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

        .table-responsive {
            margin-top: 20px;
        }
@media (max-width: 768px) {
            .screen {
                width: 90%; /* Shrinks the screen to fit smaller devices */
                height: auto;
                padding: 20px;
            }

            .title {
                font-size: 20px; /* Reduces title size for smaller screens */
            }

            .button {
                font-size: 16px;
                padding: 14px 18px;
            }
        }
		@media (max-width: 480px) {
            .title {
                font-size: 18px;
            }

            .button {
                font-size: 14px;
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include "navbar.php" ?>
    <!-- End Header -->

    <!-- Table Container -->
    <div class="table-container">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by Name or ID" class="form-control mb-3">
        <div class="table-responsive">
            <table id="locationTable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Floor</th>
                        <th>Section</th>
                    </tr>
                </thead>
                <tbody id="tableLocation">
                    <!-- Table rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>
    <script>
    const firebaseConfig = {
        apiKey: "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA",
        authDomain: "smart-helmet-database-affb6.firebaseapp.com",
        databaseURL: "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com",
        projectId: "smart-helmet-database-affb6",
        storageBucket: "smart-helmet-database-affb6.appspot.com",
        messagingSenderId: "197707055105",
        appId: "1:197707055105:web:769e69f5808ab5f7bcc000",
        measurementId: "G-LCZ5V9S1B1"
    };

    firebase.initializeApp(firebaseConfig);

    const supervisorId = <?php echo $supervisorId; ?>;

    function fetchWorkers() {
        const dbRef = firebase.database().ref();
        
        // Retrieve supervisor's worker IDs
        dbRef.child("supervisors").child(supervisorId).on('value', (snapshot) => {
            const supervisorData = snapshot.val();

            if (!supervisorData || !supervisorData.workerIDs) {
                console.error("No worker IDs found for this supervisor.");
                return;
            }

            const workerIDs = supervisorData.workerIDs.split(',');
            workerIDs.forEach((workerID) => {
                dbRef.child("workers").orderByChild("ID").equalTo(workerID.trim()).on('value', (workerSnapshot) => {
                    workerSnapshot.forEach((workerData) => {
                        const worker = workerData.val();

                        if (!worker || !worker.sensorID) return;

                        // Retrieve sensor data and populate the table
                        dbRef.child("Sensor").child(worker.sensorID).on('value', (sensorSnapshot) => {
                            const sensorData = sensorSnapshot.val();
                            updateTable(worker, sensorData);
                        });
                    });
                });
            });
        });
    }

    function updateTable(worker, sensorData) {
        const tableBody = document.getElementById('tableLocation');
        let existingRow = document.querySelector(`tr[data-worker-id="${worker.ID}"]`);

        if (!existingRow) {
            // Create a new row if it doesn't exist
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-worker-id', worker.ID);
            newRow.innerHTML = `
                <td>${worker.ID}</td>
                <td>${worker.name}</td>
                <td>${sensorData?.Floor || "N/A"}</td>
                <td>${sensorData?.Section || "N/A"}</td>
            `;
            tableBody.appendChild(newRow);
        } else {
            // Update the existing row
            existingRow.children[2].textContent = sensorData?.Floor || "N/A";
            existingRow.children[3].textContent = sensorData?.Section || "N/A";
        }
    }

    function searchTable() {
        const filter = document.getElementById("searchInput").value.toUpperCase();
        const table = document.getElementById("locationTable");
        const rows = table.getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            const idCell = rows[i].getElementsByTagName("td")[0];
            const nameCell = rows[i].getElementsByTagName("td")[1];
            const idText = idCell?.textContent || idCell?.innerText || "";
            const nameText = nameCell?.textContent || nameCell?.innerText || "";
            rows[i].style.display = idText.toUpperCase().includes(filter) || nameText.toUpperCase().includes(filter) ? "" : "none";
        }
    }

    // Automatically fetch and display data when the page loads
    document.addEventListener("DOMContentLoaded", fetchWorkers);
</script>

<!-- truck movment-->
<div class="truck">
  <div class="container-fluid">
     <div class="row">
        <div class="col-md-6 jkhgkj">
           <div class="truck_img1">
              <img src="images/truck.png" alt="#"/>
           </div>
        </div>
        <div class="col-md-6">
           <div class="truck_img1">
              <img src="images/jcb.png" alt="#"/>
           </div>
        </div>
     </div>
  </div>
</div>
<!-- end truck movment-->
<!--  footer -->
<footer>
  <div class="footer">
     <div class="container">
        <div class="row">
      
        </div>
     </div>
     <div class="copyright">
        <div class="container">
           <div class="row">
              <div class="col-md-8 offset-md-2">
                 <p>© 2024 SMART HELMET</a></p>
              </div>
           </div>
        </div>
     </div>
  </div>
</footer>
<!-- end footer -->
</body>
</html>
