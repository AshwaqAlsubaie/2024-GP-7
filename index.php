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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
      media="screen">
  <!-- Include FFmpeg.js from the CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.11.4/dist/ffmpeg.min.js"></script>

   <style>

       /* Record Button */
        #recordBtn {
            background-color: #f44336;
            color: white;
        }

        #recordBtn:active {
            background-color: #d32f2f;
            transform: scale(0.98);
        }

        /* Stop Button */
        #stopBtn {
            background-color: #ff9800;
            color: white;
        }

        #stopBtn:active {
            background-color: #f57c00;
            transform: scale(0.98);
        }

        /* Listen Button */
        #playBtn {
            background-color: #4caf50;
            color: white;
        }

        #playBtn:active {
            background-color: #388e3c;
            transform: scale(0.98);
        }

        /* Send Button */
        #sendBtn {
            background-color: #2196f3;
            color: white;
        }

        #sendBtn:active {
            background-color: #1976d2;
            transform: scale(0.98);
        }

        /* Disabled Button State */
        button:disabled {
            background-color: #bdbdbd;
            color: white;
            cursor: not-allowed;
        }

        /* Adding an animated microphone icon */
        button::before {
            content: "\1F3A4"; /* Microphone emoji */
            font-size: 18px;
            margin-right: 8px;
        }

        /* Hover Effect */
        button:hover:not(:disabled) {
            background-color: #555;
            color: #fff;
        }
    </style>
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
         
 <div class="container">
        <button id="startBtn">Start Recording</button>
        <button id="stopBtn" disabled>Stop Recording</button>
        <br><br>
        <audio id="audioPlayer" controls></audio>
        <br><br>
        <button id="uploadBtn" disabled>Upload Audio</button>
    </div>
         
    <script type="module">
    // Import Firebase SDKs
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
    import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-storage.js";
    import { getDatabase, ref as databaseRef, set, push } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";
   //  import { createFFmpeg, fetchFile } from 'https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@latest/dist/ffmpeg.min.js';
   //  import { createFFmpeg, fetchFile } from '.@ffmpeg/ffmpeg/dist/ffmpeg.min.js';


    // Your Firebase config
    const firebaseConfig = {
      apiKey: "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA",
      authDomain: "smart-helmet-database-affb6.firebaseapp.com",
      databaseURL: "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com",
      projectId: "smart-helmet-database-affb6",
      storageBucket: "gs://smart-helmet-database-affb6.appspot.com",
      messagingSenderId: "197707055105",
      appId: "1:197707055105:web:769e69f5808ab5f7bcc000",
      measurementId: "G-LCZ5V9S1B1"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const storage = getStorage(app);
    const database = getDatabase(app);
    const { createFFmpeg, fetchFile } = FFmpeg;

  // Initialize FFmpeg
  const ffmpeg = createFFmpeg({ log: true });

    let mediaRecorder;
    let audioChunks = [];
    let audioBlob;
    let audioUrl;
    let audio;

  

    document.getElementById('startBtn').addEventListener('click', () => {
      navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
          mediaRecorder = new MediaRecorder(stream);
          mediaRecorder.start();

          mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
          };

          document.getElementById('stopBtn').disabled = false;
          document.getElementById('startBtn').disabled = true;
        });
    });

    let mp3Blob; // Declare mp3Blob globally

document.getElementById('stopBtn').addEventListener('click', async () => {
  mediaRecorder.stop();

  mediaRecorder.onstop = async () => {
    audioBlob = new Blob(audioChunks, { type: 'audio/webm' }); // Default format is webm/ogg

    // Convert to MP3 using ffmpeg.js
    await ffmpeg.load();
    ffmpeg.FS('writeFile', 'input.webm', await fetchFile(audioBlob));
    await ffmpeg.run('-i', 'input.webm', 'output.mp3');
    const mp3Data = ffmpeg.FS('readFile', 'output.mp3');
    mp3Blob = new Blob([mp3Data.buffer], { type: 'audio/mp3' }); // Assign value to mp3Blob

    // Generate the audio URL and update the audio player
    audioUrl = URL.createObjectURL(mp3Blob);
    audio = new Audio(audioUrl);

    document.getElementById('audioPlayer').src = audioUrl;
    document.getElementById('uploadBtn').disabled = false;
    document.getElementById('stopBtn').disabled = true;
  };
});

document.getElementById('uploadBtn').addEventListener('click', () => {
  if (!mp3Blob) {
    alert('No audio recorded!');
    return;
  }

  const audioStorageRef = storageRef(storage, 'audios/' + Date.now() + '.mp3');

  uploadBytes(audioStorageRef, mp3Blob).then(snapshot => {
    getDownloadURL(snapshot.ref).then(downloadURL => {
      // Save to Realtime Database
      const messagesRef = push(databaseRef(database, 'workers/worker1/messages'));
      set(messagesRef, {
        audio_url: downloadURL,
        timestamp: Date.now(),
        sender: 'supervisor1'
      });
      alert('Audio uploaded successfully!');
      document.getElementById('uploadBtn').disabled = true;
      document.getElementById('startBtn').disabled = false;
    });
  }).catch(error => {
    console.error("Error uploading audio:", error);
  });
});

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
                           <span>VITAL SIGNS</span>
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
                     <h2>About Our Website </h2>
                     <span>Our website provides you with the ease of monitoring your workers, whether in terms of
                           their health condition, their attendance, their locations, or booking a meeting with
                           them.</span>

                  </div>
               </div>
               <div class="col-md-5">
                  <div class="about_img">
                     <figure><img src="images/about.png" alt=" about img" /></figure>
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
