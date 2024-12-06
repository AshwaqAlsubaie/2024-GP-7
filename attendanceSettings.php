<?php
session_start();

if(!$_SESSION['role'] == 'admin') {
    header("location: preLogin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $key = "-O5dXqVGzl3l1LbXkePM";  // The existing key to overwrite
    $beginningTime = $_POST['beginningTime'];
    $leavingTime = $_POST['leavingTime'];
    $delayTime = $_POST['delayTime'];
    $absentTime = $_POST['absentTime'];

    // Assuming you store the settings in a JSON or database, this is where you save the data.
    $settings = [
        'beginningTime' => $beginningTime,
        'leavingTime' => $leavingTime,
        'delayTime' => $delayTime,
        'absentTime' => $absentTime
    ];

    // Save to Firebase or other databases
    saveAttendanceSettings($settings);

    echo "<script>alert('Settings saved successfully!');</script>";

    header("location: adminPage.php");
}

function saveAttendanceSettings($settings) {
    // Code to save settings to the database (e.g., Firebase)
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/attendance-settings.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($settings));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response !== false;
}
?>
