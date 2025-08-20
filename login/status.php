<?php

include './ie/db_connection.php';
session_start();
unset($_SESSION['form_submitted']);
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


            <?php  echo "You are assigned in multiple Work Order which are open. Please inform the Responsible IE."; ?>
        </div>
    </body>

</html>

<?php exit; }else{
   
    
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
        <title>Ework Operation</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <script src="js/3.3.1/jquery.min.js"></script>
        <script src="js/jquery-confirm.min.js"></script>
        <style>
        body {
            font-family: serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            font-size: 12px !important;
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

<?php  exit;}}
}
if ($data['Position'] != '2') {
    header("Location: bulk_status.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ework Operation</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <script src="js/3.3.1/jquery.min.js"></script>
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

        .header-logout {
            display: flex;
            justify-content: space-between;
            padding: 26px 0 !important;
            /* Increased padding for better spacing */
            margin-bottom: 20px !important;
            /* Adjusted margin */
        }

        /* Add new styles for the logout button */
        form {
            position: absolute;
            top: 10px;
            right: 10px;
        }

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

        .header-label {
            font-weight: bold;
            margin-bottom: 10px;
            /* Added margin between labels */
        }

        .header-label label {
            display: block;
        }

        .body {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
        }

        .button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .rework-button {
            background-color: orange;
            color: #fff;
            width: 120px;
            height: 56px;
            margin-right: 44px;
        }
        .pass-button {
            background-color: green;
            color: #fff;
            width: 120px;
            height: 56px;
            margin-right: 44px;
        }

        .fail-button {
            background-color: red;
            color: #fff;
            width: 120px;
            height: 56px;
            margin-right: 44px;
        }

        .reject-button {
            background-color: black;
            color: #fff;
            width: 120px;
            height: 56px;
/*            margin-right: 44px;*/
        }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header-logout">
                <form method="post">
                    <button class="logout-button" name="logout">Logout</button>
                </form>
            </div>
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
            <div class="body">
                <a href="reworkquantity.php"><button class="button rework-button">Re-Work</button></a>
                <a href="passquantity.php"><button class="button pass-button">Pass</button></a>
                <a href="failquantity.php"><button class="button fail-button">Fail</button></a>
                <a href="rejectquantity.php"><button class="button reject-button">Reject</button></a>
            </div>
        </div>
    </body>

</html>