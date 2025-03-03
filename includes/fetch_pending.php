<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

$sql = "SELECT 
            document_number, 
            SUM(quantities) AS total_quantities, 
            GROUP_CONCAT(DISTINCT column_name SEPARATOR ', ') AS column_names, 
            status,
            status_balance,
            date,
            MAX(created_at) AS created_at 
        FROM pending 
        GROUP BY document_number 
        ORDER BY created_at DESC";

$result = $conn->query($sql);
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// ✅ ส่งข้อมูลออกเป็น JSON
echo json_encode($data);
?>
