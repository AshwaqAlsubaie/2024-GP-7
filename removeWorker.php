<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supervisorKey = $_POST['supervisorKey'];
    $workerID = $_POST['workerID'];

    // Firebase URL for the supervisor
    $firebaseUrl = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/' . $supervisorKey . '.json';

    // Get the current supervisor data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $supervisorData = curl_exec($ch);
    curl_close($ch);

    $supervisor = json_decode($supervisorData, true);

    // Remove the worker ID from the workerIDs string
    $workersArray = explode(',', $supervisor['workerIDs']);
    $updatedWorkers = array_diff($workersArray, [$workerID]); // Remove the workerID from the array
    $supervisor['workerIDs'] = implode(',', $updatedWorkers); // Convert back to comma-separated string

    // Update the supervisor data in Firebase
    $updatedSupervisorJson = json_encode($supervisor);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $updatedSupervisorJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response !== false) {
        echo "Worker removed successfully!";
    } else {
        echo "Error: Unable to remove the worker.";
    }
}
?>
