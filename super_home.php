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
        .button-link {
    display: inline-block;
    background-color: #f1f1f1; 
    color: black;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.button-link:hover {
    background-color: #0056b3; 
    color: white;
    text-decoration: none;
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

  <h1 style="color: rgb(248, 242, 232); text-align: center; font-size: 50px;position: relative; top: 0.5em;"> Re-change the password</h1>  

  
  <div class="form-container">

   <form method="post">
       <div class="form-field">
         <label for="password" style="color: rgb(255, 255, 255);">New password:</label>
         <input type="password" id="password" name="password" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must be at least 8 characters long and contain at least one letter, one number, and one special character">
       </div>

       <div class="form-field">
           <label for="confirm" style="color: rgb(255, 255, 255);">Confirm password:</label>
           <input type="password" id="confirm" name="confirm" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must be at least 8 characters long and contain at least one letter, one number, and one special character">
       </div>
       <div class="form-field">
           <a href="index.php" class="button-link">I already reset it, take me to the home page</a>
       </div>


       <input type="submit" value="Change Password">

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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Get form data
   $password = $_POST['password'];
   $confirm = $_POST['confirm'];

   if ($password !== $confirm) {
       echo "<script>alert('Password Do not Match!')</script>";
       exit();
   } else {
       $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
       $ch = curl_init($url);
       
       // Set cURL options for a GET request
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
       
       // Execute cURL session
       $response = curl_exec($ch);
       
       // Close cURL session
       curl_close($ch);
       
       // Decode JSON response
       $supervisorData = json_decode($response, true);
       
       // Check if email and password match
       foreach ($supervisorData as &$supervisor) {
           if ($supervisor['ID'] == $_SESSION['ID'] ) {
               // Update the password in the original array
               $supervisor['password'] = md5($password);
               break; // Stop looping once the supervisor is found
           }
       }

       var_dump($supervisorData);

       // Update the data in the Firebase Realtime Database
       $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($supervisorData));
       curl_exec($ch);
       curl_close($ch);

       echo "<script>alert('Password Changed Successfully!')</script>";
       exit();
   }
}

?>