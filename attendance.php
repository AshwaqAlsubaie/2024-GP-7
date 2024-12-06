<?php
session_start();

 if(!$_SESSION['role'] == 'supervisor')
 {
    header("location: preLogin.php");
 }
 $supervisorId = json_encode($_SESSION['record']); // Encode supervisor ID for JavaScript usage

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Worker System</title>


    <link rel="stylesheet" href="Css/VitalStyle.css">
    <link rel="stylesheet" href="Css/commCss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">

    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- site metas -->
    <title>SMART HELMET</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
   body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #283048, #859398); /* Updated background gradient */
        color: white;
    }

    .full_bg, .header {
        background: linear-gradient(to right, #283048, #859398); /* Matches body background */
        color: white;
        padding-bottom: 15px;
    }

    .table-container {
        width: 80%;
        margin: 70px auto; /* Add margin-top to create space from the header */
        padding: 20px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background: #ffffff; /* White background for the table container */
    }

    table {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 15px;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #005b9a;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    
    td {
    color: black !important; /* Forces the text color to black */
}


    #searchInput {
        width: 50%;
        margin-bottom: 20px;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .table-responsive {
        margin-top: 20px;
    }
    </style>
 
</head>


<body>

    <!-- header -->
    <header class="full_bg">
         <!-- header inner -->
        <?php include "navbar.php" ?>
        <!-- end header inner -->
        <!-- end header -->
 <div class="table-container">
    <input type="text" id="nameFilter" placeholder="Search by Name" class="form-control mb-3" onkeyup="filterResults()">
    <input type="date" id="dateFilter" class="form-control mb-3" onchange="filterResults()">
    <div class="table-responsive">
        <table id="attendanceTable" class="table table-hover table-bordered">
        <thead>
            <tr>
                 <th>Name</th>
                <th>ID</th>
                <th>Date</th>
                <th>Shifts</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="attendanceTableBody">
               
    
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-database-compat.js"></script>
        <script src="js/firebaseConfig.js"></script> <!-- Include Firebase config -->
        <script src="js/notification.js"></script> <!-- include notification file -->
        <!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        
         <script>
    const database = firebase.database();
    const workersRef = database.ref('workers');
    const sensorsRef = database.ref('Sensor');
    const attendanceSettingsRef = database.ref('shifts-settings');

    let beginningTime;
    let leavingTime;
    let allowedLate;

    supervisorId = <?php echo json_encode($_SESSION['record']); ?>

    function timeToMinutes(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours * 60 + minutes;
    }

    function getShift(attendanceTime, shifts) {
        const attendanceMinutes = timeToMinutes(attendanceTime);

        for (const [shiftKey, shift] of Object.entries(shifts)) {
            const shiftBeginningMinutes = timeToMinutes(shift.beginningTime);
            const shiftLeavingMinutes = timeToMinutes(shift.leavingTime);

            // Check if attendance time falls within the shift
            if (attendanceMinutes >= shiftBeginningMinutes && attendanceMinutes <= shiftLeavingMinutes) {
                return shiftKey; // Return the shift identifier (e.g., "shift1", "shift2", etc.)
            }
        }

        // If no shift matches, return null or some indication
        return null;
    }

// Function to update or add data to the table
function updateAttendanceTable(workerData, sensorData) {
    const tableBody = $('#attendanceTableBody');
    const rowId = `worker-${workerData.ID}-sensor-${workerData.sensorID}`;
    let row = $('#' + rowId);

    attendanceSettingsRef.once('value')
        .then(snapshot => {
            let settings = snapshot.val();
            let attendanceStatus = "No Data";

            if (sensorData && sensorData.AttendanceStatus === 'Card has read') {
                const attendanceTime = sensorData.AttendanceTime;
                const shiftNumber = workerData.shift;
                settings = settings ? settings[shiftNumber] : null;

                const attendanceMinutes = timeToMinutes(attendanceTime);
                const beginningMinutes = timeToMinutes(settings ? settings.beginningTime : '');
                const delayMinutes = settings ? parseInt(settings.delayTime) : 0;
                const absentMinutes = settings ? parseFloat(settings.absentTime) * 60 : 0;

                const allowedTime = beginningMinutes + delayMinutes;
                const absentThreshold = beginningMinutes + absentMinutes;

                if (attendanceMinutes > absentThreshold) {
                    attendanceStatus = "Absent";
                } else if (attendanceMinutes > allowedTime) {
                    const lateMinutes = attendanceMinutes - allowedTime;
                    attendanceStatus = `Late - ${lateMinutes} minutes`;
                } else {
                    attendanceStatus = "On Time";
                }
            } 

            if (row.length === 0) {
                row = $(` 
                    <tr id="${rowId}">
                        <td>${workerData.name || 'No data'}</td>
                        <td>${workerData.ID || 'No data'}</td>
                        <td>${sensorData ? sensorData.AttendanceDate : 'No data'}</td>
                        <td>${settings && settings.shiftName ? settings.shiftName : 'No data'}</td>
                        <td>${sensorData ? sensorData.AttendanceTime : 'No data'}</td>
                        <td>${attendanceStatus}</td>
                    </tr>
                `);
                tableBody.append(row);
            } else {
                row.find('td').eq(0).text(workerData.name || 'No data');
                row.find('td').eq(1).text(workerData.ID || 'No data');
                row.find('td').eq(2).text(sensorData ? sensorData.AttendanceDate : 'No data');
                row.find('td').eq(3).text(settings && settings.shiftName ? settings.shiftName : 'No data');
                row.find('td').eq(4).text(sensorData ? sensorData.AttendanceTime : 'No data');
                row.find('td').eq(5).text(attendanceStatus);
            }
        })
        .catch(error => {
            console.error('Error fetching attendance settings:', error);
        });
}


// Real-time listener for workers and their corresponding sensor data
database.ref(`supervisors/${supervisorId}/workerIDs`).on('value', (snapshot) => {
    const workerIDs = snapshot.val();

    // Real-time listener for all workers
    workersRef.on('value', (workersSnapshot) => {
        workersSnapshot.forEach((workerSnapshot) => {
            const workerData = workerSnapshot.val();
            const workerId = workerData.ID;

            if (workerIDs.includes(workerId)) {
                // Add worker to the table with placeholder data if not already added
                updateAttendanceTable(workerData, {
                    AttendanceDate: 'No data',
                    AttendanceTime: 'No data',
                    AttendanceStatus: 'No data'
                });

                // Real-time listener for each worker's sensor data
                sensorsRef.child(workerData.sensorID).on('value', (sensorSnapshot) => {
                    const sensorData = sensorSnapshot.val();
                    if (sensorData) {
                        updateAttendanceTable(workerData, sensorData);
                    }
                });
            }
        });
    });
});



// Function to filter results by name and date
function filterResults() {
    const nameFilter = $('#nameFilter').val().toLowerCase();
    const dateFilter = $('#dateFilter').val();
    
    $('#attendanceTableBody tr').each(function () {
        const name = $(this).find('td:nth-child(1)').text().toLowerCase();
        const date = $(this).find('td:nth-child(3)').text();
        
        const nameMatch = name.includes(nameFilter);
        const dateMatch = dateFilter === '' || date === dateFilter;
        
        $(this).toggle(nameMatch && dateMatch);
    });
}

// Function to reset filters
function resetFilters() {
    $('#nameFilter').val('');
    $('#dateFilter').val('');
    $('#attendanceTableBody tr').show();
}

</script>

            </tbody>
        </table>
        </div>
 </div>

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

</body>

</html>
