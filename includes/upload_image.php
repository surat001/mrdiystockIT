<?php
header('Content-Type: application/json');

$targetDir = __DIR__ . "/../uploads/"; // โฟลเดอร์อยู่ที่ระดับ root directory

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$response = ["status" => "error"];

if (!empty($_FILES['file']['name'])) {
    $fileName = time() . "_" . basename($_FILES['file']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        $response = ["status" => "success", "filePath" => "uploads/" . $fileName]; // ให้ path กลับมาแบบ relative
    } else {
        $response = ["status" => "error", "message" => "File upload failed"];
    }
} else {
    $response = ["status" => "error", "message" => "No file uploaded"];
}

echo json_encode($response);
?>
