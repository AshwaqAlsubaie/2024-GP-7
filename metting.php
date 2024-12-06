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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures responsiveness -->
    <title>Meeting Booking - SMART HELMET</title>
        <!-- External Stylesheets -->
    <link rel="stylesheet" href="Css/VitalStyle.css">
    <link rel="stylesheet" href="Css/commCss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #283048, #859398);
            color: white;
        }

        .full_bg, .header {
            background: linear-gradient(to right, #283048, #859398); /* Matches body background */
            color: white;
            padding-bottom: 15px;
        }

        h1 {
            text-align: center;
            font-size: 3em;
            margin-top: 30px;
            color: #ffe8a1; /* Gold color for better emphasis */
            text-shadow: 2px 2px 5px #000; /* Shadow for better contrast */
        }

        .booking-card {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="date"],
        input[type="time"],
        input[type="number"] {
           font-family: Arial, sans-serif; /* A font that looks good in English */
           direction: ltr; /* Force left-to-right direction */
           text-align: left; /* Ensure numbers are aligned to the left */
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: #005b9a;
            color: #ececf6;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #d4af37; /* Golden color on hover */
        }

        .form-group {
            margin-bottom: 1.5em;
        }

        .truck {
            margin-top: 50px;
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
        
    .meeting-item {
        padding: 15px 10px;
        border-bottom: 1px solid #ccc; /* الخط الفاصل */
    }

    .meeting-item:last-child {
        border-bottom: none; /* إزالة الخط من آخر عنصر */
    }

    .booked-meetings-container {
        margin-top: 20px;
    }

    .meeting-item p {
        margin: 5px 0; /* مسافة بين الفقرات داخل العنصر */
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
      
        <?php include "navbar.php" ?>
         <!-- end header inner -->
         <!-- end header -->
         <!-- banner -->
    <!-- Meeting Booking Section -->
    <h1>Meeting Booking</h1>
<div class="booking-card">
    <form id="meeting-form" action="submit-meeting.php" method="POST">
        <div class="form-group">
            <label for="meeting-date">Meeting Date:</label>
            <input type="date" id="meeting-date" name="meeting_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="meeting-time">Meeting Time:</label>
            <input type="time" id="meeting-time" name="meeting_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="meeting-duration">Meeting Duration (in minutes):</label>
            <input type="number" id="meeting-duration" name="meeting_duration" min="15" max="120" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="workers">Select Workers to Invite:</label>
            <div id="workers-list">
                <!-- This div will be populated dynamically with worker checkboxes -->
            </div>
        </div>

        <!-- Hidden input to store selected emails -->
        <input type="hidden" id="selected-emails" name="selected_emails">

        <div class="form-group text-center">
            <input type="submit" value="Book Meeting" class="btn btn-primary">
        </div>
    </form>
</div>

<!-- Section to display booked meetings -->
<div class="booking-card">
    <h2>Booked Meetings</h2>
    <div id="booked-meetings-list">
        <!-- Booked meetings will be displayed here -->
    </div>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
    import { getDatabase, ref as databaseRef, get } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-database.js";

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

    const app = initializeApp(firebaseConfig);
    const database = getDatabase(app);

    const supervisorId = "<?php echo $_SESSION['record']; ?>";
    console.log("Supervisor ID:", supervisorId);

    const supervisorRef = databaseRef(database, 'supervisors/' + supervisorId);
    get(supervisorRef).then((snapshot) => {
        const supervisorData = snapshot.val();
        console.log("Supervisor Data:", supervisorData);

        if (supervisorData && supervisorData.workerIDs) {
            const workerIDs = supervisorData.workerIDs.split(',');
            const workersListDiv = document.getElementById('workers-list');

            const allWorkersRef = databaseRef(database, 'workers');
            get(allWorkersRef).then((workersSnapshot) => {
                const allWorkersData = workersSnapshot.val();
                console.log("All Workers Data:", allWorkersData);

                workerIDs.forEach(workerId => {
                    let matchedWorker = null;
                    for (let workerKey in allWorkersData) {
                        if (allWorkersData[workerKey].ID == workerId) {
                            matchedWorker = allWorkersData[workerKey];
                            break;
                        }
                    }

                    if (matchedWorker) {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'workers[]';
                        checkbox.value = matchedWorker.email; // Pass email directly
                        checkbox.id = workerId;

                        const label = document.createElement('label');
                        label.htmlFor = workerId;
                        label.appendChild(document.createTextNode(matchedWorker.name));

                        const div = document.createElement('div');
                        div.className = 'form-check';
                        div.appendChild(checkbox);
                        div.appendChild(label);

                        workersListDiv.appendChild(div);
                    } else {
                        console.error("No worker data found for worker ID:", workerId);
                    }
                });
            }).catch((error) => {
                console.error('Error fetching all workers data:', error);
            });
        } else {
            console.error("No worker IDs found for this supervisor");
        }
    }).catch((error) => {
        console.error('Error fetching supervisor data:', error);
    });

    // Handle form submission
    document.getElementById('meeting-form').addEventListener('submit', (e) => {
        const selectedEmails = Array.from(
            document.querySelectorAll('input[name="workers[]"]:checked')
        ).map((checkbox) => checkbox.value);

        if (selectedEmails.length === 0) {
            e.preventDefault();
            alert('Please select at least one worker.');
            return;
        }

        // Set selected emails into hidden input
        document.getElementById('selected-emails').value = JSON.stringify(selectedEmails);
    });

    // Fetch booked meetings from supervisors node
    const supervisorMeetingsRef = databaseRef(database, `supervisors/${supervisorId}/meetings`);
    const bookedMeetingsListDiv = document.getElementById('booked-meetings-list');

    get(supervisorMeetingsRef).then((snapshot) => {
        const meetingsData = snapshot.val();
        if (meetingsData) {
            for (const meetingId in meetingsData) {
                const meeting = meetingsData[meetingId];
                const meetingDiv = document.createElement('div');
                meetingDiv.className = 'meeting-item';
                meetingDiv.innerHTML = `
                    <p><strong>Date:</strong> ${meeting.date}</p>
                    <p><strong>Time:</strong> ${meeting.time}</p>
                    <p><strong>Duration:</strong> ${meeting.duration} minutes</p>
                `;
                bookedMeetingsListDiv.appendChild(meetingDiv);
            }
        } else {
            bookedMeetingsListDiv.innerHTML = '<p>No meetings booked yet.</p>';
        }
    }).catch((error) => {
        console.error('Error fetching booked meetings:', error);
    });
</script>

    
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
