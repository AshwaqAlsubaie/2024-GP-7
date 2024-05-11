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
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      
   </head>
   <!-- body -->
   <body class="main-layout">
    

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
                                      <a href="index.php" ><img class="nav-img" src="img/Screenshot_2024-02-16_160751-removebg-preview.png" alt="#"/></a>
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
         <!-- banner -->
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
                  <div id="text_hover"  class="const text_align_center">
                     <i><img src="images/ser1.png" alt="#"/></i>
                     <span>VITAL SIGNS</span>
                  </div>
               </a>
               </div>

               <div class="col-md-3">
                  <a href="attendance.php">
                  <div id="text_hover" class="const text_align_center">
                     <i><img src="images/ser2.png" alt="#"/></i>
                     <span>ATTENDANCE</span>
                  </div>
               </a>
               </div>

               <div class="col-md-3">
                  <a href="location.php">
                  <div id="text_hover" class="const text_align_center">
                     <i> <img src="images/ser3.png" alt="#"/></i>
                     <span> SITE MONITORING</span>
                  </div>
               </a>
               </div>

               <div class="col-md-3">
                  <a href="metting.php">
                  <div id="text_hover" class="const text_align_center">
                     <i><img src="images/ser2.png" alt="#"/></i>
                     <span>BOOK A MEETING</span>
                  </div>
               </a>
               </div>
            </div>
         </div>
      </div>
      <!-- end four_box -->

      <!-- about -->
      <div class="about">
         <div class="container-fluid">
            <div class="row d_flex">
               <div class="col-md-7">
                  <div class="titlepage">
                     <h2>About Our Website   </h2>
                     <span>Our website provides you with the ease of monitoring your workers, whether in terms of their health condition, their attendance, their locations, or booking a meeting with them.</span>
                   
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="about_img">
                     <figure><img src="images/about.png" alt=" about img"/></figure>
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