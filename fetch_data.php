<?php
// Include database connection file
include_once("db_connect.php");

// Parameters for pagination
$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];

// Query to fetch data
$sql = "SELECT
       SUBSTRING(`wp`.`DateTime`, 1, 10) AS `DateTime`,
       `wa`.`site`,
       `wa`.`building`,
       `wa`.`floor`,
       `wp`.`SewingLine`,
       `wp`.`WorkOrder`,
       `wa`.`Customer`,
       `wa`.`Style`,
       `wa`.`StyleDescription`,
       `te`.`WorkingHour`,
       `te`.`TargetEfficiency`,
       `te`.`ActualWorkingHour`,
       `wa`.`quantity`,
       `wa`.`docnumber`
       FROM
         `eworker_operation` `wp`
         LEFT JOIN `ework_target_efficiency` `te` ON `wp`.`SewingLine` = `te`.`SewingLine` AND `wp`.`WorkOrder` = `te`.`WorkOrder` AND `wp`.`Style` = `te`.`Style` AND SUBSTRING(`wp`.`DateTime`, 1, 10) = SUBSTRING(`te`.`Date`, 1, 10)
         LEFT JOIN `eworker_assignment` `wa` ON `wp`.`docnumber` = `wa`.`docnumber`
       WHERE
         (`DateTime` LIKE '%".$search."%' OR
         `site` LIKE '%".$search."%' OR
         `building` LIKE '%".$search."%' OR
         `floor` LIKE '%".$search."%' OR
         `SewingLine` LIKE '%".$search."%' OR
         `WorkOrder` LIKE '%".$search."%' OR
         `Customer` LIKE '%".$search."%' OR
         `Style` LIKE '%".$search."%' OR
         `StyleDescription` LIKE '%".$search."%' OR
         `WorkingHour` LIKE '%".$search."%' OR
         `TargetEfficiency` LIKE '%".$search."%' OR
         `ActualWorkingHour` LIKE '%".$search."%' OR
         `quantity` LIKE '%".$search."%' OR
         `docnumber` LIKE '%".$search."%')
       GROUP BY SUBSTRING(`wp`.`DateTime`, 1, 10), `wp`.`docnumber`
       ORDER BY SUBSTRING(`wp`.`DateTime`, 1, 10) DESC
       LIMIT ".$start.", ".$length;

$result = mysqli_query($conn, $sql);
$data = array();

while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
}

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM eworker_operation";
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['totalRecords'];

// Total records with filtering
$totalFiltered = mysqli_num_rows($result);

// Prepare JSON response
$json_data = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>