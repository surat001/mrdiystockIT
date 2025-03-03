<?php
header('Content-Type: application/json');

// รับค่าจาก JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$filePath = isset($data['filePath']) ? $data['filePath'] : '';

if (!$filePath) {
    echo json_encode(["status" => "error", "message" => "No file path provided."]);
    exit;
}

// แก้พาธให้ถูกต้อง โดยใช้ __DIR__ และกลับไปโฟลเดอร์หลัก
$fullPath = __DIR__ . "/../uploads/" . basename($filePath); 

error_log("Trying to delete: " . $fullPath);

if (!file_exists($fullPath)) {
    echo json_encode(["status" => "error", "message" => "File not found. Full path: " . $fullPath]);
    exit;
}

if (unlink($fullPath)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete file."]);
}
?>
