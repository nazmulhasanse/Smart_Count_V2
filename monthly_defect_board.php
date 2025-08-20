<?php

include 'header.php';
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    


    $Date      = $_GET['Date'];
    $Line      = $_GET['Line'];

?>


    <header style="text-align: center;">
           <h1><?php echo $Date; ?></h1>
      </header>

    <div class="row">
       <div class="col-1">
           
       </div> 
       <div class="col-2">
            <b>Site: </b><?php echo $_GET['Site']; ?></br>
            
       </div>
       <div class="col-2">
            <b>Building: </b><?php echo $_GET['Building']; ?></br>
            
       </div>
       <div class="col-2">
            <b>FLoor: </b><?php echo $_GET['Floor']; ?></br>
            
       </div>
       <div class="col-2">
            <b>Line No.: </b><?php echo $_GET['Line']; ?></br>
            
       </div>
       <div class="col-2">
            <b>Customer: </b><?php echo $_GET['Customer']; ?>
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



    $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('pass', 'fail', 'reject') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 10) LIKE '$Date%'  AND `o`.`SewingLine` = '$Line' GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10) ORDER BY `Day` ASC";

    // echo $sql;
        
    $data = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $Day                   =  $row['Day'];
      $DailyProductionQty    = $row['DailyProductionQty'];
      if($DailyProductionQty == '') $DailyProductionQty = '0';
      
      $data .= "{ label: '$Day', y: $DailyProductionQty},";

    }

    // echo $data;

    $sql2 = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionFailQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('fail', 'rework-fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 10) LIKE '$Date%' AND `o`.`SewingLine` = '$Line' GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10)";

    // echo $sql;
        
    $data2 = "";

    $queryResult2 = $conn->query($sql2);

    while ($row2 = $queryResult2->fetch_assoc()) {
      
      
      $Day                       =  $row2['Day'];
      $DailyProductionFailQty    = $row2['DailyProductionFailQty'];
      if($DailyProductionFailQty == '') $DailyProductionFailQty = '0';
      
      $data2 .= "{ label: '$Day', y: $DailyProductionFailQty},";



    }



    $sql = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('pass', 'fail', 'reject') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 10) LIKE '$Date%' AND `o`.`SewingLine` = '$Line' GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10)";

    $data3 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $Day                =  $row['Day'];
      $DailyProductionQty = $row['DailyProductionQty'];
      if($DailyProductionQty == '') $DailyProductionQty = '0';


        $sql2 = "SELECT SUBSTRING(`o`.`DateTime`, 1, 10) AS `Day`, SUM(`o`.`Qty`) AS `DailyProductionFailQty` FROM `eworker_operation` `o` LEFT JOIN `ework_workers` w ON `o`.`cardnumber` = `w`.`cardnumber` WHERE `o`.`qty_type` IN ('fail', 'rework-fail') AND `w`.`Position` = '2' AND SUBSTRING(`o`.`DateTime`, 1, 10) = '$Day' AND `o`.`SewingLine` = '$Line' GROUP BY SUBSTRING(`o`.`DateTime`, 1, 10) LIMIT 1";

        $queryResult2 = $conn->query($sql2);
        $row2 = $queryResult2->fetch_assoc();
        $DailyProductionFailQty = $row2['DailyProductionFailQty'];
        if($DailyProductionFailQty == '') $DailyProductionFailQty = '0';

        if($DailyProductionFailQty == '0') { 
            $defectPercentage = '0';
        } else{
            $defectPercentage = number_format(($DailyProductionFailQty / $DailyProductionQty) * 100, 2);
        }

        $data3 .=  "{ label: '$Day', y: $defectPercentage},";

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
        theme: "light1",
        title: {
            text: "Monthly Re-Work Rate",
            fontSize: 30
        },
        dataPointMaxWidth: 100,
     
    axisY:{
            includeZero: true
        },
    axisX: {
            labelAngle: -45, // Rotate labels
            //interval: 1, // Adjust the interval between ticks
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
        indexLabelFontSize: 20,
        dataPoints: [
            <?php echo $data; ?>
        ]
    },{
        type: "column",
        name: "Re-Work Qty",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        showInLegend: true,
        indexLabelFontSize: 20,
        dataPoints: [
            <?php echo $data2; ?>
        ]
    },{
        type: "column",
        name: "Re-Work (%)",
        indexLabel: "{y}%",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        showInLegend: true,
        indexLabelFontSize: 20,
        dataPoints: [
            <?php echo $data3; ?>
        ]
    }
]    
});
    chart.render();
}


</script>

<script type="text/javascript" src="canvas/canvasjs.min.js"></script>


<div id="chartConntainer" style="height: 700px; width: 100%;"></div>    
<?php include 'footer.php'; ?>