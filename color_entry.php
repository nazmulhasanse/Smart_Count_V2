<?php
  session_start();
  if (!isset($_SESSION['username'])) {
       header("Location: index.php");
       exit;
  }
?>
<?php 
   include 'header.php'; 
   $query = "SELECT * FROM ework_mrd_library where LibraryName = 'color'";
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
       $LibraryName = 'color';
       $code = $result->num_rows +1;
       $Description = $_POST['description'];
       $isapproved = 1;
       $isactive = 1;

   
       // SQL query to insert data into the table
       $sql = "INSERT INTO ework_mrd_library ( LibraryName, code, Description, isapproved, isactive)
               VALUES ('$LibraryName', '$code', '$Description', '$isapproved', '$isactive')";
   
       if ($conn->query($sql) === TRUE) {
           // JavaScript alert for successful record creation
           // echo '<script>alert("New record created successfully");</script>';
           // Redirect to all_workers.php after displaying the alert
           echo '<script>window.location.href = "color_entry.php";</script>';
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
            <h1>Color</h1>
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
                                                    <th class="linenumber">SL#</th>
                                         
                                                    <th class="linenumber">Color Name</th>
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
                                                            <?php echo $value['id'] ?>
                                                        </div>
                                                    </td>
                                                   <td>
                                                        <div class="btn-group" role="group">
                                                            <?php echo $value['Description'] ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm editLine"
                                                                data-id="<?php echo $value['id']; ?>"
                                                                data-description="<?php echo $value['Description']; ?>">
                                                                Edit
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm deleteLine"
                                                                data-id="<?php echo $value['id']; ?>">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <!-- ... (Remaining code) ... -->
                                                </tr>
                                                <?php }?>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="update_color_data.php">
                            <div class="modal" id="myModaledit" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog  modal-xl">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Color</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <!-- Your form content goes here -->
                                            <div id="rightFormLines">
                                                <div id="rightFormLines">
                                                    <div id="dragbar"></div>
                                                    <fieldset id="fieldset_linegroup1" class="border p-3">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="name" class="form-label">Color Name</label>
                                                                <input type="text" name="edite_value" id="edite_value"
                                                                    class="form-control" value="">
                                                                <input type="hidden" name="id" id="id"
                                                                    class="form-control" value="">
                                                            </div>

                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="Submit" class="btn btn-outline-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="post" action="">
                            <div class="modal" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog  modal-xl">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Color</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <!-- Your form content goes here -->
                                            <div id="rightFormLines">
                                                <div id="rightFormLines">
                                                    <div id="dragbar"></div>
                                                    <fieldset id="fieldset_linegroup1" class="border p-3">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="description" class="form-label">Color Name
                                                                    *</label>
                                                                <input type="text" name="description" id="description"
                                                                    class="form-control" value="" required="">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
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
                var description = button.getAttribute('data-description');
                var id = button.getAttribute('data-id');

                // Populate the modal with the retrieved data
                document.getElementById('edite_value').value = description;
                document.getElementById('id').value = id;

                // Show the modal using Bootstrap's modal method
                var myModaledit = new bootstrap.Modal(document.getElementById('myModaledit'));
                myModaledit.show();
            });
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.deleteLine');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the card number from the data attribute
                var id = button.getAttribute('data-id');

                // Display a confirmation dialog
                var isConfirmed = confirm('Are you sure you want to delete?');

                // If the user confirms, proceed with deletion
                if (isConfirmed) {
                    // You can perform the deletion via AJAX or redirect to a delete script
                    // For simplicity, I'm using a placeholder URL here
                    var deleteUrl = 'delete_color.php?id=' + id;
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
    $(function() {
        $("#myDataTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel"]
            // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');

    });
    </script>
</body>

</html>