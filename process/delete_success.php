<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $document_id = isset($_POST['document_id']) ? $conn->real_escape_string($_POST['document_id']) : '';

    if (empty($document_id)) {
        echo json_encode(["status" => "error", "message" => "Missing document_id"]);
        exit;
    }

    // ✅ เริ่ม Transaction
    $conn->begin_transaction();
    try {
        // ❌ ลบข้อมูลทั้งหมดที่มี document_number เดียวกัน
        $sql = "DELETE FROM success WHERE document_number = '$document_id'";
        if (!$conn->query($sql)) {
            throw new Exception("Error deleting record: " . $conn->error);
        }

        // ✅ Commit ถ้าทำสำเร็จ
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Document $document_id deleted successfully!"]);

    } catch (Exception $e) {
        // ❌ Rollback ถ้ามีปัญหา
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>