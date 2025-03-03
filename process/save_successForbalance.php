<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockData = json_decode($_POST['stockData'], true);

    if (empty($stockData)) {
        echo json_encode(["status" => "error", "message" => "❌ No data received"]);
        exit;
    }

    // ✅ Log ค่าที่ได้รับ (Debugging)
    file_put_contents("log.txt", print_r($stockData, true));

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
            preg_match('/-(\d+)$/', $row['document_number'], $matches);
            $next_number = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        } else {
            $next_number = 1;
        }

        // ✅ กำหนด `document_number` เช่น `FEB25-001`
        $doc_number = "$doc_prefix-" . str_pad($next_number, 3, "0", STR_PAD_LEFT);

        // ✅ ตรวจสอบว่า `document_number` ซ้ำหรือไม่
        while (true) {
            $check_existing = $conn->query("SELECT 1 FROM pending WHERE document_number = '$doc_number' 
                                            UNION 
                                            SELECT 1 FROM success WHERE document_number = '$doc_number'");
            if ($check_existing->num_rows > 0) {
                $next_number++;
                $doc_number = "$doc_prefix-" . str_pad($next_number, 3, "0", STR_PAD_LEFT);
            } else {
                break;
            }
        }

        foreach ($stockData as $data) {
            if (empty($data['barcode']) || empty($data['part_name']) || empty($data['quantities']) || empty($data['column_name'])) {
                throw new Exception("❌ Missing required fields");
            }

            // ✅ กำหนดค่า
            $barcode = $conn->real_escape_string($data['barcode']);
            $part_name = $conn->real_escape_string($data['part_name']);
            $quantities = intval($data['quantities']);
            $column_name = $conn->real_escape_string($data['column_name']);
            $remark = $conn->real_escape_string($data['remark'] ?? "");
            $do_no = $conn->real_escape_string($data['do_no'] ?? "");
            $doc_no = $conn->real_escape_string($data['doc_no'] ?? "");
            $rq_date = !empty($data['rq_date']) ? $conn->real_escape_string($data['rq_date']) : NULL;
            $inv_no = $conn->real_escape_string($data['inv_no'] ?? "");
            $store = $conn->real_escape_string($data['store'] ?? "");
            $outlets = $conn->real_escape_string($data['outlets'] ?? "");
            $cost = !empty($data['cost']) ? floatval($data['cost']) : 0.00;

            $addS1 = $conn->real_escape_string($data['addS1'] ?? "");
            $addS2 = $conn->real_escape_string($data['addS2'] ?? "");
            $addS3 = $conn->real_escape_string($data['addS3'] ?? "");

            // ✅ แปลง `rq_date` เป็น **เดือน, ปี, สัปดาห์**
            if (!empty($rq_date)) {
                $rq_timestamp = strtotime($rq_date);
                $month = intval(date("m", $rq_timestamp));  // เดือน (01-12)
                $year = intval(date("Y", $rq_timestamp));  // ปี ค.ศ. 4 หลัก
                $week = intval(date("W", $rq_timestamp));  // สัปดาห์ที่

                // 🔹 ปรับให้สัปดาห์เริ่มที่วันจันทร์
                if (date("N", strtotime("$year-01-01")) > 4) {
                    $week--;
                }
            } else {
                $month = 0;
                $year = 0;
                $week = 0;
            }

            // ✅ ตรวจสอบว่า `barcode` และ `part_name` มีอยู่ใน `devices`
            $check_device = $conn->query("SELECT id FROM devices WHERE barcode = '$barcode' AND part_name = '$part_name'");
            if ($check_device->num_rows == 0) {
                throw new Exception("❌ Barcode or Part Name not found in devices: " . $barcode);
            }

            // ✅ บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO success
                    (barcode, part_name, quantities, document_number, column_name, remark, status, status_balance, do_no, doc_no, date, inv_no, store, outlets, cost, addS1, addS2, addS3, Month, Years, Week, created_at) 
                    VALUES (?, ?, ?, ?, '', ?, 'success', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $conn->prepare($sql);
            $stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL Prepare Failed: " . $conn->error);
}

            $stmt->bind_param(
                "ssisssssssssssssiii",
                $barcode, $part_name, $quantities, $doc_number, $remark, 
                $column_name, $do_no, $doc_no, $rq_date, $inv_no, $store, $outlets, $cost,
                $addS1, $addS2, $addS3, $month, $year, $week
            );

            if (!$stmt->execute()) {
                throw new Exception("❌ Error inserting data: " . $stmt->error);
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
