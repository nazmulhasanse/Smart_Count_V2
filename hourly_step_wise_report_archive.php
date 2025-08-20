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
       w.Name,
       o.Style,
       o.WorkOrder,
       o.cardnumber, 
       o.StepNumber, 
       o.docnumber, 
       o.SewingLine, 
       SUBSTRING(o.DateTime, 1, 10) AS DATE 
       FROM `eworker_operation_archive` o
       LEFT JOIN `ework_workers` w ON o.cardnumber = w.cardnumber
       GROUP BY  o.cardnumber, o.StepNumber, o.docnumber, DATE
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
           <h1>Process wise Report</h1>
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
                                       <th>Card Number</th>
                                       <th>Name</th>
                                       <th>Step Number</th>
                                       <th>Docnumber</th>
                                       <th>Date</th>
                                       <th>Style</th>
                                       <th>WorkOrder</th>
                                       <th>SewingLine</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while ($row = $result->fetch_assoc()) {
                                          
                                       ?>
                                    <tr data-id="1" class="valid new">
                                       <?php
                                          echo '<td><a target="_blank" href="hourly_steps_board_archive.php?Date=' . urlencode($row['DATE']) . '&StepNumber=' . urlencode($row['StepNumber'])  . '&cardnumber=' . urlencode($row['cardnumber']) .  '&docnumber=' . urlencode($row['docnumber']).  '&Style=' . urlencode($row['Style']).  '&WorkOrder=' . urlencode($row['WorkOrder']) .'&SewingLine=' . urlencode($row['SewingLine']) .'">' . htmlspecialchars($row['cardnumber']) . '</a></td>';
                                          echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['StepNumber']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['docnumber']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['DATE']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['Style']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['WorkOrder']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['SewingLine']) . '</td>';
                                         
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
               "excel",
               {
                   text: 'Archives',
                   action: function (e, dt, node, config) {
                       // Add your custom action here
                       window.open('hourly_step_wise_report_archive.php', '_blank');
                   }
               }
           ]
       }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
      });
   </script>
</body>
</html>