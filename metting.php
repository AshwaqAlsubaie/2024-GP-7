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
  <title>Book a Meeting</title>
  <style>
   body {
  font-family: Arial, sans-serif;
}

h2 {
  text-align: center;
  margin-top: 20px;
}

form {
  text-align: center;
  margin-top: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="date"],
input[type="time"],
input[type="number"],
input[type="submit"] {
  margin-bottom: 10px;
}

input[type="submit"] {
  background-color: #f0de65;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

input[type="submit"]:hover {
  background-color: #0e3e6d;
}

  </style>
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

  <h1 style="color: blanchedalmond; text-align: center; font-size: 50px;position: relative; top: 0.5em;"> Meeting Booking</h1>  

<form action="/submit-meeting" method="post">
  <label for="meeting-date"style="color: antiquewhite;" >Meeting Date:</label>
  <input type="date" id="meeting-date" name="meeting-date"><br><br>

  <label for="meeting-time"style="color: antiquewhite;" >Meeting Time:</label>
  <input type="time" id="meeting-time" name="meeting-time"><br><br>

  <label for="meeting-duration"style="color: antiquewhite;" >Meeting Duration (in minutes):</label>
  <input type="number" id="meeting-duration" name="meeting-duration" min="15" max="120"><br><br>

  <input type="submit" value="OK">
</form>
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