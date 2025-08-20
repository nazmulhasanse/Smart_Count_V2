<?php

include 'header.php';
error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    


    $Date      = $_GET['Date'];
    $docnumber = $_GET['docnumber'];

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
            
       </div>
       <div class="col-2">
            <b>FLoor: </b><?php echo $_GET['Floor']; ?></br>
            <b>Line No.: </b><?php echo $_GET['Line']; ?></br>
            
       </div>
       <div class="col-2">
            <b>Customer: </b><?php echo $_GET['Customer']; ?></br>
            <b>Work Order: </b><?php echo $_GET['WorkOrder']; ?></br>
            
       </div>
       <div class="col-2">
            <b>Style: </b><?php echo $_GET['Style']; ?></br>
            <b>Description: </b><?php echo $_GET['StyleDescription']; ?></br>
            
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



    // $sql = "SELECT color, size, SUM(Qty) AS InputQty FROM `eworker_operation` WHERE StepNumber = '1' AND SUBSTRING(`DateTime`, 1, 10) = '$Date' GROUP BY color, size ORDER BY size ASC";

    // // echo $sql;
        
    // $data = "";

    // $queryResult = $conn->query($sql);

    // while ($row = $queryResult->fetch_assoc()) {
      
      
    //   $color =  $row['color'];
    //   $size =  $row['size'];
    //   $color_size = $color . ',' . $size;
    //   $InputQty  = $row['InputQty'];
      
    //   $data .= "{ label: '$color_size', y: $InputQty, color: 'blue'},";

    // }


    $sql = "SELECT o.color, o.size, SUM(o.Qty) AS Qty FROM `eworker_operation` o LEFT JOIN `ework_workers` w ON o.cardnumber = w.cardnumber WHERE w.Position = '2' AND o.color != '' AND o.size != '' AND SUBSTRING(o.`DateTime`, 1, 10) = '$Date' GROUP BY o.color, o.size ORDER BY o.size ASC";

    // echo $sql;
        
    $data2 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $color =  $row['color'];
      $size =  $row['size'];
      $color_size = $color . ',' . $size;
      $Qty  = $row['Qty'];
      
      // $data2 .= "{ label: '$color_size', y: $Qty, color: 'green'},";
      $data2 .= "{ label: '$color_size', y: $Qty},";

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
            text: "Daily Color & Size Output",
            fontSize: 30
        },
        dataPointMaxWidth: 50,
     
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
        name: "Input Qty",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        // showInLegend: true,
        indexLabelFontSize: 20,
        dataPoints: [
            <?php echo $data; ?>
        ]
    },{
        type: "column",
        name: "Output Qty",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        // showInLegend: true,
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


<div id="chartConntainer" style="height: 700px; width: 100%;"></div>    
<?php include 'footer.php'; ?>