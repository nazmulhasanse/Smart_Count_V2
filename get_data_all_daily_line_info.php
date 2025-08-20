<?php
// Assuming you have established a database connection ($conn)
error_reporting(0);

include 'db_connection.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // Get the card number from the URL parameter
    $docnumber = $_POST['docnumber'];

    // SQL query to delete the record based on the card number
    $sql = "SELECT * FROM `eworker_assignment` WHERE docnumber = '$docnumber' LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $WorkOrder  =  $row['WorkOrder'];
    $SewingLine =  $row['line'];
    $Style      =  $row['Style'];


    $sql_operator = "SELECT 
                *
            FROM
                `eworker_assignment` a
                LEFT JOIN
                `ework_workers` w ON a.cardnumber = w.cardnumber
            WHERE
                     a.docnumber = '$docnumber'
                    AND w.position = '0'
                    AND a.WorkerActive = '1'
            GROUP BY a.cardnumber";

    $result_operator = $conn->query($sql_operator);
    $operator = mysqli_num_rows($result_operator);

    $sql_helper = "SELECT 
                *
            FROM
                `eworker_assignment` a
                LEFT JOIN
                `ework_workers` w ON a.cardnumber = w.cardnumber
            WHERE
                     a.docnumber = '$docnumber'
                    AND w.position = '1'
                    AND a.WorkerActive = '1'
            GROUP BY a.cardnumber";

    $result_helper = $conn->query($sql_helper);
    $helper = mysqli_num_rows($result_helper);


?>



<div class="col-md-4 mb-3">
    <label for="SewingLine" class="form-label">Sewing Line*</label>
    <input type="text" name="SewingLine" id="SewingLine" class="form-control" value="<?php echo  $SewingLine; ?>" readonly>

 </div>

 
 <div class="col-md-4 mb-3">
    <label for="WorkOrder" class="form-label">Work Order*</label>
    
    <input type="text" name="WorkOrder" id="WorkOrder" class="form-control" value="<?php echo  $WorkOrder; ?>" readonly>
 </div>
 <div class="col-md-4 mb-3">
    <label for="Style" class="form-label">Style*</label>
    <input type="text" name="Style" id="Style" class="form-control" value="<?php echo  $Style; ?>" readonly>
 </div>

 <div class="col-md-4 mb-3">
    <label for="Operator" class="form-label">Operator</label>
    <input type="text" name="Operator" id="Operator" class="form-control" value="<?php echo  $operator; ?>" readonly>
 </div>
 <div class="col-md-4 mb-3">
    <label for="Helper" class="form-label">Helper</label>
    <input type="text" name="Helper" id="Helper" class="form-control" value="<?php echo  $helper; ?>" readonly>
 </div>
