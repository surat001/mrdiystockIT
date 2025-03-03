<?php
header('Content-Type: application/json');

try {
    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "mrdiyit");
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // รับข้อมูลจาก URL
    $groupId = $_GET['id'] ?? '';

    // ตรวจสอบค่าที่จำเป็น
    if (empty($groupId)) {
        throw new Exception('Group ID is required.');
    }

    // ลบข้อมูลในตาราง groups
    $sqlDeleteGroup = "DELETE FROM groups WHERE id = ?";
    $stmtDeleteGroup = $conn->prepare($sqlDeleteGroup);
    if (!$stmtDeleteGroup) {
        throw new Exception('Error preparing delete statement: ' . $conn->error);
    }

    $stmtDeleteGroup->bind_param("i", $groupId);
    if (!$stmtDeleteGroup->execute()) {
        throw new Exception('Error deleting group: ' . $stmtDeleteGroup->error);
    }

    echo json_encode(['status' => 'success', 'message' => 'Group deleted successfully!']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // ปิดการเชื่อมต่อ
    if ($conn) {
        $conn->close();
    }
}
?>