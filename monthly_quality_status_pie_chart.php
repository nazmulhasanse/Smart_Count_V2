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




    
    ?>

    <header style="text-align: center;">
           <h1><?php echo $Date; ?></h1>
      </header>

    
    
<script type="text/javascript">
    setTimeout(function() {
      location.reload();
    }, 30000); // 30,000 milliseconds = 30 seconds

</script>

<?php

    $sql = "SELECT 
                SUBSTRING(o.DateTime, 1, 7) AS DATE,
                SUM(Qty) AS InspectionQty
            FROM
                eworker_operation o
                    LEFT JOIN
                ework_workers w ON o.cardnumber = w.cardnumber
                    LEFT JOIN
                eworker_assignment a ON o.cardnumber = a.cardnumber
                    AND o.docnumber = a.docnumber
            WHERE
                SUBSTRING(o.DateTime, 1, 7) = '$Date'
                    AND w.Position = '2'
                    AND o.qty_type IN ('pass', 'fail', 'reject')
                    AND `o`.`SewingLine` = '$Line'
                    ";

     // echo $sql;       


    $queryResult = $conn->query($sql);

    $row = $queryResult->fetch_assoc();
    $InspectionQty = $row['InspectionQty'];
    $Date = $row['DATE'];

    $data  =  "{ label: '$Date', y: $InspectionQty}";


    $sql = "SELECT 
            SUBSTRING(o.DateTime, 1, 7) AS DATE,
            SUM(Qty) AS FirstTimePass
        FROM
            eworker_operation o
                LEFT JOIN
            ework_workers w ON o.cardnumber = w.cardnumber
                LEFT JOIN
            eworker_assignment a ON o.cardnumber = a.cardnumber
                AND o.docnumber = a.docnumber
        WHERE
            SUBSTRING(o.DateTime, 1, 7) = '$Date'
                AND o.qty_type = 'pass'
                AND w.Position = '2'
                AND `o`.`SewingLine` = '$Line'
                ";

     // echo $sql;       


    $queryResult = $conn->query($sql);

    $row = $queryResult->fetch_assoc();
    $FirstTimePass = $row['FirstTimePass'];
    $Date = $row['DATE'];

    $FirstTimePassPercentage    = number_format(($FirstTimePass / $InspectionQty) * 100,2);
    $data2 = "{ label: '1st-time-pass', y: $FirstTimePassPercentage, color: 'green'},";


    $sql = "SELECT 
            SUBSTRING(o.DateTime, 1, 7) AS DATE,
            SUM(Qty) AS ReworkPass
        FROM
            eworker_operation o
                LEFT JOIN
            ework_workers w ON o.cardnumber = w.cardnumber
                LEFT JOIN
            eworker_assignment a ON o.cardnumber = a.cardnumber
                AND o.docnumber = a.docnumber
        WHERE
            SUBSTRING(o.DateTime, 1, 7) = '$Date'
                AND o.qty_type = 'rework-pass'
                AND w.Position = '2'
                AND `o`.`SewingLine` = '$Line'
                ";

     // echo $sql;       


    $queryResult = $conn->query($sql);

    $row = $queryResult->fetch_assoc();
    $ReworkPass = $row['ReworkPass'];
    if($ReworkPass == '') $ReworkPass = '0';

    $Date = $row['DATE'];

    $ReworkPassPercentage    = number_format(($ReworkPass / $InspectionQty) * 100,2);
    $data2s = "{ label: 'rework-pass', y: $ReworkPassPercentage, color: 'blue'},";



    $sql = "SELECT 
            SUBSTRING(o.DateTime, 1, 7) AS DATE,
            SUM(Qty) AS FirstTimeReject
        FROM
            eworker_operation o
                LEFT JOIN
            ework_workers w ON o.cardnumber = w.cardnumber
                LEFT JOIN
            eworker_assignment a ON o.cardnumber = a.cardnumber
                AND o.docnumber = a.docnumber
        WHERE
            SUBSTRING(o.DateTime, 1, 7) = '$Date'
                AND o.qty_type = 'reject'
                AND w.Position = '2'
                AND `o`.`SewingLine` = '$Line'
                ";

     // echo $sql;       


    $queryResult = $conn->query($sql);

    $row = $queryResult->fetch_assoc();
    $FirstTimeReject = $row['FirstTimeReject'];
    // echo $FirstTimeReject;
    if($FirstTimeReject == '') $FirstTimeReject = '0';
    $Date = $row['DATE'];

    $FirstTimeRejectPercentage    = number_format(($FirstTimeReject / $InspectionQty) * 100,2);
    $data3 = "{ label: '1st-time-reject', y: $FirstTimeRejectPercentage, color: 'red'},";



    $sql = "SELECT 
            SUBSTRING(o.DateTime, 1, 7) AS DATE,
            SUM(Qty) AS ReworkReject
        FROM
            eworker_operation o
                LEFT JOIN
            ework_workers w ON o.cardnumber = w.cardnumber
                LEFT JOIN
            eworker_assignment a ON o.cardnumber = a.cardnumber
                AND o.docnumber = a.docnumber
        WHERE
            SUBSTRING(o.DateTime, 1, 7) = '$Date'
                AND o.qty_type = 'rework-reject'
                AND w.Position = '2'
                AND `o`.`SewingLine` = '$Line'
                ";

     // echo $sql;       


    $queryResult = $conn->query($sql);

    $row = $queryResult->fetch_assoc();
    $ReworkReject = $row['ReworkReject'];
    if($ReworkReject == '') $ReworkReject = '0';
    $Date = $row['DATE'];

    $ReworkRejectPercentage    = number_format(($ReworkReject / $InspectionQty) * 100,2);
    $data4 = "{ label: 'rework-reject', y: $ReworkRejectPercentage, color: 'black'},";



    $TotalRework = $InspectionQty - ($FirstTimePass + $FirstTimeReject + $ReworkReject + $ReworkPass);

    $TotalReworkPercentage    = number_format(($TotalRework / $InspectionQty) * 100,2);
    $data5 = "{ label: 'on-going-rework', y: $TotalReworkPercentage, color: 'orange'},";
      




    


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
            text: "Monthly Quality Status Chart"
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
                <?php echo $data2; ?>
                <?php echo $data2s; ?>
                <?php echo $data3; ?>
                <?php echo $data5; ?>
                <?php echo $data4; ?>
            ]
        }
    ]
});
    chart.render();
}


</script>

<script type="text/javascript" src="canvas/canvasjs.min.js"></script>


<div id="chartConntainer" style="height: 500px; width: 100%;"></div>    
<?php include 'footer.php'; ?>