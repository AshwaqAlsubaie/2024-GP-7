<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Re-change Password</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
  body {
  font-family: 'Arial', sans-serif;
  background: linear-gradient(to right, #283048, #859398);
  color: white;
  height: 100vh;
  margin: 0;
}

.main-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: calc(100vh - 70px); 
}


    .form-container {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(5px);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }

    .form-container h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }

    .form-container label {
      color: #fff;
      font-size: 0.9rem;
    }

    .form-container .form-control {
      background-color: rgba(255, 255, 255, 0.2);
      border: none;
      color: #fff;
      border-radius: 5px;
      padding: 10px;
    }

    .form-container .form-control::placeholder {
      color: rgba(255, 255, 255, 0.8);
    }

    .form-container .form-control:focus {
      box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
      border: none;
    }

    .form-container .btn-primary {
      background-color: #0056b3;
      border: none;
      border-radius: 5px;
      padding: 10px;
      width: 100%;
      font-size: 1rem;
      font-weight: bold;
    }

    .form-container .btn-primary:hover {
      background-color: #003e75;
    }

    footer {
      position: fixed;
      bottom: 10px;
      width: 100%;
      text-align: center;
      font-size: 0.9rem;
      color: #ddd;
    }

    footer p {
      margin: 0;
    }
  </style>
    <!-- Header -->

</head>
<body>
  <!-- Header -->
  <?php include "navbar.php"; ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="form-container">
      <h1>Re-change Password</h1>
      <form method="post">
        <div class="form-group">
          <label for="password">New Password:</label>
          <input type="password" class="form-control" id="password" name="password" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must be at least 8 characters long and contain at least one letter, one number, and one special character">
        </div>
        <div class="form-group">
          <label for="confirm">Confirm Password:</label>
          <input type="password" class="form-control" id="confirm" name="confirm" required pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}" title="Password must be at least 8 characters long and contain at least one letter, one number, and one special character">
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
      </form>
    </div>
  </div>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Check if passwords match
    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    } else {
        // Start output buffering to prevent unexpected output
        ob_start();

        // Fetch data from Firebase
        $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
        $ch = curl_init($url);
        
        // Set cURL options for a GET request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        // Execute cURL session
        $response = curl_exec($ch);
        
        // Close cURL session
        curl_close($ch);

        // Check if the response is valid
        if (!$response) {
            echo "<script>alert('Failed to connect to the database.');</script>";
            ob_end_clean(); // Clear the output buffer
            exit();
        }
        
        // Decode JSON response
        $supervisorData = json_decode($response, true);

        // Ensure JSON decoding is successful
        if (!$supervisorData) {
            echo "<script>alert('Invalid data received from the database.');</script>";
            ob_end_clean(); // Clear the output buffer
            exit();
        }
        
        // Loop through each supervisor record to find the one with matching ID
        foreach ($supervisorData as &$supervisor) {
            if ($supervisor['ID'] == $_SESSION['ID']) {
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

        // End output buffering
        ob_end_clean();

        // Show success message
        echo "<script>alert('Password Changed Successfully!');</script>";
        exit();
    }
}
?>


  <!-- Footer -->
  <footer>
    <p>© 2024 SMART HELMET. All rights reserved.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
