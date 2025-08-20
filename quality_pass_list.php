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
       SELECT 
    o.ework_id, 
    o.docnumber, 
    o.cardnumber, 
    o.WorkOrder, 
    o.Style, 
    o.SewingLine,
    o.StepNumber,
    o.Qty,
    o.qty_type,
    o.color,
    o.size,
    o.DateTime
   FROM
    eworker_operation o
    LEFT JOIN ework_workers w ON o.cardnumber = w.cardnumber
    WHERE
    o.qty_type = 'pass'
    AND w.position = '2'
    AND o.DateTime LIKE '$Date%'
   ORDER BY o.DateTime DESC
    LIMIT 1500
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
           <h1>Quality Pass List</h1>
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
                                       <th>ID</th>
                                       <th>Doc No.</th>
                                       <th>Card No.</th>
                                       <th>Work Order</th>
                                       <th>Style</th>
                                       <th>Sewing Line</th>
                                       <th>Step Number</th>
                                       <th>Quantity</th>
                                       <th>Quantity Type</th>
                                       <th>Color</th>
                                       <th>Size</th>
                                       <th>Entry Time</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while ($row = $result->fetch_assoc()) {
                                       
                                      
                                       
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($row['ework_id']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['docnumber']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['cardnumber']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['WorkOrder']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['Style']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['SewingLine']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['StepNumber']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['Qty']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['qty_type']) . '</td>';
                                        echo '<td><input type="text" name="color[]" value="' . htmlspecialchars($row['color']) . '" onkeyup="saveData(this, \'color\', ' . $row['ework_id'] . ')"></td>';
                                        echo '<td><input type="text" name="size[]" value="' . htmlspecialchars($row['size']) . '" onkeyup="saveData(this, \'size\', ' . $row['ework_id'] . ')"></td>';
                                        echo '<td>' . htmlspecialchars($row['DateTime']) . '</td>';
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
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "pageLength": 100, // Set the number of rows per page (adjust as needed)
        "buttons": ["excel"]
    }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
});


      function saveData(input, field, ework_id) {
            // Use AJAX to send data to the server for saving
            var value = input.value;
            $.ajax({
                type: "POST",
                url: "quality_pass_list_save_data.php", // Replace with your server-side script
                data: {
                    field: field,
                    value: value,
                    ework_id: ework_id
                },
                success: function (response) {
                    // Handle success (if needed)
                    console.log(response);
                },
                error: function (error) {
                    // Handle errors (if needed)
                    console.error(error);
                }
            });
        }
   </script>
</body>
</html>