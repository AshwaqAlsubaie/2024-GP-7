<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

<style>

@import url('https://fonts.googleapis.com/css?family=Raleway:400,700');

* {
	box-sizing: border-box;
	margin: 0;
	padding: 0;	
	font-family: Raleway, sans-serif;
}

body {
	background: linear-gradient(90deg, #4569cd, #1a0f5c);		
}

.container {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 100vh;
}

.screen {		
	background: linear-gradient(90deg, #3f55ac, #353ca2);		
	position: relative;	
	height: 600px;
	width: 530px;	
	box-shadow: 0px 0px 24px #231e52;
}

.screen__content {
	z-index: 1;
	position: relative;	
	height: 100%;
}

.screen__background {		
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 0;
	-webkit-clip-path: inset(0 0 0 0);
	clip-path: inset(0 0 0 0);	
}

.screen__background__shape {
	transform: rotate(45deg);
	position: absolute;
}

.screen__background__shape1 {
	height: 520px;
	width: 520px;
	background: #FFF;	
	top: -50px;
	right: 120px;	
	border-radius: 0 72px 0 0;
}

.screen__background__shape2 {
	height: 220px;
	width: 220px;
	background: #362a94;	
	top: -172px;
	right: 0;	
	border-radius: 32px;
}

.screen__background__shape3 {
	height: 540px;
	width: 190px;
	background: linear-gradient(270deg, #3628a5, #43319e);
	top: -24px;
	right: 0;	
	border-radius: 32px;
}

.screen__background__shape4 {  
	height: 400px;
	width: 200px;
	background: #4b2fed;	
	top: 420px;
	right: 50px;	
	border-radius: 60px;
}

.login {
	width: 320px;
	padding: 30px;
	padding-top: 156px;
}

.login__field {
	padding: 20px 0px;	
	position: relative;	
}

.login__icon {
	position: absolute;
	top: 30px;
	color: #252265;
}

.login__input {
	border: none;
	border-bottom: 2px solid #D1D1D4;
	background: none;
	padding: 10px;
	padding-left: 24px;
	font-weight: 700;
	width: 75%;
	transition: .2s;
}

.login__input:active,
.login__input:focus,
.login__input:hover {
	outline: none;
	border-bottom-color: #3e36d6;
}

.login__submit {
	background: #fff;
	font-size: 14px;
	margin-top: 30px;
	padding: 16px 20px;
	border-radius: 26px;
	border: 1px solid #D4D3E8;
	text-transform: uppercase;
	font-weight: 700;
	display: flex;
	align-items: center;
	width: 100%;
	color: #2b2691;
	box-shadow: 0px 2px 2px #5546df;
	cursor: pointer;
	transition: .2s;
}

.login__submit:active,
.login__submit:focus,
.login__submit:hover {
	border-color: #272376;
	outline: none;
}

.button__icon {
	font-size: 24px;
	margin-left: auto;
	color: #3a31e0;
}

    </style>     
</head>
<body>

<div class="container">
	<div class="screen">
		<div class="screen__content">

			<form class="login" method="post">
				<div class="login__field">
					<i class="login__icon fas fa-user"></i>
					<input type="text" id="email" class="login__input" placeholder="Email" name="email" required>
				</div>

				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" id ="pass" class="login__input" placeholder="Password" name="password" required>
				</div>
				<input class="button login__submit" type="submit" value="Log In" />	  
			</form>
			
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

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

	// Initialize cURL session
	$url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/admin.json';
	$ch = curl_init($url);

	// Set cURL options for a GET request
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

	// Execute cURL session
	$response = curl_exec($ch);

	// Close cURL session
	curl_close($ch);

	// Decode JSON response
	$adminData = json_decode($response, true);

    // Check if email and password match
	if ($adminData['email'] == $email && $adminData['password'] == md5($password)) {
		// Authentication successful, set session variables and redirect
		$_SESSION['ID'] = $adminData['ID'];
		$_SESSION['email'] = $email;
		$_SESSION['role'] = "admin";
		header("Location: adminPage.php");
		exit();
	}

    // If no match found, redirect back to login page with error message
	echo "<script>alert('Wrong E-mail or password')</script>";
    exit();
}
?>


