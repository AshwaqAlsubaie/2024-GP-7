<?php
session_start();

 if(!$_SESSION['role'] == 'admin')
 {
    header("location: preLogin.php");
 }
// Handle deletion request
if (isset($_POST['delete_shift'])) {
    $shiftKey = $_POST['shiftKey'];
    deleteShift($shiftKey);
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
 function deleteShift($shiftKey) {
    $url = 'https://smart-helmet-database-affb6-default-rtdb.firebaseio.com/shifts-settings/' . $shiftKey . '.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        echo "<script>alert('Failed to delete the shift.');</script>";
    } else {
        echo "<script>alert('Shift deleted successfully!');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> <!-- Ensures responsiveness -->
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
    <link rel="stylesheet" href="css/commCss.css">
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
                    <li class="breadcrumb-item active" aria-current="page">Set Time Attendance</li>
                </ol>
            </nav>
        </div>


        <div class="wrapper" style="position: relative; top: 5em;">
            <!-- <header style="background-color: #CCE3EB;">
                <p>Date and Time: <span id="currentDateTime"></span></p>
            </header> -->
            <div class="search-bar">
                <!-- <label for="searchInput">Search:</label>
                <input type="text" id="searchInput" oninput="searchTable()" placeholder="Search by Name or ID"> -->
            </div>

            <div class="table-responsive">

            <table class="table" id="crud">
                <h1>Time Attendance</h1>
                <caption></caption>
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
                        <th>Shift</th>
                        <th>Allowed Beginning Time</th>
                        <th>Allowed Leaving Time</th>
                        <th>Delay Time(Minute)</th>
                        <th>Absent Time(Hour)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                if ($shifts) {
                    $id = 1; // Initialize ID counter
                    foreach ($shifts as $key => $shift) {
                        echo "<tr>";
                        // echo "<td>" . $id++ . "</td>"; // Increment ID
                        echo "<td>" . htmlspecialchars($shift['shiftName']) . "</td>";
                        echo "<td>" . htmlspecialchars($shift['beginningTime']) . "</td>";
                        echo "<td>" . htmlspecialchars($shift['leavingTime']) . "</td>";
                        echo "<td>" . htmlspecialchars($shift['delayTime']) . "</td>";
                        echo "<td>" . htmlspecialchars($shift['absentTime']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-sm btn-info text-dark border border-dark' style='font: unset;' data-toggle='modal' data-target='#editShiftModal' data-shift-key='" . $key . "' data-shift-name='" . $shift['shiftName'] . "' data-beginning-time='" . $shift['beginningTime'] . "' data-leaving-time='" . $shift['leavingTime'] . "'  data-delay-time='" . $shift['delayTime'] . "' data-absent-time='" . $shift['absentTime'] . "'><i class='fa fa-pencil'></i> Edit</button> ";
                        // Form for deleting the shift
                        // echo "<form method='POST' style='display:inline-block;'>";
                        // echo "<input type='hidden' name='shiftKey' value='" . htmlspecialchars($key) . "'>";
                        // echo "<button type='submit' name='delete_shift' class='btn btn-sm btn-danger text-dark' style='font: unset;'  onclick='return confirm(\"Are you sure you want to delete this shift?\");'>Delete</button>";
                        // echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No Time available</td></tr>";
                }
                ?>
                </tbody>
            </table>
            </div>

        </div>
        <!-- JavaScript code for updating date and time, searching table, and Firebase integration -->

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
        <!-- Edit Shift Modal -->
        <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShiftModalLabel">Edit Shift</h5>
                        <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="editTimeAttendance.php">
                            <input type="hidden" id="editShiftKey" name="shiftKey">
                            <div class="form-group">
                                <label for="editShiftName">Shift name:</label>
                                <input readonly type="text" class="form-control" id="editShiftName" name="shiftName" required>
                            </div>
                            <div class="form-group">
                                <label for="editBeginningTime">Allowed Beginning Time:</label>
                                <input readonly type="time" class="form-control" id="editBeginningTime" name="beginningTime"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editLeavingTime">Allowed Leaving Time:</label>
                                <input readonly type="time" class="form-control" id="editLeavingTime" name="leavingTime"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="delayTime">Delay Time (minutes):</label>
                                <input type="number" class="form-control" id="editDelayTime" name="delayTime" required
                                    min="0">
                            </div>
                            <div class="form-group">
                                <label for="absentTime">Absent Time (Hours):</label>
                                <input type="number" class="form-control" id="editAbsentTime" name="absentTime" required
                                    min="0">
                            </div>
                            <input type="submit" class="btn btn-primary" name="edit_shift" value="Save Changes">
                        </form>
                    </div>
                </div>
            </div>
        </div>

</body>
<script>
$('#editShiftModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var shiftKey = button.data('shift-key');
    var shiftName = button.data('shift-name');
    var beginningTime = button.data('beginning-time');
    var leavingTime = button.data('leaving-time');
    var delayTime = button.data('delay-time');
    var absentTime = button.data('absent-time');

    var modal = $(this);
    modal.find('#editShiftKey').val(shiftKey);
    modal.find('#editShiftName').val(shiftName);
    modal.find('#editBeginningTime').val(beginningTime);
    modal.find('#editLeavingTime').val(leavingTime);
    modal.find('#editDelayTime').val(delayTime);
    modal.find('#editAbsentTime').val(absentTime);
});
</script>

</html>
