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
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    AND C.WorkOrder = B.WorkOrder;
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $data = $row;

            $workorder = $data['WorkOrder'];
            $docnumber = $data['docnumber'];
        }
    } else { ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ework Oparation</title>
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

    // Set the timezone
    date_default_timezone_set('Asia/Dhaka');

    // Define $loginDatetime
    $loginDatetime = date('Y-m-d H:' . '00:00');

    // Define $endDatetime by adding one hour
    $endDatetime = date('Y-m-d H:' . '00:00', strtotime($loginDatetime . ' +1 hour'));

    // Define $startDatetime by subtracting one hour
    $startDatetime = date('Y-m-d H:' . '00:00', strtotime($loginDatetime . ' -1 hour'));

    // Define $currentDate
    $currentDate = date('Y-m-d');


    // total qty from worker oparation 
    $sql_qty = "SELECT ework_id, color, size, end_datetime,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail')),
        0
    ) AS total_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail') AND DateTime BETWEEN '$loginDatetime' AND '$endDatetime'),
        0
    ) AS total_hour_failed,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail') AND DateTime BETWEEN '$startDatetime' AND '$loginDatetime'),
        0
    ) AS total_last_hour_failed,
    COALESCE(
        (SELECT Qty FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail') ORDER BY ework_id DESC LIMIT 1),
        0
    ) AS last_row_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail')),
        0
    ) AS total_sum_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail') AND DATE(DateTime) = '$currentDate'),
        0
    ) AS today_total_failed
FROM eworker_operation
WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type IN ('fail')
ORDER BY ework_id DESC
LIMIT 1;";

    $result_qty = $conn->query($sql_qty);
    if ($result_qty === false) {
        // Handle the SQL error
        echo "Error executing the query: " . $conn->error;
    } else {
        if ($result_qty->num_rows > 0) {
            // output data of each row
            while ($row_qty = $result_qty->fetch_assoc()) {
                $data_qty = $row_qty;
            }
        } else {
            echo "0 results";
        }
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


    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/jquery-ui.js"></script>

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
            width: 50%;
            margin: 0px 1px 18px 0px;
            padding: 6px;
        }
    </style>

    <!-- Bootstrap CSS -->
<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script src="bootstrap/bootstrap.min.js"></script>


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
                <label> <?php echo  $data['Step_Description']; ?></label></br>


            </div>
        </div>
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#failedStatusModal">Status</button>
        <?php
        // Define $dataArray outside of the conditional block
        $dataArray = [];
        if ($data['Position'] == '2') {
            $style =  $data['Style'];
            $line  =  $data['SewingLine'];
            $sql_PartCodes = "SELECT PartName FROM `ework_partname` where WorkOrder = '$workorder' and  Style = '$style' and line ='$line' and WO_docnumber = '$docnumber'";
            $result_PartCodes = $conn->query($sql_PartCodes);

            if ($result_PartCodes->num_rows > 0) {
                $data_PartCodes = [];
                while ($row = $result_PartCodes->fetch_assoc()) {
                    $data_PartCodes[] = $row;
                }
            } else {
                echo "0 results for PartCodes";
            }

            $sql_ReworkReasonCodes = "SELECT CONCAT(Code, ' - ', Description) AS Code FROM `ework_mrd_library` WHERE LibraryName ='ReworkReasonCodes';";
            $result_ReworkReasonCodes = $conn->query($sql_ReworkReasonCodes);

            if ($result_ReworkReasonCodes->num_rows > 0) {
                $data_ReworkReasonCodes = [];
                while ($row = $result_ReworkReasonCodes->fetch_assoc()) {
                    $data_ReworkReasonCodes[] = $row;
                }
            } else {
                echo "0 results for ReworkReasonCodes";
            }

            $sql_StepNumber = "SELECT CONCAT(StepNumber, ' - ', Description_StepName) as StepNumber   FROM `ework_sales_order` WHERE WorkOrder = '$workorder' and docnumber = '$docnumber' order by StepNumber asc ";
            $result_StepNumber = $conn->query($sql_StepNumber);

            if ($result_StepNumber->num_rows > 0) {
                $data_StepNumber = [];
                while ($row = $result_StepNumber->fetch_assoc()) {
                    $data_StepNumber[] = $row;
                }
            } else {
                echo "0 results for StepNumber";
            }
        }
        ?>

        <?php if ($data['Position'] == '2') : ?>
            <script>
                var dataArray = [];
                var availablePartCodes =
                    <?php echo !empty($data_PartCodes) ? json_encode(array_column($data_PartCodes, 'PartName')) : '[]'; ?>;
                var availableReworkReasonCodes =
                    <?php echo !empty($data_ReworkReasonCodes) ? json_encode(array_column($data_ReworkReasonCodes, 'Code')) : '[]'; ?>;
                var availableStepNumbers =
                    <?php echo !empty($data_StepNumber) ? json_encode(array_column($data_StepNumber, 'StepNumber')) : '[]'; ?>;

                function addValue() {
                    var selectedPartCode = document.getElementById("SelectPartCode").value;
                    var selectedReworkReasonCode = document.getElementById("SelectReworkReasonCodes").value;
                    var selectedStepNumber = document.getElementById("SelectStepNumber").value;
                    var Defect_qty = document.getElementById("Defect_qty").value;

                    // Check if all required fields have values
                    if (!selectedPartCode || !selectedReworkReasonCode || !selectedStepNumber || !Defect_qty) {
                        alert("All fields are required. Please fill in all the fields.");
                        return; // Exit the function if any required field is empty
                    }

                    // Validate PartName, Code, and StepNumber against available data
                    if (!validateInput(selectedPartCode, availablePartCodes)) {
                        alert("Please select a valid PartName.");
                        return;
                    }

                    if (!validateInput(selectedReworkReasonCode, availableReworkReasonCodes)) {
                        alert("Please select a valid Rework Reason Code.");
                        return;
                    }

                    if (!validateInput(selectedStepNumber, availableStepNumbers)) {
                        alert("Please select a valid Step Number.");
                        return;
                    }

                    selectedStepNumber = selectedStepNumber.replace("&", "and");


                    // Add to the dataArray
                    var value = {
                        'PartCode': selectedPartCode,
                        'ReworkReason': selectedReworkReasonCode,
                        'StepNumber': selectedStepNumber,
                        'Defect_qty': Defect_qty
                    };

                    dataArray.push(value);

                    // Update the display of selected values
                    updateDisplay();

                    // Clear the selected values
                    clearAutocomplete();
                }

                function validateInput(inputValue, availableValues) {
                    // Check if inputValue exists in the availableValues array
                    return availableValues.indexOf(inputValue) !== -1;
                }

                function updateDisplay() {
                    var container = document.getElementById("addValueTable");
                    container.innerHTML =
                        "<tr><th>Part Code</th><th>Rework Reason</th><th>Step</th><th>No.of Defect</th><th>Action</th></tr>";
                    dataArray.forEach(function(value) {
                        var row = document.createElement("tr");
                        row.innerHTML =
                            "<td>" + value['PartCode'] + "</td>" +
                            "<td>" + value['ReworkReason'] + "</td>" +
                            "<td>" + value['StepNumber'] + "</td>" +
                            "<td>" + value['Defect_qty'] + "</td>" +
                            "<td><a href='#' class='btn btn-danger btn-sm' onclick='removeValue(this)'><i style='color: red;font-size: 15px;'></i>Delete</a></td>";
                        container.appendChild(row);
                    });
                }


                function clearAutocomplete() {
                    $("#SelectPartCode").val("");
                    $("#SelectReworkReasonCodes").val("");
                    $("#SelectStepNumber").val("");
                    $("#Defect_qty").val("");
                }

                function removeValue(element) {
                    // Find the closest table row (parent of the delete button)
                    var row = $(element).closest('tr');

                    // Remove the corresponding value from the dataArray
                    var index = row.index() - 1; // Subtract 1 to account for the table header
                    dataArray.splice(index, 1);

                    // Update the display after removal
                    updateDisplay();
                }


                function initAutocomplete() {
                    var availablePartCodes =
                        <?php echo !empty($data_PartCodes) ? json_encode(array_column($data_PartCodes, 'PartName')) : '[]'; ?>;
                    var availableReworkReasonCodes =
                        <?php echo !empty($data_ReworkReasonCodes) ? json_encode(array_column($data_ReworkReasonCodes, 'Code')) : '[]'; ?>;
                    var availableStepNumbers =
                        <?php echo !empty($data_StepNumber) ? json_encode(array_column($data_StepNumber, 'StepNumber')) : '[]'; ?>;
                    var availableDescriptionStepNames =
                        <?php echo !empty($data_Defect_qty) ? json_encode(array_column($data_Defect_qty, 'Defect_qty')) : '[]'; ?>;


                    $("#SelectPartCode").autocomplete({
                        source: availablePartCodes,
                        minLength: 0,
                    }).focus(function() {
                        // Trigger autocomplete on focus
                        $(this).autocomplete("search", "");
                    });
                    $("#SelectReworkReasonCodes").autocomplete({
                        source: availableReworkReasonCodes,
                        minLength: 0,
                    });

                    // Add autocomplete for StepNumber and Description_StepName
                    $("#SelectStepNumber").autocomplete({
                        source: availableStepNumbers,
                        minLength: 0,
                    }).focus(function() {
                        // Trigger autocomplete on focus
                        $(this).autocomplete("search", "");
                    });

                }

                $(document).ready(function() {
                    initAutocomplete();
                });
            </script>
            <!-- HTML code -->

            <div class="select-container">
                <input type="text" name="SelectPartCode" class="select-box" id="SelectPartCode" placeholder="PartCode"
                    autocomplete="off" require>
                <input type="text" name="SelectReworkReasonCodes" class="select-box" id="SelectReworkReasonCodes"
                    placeholder="ReworkReasonCodes" autocomplete="off" require>

                <input type="text" name="SelectStepNumber" class="select-box" id="SelectStepNumber"
                    placeholder="Step Number" autocomplete="off" require>
                <input type="number" name="Defect_qty" class="select-box" id="Defect_qty" placeholder="No.of Defect"
                    autocomplete="off" require>
                <button style="height: 50px; background: green;color: white;" type="button" name="addValue"
                    onclick="addValue()">Add</button>
            </div>
            <div class="select-container">
                <table id="addValueTable" class="table">
                    <!-- Existing table header -->
                    <tr>
                        <th>Part Code</th>
                        <th>Rework Reason</th>
                        <th>Step</th>
                        <th>No.of Defect</th>
                        <th>Action</th>
                    </tr>
                    <!-- Existing table rows -->
                    <?php foreach ($dataArray as $value) : ?>
                        <tr>
                            <td><?php echo $value['PartCode']; ?></td>
                            <td><?php echo $value['ReworkReason']; ?></td>
                            <td><?php echo $value['StepNumber']; ?></td>
                            <td><?php echo $value['Defect_qty']; ?></td>
                            <td><a href="#" class="btn btn-danger" onclick="removeValue(this)"><i
                                        style="color: red;font-size: 20px;" class="fa fa-trash-o"></i> Delete</a>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>




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

                <ul>
                    <label class="input-label" for="qty">Qty:</label>
                    <?php if (isset($_SESSION['form_submitted'])) : ?>

                        <li> <input style="font-size:15px;text-align:center" class="number-input"
                                value="<?php echo $data_qty['last_row_qty'] . ' / ' . $data_qty['total_qty']; ?>"
                                readonly>

                        </li>
                    <?php else: ?>
                        <li>
                            <input type="hidden" id="qty_type" name="qty_type" class="number-input" value="fail">
                            <input type="number" id="qty" name="qty" class="number-input" value="1"
                                placeholder="Enter quantity" require readonly>
                        </li>
                    <?php endif; ?>
                </ul>


            </div>
            <!-- Footer section with submit button -->
            <div class="footer">
                <?php if (!isset($_SESSION['form_submitted'])) : ?>
                    <button class="button pass-button " id='submitButton' type="submit" value="Submit">Submit</button>
                <?php endif; ?>
            </div>
        </form>
        <div class="footer">
            <?php if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) : ?>
                <form method="post" action="failquantity_edite.php">
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
            <a class="button pass-button" href="status.php"> <i
                    style="color:white"></i>Home</a>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="failedStatusModal" tabindex="-1" aria-labelledby="failedStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #f8d7da; border: 2px solid #f5c2c7; border-radius: 10px;">
            <div class="modal-header" style="background-color: #f5c2c7; color: white; border-bottom: 2px solid #f8d7da;">
                <h5 class="modal-title" id="failedStatusModalLabel">Failed Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;"></button>
            </div>

            <!-- Updated PHP Section -->
            <div class="modal-body" style="color: #333; padding: 20px; background-color: #f8e8ea; font-family: 'Arial', sans-serif;">
                <h5>Total Failed Quantity:
                    <?php
                    if (isset($data_qty['total_qty']) && $data_qty['total_qty'] !== '') {
                        echo $data_qty['total_qty'];
                    }
                    ?>
                </h5>

                <h5>Last Hour Failed Qty:
                    <?php
                    if (isset($data_qty['total_last_hour_failed']) && $data_qty['total_last_hour_failed'] !== '') {
                        echo $data_qty['total_last_hour_failed'];
                    }
                    ?>
                </h5>

                <h5>This Hour Failed Qty:
                    <?php
                    if (isset($data_qty['total_hour_failed']) && $data_qty['total_hour_failed'] !== '') {
                        echo $data_qty['total_hour_failed'];
                    }
                    ?>
                </h5>

                <h5>Today's Failed Qty:
                    <?php
                    if (isset($data_qty['today_total_failed']) && $data_qty['today_total_failed'] !== '') {
                        echo $data_qty['today_total_failed'];
                    }
                    ?>
                </h5>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="background-color: #f8d7da; border-top: 2px solid #f5c2c7;">
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

        $(document).ready(function() {
            $("#formId").submit(function(ev) {
                ev.preventDefault();

                // Check if the Color and Size dropdowns are selected
                // let SelectPartCode = $("#SelectPartCode").val();
                // let SelectReworkReasonCodes = $("#SelectReworkReasonCodes").val();
                // let qty = $("#qty").val();

                // if (!SelectPartCode || !SelectReworkReasonCodes || !qty) {
                //     alert("Please select PartCode and ReworkReason.");
                //     return;
                // }

                // Add the values to dataArray
                // addValue();

                // Serialize the form data
                var formData = $(this).serialize();

                // Convert dataArray to JSON string
                var dataArrayJSON = JSON.stringify(dataArray);

                // Include dataArray in the serialized data
                formData += '&dataArray=' + dataArrayJSON;

                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(response) {
                        // Parse the JSON response if needed
                        var lastId = JSON.parse(response['id']);
                        window.location.replace("failquantity_edite.php?last_id=" + lastId);
                    },
                    error: function(response) {
                        // Log the error details to the console
                        console.error(response);
                        alert("Some Error Occurred");
                    }
                });

            });
        });
    </script>
</body>

</html>