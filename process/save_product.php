<?php
include('../includes/db_connect.php');

header('Content-Type: application/json'); // บอกว่า Response เป็น JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barcode = $conn->real_escape_string($_POST['barcode']);
    $part_name = $conn->real_escape_string($_POST['part_name']);
    $set_balance = $conn->real_escape_string($_POST['set_balance']);
    $picture_url = $conn->real_escape_string($_POST['image_path']);

    // 🔍 ตรวจสอบว่ามี Barcode หรือ Part Name ซ้ำหรือไม่
    $check_sql = "SELECT id FROM devices WHERE barcode = '$barcode' OR part_name = '$part_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Barcode or Part Name already exists!"]);
        exit;
    }

    // ✅ เริ่ม Transaction
    $conn->begin_transaction();

    try {
        // ✅ 1. บันทึกข้อมูลลงตาราง devices
        $sql_device = "INSERT INTO devices (barcode, part_name, set_balance, picture_url) 
                       VALUES ('$barcode', '$part_name', '$set_balance', '$picture_url')";
        if (!$conn->query($sql_device)) {
            throw new Exception("Database Error: " . $conn->error);
        }

        // 📌 ดึง device_id ที่เพิ่งสร้าง
        $device_id = $conn->insert_id;

        // ✅ 2. บันทึกลงตาราง stock
        $sql_stock = "INSERT INTO stock (device_id, qube_system, waiting_to_receive, quantities_on_hand, process_adjust, claim_warranty, borrow, damage, lost_items, next_orders, reserved, used)
                      VALUES ('$device_id', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
        if (!$conn->query($sql_stock)) {
            throw new Exception("Stock Insert Error: " . $conn->error);
        }

        // ✅ 3. บันทึกลงตาราง remarks
        $sql_remarks = "INSERT INTO remarks (device_id, remark) VALUES ('$device_id', '')";
        if (!$conn->query($sql_remarks)) {
            throw new Exception("Remarks Insert Error: " . $conn->error);
        }

        // ✅ 4. บันทึกลงตาราง min_max_settings
        $sql_min_max = "INSERT INTO min_max_settings (device_id, min_calculated, min_manual, max_manual)
                        VALUES ('$device_id', 0, 0, 0)";
        if (!$conn->query($sql_min_max)) {
            throw new Exception("Min-Max Insert Error: " . $conn->error);
        }

        // ✅ ถ้าทุกอย่างสำเร็จ ให้ COMMIT
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "Product added successfully!"]);
    } catch (Exception $e) {
        // ❌ ถ้าเกิดข้อผิดพลาด ให้ ROLLBACK ข้อมูล
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    $conn->close();
}
?>
