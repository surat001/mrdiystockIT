<?php
// ตรวจสอบคำขอ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $group = $_POST['group_name'] ?? '';

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "mrdiyit");
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
    }

    // ตรวจสอบว่า Name หรือ Username ซ้ำหรือไม่
    $checkStmt = $conn->prepare('SELECT * FROM users WHERE username = ? OR name = ?');
    $checkStmt->bind_param('ss', $username, $name);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // ตรวจสอบว่าซ้ำเพราะอะไร
        $existingUser = $result->fetch_assoc();
        if ($existingUser['username'] === $username) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
        } elseif ($existingUser['name'] === $name) {
            echo json_encode(['status' => 'error', 'message' => 'Name already exists.']);
        }
    } else {
        // เข้ารหัสรหัสผ่าน
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // เพิ่มข้อมูลลงในฐานข้อมูล
        $stmt = $conn->prepare('INSERT INTO users (name, username, password, group_id) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $username, $hashedPassword, $group);

        if ($stmt->execute()) {
            // หลังจากเพิ่มผู้ใช้สำเร็จ จะส่งข้อมูลของผู้ใช้ใหม่กลับไป
            $newUserId = $stmt->insert_id;  // ได้ ID ของผู้ใช้ใหม่ที่เพิ่ม
            echo json_encode([
                'status' => 'success',
                'message' => 'User added successfully!',
                'newUser' => [
                    'user_id' => $newUserId,
                    'name' => $name,
                    'username' => $username,
                    'group' => $group
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
