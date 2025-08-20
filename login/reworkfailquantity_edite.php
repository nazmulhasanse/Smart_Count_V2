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
    AND C.WorkOrder = B.WorkOrder
    limit 1;
    ";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
       $data = $row;
     
       $workorder = $data['WorkOrder'];
      }
     } else {?>
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


            <?php  echo "You are not assigned in any Work Order."; ?>
        </div>
    </body>

</html>

<?php  exit;}
// Ensure that $dataFromUrl is properly sanitized to prevent SQL injection
$last_id = $_GET['last_id'] ?? '';


if ($last_id) {
$sql_defect = "SELECT PartName as PartCode, DefectReason as ReworkReason,StepNumber,No_of_Defect as Defect_qty, ID, OperationID FROM ework_defect WHERE OperationID = $last_id and Status = 'A'";
$result_defect = $conn->query($sql_defect);
if ($result_defect === false) {
    // Handle the SQL error
    echo "Error executing the query: " . $conn->error;
} else {
if ($result_defect->num_rows > 0) {
   $defet_data = [];
    while($row_defect = $result_defect->fetch_assoc()) {
     $defet_data[] = $row_defect;
   
    }
  } else {
    //echo "0 results";
  }
}
}


    // total qty from worker oparation 
    $sql_qty = " SELECT ework_id, color, size,end_datetime,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-fail' AND ework_id < (SELECT MAX(ework_id) FROM eworker_operation)),
        0
    ) AS total_qty,
    COALESCE(
        (SELECT Qty FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-fail' ORDER BY ework_id DESC LIMIT 1),
        0
        ) AS last_row_qty,
    COALESCE(
        (SELECT sum(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-fail'),
        0
    ) AS total_sum_qty
    FROM eworker_operation
    WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-fail'
    ORDER BY ework_id DESC
    LIMIT 1;
        ";
    $result_qty = $conn->query($sql_qty);
    if ($result_qty === false) {
        // Handle the SQL error
        echo "Error executing the query: " . $conn->error;
    } else {
    if ($result_qty->num_rows > 0) {
        // output data of each row
        while($row_qty = $result_qty->fetch_assoc()) {
         $data_qty = $row_qty;
        }
      } else {
        //echo "0 results";
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
                    <?php if ($data['Position'] == '0') {?>
                    <label>Operator</label></br>
                    <?php } elseif ($data['Position'] == '1') {?>
                    <label>Helper</label></br>
                    <?php }else{?>
                    <label>Quality Inspector</label></br>
                    <?php } ?>
                    <label><?php echo  $data['Style']; ?></label></br>
                    <label> <?php echo  $data['Step_Description']; ?></label></br>


                </div>
            </div>
            <label style="text-align: center;
                display: block;
                font-weight: bold;">Total qty:<?php
            if(isset($data_qty['total_sum_qty']) && $data_qty['total_sum_qty'] !== '') {
                echo $data_qty['total_sum_qty'];
            }
            ?></label>
            <?php
            // Define $dataArray outside of the conditional block
            $dataArray = [];
            if (isset($defet_data)) {
                $dataArray = $defet_data;
            }
           
            if ($data['Position'] == '2') {
                $style =  $data['Style'];
                $line  =  $data['SewingLine'];
                $sql_PartCodes = "SELECT PartName FROM `ework_partname` where WorkOrder = '$workorder' and  Style = '$style' and line ='$line'";
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

                $sql_StepNumber = "SELECT CONCAT(StepNumber, ' - ', Description_StepName) as StepNumber   FROM `ework_sales_order` WHERE WorkOrder = '$workorder' ";
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

            function addValue() {
                var selectedPartCode = $("#SelectPartCode").val();
                var selectedReworkReasonCode = $("#SelectReworkReasonCodes").val();
                var selectedStepNumber = $("#SelectStepNumber").val();
                var Defect_qty = $("#Defect_qty").val();

                // Check if all required fields have values
                if (!selectedPartCode || !selectedReworkReasonCode || !selectedStepNumber || !Defect_qty) {
                    alert("All fields are required. Please fill in all the fields.");
                    return; // Exit the function if any required field is empty
                }

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

            function updateDisplay() {
                var container = $("#addValueTable");
                container.html(
                    "<tr><th>Part Code</th><th>Rework Reason</th><th>Step</th><th>No. of Defect</th><th>Action</th></tr>"
                );

                dataArray.forEach(function(value) {
                    var row = $("<tr>");
                    row.html(
                        "<td>" + value['PartCode'] + "</td>" +
                        "<td>" + value['ReworkReason'] + "</td>" +
                        "<td>" + value['StepNumber'] + "</td>" +
                        "<td>" + value['Defect_qty'] + "</td>" +
                        "<td><a href='#' class='delete-btn' onclick='removeValue(this)'><i style='color: red;font-size: 12px;' class='fa fa-trash-o'></i></a></td>"
                    );
                    container.append(row);
                });
            }

            function clearAutocomplete() {
                $(".SelectPartCode").val("");
                $(".SelectReworkReasonCodes").val("");
                $(".SelectStepNumber").val("");
                $(".Defect_qty").val("");
            }



            // Rest of your existing code...

            // Initialize autocomplete on document ready
            $(document).ready(function() {
                initAutocomplete();
            });


            function initAutocomplete() {
                var availablePartCodes =
                    <?php echo !empty($data_PartCodes) ? json_encode(array_column($data_PartCodes, 'PartName')) : '[]'; ?>;
                var availableReworkReasonCodes =
                    <?php echo !empty($data_ReworkReasonCodes) ? json_encode(array_column($data_ReworkReasonCodes, 'Code')) : '[]'; ?>;
                var availableStepNumbers =
                    <?php echo !empty($data_StepNumber) ? json_encode(array_column($data_StepNumber, 'StepNumber')) : '[]'; ?>;
                var availableDescriptionStepNames =
                    <?php echo !empty($data_Defect_qty) ? json_encode(array_column($data_Defect_qty, 'Defect_qty')) : '[]'; ?>;


                $(".SelectPartCode").autocomplete({
                    source: availablePartCodes,
                    minLength: 0,
                });

                $(".SelectReworkReasonCodes").autocomplete({
                    source: availableReworkReasonCodes,
                    minLength: 0,
                });

                // Add autocomplete for StepNumber and Description_StepName
                $(".SelectStepNumber").autocomplete({
                    source: availableStepNumbers,
                    minLength: 0,
                });


            }

            $(document).ready(function() {
                initAutocomplete();
            });



            function toggleEdit(element) {
                // Find the closest table row (parent of the edit button)
                var row = $(element).closest('tr');

                // Toggle the edit mode for the row
                row.toggleClass('editing');

                // Update the buttons' visibility
                $('.edit-btn', row).toggle();
                $('.save-btn', row).toggle();


                // Toggle the readOnly property for input fields
                $('.editable-input', row).prop('readOnly', function(_, value) {
                    return !value;
                });
                // Reinitialize autocomplete for input fields
                if (row.hasClass('editing')) {
                    initAutocomplete();
                }
            }


            function saveEdit(element) {
                // Find the closest table row (parent of the save button)
                var row = $(element).closest('tr');

                console.log('Row:', row);

                // Get the edited values
                var editedPartCode = $('.editable-part-code', row).val();
                var editedReworkReason = $('.editable-rework-reason', row).val();
                var editedStepNumber = $('.editable-step-number', row).val();
                var editedDefectQty = $('.editable-defect-qty', row).val();
                var editablestepOperationID = $('.editable-step-OperationID', row).val();
                var editablestepID = $('.editable-step-ID', row).val();
                var url = 'editquantity.php';
                // Your AJAX request
                $.ajax({
                    type: "POST",
                    url: url, // Replace with your actual URL
                    data: {
                        partCode: editedPartCode,
                        reworkReason: editedReworkReason,
                        stepNumber: editedStepNumber,
                        defectQty: editedDefectQty,
                        stepOperationID: editablestepOperationID,
                        stepID: editablestepID
                    },
                    success: function(response) {
                        // Parse the JSON response if needed
                        location.reload();
                    },
                    error: function(response) {
                        // Log the error details to the console
                        console.error(xhr.responseText);
                        alert("Some Error Occurred");
                    }
                });
            }


            function removeValue(element) {
                var row = $(element).closest('tr');
                var editablestepID = $('.editable-step-ID', row).val();

                // Display a confirmation dialog
                var isConfirmed = confirm("Are you sure you want to delete this item?");

                if (isConfirmed) {
                    var EntryType = 'Delete';
                    var url = 'editquantity.php';

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            EntryType: EntryType,
                            stepID: editablestepID
                        },
                        success: function(response) {
                            // Parse the JSON response if needed
                            alert("Deleted Successfully!");
                            window.location.href = "reworkfailquantity.php";
                        },
                        error: function(xhr, status, error) {
                            // Log the error details to the console
                            console.error(xhr.responseText);
                            alert("Some Error Occurred");
                        }
                    });
                }
            }
            </script>
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
                    <tr class="editable-row">
                        <input type="hidden" class="number-input  editable-step-OperationID"
                            value="<?php echo $value['OperationID']; ?>"><input type="hidden"
                            class="number-input  editable-step-ID" value="<?php echo $value['ID']; ?>">


                        <td><input type="text" class="number-input editable-input editable-part-code SelectPartCode"
                                value="<?php echo $value['PartCode']; ?>" readonly></td>
                        <td><input type="text"
                                class="number-input editable-input editable-rework-reason SelectReworkReasonCodes"
                                value="<?php echo $value['ReworkReason']; ?>" readonly>
                        </td>
                        <td><input type="text" class="number-input editable-input editable-step-number SelectStepNumber"
                                value="<?php echo $value['StepNumber']; ?>" readonly></td>
                        <td><input type="text" class="number-input editable-input editable-defect-qty"
                                value="<?php echo $value['Defect_qty']; ?>" readonly></td>
                        <td>
                            <a href="#" class="edit-btn" onclick="toggleEdit(this)"><i
                                    style="color: blue; font-size: 17px;"></i></a>
                            <a href="#" class="save-btn" style="display: none;" onclick="saveEdit(this)"><i
                                    style="color: green; font-size: 17px;" class="fa fa-check"></i></a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="removeValue(this)"><i
                                    style="color: red; font-size: 17px;"></i>Delete</a>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </table>
            </div>
            <?php endif; ?>




            <!-- Footer section with submit button -->
            <div class="footer">
            </div>


            <div class="">
                <a class="btn btn-success btn-lg" href="status.php"> <i
                        style="color:white"></i>Back</a>
            </div>
        </div>



    </body>

</html>