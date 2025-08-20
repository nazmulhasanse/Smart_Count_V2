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
    AND C.WorkOrder = B.WorkOrder;
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ework Operation</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-confirm.min.js"></script>

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

    // total qty from worker oparation 
    $sql_qty = " SELECT ework_id, color, size,
    COALESCE(
        (SELECT SUM(Qty) FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-pass' AND ework_id < (SELECT MAX(ework_id) FROM eworker_operation)),
        0
    ) AS total_qty,
    COALESCE(
        (SELECT Qty FROM eworker_operation WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-pass' ORDER BY ework_id DESC LIMIT 1),
        0
    ) AS last_row_qty
FROM eworker_operation
WHERE cardnumber = '$cardnumber' AND WorkOrder = '$workorder' and Status ='A' and qty_type = 'rework-pass'
ORDER BY ework_id DESC
LIMIT 1; 
        ";
    $result_qty = $conn->query($sql_qty);
    if ($result_qty->num_rows > 0) {
        // output data of each row
        while($row_qty = $result_qty->fetch_assoc()) {
         $data_qty = $row_qty;
        
        }
      } else {
        echo "0 results";
      }
  
}
if ($data['Position'] != '2') {
    header("Location: reworkpassquantity.php");
    exit;
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
            width: 45%;
            margin: 0px 15px 23px 0px;
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
                    <label><?php echo  $workorder; ?></label></br>
                    <label>Stpe: <?php echo  $data['Step']; ?></label></br>
                </div>
                <div class="header-label">
                    <label><?php echo  $data['CardNo']; ?></label></br>
                    <label><?php echo  $data['Style']; ?></label></br>
                    <?php if ($data['Position'] == '0') {?>
                    <label>Operator</label></br>
                    <?php } elseif ($data['Position'] == '1') {?>
                    <label>Helper</label></br>
                    <?php }else{?>
                    <label>Quality Inspector</label></br>
                    <?php } ?>

                </div>
            </div>
            <!-- Body section with input field  -->
            <form class="login-form" action="pass_data_store.php" id='formId' method="POST">
                <div class=" body">

                    <?php
                    if ($data['Position'] == '2'|| $data['Position'] == '0' &&  $data['ColorSizeEntry'] == '1') {
                        $sql_size_colors = "SELECT color, size FROM `ework_order_wise_color_size_qty` WHERE WorkOrder = '$workorder';";
                        $result_size_colors = $conn->query($sql_size_colors);
                        
                        if ($result_size_colors) {
                            // Check if the MySQL native driver is available
                            // if ($conn->driver_name === 'mysqlnd') {
                            //     // Fetch all rows as an associative array
                            //     $data_size_colors = $result_size_colors->fetch_all(MYSQLI_ASSOC);
                            // } else {
                                // Fetch rows one by one if the native driver is not available
                                $data_size_colors = [];
                                while ($row = $result_size_colors->fetch_assoc()) {
                                    $data_size_colors[] = $row;
                                }
                            // }
                        
                        } else {
                            echo "0 results";
                        }
                 

                    ?>

                    <div class="select-container">
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
                    </div>

                    <?php } ?>


                    <input type="hidden" id="ework_id" name="ework_id" class="number-input"
                        value="<?php echo $data_qty['ework_id'] ?>">
                    <ul>
                        <label class="input-label" for="qty">Qty:</label>
                        <li>
                            <input type="number" id="last_row_update_qty" name="last_row_update_qty"
                                class="number-input" value="<?php echo $data_qty['last_row_qty'] ?>"
                                placeholder="Enter quantity" max="20" require>
                        </li>
                    </ul>


                </div>
                <!-- Footer section with submit button -->
                <div class="footer">

                    <button class="button pass-button " id='submitButton' type="submit" value="Submit">Submit</button>

                </div>
            </form>
            <div class="">
                <a class="button pass-button btn-home" href="index.php"> <i class="fa fa-home"
                        style="color:white"></i></a>
            </div>
        </div>


        <script>
        $(document).ready(function() {
            $("#formId").submit(function(ev) {
                ev.preventDefault();
                let position =
                    "<?php echo ($data['Position'] == '2' || ($data['Position'] == '0' && $data['ColorSizeEntry'] == '1')) ? 'yes' : 'no'; ?>";
                if (position === 'yes') {
                    // Check if the Color and Size dropdowns are selected
                    let colorSelected = $("#colorSelect").val();
                    let sizeSelected = $("#sizeSelect").val();
                    let qty = $("#last_row_update_qty").val();
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
                        alert("Form Submitted Successfully");
                        window.location.href = "reworkpassquantity.php";
                    },
                    error: function(data) {
                        alert("Some Error Occurred");
                    }
                });
                // }
            });
        });
        </script>
    </body>

</html>