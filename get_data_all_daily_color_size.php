<?php
// Assuming you have established a database connection ($conn)
error_reporting(0);


// ...
include 'db_connection.php';
// if ($_SERVER["REQUEST_METHOD"] == "GET") {
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

    $query_Color = "SELECT DISTINCT(color) FROM `ework_order_wise_color_size_qty` WHERE WO_docnumber = '$docnumber' AND WO_docnumber != ''";
    $result_Color = $conn->query($query_Color);

    $query_Size = "SELECT DISTINCT(size) FROM `ework_order_wise_color_size_qty` WHERE WO_docnumber = '$docnumber' AND WO_docnumber != ''";
    $result_Size = $conn->query($query_Size);
    

    



// }
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
    <label for="Color" class="form-label">Color</label>
    <select name="Color" id="Color" class="form-select"
       required="required">
       <option value="">Select</option>
       <?php
          foreach ($result_Color as $key => $Color) {
          ?>
       <option value="<?php echo $Color['color']; ?>"><?php echo $Color['color']; ?></option>
       <?php } ?>
    </select>
 </div>
 <div class="col-md-4 mb-3">
    <label for="Size" class="form-label">Size</label>
    <select name="Size" id="Size" class="form-select"
       required="required">
       <option value="">Select</option>
       <?php
          foreach ($result_Size as $key => $Size) {
          ?>
       <option value="<?php echo $Size['size']; ?>"><?php echo $Size['size']; ?></option>
       <?php } ?>
    </select>
 </div>

 <div class="col-md-4 mb-3">
    <label for="Qty" class="form-label">Quantity</label>
    <input type="text" name="Qty" id="Qty" class="form-control" value="" >
 </div>