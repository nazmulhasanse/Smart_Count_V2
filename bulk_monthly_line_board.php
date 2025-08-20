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
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUM(Qty) AS FinishedGoodsQty,
                o.docnumber AS docnumber
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                SUBSTRING(o.DateTime, 1, 7) LIKE '$Date%'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND w.Position = '2'
                    AND o.SewingLine = '$Line'
            GROUP BY DATE
            ORDER BY DATE";

            

    $data = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {


      
      
      $DATEs   = $row['DATE'];

      

      $FinishedGoodsQty = $row['FinishedGoodsQty'];
      if($FinishedGoodsQty == '') $FinishedGoodsQty = '0';


      $data .=  "{ label: '$DATEs', y: $FinishedGoodsQty},";

    }







    
    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUM(Qty) AS FinishedGoodsQty,
                o.docnumber AS docnumber
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                SUBSTRING(o.DateTime, 1, 7) LIKE '$Date%'
                    AND o.qty_type IN ('pass', 'rework-pass')
                    AND w.Position = '2'
                    AND o.SewingLine = '$Line'
            GROUP BY DATE
            ORDER BY DATE";




    $data2 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
        $DATEs            = $row['DATE'];
        $docnumber        = $row['docnumber'];
        $FinishedGoodsQty = $row['FinishedGoodsQty'];

        $sql2 = "SELECT 
                TotalSMV AS SMV
            FROM
                `ework_target_efficiency`
            WHERE
                Docnumber = '$docnumber'
                AND Date = '$DATEs'";
        // echo $sql2;

        $queryResult2 = $conn->query($sql2);
        $row2 = $queryResult2->fetch_assoc();
        $SMV =  number_format($row2['SMV'],2);

        // echo $SMV;


        $sql3 = "SELECT Operator, Helper, actualworkinghour FROM `ework_target_efficiency` WHERE `Date` = '$DATEs' AND SewingLine = '$Line' LIMIT 1";
        $queryResult3 = $conn->query($sql3);
        $row3 = $queryResult3->fetch_assoc();
        $Operator =  $row3['Operator'];
        $Helper =  $row3['Helper'];
        $actualworkinghour =  $row3['actualworkinghour'];

        if($actualworkinghour == '0' || $actualworkinghour == ''){
            $Efficiency = '0';
        } else{

            $Efficiency = $FinishedGoodsQty * $SMV / (($Operator + $Helper) * 60 * $actualworkinghour) * 100;

            $Efficiency = number_format($Efficiency,2);
        }
      
        // echo $Efficiency;


        $data2 .=  "{ label: '$DATEs', y: $Efficiency},";

    }


    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 10) AS DATE,
                SUM(Qty) AS DailyProductionQty,
                o.docnumber AS docnumber
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                SUBSTRING(o.DateTime, 1, 7) LIKE '$Date%'
                    AND w.Position = '2'
                    AND o.SewingLine = '$Line'
            GROUP BY DATE
            ORDER BY DATE";

    $data3 = "";

    $queryResult = $conn->query($sql);

    while ($row = $queryResult->fetch_assoc()) {
      
      
      $DATEs   = $row['DATE'];
      

      $DailyProductionQty = $row['DailyProductionQty'];


      $data3 .=  "{ label: '$DATEs', y: $DailyProductionQty},";

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
            text: "Monthly Output & Efficiency Status",
            fontSize: 30
        },
        dataPointMaxWidth: 50,
     
    axisY: {
        includeZero: true,

    },
    axisX: {
            labelAngle: -45, // Rotate labels
            interval: 1 // Adjust the interval between ticks
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
        indexLabelFontSize: 20,
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