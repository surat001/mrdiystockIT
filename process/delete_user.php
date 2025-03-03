<?php
// ตั้งค่า header ให้ส่ง JSON
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "mrdiyit");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// ตรวจสอบว่าได้รับ user_id หรือไม่
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int) $_GET['id'];
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

// สร้าง query สำหรับลบผู้ใช้
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// ผูกค่ากับ statement
$stmt->bind_param('i', $userId);

// ลบข้อมูล
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>