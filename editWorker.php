<?php
// Handle edit request
if (isset($_POST['edit_worker'])) {
    $key = $_POST['workerKey'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phoneNumber'];
    $shift = $_POST['shift'];

    var_dump($_POST);

    editWorker($key, $name, $email, $phone, $shift);

    echo "<script>alert('Worker updated successfully!');</script>";

    header("location: showWorkers.php");
}

function editWorker($key, $name, $email, $phone, $shift) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers/' . $key . '.json';
    $workerData = json_encode([
        'name' => $name,
        'email' => $email,
        'phoneNumber' => $phone,
        'shift' => $shift
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $workerData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to update the worker.');</script>";
    } else {
        echo "<script>alert('Worker updated successfully!');</script>";
    }
}

?>
