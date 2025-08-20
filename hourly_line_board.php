<?php

include 'header.php';
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




$Date        = $_GET['Date'];
$Line        = $_GET['Line'];


$sql1 = "SELECT
    Docnumber
FROM
    ework_target_efficiency
WHERE
    Date = '$Date' 
    AND SewingLine = '$Line'";


$queryResult1 = $conn->query($sql1);
$Docnumber = $queryResult1->fetch_assoc()['Docnumber'];
// echo $Docnumber;

$sql2 = "SELECT
    *
FROM
    ework_sales_order
WHERE
    docnumber = '$Docnumber'";


$queryResult2  = $conn->query($sql2);
$row2          = $queryResult2->fetch_assoc();
$site          = $row2['site']; 
$building      = $row2['building']; 
$floor         = $row2['floor']; 
$customer      = $row2['Customer']; 
$style         = $row2['Style']; 
$description   = $row2['StyleDescription']; 
$workorder     = $row2['WorkOrder']; 
// $allocated_qty = $row2['SalesOrderQuantity']; 




?>

<header style="text-align: center;">
    <h1><?php echo $Date; ?></h1>
</header>

<div class="row">
    <div class="col-1">

    </div>
    <div class="col-2">

        <b>Site: </b><?php echo $site; ?></br>
        <b>Building: </b><?php echo $building; ?></br>

    </div>
    <div class="col-2">

        <b>Floor: </b><?php echo $floor; ?></br>
        <b>Line: </b><?php echo $Line; ?></br>

    </div>
    <div class="col-2">

        <b>Style: </b><?php echo $style; ?></br>
        <b>Style Description: </b><?php echo $description; ?></br>

    </div>
    <div class="col-2">

        <b>Customer: </b><?php echo $customer; ?></br>
        <b>Workorder: </b><?php echo $workorder; ?></br>

    </div>
    <div class="col-2">

        <b>Card Number: </b><?php echo $_GET['cardnumber']; ?></br>
        <b>Name: </b><?php echo $_GET['name']; ?></br>

    </div>
    

</div>


<script type="text/javascript">
    setTimeout(function() {
        location.reload();
    }, 30000); // 30,000 milliseconds = 30 seconds
</script>

<?php


$sql = "SELECT
    date AS DATE,
    hour AS TIME,
    SUM(quantity) AS HourlyProductionQty
FROM
    eworker_bulk_operation
WHERE
    date = '$Date' AND line = '$Line'
GROUP BY
    date,
    hour
ORDER BY
    id ASC;";
// echo $sql;
$data = "";
$data2 = "";
$data3 = "";

$queryResult = $conn->query($sql);

while ($row = $queryResult->fetch_assoc()) {


    $time     = $row['TIME'];
    
      // if($time == '12'){
      //   $time = '12 PM';
      // }else if($time < '12'){
      //   $time = $time . ' AM';
      // }else if($time > '12'){
      //   $time = ($time - 12) . ' PM';
      // }


    $HourlyProductionQty = $row['HourlyProductionQty'];
    if ($HourlyProductionQty == '') $HourlyProductionQty = '0';


    $data3 .=  "{ label: '$time', y: $HourlyProductionQty},"; //Found Hourly Quantity





    //     $sql2 = "SELECT 
    //                 SUBSTRING(o.DateTime, 1, 10) AS DATE,
    //                 SUBSTRING(o.DateTime, 12, 2) AS TIME,
    //                 SUM(Qty) AS FinishedGoodsQty
    //             FROM
    //                 eworker_operation o
    //                     LEFT JOIN
    //                 ework_workers w ON o.cardnumber = w.cardnumber
    //                     LEFT JOIN
    //                 eworker_assignment a ON o.cardnumber = a.cardnumber
    //                     AND o.docnumber = a.docnumber
    //             WHERE
    //                 o.DateTime LIKE '%$Date%'
    //                     AND o.qty_type IN ('pass', 'rework-pass')
    //                     AND w.Position = '2'
    //                     AND o.docnumber = '$docnumber'
    //                     AND o.SewingLine = '$Line'
    //                     AND SUBSTRING(o.DateTime, 12, 2) = '$PrimeTime'
    //             GROUP BY DATE, TIME 
    //             ORDER BY TIME ASC";

    //     // echo $sql2;




    //     $queryResult2 = $conn->query($sql2);


    //     $row2 = $queryResult2->fetch_assoc();


    //     $FinishedGoodsQty = $row2['FinishedGoodsQty'];
    //     if ($FinishedGoodsQty == '') $FinishedGoodsQty = '0';


    //     $data .=  "{ label: $Timex, y: $FinishedGoodsQty},";





    //     $sql3 = "SELECT 
    //                 SUM(A.StepTimeMins) AS SMV
    //             FROM
    //                 `ework_sales_order` AS A
    //             WHERE
    //                 A.docnumber = '$docnumber'
    //             GROUP BY A.docnumber";

    //     $queryResult3 = $conn->query($sql3);
    //     $row3 = $queryResult3->fetch_assoc();
    //     $SMV =  $row3['SMV'];

    //     if ($row3['SMV'] != null) {
    //         $SMV = $row3['SMV'];
    //         // Process $SMV or do something with it
    //     } else {
    //         // Handle the case where SMV is not set
    //         $SMV = '1';
    //     }



    //     $sql4 = "SELECT Operator, Helper FROM `ework_target_efficiency` WHERE Date = '$Date' AND Docnumber = '$docnumber'";
    //     $queryResult4 = $conn->query($sql4);
    //     $row4 = $queryResult4->fetch_assoc();
    //     $Operator =  $row4['Operator'];
    //     $Helper =  $row4['Helper'];

    //     $sql5 = "SELECT 
    //                 SUBSTRING(o.DateTime, 1, 10) AS DATE,
    //                 SUBSTRING(o.DateTime, 12, 2) AS TIME,
    //                 SUM(Qty) AS FinishedGoodsQty
    //             FROM
    //                 eworker_operation o
    //                     LEFT JOIN
    //                 ework_workers w ON o.cardnumber = w.cardnumber
    //                     LEFT JOIN
    //                 eworker_assignment a ON o.cardnumber = a.cardnumber
    //                     AND o.docnumber = a.docnumber
    //             WHERE
    //                 o.DateTime LIKE '%$Date%'
    //                     AND o.qty_type IN ('pass', 'rework-pass')
    //                     AND w.Position = '2'
    //                     AND o.docnumber = '$docnumber'
    //                     AND o.SewingLine = '$Line'
    //                     AND SUBSTRING(o.DateTime, 12, 2) = '$PrimeTime'
    //             GROUP BY DATE, TIME
    //             ORDER BY TIME ASC";


    //     $queryResult5 = $conn->query($sql5);


    //     $row5 = $queryResult5->fetch_assoc();

    //     $Efficiency = $row5['FinishedGoodsQty'] * $SMV / (($Operator + $Helper) * 60) * 100;

    //     $Efficiency = number_format($Efficiency, 2);

    //     if (!is_finite($Efficiency)) {
    //         // Handle the infinity case
    //         $Efficiency = 0;
    //     }

    //     $data2 .=  "{ label: $Timex, y: $Efficiency},";
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
    window.onload = function() {
        var chart = new CanvasJS.Chart("chartConntainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light2",
            title: {
                text: "Hourly Output",
                fontSize: 30
            },
            dataPointMaxWidth: 50,

            axisY: {
                includeZero: true
            },
            data: [{
                    type: "column",
                    name: "Hourly Quantity",
                    indexLabel: "{y}",
                    indexLabelOrientation: "vertical", // Add this line
                    indexLabelFontColor: "Black", // Add this line
                    // indexLabelPlacement: "inside",
                    color: "blue", // Set the color to bright cyan
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