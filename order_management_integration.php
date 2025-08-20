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
   o.docnumber,
   o.WorkOrder,
   o.Style,
   o.color, 
   o.size, 
   SUM(o.Qty) AS OutputQty
   FROM `eworker_operation` o 
   LEFT JOIN `ework_workers` w ON o.cardnumber = w.cardnumber 
   WHERE w.Position = '2' AND o.color != '' AND o.size != ''
   GROUP BY o.docnumber, o.color, o.size ORDER BY o.docnumber DESC
   ";
   
   // echo $sql; exit();
   
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
           <h1>Order wise Input, Output & WIP</h1>
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
                                       <th>Doc#</th>
                                       <th>WO#</th>
                                       <th>Style</th>
                                       <th>Buyer</th>
                                       <th>Color</th>
                                       <th>Size</th>
                                       <th>Input Qty</th>
                                       <th>Output Qty</th>
                                       <th>WIP</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       while ($row = $result->fetch_assoc()) {

                                          $docnumber  = $row['docnumber'];
                                          $color = $row['color'];
                                          $size = $row['size'];


                                          $sql2 = "SELECT Customer FROM `ework_sales_order` WHERE docnumber = '$docnumber' LIMIT 1";
                                          $result2 = $conn->query($sql2);
                                          $row2 = $result2->fetch_assoc();
                                          $buyer = $row2['Customer'];

                                          $sql3 = "SELECT SUM(Qty) AS InputQty FROM `ework_daily_color_size` WHERE docnumber = '$docnumber' AND color = '$color' AND size = '$size'";
                                          $result3 = $conn->query($sql3);
                                          $row3 = $result3->fetch_assoc();
                                          $InputQty = $row3['InputQty'];

                                             
                                       ?>
                                    <tr data-id="1" class="valid new">
                                       <?php
                                          echo '<td>' . htmlspecialchars($row['docnumber']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['WorkOrder']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['Style']) . '</td>';
                                          echo '<td>' . htmlspecialchars($buyer) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['color']) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['size']) . '</td>';
                                          echo '<td>' . htmlspecialchars($InputQty) . '</td>';
                                          echo '<td>' . htmlspecialchars($row['OutputQty']) . '</td>';
                                          echo '<td>' . htmlspecialchars($InputQty - $row['OutputQty']) . '</td>';
                                          
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
            "scrollY": "400px", // Set the desired height
            "scrollX": true,   // Enable horizontal scrolling if needed
            "buttons": ["excel"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
      });
   </script>
</body>
</html>