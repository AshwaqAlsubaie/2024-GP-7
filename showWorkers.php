<?php
session_start();

 if(!$_SESSION['role'] == 'admin')
 {
    header("location: preLogin.php");
 }

 if(isset($_SESSION['record'])){
    $supervisorId = json_encode($_SESSION['record']); // Encode supervisor ID for JavaScript usage
 }

 if (isset($_POST['delete_worker'])) {
    $workerId = $_POST['workerId'];
    deleteWorkerById($workerId);
}
function getWorkerKeyById($workerId) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to fetch workers.');</script>";
        return null;
    }

    $workers = json_decode($response, true);

    foreach ($workers as $workerKey => $workerData) {
        if ($workerData['ID'] == $workerId) {
            return $workerKey;
        }
    }

    return null; // Worker ID not found
}

function deleteWorkerById($workerId) {
    $workerKey = getWorkerKeyById($workerId);

    if ($workerKey === null) {
        echo "<script>alert('Worker with ID $workerId not found.');</script>";
        return;
    }

    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers/' . $workerKey . '.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to delete the worker.');</script>";
    } else {
        echo "<script>alert('Worker deleted successfully!');</script>";
    }
}
$shifts = fetchShiftsSettings();
 

function fetchShiftsSettings() {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/shifts-settings.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Decode the JSON response into an associative array
    $shifts = json_decode($response, true);

    return $shifts;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>smart helmet </title>
    <!-- Firebase scripts -->
    <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.1.1/firebase-database-compat.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script>
    const supervisorId = <?php echo $supervisorId; ?>;
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/firebaseConfig.js"></script> <!-- Include Firebase config -->
    <script src="js/notification.js"></script> <!-- include notification file -->
    <!-- External stylesheets -->
    <link rel="stylesheet" href="css/VitalStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/commcss.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">

    <!-- site metas -->
    <title>SMART HELMET</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">



</head>

<body>
    <!-- header -->
    <header class="full_bg">
      <!-- header inner -->
        <?php include "navbar.php" ?>
        <!-- end header inner -->
        <!-- end header -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="adminPage.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Show Worker</li>
                </ol>
            </nav>
        </div>

        <div class="wrapper" style="position: relative; top: 5em;">
        
            <header style="background-color: #CCE3EB;">
            </header>
            <div class="search-bar">
                <label for="searchInput">Search:</label>
                <input type="text" id="searchInput" oninput="searchTable()" placeholder="Search by Name or ID">
            </div>

            <div class="table-responsive">
            <table class="table" id="crud">
                <caption></caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Shift</th>
                        <th width="25%">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>
        </div>
        <!-- JavaScript code for updating date and time, searching table, and Firebase integration -->
        <script>
    // Function to update date and time
    function updateDateTime() {
        const currentDateTimeElement = document.getElementById("currentDateTime");
        const now = new Date();
        currentDateTimeElement.textContent = `${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Function to search the table based on input
    function searchTable() {
        const input = document.getElementById("searchInput").value.trim().toLowerCase();
        const rows = document.querySelectorAll("#crud tbody tr");

        rows.forEach(row => {
            const name = row.cells[1]?.textContent.toLowerCase() || "";
            const id = row.cells[0]?.textContent.toLowerCase() || "";
            row.style.display = name.includes(input) || id.includes(input) ? "" : "none";
        });
    }
</script>

<script>
    const database = firebase.database().ref("workers");
    const shiftsRef = firebase.database().ref("shifts-settings");
    const tableBody = document.getElementById("tableBody");
    let shiftMap = {}; // Cache for shift mappings
    let workerCache = {}; // Cache for worker data

    // Fetch shift mappings once and cache them
    function loadShiftMap() {
        return shiftsRef.once("value").then(snapshot => {
            snapshot.forEach(childSnapshot => {
                shiftMap[childSnapshot.key] = childSnapshot.val().shiftName;
            });
        });
    }

    // Populate the table with workers, utilizing cache
    function populateTable(snapshot) {
        tableBody.innerHTML = "";
        const fragment = document.createDocumentFragment();
        const workers = [];

        snapshot.forEach(childSnapshot => {
            const worker = childSnapshot.val();
            // Cache worker data to avoid redundant fetches
            workerCache[worker.ID] = worker;
            workers.push(worker);
        });

        // Sort workers by ID
        workers.sort((a, b) => a.ID - b.ID);

        workers.forEach(worker => {
            const shiftName = shiftMap[worker.shift] || "No shift selected";

            const tr = document.createElement("tr");
            tr.id = `worker-${worker.ID}`;
            tr.innerHTML = `
                <td>${worker.ID}</td>
                <td>${worker.name}</td>
                <td>${worker.email}</td>
                <td>${worker.phoneNumber}</td>
                <td>${shiftName}</td>
                <td>
                    <button class="btn btn-sm btn-info text-dark border border-dark" onclick='showEditModal(${worker.ID})'>
                        <i class="fa fa-pencil"></i> Edit
                    </button>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="workerId" value="${worker.ID}">
                        <button type="submit" name="delete_worker" class="btn btn-sm btn-danger text-dark border border-dark" onclick="return confirm('Are you sure you want to delete this worker?');">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            `;
            fragment.appendChild(tr);
        });

        tableBody.appendChild(fragment);
    }

    // Load data from Firebase (Shift and Worker data) with caching
    Promise.all([loadShiftMap(), database.once("value")])
        .then(([_, snapshot]) => populateTable(snapshot))
        .catch(error => console.error("Error loading data:", error));

    // Show edit modal with worker data from cache
    function showEditModal(workerID) {
        const worker = workerCache[workerID]; // Use cached worker data
        if (!worker) {
            alert("Worker data not found.");
            return;
        }

        document.getElementById("editWorkerId").value = "worker" + (worker.ID - 100);
        document.getElementById("editWorkerName").value = worker.name;
        document.getElementById("editWorkerEmail").value = worker.email;
        document.getElementById("editWorkerPhone").value = worker.phoneNumber;
        $("#editWorkerModal").modal("show");
    }

    // Handle worker update form submission
    document.getElementById("editWorkerForm").addEventListener("submit", function (event) {
        event.preventDefault();

        const workerId = document.getElementById("editWorkerId").value;
        const name = document.getElementById("editWorkerName").value.trim();
        const email = document.getElementById("editWorkerEmail").value.trim();
        const phoneNumber = document.getElementById("editWorkerPhone").value.trim();

        firebase
            .database()
            .ref(`workers/${workerId}`)
            .update({ ID: workerId, name, email, phoneNumber })
            .then(() => {
                alert("Worker updated successfully!");
                // Update cached data
                workerCache[workerId] = { ID: workerId, name, email, phoneNumber };
                // Update UI without reloading
                updateWorkerRow(workerId, { name, email, phoneNumber });
                $("#editWorkerModal").modal("hide");
            })
            .catch(error => alert("Error updating worker: " + error.message));
    });

    // Update the worker's row dynamically after the update
    function updateWorkerRow(workerId, updatedData) {
        const row = document.getElementById(`worker-${workerId}`);
        row.querySelector('td:nth-child(2)').textContent = updatedData.name;
        row.querySelector('td:nth-child(3)').textContent = updatedData.email;
        row.querySelector('td:nth-child(4)').textContent = updatedData.phoneNumber;
    }
</script>


        <!-- truck movment-->
        <div class="truck">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 jkhgkj">
                        <div class="truck_img1">
                            <img src="images/truck.png" alt="#" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="truck_img1">
                            <img src="images/jcb.png" alt="#" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end truck movment-->
        <!--  footer -->
        <footer>
            <div class="footer">
                <div class="container">
                    <div class="row">

                    </div>
                </div>
                <div class="copyright">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <p>© 2024 SMART HELMET</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->
<!-- Edit Worker Modal -->
<div class="modal fade" id="editWorkerModal" tabindex="-1" role="dialog" aria-labelledby="editWorkerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editWorkerModalLabel">Edit Worker</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="editWorkerForm" action="editWorker.php">
          <input type="hidden" id="editWorkerId" name="workerKey">
          <div class="form-group">
            <label for="editWorkerName">Name</label>
            <input type="text" class="form-control" id="editWorkerName" name="name" required>
          </div>
          <div class="form-group">
            <label for="editWorkerEmail">Email</label>
            <input type="email" class="form-control" id="editWorkerEmail" name="email" required>
          </div>
          <div class="form-group">
            <label for="editWorkerPhone">Phone</label>
            <input type="text" class="form-control" id="editWorkerPhone" name="phoneNumber" required>
          </div>
          <div class="form-group">
                <label for="editWorkerPhone">Shift</label>
                <select class="form-control" id="shift" name="shift" required>
                    <option value="" hidden selected >Select a shift</option>
                    <?php foreach($shifts as $key => $shift): ?>
                        <option value="<?= $key ?>"><?= $shift['shiftName'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
          <!-- <div class="form-group">
            <label for="editWorkerShift">Shift</label>
            <input type="text" class="form-control" id="editWorkerShift" name="shift" required>
          </div> -->
          <input type="submit"  name="edit_worker" class="btn btn-primary" value="Save Change" />
        </form>
      </div>
    </div>
  </div>
</div>

</body>

</html>
