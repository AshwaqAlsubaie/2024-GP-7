<?php
// Handle edit request
if (isset($_POST['edit_shift'])) {
    $shiftKey = $_POST['shiftKey'];
    $shiftName = $_POST['shiftName'];
    $beginningTime = $_POST['beginningTime'];
    $leavingTime = $_POST['leavingTime'];

    var_dump($_POST);

    editShift($shiftKey, $shiftName, $beginningTime, $leavingTime);

    echo "<script>alert('Shifts updated successfully!');</script>";

    header("location: shifts.php");
}

function editShift($shiftKey, $shiftName, $beginningTime, $leavingTime) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/shifts-settings/' . $shiftKey . '.json';
    $shiftData = json_encode([
        'shiftName' => $shiftName,
        'beginningTime' => $beginningTime,
        'leavingTime' => $leavingTime
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $shiftData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to update the shift.');</script>";
    } else {
        echo "<script>alert('Shift updated successfully!');</script>";
    }
}

?>
