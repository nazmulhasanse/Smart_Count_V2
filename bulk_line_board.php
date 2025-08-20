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
                TotalSMV AS SMV
            FROM
                `ework_target_efficiency`
            WHERE
                Docnumber = '$docnumber'
                AND Date = '$Date'";

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
                eworker_operation o
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
                eworker_operation o
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

    $sql = "SELECT Operator, Helper FROM `ework_target_efficiency` WHERE Date = '$Date' AND Docnumber = '$docnumber'";
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
                eworker_operation o
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
                eworker_operation o
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



    $sql = "SELECT 
                SUBSTRING(o.DateTime, 12, 2) AS Time
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers e ON o.cardnumber = e.cardnumber
            WHERE
                    o.WorkOrder = '$WorkOrder'
                    AND o.Style = '$Style'
                    AND o.SewingLine = '$Line'
                    AND o.DateTime LIKE '%$Date%'
                    AND e.Position = '2'
            GROUP BY SUBSTRING(o.DateTime, 12, 2)";

    $queryResult = $conn->query($sql);
    $hour = mysqli_num_rows($queryResult);

    $actualWorkingHour = $_GET['ActualWorkingHour'];

    // echo $actualWorkingHour;
    // exit();

    if($actualWorkingHour == ''){ 

        $TodayEfficiency = ($ToadyPassedQty * $SMV) / (($Operator + $Helper) * 60 * $hour) * 100;
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
            <!-- <b>Working Hour: </b><?php echo $_GET['ActualWorkingHour']; ?></br> -->
            <b></b><?php //echo $_GET['ActualWorkingHour']; ?></br>
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
                    AND o.SewingLine = '$Line'
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
                    AND o.SewingLine = '$Line'
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
                    AND o.SewingLine = '$Line'
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



    

    // echo $data;
    // echo $data2;
    // echo $data3;


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
    },
    {
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
    },
    {
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