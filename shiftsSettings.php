<?php
session_start();

if(!$_SESSION['role'] == 'admin') {
    header("location: preLogin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $shiftName = $_POST['shiftName'];
    $beginningTime = $_POST['beginningTime'];
    $leavingTime = $_POST['leavingTime'];

    
    $settings = [
        'shiftName' => $shiftName,
        'beginningTime' => $beginningTime,
        'leavingTime' => $leavingTime,
    ];

    // Save to Firebase
    saveShiftsSettings($settings);

    echo "<script>alert('Settings saved successfully!');</script>";

    header("location: shifts.php");
}

function saveShiftsSettings($settings) {
    // Code to save settings to the database
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/shifts-settings.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($settings));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response !== false;
}
?>
