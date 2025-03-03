<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

$sql = "SELECT 
            document_number, 
            column_name, 
            SUM(quantities) AS total_quantities, 
            status_balance,
            MAX(date) AS date, 
            'success' AS status, 
            do_no,
            MAX(created_at) AS created_at 
        FROM success 
        GROUP BY document_number 
        ORDER BY date DESC";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// ✅ ส่งข้อมูลออกเป็น JSON
echo json_encode($data);
?>
