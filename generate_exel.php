<?php
require 'vendor/autoload.php'; // Include Composer autoload file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Database connection
include 'db_connection.php';

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$headers = [
    'cardnumber', 'name', 'password', 'position', 'Customer', 
    'WorkOrder', 'Style', 'StepNumber', 'Description_StepName', 
    'quantity', 'docnumber', 'line'
];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Fetch data from eworker_assignment
$sql = "SELECT * FROM eworker_assignment WHERE WorkerActive = 1 AND StepActive = 1 AND docstatus = 1";
$result = $conn->query($sql);

$rowCount = 2;
while ($row = $result->fetch_assoc()) {
    $cardnumber = $row['cardnumber'];
    $name = $row['name'];
    $position = $row['position'];
    $Customer = $row['Customer'];
    $WorkOrder = $row['WorkOrder'];
    $Style = $row['Style'];
    $StepNumber = $row['StepNumber'];
    $Description_StepName = $row['Description_StepName'];
    $quantity = $row['quantity'];
    $docnumber = $row['docnumber'];
    $line = $row['line'];

    // Fetch password from ework_workers
    $sql_worker = "SELECT password FROM ework_workers WHERE cardnumber = '$cardnumber'";
    $result_worker = $conn->query($sql_worker);
    $password = $result_worker->fetch_assoc()['password'];

    $sheet->setCellValue('A' . $rowCount, $cardnumber);
    $sheet->setCellValue('B' . $rowCount, $name);
    $sheet->setCellValue('C' . $rowCount, $password);
    $sheet->setCellValue('D' . $rowCount, $position);
    $sheet->setCellValue('E' . $rowCount, $Customer);
    $sheet->setCellValue('F' . $rowCount, $WorkOrder);
    $sheet->setCellValue('G' . $rowCount, $Style);
    $sheet->setCellValue('H' . $rowCount, $StepNumber);
    $sheet->setCellValue('I' . $rowCount, $Description_StepName);
    $sheet->setCellValue('J' . $rowCount, $quantity);
    $sheet->setCellValue('K' . $rowCount, $docnumber);
    $sheet->setCellValue('L' . $rowCount, $line);

    $rowCount++;
}

// Write to file
$writer = new Xlsx($spreadsheet);
$filename = 'Workers_Assignment_Data.xlsx';
$writer->save($filename);

echo "Excel file created: " . $filename;

// Close database connection
$conn->close();
?>
