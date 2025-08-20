<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


date_default_timezone_set('Asia/Dhaka'); // Set your timezone, e.g., 'Asia/Kolkata'

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Get the current hour
$currentHour = date('G');

// Greeting message based on the time of the day
if ($currentHour >= 5 && $currentHour < 12) {
    $greeting = 'Good morning';
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = 'Good afternoon';
} else {
    $greeting = 'Good evening';
}

?>
<?php include('header.php'); ?>

<head>
    <style>

        /* Add padding to the right of the form */
        form {
            padding-right: 20px; /* Adjust the value as needed */
        }
    </style>
</head>



<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include('navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include('sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <!-- <h1 class="m-0">Dashboard</h1> -->
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <!-- <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li> -->
                                <!-- <li class="breadcrumb-item active">Dashboard</li> -->
                            </ol>
                        </div><!-- /.col -->

                        <br>

                        <!-- <div>
                            <h1>Welcome to the Smart Count!</h1>
                            <br>
                            <p><?php echo $greeting; ?>, the current date and time are: <?php echo $currentDateTime; ?>
                            </p>
                        </div> -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->



    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

<h1>Filter for Operation List</h1>

<?php date_default_timezone_set("Asia/Dhaka"); ?>



<script>
        function redirectToQualityPassList() {
            var selectedDate = document.getElementById('datepicker').value;
            // Redirect to quality_pass_list.php with the selected date as a parameter
            window.location.href = 'operation_list.php?Date=' + selectedDate;
        }
    </script>

    <form onsubmit="redirectToQualityPassList(); return false;">
        <label for="datepicker">Select a date:</label>
        <input type="date" id="datepicker" name="datepicker" value="<?php echo date('Y-m-d'); ?>" required>
        <br>
        <input type="submit" value="Submit">
    </form>


    <br><br>
    <a target="_blank" href="filter_operation_list_archive.php">To see the archive data click here</a>


</body>
</html>