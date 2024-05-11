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
<title>smart helmet </title>
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

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" />

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



<div class="wrapper"style="position: relative; top: 5em;" >
  <header style="background-color: #CCE3EB;">
  <p>Date and Time: <span id="currentDateTime"></span></p>
</header>
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
<th>Location</th>
</tr>
</thead>
<tbody id="tableBody">        
</tbody>
 </table>
</div>
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

  // Function to append a worker to the table
  function appendWorkerToTable(worker) {
    const tr = document.createElement('tr');
    if(worker.name.trim() !== "")
	{
		tr.innerHTML = `
		  <td>${worker.ID}</td>
		  <td>${worker.name}</td>
		  <td>
		  </td>
		`;
	}
    tableBody.appendChild(tr);
  }

  // Initial loading of data
  database.on('value', snapshot => {
    populateTable(snapshot);
  });

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

<script>

firebase.auth().onAuthStateChanged(function(user) {
    if (!user) {
      // User is not signed in.
      window.location.href = 'html/login_page.html'; // Redirect to the login page
    }
  });

         function logout() {
           firebase.auth().signOut().then(function() {
             // Sign-out successful.
             window.location.href = 'html/login_page.html';
           }).catch(function(error) {
             // An error happened.
             console.error("Sign out error", error);
           });
         }
       
</script>

</body>

</html>

