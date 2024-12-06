<?php
session_start();

if(!$_SESSION['role'] == 'supervisor')
{
   header("location: preLogin.php");
}
$supervisorId = json_encode($_SESSION['record']); // Encode supervisor ID for JavaScript usage
?>
<!DOCTYPE html>

<html lang="en">

<head>

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>SMART HELMET</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <!-- style css -->
   <link rel="stylesheet" href="css/style.css">
   <!-- Responsive-->
   <link rel="stylesheet" href="css/responsive.css">
   <!-- fevicon -->
   <link rel="icon" href="images/fevicon.png" type="image/gif" />
   <!-- Scrollbar Custom CSS -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <!-- Tweaks for older IEs-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

   <!-- <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css"> -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
      media="screen">
   <style>
   .about-header {
      font-size: 2.5em;
      font-weight: bold;
      color: #005b9a; /* Updated color to make it more striking */
      text-align: left;
      margin-bottom: 15px;
   }

   .about-description {
      font-size: 1.1em;
      line-height: 1.6;
      color: #555;
   }

   .about-img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
   }


   .send-voice-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 100px;
      height: 100px;
      background-color: #ffeeba;
      border-radius: 50%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      transition: transform 0.3s ease;
   }

   .send-voice-container:hover {
      transform: scale(1.1);
      background-color: #ffdf80; /* Brighten the background on hover */
   }

   #voice-box {
      text-align: center;
   }

   .mic-icon {
      width: 50px;
      height: 50px;
      margin-bottom: 5px;
      transition: transform 0.3s ease;
   }

   .send-voice-container:hover .mic-icon {
      transform: rotate(10deg); /* Add slight rotation effect on hover */
   }


   /* Adding spacing to make the About section stand out */
   .about {
      margin-top: 60px;
      margin-bottom: 60px;
   }

   .container {
      padding: 20px;
   }
   </style>

   <script>
   const supervisorId = <?php echo $supervisorId; ?>;
   </script>
   <!-- Firebase scripts -->
   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
   <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>
   <!-- <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
   <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-storage.js"></script>
   <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-database.js"></script> -->
   <script src="js/firebaseConfig.js"></script> <!-- Include Firebase config -->
   <script src="js/notification.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<!-- body -->

<body class="main-layout">


   <!-- header -->
   <header class="full_bg">
      <!-- header inner -->
         <?php include 'navbar.php'; ?> 
      
      <!-- end header inner -->
      <!-- end header -->
      <!-- banner -->

      <div class="send-voice-container" title="Send voice announcement to your workers">
    <a href="Record.php">
        <div id="voice-box" class="text_align_center">
            <i><img src="images/mic-icon.png" alt="Send Voice Icon" class="mic-icon" /></i>
           
        </div>
    </a>
</div>


      <script>

      const db = firebase.database();
      // const storage = firebase.storage();
      const attendanceSettingsRef = db.ref('attendance-settings');

      window.onload = function() {
         // Fetch the settings from Firebase
         attendanceSettingsRef.once('value', (snapshot) => {
               const settings = snapshot.val();

               if (settings) {
                  // Populate the form fields with data from Firebase
                  document.getElementById('beginningTime').value = settings.beginningTime || '';
                  document.getElementById('leavingTime').value = settings.leavingTime || '';
                  document.getElementById('delayTime').value = settings.delayTime || '';
                  document.getElementById('absentTime').value = settings.absentTime || '';
               }
         }).catch((error) => {
               console.error("Error fetching settings:", error);
         });
      };

   
      </script>
      <section class="banner_main">
         <div id="myCarousel" class="carousel slide banner" data-ride="carousel">
               <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                  <li data-target="#myCarousel" data-slide-to="2"></li>
               </ol>
               <div class="carousel-inner">
                  <div class="carousel-item active">
                     <div class="container">
                           <div class="carousel-caption  banner_po">
                              <div class="row">
                                 <div class="col-md-9">
                                       <div class="build_box">

                                       </div>
                                 </div>
                              </div>
                           </div>
                     </div>
                  </div>
               </div>

         </div>
      </section>
   </header>
   <!-- end banner -->
   <!-- four_box -->
   <div class="three_box">
      <div class="container">
         <div class="row">
               <div class="col-md-3">
                  <a href="vital signs.php">
                     <div id="text_hover" class="const text_align_center">
                           <i><img src="images/ser1.png" alt="#" /></i>
                           <span>Safety Monitoring</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="attendance.php">
                     <div id="text_hover" class="const text_align_center">
                           <i><img src="images/ser2.png" alt="#" /></i>
                           <span>ATTENDANCE</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="location.php">
                     <div id="text_hover" class="const text_align_center">
                           <i> <img src="images/ser3.png" alt="#" /></i>
                           <span> SITE MONITORING</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="metting.php">
                     <div id="text_hover" class="const text_align_center">
                           <i><img src="images/ser2.png" alt="#" /></i>
                           <span>BOOK A MEETING</span>
                     </div>
                  </a>
               </div>
         </div>
      </div>
   </div>
   <!-- end four_box -->
 <!-- Time Icon Button -->



    <!-- about -->
<div class="about">
   <div class="container-fluid">
      <div class="row d_flex">
         <div class="col-md-7">
            <div class="titlepage">
               <h2 class="about-header">About Our Website</h2>
               <p class="about-description">
                  Our website provides you with the ease of monitoring your workers, whether in terms of
                  their health condition, their attendance, their locations, or booking a meeting with
                  them.
               </p>
            </div>
         </div>
         <div class="col-md-5">
            <div class="about_img">
               <figure>
                  <img src="images/about.png" alt="about img" class="about-img" />
               </figure>
            </div>
         </div>
      </div>
   </div>
</div>
   <!-- end about -->



   <!-- truck movment-->
   <div class="truck">
      <div class="container-fluid">
         <div class="row">
               <div class="col-md-6 jkhgkj">
                  <div class="truck_img1">
                     <img src="images/truck.png" alt="#" />
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="truck_img1">
                     <img src="images/jcb.png" alt="#" />
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
