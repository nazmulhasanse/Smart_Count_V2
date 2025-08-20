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


$sql_npt = "SELECT idlines FROM ework_daily_npt WHERE cardnumber = '$cardnumber' and end_time ='' ";
$result_npt = $conn->query($sql_npt);
if ($result_npt->num_rows > 1) {
    $npt_id = $result_npt->fetch_assoc();
    header("Location: npt_entry.php?id=".$npt_id['idlines']);
    exit;
}

if (isset($_SESSION['cardnumber'])) {
    $sql = "SELECT
    C.WorkOrder AS WorkOrder,
    C.Style AS Style,
    C.StepNumber AS Step,
    C.Description_StepName AS Step_Description,
    C.Customer,
    C.ColorSizeEntry AS ColorSizeEntry,
    C.line AS SewingLine,
    A.Position AS Position,
    B.docnumber as docnumber,
    C.cardnumber AS CardNo,
C.idlines,
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
    if ($result->num_rows > 1) {?>
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


            <?php  echo "You are assigned multiple Work Order which are open. Please inform the Responsible IE."; ?>
        </div>
    </body>

</html>

<?php exit; }else{
   
  
    if ($result->num_rows > 0) {
      // output data of each row
    //   $count = count($data);
      while($row = $result->fetch_assoc()) {
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


            <?php  echo "You are not assigned in any Work Order."; ?>
        </div>
    </body>

</html>

<?php  exit;}     
     }



// Set the timezone to Bangladesh
date_default_timezone_set('Asia/Dhaka');
$loginDatetime = date('Y-m-d H:'.'00:00');

// Calculate the end datetime by adding one hour
$endDatetime = date('Y-m-d H:'.'00:00', strtotime($loginDatetime . ' +1 hour'));



$startDatetime = date('Y-m-d H:'.'00:00', strtotime($loginDatetime . ' -1 hour'));


// Get the current date for today's total quantity
$currentDate = date('Y-m-d');

$sql_qty = "SELECT ework_id, color, size, end_datetime, DateTime,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass' AND ework_id < (SELECT MAX(ework_id) FROM eworker_operation)),
        0
    ) AS total_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass' AND DateTime BETWEEN '$loginDatetime' AND '$endDatetime'),
        0
    ) AS total_hour_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass' AND DateTime BETWEEN '$startDatetime' AND '$loginDatetime'),
        0
    ) AS total_last_hour_qty,
    COALESCE(
        (SELECT Qty FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass' ORDER BY ework_id DESC LIMIT 1),
        0
    ) AS last_row_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass'),
        0
    ) AS total_sum_qty,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass' AND DATE(DateTime) = '$currentDate'),
        0
    ) AS today_total_qty
    FROM eworker_operation
    WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' AND Status ='A' AND qty_type = 'pass'
    ORDER BY ework_id DESC
    LIMIT 1;";
    $result_qty = $conn->query($sql_qty);
 

    $submit_query = "SELECT end_datetime, DateTime,ework_id FROM eworker_operation WHERE cardnumber = '$cardnumber' AND Status ='A' AND qty_type = 'pass' ORDER BY ework_id DESC LIMIT 1";

    $submit_query_data = $conn->query($submit_query);

    if ($result_qty->num_rows > 0) {

        while($row_qty = $result_qty->fetch_assoc()) {
         $data_qty = $row_qty;
    
        }
      } else {
  
      }


    if ($submit_query_data->num_rows > 0) {
        // output data of each row
  
                $submitType = 'start'; 
         
        while($row_qty_submit = $submit_query_data->fetch_assoc()) {
         $data_qty_submit = $row_qty_submit;
         $submitType =  empty($data_qty_submit['end_datetime']) || is_null($data_qty_submit['end_datetime']) ? 'end' : 'start';
    
        }
      } else {
        $submitType = 'start'; 
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
            font-size: 12px;
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
        .npt-button {
            background-color: red;
            /* Blue color, you can change this */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 3px;
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
            height: 100px;
            width: 200px;
        }
        </style>
    </head>
<?php 
$doc_number = $data['docnumber'];
$sql = "SELECT idlines,StepNumber,Description_StepName FROM `eworker_assignment` WHERE cardnumber = '$cardnumber' AND docnumber = '$doc_number'";
// Execute the query
$result = $conn->query($sql);

// Check if any records were found and print the results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       $step_data[] = $row;
    }
} else {
    echo "0 results";
}

?>
    <body>
        <?php 
        if (isset($_SESSION['cardnumber'])) {
            $sql = "SELECT 
            B.Style,
            B.site,
            B.building,
            B.floor,
            B.Customer,
            B.line,
            C.StepNumber,
            C.docnumber,
            C.WorkOrder
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
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
             $data_npt = $row;
            }
          
        }
        }
        $query_npt = "SELECT * FROM ework_mrd_library WHERE LibraryName='NPT'";
        $result_npt = $conn->query($query_npt);
        ?>
        <div class="container">

        <div  style="float: left">
             <form   id='formnpt' method="POST" >

            <input name="Style" type="hidden" value="<?php echo $data_npt['Style']; ?>" />
            <input name="site"  type="hidden" value="<?php echo $data_npt['site']; ?>" />
            <input name="building"  type="hidden" value="<?php echo $data_npt['building']; ?>" />
            <input name="floor"  type="hidden" value="<?php echo $data_npt['floor']; ?>" />
            <input name="Customer"  type="hidden" value="<?php echo $data_npt['Customer']; ?>" />
            <input name="line"  type="hidden" value="<?php echo $data_npt['line']; ?>" />
            <input name="npt_type"  type="hidden" value="start" />
            <input name="npttype"  type="hidden" value="1" />
            <input name="start_time"  type="hidden" value="<?php echo date('h:ia');?>" />
            <input name="docdate"  type="hidden" value="<?php echo date('Y-m-d');?>" />
            <input name="cardnumber"  type="hidden" value="<?php echo $cardnumber;?>" />
            <input name="docnumber"  type="hidden" value="<?php echo $data_npt['docnumber'];?>" />
            <input name="WorkOrder"  type="hidden" value="<?php echo $data_npt['WorkOrder'];?>" />
            <input name="StepNumber"  type="hidden" value="<?php echo $data_npt['StepNumber'];?>" />
            <div class="select">
            </div>
            <button class="npt-button" >Start NPT</button>

             </form>
        </div>
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
                    <label for="step" style="font-size: 17px;color: crimson;">Choose a Step:</label>
                    <input type="hidden" id="idlines" name="idlines" class="number-input"
                        value="<?php echo  $data['idlines']; ?> ">
                    <select id="selectstep" style="padding: 3px;
    margin-bottom: 8px;">
                        <?php foreach ($step_data as $key => $value) { ?>
                        <option value="<?php echo $value['idlines'];?>" <?php echo ($value['StepNumber'] == $data['Step']) ? 'selected' : ''; ?>><?php echo $value['StepNumber'].'-'.$value['Description_StepName']; ?></option>
                        <?php }  ?>
                    </select>
                    </br>
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
                    <label style="font-size:10px"> <?php echo  $data['Step_Description']; ?></label></br>


                </div>
            </div>
            <div class="header" style="background-color: #ffc000;font-size: 13px;">
                <div class="header-label" style="margin-top: 22px;">
                    <label style="text-align: center;
                        display: block;
                        font-weight: bold;">Total qty:<?php
                    if(isset($data_qty['total_sum_qty']) && $data_qty['total_sum_qty'] !== '') {
                        echo $data_qty['total_sum_qty'];
                    }
                    ?></label>

                </div>
                <div class="header-label" style="margin-top: 22px;">
                    <label style="text-align: center;
                        display: block;
                        font-weight: bold;">Last Hr's qty:<?php
                    if(isset($data_qty['total_last_hour_qty']) && $data_qty['total_last_hour_qty'] !== '') {
                        echo $data_qty['total_last_hour_qty'];
                    }
                    ?></label>


                </div>
                <div class="header-label" style="margin-top: 22px;">
                    <label style="text-align: center;
                        display: block;
                        font-weight: bold;">This Hr's qty:<?php
                    if(isset($data_qty['total_hour_qty']) && $data_qty['total_hour_qty'] !== '') {
                        echo $data_qty['total_hour_qty'];
                    }
                    ?></label>


                </div>
                <div class="header-label" style="margin-top: 22px;">
                    <label style="text-align: center;
                        display: block;
                        font-weight: bold;">Today's qty:<?php
                    if(isset($data_qty['today_total_qty']) && $data_qty['today_total_qty'] !== '') {
                        echo $data_qty['today_total_qty'];
                    }
                    ?></label>


                </div>
            </div>
            <!-- Body section with input field  -->
            <form class="login-form"  id='formId' method="POST">
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
                     if ($data['ColorSizeEntry'] == '1') {
                          $sql_size_colors = "SELECT color, size FROM `ework_order_wise_color_size_qty` WHERE WorkOrder = '$workorder';";
                        $result_size_colors = $conn->query($sql_size_colors);
                        
                        if ($result_size_colors) {
                            if (property_exists($conn, 'driver_name') && $conn->$driver_name === 'mysqlnd') {
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
                      

                        <?php //if ($data['Position'] == '0' || $data['Position'] == '1') {?>

                        <?php if  (!isset($data_qty))  {?>

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
                        <?php }else{?>

                        <select name="color" class="select-box" id="colorSelect">
                            <option value="">--- Choose a Color ---</option>
                            <?php foreach ($data_size_colors as $data_size_color) : ?>
                            <option value="<?php echo $data_size_color['color']; ?>"
                                <?php echo ( $data_size_color['color'] == $data_qty['color']) ? 'selected' : ''; ?>>
                                <?php echo $data_size_color['color']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="size" class="select-box" id="sizeSelect">
                            <option value="">--- Choose a Size ---</option>
                            <?php foreach ($data_size_colors as $data_size_color) : ?>
                            <option value="<?php echo $data_size_color['size']; ?>"
                                <?php echo ( $data_size_color['size'] == $data_qty['size']) ? 'selected' : ''; ?>>
                                <?php echo $data_size_color['size']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <?php }?>
            
                     

                    </div>
                    <?php } ?>
                    <ul>
                        <label class="input-label" for="qty">Qty:</label>
                        <?php if (isset($data_qty)) : ?>

                        <li> <input style="font-size:15px;text-align:center" class="number-input"
                                value="<?php echo isset($data_qty['last_row_qty']) . ' / ' . isset($data_qty['total_qty']); ?>"
                                readonly>
                                <input type="hidden" id="qty_type" name="qty_type" class="number-input" value="pass">
                                <input type="hidden" id="qty" name="qty" class="number-input" value="1"
                               >

                        </li>
                        <?php else: ?>
                        <li>
                            <input type="hidden" id="qty_type" name="qty_type" class="number-input" value="pass">
                            <input type="number" id="qty" name="qty" class="number-input" value="1"
                                 require readonly>
                         
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
           

                <!-- Footer section with submit button -->
      <!-- Footer section with submit button -->
    
      <div class="footer">
    <?php 

       
        $buttonId = $submitType == 'start' ? 'submitButton2' : 'submitButton1';
        $buttonColor = $submitType == 'end' ? 'red' : '';
    ?>
    <input type="hidden" id="ework_id" name="ework_id" class="number-input" value="<?php echo $data_qty_submit['ework_id'] ?? ''; ?>">
    <input type="hidden" id="submit_type" name="submit_type" class="number-input" value="<?php echo $submitType; ?>">
    <button class="button pass-button" id="<?php echo $buttonId; ?>" type="submit" value="Submit" style="background-color:<?php echo $buttonColor; ?>">
        <?php echo ucfirst($submitType); ?>
    </button>
</div>



            </form>
                  <!-- <script>
      document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("formId");
            form.addEventListener("submit", function (event) {
                var submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                // Prevent the default form submission behavior
                event.preventDefault();
            });
        });</script> -->

      <script>
        $(document).ready(function() {
            $('#selectstep').change(function() {
                var selectedValue = $(this).val();
                var selectedValueidlines = document.getElementById("idlines").value;
                $.ajax({
                    url: 'update_step.php',
                    type: 'POST',
                    data: {idlines: selectedValue, id: selectedValueidlines},
                    success: function(response) {
                        //alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            });
        });
    </script>
<script>

    $(document).ready(function() {
        // Your provided $datetime value
        var datetimeString = '<?php echo $data_qty_submit['DateTime']; ?>';
        var submitetype = '<?php echo $submitType; ?>';
        var datetime = new Date(datetimeString);
        var currentTime = new Date();
        var timeDiff = datetime.getTime() - currentTime.getTime();

        if (timeDiff < 0) {
            timeDiff = 5 * 60 * 1000; // 5 minutes in milliseconds
        } else {
            timeDiff += 5 * 60 * 1000; // 5 minutes in milliseconds
        }
    console.log(timeDiff);
    console.log(submitetype);
    if (submitetype == 'end') {
        setTimeout(function() {
            $("#formId").submit();
        }, timeDiff);  
    }

    });


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
                    let position ="<?php echo (($data['ColorSizeEntry'] == '1')) ? 'yes' : 'no'; ?>";
                    if (position === 'yes') {
                        // Check if the Color and Size dropdowns are selected
                        let colorSelected = $("#colorSelect").val();
                        let sizeSelected = $("#sizeSelect").val();
                        let qty = $("#qty").val();
                        console.log(colorSelected);
                        console.log(sizeSelected);
                        console.log(qty);
                        if (!colorSelected || !sizeSelected || !qty) {
                            alert("Please select Color and Size.");
                            return;
                        }
                    }

                    var form = $(this);
                    var url = 'passquantity_Operator_Store.php';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                              success: function(response) {
                            // var audio = new Audio('start.mp3');
                            // audio.onended = function() {
                                window.location.reload();
                            // };
                            // audio.play();
                        },                    
                        error: function(response) {
                            alert("Some Error Occurred");
                        }
                    });

                });
            });
       



            
            $(document).ready(function() {
                $("#formnpt").submit(function(ev) {
                    ev.preventDefault();
                    var submitetype = '<?php echo $submitType; ?>';
                    console.log(submitetype);
                    if (submitetype == 'end') {
                        alert("Before starting NPT, please press the 'End' button.");
                        return;
                    }
                    var formData = $(this).serialize(); // Get form data
                    var url = 'npt_entry_store.php';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        success: function(response) {
                        console.log("Response: ", response);
                        try {
                            if (response.status === "success") {
                                console.log("Redirecting to PHP page with ID: ", response.last_id);
                                // Redirect to another PHP page with the last inserted ID
                                var lastId = response.last_id;
                                window.location.href = "npt_entry.php?id=" + lastId;
                            } else {
                                alert("Error: " + response.message);
                            }
                        } catch (e) {
                            console.error("Parsing error:", e);
                            alert("Some Error Occurred");
                        }
                    },

                    });

                });
            });
        </script>
    </body>

</html>