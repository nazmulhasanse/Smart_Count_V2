<?php
include './ie/db_connection.php'; 
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardnumber = $_POST["id"]; // Updated variable name
    $password = $_POST["password"];

    // Sanitize user input to prevent SQL injection
    $cardnumber = $conn->real_escape_string($cardnumber);

    // Set the timezone to Bangladesh
    date_default_timezone_set('Asia/Dhaka');

    // Get the current date and time
    $currentDateTime = date('Y-m-d H:i:s');

    // Perform a database query to check if the user exists and the password matches
    $query = "SELECT *
    FROM
        eworker_assignment AS C
    LEFT JOIN
        ework_workers AS A ON C.cardnumber = A.cardnumber
    LEFT JOIN
        ework_sales_order AS B ON C.WorkOrder = B.WorkOrder
    WHERE
        C.cardnumber = '$cardnumber'
        AND C.WorkOrder = B.WorkOrder
        AND B.docstatus = 1
        AND C.WorkerActive = 1
        AND C.StepActive = 1
        AND C.FinalStep = 1";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashed_password = md5($password);

        if ($hashed_password === $user['password']) {
            $_SESSION['username'] = $cardnumber;
            $_SESSION['cardnumber'] = $cardnumber;
            $_SESSION['loginDatetime'] = $currentDateTime;
            header("Location: bulk_status.php");
        } else {
            $_SESSION['error'] = "Invalid ID or Password";
            header("Location: index.php");
        }
    } else{

        $sql = "SELECT
        B.WorkOrder AS WorkOrder,
        B.Style AS Style,
        C.StepNumber AS Step,
        C.Description_StepName AS Step_Description,
        B.Customer,
        B.ColorSizeEntry AS ColorSizeEntry,
        B.line AS SewingLine,
        A.Position AS Position,
        B.docnumber as docnumber,
        C.cardnumber AS CardNo,
        C.name AS Name
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
        AND A.Position = 2
        GROUP BY C.idlines;
        ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            $_SESSION['username'] = $cardnumber;
            $_SESSION['cardnumber'] = $cardnumber;
            $_SESSION['loginDatetime'] = $currentDateTime;
            header("Location: status.php");
            exit;
        }else{
            $_SESSION['error'] = "Invalid ID or Password";
            header("Location: index.php");
        }
    } 

    // Close the database connection
    $conn->close();
}
?>
