<?php
error_reporting(0);


// database connection already established
include 'db_connection.php';
// ...
// if ($_SERVER["REQUEST_METHOD"] == "GET") {
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$docNumber = $_REQUEST['docnumber'];

// $query = "SELECT DISTINCT(StepNumber) AS StepNumber FROM `ework_sales_order` WHERE docnumber = '$docNumber' ORDER BY StepNumber ASC";
$query = "SELECT StepNumber, cardnumber, Name, Machine FROM `eworker_assignment` WHERE docnumber = '$docNumber' AND WorkerActive = '1' AND StepActive = '1' ORDER BY StepNumber ASC";
$result = $conn->query($query);



?>

 <label class="form-label">Step Number<span class="required">*</span></label>
            <!-- Dynamically generate checkboxes -->
          
<?php while($row = $result->fetch_assoc()){ 

    if($row['Machine'] == 'BAS'){
        $row['Machine'] = 'BAS Machine';
    } else if($row['Machine'] == 'BA'){
        $row['Machine'] = 'Button Attach';
    } else if($row['Machine'] == 'BH'){
        $row['Machine'] = 'Button Hole';
    } else if($row['Machine'] == 'FL'){
        $row['Machine'] = 'Flatlock';
    } else if($row['Machine'] == 'FUSING'){
        $row['Machine'] = 'Fusing';
    } else if($row['Machine'] == 'KNS'){
        $row['Machine'] = 'Kansai';
    } else if($row['Machine'] == 'MAN'){
        $row['Machine'] = 'Manual';
    } else if($row['Machine'] == 'OL'){
        $row['Machine'] = 'Overlock';
    } else if($row['Machine'] == 'PM'){
        $row['Machine'] = 'Plain';
    } else if($row['Machine'] == 'FD'){
        $row['Machine'] = 'FD Machine';
    } else if($row['Machine'] == 'CSM'){
        $row['Machine'] = 'Chain Stitch Machine';
    }

?>


        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="StepNumber[]" id="lg_StepNumber" value="<?php echo $row['StepNumber'] . ', ' . $row['Machine'] . ', ' . $row['Name'] . ', ' .  $row['cardnumber'];  ?>">
            <label class="form-check-label" for="lg_StepNumber">
               <?php echo $row['StepNumber'] . ', ' . $row['Machine'] . ', ' . $row['Name'] . ', ' .  $row['cardnumber']; ?>
            </label>
        </div>


<?php } ?>