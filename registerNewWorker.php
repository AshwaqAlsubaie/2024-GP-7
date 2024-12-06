<?php
session_start();


$jsonString = file_get_contents('database.json');

 if(!$_SESSION['role'] == 'admin')
 {
    header("location: preLogin.php");
 }
 $shifts = fetchShiftsSettings();
 

 function fetchShiftsSettings() {
     $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/shifts-settings.json';
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $response = curl_exec($ch);
     curl_close($ch);
     
     // Decode the JSON response into an associative array
     $shifts = json_decode($response, true);
 
     return $shifts;
 }
?>
<!DOCTYPE html>
<html>
<head>
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
<link rel="stylesheet" href="css/VitalStyle.css">
<link rel="stylesheet" href="css/commCss.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responsive.css">
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

    <!-- mobile metas -->
<meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures responsiveness -->
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


  <!-- end header inner -->
  <!-- end header -->
  <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="adminPage.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Register New Worker</li>
                </ol>
            </nav>
        </div>
 <br /><br />
  <h1 style="color: rgb(248, 242, 232); text-align: center; font-size: 50px;position: relative; top: 0.5em;"> Register New Worker</h1>  

  
  <div class="form-container">
   <form method="post" onsubmit="return validateForm();">
       <div class="form-field">
         <label for="name" style="color: rgb(255, 255, 255);">Name:</label>
         <input type="text" id="name" name="name" placeholder="Smith" required>
       </div>
       <div class="form-field">
         <label for="name" style="color: rgb(255, 255, 255);">Shift:</label>
         <select class="form-control" id="shift" name="shift" required>
            <option value="" hidden selected >Select a shift</option>
            <?php foreach($shifts as $key => $shift): ?>
                <option value="<?= $key ?>"><?= $shift['shiftName'] ?></option>
            <?php endforeach; ?>
         </select>
       </div>
       <div class="form-field">
         <label for="email" style="color: rgb(255, 255, 255);">Email:</label>
           <input class="form-control" type="email" id="email" name="email" placeholder="someone@example.com" required>
       </div>
       <div class="form-field">
         <label for="phone" style="color: rgb(255, 255, 255);">Phone:</label>
           <input class="form-control" type="phone" id="phone" name="phone" placeholder="+9665xxxxxxxx" required>
       </div>
     </div>

     <input type="submit" value="Register Worker">
   </form>
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
<script>
function validateForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    // Email regex pattern
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Phone regex pattern (e.g., Saudi number format: +9665xxxxxxxx)
    const phonePattern = /^\+9665\d{8}$/;
    // Name regex pattern (only letters allowed)
    const namePattern = /^[A-Za-z\s]+$/;

    if (!namePattern.test(name)) {
        alert('Name can only contain letters and spaces.');
        return false;
    }

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert('Please enter a valid Saudi phone number (+966 5xxxxxxxx).');
        return false;
    }

    return true;
}

</script>

<?php

// Function to register a worker
function registerWorker() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $name = $_POST['name'];
        $shift = $_POST['shift'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

         // Server-side validation for name (only letters allowed)
        if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
            echo "<script>alert('Name can only contain letters and spaces.');</script>";
            return;
        }
         // Server-side validation for email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format!');</script>";
            return;
        }

        // Saudi phone number pattern: +9665xxxxxxxx
        if (!preg_match('/^\+9665\d{8}$/', $phone)) {
            echo "<script>alert('Invalid phone number format! It should be +966 5xxxxxxxx.');</script>";
            return;
        }

        // Prepare data for Firebase
        $uniqueKey = getNextAvailableKey(); // Get the next available key
        if (!$uniqueKey) {
            echo "<script>alert('Failed to determine unique key!');</script>";
            return;
        }
        $id = $uniqueKey + 100;
        $postData = [
            'ID' => "$id",
            'shift' => $shift,
            'name' => $name,
            'email' => $email,
            'phoneNumber' => $phone,
            'sensorID' => 'Sensors' . $uniqueKey // Just an example, you may want to adjust this
        ];

        if (emailExists($email)) {
            echo "<script>alert('Email already exists!');</script>";
            return;  // Stop further execution if the email already exists
        }

          // Check if phone number exists
        if (phoneExists($phone)) {
            echo "<script>alert('Phone number already exists!');</script>";
            return;  // Stop further execution if the phone number already exists
        }

        // Post data to Firebase
        if (postToFirebase($uniqueKey, $postData)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Failed to register!');</script>";
        }
    }
}

// Function to get the next available worker key
function getNextAvailableKey() {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        $existingKeys = array_keys($data);
        $existingNumbers = array_map(function($key) {
            return (int)substr($key, 6); // Extract the number part from 'workerX'
        }, $existingKeys);
        
        $nextNumber = max($existingNumbers) + 1;
        return $nextNumber;
    }
    return false;
}

function emailExists($email) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers.json';
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

// Function to check if phone number already exists
function phoneExists($phone) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $data = json_decode($response, true);
        foreach ($data as $key => $value) {
            if (isset($value['phoneNumber']) && $value['phoneNumber'] === $phone) {
                return true;
            }
        }
    }
    return false;
}

// Function to post data to Firebase with a specific key
function postToFirebase($key, $data) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers/' . "worker" . $key . '.json'; // Use specific key
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Use PUT to set specific key
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}


// Call the function to register worker
registerWorker();
?>

?>


