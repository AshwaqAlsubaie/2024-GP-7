<?php
session_start();


$jsonString = file_get_contents('database.json');

 if(!$_SESSION['role'] == 'admin')
 {
    header("location: preLogin.php");
 }
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures responsiveness -->
  <title> Admin page</title>
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


input[type="submit"] {
  margin-bottom: 10px;
}

input[type="submit"] {
  background-color: #2e5ff2;
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


        .form-container {
            display: flex;
            flex-direction: column;
            width: 300px; 
            margin: auto; 
        }
        .form-field {
            margin-bottom: 20px; /* Space between fields */
        }
        input[type="text"], input[type="email"], input[type="password"]{
            width: 100%; 
        }
        select{
         width: 80%;
        }
        .addWorkerBtn{
          padding: 3px 8px;
          border: 1px solid black;
          border-radius: 50px;
        }
        .badge{
          background-color: #f1f1f1;
          color: black;
        }
        /* Responsive Design */
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
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">
  <!-- Firebase scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
   <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>
<script src="js/firebaseConfig.js"></script> <!-- Include Firebase config -->
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
  <!-- header -->
 <header class="full_bg">
   <!-- header inner -->
  <?php include "navbar.php" ?>
  <?php
         if($_SESSION['role'] == 'admin')
         {
            // echo <<< _EOF
            // <div type="button" class="btn btn-primary float-right mr-2" data-toggle="modal" data-target="#timeSettingsModal">
            // <i class="fa fa-clock-o"></i> Set Attendance
            // </div>
            // _EOF;
         }
      ?>


  <!-- end header inner -->
  <!-- end header -->
 
 


 <!-- four_box -->
 <div class="three_box" style="margin-top: 20vh">
      <div class="container">
         <div class="row">
               <div class="col-md-3">
                  <a href="registerNewWorker.php">
                     <div id="text_hover" class="const text_align_center">
                           <i class="fa fa-user-plus text-dark" style="font-size: 15vh;"></i>
                           <span>Register New Worker</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="registerNewSupervisor.php">
                     <div id="text_hover" class="const text_align_center">
                           <i class="fa fa-user-plus text-dark" style="font-size: 15vh;"></i>
                           <span>Register New Supervisor</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="shifts.php">
                     <div id="text_hover" class="const text_align_center">
                            <i class="fa fa-sync-alt text-dark" style="font-size: 15vh;"></i>
                           <span>Shifts</span>
                     </div>
                  </a>
               </div>

               <div class="col-md-3">
                  <a href="setTimeAttendance.php">
   
                     <div id="text_hover" class="const text_align_center" >
                           <i class="fa fa-clock text-dark" style="font-size: 15vh;"></i>
                           <span>Set Time Attendance</span>
                     </div>
                  </a>
               </div>
         </div>
         <br />
         <div class="row">
               <div class="col-md-3">
                  <a href="showWorkers.php">
                     <div id="text_hover" class="const text_align_center">
                           <i class="fa fa-users text-dark" style="font-size: 15vh;"></i>
                           <span>list of workers</span>
                     </div>
                  </a>
               </div>
               <div class="col-md-3">
               <a href="showsupervisors.php">
                     <div id="text_hover" class="const text_align_center">
                           <i class="fa fa-users text-dark" style="font-size: 15vh;"></i>
                           <span> list of Supervisors</span>
                     </div>
                  </a>
               </div>
         </div>
      </div>
   </div>
   <!-- end four_box -->
   
<?php
   if($_SESSION['role'] == 'admin')
   {
      echo <<<__EOF
      <!-- Modal Structure -->
      <div class="modal fade" id="timeSettingsModal" tabindex="-1" role="dialog" aria-labelledby="timeSettingsModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="timeSettingsModalLabel">Set Attendance Times</h5>
                      <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </a>
                  </div>
                  <div class="modal-body">
                      <form method="post" action="attendanceSettings.php">
                          <div class="form-group">
                              <label for="beginningTime">Allowed Beginning Time:</label>
                              <input type="time" class="form-control" id="beginningTime" name="beginningTime" required>
                          </div>
                          <div class="form-group">
                              <label for="leavingTime">Allowed Leaving Time:</label>
                              <input type="time" class="form-control" id="leavingTime" name="leavingTime" required>
                          </div>
                          <div class="form-group">
                              <label for="delayTime">Delay Time (minutes):</label>
                              <input type="number" class="form-control" id="delayTime" name="delayTime" required min="0">
                          </div>
                          <div class="form-group">
                              <label for="absentTime">Absent Time (Hours):</label>
                              <input type="number" class="form-control" id="absentTime" name="absentTime" required min="0">
                          </div>
                          <input type="submit" class="btn btn-primary" value="Save Settings">
                      </form>
                  </div>
              </div>
          </div>
      </div>
      __EOF;
   }


   ?>
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
    // to check the email and email conformation are matching
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var email = document.getElementById('email').value;
    var confirmEmail = document.getElementById('confirm_email').value;
    var emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/; // Regular expression for email

    // Check if email matches the regular expression
    if (!emailPattern.test(email)) {
        alert("Please enter a valid Gmail address.");
        event.preventDefault(); // Prevent form submission
        return;
    }

    // Check if both emails match
    if (email !== confirmEmail) {
        alert("The email addresses do not match.");
        event.preventDefault(); // Prevent form submission
    }
});
</script>


<script>
    var selectedWorkerIds = [];

    function addWorker() {
        var workerId = document.getElementById("workerIds").value;
        if (!selectedWorkerIds.includes(workerId)) {
            selectedWorkerIds.push(workerId);
            document.getElementById("selectedWorkers").innerHTML += "<span class='badge'>" + workerId + "</span> ";
        }
        document.getElementById("workerIdsHidden").value = selectedWorkerIds.join(",");
    }
</script>
</body>

</html>
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
<?php

// Function to register a supervisor
function registerSupervisor() {
    require_once 'sendMail.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $workerIds = $_POST['worker_ids']; 
        $password = generatePassword();
        $hashed_password = md5($password); 

        // Prepare data for Firebase
        $uniqueId = uniqid();
        $postData = [
            'ID' => $uniqueId,
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'workerIDs' => $workerIds
        ];
        if(emailExists($email)){
             echo "<script>alert('Email already exist!');</script>";
             return;  // Stop further execution if the email already exists
        }

        // Send registration email
        sendRegistrationEmail($email, $password);

        // Post data to Firebase
        if (postToFirebase($postData)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Failed to register!');</script>";
        }
    }
}

// Function to generate a random password
function generatePassword() {
    return bin2hex(random_bytes(8));
}

// Function to send email
function sendRegistrationEmail($email, $password) {
    $subject = 'Your Registration Details';
    $body = "<h3>Thank you for registering on our site.</h3>
             <p>Your temporary password is: <strong>$password</strong></p>
             <p>Please change your password after your first login.</p>
             <p>Sincerely,<br>Smart Helmet Team</p>";

    sendEmail($email, $subject, $body);
}

function emailExists($email) {
   $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response = curl_exec($ch);
   curl_close($ch);
   if ($response) {
      $data = json_decode($response, true);
        foreach ($data as $key => $value) {
            if (isset($value['email']) && $value['email'] === $email) {
                return true;
       }
     }
    }
    return false;
 }
// Function to post data to Firebase
function postToFirebase($data) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response !== false;
}

// Call the function to register supervisor
registerSupervisor();

?>
