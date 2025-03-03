<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

$document_number = isset($_GET['document_number']) ? $_GET['document_number'] : '';

if (!$document_number) {
    echo json_encode(["status" => "error", "message" => "Document number is required"]);
    exit();
}

$sql = "SELECT 
            barcode, 
            part_name, 
            column_name, 
            SUM(quantities) AS total_quantities, 
            remark 
        FROM pending 
        WHERE document_number = ? 
        GROUP BY barcode, part_name, column_name, remark";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $document_number);
$stmt->execute();
$result = $stmt->get_result();
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
