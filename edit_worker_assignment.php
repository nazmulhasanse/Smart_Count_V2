<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   include 'header.php';
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
           <h1>Worker Assignment</h1>
       </header>
        <div class="content-wrapper">
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="container mt-3">
                        <table id="listTable" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        <center>Date</center>
                                    </th>
                                    <th>
                                        <center>Work Order</center>
                                    </th>
                                    <th>
                                        <center>Doc No.</center>
                                    </th>
                                    <th>
                                        <center>Style</center>
                                    </th>
                                    <th>
                                        <center>Style Description</center>
                                    </th>
                                    <th>
                                        <center>Sewing Line NO.</center>
                                    </th>
                                    <th>
                                        <center>Customer</center>
                                    </th>
                                    <th>
                                        <center>Quantity</center>
                                    </th>
                                    <th>
                                        <center>Step No.</center>
                                    </th>
                                    <th>
                                        <center>Step Name</center>
                                    </th>
                                    <th>
                                        <center>Process</center>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $docnumber = htmlspecialchars($_GET['id']);
                        
                                       $sql = "SELECT * FROM `eworker_assignment` where docnumber = '$docnumber' limit 1";
                                       $result = $conn->query($sql);
                        
                                       if ($result->num_rows > 0) {
                                           $sewing_line_data = [];
                                       // output data of each row
                                       while($row = $result->fetch_assoc()) {
                                           $sewing_line_data[] =  $row;
                                       }
                                       } else {
                                       echo "0 results";
                                       }
                                      
                                       ?>
                                <?php foreach($sewing_line_data as $value){?>
                                <tr>
                                    <td id="docdate" value="<?php echo $value['docdate']; ?>">
                                        <?php echo $value['docdate'];?></td>
                                    <td id="WorkOrder" value="<?php echo $value['WorkOrder']; ?>">
                                        <?php echo $value['WorkOrder'];?></td>
                                    <td id="docnumber" value="<?php echo $value['docnumber']; ?>">
                                        <?php echo $value['docnumber'];?></th>
                                    <td id="Style" value="<?php echo $value['Style']; ?>"><?php echo $value['Style'];?>
                                    </td>
                                    <td id="StyleDescription" value="<?php echo $value['StyleDescription']; ?>">
                                        <?php echo $value['StyleDescription'];?>
                                    </td>
                                    <td id="linenumber" value="<?php echo $value['linenumber']; ?>">
                                        <?php echo $value['linenumber'];?>
                                    </td>
                                    <td id="Customer" value="<?php echo $value['Customer']; ?>">
                                        <?php echo $value['Customer'];?></td>
                                    <td id="quantity" value="<?php echo $value['quantity']; ?>">
                                        <?php echo $value['quantity'];?></td>
                                    <td id="StepNumber" value="<?php echo $value['StepNumber']; ?>">
                                        <?php echo $value['StepNumber'];?></td>
                                    <td id="Description_StepName" value="<?php echo $value['Description_StepName']; ?>">
                                        <?php echo $value['Description_StepName'];?></td>
                                    <td id="Process" value="<?php echo $value['Process']; ?>">
                                        <?php echo $value['Process'];?></td>
                                    <input type="hidden" name="SalesOrder" id="SalesOrder" class="form-control"
                                        value="<?php echo $value['SalesOrder'];?>">
                                    <input type="hidden" name="SalesOrderQuantity" id="SalesOrderQuantity"
                                        class="form-control" value="<?php echo $value['SalesOrderQuantity'];?>">
                                    <input type="hidden" name="ShipDate" id="ShipDate" class="form-control"
                                        value="<?php echo $value['ShipDate'];?>">


                                    <input type="hidden" name="Machine" id="Machine" class="form-control"
                                        value="<?php echo $value['Machine'];?>">
                                    <input type="hidden" name="StepTimeMins" id="StepTimeMins" class="form-control"
                                        value="<?php echo $value['StepTimeMins'];?>">
                                    <input type="hidden" name="NextStep" id="NextStep" class="form-control"
                                        value="<?php echo $value['NextStep'];?>">
                                    <input type="hidden" name="FirstStep" id="FirstStep" class="form-control"
                                        value="<?php echo $value['FirstStep'];?>">
                                    <input type="hidden" name="docstatus" id="docstatus" class="form-control"
                                        value="<?php echo $value['docstatus'];?>">
                                    <input type="hidden" name="ColorSizeEntry" id="ColorSizeEntry" class="form-control"
                                        value="<?php echo $value['ColorSizeEntry'];?>">
                                    <input type="hidden" name="site" id="site" class="form-control"
                                        value="<?php echo $value['site'];?>">
                                    <input type="hidden" name="building" id="building" class="form-control"
                                        value="<?php echo $value['building'];?>">
                                    <input type="hidden" name="floor" id="floor" class="form-control"
                                        value="<?php echo $value['floor'];?>">
                                    <input type="hidden" name="LastStep" id="LastStep" class="form-control"
                                        value="<?php echo $value['LastStep'];?>">
                                    <input type="hidden" name="WorkerAssignment" id="WorkerAssignment"
                                        class="form-control" value="<?php echo $value['WorkerAssignment'];?>">
                                    <input type="hidden" name="ColorSizeAssortment" id="ColorSizeAssortment"
                                        class="form-control" value="<?php echo $value['ColorSizeAssortment'];?>">
                                    <input type="hidden" name="GarmentParts" id="GarmentParts" class="form-control"
                                        value="<?php echo $value['GarmentParts'];?>">
                                    <input type="hidden" name="WorkerActive" id="WorkerActive" class="form-control"
                                        value="<?php echo $value['WorkerActive'];?>">
                                    <input type="hidden" name="doccreationtime" id="doccreationtime"
                                        class="form-control" value="<?php echo $value['doccreationtime'];?>">
                                </tr>
                                <?php }?>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                        <div id="formLinesContainer" class=" mt-3">
                            <div id="formLinesFieldset">
                                <div id="rightFormLines">
                                    <div id="dragbar"></div>
                                    <fieldset id="fieldset_linegroup1" class="border p-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="Worker" class="form-label">Assign Worker<span
                                                        class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="Worker" id="Worker" class="form-control"
                                                        required>
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Click this for look up data"
                                                        onclick="showModalWorker()"><i
                                                            class="material-icons">search</i></button>
                                                </div>
                                            </div>
                                            <!-- Bootstrap 5 Modal -->
                                            <div class="modal" tabindex="-1" role="dialog" id="lookupModalWorker">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Select Worker Name</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                                $sql = "SELECT * FROM `ework_workers`";
                                                                $result = $conn->query($sql);
                                                                
                                                                if ($result->num_rows > 0) {
                                                                    $worker_data = [];
                                                                // output data of each row
                                                                while($row = $result->fetch_assoc()) {
                                                                    $worker_data[] =  $row;
                                                                }
                                                                } else {
                                                                echo "0 results";
                                                                }
                                                            ?>
                                                            <!-- Dropdown for options -->
                                                            <div class="input-group m-2">
                                                                <select class="form-select" id="lookupOptionsWorker"
                                                                    onchange="updateFields()">
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['cardnumber'];?>">
                                                                        <?php echo $worker_data_value['cardnumber'];?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="input-group m-2">
                                                                <select class="form-select" id="Name" disabled>
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['Name'];?>">
                                                                        <?php echo $worker_data_value['Name'];?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                                <select class="form-select" id="Position" disabled>
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['Position'];?>">
                                                                        <?php if($worker_data_value['Position'] == 0) { echo "Operator";}elseif ($worker_data_value['Position'] == 1) {
                                                                        echo "Helper";
                                                                        }elseif ($worker_data_value['Position'] == 2) {
                                                                        echo "Quality";
                                                                        }
                                                                        ?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- Close button -->
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <!-- Save button (you can customize the onclick function as needed) -->
                                                            <button type="button" class="btn btn-outline-primary"
                                                                onclick="saveDataWorker()">Save
                                                                changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-success" onclick="addToCart();">Add
                                            New</button>
                                    </fieldset>
                                </div>
                                <div class="clearfix"></div>
                                <div id="leftFormLines">
                                    <div id="formLines">
                                        <div id="fieldset_lineinformation">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="linenumber">Line</th>
                                                        <th class="Worker">Worker </th>
                                                        <th class="CardName">Card Name </th>
                                                        <th class="Position">Position </th>
                                                        <th class="Status">Status </th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                        $docnumber = htmlspecialchars($_GET['id']);
                                                        
                                                        $sql = "SELECT * FROM `eworker_assignment` where docnumber = '$docnumber'";
                                                        $result = $conn->query($sql);
                                            
                                                        if ($result->num_rows > 0) {
                                                            $sewing_line_data = [];
                                                        // output data of each row
                                                        while($row = $result->fetch_assoc()) {
                                                            $sewing_line_data[] =  $row;
                                                        }
                                                        } else {
                                                        echo "0 results";
                                                        } ?>

                                                <tbody>
                                                    <?php foreach ($sewing_line_data as $value) { ?>
                                                    <tr>
                                                        <td><?php echo $value['linenumber']; ?></td>


                                                        <td>
                                                            <div class="input-group m-2">
                                                                <select class="form-select"
                                                                    id="cardnumber_<?php echo $value['idlines']; ?>"
                                                                    onchange="updateFieldsOnEdit('<?php echo $value['idlines']; ?>')">
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['cardnumber'];?>"
                                                                        <?php if ($value['cardnumber'] == $worker_data_value['cardnumber']) { echo "selected"; } ?>>
                                                                        <?php echo $worker_data_value['cardnumber'];?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group m-2">
                                                                <select class="form-select"
                                                                    id="Name_<?php echo $value['idlines']; ?>" disabled>
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['Name'];?>"
                                                                        <?php if ($value['name'] == $worker_data_value['Name']) { echo "selected"; } ?>>
                                                                        <?php echo $worker_data_value['Name'];?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group m-2">
                                                                <select class="form-select"
                                                                    id="Position_<?php echo $value['idlines']; ?>"
                                                                    disabled>
                                                                    <?php foreach($worker_data as $worker_data_value){?>
                                                                    <option
                                                                        value="<?php echo $worker_data_value['Position'];?>"
                                                                        <?php if ($value['position'] == $worker_data_value['Position']) { echo "selected"; } ?>>
                                                                        <?php if($worker_data_value['Position'] == 0) { echo "Operator";}elseif ($worker_data_value['Position'] == 1) {
                                                                        echo "Helper";
                                                                        }elseif ($worker_data_value['Position'] == 2) {
                                                                        echo "Quality";
                                                                        }
                                                                        ?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group m-2">
                                                                <select class="form-select"
                                                                    id="WorkerStatus_<?php echo $value['idlines']; ?>">
                                                                    <?php  $data_status = ['',1];
                                                                       foreach($data_status as $data_status_value){?>
                                                                    <option value="<?php echo $data_status_value?>"
                                                                        <?php if ($value['WorkerActive'] == 1) { echo "selected"; }?>>
                                                                        <?php if($data_status_value == '') { echo "No";}elseif ($data_status_value == 1) {
                                                                        echo "Yes";
                                                                        }
                                                                        ?>
                                                                    </option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                                onclick="updateCartItem('<?php echo $value['idlines']; ?>')">Update</button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="confirmDelete('<?php echo $value['idlines']; ?>')">Delete</button>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>

                                            </table>
                                        </div>
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
        <!-- Edit Modal -->

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
        // Assuming 'a' is a variable that holds the condition
        var Docstatus = '<?php echo  $sewing_line_data[0]['docstatus'];?>';

        // Function to disable all input fields
        function disableAllFields() {
            var inputs = document.querySelectorAll('input, textarea, select,button');
            inputs.forEach(function(input) {
                input.disabled = true;
            });
        }

        // Check if 'a' is equal to 1, then disable all fields
        if (Docstatus === '9') {
            disableAllFields();
        }

        function updateFields() {
            // Get the selected cardnumber
            var selectedCardnumber = document.getElementById("lookupOptionsWorker").value;

            // Find the corresponding data in the worker_data array
            var selectedData = <?php echo json_encode($worker_data); ?>.find(function(item) {
                return item.cardnumber == selectedCardnumber;
            });

            // Update the Name and Position fields with the selected data
            document.getElementById("Name").value = selectedData.Name;
            document.getElementById("Position").value = selectedData.Position;
        }

        function updateFieldsOnEdit(idlines) {
            // Get the selected cardnumber
            var selectedCardnumber = document.getElementById("cardnumber_" + idlines).value;

            // Find the corresponding data in the worker_data array
            var selectedData = <?php echo json_encode($worker_data); ?>.find(function(item) {
                return item.cardnumber == selectedCardnumber;
            });

            // Update the Name and Position fields with the selected data
            document.getElementById("Name_" + idlines).value = selectedData.Name;
            document.getElementById("Position_" + idlines).value = selectedData.Position;
        }


        var myModalWorker = new bootstrap.Modal(document.getElementById('lookupModalWorker'));

        function showModalWorker() {
            myModalWorker.show();
        }

        // Function to save data (updated to hide modal)
        function saveDataWorker() {
            var selectedValue = document.getElementById('lookupOptionsWorker').value;
            document.getElementById('Worker').value = selectedValue;
            myModalWorker.hide();
        }

        $(document).ready(function() {
            $("#openFormBtn").on("click", function() {
                $("#rightFormLines").show();
            });
        });
        $(document).ready(function() {
            $("#closefrom").on("click", function() {
                $("#rightFormLines").hide();
            });
        });
        </script>
        <!-- JavaScript to show the modal -->
        <script>
        var cartItems = [];
        var rowNumber = 1;

        function addToCart() {


            var Worker = document.getElementById("Worker").value;
            var Name = document.getElementById("Name").value;
            var Position = document.getElementById("Position").value;

            var docdate = document.getElementById("docdate").getAttribute("value");
            var WorkOrder = document.getElementById("WorkOrder").getAttribute("value");
            var docnumber = document.getElementById("docnumber").getAttribute("value");
            var Style = document.getElementById("Style").getAttribute("value");
            var StyleDescription = document.getElementById("StyleDescription").getAttribute("value");
            var linenumber = document.getElementById("linenumber").getAttribute("value");
            var Customer = document.getElementById("Customer").getAttribute("value");
            var quantity = document.getElementById("quantity").getAttribute("value");

            var SalesOrder = document.getElementById("SalesOrder").value;
            var SalesOrderQuantity = document.getElementById("SalesOrderQuantity").value;
            var ShipDate = document.getElementById("ShipDate").value;
            var StepNumber = document.getElementById("StepNumber").getAttribute("value");
            var Process = document.getElementById("Process").getAttribute("value");
            var Description_StepName = document.getElementById("Description_StepName").getAttribute("value");
            var Machine = document.getElementById("Machine").value;
            var StepTimeMins = document.getElementById("StepTimeMins").value;
            var NextStep = document.getElementById("NextStep").value;
            var FirstStep = document.getElementById("FirstStep").value;
            var docstatus = document.getElementById("docstatus").value;
            var ColorSizeEntry = document.getElementById("ColorSizeEntry").value;
            var site = document.getElementById("site").value;
            var building = document.getElementById("building").value;
            var floor = document.getElementById("floor").value;
            var LastStep = document.getElementById("LastStep").value;
            var WorkerAssignment = document.getElementById("WorkerAssignment").value;
            var ColorSizeAssortment = document.getElementById("ColorSizeAssortment").value;
            var GarmentParts = document.getElementById("GarmentParts").value;
            var WorkerActive = document.getElementById("WorkerActive").value;
            var doccreationtime = document.getElementById("doccreationtime").value;

            // Check if all required fields are filled
            if (!Worker) {
                alert("Please fill in all required fields");
                return;
            }

            // Create a cartItem object
            var cartItem = {
                Worker: Worker,
                Name: Name,
                Position: Position,
                docdate: docdate,
                WorkOrder: WorkOrder,
                docnumber: docnumber,
                Style: Style,
                StyleDescription: StyleDescription,
                linenumber: linenumber,
                Customer: Customer,
                quantity: quantity,
                SalesOrder: SalesOrder,
                SalesOrderQuantity: SalesOrderQuantity,
                ShipDate: ShipDate,
                StepNumber: StepNumber,
                Process: Process,
                Description_StepName: Description_StepName,
                Machine: Machine,
                StepTimeMins: StepTimeMins,
                NextStep: NextStep,
                FirstStep: FirstStep,
                docstatus: docstatus,
                ColorSizeEntry: ColorSizeEntry,
                site: site,
                building: building,
                floor: floor,
                LastStep: LastStep,
                WorkerAssignment: WorkerAssignment,
                ColorSizeAssortment: ColorSizeAssortment,
                GarmentParts: GarmentParts,
                WorkerActive: WorkerActive,
                doccreationtime: doccreationtime,
                type: 'update'
            };
            cartItems.push(cartItem);
            if (cartItem.length === 0) {
                alert("Cart is empty. Add items before saving.");
                return;
            }

            var saveData = $.ajax({
                type: 'POST',
                url: "worker_assignment_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Form Submitted Successfully");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }



        function updateCartItem(idlines) {
            // Fetch other necessary data
            var updatedItem = {


                cardnumber: document.getElementById('cardnumber_' + idlines).value,
                name: document.getElementById('Name_' + idlines).value,
                position: document.getElementById('Position_' + idlines).value,
                WorkerActive: document.getElementById('WorkerStatus_' + idlines).value,
                idlines: idlines,
                type: 'edit'
                // Add other properties as needed
            };


            // Check if all required fields are filled
            if (!updatedItem) {
                alert("Please fill in all required fields");
                return;
            }
            cartItems.push(updatedItem);
            var saveData = $.ajax({
                type: 'POST',
                url: "worker_assignment_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Form Submitted Successfully");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        function confirmDelete(idlines) {
            var confirmDelete = confirm("Do you really want to delete the row with linenumber " + idlines + "?");

            if (confirmDelete) {
                // User clicked OK, proceed with the delete action
                removeFromCart(idlines);
            } else {
                // User clicked Cancel, do nothing or provide feedback
            }
        }

        // sendFormDataToServer();

        function removeFromCart(idlines) {
            // Fetch other necessary data
            var updatedItem = {

                idlines: idlines,
                type: 'delete'
                // Add other properties as needed
            };
            // Check if all required fields are filled
            if (!updatedItem) {
                alert("Please fill in all required fields");
                return;
            }
            cartItems.push(updatedItem);
            var saveData = $.ajax({
                type: 'POST',
                url: "worker_assignment_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Form Submitted Successfully");
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }
        </script>
</body>

</html>