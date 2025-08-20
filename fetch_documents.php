
<?php
 session_start();
 if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   include 'header.php'; 

// Get POST data
$docdate = $_REQUEST['docdate'];
$docnumber = $_REQUEST['docnumber'];


$sql_ID    = "SELECT ework_id FROM ework_daily_npt WHERE ework_id !=''";
$result_ID = $conn->query($sql_ID);


$values = array();

// Check if query executed successfully
if ($result_ID) {
    // Fetch all rows
    while ($row_ID = $result_ID->fetch_assoc()) {
        // Append each value to the array
        $values[] = $row_ID['ework_id'];
    }
}


// Initialize an array to hold the quoted and separated values for each column
$quoted_rows = array();

// Loop through each row
foreach ($values as $value) {
    // Split the value by commas
    $row_values = explode(',', $value);

    // Add quotes around each value and trim extra spaces
    $quoted_values = implode(', ', array_map(function($row_value) {
        return "'" . trim($row_value) . "'";
    }, $row_values));

    // Add the quoted values to the array for this row
    $quoted_rows[] = $quoted_values;

    // var_dump($value);
}

// Join the quoted rows with a comma separator
$final_string = implode(', ', $quoted_rows);

if($final_string == '') $final_string = "''";




// Prepare the SQL query using prepared statements to prevent SQL injection


// $sql = "SELECT * FROM eworker_operation WHERE TIMESTAMPDIFF(MINUTE, DateTime, end_datetime) > 30 AND DateTime LIKE '$docdate%' AND docnumber = '$docnumber' AND ework_id NOT IN ($final_string)";

$sql = "SELECT 
        eo.ework_id,
        eo.cardnumber,
        ew.Name,
        eo.DateTime,
        eo.end_datetime,
        eo.StepNumber
        FROM eworker_operation AS eo
        LEFT JOIN ework_workers AS ew ON eo.cardnumber = ew.cardnumber
        WHERE TIMESTAMPDIFF(MINUTE, eo.DateTime, eo.end_datetime) > 15
          AND eo.DateTime LIKE '$docdate%'
          AND eo.end_datetime LIKE '$docdate%'
          AND eo.docnumber = '$docnumber'
          AND ew.Position != '2'
          AND eo.ework_id NOT IN ($final_string);";

// var_dump($sql);

$result = $conn->query($sql);


$query_NPT_Reason = "SELECT * FROM ework_mrd_library WHERE LibraryName='NPT'";
$result_NPT_Reason = $conn->query($query_NPT_Reason);

?>

<style type="text/css">
        body {
            font-family: Arial, sans-serif;
            
        }
        
        .popup {
            font-size: 8px;
            display: none;
            position: auto;
            left: 5%;
            top: 5%;
            transform: translate(-15%, -5%);
            border: 1px solid #000;
            background-color: #f9f9f9;
            padding: 2px;
            z-index: 1000;
            max-width: 900px;
            max-height: 900px;
            width: 150%;
            height: 200%;
        }

        .popup-section {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            border: 1px solid #000;
        }

 
</style>
        <div>
            
            <label for="reason" class="form-label">Reason*</label>
            <button class="btn btn-primary" onclick="showPopup()">Choose Reason</button>
            <!-- <select name="reason" id="reason" class="form-select" required="required">
                <option value="">Select</option>
                <?php
                foreach ($result_NPT_Reason as $key => $NPT_Reason) {
                ?>
                <option value="<?php echo $NPT_Reason['Description']; ?>"><?php echo $NPT_Reason['Description']; ?></option>
                <?php } ?>
            </select> --> 
            <input type="text" name="reason" id="reason"
                class="form-control" value="" readonly="readonly"> 
        </div>


    <div id="popup" class="popup" style="display:none;">
    <div class="popup-header">Input Problem (ইনপুট সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="print" name="input_problem" value="Print (Input Problem)" onclick="selectProblem(this)">
            <label for="print"><img src="/ie/npt-reasons/print.png" alt="Print" style="width: 50px; height: 50px;"> Print - প্রিন্ট</label>
        </div>
        <div>
            <input type="radio" id="store" name="input_problem" value="Store (Input Problem)" onclick="selectProblem(this)">
            <label for="store"><img src="/ie/npt-reasons/store.png" alt="Store" style="width: 50px; height: 50px;"> Store - স্টোর</label>
        </div>
        <div>
            <input type="radio" id="cutting" name="input_problem" value="Cutting (Input Problem)" onclick="selectProblem(this)">
            <label for="cutting"><img src="/ie/npt-reasons/cutting.png" alt="Cutting" style="width: 50px; height: 50px;"> Cutting - কাটিং</label>
        </div>
        <div>
            <input type="radio" id="sewing" name="input_problem" value="Sewing (Input Problem)" onclick="selectProblem(this)">
            <label for="sewing"><img src="/ie/npt-reasons/sewing.png" alt="Sewing" style="width: 50px; height: 50px;"> Sewing - সেলাই</label>
        </div>
        <div>
            <input type="radio" id="supplier" name="input_problem" value="Supplier (Input Problem)" onclick="selectProblem(this)">
            <label for="supplier"><img src="/ie/npt-reasons/supplier.png" alt="Supplier" style="width: 50px; height: 50px;"> Supplier - সরবরাহকারী</label>
        </div>
        <div>
            <input type="radio" id="auto_elastic" name="input_problem" value="Auto Elastic (Input Problem)" onclick="selectProblem(this)">
            <label for="auto_elastic"><img src="/ie/npt-reasons/auto-elastic.png" alt="Auto Elastic" style="width: 50px; height: 50px;"> Auto Elastic - অটো ইলাস্টিক</label>
        </div>
    </div>
    <div class="popup-header">Machine Problem (মেশিনের সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="breakdown" name="machine_problem" value="Breakdown (Machine Problem)" onclick="selectProblem(this)">
            <label for="breakdown"><img src="/ie/npt-reasons/breakdown.png" alt="Breakdown" style="width: 50px; height: 50px;"> Breakdown - ব্রেকডাউন</label>
        </div>
        <div>
            <input type="radio" id="setting_delay" name="machine_problem" value="Setting Delay (Machine Problem)" onclick="selectProblem(this)">
            <label for="setting_delay"><img src="/ie/npt-reasons/setting-delay.png" alt="Setting Delay" style="width: 50px; height: 50px;"> Setting Delay - সেটিং বিলম্ব</label>
        </div>
    </div>
    <div class="popup-header">Utility Problem (পাওয়ার সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="electricity" name="utility_problem" value="Electricity (Utility Problem)" onclick="selectProblem(this)">
            <label for="electricity"><img src="/ie/npt-reasons/electricity.png" alt="Electricity" style="width: 50px; height: 50px;"> Electricity - বিদ্যুৎ</label>
        </div>
        <div>
            <input type="radio" id="compressed_air" name="utility_problem" value="Compressed Air (Utility Problem)" onclick="selectProblem(this)">
            <label for="compressed_air"><img src="/ie/npt-reasons/compressed-air.png" alt="Compressed Air" style="width: 50px; height: 50px;"> Compressed Air - বায়ুর চাপ</label>
        </div>
    </div>
    <div class="popup-header">Other Problem (অন্যান্য সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="rework" name="other_problem" value="Rework (Other Problem)" onclick="selectProblem(this)">
            <label for="rework"><img src="/ie/npt-reasons/rework.png" alt="Rework" style="width: 50px; height: 50px;"> Rework - রিওয়ার্ক</label>
        </div>
        <div>
            <input type="radio" id="training_meeting" name="other_problem" value="Training Meeting (Other Problem)" onclick="selectProblem(this)">
            <label for="training_meeting"><img src="/ie/npt-reasons/training-meeting.png" alt="Training Meeting" style="width: 50px; height: 50px;"> Training/Meeting - প্রশিক্ষণ/মিটিং</label>
        </div>
        <div>
            <input type="radio" id="technical_delay" name="other_problem" value="Technical Delay (Other Problem)" onclick="selectProblem(this)">
            <label for="technical_delay"><img src="/ie/npt-reasons/technical-delay.png" alt="Technical Delay" style="width: 50px; height: 50px;"> Technical Delay - টেকনিক্যাল বিলম্ব</label>
        </div>
        <div>
            <input type="radio" id="work_balancing" name="other_problem" value="Work Balancing (Other Problem)" onclick="selectProblem(this)">
            <label for="work_balancing"><img src="/ie/npt-reasons/work-balancing.png" alt="Work Balancing" style="width: 50px; height: 50px;"> Work Balancing - কাজের ভারসাম্য</label>
        </div>
    </div>
    <!-- <div class="popup-footer">
        <button style="text-align:center;" type="button" onclick="submitForm()">Submit</button>
    </div> -->
</div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function selectProblem(radio) {
            document.getElementById('reason').value = radio.value;
            document.getElementById('popup').style.display = 'none';
        }

        // function submitForm() {
        //     var inputForm = document.querySelector('form');
        //     var selectedInputProblem = inputForm.input_problem.value;
        //     var selectedMachineProblem = inputForm.machine_problem.value;
        //     var selectedUtilityProblem = inputForm.utility_problem.value;
        //     var selectedOtherProblem = inputForm.other_problem.value;

        //     alert(selectedInputProblem);

        //     document.getElementById('popup').style.display = 'none';
        // }
    </script>

            <div>
                
            <label for="remarks" class="form-label">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control"></textarea>

            </div>

            <br>

            <div style="text-align: center;"><input type='button' class='btn btn-primary' value='Submit' id='submitBtn' onclick='submitForm()'></div>

            <br>






        <!-- Your table goes here -->
        <form id='myForm' method='post' action=''>
        <!-- <input type='submit' value='Submit' id='submitBtn'> -->
        <table class="table table-bordered" id="styled_tablediv">
          <thead>
            <tr>
              <th style='border: 1px solid black;'>
                <input type='checkbox' class='btn btn-primary' id='checkAll' onclick='checkAllItems()'>
              </th>
              <th style='border: 1px solid black;' hidden>ID</th>
              <th style='border: 1px solid black;' >Card No.</th>
              <th style='border: 1px solid black;' >Name</th>
              <th style='border: 1px solid black;'>Start Time</th>
              <th style='border: 1px solid black;'>End Time</th>
              <th style='border: 1px solid black;'>Duration (In minutes)</th>
              <th style='border: 1px solid black;'>Step Number</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data rows will be injected here by PHP -->
          <?php

          // Continue your existing PHP code...

          if ($result->num_rows > 0) { 
              // // Output data of each row

              while($row = $result->fetch_assoc()) {

                  $ework_id = $row["ework_id"];
                  $StepNumber = $row["StepNumber"];

                  $sql1         = "SELECT docnumber FROM `eworker_operation` WHERE ework_id = '$ework_id'";
                  $result1      = $conn->query($sql1);
                  $row1         = $result1->fetch_assoc();
                  $docnumber    = $row1['docnumber'];

                  $sql2         = "SELECT StepTimeMins FROM `ework_sales_order` WHERE docnumber = '$docnumber' AND StepNumber = '$StepNumber'";
                  $result2      = $conn->query($sql2);
                  $row2         = $result2->fetch_assoc();
                  $StepTimeMins = $row2['StepTimeMins'];

                  $timeDifferenceInMinutes = getTimeDifferenceInMinutes($row["DateTime"], $row["end_datetime"], $StepTimeMins);

                  $date1 = new DateTime($row["DateTime"]);
                  $date2 = new DateTime($row["end_datetime"]);

                  // Format the time in 12-hour format with am/pm
                  $row["DateTime"]     = $date1->format('h:ia');
                  $row["end_datetime"] = $date2->format('h:ia');

                  echo "<tr>
                      <td style='border: 1px solid black;'>
                        <input type='checkbox' class='btn btn-primary' name='selectedItems[]' onclick='chooseItem(this)'>
                      </td>
                      <td style='border: 1px solid black;' hidden>".$row["ework_id"]." </td>
                      <td style='border: 1px solid black;' >".$row["cardnumber"]." </td>
                      <td style='border: 1px solid black;' >".$row["Name"]." </td>
                      <td style='border: 1px solid black;'>".$row["DateTime"]." </td>
                      <td style='border: 1px solid black;'>".$row["end_datetime"]." </td>
                      <td style='border: 1px solid black;'>".$timeDifferenceInMinutes." </td>
                      <td style='border: 1px solid black;'>".$row["StepNumber"]."</td>

                      <input type='hidden' name='ework_id[]' value='".$row["ework_id"]."'>
                      <input type='hidden' name='cardnumber[]' value='".$row["cardnumber"]."'>
                      <input type='hidden' name='Name[]' value='".$row["Name"]."'>
                      <input type='hidden' name='DateTime[]' value='".$row["DateTime"]."'>
                      <input type='hidden' name='end_datetime[]' value='".$row["end_datetime"]."'>
                      <input type='hidden' name='timeDifferenceInMinutes[]' value='".$timeDifferenceInMinutes."'>
                      <input type='hidden' name='StepNumber[]' value='".$row["StepNumber"]."'>
                    </tr>";



              }
          } else {
              echo "No results found";
          }

          function getTimeDifferenceInMinutes($startDateTime, $endDateTime, $StepTimeMins) {
              $startTime = strtotime($startDateTime);
              $endTime = strtotime($endDateTime);
              $difference = abs($endTime - $startTime) / 60; // Difference in minutes

              

              $difference = $difference - $StepTimeMins;

              return number_format($difference, 2);
          }
          ?>



          </tbody>
        </table>
        </form>

       

        









<script type="text/javascript">

function checkAllItems() {
    var checkboxes = document.getElementsByName('selectedItems[]');
    var checkAllCheckbox = document.getElementById('checkAll');
    
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAllCheckbox.checked;
    }
}



var cartItems = []; // Array to store selected items

function chooseItem(checkbox) {

      var row = checkbox.closest('tr');
      var eworkId    = row.querySelector('input[name="ework_id[]"]').value;
      var cardNumber = row.querySelector('input[name="cardnumber[]"]').value;
      var name       = row.querySelector('input[name="Name[]"]').value;
      var start_time = row.querySelector('input[name="DateTime[]"]').value;
      var end_time   = row.querySelector('input[name="end_datetime[]"]').value;
      var duration   = row.querySelector('input[name="timeDifferenceInMinutes[]"]').value;
      var StepNumber = row.querySelector('input[name="StepNumber[]"]').value;

      var docdate   = document.getElementById('docdate').value;
      var WorkOrder = document.getElementById('WorkOrder').value;
      var site      = document.getElementById('site').value;
      var building  = document.getElementById('building').value;
      var floor     = document.getElementById('floor').value;
      var line      = document.getElementById('line').value;
      var Customer  = document.getElementById('Customer').value;
      var Style     = document.getElementById('Style').value;

      var urlParams = new URLSearchParams(window.location.search);
      var nptnumber = urlParams.get('id');

      var npttype = '1';
      // alert(docdate + docnumber + WorkOrder + site + building + floor + line + Customer + Style);
      // Fill in the form fields

      var reason = document.getElementById('reason').value;

      var remarks = document.getElementById('remarks').value;


      if(checkbox.checked){

          if(nptnumber != null){

            var docnumber = document.getElementById('DocNumber').value;

            var cartItem = {
                nptnumber: nptnumber,
                StepNumber: StepNumber,
                docdate: docdate,
                docnumber: docnumber,
                WorkOrder: WorkOrder,
                site: site,
                building: building,
                floor: floor,
                line: line,
                Customer: Customer,
                Style: Style,
                start_time: start_time,
                end_time: end_time,
                duration: duration,
                reason: reason,
                remarks: remarks,
                npttype: npttype,
                ework_id: eworkId,
                type: 'addlines'
            };


            if (checkbox.checked) {
                cartItems.push(cartItem);
                // alert(cartItems);
            } else {
                var index = cartItems.findIndex(item => item.ework_id === eworkId);
                if (index !== -1) {
                    cartItems.splice(index, 1);
                }
            }


        } else {

            var docnumber = document.getElementById('docnumber').value;

            var cartItem = {
                StepNumber: StepNumber,
                docdate: docdate,
                docnumber: docnumber,
                WorkOrder: WorkOrder,
                site: site,
                building: building,
                floor: floor,
                line: line,
                Customer: Customer,
                Style: Style,
                start_time: start_time,
                end_time: end_time,
                duration: duration,
                reason: reason,
                remarks: remarks,
                npttype: npttype,
                ework_id: eworkId,
                type: 'add'
            };


            // If checkbox is checked, add eworkId to selectedItems array, else remove it
            if (checkbox.checked) {
                cartItems.push(cartItem);
                // alert(cartItems);
            } else {
                var index = cartItems.findIndex(item => item.ework_id === eworkId);
                if (index !== -1) {
                    cartItems.splice(index, 1);
                }
            }

        }
    }



    
    

}



// function chooseItem(checkbox) {

//     var selectedItems = document.querySelectorAll('input[name="selectedItems[]"]:checked');
//     var chosenItems = [];
//     selectedItems.forEach(function(item) {
//         chosenItems.push(item.value);
//     });


//     document.getElementById('lg_ework_id').value = chosenItems.join(', ');

// }


// document.getElementById("submitBtn").addEventListener("click", function() {
//     console.log("Submit button clicked."); // Check if this message appears in the console
//     var checkboxes = document.querySelectorAll('input[type="checkbox"]');
//     checkboxes.forEach(function(checkbox) {
//         checkbox.addEventListener('click', function() {
//             console.log("Checkbox clicked."); // Check if this message appears in the console
//             chooseItem(this); // Make sure the chooseItem function is called when a checkbox is clicked
//         });
//     });

    function submitForm() {


          var urlParams = new URLSearchParams(window.location.search);
          var nptnumber = urlParams.get('id');

          if(nptnumber != null){

            var saveData = $.ajax({
                type: 'POST',
                url: "npt_edit_store.php",
                data: {
                    cartItems: cartItems
                },
                dataType: "text",
                success: function(data) {
                    // alert("Line Successfully Inserted");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });

          }else{

            var saveData = $.ajax({
                type: 'POST',
                url: "npt_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "json", // Expect a JSON response
                success: function(response) {
                    console.log(response); // Log the response for debugging

                    if (response.success) {
                        window.location.href = `npt_edit.php?id=${response.nptnumber}`;
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + " " + error);
                    console.error(xhr.responseText); // Log the full response for debugging
                    alert("An error occurred while processing your request.");
                }
           
            });

          }

        }

      
    </script>