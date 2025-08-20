<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docNumber = htmlspecialchars($_POST['docNumber']);
    $docDate = htmlspecialchars($_POST['docDate']);
    $workOrder = htmlspecialchars($_POST['workOrder']);
    $style = htmlspecialchars($_POST['style']);
    
    // Capture the initial quantity from the POST request
    $initialQuantity = htmlspecialchars($_POST['initialQuantity']);
    
    // Decode cart items
    $cartItems = json_decode($_POST['cartItems'], true);
    $totalQuantity = intval($_POST['totalQuantity']);

    // Step 1: Check if initial quantity already exists for the docNumber
    $checkQuery = $conn->prepare("SELECT * FROM ework_qty_breakdown WHERE DocNumber = ? AND is_initial = 1");
    $checkQuery->bind_param("s", $docNumber);
    $checkQuery->execute();
    $checkQuery->store_result();
    
    // Step 2: If no initial quantity is found, insert it with is_initial = 1
    if ($checkQuery->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO ework_qty_breakdown (DocNumber, DocDate, WorkOrder, Style, LineEntryDate, Quantity, is_initial) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $lineEntryDate = date("Y-m-d"); // Insert today's date
        $stmt->bind_param("sssssi", $docNumber, $docDate, $workOrder, $style, $lineEntryDate, $initialQuantity);

        if (!$stmt->execute()) {
            echo "Error inserting initial quantity: " . $stmt->error;
            exit;
        }

        $stmt->close();
    }

    // Step 3: Now, insert each cart item with is_initial = 0
    $stmt = $conn->prepare("INSERT INTO ework_qty_breakdown (DocNumber, DocDate, WorkOrder, Style, LineEntryDate, Quantity, is_initial) VALUES (?, ?, ?, ?, ?, ?, 0)");

    foreach ($cartItems as $item) {
        $lineEntryDate = $item['lineEntryDate'];
        $quantity = $item['quantity'];

        $stmt->bind_param("sssssi", $docNumber, $docDate, $workOrder, $style, $lineEntryDate, $quantity);

        if (!$stmt->execute()) {
            echo "Error inserting cart items: " . $stmt->error;
            exit;
        }
    }

    $stmt->close();
}

    // After inserting into the `ework_qty_breakdown` table, update the total quantity in the `ework_sales_order` table
    $updateSql = "UPDATE ework_sales_order SET quantity = ? WHERE docnumber = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("is", $totalQuantity, $docNumber); // Bind the totalQuantity and docNumber for the update

    if (!$updateStmt->execute()) {
        echo "Error updating total quantity in the ework_qty_breakdown` table: " . $updateStmt->error;
        exit;
    }

    // After inserting into the `ework_qty_breakdown` table, update the total quantity in the `eworker_assignment` table
    $updateSql_eworker_assignment = "UPDATE eworker_assignment SET quantity = ? WHERE docnumber = ?";
    $updateStmt_eworker_assignment = $conn->prepare($updateSql_eworker_assignment);
    $updateStmt_eworker_assignment->bind_param("is", $totalQuantity, $docNumber); // Bind the totalQuantity and docNumber for the update

    if (!$updateStmt_eworker_assignment->execute()) {
        echo "Error updating total quantity in the eworker_assignment table: " . $$updateStmt_eworker_assignment->error;
        exit;
    }

    // After inserting into the `ework_qty_breakdown` table, update the total quantity in the `ework_order_wise_color_size_qty` table
    $updateSql_WorderWiseColorQty = "UPDATE ework_order_wise_color_size_qty SET quantity = ? WHERE WO_docnumber = ?";
    $updateStmt_WorderWiseColorQty = $conn->prepare($updateSql_WorderWiseColorQty);
    $updateStmt_WorderWiseColorQty->bind_param("is", $totalQuantity, $docNumber); // Bind the totalQuantity and docNumber for the update

    if (!$updateStmt_WorderWiseColorQty->execute()) {
        echo "Error updating total quantity in the `ework_order_wise_color_size_qty` table: " . $updateStmt_WorderWiseColorQty->error;
        exit;
    }

    

    $updateStmt->close();
    $updateStmt_eworker_assignment->close();
    $updateStmt_WorderWiseColorQty->close();
    $conn->close();

    echo "Data saved and total quantity updated successfully.";




?>


