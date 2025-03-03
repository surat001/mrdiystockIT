<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json");

include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockData = json_decode($_POST['stockData'], true);

    if (empty($stockData)) {
        echo json_encode(["status" => "error", "message" => "No data received"]);
        exit;
    }

    // ✅ เริ่ม Transaction
    $conn->begin_transaction();

    try {
        // ✅ สร้าง `document_number` ใหม่ตามเดือน-ปี
        $month_abbr = strtoupper(date("M")); // เช่น "FEB"
        $year_abbr = date("y");  // ปี ค.ศ. 2 หลัก เช่น 25 (สำหรับ 2025)
        $doc_prefix = $month_abbr . $year_abbr;

        // ✅ ดึง `document_number` ล่าสุดจากทั้ง `pending` และ `success`
        $sql = "SELECT document_number FROM (
                    SELECT document_number FROM pending WHERE document_number LIKE '$doc_prefix%'
                    UNION
                    SELECT document_number FROM success WHERE document_number LIKE '$doc_prefix%'
                ) AS combined
                ORDER BY LENGTH(document_number) DESC, document_number DESC LIMIT 1";

        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()) {
            // ✅ หาหมายเลขล่าสุดของ document_number
            preg_match('/-(\d+)$/', $row['document_number'], $matches);
            if (isset($matches[1])) {
                $next_number = intval($matches[1]) + 1;
            } else {
                $next_number = 1;
            }
        } else {
            // ✅ ถ้ายังไม่มี ให้เริ่มที่ `001`
            $next_number = 1;
        }

        // ✅ กำหนด `document_number` เช่น `FEB25-001`, `FEB25-002`, `FEB25-003`
        $doc_number = "$doc_prefix-" . str_pad($next_number, 3, "0", STR_PAD_LEFT);

        // ✅ ตรวจสอบว่า `document_number` ซ้ำหรือไม่
        while (true) {
            $check_existing = $conn->query("SELECT 1 FROM pending WHERE document_number = '$doc_number' 
                                            UNION 
                                            SELECT 1 FROM success WHERE document_number = '$doc_number'");
            if ($check_existing->num_rows > 0) {
                // ✅ ถ้ามี `document_number` นี้แล้ว ให้เพิ่มเลข
                $next_number++;
                $doc_number = "$doc_prefix-" . str_pad($next_number, 3, "0", STR_PAD_LEFT);
            } else {
                break;
            }
        }

        $barcodes_in_doc = []; // เก็บ barcode ที่ใช้ใน document นี้

        foreach ($stockData as $data) {
            if (empty($data['barcode']) || empty($data['part_name']) || empty($data['quantities']) || empty($data['column_name'])) {
                throw new Exception("Missing required fields");
            }

            $barcode = $conn->real_escape_string($data['barcode']);
            $part_name = $conn->real_escape_string($data['part_name']);
            $quantities = intval($data['quantities']);
            $column_name = $conn->real_escape_string($data['column_name']);
            $remark = $conn->real_escape_string($data['remark']);

             // ✅ กำหนดค่า status_balance ตาม column_name
             if ($column_name === "Waiting to Receive") {
                $status_balance = "Receive";
            } elseif ($column_name === "Process Adjust") {
                $status_balance = "Adjustment";
            } else {
                $status_balance = "";
            }

            // ✅ ตรวจสอบว่า `barcode` และ `part_name` มีอยู่ใน `devices`
            $check_device = $conn->query("SELECT id FROM devices WHERE barcode = '$barcode' AND part_name = '$part_name'");
            if ($check_device->num_rows == 0) {
                throw new Exception("❌ Barcode or Part Name not found in devices: " . $barcode);
            }

            // ✅ บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO pending (barcode, part_name, quantities, document_number, column_name, remark, status,status_balance, created_at)
                    VALUES ('$barcode', '$part_name', '$quantities', '$doc_number', '$column_name', '$remark', 'pending', '$status_balance', NOW())";

            if (!$conn->query($sql)) {
                throw new Exception("❌ Error inserting data: " . $conn->error);
            }
        }

        // ✅ ถ้าสำเร็จทั้งหมดให้ Commit
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "✅ Data saved successfully!", "document_number" => $doc_number]);

    } catch (Exception $e) {
        // ❌ ถ้ามีข้อผิดพลาดให้ Rollback
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
