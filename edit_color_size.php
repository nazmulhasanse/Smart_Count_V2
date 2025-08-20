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
            <h1>Color & Size</h1>
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
                                        <center>Doc No.</center>
                                    </th>
                                    <th>
                                        <center>Work Order</center>
                                    </th>
                                    <th>
                                        <center>Date</center>
                                    </th>
                                    <th>
                                        <center>Quantity</center>
                                    </th>
                                    <th>
                                        <center>Customer</center>
                                    </th>
                                    <th>
                                        <center>Style</center>
                                    </th>
                                    <th>
                                        <center>Style Description</center>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $docnumber = htmlspecialchars($_GET['id']);
                        
                                       $sql = "SELECT * FROM `ework_order_wise_color_size_qty` where WO_docnumber = '$docnumber' limit 1";
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
                                    <td id="docnumber" value="<?php echo $value['docnumber']; ?>">
                                        <?php echo $value['docnumber'];?></th>
                                    <td id="WorkOrder" value="<?php echo $value['WorkOrder']; ?>">
                                        <?php echo $value['WorkOrder'];?></td>
                                    <td id="docdate" value="<?php echo $value['docdate']; ?>">
                                        <?php echo $value['docdate'];?></td>
                                    <td id="quantity" value="<?php echo $value['quantity']; ?>">
                                        <?php echo $value['quantity'];?></td>
                                    <td id="Customer" value="<?php echo $value['Customer']; ?>">
                                        <?php echo $value['Customer'];?></td>
                                    <td id="Style" value="<?php echo $value['Style']; ?>"><?php echo $value['Style'];?>
                                    </td>
                                    <input type="hidden" id="WO_docnumber" value="<?php echo $value['WO_docnumber'];?>">
                                    <td id="StyleDescription" value="<?php echo $value['StyleDescription']; ?>">
                                        <?php echo $value['StyleDescription'];?>
                                    </td>

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
                                                <label for="size" class="form-label">Size<span
                                                        class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="size" id="size" class="form-control"
                                                        required />
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Click this for look up data" onclick="showModalSize()"><i
                                                            class="material-icons">search</i></button>
                                                </div>
                                            </div>
                                            <!-- Bootstrap 5 Modal -->
                                            <div class="modal" tabindex="-1" role="dialog" id="lookupModalSize">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Size Data</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                                $sql = "SELECT Description FROM `ework_mrd_library` WHERE LibraryName = 'Size'";
                                                                $result = $conn->query($sql);
                                                                
                                                                if ($result->num_rows > 0) {
                                                                    $size_data = [];
                                                                // output data of each row
                                                                while($row = $result->fetch_assoc()) {
                                                                    $size_data[] =  $row;
                                                                }
                                                                } else {
                                                                echo "0 results";
                                                                }
                                                                ?>
                                                            <!-- Dropdown for options -->
                                                            <div class="input-group mt-3">
                                                                <select class="form-select" id="lookupOptionsSize">
                                                                    <?php foreach($size_data as $value){?>
                                                                    <option value="<?php echo $value['Description'];?>">
                                                                        <?php echo $value['Description'];?>
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
                                                                onclick="saveDataSize()">Save
                                                                changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="color" class="form-label">Color Name<span
                                                        class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="color" id="color" class="form-control"
                                                        required />
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Click this for look up data"
                                                        onclick="showModalSewing()"><i
                                                            class="material-icons">search</i></button>
                                                </div>
                                            </div>
                                            <!-- Bootstrap 5 Modal -->
                                            <div class="modal" tabindex="-1" role="dialog" id="lookupModalColor">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Color Data</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php
                                                                $sql = "SELECT Description FROM `ework_mrd_library` WHERE LibraryName = 'Color'";
                                                                $result = $conn->query($sql);
                                                                
                                                                if ($result->num_rows > 0) {
                                                                    $color_data = [];
                                                                // output data of each row
                                                                while($row = $result->fetch_assoc()) {
                                                                    $color_data[] =  $row;
                                                                }
                                                                } else {
                                                                echo "0 results";
                                                                }
                                                                ?>
                                                            <!-- Dropdown for options -->
                                                            <div class="input-group mt-3">
                                                                <select class="form-select" id="lookupOptionsColor">
                                                                    <?php foreach($color_data as $value){?>
                                                                    <option value="<?php echo $value['Description'];?>">
                                                                        <?php echo $value['Description'];?>
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
                                                                onclick="saveDataColor()">Save
                                                                changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="color_size_Quantity" class="form-label">Quantity <span
                                                        class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="color_size_Quantity"
                                                        id="color_size_Quantity" class="form-control" required />
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="CustomerPO" class="form-label">Customer PO</label>
                                                <div class="input-group">
                                                    <input type="text" name="CustomerPO"
                                                        id="CustomerPO" class="form-control">
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
                                                        <th class="linenumber">Color </th>
                                                        <th class="linenumber">Size </th>
                                                        <th class="linenumber">Quantity </th>
                                                        <th class="linenumber">Customer PO </th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                        $docnumber = htmlspecialchars($_GET['id']);
                                                        
                                                        $sql = "SELECT * FROM `ework_order_wise_color_size_qty` where WO_docnumber = '$docnumber' order by linenumber ASC";
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
                                                            <!-- Dropdown for options -->
                                                            <div class="input-group ">
                                                                <select class="form-select"
                                                                    id="lookupOptionsColor_<?php echo $value['idlines']; ?>">
                                                                    <?php foreach ($color_data as $color_data_value) { ?>
                                                                    <option
                                                                        value="<?php echo $color_data_value['Description']; ?>"
                                                                        <?php if ($value['color'] == $color_data_value['Description']) { echo "selected"; } ?>>
                                                                        <?php echo $color_data_value['Description']; ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <select class="form-select"
                                                                    id="lookupOptionsSize_<?php echo $value['idlines']; ?>">
                                                                    <?php foreach ($size_data as $size_data_value) { ?>
                                                                    <option
                                                                        value="<?php echo $size_data_value['Description']; ?>"
                                                                        <?php if ($value['size'] == $size_data_value['Description']) { echo "selected"; } ?>>
                                                                        <?php echo $size_data_value['Description']; ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="color_size_quntity"
                                                                id="color_size_quntity_<?php echo $value['idlines']; ?>"
                                                                class="form-control" value="<?php echo $value['qty']?>"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="CustomerPO"
                                                                id="CustomerPO_<?php echo $value['idlines']; ?>"
                                                                class="form-control" value="<?php echo $value['CustomerPO']?>"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-primary"
                                                                onclick="updateCartItem('<?php echo $value['idlines']; ?>')">Save</button>
                                                            <button type="button" class="btn btn-outline-danger"
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


        var myModalsewing = new bootstrap.Modal(document.getElementById('lookupModalColor'));

        function showModalSewing() {
            myModalsewing.show();
        }

        // Function to save data (updated to hide modal)
        function saveDataColor() {
            var selectedValue = document.getElementById('lookupOptionsColor').value;
            document.getElementById('color').value = selectedValue;
            myModalsewing.hide();
        }

        var myModalSize = new bootstrap.Modal(document.getElementById('lookupModalSize'));

        function showModalSize() {
            myModalSize.show();
        }

        // Function to save data (updated to hide modal)
        function saveDataSize() {
            var selectedValue = document.getElementById('lookupOptionsSize').value;
            document.getElementById('size').value = selectedValue;
            myModalSize.hide();
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
        <!-- JavaScript to show the modal --------------------------------->
        <script>
        var cartItems = [];
        var rowNumber = 1;

        function addToCart() {

            var size = document.getElementById("size").value;
            var color = document.getElementById("color").value;
            var color_size_Quantity = document.getElementById("color_size_Quantity").value;
            var CustomerPO = document.getElementById("CustomerPO").value;

            var docdateValue = document.getElementById("docdate").getAttribute("value");
            var docnumberValue = document.getElementById("docnumber").getAttribute("value");
            var WO_docnumber = document.getElementById("WO_docnumber").value;
            var WorkOrderValue = document.getElementById("WorkOrder").getAttribute("value");
            var quantityValue = document.getElementById("quantity").getAttribute("value");
            // var SalesOrderValue = document.getElementById("SalesOrder").getAttribute("value");
            var CustomerValue = document.getElementById("Customer").getAttribute("value");
            var StyleValue = document.getElementById("Style").getAttribute("value");
            var StyleDescriptionValue = document.getElementById("StyleDescription").getAttribute("value");
            // var SalesOrderQuantityValue = document.getElementById("SalesOrderQuantity").getAttribute("value");

            // Check if all required fields are filled
            if (!color || !size || !color_size_Quantity) {
                alert("Please fill in all required fields");
                return;
            }

            // Create a cartItem object
            var cartItem = {
                size: size,
                color: color,
                color_size_Quantity: color_size_Quantity,
                CustomerPO: CustomerPO,

                docdate: docdateValue,
                docnumber: docnumberValue,
                WO_docnumber: WO_docnumber,
                WorkOrder: WorkOrderValue,
                // quantity: quantityValue,
                // SalesOrder: SalesOrderValue,
                Customer: CustomerValue,
                Style: StyleValue,
                StyleDescription: StyleDescriptionValue,
                // SalesOrderQuantity: SalesOrderQuantityValue
                type: 'update'
            };
            cartItems.push(cartItem);
            if (cartItem.length === 0) {
                alert("Cart is empty. Add items before saving.");
                return;
            }

            var saveData = $.ajax({
                type: 'POST',
                url: "color_size_store.php",
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


// -------------------------//
        function updateCartItem(idlines) {
            // Fetch other necessary data
            var updatedItem = {

                color: document.getElementById('lookupOptionsColor_' + idlines).value,
                size: document.getElementById('lookupOptionsSize_' + idlines).value,
                quantity: document.getElementById('color_size_quntity_' + idlines).value,
                CustomerPO: document.getElementById('CustomerPO_' + idlines).value,
                idlines: idlines,
                type: 'edit'
                // Add other properties as needed
            };


            // Check if all required fields are filled
            if (!color || !size || !quantity) {
                alert("Please fill in all required fields");
                return;
            }
            cartItems.push(updatedItem);
            var saveData = $.ajax({
                type: 'POST',
                url: "color_size_store.php",
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
            if (!color || !size || !quantity) {
                alert("Please fill in all required fields");
                return;
            }
            cartItems.push(updatedItem);
            var saveData = $.ajax({
                type: 'POST',
                url: "color_size_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Form Submitted Successfully");
                    window.location.reload();
                    //window.location.replace("http://sc2.liz.com/ie/color_size_list.php");
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }
        </script>
</body>

</html>