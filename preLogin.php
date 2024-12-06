<?php
// Start session to track user login
session_start();

// Check if user is already logged in, if so, redirect to another page (e.g., admin/dashboard)
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php'); // Redirect to dashboard or another protected page
    exit();
}

// If login attempt is made, handle authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with your database connection
    $conn = new mysqli('localhost', 'root', '', 'gp1'); // Adjust your DB credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prevent SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Query to validate user
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password (hashed passwords recommended)
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role']; // e.g., 'admin', 'supervisor'

            // Redirect to the correct page based on user role
            if ($_SESSION['role'] === 'admin') {
                header('Location: admin_dashboard.php'); // Redirect to admin page
            } else {
                header('Location: supervisor_dashboard.php'); // Redirect to supervisor page
            }
            exit();
        } else {
            echo 'Invalid credentials';
        }
    } else {
        echo 'No user found';
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Page</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Include your CSS styles here (same as your provided code) */
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Raleway, sans-serif; }
        body { background: linear-gradient(90deg, #4569cd, #1a0f5c); }
        .container { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .screen { background: linear-gradient(90deg, #3f55ac, #353ca2); position: relative; height: 600px; width: 530px; box-shadow: 0px 0px 24px #231e52; border-radius: 10px; overflow: hidden; }
        .screen__content { z-index: 1; position: relative; height: 100%; padding: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .title { color: #ffffff; font-size: 24px; margin-bottom: 30px; font-family: 'Montserrat', sans-serif; animation: fadeIn 2s; text-align: center; }
        .button { background: #fff; font-size: 18px; padding: 16px 20px; border-radius: 26px; border: 1px solid #D4D3E8; margin-top: 20px; width: 80%; text-transform: uppercase; font-weight: 700; display: flex; align-items: center; justify-content: center; color: #2b2691; box-shadow: 0px 2px 2px #5546df; cursor: pointer; transition: .2s; }
        .button:hover { border-color: #272376; background-color: #d8d0d0; }
        .button__icon { font-size: 24px; margin-left: 10px; color: #3a31e0; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Responsive Design */
        @media (max-width: 768px) { .screen { width: 90%; height: auto; padding: 20px; } .title { font-size: 20px; } .button { font-size: 16px; padding: 14px 18px; } }
        @media (max-width: 480px) { .title { font-size: 18px; } .button { font-size: 14px; padding: 12px 16px; } }
    </style>     
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <div class="title">Start Your Journey as</div>

                <button class="button" onclick="window.location.href='super_login.php';">
                    Supervisor
                    <i class="button__icon fas fa-user-tie"></i>
                </button>
                
                <button class="button" onclick="window.location.href='admin_login.php';">
                    Administrator
                    <i class="button__icon fas fa-user-cog"></i>
                </button>
            </div>

            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</body>
</html>


