<?php
  session_start();
  if (!isset($_SESSION['username'])) {
       header("Location: index.php");
       exit;
  }
?>
<?php 
   include 'header.php'; 
   $query = "SELECT * FROM ework_workers";
   $result = $conn->query($query);
   ?>
<?php
   // Check if the form is submitted
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
       // Check connection
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
       }
   
       // Fetch values from the form
       $cardnumber = $_POST['cardnumber'];
       $name = $_POST['Name'];
       $sex = $_POST['Sex'];
       $phonenumber = $_POST['Phonenumber'];
       $joindate = $_POST['JoinDate'];
       $dob = $_POST['DateofBirth'];
       $position = $_POST['Position'];
       $workstation = $_POST['Department'];
       $password = md5($_POST['cardnumber']);
   
       // SQL query to insert data into the table
       $sql = "INSERT INTO ework_workers (cardnumber, name, sex, phonenumber, joindate, DateofBirth, position, Department,password)
               VALUES ('$cardnumber', '$name', '$sex', '$phonenumber', '$joindate', '$dob', '$position', '$workstation','$password')";
   
       if ($conn->query($sql) === TRUE) {
           // JavaScript alert for successful record creation
           // echo '<script>alert("New record created successfully");</script>';
           // Redirect to all_workers.php after displaying the alert
           echo '<script>window.location.href = "all_workers.php";</script>';
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
           <h1>Workers</h1>
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
                              <table class="table table-bordered" id="myDataTable">
                                 <thead>
                                    <tr>
                                       <th class="linenumber">Card No.</th>
                                       <th class="linenumber">Name</th>
                                       <th class="linenumber">Sex</th>
                                       <th class="linenumber">Phone No.</th>
                                       <th class="linenumber">Join Date</th>
                                       <th class="linenumber">Date of Birth</th>
                                       <th class="linenumber">Position</th>
                                       <th class="linenumber">Workstation</th>
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
                                             <?php echo $value['cardnumber'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['Name'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php 
                                                if ($value['Sex']=='M') {
                                                    echo "Male";
                                                }elseif ($value['Sex']=='F') {
                                                    echo "Female";
                                                }
                                             ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['Phonenumber'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['JoinDate'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['DateofBirth'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php 
                                                if ($value['Position']=='0') {
                                                    echo "Operator";
                                                }elseif ($value['Position']=='1') {
                                                    echo "Helper";
                                                }elseif ($value['Position']=='2') {
                                                    echo "Quality";
                                                } elseif ($value['Position']=='3') {
                                                    echo "Staff";
                                                } elseif ($value['Position']=='4') {
                                                    echo "Management";
                                                }  
                                                ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <?php echo $value['Department'] ?>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="btn-group" role="group">
                                             <button type="button" class="btn btn-outline-primary btn-sm editLine" 
                                                data-cardnumber="<?php echo $value['cardnumber']; ?>"
                                                data-name="<?php echo $value['Name']; ?>"
                                                data-sex="<?php echo $value['Sex']; ?>"
                                                data-phonenumber="<?php echo $value['Phonenumber']; ?>"
                                                data-joindate="<?php echo $value['JoinDate']; ?>"
                                                data-dob="<?php echo $value['DateofBirth']; ?>"
                                                data-position="<?php echo $value['Position']; ?>"
                                                data-workstation="<?php echo $value['Department']; ?>"
                                                data-password="<?php echo ""; ?>">
                                             Edit
                                             </button>
                                             <button type="button" class="btn btn-outline-primary btn-sm deleteLine"
                                                data-cardnumber="<?php echo $value['cardnumber']; ?>">
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
                  <form method="post" action="edit_worker.php">
                     <div class="modal" id="myModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-xl">
                           <div class="modal-content">
                              <!-- Modal Header -->
                              <div class="modal-header">
                                 <h4 class="modal-title">Worker</h4>
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
                                                <label for="cardnumber" class="form-label">Card No.*</label>
                                                <input type="text" name="cardnumber" id="cardnumber" class="form-control" value="" required="" readonly="">
                                             </div>
                                             <!-- <div class="col-md-4 mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="text" name="password" id="password" class="form-control" value="">
                                                </div> -->
                                             <div class="col-md-4 mb-3">
                                                <label for="Name" class="form-label">Name*</label>
                                                <input type="text" name="Name" id="Name" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Sex" class="form-label">Sex*</label>
                                                <select name="Sex" id="Sex" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="M">Male</option>
                                                   <option value="F">Female</option>
                                                </select>
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Phonenumber" class="form-label">Phone No.</label>
                                                <input type="number" name="Phonenumber" id="Phonenumber" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="JoinDate" class="form-label">Join Date*</label>
                                                <input type="date" name="JoinDate" id="JoinDate" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="DateofBirth" class="form-label">Date of Birth</label>
                                                <input type="date" name="DateofBirth" id="DateofBirth" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Position" class="form-label">Position*</label>
                                                <select name="Position" id="Position" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="0">Operator</option>
                                                   <option value="1">Helper</option>
                                                   <option value="2">Quality</option>
                                                   <option value="3">Staff</option>
                                                   <option value="4">Management</option>
                                                </select>
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Department" class="form-label">Workstation*</label>
                                                <select name="Department" id="Department" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="SEW">Sewing</option>
                                                   <option value="SQC">Sewing Quality</option>
                                                </select>
                                             </div>
                                          </div>
                                       </fieldset>
                                    </div>
                                 </div>
                              </div>
                              <!-- Modal Footer -->
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                 <button type="Submit" class="btn btn-outline-primary">Update</button>
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
                                 <h4 class="modal-title">Worker</h4>
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
                                                <label for="cardnumber" class="form-label">Card No.*</label>
                                                <input type="text" name="cardnumber" id="cardnumber" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Name" class="form-label">Name*</label>
                                                <input type="text" name="Name" id="Name" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Sex" class="form-label">Sex*</label>
                                                <select name="Sex" id="Sex" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="M">Male</option>
                                                   <option value="F">Female</option>
                                                </select>
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Phonenumber" class="form-label">Phone No.</label>
                                                <input type="number" name="Phonenumber" id="Phonenumber" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="JoinDate" class="form-label">Join Date*</label>
                                                <input type="date" name="JoinDate" id="JoinDate" class="form-control" value="" required="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="DateofBirth" class="form-label">Date of Birth</label>
                                                <input type="date" name="DateofBirth" id="DateofBirth" class="form-control" value="">
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Position" class="form-label">Position*</label>
                                                <select name="Position" id="Position" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="0">Operator</option>
                                                   <option value="1">Helper</option>
                                                   <option value="2">Quality</option>
                                                   <option value="3">Staff</option>
                                                   <option value="4">Management</option>
                                                </select>
                                             </div>
                                             <div class="col-md-4 mb-3">
                                                <label for="Department" class="form-label">Workstation*</label>
                                                <select name="Department" id="Department" class="form-select"
                                                   required="required">
                                                   <option value="">Select</option>
                                                   <option value="SEW">Sewing</option>
                                                   <option value="SQC">Sewing Quality</option>
                                                </select>
                                             </div>
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
      document.addEventListener('DOMContentLoaded', function() {
          var editButtons = document.querySelectorAll('.editLine');
      
          editButtons.forEach(function(button) {
              button.addEventListener('click', function() {
                  // Retrieve data attributes
                  var cardnumber = button.getAttribute('data-cardnumber');
                  var name = button.getAttribute('data-name');
                  var sex = button.getAttribute('data-sex');
                  var phonenumber = button.getAttribute('data-phonenumber');
                  var joindate = button.getAttribute('data-joindate');
                  var dob = button.getAttribute('data-dob');
                  var position = button.getAttribute('data-position');
                  var workstation = button.getAttribute('data-workstation');
                  // var password = button.getAttribute('data-password');
      
                  // Populate the modal with the retrieved data
                  document.getElementById('cardnumber').value = cardnumber;
                  document.getElementById('Name').value = name;
                  document.getElementById('Sex').value = sex;
                  document.getElementById('Phonenumber').value = phonenumber;
                  document.getElementById('JoinDate').value = joindate;
                  document.getElementById('DateofBirth').value = dob;
                  document.getElementById('Position').value = position;
                  document.getElementById('Department').value = workstation;
                  // document.getElementById('password').value = password;
      
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
                  var cardNumber = button.getAttribute('data-cardnumber');
      
                  // Display a confirmation dialog
                  var isConfirmed = confirm('Are you sure you want to delete?');
      
                  // If the user confirms, proceed with deletion
                  if (isConfirmed) {
                      // You can perform the deletion via AJAX or redirect to a delete script
                      // For simplicity, I'm using a placeholder URL here
                      var deleteUrl = 'delete_worker.php?cardnumber=' + cardNumber;
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
        $("#myDataTable").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["excel"]
          // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
        
      });
   </script>
</body>
</html>