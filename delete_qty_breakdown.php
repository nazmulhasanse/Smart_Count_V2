<?php
session_start();
include 'db_connection.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docNumber = htmlspecialchars($_POST['docNumber']);
    $quantity = intval($_POST['quantity']); // The quantity to subtract and delete

    // Step 1: Subtract the quantity from the related tables before deletion

    // Update the `ework_sales_order` table
    $updateSalesOrder = $conn->prepare("UPDATE ework_sales_order SET quantity = quantity - ? WHERE docnumber = ?");
    $updateSalesOrder->bind_param("is", $quantity, $docNumber);

    if (!$updateSalesOrder->execute()) {
        echo "Error updating ework_sales_order: " . $updateSalesOrder->error;
        exit;
    }

    // Update the `eworker_assignment` table
    $updateWorkerAssignment = $conn->prepare("UPDATE eworker_assignment SET quantity = quantity - ? WHERE docnumber = ?");
    $updateWorkerAssignment->bind_param("is", $quantity, $docNumber);

    if (!$updateWorkerAssignment->execute()) {
        echo "Error updating eworker_assignment: " . $updateWorkerAssignment->error;
        exit;
    }

    // Update the `ework_order_wise_color_size_qty` table
    $updateColorSizeQty = $conn->prepare("UPDATE ework_order_wise_color_size_qty SET quantity = quantity - ? WHERE WO_docnumber = ?");
    $updateColorSizeQty->bind_param("is", $quantity, $docNumber);

    if (!$updateColorSizeQty->execute()) {
        echo "Error updating ework_order_wise_color_size_qty: " . $updateColorSizeQty->error;
        exit;
    }

    // Step 2: Now delete the quantity entry from the `ework_qty_breakdown` table
    $deleteQty = $conn->prepare("DELETE FROM ework_qty_breakdown WHERE DocNumber = ? AND Quantity = ?");
    $deleteQty->bind_param("si", $docNumber, $quantity);

    if (!$deleteQty->execute()) {
        echo "Error deleting quantity from ework_qty_breakdown: " . $deleteQty->error;
        exit;
    }

    $updateSalesOrder->close();
    $updateWorkerAssignment->close();
    $updateColorSizeQty->close();
    $deleteQty->close();
    $conn->close();

    echo "Quantity deleted and tables updated successfully.";
}
?>
