<?php

include './ie/db_connection.php';
session_start();
if (!isset($_SESSION['cardnumber'])) {
    header("Location: index.php");
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
$cardnumber = $_SESSION['cardnumber'];
if (isset($_SESSION['cardnumber'])) {
    $sql = "SELECT
    B.WorkOrder AS WorkOrder,
    B.Style AS Style,
    C.StepNumber AS Step,
    C.Description_StepName AS Step_Description,
    B.Customer,
    B.ColorSizeEntry AS ColorSizeEntry,
    B.line AS SewingLine,
    A.Position AS Position,
    B.docnumber as docnumber,
    C.cardnumber AS CardNo,
    C.name AS Name
    FROM
        eworker_assignment AS C
    LEFT JOIN
        ework_workers AS A ON C.cardnumber = A.cardnumber
    LEFT JOIN
        ework_sales_order AS B ON C.WorkOrder = B.WorkOrder
    WHERE
        C.cardnumber = '$cardnumber'
   	and B.docstatus=1
       	and C.WorkerActive=1
	and C.StepActive=1
    AND C.WorkOrder = B.WorkOrder
    GROUP BY C.idlines;
    ";
    $result = $conn->query($sql);
    if ($result->num_rows > 1) { ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ework Operation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f2f2f2;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    overflow-x: hidden;
                    /* Prevent horizontal scrolling */
                }

                .container {
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    padding: 10px;
                    /* width: 100%; */
                    max-width: 90%;
                    position: relative;
                    /* Set position to relative for absolute positioning inside */
                }

                .header {
                    position: relative;
                    /* Set position to relative for absolute positioning inside */
                    display: flex;
                    justify-content: space-between;
                    border-bottom: 1px solid #ccc;
                    padding: 15px 0;
                    /* Increased padding for better spacing */
                    margin-bottom: 20px;
                    /* Adjusted margin */
                }
            </style>

        <body>
            <div class="container">
                <div class="header-logout">
                    <form method="post">
                        <button class="logout-button" name="logout">Logout</button>
                    </form>
                </div>


                <?php echo "You are assigned multiple Work Order which are open. Please inform the Responsible IE."; ?>
            </div>
        </body>

        </html>

        <?php exit;
    } else {


        if ($result->num_rows > 0) {
            // output data of each row
            //   $count = count($data);
            while ($row = $result->fetch_assoc()) {
                $data = $row;

                $workorder = $data['WorkOrder'];
            }
        } else {

        ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Ework Operation</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f2f2f2;
                        margin: 0;
                        padding: 0;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        overflow-x: hidden;
                        /* Prevent horizontal scrolling */
                    }

                    .container {
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        padding: 10px;
                        /* width: 100%; */
                        max-width: 90%;
                        position: relative;
                        /* Set position to relative for absolute positioning inside */
                    }

                    .header {
                        position: relative;
                        /* Set position to relative for absolute positioning inside */
                        display: flex;
                        justify-content: space-between;
                        border-bottom: 1px solid #ccc;
                        padding: 15px 0;
                        /* Increased padding for better spacing */
                        margin-bottom: 20px;
                        /* Adjusted margin */
                    }
                </style>

            <body>
                <div class="container">
                    <div class="header-logout">
                        <form method="post">
                            <button class="logout-button" name="logout">Logout</button>
                        </form>
                    </div>


                    <?php echo "You are not assigned in any Work Order."; ?>
                </div>
            </body>

            </html>

<?php exit;
        }
    }



    // Set the timezone to Bangladesh
    date_default_timezone_set('Asia/Dhaka');
    $loginDatetime = date('Y-m-d H:' . '00:00');

    // Calculate the end datetime by adding one hour
    $endDatetime = date('Y-m-d H:' . '00:00', strtotime($loginDatetime . ' +1 hour'));



    $startDatetime = date('Y-m-d H:' . '00:00', strtotime($loginDatetime . ' -1 hour'));


    // Get the current date for today's total quantity
    $currentDate = date('Y-m-d');

    $sql_qty = "SELECT ework_id, color, size, end_datetime,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass') AND ework_id < (SELECT MAX(ework_id) FROM eworker_operation)),
        0
    ) AS total_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass') AND DateTime BETWEEN '$loginDatetime' AND '$endDatetime'),
        0
    ) AS total_hour_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass') AND DateTime BETWEEN '$startDatetime' AND '$loginDatetime'),
        0
    ) AS total_last_hour_qty,
    COALESCE(
        (SELECT Qty FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass') ORDER BY ework_id DESC LIMIT 1),
        0
    ) AS last_row_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass')),
        0
    ) AS total_sum_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass') AND DATE(DateTime) = '$currentDate'),
        0
    ) AS today_total_qty
FROM eworker_operation
WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('rework-pass')
ORDER BY ework_id DESC
LIMIT 1;";
    $result_qty = $conn->query($sql_qty);
    // $data_qty['end_datetime'] = '0000-00-00 00:00:00';
    if ($result_qty->num_rows > 0) {
        // output data of each row
        while ($row_qty = $result_qty->fetch_assoc()) {
            $data_qty = $row_qty;
            //  print_r($data_qty);
        }
    } else {
        // echo "0 results";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ework Operation</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-confirm.min.js"></script>
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* height: 0vh !important; */
            margin-top: 20px;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        .container {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            /* width: 100%; */
            max-width: 90%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
            /* Increased padding for better spacing */
            /* margin-bottom: 40px; */
        }

        .header-logout {
            display: flex;
            justify-content: flex-end;
            /* Align to the right */
            padding: 15px 0;
            /* Increased padding for better spacing */
            margin-bottom: 20px;
            /* Adjusted margin */
        }

        .header-label {
            font-weight: bold;
        }

        .header-label label {
            display: block;
            margin-bottom: 1px;
            /* Added margin between labels */
        }

        .body {
            display: flex;
            flex-direction: column;
            padding: 15px 0;
            /* Increased padding for better spacing */
        }

        .footer {

            justify-content: center;
            padding: 15px 0;
            display: flex;
            /* Increased padding for better spacing */
        }

        .button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .pass-button {
            background-color: green;
            color: #fff;
        }

        .pass-button:hover,
        .pass-button:focus,
        .pass-button:active {
            text-decoration: none;
            /* Prevents underline on hover or active states */
            color: #fff;
            /* Keeps the text color white */
        }

        .fail-button {
            background-color: red;
            color: #fff;
        }

        .input-label {
            font-weight: bold;
        }

        .number-input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            width: 100%;

            /* Added margin below the input field */
        }

        /* Add new styles for the logout button */
        .logout-button {
            background-color: #3498db;
            /* Blue color, you can change this */
            color: #fff;
            /*padding: 10px 20px;*/
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        ul {
            display: flex;
            /* padding: 2px; */
            list-style: none;
        }

        label {
            margin-top: 6px;
        }

        #editButton {
            margin-left: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 20px;
        }

        #editButton:hover {
            background-color: #2980b9;
        }

        /* Add some styling for the form */
        .login-form {
            margin-top: 20px;
        }

        .select-container {
            display: flex;
            width: 100%;

        }

        .select-box {
            width: 45%;
            margin: 0px 15px 23px 0px;
            padding: 6px;
        }

        .pass-button {
            height: 65px;
            width: 130px;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- Logout button in the header -->
        <div class="header-logout">
            <form method="post">
                <button class="logout-button" name="logout">Logout</button>
            </form>
        </div>
        <!-- Header section with labels -->
        <div class="header">
            <div class="header-label">
                <label><?php echo  $data['Name']; ?></label></br>
                <label> <?php echo  $data['Customer']; ?></label></br>
                <label><?php echo  $workorder; ?></label></br>
                <label>Step: <?php echo  $data['Step']; ?></label></br>

            </div>

            <div></br></br></br>
                <label style="font-size:25px"><strong><?php echo 'Re-Work Pass process is going on...'; ?></strong></label>
            </div>


            <div class="header-label">
                <label><?php echo  $data['CardNo']; ?></label></br>
                <?php if ($data['Position'] == '0') { ?>
                    <label>Operator</label></br>
                <?php } elseif ($data['Position'] == '1') { ?>
                    <label>Helper</label></br>
                <?php } else { ?>
                    <label>Quality Inspector</label></br>
                <?php } ?>
                <label><?php echo  $data['Style']; ?></label></br>
                <label style="font-size:10px"> <?php echo  $data['Step_Description']; ?></label></br>


            </div>
        </div>
        <!-- Status' Modal Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">Status</button>


        <!-- Body section with input field  -->
        <form class="login-form" action="pass_data_store.php" id='formId' method="POST">
            <div class=" body">
                <input type="hidden" id="Name" name="Name" class="number-input"
                    value="<?php echo  $data['Name']; ?> ">
                <input type="hidden" id="WorkOrder" name="WorkOrder" class="number-input"
                    value="<?php echo  $workorder; ?>">
                <input type="hidden" id="Step" name="Step" class="number-input"
                    value="<?php echo  $data['Step']; ?>">
                <input type="hidden" id="CardNo" name="CardNo" class="number-input"
                    value="<?php echo  $data['CardNo']; ?>">
                <input type="hidden" id="Style" name="Style" class="number-input"
                    value="<?php echo  $data['Style']; ?>">
                <input type="hidden" id="SewingLine" name="SewingLine" class="number-input"
                    value="<?php echo  $data['SewingLine']; ?>">
                <input type="hidden" id="docnumber" name="docnumber" class="number-input"
                    value="<?php echo  $data['docnumber']; ?>">
                <?php
                if ($data['Position'] == '2' || $data['ColorSizeEntry'] == '1') {
                    $sql_size_colors = "SELECT color, size FROM `ework_order_wise_color_size_qty` WHERE WorkOrder = '$workorder';";
                    $result_size_colors = $conn->query($sql_size_colors);

                    if ($result_size_colors) {
                        if (property_exists($conn, 'driver_name') && $conn->driver_name === 'mysqlnd') {
                            // Fetch all rows as an associative array
                            $data_size_colors = $result_size_colors->fetch_all(MYSQLI_ASSOC);
                        } else {
                            // Fetch rows one by one if the native driver is not available
                            $data_size_colors = [];
                            while ($row = $result_size_colors->fetch_assoc()) {
                                $data_size_colors[] = $row;
                            }
                        }
                    } else {
                        echo "0 results";
                    }


                ?>

                    <div class="select-container">
                        <?php if (isset($_SESSION['form_submitted'])) : ?>
                            <select name="color" class="select-box" id="colorSelect">
                                <option value="">--- Choose a Color ---</option>
                                <?php foreach ($data_size_colors as $data_size_color) : ?>
                                    <option value="<?php echo $data_size_color['color']; ?>"
                                        <?php echo ($data_size_color['color'] == $data_qty['color']) ? 'selected' : ''; ?>>
                                        <?php echo $data_size_color['color']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="size" class="select-box" id="sizeSelect">
                                <option value="">--- Choose a Size ---</option>
                                <?php foreach ($data_size_colors as $data_size_color) : ?>
                                    <option value="<?php echo $data_size_color['size']; ?>"
                                        <?php echo ($data_size_color['size'] == $data_qty['size']) ? 'selected' : ''; ?>>
                                        <?php echo $data_size_color['size']; ?></option>
                                <?php endforeach; ?>
                            </select>

                        <?php else: ?>

                            <?php //if ($data['Position'] == '0' || $data['Position'] == '1') {
                            ?>

                            <?php if (!isset($data_qty)) { ?>

                                <select name="color" class="select-box" id="colorSelect">
                                    <option value="">--- Choose a Color ---</option>
                                    <?php foreach ($data_size_colors as $data_size_color) : ?>
                                        <option value="<?php echo $data_size_color['color']; ?>">
                                            <?php echo $data_size_color['color']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="size" class="select-box" id="sizeSelect">
                                    <option value="">--- Choose a Size ---</option>
                                    <?php foreach ($data_size_colors as $data_size_color) : ?>
                                        <option value="<?php echo $data_size_color['size']; ?>">
                                            <?php echo $data_size_color['size']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else { ?>

                                <select name="color" class="select-box" id="colorSelect">
                                    <option value="">--- Choose a Color ---</option>
                                    <?php foreach ($data_size_colors as $data_size_color) : ?>
                                        <option value="<?php echo $data_size_color['color']; ?>"
                                            <?php echo ($data_size_color['color'] == $data_qty['color']) ? 'selected' : ''; ?>>
                                            <?php echo $data_size_color['color']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="size" class="select-box" id="sizeSelect">
                                    <option value="">--- Choose a Size ---</option>
                                    <?php foreach ($data_size_colors as $data_size_color) : ?>
                                        <option value="<?php echo $data_size_color['size']; ?>"
                                            <?php echo ($data_size_color['size'] == $data_qty['size']) ? 'selected' : ''; ?>>
                                            <?php echo $data_size_color['size']; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            <?php } ?>
                            <!-- <?php //}else{
                                    ?>

                        <select name="color" class="select-box" id="colorSelect">
                            <option value="">--- Choose a Color ---</option>
                            <?php //foreach ($data_size_colors as $data_size_color) : 
                            ?>
                            <option value="<?php //echo $data_size_color['color']; 
                                            ?>">
                                // echo $data_size_color['color']; ?></option>
                            <?php //endforeach; 
                            ?>
                        </select>
                        <select name="size" class="select-box" id="sizeSelect">
                            <option value="">--- Choose a Size ---</option>
                            <?php //foreach ($data_size_colors as $data_size_color) : 
                            ?>
                            <option value="<?php //echo $data_size_color['size']; 
                                            ?>">
                                <?php //echo $data_size_color['size']; 
                                ?></option>
                            <?php //endforeach; 
                            ?>
                        </select> -->


                            <?php //}
                            ?>
                        <?php endif; ?>

                    </div>
                <?php } ?>
                <ul>
                    <label class="input-label" for="qty">Qty:</label>
                    <?php if (isset($_SESSION['form_submitted'])) : ?>

                        <li> <input style="font-size:15px;text-align:center" class="number-input"
                                value="<?php echo $data_qty['last_row_qty'] . ' / ' . $data_qty['total_qty']; ?>"
                                readonly>

                        </li>
                    <?php else: ?>
                        <li>
                            <input type="hidden" id="qty_type" name="qty_type" class="number-input" value="rework-pass">
                            <input type="number" id="qty" name="qty" class="number-input" value="20"
                                placeholder="Enter quantity" max="20" required>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
            <?php if ($data['Position'] == '2') { ?>

                <div class="footer">
                    <?php if (!isset($_SESSION['form_submitted'])) : ?>
                        <button class="button pass-button " id='submitButton' type="submit" value="Submit">Submit</button>
                    <?php endif; ?>
                </div>

                <?php } else {


                if (!isset($data_qty['end_datetime'])) { ?>

                    <div class="footer">
                        <input type="hidden" id="submit_type" name="submit_type" class="number-input" value="start">
                        <button class="button pass-button " id='submitButton' type="submit" value="Submit">Start</button>
                    </div>
                <?php   } else { ?>

                    <!-- Footer section with submit button -->
                    <?php if (isset($data_qty['end_datetime']) && $data_qty['end_datetime'] !== '0000-00-00 00:00:00') {
                    ?>
                        <div class="footer">
                            <input type="hidden" id="submit_type" name="submit_type" class="number-input" value="start">
                            <button class="button pass-button " id='submitButton' type="submit" value="Submit">Start</button>
                        </div>
                    <?php } else { ?>
                        <div class="footer">
                            <input type="hidden" id="ework_id" name="ework_id" class="number-input"
                                value="<?php echo $data_qty['ework_id'] ?>">
                            <input type="hidden" id="submit_type" name="submit_type" class="number-input" value="end">
                            <button class="button pass-button " id='submitButton' type="submit" value="Submit"
                                style="background-color:red">End</button>
                        </div>
                <?php   }
                } ?>

            <?php } ?>
        </form>



        <script>
            $(document).ready(function() {
                $("#formId").submit(function(event) {
                    $("#submitButton").attr("disabled", true);
                    event.preventDefault();
                });
            });
        </script>
        <?php if ($data['Position'] == '2') { ?>
            <div class="footer">
                <?php if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) : ?>
                    <form method="post" action="reworkpassquantity_edite.php">
                        <button class="button pass-button " id="editButton">Edit</button>
                    </form>
                    <form method="post" id="deleteForm">
                        <input type="hidden" id="ework_id" name="ework_id" class="number-input"
                            value="<?php echo $data_qty['ework_id'] ?>">
                        <input type="hidden" id="delete" name="delete" class="number-input" value="yes">
                        <button class="button pass-button" id="editButton" style="background-color: red"
                            onclick="confirmDelete()">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="">
                <a class="button pass-button" href="reworkquantity.php"> <i
                        style="color:white"></i>Back</a>
            </div>
        <?php  } ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #f0f8ff; border: 2px solid #4b9cd3; border-radius: 10px;">
                <div class="modal-header" style="background-color: #4b9cd3; color: white; border-bottom: 2px solid #f0f8ff;">
                    <h5 class="modal-title" id="statusModalLabel">Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;"></button>
                </div>

                <!-- Updated PHP Section -->
                <div class="modal-body" style="color: #333; padding: 20px; background-color: #e3f2fd; font-family: 'Arial', sans-serif;">
                    <h5>Total Quantity:
                        <?php
                        if (isset($data_qty['total_sum_qty']) && $data_qty['total_sum_qty'] !== '') {
                            echo $data_qty['total_sum_qty'];
                        }
                        ?>
                    </h5>

                    <h5>Last Hour Qty:
                        <?php
                        if (isset($data_qty['total_last_hour_qty']) && $data_qty['total_last_hour_qty'] !== '') {
                            echo $data_qty['total_last_hour_qty'];
                        }
                        ?>

                    </h5>

                    <h5>This Hour Qty:
                        <?php
                        if (isset($data_qty['total_hour_qty']) && $data_qty['total_hour_qty'] !== '') {
                            echo $data_qty['total_hour_qty'];
                        }
                        ?>
                    </h5>

                    <h5>Today's Qty:
                        <?php
                        if (isset($data_qty['today_total_qty']) && $data_qty['today_total_qty'] !== '') {
                            echo $data_qty['today_total_qty'];
                        }
                        ?>

                    </h5>
                </div>

                <!-- Footer -->
                <div class="modal-footer" style="background-color: #f0f8ff; border-top: 2px solid #4b9cd3;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: IndianRed; color: white; border: none;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete this item?")) {
                // If user clicks OK, perform the delete action
                $.ajax({
                    type: 'POST',
                    url: 'pass_data_store.php',
                    data: $('#deleteForm').serialize(), // Serialize the form data
                    success: function(response) {
                        // Handle success response if needed
                        console.log('Delete successful');
                    },
                    error: function(error) {
                        // Handle error if needed
                        console.error('Error deleting item', error);
                    }
                });
            } else {
                // If user clicks Cancel, do nothing
                console.log('Delete canceled');
            }
        }
        let position_submit =
            "<?php echo ($data['Position'] != '2') ? 'yes' : 'no'; ?>";
        if (position_submit === 'yes') {
            $(document).ready(function() {
                $("#formId").submit(function(ev) {
                    ev.preventDefault();
                    let position =
                        "<?php echo (($data['ColorSizeEntry'] == '1')) ? 'yes' : 'no'; ?>";
                    if (position === 'yes') {
                        // Check if the Color and Size dropdowns are selected
                        let colorSelected = $("#colorSelect").val();
                        let sizeSelected = $("#sizeSelect").val();
                        let qty = $("#qty").val();
                        if (!colorSelected || !sizeSelected || !qty) {
                            alert("Please select Color and Size.");
                            return;
                        }
                    }

                    var form = $(this);
                    var url = form.attr('action');
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function(data) {
                            $.ajax({
                                type: 'POST',
                                url: 'clear_session.php', // Change the URL to the correct path of your server-side script
                                success: function(response) {
                                    // alert("Form Submitted Successfully");
                                    window.location.reload();

                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        },
                        error: function(response) {
                            alert("Some Error Occurred");
                        }
                    });

                });
            });
        } else {
            $(document).ready(function() {
                $("#formId").submit(function(ev) {
                    ev.preventDefault();
                    let position =
                        "<?php echo ($data['Position'] == '2' || ($data['ColorSizeEntry'] == '1')) ? 'yes' : 'no'; ?>";
                    if (position === 'yes') {
                        // Check if the Color and Size dropdowns are selected
                        let colorSelected = $("#colorSelect").val();
                        let sizeSelected = $("#sizeSelect").val();
                        let qty = $("#qty").val();
                        if (!colorSelected || !sizeSelected || !qty) {
                            alert("Please select Color and Size.");
                            return;
                        }
                    }

                    // let text = "Are you Sure to Proceed?";
                    // if (confirm(text)) {
                    var form = $(this);
                    var url = form.attr('action');
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function(data) {
                            // alert("Form Submitted Successfully");
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                    // }
                });
            });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="bootstrap/popper.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
</body>



</html>