<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);
session_start();

if (!$_SESSION['role'] == 'admin') {
    header("location: preLogin.php");
}

// دالة لاسترجاع المشرفين من قاعدة بيانات Firebase
function getSupervisors() {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to fetch supervisors.');</script>";
        return [];
    }

    return json_decode($response, true);
}
function getAllWorkers() {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to fetch worker data.');</script>";
        return []; // Return an empty array on failure
    }

    $workerData = json_decode($response, true);
    $workers = [];

    // Check if worker data is not empty
    if (!empty($workerData)) {
        foreach ($workerData as $worker) {
            // Collect name and ID for each worker
            $workers[] = [
                'name' => $worker['name'],
                'ID' => $worker['ID']
            ];
        }
    }

    return $workers; // Return array of workers
}

// Example usage
$allWorkers = getAllWorkers();

function getWorkerNameById($workerID) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/workers/worker' . ($workerID - 100) . '.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to fetch worker data.');</script>";
        return "Unknown Worker"; // Default value if fetching fails
    }

    $workerData = json_decode($response, true);

    if (!empty($workerData['name'])) {
        return $workerData['name'];
    } else {
        return "Unknown Worker"; // If worker name is not found
    }
}

$supervisors = getSupervisors();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Supervisors</title>
    <link rel="stylesheet" href="css/VitalStyle.css">
    <link rel="stylesheet" href="css/commcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
    /* Remove border-radius from Select2 */
    .select2-container--default .select2-selection--multiple {
        border-radius: 0 !important;
        /* Remove border radius */
        border: 1px solid #ced4da;
        /* Optional: Customize border color */
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border-radius: 0 !important;
        /* Remove border radius from selected choices */
    }

    /* Optional: Remove border radius from dropdown */
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        border-radius: 0 !important;
        /* Remove border radius from the rendered area */
    }
    @media (max-width: 768px) {
            .screen {
                width: 90%; /* Shrinks the screen to fit smaller devices */
                height: auto;
                padding: 20px;
            }

            .title {
                font-size: 20px; /* Reduces title size for smaller screens */
            }

            .button {
                font-size: 16px;
                padding: 14px 18px;
            }
        }
		@media (max-width: 480px) {
            .title {
                font-size: 18px;
            }

            .button {
                font-size: 14px;
                padding: 12px 16px;
            }}
    </style>
 <script>
    $(document).ready(function() {
        // Initialize Select2 on all worker selects
        $('.workerSelect').select2({
            placeholder: 'Select Workers',
            allowClear: true
        });
    });
    </script>
</head>

<body>
    <!-- header -->
    <header class="full_bg">
        <?php include "navbar.php" ?>
        <!-- end header -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="adminPage.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Show Supervisors</li>
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
            <table class="table" id="crud" style="z-index: 999">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="30%">Worker Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php 
    $assignedWorkers = [];
    foreach ($supervisors as $supervisor) {
        $workersArray = explode(',', $supervisor['workerIDs']);
        $assignedWorkers = array_merge($assignedWorkers, $workersArray);
    }
    $assignedWorkers = array_unique($assignedWorkers); // Ensure unique IDs

    foreach ($supervisors as $key => $supervisor) { ?>
                    <tr>
                        <td><?= $supervisor['ID'] ?></td>
                        <td><?= $supervisor['name'] ?></td>
                        <td><?= $supervisor['email'] ?></td>
                        <td>
                            <table class="table">
                                <?php $workersArray = explode(',', $supervisor['workerIDs']); ?>
                                <?php foreach ($workersArray as $workerID) { ?>
                                <tr>
                                    <td><?php echo getWorkerNameById($workerID); ?></td>
                                    <td width="10%">
                                        <button class="btn btn-sm btn-danger"
                                            onclick="removeWorkerFromSupervisor('<?= $key ?>', '<?= $workerID ?>')"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>

                            <!-- Assign new workers -->
                            <div class="input-group d-flex flex-nowrap flex-sm-wrap">
  <!-- Select Dropdown -->
  <select class="w-75 form-control workerSelect" id="workerSelect<?= $key ?>" multiple="multiple" 
          style="border-radius: 0;">
    <?php 
    foreach($allWorkers as $worker) { 
      // Only show workers who are not assigned to any supervisor
      if (!in_array($worker['ID'], $assignedWorkers)) { ?>
        <option value="<?= $worker['ID'] ?>"><?= $worker['name'] ?></option>
    <?php } } ?>
  </select>

  <!-- Assign Button -->
  <div class="input-group-append">
    <button onClick="assignWorkersToSupervisor('<?= $key ?>')" 
            class="btn btn-primary rounded-0" 
            style="font: inherit;">Assign</button>
  </div>
</div>


                        </td>
                        <td>
                            <button class="btn btn-sm btn-info text-dark border border-dark" style='font: unset;'
                                onclick="showEditModal('<?= $key ?>', '<?= $supervisor['name'] ?>', '<?= $supervisor['email'] ?>')"><i
                                    class="fa fa-pencil"></i> Edit</button>
                            <button class="btn btn-sm btn-danger text-dark border border-dark" style='font: unset;'
                                onclick="deleteSupervisor('<?= $key ?>')"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>


            </table>
                            </div>
        </div>

        <!-- Edit Supervisor Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Supervisor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="supervisorKey">
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" class="form-control" id="editName" required>
                            </div>
                            <div class="form-group">
                                <label for="editEmail">Email</label>
                                <input type="email" class="form-control" id="editEmail" required>
                            </div>
                            <!-- <div class="form-group">
                                <label for="editWorkerIDs">Worker IDs</label>
                                <input type="text" class="form-control" id="editWorkerIDs" required>
                            </div> -->
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- truck movment-->
        <div class="truck" style="z-index: 1">
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

        <script>
       

        // Function to search the table based on input
        function searchTable() {
            var input, filter, table, tr, tdName, tdID, txtValueName, txtValueID;
            input = document.getElementById("searchInput");
            filter = input.value.trim().toUpperCase();
            table = document.getElementById("crud");
            tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                tdName = tr[i].getElementsByTagName("td")[1];
                tdID = tr[i].getElementsByTagName("td")[0];
                if (tdName || tdID) {
                    txtValueName = tdName.textContent || tdName.innerText;
                    txtValueID = tdID.textContent || tdID.innerText;
                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueID.toUpperCase().indexOf(filter) > -
                        1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Function to show the edit modal with supervisor data
        function showEditModal(key, name, email) {
    document.getElementById('supervisorKey').value = key;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    $('#editModal').modal('show');
}

// Function to handle the edit form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const key = document.getElementById('supervisorKey').value;

    // Fetch the current supervisor data to retain unchanged fields
    fetch(`https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/${key}.json`)
        .then(response => response.json())
        .then(currentSupervisor => {
            // Prepare the updated data, retaining existing fields
            const updatedSupervisor = {
                ...currentSupervisor, // Spread operator to copy existing fields
                name: document.getElementById('editName').value,
                email: document.getElementById('editEmail').value,
            };

            // Send the updated data back to the database
            return fetch(`https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/${key}.json`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updatedSupervisor)
            });
        })
        .then(response => response.json())
        .then(data => {
            alert('Supervisor updated successfully!');
            location.reload();
        })
        .catch(error => console.error('Error:', error));
});


        // Function to delete a supervisor
        function deleteSupervisor(key) {
            if (confirm('Are you sure you want to delete this supervisor?')) {
                fetch(`https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/${key}.json`, {
                        method: 'DELETE'
                    })
                    .then(() => {
                        alert('Supervisor deleted successfully!');
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function removeWorkerFromSupervisor(supervisorKey, workerID) {
            // Send an AJAX request to your PHP script to remove the worker
            if (confirm('Are you sure you want to remove this worker?')) {
                // Make an AJAX request to the backend
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "removeWorker.php", true); // Create a separate PHP file to handle the worker removal
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText); // Handle the response
                        location.reload(); // Reload the page to see the updated list
                    }
                };
                xhr.send("supervisorKey=" + supervisorKey + "&workerID=" + workerID);
            }
        }
        </script>
        <script>
        function assignWorkersToSupervisor(supervisorKey) {
            // Get selected workers from the select element
            const selectElement = document.querySelector(`#workerSelect${supervisorKey}`);
            const selectedWorkers = Array.from(selectElement.selectedOptions).map(option => option.value);

            if (selectedWorkers.length === 0) {
                alert("Please select at least one worker to assign.");
                return;
            }

            // Retrieve existing worker IDs for the supervisor from Firebase
            const supervisorRef =
                `https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/supervisors/${supervisorKey}/workerIDs.json`;

            fetch(supervisorRef)
                .then(response => response.json())
                .then(existingWorkerIDs => {
                    // Combine existing workers with newly selected workers, ensuring uniqueness
                    const supervisorWorkerIDs = existingWorkerIDs ? existingWorkerIDs.split(',') : [];
                    const updatedWorkerIDs = [...new Set([...supervisorWorkerIDs, ...selectedWorkers])].join(',');

                    // Update the supervisor's workerIDs in Firebase
                    return fetch(supervisorRef, {
                        method: 'PUT', // Use PUT to update the existing data
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(updatedWorkerIDs)
                    });
                })
                .then(() => {
                    alert("Workers assigned successfully!");
                    // Optionally, refresh the table or update the UI to reflect changes
                    location.reload(); // Reload the page to show updated worker assignments
                })
                .catch(error => {
                    console.error("Error assigning workers: ", error);
                    alert("Failed to assign workers. Please try again.");
                });
        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
