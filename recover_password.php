<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];


    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    $response = curl_exec($ch);
    curl_close($ch);

    $supervisors = json_decode($response, true);
    $emailFound = false;
    $supervisorId = "";
    foreach ($supervisors as $id => $supervisor) {
        if ($supervisor['email'] == $email) {
            $emailFound = true;
            $supervisorId = $id;
            break;
        }
    }

    if ($emailFound) {
        // إنشاء رابط استعادة كلمة المرور
        $resetLink = "http://smarthelmett.com/reset_link.php?id=" . $supervisorId;

        
        $subject = "Reset Your Password";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: no-reply@smarthelmett.com";

        if (mail($email, $subject, $message, $headers)) {
             echo "<script>alert('A password reset link has been sent to your email.');</script>";
        } else {
           echo "<script>alert('Failed to send email. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(120deg, #4f73ff, #202d63);
            font-family: Arial, sans-serif;
        }

        .container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            width: 350px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
            color: #202d63;
        }

        .container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .container button {
            width: 100%;
            padding: 10px;
            background: #4f73ff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        .container button:hover {
            background: #3d5bcc;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 40px;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .password-container .toggle-password:hover {
            color: #4f73ff;
        }

                   /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            width: 90%; 
            padding: 20px; 
        }

        .container h2 {
            font-size: 20px; 
        }

        .password-container input {
            font-size: 14px; 
        }

        .container button {
            font-size: 14px; 
            padding: 10px; 
        }
    }

    @media (max-width: 480px) {
        .container h2 {
            font-size: 18px;  
        }

        .password-container input {
            font-size: 12px; 
        }

        .container button {
            font-size: 12px;  
            padding: 8px; 
        }

       
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recover Password</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
    
</body>
</html>
