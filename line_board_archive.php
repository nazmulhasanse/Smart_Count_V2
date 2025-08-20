<?php

include 'header.php';
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    


$Date 	   = $_GET['Date'];
$WorkOrder = $_GET['WorkOrder'];
$Style     = $_GET['Style'];
$Line      = $_GET['Line'];
$docnumber = $_GET['docnumber'];



    $sql = "SELECT 
                SUM(A.StepTimeMins) AS SMV
            FROM
                `ework_sales_order` AS A
            WHERE
                A.docnumber = '$docnumber'
            GROUP BY A.docnumber";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $SMV =  $row['SMV'];

    $sql = "SELECT SUM(qty) AS InputQty FROM `ework_daily_color_size` WHERE docnumber = '$docnumber' GROUP BY docnumber;";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $InputQty =  $row['InputQty'];

    // echo $sql;

    $sql = "SELECT 
                SUM(o.Qty) AS PassedQty
            FROM
                eworker_operation_archive o
                    LEFT JOIN
                ework_workers e ON o.cardnumber = e.cardnumber
            WHERE
                    o.docnumber = '$docnumber'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND e.Position = '2'
                    AND o.SewingLine = '$Line'";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $PassedQty =  $row['PassedQty'];

    $sql = "SELECT 
                SUM(o.Qty) AS FailedQty
            FROM
                eworker_operation_archive o
                    LEFT JOIN
                ework_workers e ON o.cardnumber = e.cardnumber
            WHERE
                    o.docnumber = '$docnumber'
                    AND o.qty_type IN ('fail', 'rework-fail')
                    AND e.Position = '2'
                    AND o.SewingLine = '$Line'";

    // echo $sql;

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $FailedQty =  $row['FailedQty'];


    // $sql = "SELECT 
    //             *
    //         FROM
    //             `eworker_assignment` a
    //                 LEFT JOIN
    //             `ework_workers` w ON a.cardnumber = w.cardnumber
    //         WHERE
    //                  a.docnumber = '$docnumber'
    //                 AND w.Position = '0'
    //                 AND a.Style = '$Style'
    //                 AND a.line = '$Line'
    //                 AND a.WorkerActive = '1'
    //         GROUP BY w.cardnumber";

    // $queryResult = $conn->query($sql);
    // $Operator = mysqli_num_rows($queryResult);

    // $sql = "SELECT 
    //         *
    //         FROM
    //         `eworker_assignment` a
    //             LEFT JOIN
    //         `ework_workers` w ON a.cardnumber = w.cardnumber
            
    //         WHERE
    //              a.docnumber = '$docnumber'
    //             AND w.Position = '1'
    //             AND a.Style = '$Style'
    //             AND a.line = '$Line'
    //             AND a.WorkerActive = '1'
    //         GROUP BY w.cardnumber";

    // $queryResult = $conn->query($sql);
    // $Helper = mysqli_num_rows($queryResult);

    $sql = "SELECT Operator, Helper FROM `ework_target_efficiency` WHERE Date = '$Date' AND SewingLine = '$Line'";
    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $Operator =  $row['Operator'];
    $Helper =  $row['Helper'];


    $Quality = '1';

    $TotalManPower = $Operator + $Helper + $Quality;

    $DailyTarget = ($_GET['TargetEfficiency'] * $_GET['WorkingHour'] * ($Operator + $Helper) * 60) / ($SMV * 100);

    $DailyTarget = number_format($DailyTarget);


    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE, SUM(o.Qty) AS ToadyPassedQty
            FROM
                eworker_operation_archive o
                    LEFT JOIN
                ework_workers e ON o.cardnumber = e.cardnumber
            WHERE
                    o.docnumber = '$docnumber'
                    AND o.DateTime LIKE '%$Date%'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND e.Position = '2'
                    AND o.SewingLine = '$Line'";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $ToadyPassedQty =  $row['ToadyPassedQty'];

    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE, SUM(o.Qty) AS ToadyFailQty
            FROM
                eworker_operation_archive o
                    LEFT JOIN
                ework_workers e ON o.cardnumber = e.cardnumber
            WHERE
                    o.docnumber = '$docnumber'
                    AND o.DateTime LIKE '%$Date%'
                    AND o.qty_type IN ('fail', 'rework-fail')
                    AND e.Position = '2'
                    AND o.SewingLine = '$Line'";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $ToadyFailQty =  $row['ToadyFailQty'];



    // $sql = "SELECT 
    //             SUBSTRING(o.DateTime, 12, 2) AS Time
    //         FROM
    //             eworker_operation_archive o
    //                 LEFT JOIN
    //             ework_workers e ON o.cardnumber = e.cardnumber
    //         WHERE
    //                 o.WorkOrder = '$WorkOrder'
    //                 AND o.Style = '$Style'
    //                 AND o.SewingLine = '$Line'
    //                 AND o.DateTime LIKE '%$Date%'
    //                 AND o.qty_type = 'pass'
    //                 AND e.Position = '2'
    //         GROUP BY SUBSTRING(o.DateTime, 12, 2)";

    // $queryResult = $conn->query($sql);
    // $actualWorkingHour = mysqli_num_rows($queryResult);

    $actualWorkingHour = $_GET['ActualWorkingHour'];

    // echo $actualWorkingHour;
    // exit();

    if($actualWorkingHour == 0){ 
        $TodayEfficiency = '';
    }else{
        $TodayEfficiency = ($ToadyPassedQty * $SMV) / (($Operator + $Helper) * 60 * $actualWorkingHour) * 100;
    }

    
    ?>

    <header style="text-align: center;">
           <h1><?php echo $Date; ?></h1>
      </header>

    <div class="row">
       <div class="col-1">
           
       </div> 
       <div class="col-2">
            <b>Site: </b><?php echo $_GET['Site']; ?></br>
            <b>Building: </b><?php echo $_GET['Building']; ?></br>
            <b>FLoor: </b><?php echo $_GET['Floor']; ?></br>
            <b>Line No.: </b><?php echo $_GET['Line']; ?></br>
            <b>Customer: </b><?php echo $_GET['Customer']; ?>
       </div>
       <div class="col-2">
            <b>Style: </b><?php echo $_GET['Style']; ?></br>
            <b>Item Description: </b><?php echo $_GET['StyleDescription']; ?></br>
            <b>Work Order: </b><?php echo $_GET['WorkOrder']; ?></br>
            <b>SMV: </b><?php echo $SMV; ?></br>
            <b>Target Efficiency: </b><?php echo $_GET['TargetEfficiency'].' %'; ?></br>
       </div>
       <div class="col-2">
            <b>Operator: </b><?php echo $Operator; ?></br>
            <b>Helper: </b><?php echo $Helper; ?></br>
            <b>Quality Inspector: </b><?php echo $Quality; ?></br>
            <b>Total Man Power: </b><?php echo $TotalManPower; ?></br>
            <b>Today's Efficiency: </b><?php if($TodayEfficiency == '0'){}else{ echo number_format($TodayEfficiency,2).' %'; }?></br>
       </div>
       <div class="col-2">
            <b>Allocated Qty: </b><?php echo number_format($_GET['quantity']); ?></br>
            <b>Input Qty: </b><?php echo number_format($InputQty); ?></br>
            <b>Line WIP: </b><?php echo $InputQty - $PassedQty; ?></br>
            <b style="color: green;">Total QC Passed Qty: </b><?php echo number_format($PassedQty); ?></br>
            <b style="color: red;">Total QC Failed Qty: </b><?php echo number_format($FailedQty); ?></br>
       </div>
       <div class="col-2">
            <b>Working Hour: </b><?php echo $_GET['ActualWorkingHour']; ?></br>
            <b>Daily Target: </b><?php echo $DailyTarget; ?></br>
            <b style="color: darkblue;">Today's Inspection Qty: </b><?php echo $ToadyPassedQty + $ToadyFailQty; ?></br>
            <b style="color: green;">Today's QC Passed Qty: </b><?php echo number_format($ToadyPassedQty); ?></br>
            <b style="color: red;">Today's QC Failed Qty: </b><?php echo number_format($ToadyFailQty); ?></br>
       </div>
       <div class="col-1">
           
       </div>
    </div>
    
<script type="text/javascript">
    setTimeout(function() {
      location.reload();
    }, 30000); // 30,000 milliseconds = 30 seconds

</script>

<?php

    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(Qty) AS FinishedGoodsQty
            FROM
                eworker_operation_archive o
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
                    AND o.SewingLine = '$Line'
            GROUP BY DATE, TIME 
            ORDER BY TIME";

            

    $data = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {


      
      
      $Time   = $row['TIME'];
      if($Time == '12'){
        $Time = '12 PM';
      }else if($Time < '12'){
        $Time = $Time . ' AM';
      }else if($Time > '12'){
        $Time = ($Time - 12) . ' PM';
      }

      $Time = json_encode($Time);

      

      $FinishedGoodsQty = $row['FinishedGoodsQty'];
      if($FinishedGoodsQty == '') $FinishedGoodsQty = '0';


      $data .=  "{ label: $Time, y: $FinishedGoodsQty},";

    }



    $sql = "SELECT 
                SUM(A.StepTimeMins) AS SMV
            FROM
                `ework_sales_order` AS A
            WHERE
                A.docnumber = '$docnumber'
            GROUP BY A.docnumber";

    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $SMV =  $row['SMV'];

    if ($row['SMV'] != null) {
        $SMV = $row['SMV'];
        // Process $SMV or do something with it
    } else {
        // Handle the case where SMV is not set
        $SMV = '1';
    }



    $sql = "SELECT Operator, Helper FROM `ework_target_efficiency` WHERE Date = '$Date' AND SewingLine = '$Line'";
    $queryResult = $conn->query($sql);
    $row = $queryResult->fetch_assoc();
    $Operator =  $row['Operator'];
    $Helper =  $row['Helper'];
    
    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(Qty) AS FinishedGoodsQty
            FROM
                eworker_operation_archive o
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
                    AND o.SewingLine = '$Line'
            GROUP BY DATE, TIME
            ORDER BY TIME";

    $data2 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $Time   = $row['TIME'];
      if($Time == '12'){
        $Time = '12 PM';
      }else if($Time < '12'){
        $Time = $Time . ' AM';
      }else if($Time > '12'){
        $Time = ($Time - 12) . ' PM';
      }

      $Time = json_encode($Time);
      
      
      $Efficiency = $row['FinishedGoodsQty'] * $SMV / (($Operator + $Helper) * 60) * 100;

      $Efficiency = number_format($Efficiency,2);

      if (!is_finite($Efficiency)) {
            // Handle the infinity case
            $Efficiency = 0;
      }

      $data2 .=  "{ label: $Time, y: $Efficiency},";

    }


    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUBSTRING(o.DateTime, 12, 2) AS TIME,
                SUM(o.Qty) AS HourlyProductionQty
            FROM
                eworker_operation_archive o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                o.DateTime LIKE '%$Date%'
                    -- AND o.qty_type IN ('pass', 'fail', 'reject')
                    AND w.Position = '2'
                    AND o.docnumber = '$docnumber'
                    AND o.SewingLine = '$Line'
            GROUP BY DATE, TIME
            ORDER BY TIME";
            // echo $sql;
    $data3 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $Time   = $row['TIME'];
      if($Time == '12'){
        $Time = '12 PM';
      }else if($Time < '12'){
        $Time = $Time . ' AM';
      }else if($Time > '12'){
        $Time = ($Time - 12) . ' PM';
      }

      $Time = json_encode($Time);
      

      $HourlyProductionQty = $row['HourlyProductionQty'];
      if($HourlyProductionQty == '') $HourlyProductionQty = '0';


      $data3 .=  "{ label: $Time, y: $HourlyProductionQty},";

    }


?>



<style>
    a.canvasjs-chart-credit {
        display: none !important;
    }
</style>

<script type="text/javascript">

window.onload = function () {
    var chart = new CanvasJS.Chart("chartConntainer",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "Hourly Output & Efficiency Status",
            fontSize: 30
        },
        dataPointMaxWidth: 50,
     
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
}


</script>

<script type="text/javascript" src="canvas/canvasjs.min.js"></script>


<div id="chartConntainer" style="height: 800px; width: 100%;"></div>    
<?php include 'footer.php'; ?>