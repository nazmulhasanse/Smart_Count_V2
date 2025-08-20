<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


date_default_timezone_set('Asia/Dhaka'); // Set your timezone, e.g., 'Asia/Kolkata'

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Get the current hour
$currentHour = date('G');

// Greeting message based on the time of the day
if ($currentHour >= 5 && $currentHour < 12) {
    $greeting = 'Good morning';
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = 'Good afternoon';
} else {
    $greeting = 'Good evening';
}

?>
<?php include('header.php'); ?>

<head>
    <style>
        #chartContainer3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>



<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include('navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include('sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <!-- <h1 class="m-0">Dashboard</h1> -->
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <!-- <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li> -->
                                <!-- <li class="breadcrumb-item active">Dashboard</li> -->
                            </ol>
                        </div><!-- /.col -->

                        <br>

                        <!-- <div>
                            <h1>Welcome to the Smart Count!</h1>
                            <br>
                            <p><?php echo $greeting; ?>, the current date and time are: <?php echo $currentDateTime; ?>
                            </p>
                        </div> -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->


<script type="text/javascript">
    setTimeout(function() {
      location.reload();
    }, 60000); // 60,000 milliseconds = 60 seconds = 1 minute

</script>

        <?php 

            //for line board
            $line = $_GET['line'];

            $Date        = date('Y-m-d');
            $sql         = "SELECT docnumber FROM eworker_operation WHERE  SewingLine = '$line' ORDER BY ework_id DESC LIMIT 1;";
            $queryResult = $conn->query($sql);
            $row         = $queryResult->fetch_assoc();
            $docnumber   =  $row['docnumber'];

            // echo $docnumber;

                            $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(o.Qty) AS HourlyProductionQty
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                o.DateTime LIKE '%$Date%'
                    AND o.qty_type IN ('pass', 'fail', 'reject', 'rework-pass', 'rework-fail')
                    AND w.Position = '2'
                    AND o.docnumber = '$docnumber'
                    AND o.SewingLine = '$line'
            GROUP BY DATE, TIME
            ORDER BY TIME ASC";
            // echo $sql;
    $data = "";
    $data2 = "";
    $data3 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $PrimeTime = $row['TIME'];
      $Timex     = $row['TIME'];
      if($Timex == '12'){
        $Timex= '12 PM';
      }else if($Timex < '12'){
        $Timex = $Timex . ' AM';
      }else if($Timex > '12'){
        $Timex = ($Timex - 12) . ' PM';
      }

      $Timex = json_encode($Timex);
      

      $HourlyProductionQty = $row['HourlyProductionQty'];
      if($HourlyProductionQty == '') $HourlyProductionQty = '0';


      $data3 .=  "{ label: $Timex, y: $HourlyProductionQty},";





          $sql2 = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(Qty) AS FinishedGoodsQty
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                o.DateTime LIKE '%$Date%'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND w.Position = '2'
                    AND o.docnumber = '$docnumber'
                    AND o.SewingLine = '$line'
                    AND SUBSTRING(o.DateTime, 12, 2) = '$PrimeTime'
            GROUP BY DATE, TIME 
            ORDER BY TIME ASC";

            // echo $sql2;

            


    $queryResult2 = $conn->query($sql2);


    $row2 = $queryResult2->fetch_assoc();
      

      $FinishedGoodsQty = $row2['FinishedGoodsQty'];
      if($FinishedGoodsQty == '') $FinishedGoodsQty = '0';


      $data .=  "{ label: $Timex, y: $FinishedGoodsQty},";

    



    $sql3 = "SELECT 
                TotalSMV AS SMV
            FROM
                `ework_target_efficiency`
            WHERE
                Docnumber = '$docnumber'
                AND Date = '$Date'";

    $queryResult3 = $conn->query($sql3);
    $row3 = $queryResult3->fetch_assoc();
    $SMV =  $row3['SMV'];

    if ($row3['SMV'] != null) {
        $SMV = $row3['SMV'];
        // Process $SMV or do something with it
    } else {
        // Handle the case where SMV is not set
        $SMV = '1';
    }



    $sql4 = "SELECT Operator, Helper FROM `ework_target_efficiency` WHERE Date = '$Date' AND Docnumber = '$docnumber'";
    $queryResult4 = $conn->query($sql4);
    $row4 = $queryResult4->fetch_assoc();
    $Operator =  $row4['Operator'];
    $Helper =  $row4['Helper'];
    
    $sql5 = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(Qty) AS FinishedGoodsQty
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                o.DateTime LIKE '%$Date%'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND w.Position = '2'
                    AND o.docnumber = '$docnumber'
                    AND o.SewingLine = '$line'
                    AND SUBSTRING(o.DateTime, 12, 2) = '$PrimeTime'
            GROUP BY DATE, TIME
            ORDER BY TIME ASC";


    $queryResult5 = $conn->query($sql5);

    
      $row5 = $queryResult5->fetch_assoc();
      
      $Efficiency = $row5['FinishedGoodsQty'] * $SMV / (($Operator + $Helper) * 60) * 100;

      $Efficiency = number_format($Efficiency,2);

      if (!is_finite($Efficiency)) {
            // Handle the infinity case
            $Efficiency = 0;
      }

      $data2 .=  "{ label: $Timex, y: $Efficiency},";

    }



    //for color & size

    $sql = "SELECT o.color, o.size, SUM(o.Qty) AS Qty FROM `eworker_operation` o LEFT JOIN `ework_workers` w ON o.cardnumber = w.cardnumber WHERE w.Position = '2' AND o.color != '' AND o.size != '' AND SUBSTRING(o.`DateTime`, 1, 10) = '$Date' AND o.docnumber = '$docnumber' GROUP BY o.color, o.size ORDER BY o.size ASC";

    // echo $sql;
        
    $data4 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $color =  $row['color'];
      $size =  $row['size'];
      $color_size = $color . ',' . $size;
      $Qty  = $row['Qty'];
      
      $data4 .= "{ label: '$color_size', y: $Qty},";

    }

    //for monthly quality status


    $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 7) AS `Month`, SUM(`o`.`Qty`) AS `MonthlyProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` NOT IN ('rework-pass', 'rework-fail', 'rework-reject') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) AND o.docnumber = '$docnumber'";

    // echo $sql;
        
    $data5 = "";

    $queryResult          = $conn->query($sql);
    $row                  = $queryResult->fetch_assoc();
    $Month                = $row['Month'];
    $MonthlyProductionQty = $row['MonthlyProductionQty'];
    $data5                .= "{ label: 'Monthly Production Qty', y: $MonthlyProductionQty},";

    $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 7) AS `Month`, SUM(`o`.`Qty`) AS `MonthlyPassProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('pass', 'rework-pass') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) AND o.docnumber = '$docnumber'";

    // echo $sql;
        
    $data6 = "";

    $queryResult              = $conn->query($sql);
    $row                      = $queryResult->fetch_assoc();
    $Month                    =  $row['Month'];
    $MonthlyPassProductionQty = $row['MonthlyPassProductionQty'];
    $MonthlyPassPercentage    = number_format(($MonthlyPassProductionQty / $MonthlyProductionQty) * 100,2);

    $data6                    .= "{ label: 'Pass', y: $MonthlyPassPercentage, color: 'green'},";

    $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 7) AS `Month`, SUM(`o`.`Qty`) AS `MonthlyFailProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('reject', 'rework-reject') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) AND o.docnumber = '$docnumber'";

    // print_r($sql);
        
    $data7 = "";
    $data7s = "";

    $queryResult              = $conn->query($sql);
    $row                      = $queryResult->fetch_assoc();
    $Month                    =  $row['Month'];
    $MonthlyFailProductionQty = $row['MonthlyFailProductionQty'];
    $MonthlyFailPercentage    = number_format(($MonthlyFailProductionQty / $MonthlyProductionQty) * 100,2);
    $data7                    .= "{ label: 'Reject', y: $MonthlyFailPercentage, color: 'red'},";

    $MonthlyReworkProductionQty = $MonthlyProductionQty - $MonthlyPassProductionQty - $MonthlyFailProductionQty;
    $MonthlyReworkPercentage    = number_format(($MonthlyReworkProductionQty / $MonthlyProductionQty) * 100,2);
    $data7s                    .= "{ label: 'Rework', y: $MonthlyReworkPercentage, color: 'orange'},";





    // $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('pass', 'fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10) ORDER BY `Day` ASC";

    // // echo $sql;
        
    // $data5 = "";

    // $queryResult = $conn->query($sql);

    // while ($row = $queryResult->fetch_assoc()) {
      
      
    //   $Day                   =  $row['Day'];
    //   $DailyProductionQty    = $row['DailyProductionQty'];
    //   if($DailyProductionQty == '') $DailyProductionQty = '0';
      
    //   $data5 .= "{ label: '$Day', y: $DailyProductionQty},";

    // }

    // // echo $data;

    // $sql2 = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionFailQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10)";

    // // echo $sql;
        
    // $data6 = "";

    // $queryResult2 = $conn->query($sql2);

    // while ($row2 = $queryResult2->fetch_assoc()) {
      
      
    //   $Day                       =  $row2['Day'];
    //   $DailyProductionFailQty    = $row2['DailyProductionFailQty'];
    //   if($DailyProductionFailQty == '') $DailyProductionFailQty = '0';
      
    //   $data6 .= "{ label: '$Day', y: $DailyProductionFailQty},";



    // }



    // $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('pass', 'fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 7) = SUBSTRING('$Date', 1, 7) GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10)";

    // $data7 = "";

    // $queryResult = $conn->query($sql);

    // while ($row = $queryResult->fetch_assoc()) {
      
      
    //   $Day                =  $row['Day'];
    //   $DailyProductionQty = $row['DailyProductionQty'];
    //   if($DailyProductionQty == '') $DailyProductionQty = '0';


    //     $sql2 = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionFailQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 10) = '$Day' GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10) LIMIT 1";

    //     $queryResult2 = $conn->query($sql2);
    //     $row2 = $queryResult2->fetch_assoc();
    //     $DailyProductionFailQty = $row2['DailyProductionFailQty'];
    //     if($DailyProductionFailQty == '') $DailyProductionFailQty = '0';

    //     if($DailyProductionFailQty == '0') { 
    //         $defectPercentage = '0';
    //     } else{
    //         $defectPercentage = number_format(($DailyProductionFailQty / $DailyProductionQty) * 100, 2);
    //     }

    //     $data7 .=  "{ label: '$Day', y: $defectPercentage},";

    // }


    //for monthly defect reason



    $sql = "SELECT d.DefectReason, SUM(d.No_of_Defect) AS DefectQTy FROM `ework_defect` d LEFT JOIN eworker_operation o ON o.ework_id = d.OperationID WHERE SUBSTRING(`d`.`Entry_dateime`, 1, 7) = SUBSTRING('$Date', 1, 7) AND o.docnumber = '$docnumber' GROUP BY d.DefectReason ORDER BY DefectQTy DESC LIMIT 10;";

    // echo $sql;
        
    $data8 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $DefectReason =  $row['DefectReason'];
      $DefectQTy  = $row['DefectQTy'];
      
      $data8 .= "{ label: '$DefectReason', y: $DefectQTy, color: 'red'},";

    }





?>


<style>
a.canvasjs-chart-credit {
    display: none !important;
}
</style>

<script type="text/javascript">

window.onload = function () {
    var chart = new CanvasJS.Chart("chartConntainer1",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "Today's Hourly Line Performance"
        },
        dataPointMaxWidth: 25,
     
        axisY:{
                includeZero: true
            },
                    data: [
        {
            type: "column",
            name: "Inspection Qty",
            indexLabel: "{y}",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "Black", // Add this line
            // indexLabelPlacement: "inside",
            showInLegend: true,
            dataPoints: [
                <?php echo $data3; ?>
            ]
        },{
            type: "column",
            name: "QC Passed Qty",
            indexLabel: "{y}",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "Black", // Add this line
            // indexLabelPlacement: "inside",
            showInLegend: true,
            dataPoints: [
                <?php echo $data; ?>
            ]
        },{
            type: "column",
            name: "Efficiency (%)",
            indexLabel: "{y}%",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "Black", // Add this line
            // indexLabelPlacement: "inside",
            showInLegend: true,
            dataPoints: [
                <?php echo $data2; ?>
            ]
        }
]    
});
    chart.render();




var chart = new CanvasJS.Chart("chartConntainer2",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "Today's Color & Size wise Output"
        },
        dataPointMaxWidth: 25,
     
    axisY:{
            includeZero: true
        },
    // axisX: {
    //         labelAngle: -45, // Rotate labels
    //         interval: 1, // Adjust the interval between ticks
    //     },
    data: [
    {
        type: "column",
        // name: "No. of Defects on Operation",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        // showInLegend: true,
        dataPoints: [
            <?php echo $data4; ?>
        ]
    }
]    
});
    chart.render();


//daily color & size

var chart = new CanvasJS.Chart("chartConntainer3",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "This Month Quality Status"
        },
        dataPointMaxWidth: 25,
     
    axisY:{
            includeZero: true
        },
    axisX: {
            labelAngle: -45, // Rotate labels
            interval: 1, // Adjust the interval between ticks
        },
    data: [
        {
            type: "pie",
            startAngle: 45,
            showInLegend: true,
            legendText: "{label}",
            indexLabelPlacement: "outside",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "black", // Add this line
            indexLabel: "{y}%",
            indexLabelFontSize: 16,
            dataPoints: [
                <?php echo $data6; ?>
                <?php echo $data7; ?> 
                <?php echo $data7s; ?> 
            ]
        }
    ]
});
    chart.render();

//monthly defect reason

var chart = new CanvasJS.Chart("chartConntainer4",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "This Month Top 10 Defect Reasons"
        },
        dataPointMaxWidth: 25,
     
    axisY:{
            includeZero: true
        },
    axisX: {
            labelAngle: -45, // Rotate labels
            interval: 1, // Adjust the interval between ticks
        },
    data: [
    {
        type: "column",
        // name: "No. of Defects on Operation",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        // showInLegend: true,
        dataPoints: [
            <?php echo $data8; ?>
        ]
    }
]    
});
    chart.render();

}






</script>







            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">

                                
                            <!-- <div class="card">
              <div class="card-body">
                <h5 class="card-title">Card title</h5>

                <p class="card-text">
                  Some quick example text to build on the card title and make up the bulk of the card's
                  content.
                </p>

                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
              </div>
            </div> -->

                            <!-- <div class="card card-primary card-outline">
              <div class="card-body">
                <h5 class="card-title">Card title</h5>

                <p class="card-text">
                  Some quick example text to build on the card title and make up the bulk of the card's
                  content.
                </p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
              </div>
            </div> -->

                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <script type="text/javascript" src="canvas/canvasjs.min.js"></script>

        <!-- Main Footer -->
        <div id="chartConntainer1" style="height: 45%; width: 47%; position: absolute; top: 0; left: 0; margin-top: 50px; margin-left: 80px;" ></div>
        <div id="chartConntainer2" style="height: 45%; width: 47%; position: absolute; top: 0; right: 0; margin-top: 50px;" ></div>
        <div id="chartConntainer3" style="height: 45%; width: 47%; position: absolute; bottom: 0; left: 0; margin-top: 10px; margin-left: 80px;" ></div>
        <div id="chartConntainer4" style="height: 45%; width: 47%; position: absolute; bottom: 0; right: 0; margin-top: 10px;" ></div>
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
</body>




</html>