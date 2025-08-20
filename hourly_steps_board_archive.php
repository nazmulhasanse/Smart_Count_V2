<?php

include 'header.php';
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    $Date       = $_GET['Date'];
    $docnumber  = $_GET['docnumber'];
    $cardnumber = $_GET['cardnumber'];
    $StepNumber = $_GET['StepNumber'];


    $sql2 = "SELECT Name FROM `ework_workers` WHERE cardnumber = '$cardnumber';
";

    $queryResult2 = $conn->query($sql2);
    $row2 = $queryResult2->fetch_assoc();
    $Name =  $row2['Name'];

?>

<header style="text-align: center;">
           <h1><?php echo $cardnumber . " (" . $Name .  ")" . " - Step Number: " . $StepNumber; ?></h1>
      </header>

<div class="row">
   <div class="col-1">
            
   </div>
   <div class="col-2">
        <b>Date: </b><?php echo $_GET['Date']; ?></br>
        
   </div>
   <div class="col-2">
        <b>Line No.: </b><?php echo $_GET['SewingLine']; ?></br>
   </div>
   <div class="col-2">
        <b>Style: </b><?php echo $_GET['Style']; ?></br>
   </div>
   <div class="col-2">
        <b>Work Order: </b><?php echo $_GET['WorkOrder']; ?></br>
        <!-- <b>SMV: </b><?php echo $SMV; ?></br> -->
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
    SUBSTRING(DATETIME, 1, 10) AS DATE,
    SUBSTRING(DATETIME, 12, 2) AS TIME,
    SUM(Qty) AS HourlyQty
FROM
    eworker_operation_archive o
WHERE DATETIME LIKE
    '%$Date%' AND docnumber = '$docnumber' AND cardnumber = '$cardnumber' AND StepNumber = '$StepNumber'
GROUP BY
    DATE,
    TIME
ORDER BY
    TIME;";

    // echo $sql;



    $data = "";
    $data2 = "";

    $queryResult = $conn->query($sql);

    // $sl = 1;

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
      
      $HourlyQty = $row['HourlyQty'];


        $sql2 = "SELECT 
                A.StepTimeMins AS SMV
            FROM
                `ework_sales_order` AS A
            WHERE
                A.docnumber = '$docnumber' AND A.StepNumber = '$StepNumber' LIMIT 1";

        $queryResult2 = $conn->query($sql2);
        $row2 = $queryResult2->fetch_assoc();
        $SMV =  $row2['SMV'];

      
      $Efficiency =  number_format((($SMV * $HourlyQty) /  60) * 100, 2);

        if (!is_finite($Efficiency)) {
            // Handle the infinity case
            $Efficiency = 0;
        }



      $data .=  "{ label: '$Time', y: $HourlyQty}, ";
      $data2 .=  "{ label: $Time, y: $Efficiency},";





    }




?>


<!DOCTYPE HTML>
<html>
<head>

<style>
    a.canvasjs-chart-credit {
        display: none !important;
    }



</style>


<script type="text/javascript">



window.onload = function () {
    // modal.style.display = "block";
    var chart = new CanvasJS.Chart("chartContainer",
    {   
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title: {
            text: "Hourly Steps Output & Efficiency",
            fontSize: 30
        },
        dataPointMaxWidth: 50,
        axisY:{
            includeZero: true
        },
        axisX: {
            labelAngle: -45, // Rotate labels
            interval: 1 // Adjust the interval between ticks
        },
        data: [
        {
            type: "column",
            name: "Output Qty",
            // color: "powderblue",
            indexLabel: "{y} ",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "Black", // Add this line
            showInLegend: true,
            indexLabelFontSize: 20,
            dataPoints: [
                <?php echo $data; ?>
            ]
        }
        ,{
            type: "column",
            name: "Efficiency (%)",
            indexLabel: "{y} %",
            indexLabelOrientation: "vertical", // Add this line
            indexLabelFontColor: "Black", // Add this line
            showInLegend: true,
            indexLabelFontSize: 20,
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

<div id="chartContainer" style="height: 800px; width: 100%;"></div>    
<?php include 'footer.php'; ?>
