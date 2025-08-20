<?php
include '../ie/db_connection.php';
session_start();

if (!isset($_SESSION['cardnumber'])) {
    header("Location: index.php");
    exit;
}

// Check if there is a success or error message in the URL
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'success') {
        echo '<div id="message-box" class="alert alert-success small-message">Quantity inserted successfully.</div>';
    } elseif ($_GET['message'] == 'error') {
        echo '<div id="message-box" class="alert alert-danger small-message">Error occurred while inserting data.</div>';
    }
}


$cardnumber = $_SESSION['cardnumber'];

// Fetch user details from the database using the logged-in cardnumber
$sql = "SELECT A.name AS Name, B.line AS SewingLine ,C.CounterNumber AS CounterNumber
        FROM eworker_assignment AS C
        LEFT JOIN ework_workers AS A ON C.cardnumber = A.cardnumber
        LEFT JOIN ework_sales_order AS B ON C.WorkOrder = B.WorkOrder
        WHERE C.cardnumber = '$cardnumber' 
        AND B.docstatus = 1 
        AND C.WorkerActive = 1 
        AND C.StepActive = 1 
        AND C.FinalStep=1
        LIMIT 1";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['Name'];
    $line = $user['SewingLine'];
    $CounterNumber =$user['CounterNumber'];
} else {
    $name = "Unknown";
    $line = "Unknown";
}

// echo $CounterNumber;
// exit();
// Get today's date
$date = date('Y-m-d');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Quantity</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .small-message {
            max-width: 300px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            font-size: 20px;
        }

        body {
            background-color: #27425a;
            /* Change this to your preferred color */
        }

        .form-container {
            margin: 100px auto;
            padding: 20px;
            border-radius: 12px;
            background-color: #cde1ff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .quantity-field {
            font-size: 30px;
            /* Increase font size for better visibility */
            padding: 12px;
            /* Increase padding for a bigger input box */
            width: 100%;

        }

        .btn-logout {
            background-color: red;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            color: white;
            margin-left: 10px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background-color: red;
            color: black;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 40px 20px;
            width: 100%;
            font-size: 20px;
            margin-top: 20px;
            /* Adding gap before the submit button */
        }

        .btn-primary:hover {
            background-color: #0056b3;

        }

        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            /* Space below the container */
        }

        .text-center {
            flex-grow: 1;
            text-align: center;
            /* Centers the text */
        }

        .btn-info {
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <div class="form-container">

        <div class="input-container d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#statusModal">Status</button>
            <?php
            if($CounterNumber == '' || $CounterNumber == 'NULL' ){
                ?> <h2 class="text-center">Smart Count</h2> <?php
            }else{
               ?> <h2 class="text-center">Smart Count<?php echo '-' . $CounterNumber; ?> </h2> <?php
            }
            
            ?>
            
            <button type="button" class="btn btn-logout" onclick="confirmLogout()">Logout</button>
        </div>

        <form action="bulk_quantity_store.php" method="POST">
            <form action="bulk_store.php" method="POST">
                <!-- Table for Name & Date and ID & Line -->
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong> <?php echo $name; ?></td>
                        <td><strong>Date:</strong> <?php echo $date; ?></td>
                    </tr>
                    <tr>
                        <td><strong>ID:</strong> <?php echo $cardnumber; ?></td>
                        <td><strong>Line:</strong> <?php echo $line; ?></td>
                    </tr>
                </table>

                <!-- Hidden inputs to send the data via POST -->
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="date" value="<?php echo $date; ?>">
                <input type="hidden" name="cardnumber" value="<?php echo $cardnumber; ?>">
                <input type="hidden" name="line" value="<?php echo $line; ?>">

                <!-- Quantity input -->
                <div>
                    <input type="number" id="quantity" name="quantity" class="input-field quantity-field" placeholder="Enter quantity" required>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>


    </div>

    <?php
    // Set timezone to Dhaka, Bangladesh
    date_default_timezone_set('Asia/Dhaka');

    // Get the current hour and minute
    $current_hour = date('h'); // Get current hour in 12-hour format (01 to 12)
    $current_minute = date('i'); // Get current minute (00 to 59)
    $am_pm = date('A'); // This will return 'AM' or 'PM'

    // Calculate the current hour based on your logic
    if ($current_minute > 0) {
        $hour = $current_hour + 1;
    } else {
        $hour = $current_hour;
    }

    // Format the current hour with AM/PM
    $currentHourFormatted = $hour . ' ' . $am_pm;

    // Calculate the last hour based on your logic
    if ($hour == 1 && $am_pm == 'AM') {
        $lastHourFormatted = '12 AM'; // Midnight case
    } elseif ($hour == 1) {
        $lastHourFormatted = '12 PM'; // Noon case
    } else {
        $lastHourFormatted = ($hour - 1) . ' ' . $am_pm;
    }

    // Today's date in the format you use in the database
    $today = date('Y-m-d');

    // SQL query to fetch the last hour's quantity
    $sqlLastHourQty = "SELECT SUM(quantity) AS lastHourQty FROM eworker_bulk_operation 
                   WHERE line = '$line' 
                   AND hour = '$lastHourFormatted' 
                   AND date = '$today'";
    $resultLastHour = $conn->query($sqlLastHourQty);
    $lastHourQty = ($resultLastHour->num_rows > 0) ? $resultLastHour->fetch_assoc()['lastHourQty'] : 0;

    // SQL query to fetch the current hour's quantity
    $sqlCurrentHourQty = "SELECT SUM(quantity) AS currentHourQty FROM eworker_bulk_operation 
                      WHERE line = '$line' 
                      AND hour = '$currentHourFormatted' 
                      AND date = '$today'";
    $resultCurrentHour = $conn->query($sqlCurrentHourQty);
    $currentHourQty = ($resultCurrentHour->num_rows > 0) ? $resultCurrentHour->fetch_assoc()['currentHourQty'] : 0;

    // SQL query to fetch today's total quantity
    $sqlTodaysQty = "SELECT SUM(quantity) AS todaysQty FROM eworker_bulk_operation 
                 WHERE line = '$line' 
                 AND date = '$today'";
    $resultTodaysQty = $conn->query($sqlTodaysQty);
    $todaysQty = ($resultTodaysQty->num_rows > 0) ? $resultTodaysQty->fetch_assoc()['todaysQty'] : 0;
    ?>


    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f0f8ff; border: 2px solid #4b9cd3; border-radius: 10px;">
                <div class="modal-header" style="background-color: #4b9cd3; color: white; border-bottom: 2px solid #f0f8ff;">
                    <h5 class="modal-title" id="statusModalLabel">Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;"></button>
                </div>
                <div class="modal-body" style="color: #333; padding: 20px; background-color: #e3f2fd;">
                    <h5>Last Hour Qty: <?php echo $lastHourQty; ?></span></h5>
                    <h5>This Hour Qty: <?php echo $currentHourQty; ?></span></h5>
                    <h5>Today's Qty: <?php echo $todaysQty; ?></span></h5>
                </div>
                <div class="modal-footer" style="background-color: #f0f8ff; border-top: 2px solid #4b9cd3;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: IndianRed; color: white; border: none;">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Automatically hide the message after 2 seconds
        setTimeout(function() {
            var messageBox = document.getElementById('message-box');
            if (messageBox) {
                messageBox.style.display = 'none';
            }

            // Remove 'message' parameter from the URL without reloading the page
            const url = new URL(window.location);
            url.searchParams.delete('message'); // Remove the 'message' query parameter
            window.history.replaceState({}, document.title, url); // Update the URL without reloading
        }, 2000); // 2000ms = 2 seconds

        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'bulk_logout.php';
            }
        }
    </script>
</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</html>