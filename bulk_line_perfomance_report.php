<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }

   include 'header.php'; 


   $sql = "
   SELECT 
    DATE(wp.DateTime) AS DateTime,
    wa.site,
    wa.building,
    wa.floor,
    wp.SewingLine,
    wp.WorkOrder,
    wa.Customer,
    wa.Style,
    wa.StyleDescription,
    te.WorkingHour,
    te.TargetEfficiency,
    te.ActualWorkingHour,
    wa.quantity,
    wa.docnumber
FROM 
    eworker_operation wp
LEFT JOIN 
    ework_target_efficiency te ON wp.docnumber = te.Docnumber 
                                AND DATE(wp.DateTime) = DATE(te.Date)
LEFT JOIN 
    eworker_assignment wa ON wp.docnumber = wa.docnumber
GROUP BY 
    DATE(wp.DateTime), wp.docnumber
ORDER BY 
    DATE(wp.DateTime) DESC;
       ";
       // Execute the query
       $result = $conn->query($sql);
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
           <h1>Daily Line Performance Report</h1>
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
                                       <th>Date Time</th>
                                       <th>Site</th>
                                       <th>Building</th>
                                       <th>Floor</th>
                                       <th>Sewing Line</th>
                                       <th>Work Order</th>
                                       <th>Customer</th>
                                       <th>Style</th>
                                       <th>Style Description</th>
                                       <th>Working Hour</th>
                                       <th>Target Efficiency</th>
                                       <th>Actual Working Hour</th>
                                       <th>Quantity</th>
                                       <th>Doc No.</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while ($row = $result->fetch_assoc()) {
                                          
                                       ?>
                                    <tr data-id="1" class="valid new">
                                       <?php
                                          echo '<td><a target="_blank" href="bulk_line_board.php?Date=' . urlencode($row['DateTime']) . '&Site=' . urlencode($row['site']) . '&Building=' . urlencode($row['building']) . '&Floor=' . urlencode($row['floor']) . '&Line=' . urlencode($row['SewingLine']) . '&WorkOrder=' . urlencode($row['WorkOrder']) . '&Customer=' . urlencode($row['Customer']) . '&Style=' . urlencode($row['Style']) . '&StyleDescription=' . urlencode($row['StyleDescription']) . '&WorkingHour=' . urlencode($row['WorkingHour']) . '&TargetEfficiency=' . urlencode($row['TargetEfficiency']) . '&ActualWorkingHour=' . urlencode($row['ActualWorkingHour']) . '&quantity=' . urlencode($row['quantity']) . '&docnumber=' . urlencode($row['docnumber']) .'">' . htmlspecialchars($row['DateTime']) . '</a></td>';
                                          
                                          echo '<td>' . htmlspecialchars($row['site']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['building']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['floor']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['SewingLine']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['WorkOrder']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['Customer']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['Style']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['StyleDescription']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['WorkingHour']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['TargetEfficiency']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['ActualWorkingHour']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['docnumber']) . '</td>';
                                          ?>
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
           "buttons": [
               "excel"
           ]
       }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
      });
   </script>
</body>
</html>