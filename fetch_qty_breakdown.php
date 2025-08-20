<?php
include 'db_connection.php'; // Include DB connection

// Check if docNumber is provided
if (isset($_GET['docNumber'])) {
    $docNumber = htmlspecialchars($_GET['docNumber']);

    // Fetch all data related to the docNumber
    $stmt = $conn->prepare("SELECT * FROM ework_qty_breakdown WHERE DocNumber = ?");
    $stmt->bind_param('s', $docNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = [
            "LineEntryDate" => $row['LineEntryDate'],
            "Quantity" => $row['Quantity'],
            "is_initial" => $row['is_initial'] // Ensure the 'is_initial' field is included in the response
        ];
    }

    echo json_encode($cartItems); // Return data as JSON

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "docNumber not provided."]);
}
?>
