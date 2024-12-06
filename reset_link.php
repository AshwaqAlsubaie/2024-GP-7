<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_GET['id'];
    $newPassword = $_POST['new_password'];

    // تحديث كلمة المرور في Firebase
    $url = "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/$id.json";

    $data = json_encode(["password" => md5($newPassword)]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        echo "<script>alert('Password updated successfully.');</script>";
    } else {
        echo "<script>alert('Failed to update password. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            border-radius: 10px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
            color: #202d63;
        }

        .password-container {
            position: relative;
            margin: 10px auto;
        }

        .password-container input {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            padding-right: 40px;
            margin: 0 auto;
            display: block;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .password-container .toggle-password:hover {
            color: #4f73ff;
        }

        .container button {
            width: 90%;
            padding: 12px;
            background: #4f73ff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }

        .container button:hover {
            background: #3d5bcc;
        }

       
        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
            display: none;
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
        <h2>Reset Password</h2>
        <form method="post" onsubmit="return validatePasswords();">
            <div class="password-container">
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
                <div id="password_error" class="error">Password must contain at least 8 characters, including uppercase, lowercase, number, and a special character.</div>
            </div>
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                <div id="confirm_error" class="error">Passwords do not match.</div>
            </div>
            <button type="submit">Reset Password</button>
        </form>
       
    </div>
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        function validatePasswords() {
            const password = document.getElementById("new_password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            const passwordError = document.getElementById("password_error");
            const confirmError = document.getElementById("confirm_error");

            // Reset error messages
            passwordError.style.display = "none";
            confirmError.style.display = "none";

            // Check password strength
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                passwordError.style.display = "block";
                return false;
            }

            // Check password match
            if (password !== confirmPassword) {
                confirmError.style.display = "block";
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
