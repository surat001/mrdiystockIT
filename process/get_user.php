<?php
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

// ดึงข้อมูลผู้ใช้
$sql = "
    SELECT 
        u.user_id, 
        u.name, 
        u.username, 
        u.group_id, 
        g.group_name 
    FROM 
        users u 
    LEFT JOIN 
        groups g 
    ON 
        u.group_id = g.id 
    WHERE 
        u.user_id = ?
";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$stmt->close();
$conn->close();
?>