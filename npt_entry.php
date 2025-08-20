<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   include 'header.php'; 

   $query_SewingLine = "SELECT * FROM ework_mrd_library WHERE LibraryName='SewingLine'";
   $result_SewingLine = $conn->query($query_SewingLine);

   $query_NPT_Reason = "SELECT * FROM ework_mrd_library WHERE LibraryName='NPT'";
   $result_NPT_Reason = $conn->query($query_NPT_Reason);

   $query_DocNumber = "SELECT DISTINCT(docnumber) AS DocNumber FROM `ework_sales_order` WHERE docstatus = '1' ORDER BY docnumber DESC;";
   $result_DocNumber = $conn->query($query_DocNumber);

   

?>

<style type="text/css">
        body {
            font-family: Arial, sans-serif;
        }
        
        .popup {
            display: none;
            position: auto;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #000;
            background-color: #f9f9f9;
            padding: 20px;
            z-index: 1000;
            max-width: 600px;
            max-height: 900px;
            width: 100%;
            height: 100%;
        }
        
        .popup-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .popup-section {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        
        .popup-section div {
            display: flex;
            align-items: center;
            width: 50%;
            box-sizing: border-box;
            padding: 5px;
        }
        
        .popup-section div input {
            margin-right: 10px;
        }
        
        .popup-section div label {
            display: flex;
            align-items: center;
/*            background-color: #001f3f;*/
/*            color: #fff;*/
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .popup-section div label img {
            margin-right: 10px;
        }
        
        .popup-footer {
            text-align: right;
        }
        
        .popup-footer button {
            padding: 10px 20px;
            background-color: #001f3f;
            color: #fff;
            border: none;
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
           <h1>NPT Document</h1>
       </header>
        <div class="content-wrapper">
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <form action="#" method="post" id="formERP" autocomplete="off" class="container mt-3">
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary me-2"
                                    onclick="sendFormDataToServer()"><i class="material-icons">Save</i></button>
                                <input type="button" class="btn btn-secondary me-2" value="Cancel"
                                    title="Cancel changes and go back to the previous page" onclick="sendToListButton()">
                                <!-- <button type="button" class="btn btn-light me-2"><i class="material-icons">edit</i></button> -->
                                <!-- Add other buttons as needed -->
                            </div>
                        </div>
                        <div class="border p-3">
                            <div class="row mb-3">

                                <div class="col-md-4">
                                    <label for="docdate" class="form-label">Date<span class="required">*</span></label>
                                    <input type="date" name="docdate" id="docdate" class="form-control datepicker"  >
                                </div>

                                <div class="col-md-4">
                                    <label for="docnumber" class="form-label">Document No.<span
                                            class="required">*</span></label>
                                    <select name="docnumber" id="docnumber" class="form-select"
                                       required="required">
                                       <option value="">Select</option>
                                       <?php
                                          foreach ($result_DocNumber as $key => $DocNumber) {
                                          ?>
                                       <option value="<?php echo $DocNumber['DocNumber']; ?>"><?php echo $DocNumber['DocNumber']; ?></option>
                                       <?php } ?>
                                    </select> 
                                </div>

                                


                                <div class="col-md-4">
                                    <label for="line" class="form-label">Sewing Line No.*</label>
                                    <select name="line" id="line" class="form-select"
                                       required="required">
                                       <option value="">Select</option>
                                       <?php
                                          foreach ($result_SewingLine as $key => $SewingLine) {
                                          ?>
                                       <option value="<?php echo $SewingLine['Code']; ?>"><?php echo $SewingLine['Code']; ?></option>
                                       <?php } ?>
                                    </select> 
                                </div>

                                
                                <div class="col-md-4">
                                    <label for="WorkOrder" class="form-label">Work Order</label>
                                    <input type="text" name="WorkOrder" id="WorkOrder" class="form-control" readonly>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="site" class="form-label">Site</label>
                                    <select name="site" id="site" class="form-select" readonly>
                                        <option value="">Select</option>
                                        <option value="LTD" <?php if($row_header['site']=='LTD'){echo "selected";} ?>>
                                            Lida Textile and Dyeing Limited</option>
                                        <option value="LFI" <?php if($row_header['site']=='LFI'){echo "selected";} ?>>
                                            Liz Fashion Industry Limited</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="building" class="form-label">Building</label>
                                    <select name="building" id="building" class="form-select" readonly>
                                        <option value="">Select</option>
                                        <option value="LTD01"
                                            <?php if($row_header['building']=='LTD01'){echo "selected";} ?>>Lida
                                            Production</option>
                                        <option value="Liz01"
                                            <?php if($row_header['building']=='Liz01'){echo "selected";} ?>>Liz-1
                                        </option>
                                        <option value="Liz02"
                                            <?php if($row_header['building']=='Liz02'){echo "selected";} ?>>Liz-2
                                        </option>
                                        <option value="Liz03"
                                            <?php if($row_header['building']=='Liz03'){echo "selected";} ?>>Liz-3
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="floor" class="form-label">Floor</label>
                                    <select name="floor" id="floor" class="form-select" readonly>
                                        <option value="">Select</option>
                                        <option value="002" <?php if($row_header['floor']=='002'){echo "selected";} ?>>
                                            1st</option>
                                        <option value="003" <?php if($row_header['floor']=='003'){echo "selected";} ?>>
                                            2nd</option>
                                        <option value="004" <?php if($row_header['floor']=='004'){echo "selected";} ?>>
                                            3rd</option>
                                        <option value="005" <?php if($row_header['floor']=='005'){echo "selected";} ?>>
                                            4th</option>
                                        <option value="006" <?php if($row_header['floor']=='006'){echo "selected";} ?>>
                                            5th</option>
                                        <option value="007" <?php if($row_header['floor']=='007'){echo "selected";} ?>>
                                            6th</option>
                                        <option value="001" <?php if($row_header['floor']=='001'){echo "selected";} ?>>
                                            Ground</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="Customer" class="form-label">Customer</label>
                                    <input type="text" name="Customer" id="Customer" class="form-control" readonly>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="Style" class="form-label">Style</label>
                                    <input type="text" name="Style" id="Style" class="form-control" readonly>
                                </div>
                                
                                
                            </div>
                            <!-- <button type="submit" class="btn btn-primary newLine me-2" id="searchBtn">Search</button> -->
                        </div>
                        <div id="formLinesContainer" class=" mt-3">
                            <div id="formLinesFieldset">
                                <div id="formLineslegend" class="mt-2">Line Information</div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary"
                                        id="openFormBtn">Add New Line</button>
                                    <!-- <button type="button" class="btn btn-outline-primary saveLine me-2">Save</button> -->
                                    <!-- <button type="button" class="btn btn-secondary removeLine me-2" disabled>Delete</button> -->
                                    <!-- <button type="button" class="btn btn-secondary copyAndNewLine">Copy &amp; New</button> -->
                                </div>
                                <div id="rightFormLines" style="display: none">
                                    <div id="dragbar"></div>
                                    <fieldset id="fieldset_linegroup1" class="border p-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="lg_linenumber" class="form-label">Line</label>
                                                <input type="text" name="linenumber" id="lg_linenumber"
                                                    class="form-control" value="" readonly="readonly">
                                            </div>


                                            <div class="col-md-4 mb-3">
                                                <label for="ework_id" class="form-label" hidden>Operation ID</label>
                                                <input type="text" name="ework_id" id="lg_ework_id"
                                                    class="form-control" value="" readonly="readonly" hidden>
                                            </div>


                                            <div class="col-md-4 mb-3">
                                                <label for="npttype" class="form-label">NPT Type*</label>
                                                <select name="npttype" id="npttype" class="form-select"
                                                   required="required">
                                                   <option >Select</option>
                                                   <option value="0" <?php if($row_header['npttype']=='0'){echo "selected";} ?>>
                                                        Planned</option>
                                                    <option value="1" <?php if($row_header['npttype']=='1'){echo "selected";} ?>>
                                                        Unplanned - From Record</option>
                                                    <option value="2" <?php if($row_header['npttype']=='2'){echo "selected";} ?>>
                                                        Unplanned - Manual</option>
                                                </select> 
                                            </div>


                                            

                                            


                                            <div class="col-md-4 mb-3" id="buttonContainer">

                                                

                                            </div>

                                            <div class="col-md-4 mb-3" id="buttonContainer2">


                                                

                                            </div>

                                            <div class="col-md-4 mb-3" id="buttonContainer3">


                                                

                                            </div>


                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Choose Steps</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" onclick="selectAll()">Select All</button>
                                                    <button type="button" class="btn btn-primary" onclick="unSelectAll()">Unselect All</button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div id="Step_Number"></div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>


                                            <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="dataModalLabel">Choose Lines</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-footer">
                                                 <!--    <button type="button" class="btn btn-primary" onclick="selectAll()">Check All</button>
                                                    <button type="button" class="btn btn-primary" onclick="unSelectAll()">Uncheck All</button> -->
                                                  </div>

                                                  <div class="modal-body">
                                                    <div id="styled_tablediv"></div>
                                                  </div>
                                                  
                                                </div>
                                              </div>
                                            </div>


                                            <script>
                                                function selectAll() {
                                                    // Get the form element containing checkboxes
                                                    var form = document.getElementById("Step_Number");

                                                    // Loop through each checkbox and set its checked property to true
                                                    var checkboxes = form.querySelectorAll('input[type="checkbox"]');
                                                    checkboxes.forEach(function(checkbox) {
                                                      checkbox.checked = true;
                                                    });
                                                  }

                                                function unSelectAll() {
                                                    // Get the form element containing checkboxes
                                                    var form = document.getElementById("Step_Number");

                                                    // Loop through each checkbox and set its checked property to true
                                                    var checkboxes = form.querySelectorAll('input[type="checkbox"]');
                                                    checkboxes.forEach(function(checkbox) {
                                                      checkbox.checked = false;
                                                    });
                                                  }



                                                  
                                            </script>


                                            <div class="col-md-4 mb-3">
                                                <label for="start_time" class="form-label">Start Time<span class="required">*</span></label>
                                                <input type="text" name="start_time" id="start_time" class="form-control datepicker"  onchange="calculateDuration()">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="end_time" class="form-label">End Time<span class="required">*</span></label>
                                                <input type="text" name="end_time" id="end_time" class="form-control datepicker" onchange="calculateDuration()" >
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="duration" class="form-label">Duration (in minutes)</label>
                                                <input type="text" name="duration" id="duration"
                                                    class="form-control" value="" readonly="readonly">
                                            </div>


                                            

                                            <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

                                            <div class="col-md-4 mb-3">
                                                <label for="reason" class="form-label">Reason*</label>
                                                <button class="btn btn-primary" onclick="showPopup()">Choose Reason</button>
                                                <!-- <select name="reason" id="reason" class="form-select" required="required">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($result_NPT_Reason as $key => $NPT_Reason) {
                                                    ?>
                                                    <option value="<?php echo $NPT_Reason['Description']; ?>"><?php echo $NPT_Reason['Description']; ?></option>
                                                    <?php } ?>
                                                </select> --> 
                                                <input type="text" name="reason" id="reason"
                                                    class="form-control" value="" readonly="readonly">
                                            </div>

    <div id="popup" class="popup" style="display:none;">
    <div class="popup-header">Input Problem (ইনপুট সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="print" name="input_problem" value="Print (Input Problem)" onclick="selectProblem(this)">
            <label for="print"><img src="/ie/npt-reasons/print.png" alt="Print" style="width: 50px; height: 50px;"> Print - প্রিন্ট</label>
        </div>
        <div>
            <input type="radio" id="store" name="input_problem" value="Store (Input Problem)" onclick="selectProblem(this)">
            <label for="store"><img src="/ie/npt-reasons/store.png" alt="Store" style="width: 50px; height: 50px;"> Store - স্টোর</label>
        </div>
        <div>
            <input type="radio" id="cutting" name="input_problem" value="Cutting (Input Problem)" onclick="selectProblem(this)">
            <label for="cutting"><img src="/ie/npt-reasons/cutting.png" alt="Cutting" style="width: 50px; height: 50px;"> Cutting - কাটিং</label>
        </div>
        <div>
            <input type="radio" id="sewing" name="input_problem" value="Sewing (Input Problem)" onclick="selectProblem(this)">
            <label for="sewing"><img src="/ie/npt-reasons/sewing.png" alt="Sewing" style="width: 50px; height: 50px;"> Sewing - সেলাই</label>
        </div>
        <div>
            <input type="radio" id="supplier" name="input_problem" value="Supplier (Input Problem)" onclick="selectProblem(this)">
            <label for="supplier"><img src="/ie/npt-reasons/supplier.png" alt="Supplier" style="width: 50px; height: 50px;"> Supplier - সরবরাহকারী</label>
        </div>
        <div>
            <input type="radio" id="auto_elastic" name="input_problem" value="Auto Elastic (Input Problem)" onclick="selectProblem(this)">
            <label for="auto_elastic"><img src="/ie/npt-reasons/auto-elastic.png" alt="Auto Elastic" style="width: 50px; height: 50px;"> Auto Elastic - অটো ইলাস্টিক</label>
        </div>
    </div>
    <div class="popup-header">Machine Problem (মেশিনের সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="breakdown" name="machine_problem" value="Breakdown (Machine Problem)" onclick="selectProblem(this)">
            <label for="breakdown"><img src="/ie/npt-reasons/breakdown.png" alt="Breakdown" style="width: 50px; height: 50px;"> Breakdown - ব্রেকডাউন</label>
        </div>
        <div>
            <input type="radio" id="setting_delay" name="machine_problem" value="Setting Delay (Machine Problem)" onclick="selectProblem(this)">
            <label for="setting_delay"><img src="/ie/npt-reasons/setting-delay.png" alt="Setting Delay" style="width: 50px; height: 50px;"> Setting Delay - সেটিং বিলম্ব</label>
        </div>
    </div>
    <div class="popup-header">Utility Problem (পাওয়ার সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="electricity" name="utility_problem" value="Electricity (Utility Problem)" onclick="selectProblem(this)">
            <label for="electricity"><img src="/ie/npt-reasons/electricity.png" alt="Electricity" style="width: 50px; height: 50px;"> Electricity - বিদ্যুৎ</label>
        </div>
        <div>
            <input type="radio" id="compressed_air" name="utility_problem" value="Compressed Air (Utility Problem)" onclick="selectProblem(this)">
            <label for="compressed_air"><img src="/ie/npt-reasons/compressed-air.png" alt="Compressed Air" style="width: 50px; height: 50px;"> Compressed Air - বায়ুর চাপ</label>
        </div>
    </div>
    <div class="popup-header">Other Problem (অন্যান্য সমস্যা)</div>
    <div class="popup-section">
        <div>
            <input type="radio" id="rework" name="other_problem" value="Rework (Other Problem)" onclick="selectProblem(this)">
            <label for="rework"><img src="/ie/npt-reasons/rework.png" alt="Rework" style="width: 50px; height: 50px;"> Rework - রিওয়ার্ক</label>
        </div>
        <div>
            <input type="radio" id="training_meeting" name="other_problem" value="Training Meeting (Other Problem)" onclick="selectProblem(this)">
            <label for="training_meeting"><img src="/ie/npt-reasons/training-meeting.png" alt="Training Meeting" style="width: 50px; height: 50px;"> Training/Meeting - প্রশিক্ষণ/মিটিং</label>
        </div>
        <div>
            <input type="radio" id="technical_delay" name="other_problem" value="Technical Delay (Other Problem)" onclick="selectProblem(this)">
            <label for="technical_delay"><img src="/ie/npt-reasons/technical-delay.png" alt="Technical Delay" style="width: 50px; height: 50px;"> Technical Delay - টেকনিক্যাল বিলম্ব</label>
        </div>
        <div>
            <input type="radio" id="work_balancing" name="other_problem" value="Work Balancing (Other Problem)" onclick="selectProblem(this)">
            <label for="work_balancing"><img src="/ie/npt-reasons/work-balancing.png" alt="Work Balancing" style="width: 50px; height: 50px;"> Work Balancing - কাজের ভারসাম্য</label>
        </div>
    </div>
    <!-- <div class="popup-footer">
        <button style="text-align:center;" type="button" onclick="submitForm()">Submit</button>
    </div> -->
</div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function selectProblem(radio) {
            document.getElementById('reason').value = radio.value;
            document.getElementById('popup').style.display = 'none';
        }

        // function submitForm() {
        //     var inputForm = document.querySelector('form');
        //     var selectedInputProblem = inputForm.input_problem.value;
        //     var selectedMachineProblem = inputForm.machine_problem.value;
        //     var selectedUtilityProblem = inputForm.utility_problem.value;
        //     var selectedOtherProblem = inputForm.other_problem.value;

        //     alert(selectedInputProblem);

        //     document.getElementById('popup').style.display = 'none';
        // }
    </script>


                                            <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


                                            <script>
                                                $(document).ready(function() {
                                                    $('#reason').select2({
                                                        placeholder: "",
                                                        allowClear: true,
                                                        width: '100%'
                                                    });
                                                });
                                            </script> -->

                                            

                                            <div class="col-md-4 mb-3">
                                                <label for="remarks" class="form-label">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control"
                                                    required></textarea>
                                            </div>
                                            
                                        </div>
                                        <button type="button" class="btn btn-primary"
                                            onclick="addToCart(); return false;">Add to line</button>
                                        <button type="button" class="btn btn-secondary"
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
                                                        <th class="StepNumber" required="required">Step Number<span class="required">*</span></th>
                                                        <th class="npttype" required="required">NPT Type<span class="required">*</span></th>
                                                        <!-- <th class="eworkid" required="required">Operation ID</th> -->
                                                        <th class="start_time" required="required">Start Time<span class="required">*</span></th>
                                                        <th class="end_time" required="required">End Time<span class="required">*</span></th>
                                                        <th class="duration">Duration (in minutes)</th>
                                                        <th class="reason" required="required">Reason<span class="required">*</span></th>
                                                        <th class="remarks">Remarks</th>
                                                        
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
                </div>
                </form>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer -->
    <!-- Edit Modal -->



        <?php include 'footer.php'; ?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <script>
        $(document).ready(function() {
            $('#docnumber').change(function() {
                var docNumber = $(this).val();
                // alert(docNumber);

                var postData = {
                    docNumber: docNumber
                };
                $.ajax({
                    url: 'get_npt_data.php', // PHP script to retrieve work order based on sewing line
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        $('#WorkOrder').val(response.WorkOrder);
                        $('#Style').val(response.Style);
                        $('#site').val(response.site);
                        $('#building').val(response.building);
                        $('#floor').val(response.floor);
                        $('#Customer').val(response.Customer);
                        $('#line').val(response.line);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });

         


          $(document).ready(function() {
                $('#docnumber').on('change', function() {
                    var docnumber = this.value;

                  // alert(docnumber);
                    $.ajax({
                        url: "get_step.php",
                        type: "POST",
                        data: {
                            docnumber: docnumber
                        },
                        cache: false,
                        success: function(result) {
                            $("#Step_Number").html(result);
                        }
                    });
                });
            });

    </script>
    <script>
    $(function() {
        $("#myDataTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["csv", "excel"]
            // "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#myDataTable_wrapper .col-md-6:eq(0)');

    });
    </script>
    <script>
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

        var lg_StepNumberValue = ""; // Initialize an empty string to store the concatenated values
        // var lg_StepNumberValue = document.getElementById("lg_StepNumber").value;


        // Get all checkboxes with name "StepNumber[]"
        var checkboxes = document.getElementsByName("StepNumber[]");

        // Loop through each checkbox
        for (var i = 0; i < checkboxes.length; i++) {
            // Check if the checkbox is checked
            if (checkboxes[i].checked) {
                // Concatenate the value of the checked checkbox to lg_StepNumberValue
                lg_StepNumberValue += checkboxes[i].value + ";\n";
            }
        }

        // Remove the trailing comma if there are selected values
        if (lg_StepNumberValue.length > 0) {
            lg_StepNumberValue = lg_StepNumberValue.slice(0, -2); // Remove the last comma
        }


        var docdateValue    = document.getElementById("docdate").value;
        var docnumberValue  = document.getElementById("docnumber").value;
        var WorkOrderValue  = document.getElementById("WorkOrder").value;
        var siteValue       = document.getElementById("site").value;
        var buildingValue   = document.getElementById("building").value;
        var floorValue      = document.getElementById("floor").value;
        var lineValue       = document.getElementById("line").value;
        var CustomerValue   = document.getElementById("Customer").value;
        var StyleValue      = document.getElementById("Style").value;
        var start_timeValue = document.getElementById("start_time").value;
        var end_timeValue   = document.getElementById("end_time").value;
        var durationValue   = document.getElementById("duration").value;
        var reasonValue     = document.getElementById("reason").value;
        var remarksValue    = document.getElementById("remarks").value;
        var npttypeValue    = document.getElementById("npttype").value;
        var eworkIDValue    = document.getElementById("lg_ework_id").value;
       

        // Check if all required fields are filled
        // if (!start_timeValue || !end_timeValue || !reasonValue) {
        //     alert("Please fill in all required fields");
        //     return;
        // } else 

        if (durationValue < 0){
            alert("End time should not be less than or equal to Start time!");
            return;
        } else if (durationValue == 0){
            alert("End time should not be equal to start time!");
            return;
        }

        // Create a cartItem object
        var cartItem = {
            StepNumber: lg_StepNumberValue,
            docdate: docdateValue,
            docnumber: docnumberValue,
            WorkOrder: WorkOrderValue,
            site: siteValue,
            building: buildingValue,
            floor: floorValue,
            line: lineValue,
            Customer: CustomerValue,
            Style: StyleValue,
            start_time: start_timeValue,
            end_time: end_timeValue,
            duration: durationValue,
            reason: reasonValue,
            remarks: remarksValue,
            npttype: npttypeValue,
            ework_id: eworkIDValue,
            type: 'add'
        };


        if(npttypeValue == 0){
            var npt = 'Planned';
        }else if(npttypeValue == 1){
            var npt = 'Unplanned - From Record';
        }else{
            var npt = 'Unplanned - Manual';
        }


        // Push the cartItem into the cartItems array
        cartItems.push(cartItem);

        // Create a table row for the added item
        var newRow = document.createElement("tr");
        newRow.innerHTML = `
          <td>${rowNumber}</td>
          <td>${lg_StepNumberValue}</td>
          <td>${npt}</td>
          <td>${start_timeValue}</td>
          <td>${end_timeValue}</td>
          <td>${durationValue}</td>
          <td>${reasonValue}</td>
          <td>${remarksValue}</td>

          <td>
              <button type="button" class="btn btn-secondary" onclick="removeFromCart(this)">Delete</button>
          </td>
      `;
          // <td>${eworkIDValue}</td>

        // Append the new row to the table body
        document.getElementById("cartItems").appendChild(newRow);

        // Increment the row number for the next entry
        rowNumber++;
    }

    // Function to make the AJAX request
    function sendFormDataToServer() {
        // Check if cartItems is not empty
        if (cartItems.length === 0) {
            alert("Please fill in all required fields.");
            return;
        }

        var saveData = $.ajax({
            type: 'POST',
            url: "npt_store.php",
            data: {
                cartItems: cartItems
            }, // Send an object with a property named "cartItems" containing the array
            dataType: "json", // Expect a JSON response
            success: function(response) {
                console.log(response); // Log the response for debugging

                if (response.success) {
                    window.location.href = `npt_edit.php?id=${response.nptnumber}`;
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + " " + error);
                console.error(xhr.responseText); // Log the full response for debugging
                alert("An error occurred while processing your request.");
            }
       
         });


    }

    function removeFromCart(button) {
        // Get the row containing the button
        var row = button.closest("tr");
        

        row.remove();
    }


    function sendToListButton(button) {
        window.location.href = "npt_list.php";
    }

    function saveChanges() {
        // Find the editing row based on the 'editing' class
        var editingRow = document.querySelector("tr.fieldset_linegroup1");

        if (editingRow) {
            // Update the row data
            editingRow.cells[0].innerText = document.getElementById("lg_linenumber1").value;
            editingRow.cells[1].innerText = document.getElementById("lg_StepNumber1").value;
            editingRow.cells[2].innerText = document.getElementById("start_timeValue").value;
            editingRow.cells[3].innerText = document.getElementById("end_timeValue").value;
            editingRow.cells[4].innerText = document.getElementById("durationValue").value;
            editingRow.cells[5].innerText = document.getElementById("reasonValue").value;
            editingRow.cells[6].innerText = document.getElementById("remarksValue").value;


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
        var start_timeValue    = row.cells[2].innerText;
        var end_timeValue      = row.cells[3].innerText;
        var durationValue      = row.cells[4].innerText;
        var reasonValue        = row.cells[5].innerText;
        var remarksValue       = row.cells[6].innerText;
        

        document.getElementById("lg_linenumber1").value   = lg_linenumberValue;
        document.getElementById("lg_StepNumber1").value   = lg_StepNumberValue;
        document.getElementById("start_timeValue1").value = start_timeValue;
        document.getElementById("end_timeValue1").value   = end_timeValue;
        document.getElementById("durationValue1").value   = durationValue;
        document.getElementById("reasonValue1").value     = reasonValue;
        document.getElementById("remarksValue1").value    = remarksValue;

    }


    $(document).ready(function() {
        $('#start_time').timepicker({
            format: 'HH:mm', // Set the desired datetime format
            autoclose: true, // Close the picker when a date/time is selected
            todayBtn: true, // Show a "Today" button to quickly select the current date/time
            todayHighlight: true, // Highlight today's date in the timepicker  
            showMeridian: false, // Use 24-hour format
            change: calculateDuration // Call calculateDuration function on change of start_time or end_time
        });
        $('#end_time').timepicker({
            format: 'HH:mm', // Set the desired datetime format
            autoclose: true, // Close the picker when a date/time is selected
            todayBtn: true, // Show a "Today" button to quickly select the current date/time
            todayHighlight: true, // Highlight today's date in the timepicker    
            showMeridian: false, // Use 24-hour format
            change: calculateDuration // Call calculateDuration function on change of start_time or end_time

        });
    });

    function calculateDuration() {
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();

        // Parse the time strings to moment objects
        var startTimeObj = moment(startTime, 'HH:mm');
        var endTimeObj = moment(endTime, 'HH:mm');

        // Calculate the duration in minutes
        var durationMinutes = endTimeObj.diff(startTimeObj, 'minutes');

        if(durationMinutes < 0){
            $('#duration').val(durationMinutes);
            alert("End time should not be less than or equal to Start time!");
        }else if(durationMinutes == 0){
            $('#duration').val(durationMinutes);
            alert("End time should not be equal to start time!");
        }else{
            // Set the value of the duration input
            $('#duration').val(durationMinutes);
        }
    }



    document.getElementById("site").disabled = true;
    document.getElementById("building").disabled = true;
    document.getElementById("floor").disabled = true;
    document.getElementById("line").disabled = true;



    </script>
    <script>
    $(document).ready(function(){
        $("#searchBtn").click(function(){
            var docdate = $("#docdate").val();
            var docnumber = $("#docnumber").val();

            // Update field values
            $('#docdate').val(docdate);
            $('#docnumber').val(docnumber);

            $.ajax({
                url: 'fetch_documents.php',
                type: 'POST',
                data: { docdate: docdate, docnumber: docnumber },
                success: function(response){
                    $("#fieldset_lineinformation").html(response);
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formERP").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get field values
            var docdate   = document.getElementById("docdate").value;
            var docnumber = document.getElementById("docnumber").value;
            var line      = document.getElementById("line").value;
            var workOrder = document.getElementById("WorkOrder").value;
            var site      = document.getElementById("site").value;
            var building  = document.getElementById("building").value;
            var floor     = document.getElementById("floor").value;
            var customer  = document.getElementById("Customer").value;
            var style     = document.getElementById("Style").value;

        });
    });

    document.getElementById('npttype').addEventListener('change', function() {
        // const buttonContainer = document.getElementById('buttonContainer2');
        if (this.value === '1') {
            // Create new button element

            var docdate = $("#docdate").val();
            var docnumber = $("#docnumber").val();

            const newButton = document.createElement('button');
            newButton.type = 'button';
            newButton.className = 'btn btn-primary';
            newButton.id = 'searchBtn';
            newButton.setAttribute('data-bs-toggle', 'modal');
            newButton.setAttribute('data-bs-target', '#dataModal');
            newButton.setAttribute('docdate', docdate);
            newButton.setAttribute('docnumber', docnumber);
            newButton.textContent = 'Choose Time Slot';

            // Replace the existing button with the new button
            buttonContainer2.innerHTML = '';
            buttonContainer2.appendChild(newButton);

            buttonContainer.innerHTML = '';




            $.ajax({
                url: 'fetch_documents.php',
                type: 'POST',
                 data: { docdate: docdate, docnumber: docnumber },


                 // data: dataString,
                success: function(response){
                    $("#styled_tablediv").html(response);
                }
            });


        } else{
            // Replace the existing button with the new button
            const newButton = document.createElement('button');
            newButton.type = 'button';
            newButton.className = 'btn btn-primary';
            newButton.id = 'chooseButton';
            newButton.setAttribute('data-bs-toggle', 'modal');
            newButton.setAttribute('data-bs-target', '#exampleModal');
            newButton.textContent = 'Choose Steps';

            // Replace the existing button with the new button
            buttonContainer.innerHTML = '';
            buttonContainer.appendChild(newButton);


            buttonContainer2.innerHTML = '';


        }
    });


    </script>
</body>

</html>