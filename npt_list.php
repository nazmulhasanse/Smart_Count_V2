<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   include 'header.php';
   ?>

<style type="text/css">
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 30%;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include('navbar.php'); ?>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <?php include('sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <header style="text-align: center;">
           <h1>NPT List</h1>
       </header>
        <div class="content-wrapper">
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div id="formLinesContainer" class=" mt-3">
                        <div id="formLinesFieldset">
                            <div class="mb-1">

                                <button type="button" class="btn btn-primary newLine me-2"
                                    onclick="openPlannedNPT()">New</button>
<!-- <button type="button" class="btn btn-primary newLine me-2"
                                    onclick="showDocumentChoice()">New</button>
 -->
                                <!-- <div id="documentChoiceModal" class="modal">
                                  <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <p>Choose Document Type:</p>
                                    <button type="button" class="btn btn-primary newLine me-2" onclick="openPlannedNPT()">Planned NPT</button><br>
                                    <button type="button" class="btn btn-primary newLine me-2" onclick="openUnplannedNPT()">Unplanned NPT</button>
                                  </div>
                                </div> -->
                            </div>
                            <div class="clearfix"></div>
                            <div id="leftFormLines">
                                <div id="formLines">
                                    <div id="fieldset_lineinformation">
                                        <table id="myDataTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <center>NPT No.</center>
                                                    </th>
                                                    <th>
                                                        <center>Doc Date</center>
                                                    </th>
                                                    <th>
                                                        <center>Status</center>
                                                    </th>
                                                    
                                                    <th>
                                                        <center>Doc No.</center>
                                                    </th>
                                                    <th>
                                                        <center>Work Order</center>
                                                    </th>

                                                    <th>
                                                        <center>Sewing Line</center>
                                                    </th>

                                                    <th>
                                                        <center>Customer</center>
                                                    </th>

                                                    <th>
                                                        <center>Style</center>
                                                    </th>
                                                    
                                                    
                                                    <!-- <th>
                                                        <center>Actions</center>
                                                    </th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                       $sql = "SELECT * FROM `ework_daily_npt` GROUP BY nptnumber ORDER BY docdate DESC";
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
                                                    <td><a href="npt_edit.php?id=<?php echo $value['nptnumber'];?>"
                                                            target="_blank"><?php echo $value['nptnumber'];?></a></td>
                                                    <!-- <td><?php echo $value['linenumber'];?></td> -->
                                                    <!-- <td><?php if( $value['npttype'] == '0'){?>
                                                        Planned
                                                        <?php }elseif( $value['npttype'] == '1'){ ?>
                                                        Unplanned
                                                        <?php  }?></td> -->
                                                    <td><?php echo $value['docdate'];?></td>
                                                    <td><?php if( $value['status'] == ''){?>
                                                        Not Approved
                                                        <?php }elseif( $value['status'] == '1'){ ?>
                                                        Approved
                                                        <?php }?></td>
                                                    <td><?php echo $value['docnumber'];?></td>
                                                    <td><?php echo $value['WorkOrder'];?></td>
                                                    <td><?php echo $value['line'];?></td>
                                                    <td><?php echo $value['Customer'];?></td>
                                                    <td><?php echo $value['Style'];?></td>
                                                    <!-- <td><?php echo $value['start_time'];?></td> -->
                                                    <!-- <td><?php echo $value['end_time'];?></td> -->
                                                    <!-- <td><?php echo $value['duration'];?></td> -->
                                                    <!-- <td><?php echo $value['reason'];?></td> -->
                                                    <!-- <td><?php echo $value['remarks'];?></td> -->
                                                    <!-- <td><?php echo $value['StepNumber'];?></td> -->
                                                    
                                                    <!-- <td style="width: 15%">
                                                         <div class="btn-group" role="group">
                                                            <?php if ($value['status']  == ''){ ?>
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm deleteLine"
                                                                onclick="updateDocStatus('<?php echo $value['nptnumber']; ?>', '<?php echo $value['linenumber']; ?>')">Approve</button>

                                                            <?php } elseif ($value['status']  == 1){ ?>
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm deleteLine"
                                                                onclick="disappDocStatus('<?php echo $value['nptnumber']; ?>', '<?php echo $value['linenumber']; ?>')">Disapprove</button>

                                                            <?php } elseif ($value['status']  == 2){ ?>
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm deleteLine"
                                                                onclick="updateDocStatus('<?php echo $value['nptnumber']; ?>', '<?php echo $value['linenumber']; ?>')">Approve
                                                                </button>

                                                            <?php } ?>
                                                        </div> 
                                                    </td> -->
                                                </tr>
                                                <?php }?>
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
            "scrollY": "100%", // Set the desired height
            "scrollX": true, // Enable horizontal scrolling if needed
            "buttons": ["excel"],
            "pageLength": 20,
            "order": [[1, 'desc']]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');
    });

    // function addNew() {
    //     // window.open('npt_entry.php', '_blank');

    //     var choice = prompt("Choose document:\n1. Planned NPT \n2. Unplanned NPT");

    //     if (choice === "1") {
    //         window.open('npt_entry.php', '_blank');
    //     } else if (choice === "2") {
    //         window.open('unplanned_npt_entry.php', '_blank');
    //     } else {
    //         alert("Invalid choice");
    //     }
    // }

    // function showDocumentChoice() {
    //     var modal = document.getElementById('documentChoiceModal');
    //     modal.style.display = "block";
    // }

    // function closeModal() {
    //     var modal = document.getElementById('documentChoiceModal');
    //     modal.style.display = "none";
    // }

    function openPlannedNPT() {
        window.open('npt_entry.php', '_blank');
        closeModal();
    }

    // function openUnplannedNPT() {
    //     window.open('unplanned_npt_entry.php', '_blank');
    //     closeModal();
    // }
    // var cartItems = [];






    function updateDocStatus(nptnumber, linenumber) {

        var updatedItem = {
            nptnumber: nptnumber,
            linenumber: linenumber,
            type: 'updateDocStatus'
        };
        if (!updatedItem) {
            alert("Please fill in all required fields");
            return;
        }
        cartItems.push(updatedItem);
        var saveData = $.ajax({
            type: 'POST',
            url: "npt_store.php",
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



    function disappDocStatus(nptnumber, linenumber) {

        var updatedItem = {
            nptnumber: nptnumber,
            linenumber: linenumber,
            type: 'disappDocStatus'
        };
        if (!updatedItem) {
            alert("Please fill in all required fields");
            return;
        }
        cartItems.push(updatedItem);
        var saveData = $.ajax({
            type: 'POST',
            url: "npt_store.php",
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