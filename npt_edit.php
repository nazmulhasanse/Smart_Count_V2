<?php
   session_start();
   if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
   }
   include 'header.php';
   
   $doc_number = htmlspecialchars($_GET['id']);
   $new_doc_number = htmlspecialchars($_GET['id']);

   $sql_header = "SELECT * FROM `ework_daily_npt` where nptnumber = '$new_doc_number' limit 1";
   $result_header = $conn->query($sql_header);
   $row_header = $result_header->fetch_assoc();

   $sql = "SELECT SUM(duration) AS totalPlannedDuration FROM `ework_daily_npt` where nptnumber = '$new_doc_number' AND npttype = '0'";
   $result = $conn->query($sql);
   $totalPlannedDuration = $result->fetch_assoc()['totalPlannedDuration'];

   $sql = "SELECT SUM(duration) AS totalUnplannedDuration FROM `ework_daily_npt` where nptnumber = '$new_doc_number' AND npttype = '1'";
   $result = $conn->query($sql);
   $totalUnplannedDuration = $result->fetch_assoc()['totalUnplannedDuration'];

   $query_SewingLine = "SELECT * FROM ework_mrd_library WHERE LibraryName='SewingLine'";
   $result_SewingLine = $conn->query($query_SewingLine);

   $query_NPT_Reason = "SELECT * FROM ework_mrd_library WHERE LibraryName='NPT'";
   $result_NPT_Reason = $conn->query($query_NPT_Reason);

   $query_DocNumber = "SELECT DISTINCT(docnumber) AS DocNumber FROM `ework_sales_order` WHERE docstatus = '1' ORDER BY docnumber DESC;";
   $result_DocNumber = $conn->query($query_DocNumber);







   ?>

<!-- CSS Styles -->
<style>
    .styled-table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        font-family: Arial, sans-serif;
    }
    .styled-table th, .styled-table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .styled-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .styled-table th {
        background-color: #343a40;
        color: white;
    }

    .modalx {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
            padding-bottom: 60px;
        }

        /* Modal Content/Box */
        .modal-contentx {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 100%; /* Could be more or less, depending on screen size */
            height: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Maximum width */
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 10px;
        }

        /* Close button */
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

        /* Input field styling */
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Submit button styling */
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

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
                                <?php if($row_header['status']=='' && ($_SESSION['cardnumber']=='LH1044' || $_SESSION['cardnumber']=='LH1508')){ ?>
                                    <button type="button" class="btn btn-primary me-2" onclick="approveThisDocument('<?php echo $_GET['id']; ?>')">Approve</button>
                                <?php }elseif($row_header['status']=='1' && ($_SESSION['cardnumber']=='LH1044'  || $_SESSION['cardnumber']=='LH1508')){ ?>
                                    <button type="button" class="btn btn-primary me-2" onclick="disapproveThisDocument('<?php echo $_GET['id']; ?>')">Disapprove</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="border p-3">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="docdate" class="form-label">Date<span class="required">*</span></label>
                                    <input type="text" name="docdate"  value="<?php echo $row_header['docdate']; ?>"
                                        id="docdate" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="DocNumber" class="form-label">Doc Number<span
                                            class="required">*</span></label>
                                    <input type="text" name="DocNumber" value="<?php echo $row_header['docnumber']; ?>"
                                        id="DocNumber" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Doc Status</label>
                                    <input type="text" name="status"
                                        value="<?php if($row_header['status']==''){echo "Not Approved";}elseif ($row_header['status']=='1'){echo "Approved";}?>"
                                        id="status" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="WorkOrder" class="form-label">Work Order<span
                                            class="required">*</span></label>
                                    <input type="text" name="WorkOrder" value="<?php echo $row_header['WorkOrder']; ?>"
                                        id="WorkOrder" class="form-control" readonly>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="site" class="form-label">Site<span class="readonly">*</span></label>
                                    <select name="site" id="site" class="form-select" readonly>
                                        <option value="">Select</option>
                                        <option value="LTD" <?php if($row_header['site']=='LTD'){echo "selected";} ?>>
                                            Lida Textile and Dyeing Limited</option>
                                        <option value="LFI" <?php if($row_header['site']=='LFI'){echo "selected";} ?>>
                                            Liz Fashion Industry Limited</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="building" class="form-label">Building<span
                                            class="required">*</span></label>
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
                                    <label for="floor" class="form-label">Floor<span class="required">*</span></label>
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
                                    <label for="line" class="form-label">Sewing Line No.<span
                                            class="required">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="line" id="line" class="form-control"
                                            value="<?php echo $row_header['line']; ?>" readonly>
                                        
                                    </div>
                                </div>
                                <!-- Bootstrap 5 Modal -->
                                
                                

                                
                                <div class="col-md-4">
                                    <label for="Customer" class="form-label">Customer<span
                                            class="required">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="Customer"
                                            value="<?php echo $row_header['Customer']; ?>" id="Customer"
                                            class="form-control" readonly>
                                        <!-- <button type="button" class="btn btn-primary"
                                            title="Click this for look up data" onclick="showModalCustomer()"><i
                                                class="material-icons">search</i></button> -->
                                    </div>
                                </div>
                                <!-- Bootstrap 5 Modal -->
                                
                                <div class="col-md-4">
                                    <label for="Style" class="form-label">Style<span class="required">*</span></label>
                                    <input type="text" name="Style" value="<?php echo $row_header['Style']; ?>"
                                        id="Style" class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="totalPlannedDuration" class="form-label">Total Planned NPT (in minutes)</label>
                                    <input type="text" name="totalPlannedDuration" value="<?php echo $totalPlannedDuration; ?>"
                                        id="totalPlannedDuration" class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="totalUnplannedDuration" class="form-label">Total Unplanned NPT (in minutes)</label>
                                    <input type="text" name="totalUnplannedDuration" value="<?php echo $totalUnplannedDuration; ?>"
                                        id="totalUnplannedDuration" class="form-control" readonly>
                                </div>


                            </div>
                            
                        </div>
                        <div id="formLinesContainer" class=" mt-3">
                            <div id="formLinesFieldset">
                                <div id="formLineslegend" class="mt-2">Line Information</div>

                                <?php if($row_header['status']=='' && ($_SESSION['cardnumber']=='LH1044' || $_SESSION['cardnumber']=='LH1508')){ ?>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary newLine me-2" id="openFormBtn">Add New
                                        Line</button>
                                    <!-- <button type="button" class="btn btn-primary saveLine me-2">Save</button> -->
                                    
                                </div>
                                <?php }?>
                                <div id="rightFormLines" style="display: none">
                                    <div id="dragbar"></div>
                                    <fieldset id="fieldset_linegroup" class="border p-3">
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
                                                   <option value="">Select</option>
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
                                                    <button type="button" class="btn btn-primary" onclick="selectAll()">Check All</button>
                                                    <button type="button" class="btn btn-primary" onclick="unSelectAll()">Uncheck All</button>
                                                  </div>

                                                  <div class="modal-body">
                                                    <div id="Step_Number"></div>
                                                  </div>
                                                  
                                                </div>
                                              </div>
                                            </div>


                                            


                                            <!-- Modal for Data Table -->
                               

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
                                                <input type="text" name="start_time" id="start_time" class="form-control datepicker"  onchange="calculateDuration()" >
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="end_time" class="form-label">End Time<span class="required">*</span></label>
                                                <input type="text" name="end_time" id="end_time" class="form-control datepicker" onchange="calculateDuration()" >
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="duration" class="form-label">Duration (in Minutes)</label>
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
                                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                                            </div>
                                            
                                        
                                    </fieldset>
                                    <button type="button" class="btn btn-primary"
                                            onclick="addToCart(); return false;">Save</button>
                                        <button type="button" class="btn btn-secondary"
                                            id="closefrom">Close</button>
                                </div>
                                <div class="clearfix"></div>
                                <div id="leftFormLines">
                                    <div id="formLines">
                                        <div id="lineinfo">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="linenumber">Line</th>
                                                        <th class="StepNumber" required="required">Step Details<span
                                                                class="required">*</span></th>
                                                        <th class="NptType" required="required">NPT Type<span
                                                                class="required">*</span></th>
                                                        <!-- <th class="eworkid">Operation ID</th> -->
                                                        <th class="start_time" required="required">Start Time<span class="required">*</span></th>
                                                        <th class="end_time" required="required">End Time<span class="required">*</span></th>
                                                        <th class="duration">Duration (in Minutes)</th>
                                                        <th class="reason" required="required">Reason<span class="required">*</span></th>
                                                        <th class="remarks">Remarks</th>
                                                        <th class="actionplan">Action Plan</th>
                                                        
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cartItems" class="editing">
                                                    <?php
                                          $doc_number = htmlspecialchars($_GET['id']);
                           
                                          $sql = "SELECT * FROM `ework_daily_npt` where nptnumber = '$new_doc_number' ORDER BY npttype, linenumber ASC";
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
                                                        <td><?php echo $value['linenumber']; ?></td>
                                                        <td><?php echo nl2br($value['StepNumber']); ?></td>
                                                        <td><?php 
                                                                if($value['npttype'] == 0){
                                                                    echo "Planned";
                                                                } else if($value['npttype'] == 1){
                                                                    echo "Unplanned - From Record";
                                                                } else if($value['npttype'] == 2){
                                                                    echo "Unplanned - Manual";
                                                                }
                                                        ?></td>
                                                        <!-- <td><?php echo $value['ework_id']; ?></td> -->
                                                        <td><?php echo $value['start_time']; ?></td>
                                                        <td><?php echo $value['end_time']; ?></td>
                                                        <td><?php echo $value['duration']; ?></td>
                                                        <td><?php echo $value['reason']; ?></td>
                                                        <td><?php echo $value['remarks']; ?></td>
                                                        <td><?php echo $value['action_plan']; ?></td>



                                                        <td>
                                                            <?php if($row_header['status']=='' && ($_SESSION['cardnumber']=='LH1044' || $_SESSION['cardnumber']=='LH1508')){ ?>
                                                            <button type="button" class="updateButton btn btn-primary" value="<?php echo $value['idlines']; ?>">Add Action Plan</button>
                                                            <button type="button" class="btn btn-secondary" onclick="confirmDelete('<?php echo $value['idlines']; ?>')">Delete</button>
                                                            <?php }?>

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
                </form>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer -->

    <div id="actionPlanModal" class="modalx">
    <!-- Modal content -->
        <div class="modal-contentx">
            <span class="close">&times;</span>
            <label for="action_plan">Please mention your action plan:</label><br>
            <textarea id="action_plan" class="input-field" required="required"></textarea>
            <label for="applyToAll" class="form-label">Also apply to other lines having same reason.</label>
            <select name="applyToAll" id="applyToAll" class="form-select"
               required="required">
               <option>Select</option>
               <option value="0" <?php if($row_header['applyToAll']=='0'){echo "selected";} ?>>
                    No</option>
                <option value="1" <?php if($row_header['applyToAll']=='1'){echo "selected";} ?>>
                    Yes</option>
            </select>
            <br>
            <button  class="submit-btn">Submit</button>
        </div>
    </div>


    <?php include 'footer.php'; ?>


<script>
    
        // Get the modal
        var modal = document.getElementById("actionPlanModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        function openModal(idlines) {
            modal.style.display = "block";
            // Set a data attribute on the modal to store the idlines
            modal.setAttribute('data-idlines', idlines);
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Attach event listeners to all update buttons
        document.querySelectorAll('.updateButton').forEach(button => {
            button.addEventListener('click', function() {
                // Optionally, you can pass the button value (idlines) to the modal
                var idlines = this.value;
                openModal(idlines);
            });
        });


        function updateActionPlan() {
        // Prompt the user for an action plan
        // var actionPlan = prompt("Please enter your action plan:");
        // var idlines = button.parentNode.parentNode.querySelector('#updateButton').value;
        var idlines = modal.getAttribute('data-idlines');
        var actionPlan = document.getElementById("action_plan").value;
        var applyToAll = document.getElementById("applyToAll").value;

        // alert(applyToAll);


        // If user entered a value, submit the form with this value
        if (actionPlan !== null && actionPlan !== "") {
            var updateActionPlan = {

                idlines: idlines,
                actionPlan: actionPlan,
                applyToAll: applyToAll,
                type: 'updateActionPlan'
                // Add other properties as needed
            };

            cartItems.push(updateActionPlan);
            var saveData = $.ajax({
                type: 'POST',
                url: "npt_edit_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Action plan successfully updated");
                    // window.location.href = "npt_list.php";
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }
    }


    // Attach the submit event listener to the modal's submit button
    document.querySelector('.submit-btn').addEventListener('click', updateActionPlan);

        
    

    // function calculateDuration() {
    //     var startTime = new Date(document.getElementById('start_time1').value);
    //     var endTime = new Date(document.getElementById('end_time1').value);
    //     var duration = (endTime - startTime) / (1000 * 60); // Calculate duration in minutes
    //     document.getElementById('duration1').value = duration;
    // }

    function saveChanges() {

        var editingRow = document.querySelector("tr.fieldset_linegroup1");

        if (editingRow) {
            // Update the row data
            editingRow.cells[0].innerText = document.getElementById("lg_linenumber1").value;
            editingRow.cells[1].innerText = document.getElementById("lg_StepNumber1").value;


            // Remove the 'editing' class
            editingRow.classList.remove("editing");

            // Close the edit modal
            $('#editModal').hide();

            $("#rightFormLines").hide();

        } else {
            console.error("Row not found.");
        }
    }
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Disable the select input
        document.getElementById("site").disabled = true;
        document.getElementById("building").disabled = true;
        document.getElementById("floor").disabled = true;
    </script>
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
    // if (Docstatus === '1') {
    //     disableAllFields();
    // }


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

        // Get the URL parameters
        var urlParams = new URLSearchParams(window.location.search);

        // Retrieve specific parameters
        var nptnumberValue = urlParams.get('id');

        var docdateValue    = document.getElementById("docdate").value;
        var docnumberValue  = document.getElementById("DocNumber").value;
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
        //     alert("Please fill in all required fields!");
        //     return;
        // } else 

        if (durationValue < 0){
            alert("End time should not be less than or equal to Start time!");
            return;
        } else if (durationValue == 0){
            alert("End time should not be equal to Start time!");
            return;
        }

        // Create a cartItem object
        var cartItem = {
            nptnumber: nptnumberValue,
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
            type: 'addline'
        };

        // Push the cartItem into the cartItems array
        cartItems.push(cartItem);
        var saveData = $.ajax({
            type: 'POST',
            url: "npt_edit_store.php",
            data: {
                cartItems: cartItems
            },
            dataType: "text",
            success: function(data) {
                // alert("Line Successfully Inserted");
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
            }
        });

    }



    function removeFromCart(button) {
        // Remove the corresponding row from the table
        var row = button.closest("tr");
        row.remove();
    }

    

    function editCartItem(button) {
        // Get the corresponding row
        var row = button.closest("tr");

        // Populate the modal fields with existing data
        var lg_linenumberValue = row.cells[0].innerText;
        var lg_StepNumberValue = row.cells[1].innerText;
        

        document.getElementById("lg_linenumber1").value = lg_linenumberValue;
        document.getElementById("lg_StepNumber1").value = lg_StepNumberValue;
        


    }

    function updateCartItem(idlines) {
        // Fetch other necessary data
        var updatedItem = {

            lg_StepNumber: document.getElementById('lg_StepNumber_' + idlines).value,
            
            idlines: idlines,
            type: 'edit'
            // Add other properties as needed
        };


        // Check if all required fields are filled
        if (!lg_StepNumber ) {
            alert("Please fill in all required fields");
            return;
        }
        cartItems.push(updatedItem);
        var saveData = $.ajax({
            type: 'POST',
            url: "npt_edit_store.php",
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
        var confirmDelete = confirm("Do you really want to delete this line?");

        if (confirmDelete) {
            // User clicked OK, proceed with the delete action
            removeFromCart(idlines);
        } else {
            // User clicked Cancel, do nothing or provide feedback
        }
    }

    

    function approveThisDocument(nptnumber) {

        if(confirm("Do you want to approve this " +nptnumber+ " document?")){
            var updateStatus = {

                nptnumber: nptnumber,
                type: 'updateStatus'
                // Add other properties as needed
            };

            cartItems.push(updateStatus);
            var saveData = $.ajax({
                type: 'POST',
                url: "npt_edit_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Status Successfully Updated");
                    // window.location.href = "npt_list.php";
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }
    }

    function disapproveThisDocument(nptnumber) {

        if(confirm("Do you want to disapprove this " +nptnumber+ " document?")){
            var disapproveStatus = {

                nptnumber: nptnumber,
                type: 'disapproveStatus'
                // Add other properties as needed
            };

            cartItems.push(disapproveStatus);
            var saveData = $.ajax({
                type: 'POST',
                url: "npt_edit_store.php",
                data: {
                    cartItems: cartItems
                }, // Send an object with a property named "cartItems" containing the array
                dataType: "text",
                success: function(data) {

                    // alert("Status Successfully Updated");
                    // window.location.href = "npt_list.php";
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });
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

        cartItems.push(updatedItem);
        var saveData = $.ajax({
            type: 'POST',
            url: "npt_edit_store.php",
            data: {
                cartItems: cartItems
            }, // Send an object with a property named "cartItems" containing the array
            dataType: "text",
            success: function(data) {

                // alert("Delete Successfully Done");
                // window.location.href = "npt_list.php";
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
            }
        });
    }


    $(document).ready(function() {
        $('#start_time').timepicker({
            format: 'hh:ii:ss', // Set the desired datetime format
            autoclose: true, // Close the picker when a date/time is selected
            todayBtn: true, // Show a "Today" button to quickly select the current date/time
            todayHighlight: true, // Highlight today's date in the timepicker  
            change: calculateDuration // Call calculateDuration function on change of start_time or end_time
        });
        $('#end_time').timepicker({
            format: 'hh:ii:ss', // Set the desired datetime format
            autoclose: true, // Close the picker when a date/time is selected
            todayBtn: true, // Show a "Today" button to quickly select the current date/time
            todayHighlight: true, // Highlight today's date in the timepicker    
            change: calculateDuration // Call calculateDuration function on change of start_time or end_time

        });
    });

    function calculateDuration() {
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();

        // Parse the time strings to moment objects
        var startTimeObj = moment(startTime, 'HH:mm:ss');
        var endTimeObj = moment(endTime, 'HH:mm:ss');

        // Calculate the duration in minutes
        var durationMinutes = endTimeObj.diff(startTimeObj, 'minutes');

        if(durationMinutes < 0){
            $('#duration').val(durationMinutes);
            alert("End time is less than or equel Start time!");
        }else if(durationMinutes == 0){
            $('#duration').val(durationMinutes);
            alert("End time is equel to start time!");
        }else{
            // Set the value of the duration input
            $('#duration').val(durationMinutes);
        }
    }


    $(document).ready(function() {
        var docnumber = $('#DocNumber').val();

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


    </script>
    <script>
  

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("formERP").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get field values
            var docdate   = document.getElementById("docdate").value;
            var docnumber = document.getElementById("DocNumber").value;
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
            var docnumber = $("#DocNumber").val();

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


        } else {
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