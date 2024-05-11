<?php
session_start();

 if(!$_SESSION['role'] == 'supervisor')
 {
    header("location: preLogin.php");
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VITAL SIGNS </title>
<style>
   /* CSS styling for temperature image */
   .temperature-image {
    width: 40px; /* Adjust the width as needed */
    height: auto; /* Maintain aspect ratio */
    /* Add any other styling properties here */
}
.heart-rate-image {
   width: 40px; /* Adjust the width as needed */
    height: auto; /* Maintain aspect ratio */
    /* Add any other styling properties here */
}
</style>
<!-- Firebase scripts -->
<script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>

<!-- External stylesheets -->
<link rel="stylesheet" href="Css/VitalStyle.css">
<link rel="stylesheet" href="Css/commCss.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responsive.css">
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">


<!-- mobile metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<!-- site metas -->
<title>SMART HELMET</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">
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
  <div class="name-box">
   <h3 style="color:blanchedalmond">Icon Names: </h3> 
   <p style="color: rgb(91, 233, 122);" ><img src="images/normal.png" class="icon" alt="Normal" > Normal </p>
   <p style="color: rgb(216, 234, 101);" ><img src="images/yellow.png" class="icon" alt="Mild High" > Mild High</p>
   <p style="color: rgb(250, 83, 80);" ><img src="images/abnormal.png" class="icon" alt="AbNormal"> AbNormal</p> <br>
</div>


   <div class="wrapper">
      <p>Date and Time: <span id="currentDateTime"></span></p>
<div class="search-bar">
<label for="searchInput">Search:</label>
<input type="text" id="searchInput" oninput="searchTable()" placeholder="Search by Name or ID">
</div>

<table id="crud">
<caption></caption>
<thead>
   <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Status</th> 
      <th>Vital Signs</th>
      </tr>
   </thead>
<tbody id="tableBody">        
</tbody>

 </table>
</div>

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

<!-- Javascript files-->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-3.0.0.min.js"></script>
<!-- sidebar -->
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/custom.js"></script>


<!-- JavaScript code for updating date and time, searching table, and Firebase integration -->
<script>
// Function to update date and time

        function updateDateTime() {
            var currentDateTimeElement = document.getElementById("currentDateTime");
            var currentDate = new Date().toLocaleDateString();
            var currentTime = new Date().toLocaleTimeString();
            currentDateTimeElement.textContent = currentDate + ' ' + currentTime;
        }
// Update date and time every second

        setInterval(updateDateTime, 1000);
        updateDateTime();
// Function to search the table based on input

    function searchTable() {
    var input, filter, table, tr, tdName, tdID, txtValueName, txtValueID;
    input = document.getElementById("searchInput");
    filter = input.value.trim();
    table = document.getElementById("crud");
    tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
 // Get the cells containing name and ID

        tdName = tr[i].getElementsByTagName("td")[1];
        tdID = tr[i].getElementsByTagName("td")[0];
        if (tdName || tdID) {
// Get the text content of the cells and convert to lowercase for case-insensitive search
            txtValueName = tdName.textContent || tdName.innerText;
            txtValueID = tdID.textContent || tdID.innerText;
// Check if the filter text is found in either the name or ID
            if (txtValueName.indexOf(filter) > -1 || txtValueID.indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
// Function to toggle display of additional options
        function toggleOptions(icon) {
         var options = icon.nextElementSibling;
    options.style.display = (options.style.display === 'none') ? 'block' : 'none';

    // Toggle the icon class between plus and minus
    if (icon.classList.contains('fa-plus')) {
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
    } else {
        icon.classList.remove('fa-minus');
        icon.classList.add('fa-plus');
    }
        }
    </script>


<!-- Firebase configuration and data retrieval -->
    <script>
// Firebase configuration
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

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  const database = firebase.database().ref('workers');

  const searchInput = document.getElementById('searchInput');
  const tableBody = document.getElementById('tableBody');



  // Function to populate the table with workers
  function populateTable(snapshot) {
    tableBody.innerHTML = '';
    snapshot.forEach(childSnapshot => {
      const worker = childSnapshot.val();
      
      appendWorkerToTable(worker);
    });
  }

  // Function to append a worker to the table or update existing entry
  function appendWorkerToTable(worker) {
    // Retrieve sensor data using sensorID
    const sensorRef = firebase.database().ref('Sensor/' + worker.sensorID);
    sensorRef.on('value', (sensorSnapshot) => {
        const sensorData = sensorSnapshot.val();
        let heartRateValue = parseFloat(sensorData.BPM);
        let temperatureValue = parseFloat(sensorData.BodyTemperature);

        // Check if the parsed values are NaN
        let heartRate = isNaN(heartRateValue) ? 'Unavailable' : heartRateValue.toFixed(1);
        let temperature = isNaN(temperatureValue) ? 'Unavailable' : temperatureValue.toFixed(1);

        var color = '';
        var temperatureImage = '';
        var heartRateImage = '';

                if (temperature !== 'Unavailable') {
            const tempValue = parseInt(temperature);
            if ((tempValue > 39 || tempValue<36) || (heartRateValue < 60 || heartRateValue > 120)) {
                color = '#E47374'; // Red for high temperature
                temperatureImage = 'images/abnormal.png';
            } else if ((tempValue > 37.2 && tempValue<=39)|| (heartRateValue >= 110 && heartRateValue <= 120)) {
                color = '#F4D688'; // Yellow for mild high temperature
                temperatureImage = 'images/yellow.png'; // Add the image for mild high temperature
            } else if((tempValue <=37 && tempValue>36) ||(heartRateValue >=60 || heartRateValue <= 109)){
                temperatureImage = 'images/normal.png';
            }
        } else {
            temperatureImage = ''; // No image if temperature is unavailable
            heartRateImage= '';
        }


      // Check if a row for this worker already exists
let tr = document.querySelector(`tr[data-worker-id="${worker.ID}"]`);

if (!tr) {
    // If it doesn't exist create a new row
    tr = document.createElement('tr');
    tr.setAttribute('data-worker-id', worker.ID); // Set a data attribute to identify the row

    supervisorId = <?php echo json_encode($_SESSION['record']); ?>

    let supervisorRef = firebase.database().ref(`supervisors/${supervisorId}/workerIDs`);
    supervisorRef.once('value', function(snapshot) {

        worker_ids = snapshot.val();
        let worker_ids_arr = worker_ids.split(',').map(item => parseInt(item.trim())); // Split the string by comma and remove any leading/trailing whitespace

        if(!worker_ids_arr.includes(parseInt(worker.ID)))
        {
             tr.style.display = 'none';

        }
        else{
        }
    });

    // Create cells for ID, name, status, temperature, and heart rate
    const idCell = document.createElement('td');
    idCell.textContent = worker.ID;
    const nameCell = document.createElement('td');
    nameCell.textContent = worker.name;

    // Create status cell and its content
    const statusCell = document.createElement('td');
    statusCell.className = 'status-cell';
    statusCell.style.backgroundColor = color;
    const toggleIcon = document.createElement('i');
    toggleIcon.className = "fa-solid fa-plus";
    toggleIcon.onclick = function () { toggleOptions(this); };
    statusCell.appendChild(toggleIcon);

    // Add the options div
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'options';
    optionsDiv.style.display = 'none';
    optionsDiv.innerHTML = `<p>HeartRate: <span class="heart-rate">${heartRate}</span></p>
                            <p>Temperature: <span class="temperature">${temperature}</span></p>`;
    statusCell.appendChild(optionsDiv);

    // Create cells for temperature and heart rate
    const temperatureCell = document.createElement('td');
    const temperatureImg = document.createElement('img');
    temperatureImg.className = 'temperature-image';
    temperatureImg.src = temperatureImage;
    temperatureCell.appendChild(temperatureImg);

    // Append the row to the table body
    tr.appendChild(idCell);
    tr.appendChild(nameCell);
    tr.appendChild(statusCell);
    tr.appendChild(temperatureCell);

    // Append the row to the table body
    tableBody.appendChild(tr);
} else {
    // If the row exists update the status color and the values
    const statusCell = tr.querySelector('.status-cell');
    const heartRateSpan = tr.querySelector('.heart-rate');
    const temperatureSpan = tr.querySelector('.temperature');
    const temperatureImg = temperatureSpan.querySelector('.temperature-image');
    const heartRateImg = heartRateSpan.querySelector('.heart-rate-image');

    statusCell.style.backgroundColor = color;
    heartRateSpan.textContent = heartRate;
    temperatureSpan.textContent = temperature;
    temperatureImg.src = temperatureImage;
    heartRateImg.src = heartRateImage;    

  
}


    });
}

database.on('value', snapshot => {

        populateTable(snapshot);

});

supervisorId = <?php echo json_encode($_SESSION['record']); ?>

  let supervisorRef = firebase.database().ref(`supervisors/${supervisorId}/workerIDs`);
  supervisorRef.once('value', function(snapshot) {

    worker_ids = snapshot.val();
    let worker_ids_arr = worker_ids.split(',').map(item => parseInt(item.trim())); // Split the string by comma and remove any leading/trailing whitespace

  
});
</script>
</body>

</html>
