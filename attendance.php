<?php
session_start();

 if(!$_SESSION['role'] == 'supervisor')
 {
    header("location: preLogin.php");
 }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Worker System</title>
    
    
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

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" />

<!-- site metas -->
<title>SMART HELMET</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">
<style>
    .table-custom {
        background-color: white; /* Sets background color for the whole table */
    }

    .table-custom thead th {
        background-color: white; /* Ensures the header cells are also white */
        color: black; /* Sets text color to black for contrast */
    }

    .table-custom tbody td {
        background-color: white; /* Ensures the body cells are also white */
        color: black; /* Optional: Change text color if needed */
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
    <h1 style="color: blanchedalmond; text-align: center; font-size: 50px; position: relative; top: 0.5em;"> Worker System </h1>
    
    <form id="filterForm">
      
        <label for="nameFilter" style="color: antiquewhite;position: relative; top: 2em;">Filter by Name:</label>
        <input type="text" id="nameFilter" name ="nameFilter" style="width: 15%; height: 10%; position: relative; top: 2em;"> 
        <h1 style="color: antiquewhite; position: relative; top: 1em;"> OR </h1>
        
        <label for="dateFilter" style="color: antiquewhite;position: relative; top: 1em;">Filter by Date:</label>
        <input type="date" id="dateFilter" name="dateFilter" style="position: relative; top: 1em;"> <br> <br>
        <button type="button" onclick="resetFilters()" style="background-color: rgb(247, 246, 250); width: 10%; position: relative; top: 1em;">resetFilters</button>
        <button type="button" onclick="filterResults()" style="background-color: rgb(247, 246, 250); width: 10%;position: relative; top: 1em;">Filter</button>
    </form> <br>

    <table class="table table-bordered table-striped table-custom">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Attendance Date</th>
            <th>Attendance Status</th>
        </tr>
    </thead>
    <tbody id="attendanceTableBody">
          
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

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database-compat.js"></script>
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
   const database = firebase.database();
   const workersRef = database.ref('workers');
   const sensorsRef = database.ref('Sensor');

   supervisorId = <?php echo json_encode($_SESSION['record']); ?>

   
   // Function to update or add data to the table
   function updateAttendanceTable(workerData, sensorData) {
       const tableBody = $('#attendanceTableBody');
       // Create a unique row id using worker ID and sensor ID
       const rowId = `worker-${workerData.ID}-sensor-${workerData.sensorID}`;
       let row = $('#' + rowId);
       if (row.length === 0) {
           // If the row does not exist, create it
           row = $(`
               <tr id="${rowId}">
                   <td>${workerData.name}</td>
                   <td>${workerData.ID}</td>
                   <td>${sensorData.AttendanceDate || 'No data'}</td>
                   <td>${sensorData.AttendanceStatus || 'No data'}</td>
               </tr>
           `);
           tableBody.append(row);
       } else {
           // Update the existing row
           row.html(`
               <td>${workerData.name}</td>
               <td>${workerData.ID}</td>
               <td>${sensorData.AttendanceDate || 'No data'}</td>
               <td>${sensorData.AttendanceStatus || 'No data'}</td>
           `);
       }
   }
   
   database.ref(`supervisors/${supervisorId}/workerIDs`).once('value', (snapshot) => {
      const workerIDs = snapshot.val();

      // Real-time listener for workers and their corresponding sensor data
      workersRef.on('value', (workersSnapshot) => {
               workersSnapshot.forEach((workerSnapshot) => {
               const workerData = workerSnapshot.val();
               const workerId = workerData.ID;
               
               if(workerIDs.includes(workerId))
               {
                  sensorsRef.child(workerData.sensorID).on('value', (sensorSnapshot) => {
                     updateAttendanceTable(workerData, sensorSnapshot.val());
                  });
               }

         });
      });
      
   });


   
   function filterResults() {
       const nameFilter = $('#nameFilter').val().toLowerCase();
       const dateFilter = $('#dateFilter').val();
       $('#attendanceTableBody tr').each(function() {
           const name = $(this).find('td:nth-child(1)').text().toLowerCase();
           const date = $(this).find('td:nth-child(3)').text();
           $(this).toggle((nameFilter === '' || name.includes(nameFilter)) && (dateFilter === '' || date === dateFilter));
       });
   }
   
   function resetFilters() {
       $('#nameFilter').val('');
       $('#dateFilter').val('');
       $('#attendanceTableBody tr').show();
   }
   </script>
   </body>
</html>