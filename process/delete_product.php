<?php
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล
session_start(); // ตรวจสอบ session

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barcode = isset($_POST['barcode']) ? $_POST['barcode'] : null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!$barcode) {
        echo json_encode(["status" => "error", "message" => "No barcode received"]);
        exit();
    }
    if (!$user_id) {
        echo json_encode(["status" => "error", "message" => "User not authenticated"]);
        exit();
    }

    // ✅ Debug Log
    error_log("✅ DEBUG: Received barcode: $barcode, User ID: $user_id");

    // ปิด Foreign Key Check ชั่วคราว
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

    // 🔹 ดึงข้อมูลอุปกรณ์
    $query = "SELECT * FROM devices WHERE barcode = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare statement failed: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $device = $result->fetch_assoc();
    $stmt->close();

    if (!$device) {
        echo json_encode(["status" => "error", "message" => "Device not found with barcode: " . $barcode]);
        exit();
    }

    $device_id = $device['id'];
    $picture_url = $device['picture_url'];

    // 🔹 ดึงข้อมูลจากตารางที่เกี่ยวข้อง
    $related_tables = ["min_max_settings", "remarks", "stock"];
    $log_data = [];

    foreach ($related_tables as $table_name) {
        $sql = "SELECT * FROM $table_name WHERE device_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Prepare statement failed ($table_name): " . $conn->error]);
            exit();
        }
        $stmt->bind_param("i", $device_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $log_data[$table_name] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    // 🔹 บันทึกข้อมูลลง `stock_logs`
    $old_value = json_encode([
        "devices" => $device,
        "related_data" => $log_data
    ], JSON_PRETTY_PRINT);

    $field_name = "Deleted Device";
    $new_value = null;
    $remarks = "Deleted by User ID: $user_id";

    $stock_log_query = "INSERT INTO stock_logs (device_id, user_id, edited_at, field_name, old_value, new_value, remarks)
                        VALUES (?, ?, NOW(), ?, ?, ?, ?)";
    $stock_log_stmt = $conn->prepare($stock_log_query);
    if (!$stock_log_stmt) {
        echo json_encode(["status" => "error", "message" => "Stock log prepare failed: " . $conn->error]);
        exit();
    }

    $stock_log_stmt->bind_param("isssss", $device_id, $user_id, $field_name, $old_value, $new_value, $remarks);
    
    if (!$stock_log_stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Error inserting stock log: " . $stock_log_stmt->error]);
        exit();
    }
    $stock_log_stmt->close();

    // 🔹 ลบรูปภาพจากโฟลเดอร์ uploads/
    if (!empty($picture_url)) {
        $file_path = '../uploads/' . basename($picture_url);
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // 🔹 ลบข้อมูลจากตารางที่เกี่ยวข้อง
    foreach ($related_tables as $table_name) {
        $delete_sql = "DELETE FROM $table_name WHERE device_id = ?";
        $stmt = $conn->prepare($delete_sql);
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Delete prepare failed ($table_name): " . $conn->error]);
            exit();
        }
        $stmt->bind_param("i", $device_id);
        if (!$stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Error deleting from $table_name: " . $stmt->error]);
            exit();
        }
        $stmt->close();
    }

    // 🔹 ลบข้อมูลสินค้าออกจาก `devices`
    $delete_query = "DELETE FROM devices WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Device delete prepare failed: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $device_id);
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Error deleting product: " . $stmt->error]);
        exit();
    }
    $stmt->close();

    // เปิด Foreign Key Check กลับมา
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

    echo json_encode(["status" => "success", "message" => "Product and related data deleted, and stock log updated successfully!"]);
    $conn->close();
}
?>
