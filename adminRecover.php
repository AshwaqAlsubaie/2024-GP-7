<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recover Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #283048, #859398);
      color: white;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(5px);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .form-container h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    .form-container .form-control {
      background-color: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      border-radius: 5px;
    }

    .form-container .form-control::placeholder {
      color: rgba(255, 255, 255, 0.8);
    }

    .form-container .btn-primary {
      background-color: #0056b3;
      border: none;
      border-radius: 5px;
      padding: 10px;
      width: 100%;
    }

    .form-container .btn-primary:hover {
      background-color: #003e75;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Recover Password</h1>
    <form method="post">
      <input type="email" class="form-control mb-3" name="email" placeholder="Enter your email" required>
      <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
  </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Fetch data from Firebase
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/admin.json'; // actual Firebase URL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    curl_close($ch);

    $users = json_decode($response, true);
    $userFound = false;
    $userId = null;

    // Search for user by email
    foreach ($users as $id => $user) {
        if ($user['email'] == $email) {
            $userFound = true;
            $userId = $id;
            break;
        }
    }

    if ($userFound) {
        $resetLink = "http://smarthelmet.com/adminReset_password.php?id=" . $userId;
        $subject = "Reset Your Password";
        $message = "Click the link to reset your password: " . $resetLink;
        $headers = "From: no-reply@smarthelmett.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Reset link sent to your email.');</script>";
        } else {
            echo "<script>alert('Failed to send email. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>
