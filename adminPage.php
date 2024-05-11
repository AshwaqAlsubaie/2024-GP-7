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
                               <img class="nav-img" src="img/Screenshot_2024-02-16_160751-removebg-preview.png" alt="logo"/>
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

  <h1 style="color: rgb(248, 242, 232); text-align: center; font-size: 50px;position: relative; top: 0.5em;"> Register New Supervisor</h1>  

  
  <div class="form-container">
   <form method="post">
       <div class="form-field">
         <label for="name" style="color: rgb(255, 255, 255);">Name:</label>
         <input type="text" id="name" name="name" required>
       </div>
       <div class="form-field">
         <label for="email" style="color: rgb(255, 255, 255);">Email(in Gmail):</label>
           <input type="email" id="email" name="email" required>
       </div>
       <div class="form-field">
            <label for="confirm_email" style="color: rgb(255, 255, 255);">Confirm Email:</label>
            <input type="email" id="confirm_email" name="confirm_email" required onpaste="return false" oncopy="return false">
        </div>

       <div class="form-field">
         <label for="workers" style="color: rgb(255, 255, 255);">Assigning workers:</label>
         <select id="workerIds" required>
             <?php
             // Decode JSON string to PHP array
             $data = json_decode($jsonString, true);
     
             // Iterate through workers and populate datalist options
             foreach ($data['workers'] as $worker) {
                 echo "<option value='" . $worker['ID'] . "'> " . $worker['ID'] . "</option>";
             }
             ?>
         </select>
         <button class="addWorkerBtn" type="button" onclick="addWorker()">Add</button>
         <div id="selectedWorkers"></div>
         <input type="hidden" name="worker_ids" id="workerIdsHidden">
     </div>

     <input type="submit" value="Register Supervisor">
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


