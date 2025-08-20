<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   ?>
<?php 
   include 'header.php'; 
   // Check the connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   
   
   // Modify SQL query
   $sql = "
       SELECT
           d.ID,
           d.Entry_dateime,
           a.WorkOrder,
           a.Style,
           a.line,
           d.No_of_Defect,
           d.DefectReason,
           d.PartName,
           d.StepNumber
       FROM
           `ework_defect` `d`
       LEFT JOIN `eworker_operation` `o` ON `d`.`OperationID` = `o`.`ework_id`
       LEFT JOIN `eworker_assignment` `a` ON `o`.`WorkOrder` = `a`.`WorkOrder` AND `o`.`Style` = `a`.`Style` AND `o`.`SewingLine` = `a`.`line`
       LEFT JOIN `ework_workers` `w` ON `o`.`cardnumber` = `w`.`cardnumber`
       GROUP BY `d`.`ID`, `a`.`WorkOrder`, `a`.`Style`, `a`.`line`, `w`.`cardnumber`
       ORDER BY `d`.`Entry_dateime` DESC
   ";
   
   
   
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
           <h1>Defect Details Report</h1>
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
                                       <th>Defect ID</th>
                                       <th>Date/Time</th>
                                       <th>Work Order</th>
                                       <th>Style</th>
                                       <th>Line</th>
                                       <th>No. of Defects</th>
                                       <th>Defect Reason</th>
                                       <th>Part Name</th>
                                       <th>Step Number</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while ($row = $result->fetch_assoc()) {
                                       echo '<tr>';
                                       echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['Entry_dateime']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['WorkOrder']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['Style']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['line']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['No_of_Defect']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['DefectReason']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['PartName']) . '</td>';
                                       echo '<td>' . htmlspecialchars($row['StepNumber']) . '</td>';
                                       echo '</tr>';
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
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["excel"]
          // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
        
      });
   </script>
</body>
</html>