<?php
session_start(); // Start the session
$supervisorId = json_encode($_SESSION['record']); // Encode supervisor ID for JavaScript usage
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vital Signs </title>
<!-- Firebase scripts -->
<script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>
<!-- External stylesheets -->
<link rel="stylesheet" href="Css/VitalStyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="Css/commCss.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responsive.css">
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <style>
 .table-container {
    width: 80%; /* Adjust width as needed */
    margin: 0 auto; /* This centers the div */
    padding: 20px; /* Optional: for some spacing around the table */
    box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Optional: adds shadow for better focus */
}
 table {
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
    
}
#vitalsignsTable td img {
    width: 5px; /* Slightly larger for visibility */
    height: 5px; /* Adjusted to maintain aspect ratio */
}



tr:nth-child(even) {
    background-color: #f2f2f2;
}

.red {
    background-color: #ffcccc;
}

.yellow {
    background-color: #ffff99;
}
.lang{
 color: white;   
}
.status-legend {
    text-align: center;
    margin-bottom: 20px;
    color: #00FFFFFF;
}

.legend-items {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.legend-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.legend-item img {
    width: 40px; /* Adjust size as needed */
    height: auto;
}

.legend-item span {
    margin-top: 5px;
    font-weight: bold;
}

th {
    text-align: center;  /* Centers text horizontally */
    vertical-align: middle; /* Centers text vertically (optional) */
    color: black; /* Sets text color to black */
}
#searchInput{
   margin-bottom: 40px; 
}


</style>
</head>

<body>
<!-- header -->
<header class="full_bg">
  <!-- header inner -->
  <div class="header">
     <div class="container">
        <div class="row">
           <div class="col-md-12">
              <div class="header_bottom">
                 <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                       <div class="full">
                          <div class="center-desk">
                             <div class="logo">
                               <a href="index.php" ><img class="nav-img" src="img/Screenshot_2024-02-16_160751-removebg-preview.png" alt="logo"/></a>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                       <nav class="navigation navbar navbar-expand-md navbar-dark ">
                          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                          </button>
                          <div class="collapse navbar-collapse" id="navbarsExample04">
                             <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                   <a class="nav-link" href="logout.php">Log Out</a>
                                </li>
                             </ul>
                          </div>
                       </nav>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
  <!-- end header inner -->
  <!-- end header -->
  <!-- Legend for status images -->
<div class="status-legend">
    <h3 class="lang">Status Legend: </h3>
    <div class="legend-items">
        <div class="legend-item">
            <img src="images/normal.png" alt="Normal Status">
            <span>Normal</span>
        </div>
        <div class="legend-item">
            <img src="images/yellow.png" alt="Mild High">
            <span>Needs Attention</span>
        </div>
        <div class="legend-item">
            <img src="images/abnormal.png" alt="Abnormal">
            <span>Critical</span>
        </div>
    </div>
</div>
  
<div class="table-container">
      <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by Name or ID">
    <table id="vitalSignsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Temperature</th>
                <th>Heart Rate</th>
                <th>Gas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Content will be filled by JavaScript -->
        </tbody>
    </table>
      </div>

    <script>
        const supervisorId = <?php echo $supervisorId; ?>;
    </script>
    <script src="vitalSigns.js"></script> <!-- JavaScript file for handling data fetching and table manipulation -->

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

function fetchWorkers() {
    const dbRef = firebase.database().ref();
    dbRef.child("supervisors").child(supervisorId).on('value', function(snapshot) {
        const workerIDs = snapshot.val().workerIDs.split(',');
        workerIDs.forEach(id => {
            dbRef.child("workers").orderByChild("ID").equalTo(id.trim()).on('value', snap => {
                document.getElementById('tableBody').innerHTML = ''; // Clear existing table rows
                snap.forEach(data => {
                    const worker = data.val();
                    dbRef.child("Sensor").child(worker.sensorID).on('value', sensorSnapshot => {
                        const sensorData = sensorSnapshot.val();
                        console.log(sensorData);
                        addToTable(worker, sensorData);
                    });
                });
            });
        });
    });
}

function addToTable(worker, sensorData) {
    const tableBody = document.getElementById('tableBody');
    let tr = document.querySelector(`tr[data-worker-id="${worker.ID}"]`); // Select the existing row by worker ID

    if (!tr) {
        tr = document.createElement('tr');
        tr.setAttribute('data-worker-id', worker.ID);

        const idCell = document.createElement('td');
        idCell.textContent = worker.ID;
        const nameCell = document.createElement('td');
        nameCell.textContent = worker.name;
        const tempCell = document.createElement('td');
        const bpmCell = document.createElement('td');
        const gasDetected = document.createElement('td'); // Gas cell
        const statusImgCell = document.createElement('td'); // Status cell
        const statusImg = document.createElement('img');

        statusImgCell.appendChild(statusImg);
        tr.appendChild(idCell);
        tr.appendChild(nameCell);
        tr.appendChild(tempCell);
        tr.appendChild(bpmCell);
        tr.appendChild(gasDetected); // Add gas cell before status cell
        tr.appendChild(statusImgCell); // Add status cell last

        tableBody.appendChild(tr);
    }

    const tempCell = tr.children[2];
    const bpmCell = tr.children[3];
    const gasDetected = tr.children[4];
    const statusImg = tr.children[5].firstChild; // Updated index for status image

    tempCell.textContent = sensorData.BodyTemperature ? `${sensorData.BodyTemperature}°C` : 'Unavailable';
    bpmCell.textContent = sensorData.BPM ? `${sensorData.BPM} bpm` : 'Unavailable';
    gasDetected.textContent = `${sensorData.GasDetected}`;

    // Set default color and image source
    let color = 'white';  // Default background color
    statusImg.src = '';   // Default no image

    // Check if both Temperature and Heart Rate are unavailable
    if (tempCell.textContent === 'Unavailable' && bpmCell.textContent === 'Unavailable') {
        statusImg.style.display = 'none'; // Hide image if data is unavailable
    } else {
        statusImg.style.display = ''; // Show image otherwise
        statusImg.style.width = '40px';

        if ((sensorData.BodyTemperature > 39 || sensorData.BodyTemperature < 36) || (sensorData.BPM > 120 || sensorData.BPM < 60) || sensorData.GasDetected == "Detected") {
            color = '#ffcccc';
            statusImg.src = 'images/abnormal.png';
        } else if ((sensorData.BodyTemperature > 37.2 && sensorData.BodyTemperature <= 39) || (sensorData.BPM > 110 && sensorData.BPM <= 120)) {
            color = '#ffff99';
            statusImg.src = 'images/yellow.png';
        } else if ((sensorData.BodyTemperature >= 36 && sensorData.BodyTemperature <= 37.1) || (sensorData.BPM > 60 && sensorData.BPM <= 110) || (sensorData.GasDetected == "No Gas Detected")) {
            statusImg.src = 'images/normal.png';
        }
    }
    
    tr.style.backgroundColor = color; // Set the row color
}




function searchTable() {
    const filter = document.getElementById("searchInput").value.toUpperCase();
    const table = document.getElementById("vitalSignsTable");
    const tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        const tdID = tr[i].getElementsByTagName("td")[0];
        const tdName = tr[i].getElementsByTagName("td")[1];
        if (tdID || tdName) {
            const txtID = tdID.textContent || tdID.innerText;
            const txtName = tdName.textContent || tdName.innerText;
            if (txtID.toUpperCase().indexOf(filter) > -1 || txtName.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

window.onload = fetchWorkers;
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
