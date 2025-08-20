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
            <h1>Part's Name</h1>
        </header>
        <div class="content-wrapper">
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <form action="#" method="post" id="formERP" autocomplete="off" class="container mt-3">
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary me-2"
                                    onclick="sendFormDataToServer()"><i class="material-icons">save</i></button>
                                <input type="button" class="btn btn-secondary me-2" value="Cancel"
                                    title="Cancel changes and go back to the previous page">
                                <!-- <button type="button" class="btn btn-light me-2"><i class="material-icons">edit</i></button> -->
                                <!-- Add other buttons as needed -->
                            </div>
                        </div>

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
                                    <!-- <th>
                    <center>Sales Order Qty</center>
                </th> -->


                                </tr>
                            </thead>
                            <tbody>
                                <?php
             $doc_number = htmlspecialchars($_GET['id']);
           
                            $sql = "SELECT * FROM `ework_sales_order` where docnumber = '$doc_number' limit 1";
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
                                        <?php echo $value['StyleDescription'];?></td>
                                    <td id="line" value="<?php echo $value['line']; ?>">
                                        <?php echo $value['line'];?></td>
                                    <td id="Customer" value="<?php echo $value['Customer']; ?>">
                                        <?php echo $value['Customer'];?></td>
                                    <td id="quantity" value="<?php echo $value['quantity']; ?>">
                                        <?php echo $value['quantity'];?></td>
                                    <!-- <td id="SalesOrderQuantity" value="<?php //echo $value['SalesOrderQuantity']; ?>">
                    <?php //echo $value['SalesOrderQuantity'];?></td> -->
                                </tr>
                                <?php }?>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>

                        <div id="formLinesContainer" class=" mt-3">

                            <div id="formLinesFieldset">
                                <!-- <div id="formLineslegend" class="mt-2">Parts Name Information</div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-primary newLine me-2"
                                        id="openFormBtn">Add New</button>
                                </div> -->
                                <div id="rightFormLines">
                                    <div id="dragbar"></div>


                                    <fieldset id="fieldset_linegroup1" class="border p-3">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="Part" class="form-label">Part Name<span
                                                        class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="Part" id="Part" class="form-control"
                                                        required>
                                                    <!-- <button type="button" class="btn btn-outline-primary"
                                                        title="Click this for look up data" onclick="showModalPart()"><i
                                                            class="material-icons">search</i></button> -->
                                                </div>
                                            </div>




                                        </div>
                                        <button type="button" class="btn btn-outline-success"
                                            onclick="addToCart();">Save</button>
                                        <button type="button" class="btn btn-outline-success"
                                            id="closefrom">Close</button>
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
                                                        <th class="Part">Part </th>

                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cartItems" class="editing">

                                                </tbody>
                                            </table>
                                        </div>
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
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Edit form content -->



                    <fieldset id="fieldset_linegroup1" class="border p-3">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="lg_linenumber" class="form-label">Line</label>
                                <input type="text" name="linenumber" id="lg_linenumber1" class="form-control" value=""
                                    readonly="readonly">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_StepNumber" class="form-label">Step Number<span
                                        class="required">*</span></label>
                                <input type="text" name="StepNumber" id="lg_StepNumber1" class="form-control" value=""
                                    required="required">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_Process" class="form-label">Process<span
                                        class="required">*</span></label>
                                <select name="Process" id="lg_Process1" class="form-select" required="required">
                                    <option value="">Select</option>
                                    <option value="Quality">Quality</option>
                                    <option value="Sewing">Sewing</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_Description_StepName1" class="form-label">Description Step
                                    Name<span class="required">*</span></label>
                                <textarea name="Description_StepName" id="lg_Description_StepName1" class="form-control"
                                    required="required" spellcheck="false"></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_Machine" class="form-label">Machine</label>
                                <select name="Machine" id="lg_Machine1" class="form-select">
                                    <option value="">Select</option>
                                    <option value="BA">Button Attach</option>
                                    <option value="BH">Button Hole</option>
                                    <option value="FL">Flatlock</option>
                                    <option value="FUSING">Fusing</option>
                                    <option value="KNS">Kansai</option>
                                    <option value="MAN">Manual</option>
                                    <option value="OL">Overlock</option>
                                    <option value="PM">Plain</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_StepTimeMins" class="form-label">SMV</label>
                                <input type="text" name="StepTimeMins" id="lg_StepTimeMins1" class="form-control"
                                    value="">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_NextStep" class="form-label">Next Step</label>
                                <input type="text" name="NextStep" id="lg_NextStep1" class="form-control" value="">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_FirstStep" class="form-label">First Step</label>
                                <select name="FirstStep" id="lg_FirstStep1" class="form-select">
                                    <option value="">Select</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_LastStep" class="form-label">Last Step</label>
                                <select name="LastStep" id="lg_LastStep1" class="form-select">
                                    <option value="">Select</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lg_ColorPartEntry" class="form-label">Color Part Entry</label>
                                <select name="ColorPartEntry" id="lg_ColorPartEntry1" class="form-select">
                                    <option value="">Select</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-primary" onclick="saveChanges()">Save
                            changes</button>
                    </div>
                </div>
            </div>
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
        // var myModalsewing = new bootstrap.Modal(document.getElementById('lookupModalColor'));

        // function showModalSewing() {
        //     myModalsewing.show();
        // }

        // // Function to save data (updated to hide modal)
        // function saveDataColor() {
        //     var selectedValue = document.getElementById('lookupOptionsColor').value;
        //     document.getElementById('color').value = selectedValue;
        //     myModalsewing.hide();
        // }

        // var myModalPart = new bootstrap.Modal(document.getElementById('lookupModalPart'));

        // function showModalPart() {
        //     myModalPart.show();
        // }
        // Function to save data (updated to hide modal)
        function saveDataPart() {
            var selectedValue = document.getElementById('lookupOptionsPart').value;
            document.getElementById('Part').value = selectedValue;
            myModalPart.hide();
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

            var Part = document.getElementById("Part").value;
            // var color = document.getElementById("color").value;
            // var Part_Quantity = document.getElementById("Part_Quantity").value;


            var docdateValue = document.getElementById("docdate").getAttribute("value");
            var WorkOrderValue = document.getElementById("WorkOrder").getAttribute("value");
            var quantityValue = document.getElementById("quantity").getAttribute("value");
            var docnumberValue = document.getElementById("docnumber").getAttribute("value");
            // var linenumberValue = document.getElementById("linenumber").getAttribute("value");
            var CustomerValue = document.getElementById("Customer").getAttribute("value");
            var StyleValue = document.getElementById("Style").getAttribute("value");
            var StyleDescriptionValue = document.getElementById("StyleDescription").getAttribute("value");
            var lineValue = document.getElementById("line").getAttribute("value");
            // var SalesOrderQuantityValue = document.getElementById("SalesOrderQuantity").getAttribute("value");

            // Check if all required fields are filled
            if (!Part) {
                alert("Please fill in all required fields");
                return;
            }

            // Create a cartItem object
            var cartItem = {
                Part: Part,
                // color: color,
                // Part_Quantity: Part_Quantity,

                docdate: docdateValue,
                WorkOrder: WorkOrderValue,
                quantity: quantityValue,
                // linenumber: linenumberValue,
                Customer: CustomerValue,
                Style: StyleValue,
                StyleDescription: StyleDescriptionValue,
                line: lineValue,
                docnumber: docnumberValue,
                type: 'add'
            };


            // Push the cartItem into the cartItems array
            cartItems.push(cartItem);

            console.log(cartItem);
            // Create a table row for the added item
            var newRow = document.createElement("tr");
            newRow.innerHTML = `
    <td>${rowNumber}</td>
    <td>${Part}</td>
    <td>
        <button type="button" class="btn btn-outline-danger" onclick="removeFromCart(this)">Delete</button>
    </td>
`;

            // Append the new row to the table body
            document.getElementById("cartItems").appendChild(newRow);

            // Increment the row number for the next entry
            rowNumber++;
        }

        // Function to make the AJAX request
        function sendFormDataToServer() {
            // Check if cartItems is not empty
            if (cartItems.length === 0) {
                alert("Cart is empty. Add items before saving.");
                return;
            }

            var saveData = $.ajax({
                type: 'POST',
                url: "parts_name_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(resultData) {
                    var docnumberValue = document.getElementById("docnumber").getAttribute("value");
                    window.location.href = 'edit_parts_name.php?id=' + docnumberValue;
                },
                error: function() {
                    alert("Something went wrong");
                }
            });
        }

        // sendFormDataToServer();





        function removeFromCart(button) {
            // Remove the corresponding row from the table
            var row = button.closest("tr");
            row.remove();
        }









        function saveChanges() {
            // Find the editing row based on the 'editing' class
            var editingRow = document.querySelector("tr.fieldset_linegroup1");

            if (editingRow) {
                // Update the row data
                editingRow.cells[0].innerText = document.getElementById("lg_linenumber1").value;
                editingRow.cells[1].innerText = document.getElementById("lg_StepNumber1").value;
                editingRow.cells[2].innerText = document.getElementById("lg_Process1").value;
                editingRow.cells[3].innerText = document.getElementById("lg_Description_StepName1").value;
                editingRow.cells[4].innerText = document.getElementById("lg_Machine1").value;
                editingRow.cells[5].innerText = document.getElementById("lg_StepTimeMins1").value;
                editingRow.cells[6].innerText = document.getElementById("lg_NextStep1").value;
                editingRow.cells[7].innerText = document.getElementById("lg_FirstStep1").value;
                editingRow.cells[8].innerText = document.getElementById("lg_LastStep1").value;
                editingRow.cells[9].innerText = document.getElementById("lg_ColorPartEntry1").value;


                // Remove the 'editing' class
                editingRow.classList.remove("editing");

                // Close the edit modal
                $('#editModal').hide();

                $("#rightFormLines").hide();

            } else {
                console.error("Row not found.");
            }
        }

        function editCartItem(button) {
            // Get the corresponding row
            var row = button.closest("tr");

            // Populate the modal fields with existing data
            var lg_linenumberValue = row.cells[0].innerText;
            var lg_StepNumberValue = row.cells[1].innerText;
            var lg_ProcessValue = row.cells[2].innerText;
            var lg_Description_StepNameValue = row.cells[3].innerText;
            var lg_MachineValue = row.cells[4].innerText;
            var lg_StepTimeMinsValue = row.cells[5].innerText;
            var lg_NextStepValue = row.cells[6].innerText;
            var lg_FirstStepValue = row.cells[7].innerText;
            var lg_LastStepValue = row.cells[8].innerText;
            var lg_ColorPartEntryValue = row.cells[9].innerText;

            document.getElementById("lg_linenumber1").value = lg_linenumberValue;
            document.getElementById("lg_StepNumber1").value = lg_StepNumberValue;
            document.getElementById("lg_Process1").value = lg_ProcessValue;
            document.getElementById("lg_Description_StepName1").value = lg_Description_StepNameValue;
            document.getElementById("lg_Machine1").value = lg_MachineValue;
            document.getElementById("lg_StepTimeMins1").value = lg_StepTimeMinsValue;
            document.getElementById("lg_NextStep1").value = lg_NextStepValue;
            document.getElementById("lg_FirstStep1").value = lg_FirstStepValue;
            document.getElementById("lg_LastStep1").value = lg_LastStepValue;
            document.getElementById("lg_ColorPartEntry1").value = lg_ColorPartEntryValue;


        }
        </script>
</body>

</html>