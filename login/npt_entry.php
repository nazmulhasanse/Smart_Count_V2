<?php
date_default_timezone_set('Asia/Dhaka');
include './ie/db_connection.php';
session_start();
if (!isset($_SESSION['cardnumber'])) {
    header("Location: index.php");
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$cardnumber = $_SESSION['cardnumber'];
if (isset($_SESSION['cardnumber'])) {
    $sql = "SELECT 
    B.Style,
    B.site,
    B.building,
    B.floor,
    B.Customer,
    B.line,
    C.StepNumber,
    C.docnumber,
    C.WorkOrder
    FROM
        eworker_assignment AS C
    LEFT JOIN
        ework_workers AS A ON C.cardnumber = A.cardnumber
    LEFT JOIN
        ework_sales_order AS B ON C.WorkOrder = B.WorkOrder
    WHERE
        C.cardnumber = '$cardnumber'
   	and B.docstatus=1
    and C.WorkerActive=1
	and C.StepActive=1
    AND C.WorkOrder = B.WorkOrder
    GROUP BY C.idlines;
    ";
    $result = $conn->query($sql); 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
     $data = $row;
    }
  
}
}

?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ework Operation</title>
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-confirm.min.js"></script>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        .container {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            /* width: 100%; */
            max-width: 90%;
            position: relative;
            /* Set position to relative for absolute positioning inside */
        }
        select {
            -webkit-appearance:none;
            -moz-appearance:none;
            -ms-appearance:none;
            appearance:none;
            outline:0;
            box-shadow:none;
            border:0!important;
            background: #5c6664;
            background-image: none;
            flex: 1;
            padding: 0 .5em;
            color:#fff;
            cursor:pointer;
            font-size: 1em;
            font-family: 'Open Sans', sans-serif;
            }
            select::-ms-expand {
            display: none;
            }
            .select {
            position: relative;
            display: flex;
            width: 20em;
            height: 3em;
            line-height: 3;
            background: #5c6664;
            overflow: hidden;
            border-radius: .25em;
            }
            .select::after {
            content: '\25BC';
            position: absolute;
            top: 0;
            right: 0;
            padding: 0 1em;
            background: #2b2e2e;
            cursor:pointer;
            pointer-events:none;
            transition:.25s all ease;
            }
            .select:hover::after {
            color: #23b499;
            }
            .button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .end-button {
            background-color: red;
            color: #fff;
            margin-left: 17%;
            font-size: 36px;
            margin-top: 20%;
            padding: 38px 75px;
        }
        #timer{
            font-weight: bold;
        }

        </style>
        </head>
        <?php  
            $query_npt = "SELECT * FROM ework_mrd_library WHERE LibraryName='NPT'";
            $result_npt = $conn->query($query_npt);
        ?>
<body>
<div class="container">
    <p style="text-align: center;font-size:20px">NPT time is Counting..</p>
    <form class="login-form"  id='end_npt' method="POST" >
    <input name="start_time"  type="hidden" value="<?php echo $_SESSION['npt_start_time'];?>" />
    <input name="idlines"  type="hidden" value="<?php echo $_GET['id'];?>" />
    <input name="cardnumber"  type="hidden" value="<?php echo $cardnumber;?>" />
    <input name="end_time"  type="hidden" value="<?php echo date('h:ia');?>" />
    <input name="npt_type"  type="hidden" value="end" />
    <div id="timer" style="padding: 21px;text-align: center;font-size: 25px;"></div>
    <div class="select">
    <select name="reason" id="reason">
        <option value="0" selected disabled>Choose a NPT</option>
        <?php
            foreach ($result_npt as $key => $npt) {
            ?>
        <option value="<?php echo $npt['Description']; ?>"><?php echo $npt['Description'].' - '.$npt['Code']; ?></option>
        <?php } ?>
    </select>
    
    </div>
    <button class="button end-button">End</button>
    </form>


</div>

<script>

$(document).ready(function () {
    var startTime = new Date(); // Set the start time to the current time

    function updateTimer() {
        var now = new Date();
        var diff = now - startTime;

        var hoursPassed = Math.floor(diff / (1000 * 60 * 60));
        var minutesPassed = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        var secondsPassed = Math.floor((diff % (1000 * 60)) / 1000);

        $('#timer').text(hoursPassed + 'h ' + minutesPassed + 'm ' + secondsPassed + 's');
    }

    updateTimer();
    setInterval(updateTimer, 1000);
});




            
    $(document).ready(function() {
        $("#end_npt").submit(function(ev) {
            ev.preventDefault();
            var reason = document.getElementById('reason').value;
            var formData = $(this).serialize(); // Get form data
           
            if (reason == 0) {
                alert('Please Select NPT Reason');
                return;
            }
         
            var url = 'npt_entry_store.php';
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    window.location.replace("passquantity_Operator.php");
                        // window.location.reload();
                },                    
                error: function(response) {
                    alert("Some Error Occurred");
                }
            });

        });
    });
</script>
</body>


