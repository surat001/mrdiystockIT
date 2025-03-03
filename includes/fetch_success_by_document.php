<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

$document_number = $_GET['document_number'] ?? '';

if (empty($document_number)) {
    echo json_encode(["error" => "Missing document_number"]);
    exit;
}

$sql = "SELECT 
            part_name, 
            barcode, 
            column_name, 
            status_balance,
            SUM(quantities) AS total_quantities, 
            MAX(remark) AS remark,  
            COALESCE(NULLIF(MAX(date), '0000-00-00'), CURDATE()) AS date, 
            MAX(created_at) AS created_at,
            'success' AS status  -- ✅ เพิ่ม status
            
        FROM success 
        WHERE document_number = ? 
        GROUP BY part_name, barcode, column_name
        ORDER BY date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $document_number);
$stmt->execute();
$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $row['date'] = date("Y-m-d", strtotime($row['date']));
    $data[] = $row;
}

// ถ้าไม่มีข้อมูล ให้ส่ง JSON กลับไปแจ้งเตือน
if (empty($data)) {
    echo json_encode([]);
} else {
    echo json_encode($data);
}
?>
