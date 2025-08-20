<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   ?>
<?php 
   include 'header.php'; 
   $query = "SELECT * FROM ework_daily_color_size ORDER BY `Date` DESC";
   $result = $conn->query($query);

   $query_SewingLine = "SELECT * FROM ework_mrd_library WHERE LibraryName='SewingLine'";
   $result_SewingLine = $conn->query($query_SewingLine);

   

   ?>
<?php
   // Check if the form is submitted
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $date1 = $_POST['Date'];
   // Convert the datetime to MySQL date format using PHP
   $mysqlDate = date('Y-m-d', strtotime($date1));
   
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   
   
   
   // Fetch values from the form
    $Date       = $_POST['Date'];
    $Docnumber  = $_POST['Doc'];
    $SewingLine = $_POST['SewingLine'];
    $WorkOrder  = $_POST['WorkOrder'];
    $Style      = $_POST['Style'];
    $Color      = $_POST['Color'];
    $Size       = $_POST['Size'];
    $Qty        = $_POST['Qty'];
   
   // SQL query to insert data into the table
   $sql = "INSERT INTO `ework_daily_color_size`(`docnumber`, `sewingline`, `workorder`, `date`, `style`, `color`, `size`, `qty`)
           VALUES ('$Docnumber','$SewingLine', '$WorkOrder', '$Date', '$Style', '$Color', '$Size', '$Qty')";
   
   if ($conn->query($sql) === TRUE) {
       // JavaScript alert for successful record creation
       // echo '<script>alert("New record created successfully");</script>';
       // Redirect to all_workers.php after displaying the alert
       echo '<script>window.location.href = "all_daily_color_size.php";</script>';
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   // Close the database connection
   $conn->close();
   }
   ?>
<body class="hold-transition sidebar-mini">
   <div class="wrapper">
      <!-- Navbar -->
      <?php include('navbar.php'); ?>
      <!-- /.navbar -->
      <!-- Main Sidebar Container -->
      <?php include('sidebar.php'); ?>
      <!-- Content Wrapper. Contains page content -->
      <header style="text-align: center;">
           <h1>Daily Color & Size Informations</h1>
       </header>
      <div class="content-wrapper">
         <!-- /.content-header -->
         <!-- Main content -->
         <div class="content">
            <div class="container-fluid">
               <div id="formLinesContainer" class=" mt-3">
                  <div id="formLinesFieldset">
                     <!-- <div id="formLineslegend" class="mt-2">Line Information</div> -->
                     <div class="mb-1">
                        <button type="button" class="btn btn-primary newLine me-2" data-bs-toggle="modal"
                           data-bs-target="#myModal">New</button>
                     </div>
                     <div class="clearfix"></div>
                     <div id="leftFormLines">
                        <div id="formLines">
                           <div id="fieldset_lineinformation">
                              <table class="table table-bordered" id="example">
                                 <thead>
                                    <tr>
                                       <th class="linenumber">Date</th>
                                       <th class="linenumber">Docnumber</th>
                                       <th class="linenumber">Sewing Line</th>
                                       <th class="linenumber">Work Order</th>
                                       <th class="linenumber">Style</th>
                                       <th class="linenumber">Color</th>
                                       <th class="linenumber">Size</th>
                                       <th class="linenumber">Qty</th>
                                       <th>Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       foreach ($result as $key => $value) {
                                          
                                       ?>
                                    <tr data-id="1" class="valid new">
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo date('Y-m-d', strtotime($value['date'])); ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['docnumber'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['sewingline'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['workorder'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['style'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['color'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['size'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['qty'] ?>
                                          </div>
                                       </td>
                                       
                                       <td>
                                          <div class="btn-group" role="group">
                                             <button type="button" class="btn btn-outline-primary btn-sm editLine" 
                                                data-Date="<?php echo $value['date']; ?>"
                                                data-Docnumber="<?php echo $value['docnumber']; ?>"
                                                data-SewingLine="<?php echo $value['sewingline']; ?>"
                                                data-WorkOrder="<?php echo $value['workorder']; ?>"
                                                data-Style="<?php echo $value['style']; ?>"
                                                data-Color="<?php echo $value['color']; ?>"
                                                data-Size="<?php echo $value['size']; ?>"
                                                data-Qty="<?php echo $value['qty']; ?>"
                                                data-id="<?php echo $value['id']; ?>">

                                             Edit
                                             </button>
                                             <button type="button" class="btn btn-outline-primary btn-sm deleteLine"
                                                data-id="<?php echo $value['id']; ?>">
                                             Delete
                                             </button>
                                          </div>
                                       </td>
                                       <!-- ... (Remaining code) ... -->
                                    </tr>
                                    <?php
                                       }
                                       ?>
                                    <!-- Add more rows as needed -->
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
                  <form method="post" action="edit_daily_color_size_info.php">
                     <div class="modal" id="myModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-xl">
                           <div class="modal-content">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                 <h4 class="modal-title">Daily Color & Size Information</h4>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <!-- Modal Body -->
                              <div class="modal-body">
                                 <!-- Your form content goes here -->
                                 <div id="rightFormLines">
                                    <div id="rightFormLines">
                                       <div id="dragbar"></div>
                                       <fieldset id="fieldset_linegroup1" class="border p-3">

                                          <!-- <legend class="fs-5">Worker</legend> -->
                                          <div class="row">
                                             <div class="col-md-4 mb-3">
                                                <label for="Date" class="form-label">Date*</label>
                                                <input type="date" name="Date" id="Date" class="form-control"  required="">
                                             </div>
                                             <input type="hidden" name="id" id="id" class="form-control" value="">
                                             <div class="col-md-4 mb-3">
                                                <label for="Docnumber" class="form-label">Docnumber*</label>
                                                <input type="text" name="Docnumber" id="Docnumber" onblur="updateFields()" class="form-control" value="" required="required">
                                             </div>
                                             <div class="row" id="getWorkOrders">

                                             <div class="col-md-4 mb-3">
                                                <label for="SewingLine" class="form-label">Sewing Line*</label>
                                                <select name="SewingLine" id="SewingLine" class="form-select"
                                                   required="required" readonly>
                                                   <option value="">Select</option>
                                                   <?php
                                                      foreach ($result_SewingLine as $key => $SewingLine) {
                                                      ?>
                                                   <option value="<?php echo $SewingLine['Code']; ?>"><?php echo $SewingLine['Code']; ?></option>
                                                   <?php } ?>
                                                </select> 
                                             </div>
                                             
                                             
                                             <div class="col-md-4 mb-3">
                                                <label for="WorkOrder" class="form-label">Work Order*</label>
                                                
                                                <input type="text" name="WorkOrder" id="WorkOrder" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Style" class="form-label">Style*</label>
                                                <input type="text" name="Style" id="Style" class="form-control" value="" required="">
                                             </div>

                                             

                                          <!-- </div>



                                           <div class="row"> -->

                                             <div class="col-md-4 mb-3">
                                                <label for="Color" class="form-label">Color</label>
                                                <input type="text" name="Color" id="Color" class="form-control" value="" >
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Size" class="form-label">Size</label>
                                                <input type="text" name="Size" id="Size" class="form-control" value="" >
                                             </div>

                                             <div class="col-md-4 mb-3">
                                                <label for="Qty" class="form-label">Quantity</label>
                                                <input type="text" name="Qty" id="Qty" class="form-control" value="" >
                                             </div>
                                          </div>
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                              <!-- Modal Footer -->
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                 <button type="Submit" class="btn btn-outline-primary">Save</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>
                  <form method="post" action="">
                     <div class="modal" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-xl">
                           <div class="modal-content">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                 <h4 class="modal-title">Daily Color & Size Information</h4>
                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <!-- Modal Body -->
                              <div class="modal-body">
                                 <!-- Your form content goes here -->
                                 <div id="rightFormLines">
                                    <div id="rightFormLines">
                                       <div id="dragbar"></div>
                                       <fieldset id="fieldset_linegroup1" class="border p-3">
                                          <!-- <legend class="fs-5">Worker</legend> -->
                                          <div class="row">
                                             <div class="col-md-4 mb-3">
                                                <label for="Date" class="form-label">Date*</label>
                                                <input type="date" name="Date" id="Date" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Doc" class="form-label">Docnumber*</label>
                                                <input type="text" name="Doc" id="Doc" onblur="insertFields()" class="form-control" required="required">
                                             </div>
                                          </div>

                                          <div class="row" id="getWorkOrder">

                                             <div class="col-md-4 mb-3">
                                                <label for="SewingLine" class="form-label">Sewing Line*</label>
                                                <select name="SewingLine" id="SewingLine" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <?php
                                                      foreach ($result_SewingLine as $key => $SewingLine) {
                                                      ?>
                                                   <option value="<?php echo $SewingLine['Code']; ?>"><?php echo $SewingLine['Code']; ?></option>
                                                   <?php } ?>
                                                </select>
                                             </div>
                                             
                                             
                                             <div class="col-md-4 mb-3">
                                                <label for="WorkOrder" class="form-label">Work Order*</label>
                                                
                                                <input type="text" name="WorkOrder" id="WorkOrder" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Style" class="form-label">Style*</label>
                                                <input type="text" name="Style" id="Style" class="form-control" value="" required="">
                                             </div>

                                             

                                          <!-- </div>



                                           <div class="row">

                                             <div class="col-md-4 mb-3">
                                                <label for="Color" class="form-label">Color</label>
                                                <input type="text" name="Color" id="Color" class="form-control" value="" >
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Size" class="form-label">Size</label>
                                                <input type="text" name="Size" id="Size" class="form-control" value="" >
                                             </div>

                                             <div class="col-md-4 mb-3">
                                                <label for="Qty" class="form-label">Quantity</label>
                                                <input type="text" name="Qty" id="Qty" class="form-control" value="" >
                                             </div> -->

                                             
                                          </div>
                                             
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                              <!-- Modal Footer -->
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                 <button type="Submit" class="btn btn-outline-primary">Save changes</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
         </div>
         <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      <!-- Main Footer -->
      <?php include('footer.php'); ?>
   </div>
   <script>

      function insertFields(){
         var Docnumber = document.getElementById('Doc').value;
         if(Docnumber == ''){
          alert("Please enter a valid Docnumber!");
         }else{

            $.ajax({
               url: "get_data_all_daily_color_size.php",
               type: "POST",
               data: {
                   docnumber: Docnumber
               },
               cache: false,
               success: function(result) {
                   $("#getWorkOrder").html(result);
               }
           });
         }

      }


      function updateFields(){
         var Docnumber = document.getElementById('Docnumber').value;
         if(Docnumber == ''){
          alert("Please enter a valid Docnumber!");
         }else{
            
            $.ajax({
               url: "get_data_all_daily_color_size.php",
               type: "POST",
               data: {
                   docnumber: Docnumber
               },
               cache: false,
               success: function(result) {
                   $("#getWorkOrder").html(result);
               }
           });
         }
      }

      document.addEventListener('DOMContentLoaded', function() {
          var editButtons = document.querySelectorAll('.editLine');
      
          editButtons.forEach(function(button) {
              button.addEventListener('click', function() {
                // Retrieve data attributes
                var Date       = button.getAttribute('data-date');
                var Docnumber  = button.getAttribute('data-docnumber');
                var SewingLine = button.getAttribute('data-sewingline');
                var WorkOrder  = button.getAttribute('data-workorder');
                var Style      = button.getAttribute('data-style');
                var Color      = button.getAttribute('data-color');
                var Size       = button.getAttribute('data-size');
                var Qty        = button.getAttribute('data-qty');
                var id         = button.getAttribute('data-id');

                document.getElementById('Date').value       = Date;
                document.getElementById('Docnumber').value  = Docnumber;
                document.getElementById('SewingLine').value = SewingLine;
                document.getElementById('WorkOrder').value  = WorkOrder;
                document.getElementById('Style').value      = Style;
                document.getElementById('Color').value      = Color;
                document.getElementById('Size').value       = Size;
                document.getElementById('Qty').value        = Qty;
                document.getElementById('id').value         = id;
      
                  // Show the modal using Bootstrap's modal method
                  var myModaledit = new bootstrap.Modal(document.getElementById('myModaledit'));
                  myModaledit.show();
              });
          });
      });
   </script>
   <script>


      document.addEventListener('DOMContentLoaded', function () {
          var deleteButtons = document.querySelectorAll('.deleteLine');
      
          deleteButtons.forEach(function (button) {
              button.addEventListener('click', function () {
                  // Get the card number from the data attribute
                  var id = button.getAttribute('data-id');
      
                  // Display a confirmation dialog
                  var isConfirmed = confirm('Are you sure you want to delete?');
      
                  // If the user confirms, proceed with deletion
                  if (isConfirmed) {
                      // You can perform the deletion via AJAX or redirect to a delete script
                      // For simplicity, I'm using a placeholder URL here
                      var deleteUrl = 'delete_color_size_info.php?id=' + id;
                      window.location.href = deleteUrl;
                  }
              });
          });
      });
   </script>
   <!-- ./wrapper -->
   <!-- REQUIRED SCRIPTS -->
   <!-- jQuery -->
   <script src="plugins/jquery/jquery.min.js"></script>
   <!-- Bootstrap 4 -->
   <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <!-- AdminLTE App -->
   <script src="dist/js/adminlte.min.js"></script>
   <!-- DataTables JS -->
   <script src="dist/js/jquery.dataTables.min.js"></script>
   <script src="dist/js/dataTables.bootstrap5.min.js"></script>
   <script src="dist/js/popper.min.js"></script>
   <script src="dist/js/bootstrap.min.js"></script>
   <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
   <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
   <script src="plugins/jszip/jszip.min.js"></script>
   <script src="plugins/pdfmake/pdfmake.min.js"></script>
   <script src="plugins/pdfmake/vfs_fonts.js"></script>
   <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
   <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
   <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
   <script>
      $(function () {
        $("#example").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["excel"],
          "order": [[0, 'desc']]
          // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        
      });
   </script>
</body>
</html>