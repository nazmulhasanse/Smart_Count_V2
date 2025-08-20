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

// Fetch divisions and lines from the database
$divisions = [
    "LA" => "Liz Fashion Industry Limited",
    "LD" => "Lida Textile & Dyeing Limited"
];

// Fetch the available lines dynamically from the database
$lines = [
    "LA" => ["LA414", "LA415"],
    "LD" => ["LD512", "LD514"]
];
?>
<?php include('header.php'); ?>

<head>
    <style>
        #chartContainer3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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
                            <select id="updateDivision" name="company" value="Division">
                                <option value="">Select Division</option>
                                <?php foreach ($divisions as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select id="updateLine" name="line" value="Line">
                                <option value="">Select Sewing Line</option>
                                <!-- Lines will be loaded dynamically based on the selected division -->
                            </select>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <script type="text/javascript">
                const divisionToLines = <?= json_encode($lines) ?>;

                document.getElementById('updateDivision').addEventListener('change', function() {
                    const division = this.value;
                    const lineSelect = document.getElementById('updateLine');
                    
                    // Clear existing options
                    lineSelect.innerHTML = '<option value="">Select Sewing Line</option>';
                    
                    if (division && divisionToLines[division]) {
                        divisionToLines[division].forEach(function(line) {
                            const option = document.createElement('option');
                            option.value = line;
                            option.text = line;
                            lineSelect.add(option);
                        });
                    }
                });
            </script>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="getinfo">
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.content -->
        </div>

        <?php include('footer.php'); ?>
    </div>

    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#updateLine').on('change', function() {
                // Retrieve the selected value of the dropdown
                var line = $(this).val();

                // Extract prefix (LA or LD) and the line number (e.g., 414, 415, 512, etc.)
                var prefix = line.substring(0, 2); // Extracts the first two characters (LA or LD)
                var number = line.substring(2);    // Extracts the rest of the string (the line number)

                // Redirect based on the extracted line prefix and number
                var url;
                if (prefix === "LA") {
                    url = "admin_dashboard_liz.php?line=" + line; // Use the full line code (e.g., LA414)
                } else if (prefix === "LD") {
                    url = "admin_dashboard_lida.php?line=" + line; // Use the full line code (e.g., LD512)
                } else {
                    url = "admin_dashboard.php"; // Default URL if no valid prefix
                }


                
                // Redirect to the constructed URL
                window.location.href = url;        
            });
        });
    </script>
</body>
</html>
