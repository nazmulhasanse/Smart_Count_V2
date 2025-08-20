<?php
date_default_timezone_set('Asia/Dhaka');
$currentDateTime = date('Y-m-d');
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
            <h1>Quantity Breakdown</h1>
        </header>
        <div class="content-wrapper">
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <form action="#" method="post" id="formERP" autocomplete="off" class="container mt-3">
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary me-2" onclick="saveData();">
                                    <i class="material-icons">Save</i>
                                </button>
                                <input type="button" class="btn btn-secondary me-2" value="Cancel" title="Cancel changes and go back to the previous page">
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
                                        <center>Total Allocated Qty</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $doc_number = htmlspecialchars($_GET['id']);
                                $sql = "SELECT * FROM ework_sales_order WHERE docnumber = '$doc_number' LIMIT 1";
                                $result = $conn->query($sql);




                                if ($result->num_rows > 0) {
                                    $sewing_line_data = $result->fetch_assoc();
                                } else {
                                    echo "<tr><td colspan='8'>0 results</td></tr>";
                                }
                                ?>
                                <?php if (isset($sewing_line_data)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sewing_line_data['docdate']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['WorkOrder']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['docnumber']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['Style']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['StyleDescription']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['line']); ?></td>
                                        <td><?php echo htmlspecialchars($sewing_line_data['Customer']); ?></td>
                                        <td id="totalQuantity"><?php echo htmlspecialchars($sewing_line_data['quantity']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div id="formLinesContainer" class="mt-3">
                            <fieldset class="border p-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="new_quantity" class="form-label">Quantity<span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="new_quantity" id="new_quantity" class="form-control" required>

                                            <!-- Embed docnumber in a hidden field -->
                                            <input type="hidden" id="docNumber" value="<?php echo $doc_number; ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success mt-2" onclick="addToCart();">Add Quantity</button>
                                <button type="button" class="btn btn-outline-secondary mt-2" id="closefrom">Close</button>
                            </fieldset>
                        </div>

                        <!-- <div class="table-responsive mt-3"> -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Line Entry Date</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <?php
                            // Assuming you have a connection to the database in $conn
                            $docNumber = htmlspecialchars($sewing_line_data['docnumber']); // Get docNumber from sewing_line_data

                            // Fetch the initial quantity from the ework_qty_breakdown table
                            $stmt = $conn->prepare("SELECT Quantity, DocDate FROM ework_qty_breakdown WHERE DocNumber = ? AND is_initial = 1 LIMIT 1");
                            $stmt->bind_param("s", $docNumber);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $initialData = $result->fetch_assoc();

                            // Check if initial data exists
                            if ($initialData) {
                                $initialQuantity = htmlspecialchars($initialData['Quantity']); // Get initial quantity if found
                                $initialDocDate = htmlspecialchars($initialData['DocDate']);   // Get initial DocDate if found
                            } else {
                                $initialQuantity = null; // No initial quantity found
                                $initialDocDate = null;   // No date found
                            }
                            $stmt->close();

                            ?>

                            <tbody id="cartItems">
                                <?php if ($initialQuantity !== null): ?>
                                    <!-- Static row for initial quantity -->
                                    <tr>
                                        <td><?php echo htmlspecialchars($initialDocDate); ?></td>
                                        <td id="staticQuantity" style="color: black; font-weight: bold;">
                                            Initial allocated qty: <?php echo htmlspecialchars($initialQuantity); ?>
                                        </td>
                                        <td></td> <!-- No delete button for this row -->
                                    <?php endif; ?>
                            </tbody>

                        </table>
                        <!-- </div> -->
                    </form>
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
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
        $(document).ready(function() {
            $("#closefrom").on("click", function() {
                $("#formLinesContainer").hide();
            });
        });

        var cartItems = [];
        var totalQuantity = parseInt(document.getElementById("totalQuantity").textContent) || 0; // Initialize with PHP value

        function addToCart() {
            var quantity = parseInt(document.getElementById("new_quantity").value);
            var lineEntryDate = "<?php echo $currentDateTime; ?>";

            if (!quantity || quantity <= 0) {
                alert("Please enter a valid quantity");
                return;
            }

            // Add row to the cart table
            var newRow = `
        <tr>
            <td>${lineEntryDate}</td>
            <td>${quantity}</td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFromCart(this, ${quantity})">Delete</button>
            </td>
        </tr>`;
            document.getElementById("cartItems").insertAdjacentHTML('beforeend', newRow);

            // Store the item in cart array
            cartItems.push({
                quantity: quantity,
                lineEntryDate: lineEntryDate
            });

            // Update total quantity
            totalQuantity += quantity;
            document.getElementById("totalQuantity").textContent = totalQuantity;

            // Clear the input field
            document.getElementById("new_quantity").value = "";
        }

        function removeFromCart(button, quantity) {
            // Remove the row from the table
            var row = button.closest("tr");
            var lineEntryDate = row.cells[0].textContent.trim(); // Fetch line entry date

            // Find the item in cartItems using both lineEntryDate and quantity
            var indexToRemove = cartItems.findIndex(item => item.lineEntryDate === lineEntryDate && item.quantity === quantity);

            if (indexToRemove !== -1) { // Ensure the item exists in the cartItems array
                // Remove the item from cartItems array
                cartItems.splice(indexToRemove, 1);

                // Remove the row from the table
                row.remove();

                // Update total quantity
                totalQuantity -= quantity;
                document.getElementById("totalQuantity").textContent = totalQuantity;
            } else {
                console.log("Item not found in cart.");
            }
        }



        //    {{------------- Code for saving data-----------------}}
        function saveData() {
            if (cartItems.length === 0) {
                alert("Quantity is empty ");
                return;
            }

            // Collect data from the cart (only visible items)
            var cartData = cartItems.map(item => ({
                quantity: item.quantity,
                lineEntryDate: item.lineEntryDate
            }));

            // Send data to server
            $.ajax({
                type: "POST",
                url: "add_quantity_store.php",
                data: {
                    cartItems: JSON.stringify(cartData), // Send cart items
                    docNumber: "<?php echo htmlspecialchars($sewing_line_data['docnumber']); ?>",
                    docDate: "<?php echo htmlspecialchars($sewing_line_data['docdate']); ?>",
                    workOrder: "<?php echo htmlspecialchars($sewing_line_data['WorkOrder']); ?>",
                    style: "<?php echo htmlspecialchars($sewing_line_data['Style']); ?>",

                    initialQuantity: "<?php echo htmlspecialchars($sewing_line_data['quantity']); ?>",

                    totalQuantity: totalQuantity // Send total quantity to server for updating in the `ework_sales_order` table
                },
                success: function(response) {
                    alert(response); // Show server response
                    // Optionally clear the cart
                    cartItems = [];
                    document.getElementById("cartItems").innerHTML = '';
                    document.getElementById("totalQuantity").textContent = '0';
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        //    {{------------- Code for Fetching data from 'ework_qty_breakdown' table-------------}}

        $(document).ready(function() {
            // Fetch the docNumber from the hidden input
            var docNumber = document.getElementById('docNumber').value;

            // Fetch existing cart data using AJAX
            $.ajax({
                type: "GET",
                url: "fetch_qty_breakdown.php",
                data: {
                    docNumber: docNumber
                },
                success: function(response) {
                    console.log("Fetched data:", response);
                    try {
                        var cartItems = JSON.parse(response);
                        cartItems.forEach(function(item) {
                            var lineEntryDate = item.LineEntryDate;
                            var quantity = parseInt(item.Quantity);

                            var isInitial = parseInt(item.is_initial); // Check if it's the initial quantity

                            // Only add rows that are NOT the initial quantity (assuming initial quantity is marked with 'is_initial')
                            if (!isInitial) {
                                var newRow = `
                                    <tr>
                                        <td>${lineEntryDate}</td>
                                        <td>${quantity}</td>
                                        <td><button class="delete-btn btn btn-danger btn-sm" data-docnumber="${docNumber}" data-quantity="${quantity}">Delete</button></td>
                                    </tr>`;
                                $("#cartItems").append(newRow);
                            }
                        });

                        // Handle delete button click event
                        $('.delete-btn').on('click', function() {
                            var docNumber = $(this).data('docnumber');
                            var quantity = $(this).data('quantity');
                            deleteQuantity(docNumber, quantity);
                        });

                    } catch (e) {
                        console.error("Error parsing JSON response:", e);
                        console.error("Response received:", response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching cart data:", xhr.responseText);
                }
            });

            function deleteQuantity(docNumber, quantity) {
                $.ajax({
                    type: "POST",
                    url: "delete_qty_breakdown.php",
                    data: {
                        docNumber: docNumber,
                        quantity: quantity
                    },
                    success: function(response) {
                        alert('Quantity deleted successfully');
                        location.reload(); // Reload page after deletion
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting quantity:", xhr.responseText);
                    }
                });
                $(document).on('keypress', function(e) {
                    // Prevent default behavior when the Enter key is pressed
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>

</body>

</html>