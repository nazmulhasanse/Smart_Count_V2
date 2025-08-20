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



    $sql = "SELECT
                d.DefectReason,
                SUM(d.No_of_Defect) AS DefectQTy
            FROM
                `ework_defect` d
            LEFT JOIN `eworker_operation` o ON
                d.OperationID = o.ework_id
            WHERE
                SUBSTRING(`d`.`Entry_dateime`, 1, 7) = '$Date' AND `o`.`SewingLine` = '$Line'
            GROUP BY
                d.DefectReason
            ORDER BY
                DefectQTy
            DESC
            LIMIT 10;";

    // echo $sql;
        
    $data = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $DefectReason =  $row['DefectReason'];
      $DefectQTy  = $row['DefectQTy'];
      
      $data .= "{ label: '$DefectReason', y: $DefectQTy, color: 'red'},";

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
            text: "Top 10 Defect Reasons",
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
        // name: "No. of Defects on Operation",
        indexLabel: "{y}",
        indexLabelOrientation: "vertical", // Add this line
        indexLabelFontColor: "Black", // Add this line
        // indexLabelPlacement: "inside",
        // showInLegend: true,
        indexLabelFontSize: 20,
        dataPoints: [
            <?php echo $data; ?>
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