<?php
header('Content-Type: application/json');

// ตรวจสอบคำขอ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $userId = $_POST['editId'] ?? '';
    $name = trim($_POST['editName'] ?? '');
    $username = trim($_POST['editUsername'] ?? '');
    $password = $_POST['editPassword'] ?? '';
    $group = $_POST['editGroup'] ?? '';

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "mrdiyit");
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    // ✅ ตรวจสอบว่ามีผู้ใช้นี้อยู่หรือไม่
    $checkUserStmt = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    $checkUserStmt->bind_param('i', $userId);
    $checkUserStmt->execute();
    $resultUser = $checkUserStmt->get_result();

    if ($resultUser->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    // ✅ ตรวจสอบ `name` และ `username` ซ้ำ (ยกเว้นของตัวเอง)
    $checkDuplicateStmt = $conn->prepare('SELECT * FROM users WHERE (username = ? OR name = ?) AND user_id != ?');
    $checkDuplicateStmt->bind_param('ssi', $username, $name, $userId);
    $checkDuplicateStmt->execute();
    $resultDuplicate = $checkDuplicateStmt->get_result();

    if ($resultDuplicate->num_rows > 0) {
        $existingUser = $resultDuplicate->fetch_assoc();
        if ($existingUser['username'] === $username) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
        } elseif ($existingUser['name'] === $name) {
            echo json_encode(['status' => 'error', 'message' => 'Name already exists.']);
        }
        exit;
    }

    // ✅ อัปเดตข้อมูลผู้ใช้
    if (!empty($password)) {
        // เข้ารหัสรหัสผ่านใหม่
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare('UPDATE users SET name = ?, username = ?, password = ?, group_id = ? WHERE user_id = ?');
        $stmt->bind_param('ssssi', $name, $username, $hashedPassword, $group, $userId);
    } else {
        $stmt = $conn->prepare('UPDATE users SET name = ?, username = ?, group_id = ? WHERE user_id = ?');
        $stmt->bind_param('sssi', $name, $username, $group, $userId);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    // ✅ ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $checkUserStmt->close();
    $checkDuplicateStmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
