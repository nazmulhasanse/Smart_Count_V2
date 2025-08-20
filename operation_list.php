<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }

   include 'header.php'; 
   // Check the connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   date_default_timezone_set("Asia/Dhaka");

   $Date = $_GET['Date'];

   if($Date == '') $Date = date('Y-m-d');
   
   
   // Modify SQL query
   $sql = "
    SELECT * FROM eworker_bulk_operation 
    WHERE date = '$Date'
    ORDER BY date DESC";

   // echo $sql;
   // exit();
   // Execute the query
   $result = $conn->query($sql);
   
   // Check for errors
   if (!$result) {
       die("Query failed: " . $conn->error);
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
           <h1>Operations List</h1>
      </header>
      <div class="content-wrapper">
         <!-- /.content-header -->
         <!-- Main content -->
         <div class="content">
            <div class="container-fluid">
               <div id="formLinesContainer" class=" mt-3">
                  <div id="formLinesFieldset">
                     <div class="clearfix"></div>
                     <div id="leftFormLines">
                        <div id="formLines">
                           <div id="fieldset_lineinformation">
                              <table class="table table-bordered" id="myDataTable">
                                 <thead>
                                    <tr>
                                       <th>Date</th>
                                       <th>Hour</th>
                                       <th>Quantity</th>
                                       <th>Name</th>
                                       <th>Card Number</th>
                                       <th>Line</th>
                                       <!-- <th>Sewing Line</th>
                                       <th>Customer</th>
                                       <th>Step Number</th>
                                       <th>Step Name</th>
                                       <th>Quantity</th>
                                       <th>Quantity Type</th>
                                       <th>Color</th>
                                       <th>Size</th>
                                       <th>Start Time</th>
                                       <th>End Time</th> -->
                                       <!-- <th>Part Code</th>
                                       <th>Rework Reason</th>
 -->
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while($row = $result->fetch_assoc()){
                                          echo '<tr>';
                                          echo '<td>'. htmlspecialchars($row['date']). '</td>';
                                          echo '<td>'. htmlspecialchars($row['hour']). '</td>';
                                          echo '<td>'. htmlspecialchars($row['quantity']). '</td>';
                                          echo '<td>'. htmlspecialchars($row['name']). '</td>';
                                          echo '<td>'. htmlspecialchars($row['cardnumber']). '</td>';
                                          echo '<td>'. htmlspecialchars($row['line']). '</td>';
                                       }
                                          
                                       ?>
                                    <!-- Add more rows as needed -->
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
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
           "responsive": true,
           "lengthChange": false,
           "autoWidth": false,
           "pageLength": 100, // Set the number of rows per page (adjust as needed)
           "buttons": ["excel"]
          // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
        
      });
   </script>
</body>
</html>