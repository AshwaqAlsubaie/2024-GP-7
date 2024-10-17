<?php
session_start();

if (!$_SESSION['role'] == 'supervisor') {
    header("location: preLogin.php");
    exit;
}

// Pass the supervisor ID from session to JavaScript
$supervisorId = $_SESSION['record'];
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

      /* General container settings */
  .container {
      width: 100%;
      padding: 20px;
  }

  /* Flexbox centering */
  .record-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: auto;
      margin: 0 auto;
  }

  .record-container, .upload-container {
      margin: 15px 0;
      display: flex;
      justify-content: center;
  }

  .audio-player-container {
      margin: 20px 0;
      display: flex;
      justify-content: center;
      width: 100%;
  }

  .record-btn {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
      margin: 5px;
  }
  
  .record-btn i {
      margin-right: 8px;
  }

  .record-btn:disabled {
      background-color: #ccc;
      cursor: not-allowed;
  }

  .record-btn:hover:not(:disabled) {
      background-color: #45a049;
  }

  audio {
      width: 300px; /* Adjust the width of the audio player */
      outline: none;
      border-radius: 5px;
  }
    </style>
   </head>
   <!-- body -->
   <body class="main-layout">
       
    <!-- Add PHP to pass supervisorId -->
<script type="text/javascript">
    const supervisorId = <?php echo json_encode($supervisorId); ?>;
</script>

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
    <div class="record-section">
        <div class="record-container">
            <button id="startBtn" class="record-btn">
                <i class="fa fa-microphone"></i> Start Recording
            </button>
            <button id="stopBtn" class="record-btn" disabled>
                <i class="fa fa-stop-circle"></i> Stop Recording
            </button>
        </div>
        <div class="audio-player-container">
            <audio id="audioPlayer" controls></audio>
        </div>
        <div class="upload-container">
            <button id="uploadBtn" class="record-btn" disabled>
                <i class="fa fa-cloud-upload"></i> Upload Audio
            </button>
        </div>
    </div>
</div>
         
<script type="module">
// Import Firebase SDKs
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-storage.js";
import { getDatabase, ref as databaseRef, set, push, get } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

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
let mp3Blob;

// Start recording
document.getElementById('startBtn').addEventListener('click', () => {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            audioChunks = []; // Reset the audio chunks for a new recording
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            document.getElementById('stopBtn').disabled = false;
            document.getElementById('startBtn').disabled = true;
        });
});

// Stop recording and convert to MP3
document.getElementById('stopBtn').addEventListener('click', async () => {
    mediaRecorder.stop();

    mediaRecorder.onstop = async () => {
        audioBlob = new Blob(audioChunks, { type: 'audio/webm' });

        // Check if ffmpeg is already loaded
        if (!ffmpeg.isLoaded()) {
            await ffmpeg.load();
        }

        // Convert to MP3 using ffmpeg.js
        ffmpeg.FS('writeFile', 'input.webm', await fetchFile(audioBlob));
        await ffmpeg.run('-i', 'input.webm', 'output.mp3');
        const mp3Data = ffmpeg.FS('readFile', 'output.mp3');
        mp3Blob = new Blob([mp3Data.buffer], { type: 'audio/mp3' });

        // Update audio player with the MP3
        audioUrl = URL.createObjectURL(mp3Blob);
        document.getElementById('audioPlayer').src = audioUrl;
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('stopBtn').disabled = true;
    };
});


// Upload the recorded audio and send it to all workers
document.getElementById('uploadBtn').addEventListener('click', () => {
    if (!mp3Blob) {
        alert('No audio recorded!');
        return;
    }

    // Get worker IDs for the supervisor
    const supervisorRef = databaseRef(database, 'supervisors/' + supervisorId);

    
    get(supervisorRef).then(snapshot => {
        if (snapshot.exists()) {
            const supervisorData = snapshot.val();
            const workerIDs = supervisorData.workerIDs.split(','); 

            // Loop through workerIDs and upload the audio for each worker
            workerIDs.forEach(workerId => {
                const audioStorageRef = storageRef(storage, 'audios/' + Date.now() + '.mp3');

                uploadBytes(audioStorageRef, mp3Blob).then(snapshot => {
                    getDownloadURL(snapshot.ref).then(downloadURL => {
                        const messagesRef = push(databaseRef(database, 'messages/' + workerId + '/message'));
                        const currentTimestamp = new Date();
                        const readableDate = currentTimestamp.toLocaleString('en-US');

                        // Save the message to each worker's node
                        set(messagesRef, {
                            audio_url: downloadURL,
                            timestamp: readableDate
                        });

                        console.log('Audio sent to worker:', workerId);
                    });
                }).catch(error => {
                    console.error("Error uploading audio:", error);
                });
            });

            alert('Audio uploaded to all workers successfully!');
            document.getElementById('uploadBtn').disabled = true;
            document.getElementById('startBtn').disabled = false; // Re-enable the start button for a new recording
            audioChunks = []; // Clear the audioChunks for the next recording
        } else {
            console.error("No data available for supervisor");
        }
    }).catch(error => {
        console.error("Error fetching supervisor data:", error);
    });
});
</script>

   </header>
   <!-- end banner -->

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
