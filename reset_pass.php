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
   <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures responsiveness -->
   <title>SMART HELMET</title>
   <!-- Add your CSS links here -->
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/responsive.css">
   <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   <style>
      .header {
         text-align: center;
         position: relative;
         padding: 20px;
         background-color: #005b9a;
         color: #ffffff;
      }

      .arrow-container {
         position: absolute;
         bottom: 10px;
         left: 50%;
         transform: translateX(-50%);
         cursor: pointer;
      }

      .arrow-down {
         font-size: 2rem;
         color: #ffffff;
         transition: transform 0.3s ease;
      }

      .arrow-down:hover {
         transform: translateY(5px); /* Move down slightly on hover */
      }

      .reset-password-section {
         margin-top: 60px;
         padding: 20px;
         text-align: center;
      }

      .reset-password-section h2 {
         font-size: 2em;
         color: #005b9a;
         margin-bottom: 20px;
      }

      .reset-password-section .form-container {
         max-width: 400px;
         margin: 0 auto;
         background-color: #f8f8f8;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
      }

      .reset-password-section input[type="password"],
      .reset-password-section input[type="submit"] {
         width: 100%;
         padding: 10px;
         margin-top: 10px;
         border-radius: 5px;
         border: 1px solid #ccc;
      }

      .reset-password-section input[type="submit"] {
         background-color: #005b9a;
         color: #ffffff;
         font-size: 1em;
         transition: background-color 0.3s;
      }

      .reset-password-section input[type="submit"]:hover {
         background-color: #004080;
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

</head>

<body class="main-layout">


   <!-- Reset Password and Log Out Section -->
   <div id="reset-password-section" class="reset-password-section">
      <h2>Reset Password</h2>
      <div class="form-container">
         <form method="post">
            <div class="form-group">
               <label for="new-password">New Password:</label>
               <input type="password" id="new-password" name="password" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must contain at least 8 characters, one letter, one number, and one special character">
               <span toggle="#new-password" class="fa fa-eye toggle-password"></span>
            </div>
            <div class="form-group">
               <label for="confirm-password">Confirm Password:</label>
               <input type="password" id="confirm-password" name="confirm" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must contain at least 8 characters, one letter, one number, and one special character">
               <span toggle="#confirm-password" class="fa fa-eye toggle-password"></span>
            </div>
            <input type="submit" value="Change Password">
         </form>
         <br>
         <a href="index.php" class="btn btn-secondary">home page</a>
      </div>
   </div>
    <!-- JavaScript for Show/Hide Password -->
   <script>
      document.querySelectorAll('.toggle-password').forEach(item => {
         item.addEventListener('click', function() {
            const passwordField = document.querySelector(this.getAttribute('toggle'));
            if (passwordField.getAttribute('type') === 'password') {
               passwordField.setAttribute('type', 'text');
               this.classList.remove('fa-eye');
               this.classList.add('fa-eye-slash');
            } else {
               passwordField.setAttribute('type', 'password');
               this.classList.remove('fa-eye-slash');
               this.classList.add('fa-eye');
            }
         });
      });
   </script>
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
       
       // Loop through each supervisor record to find the one with matching ID
       foreach ($supervisorData as &$supervisor) {
           if ($supervisor['ID'] == $_SESSION['ID'] ) {
               // Update the password in the original array
               $supervisor['password'] = md5($password);
               break; // Stop looping once the supervisor is found
           }
       }

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
