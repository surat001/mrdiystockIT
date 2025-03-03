<?php
header("Content-Type: application/json");
include '../includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockData = json_decode(file_get_contents("php://input"), true);

    if (empty($stockData['stockData'])) {
        echo json_encode(["status" => "error", "message" => "❌ No data received"]);
        exit;
    }

    $conn->begin_transaction();
    try {
        $new_doc_number = ""; // ตัวแปรเก็บ document_number ใหม่

        foreach ($stockData['stockData'] as $data) {
            $barcode = $conn->real_escape_string($data['barcode']);
            $part_name = $conn->real_escape_string($data['part_name']);
            $quantities = intval($data['quantities']);
            $document_number = $conn->real_escape_string($data['document_number']);
            $column_name = $conn->real_escape_string($data['column_name']);
            $remark = $conn->real_escape_string($data['remark']);
            $selected_date = !empty($data['selected_date']) ? $conn->real_escape_string($data['selected_date']) : NULL;

            // ✅ ดึงค่า quantities เดิมจากตาราง pending
            $sql_pending = "SELECT quantities FROM pending WHERE document_number = ? AND barcode = ?";
            $stmt_pending = $conn->prepare($sql_pending);
            $stmt_pending->bind_param("ss", $document_number, $barcode);
            $stmt_pending->execute();
            $result_pending = $stmt_pending->get_result();

            if ($row_pending = $result_pending->fetch_assoc()) {
                $original_quantities = intval($row_pending['quantities']);

                if ($quantities < $original_quantities) {
                    // ✅ คำนวณค่าที่ต้องบันทึกลง `pending` และ `success`
                    $remaining_quantity = $original_quantities - $quantities;

                    // ✅ แยก `document_number` และหาหมายเลขสูงสุด
                    if (empty($new_doc_number)) {
                        $base_doc = preg_replace('/#\d+$/', '', $document_number); // ตัด `#` ออกจากท้าย เช่น `FEB25-002`
                        $prefix = $base_doc . "#"; // เช่น `FEB25-002#`

                        // ✅ ค้นหาหมายเลขสูงสุดของเอกสารที่มีอยู่
                        $check_sql = "SELECT document_number FROM pending WHERE document_number LIKE ? 
                                      UNION 
                                      SELECT document_number FROM success WHERE document_number LIKE ?
                                      ORDER BY LENGTH(document_number) DESC, document_number DESC LIMIT 1";
                        $stmt_check = $conn->prepare($check_sql);
                        $prefix_like = $prefix . "%";
                        $stmt_check->bind_param("ss", $prefix_like, $prefix_like);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();

                        if ($row_check = $result_check->fetch_assoc()) {
                            preg_match('/#(\d+)$/', $row_check['document_number'], $matches);
                            $last_number = isset($matches[1]) ? intval($matches[1]) : 0;
                            $new_doc_number = $prefix . ($last_number + 1);
                        } else {
                            $new_doc_number = $prefix . "1"; // ถ้ายังไม่มีให้เริ่มจาก #1
                        }
                    }

                    // ✅ ตรวจสอบค่า `status_balance`
                    if ($column_name === "Waiting to Receive") {
                        $status_balance = "Receive";
                    } elseif ($column_name === "Process Adjust") {
                        $status_balance = "Adjustment";
                    } else {
                        $status_balance = ""; // ✅ ใช้ string ว่างแทน NULL   
                    }

                    // ✅ บันทึกค่าเหลือใน `pending`
                    $sql_insert_pending = "INSERT INTO pending (barcode, part_name, quantities, document_number, column_name, remark, status, status_balance, created_at)
                                VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, NOW())";
                    $stmt_insert_pending = $conn->prepare($sql_insert_pending);
                    $stmt_insert_pending->bind_param("ssissss", $barcode, $part_name, $remaining_quantity, $new_doc_number, $column_name, $remark, $status_balance);
                    $stmt_insert_pending->execute();
                }
            }

            // ✅ ตรวจสอบว่า `selected_date` มีค่าหรือไม่
            if (!empty($selected_date)) {
                $dateObj = new DateTime($selected_date); // แปลงวันที่เป็น DateTime
                $month = (int) $dateObj->format("m"); // ดึงค่าเดือน (1-12)
                $years = (int) $dateObj->format("Y"); // ดึงค่าปี (2025 เป็นต้นไป)
                $week = (int) $dateObj->format("W");  // ดึงค่าสัปดาห์ (ISO-8601, เริ่มที่วันจันทร์)
            } else {
                $month = NULL;
                $years = NULL;
                $week = NULL;
            }

            // ✅ บันทึกค่าที่ใช้ไปลง `success`
            $sql_insert_success = "INSERT INTO success (barcode, part_name, quantities, document_number, column_name, remark, status, status_balance, date, created_at, Month, Years, Week)
                                   VALUES (?, ?, ?, ?, ?, ?, 'success', ?, ?, NOW(), ?, ?, ?)";
            $stmt_insert_success = $conn->prepare($sql_insert_success);

            if (!$stmt_insert_success) {
                throw new Exception("❌ Prepare failed: " . $conn->error);
            }

            // ✅ ตรวจสอบค่า `status_balance`
            if ($column_name === "Waiting to Receive") {
                $status_balance = "Receive";
            } elseif ($column_name === "Process Adjust") {
                $status_balance = "Adjustment";
            } else {
                $status_balance = ""; // ✅ ใช้ string ว่างแทน NULL
            }

            $stmt_insert_success->bind_param("ssisssssiii", $barcode, $part_name, $quantities, $document_number, $column_name, $remark, $status_balance, $selected_date, $month, $years, $week);

            if (!$stmt_insert_success->execute()) {
                throw new Exception("❌ Execute failed: " . $stmt_insert_success->error);
            }

            // ✅ ลบข้อมูลจาก pending เฉพาะ `document_number` และ `barcode` ที่ถูกบันทึก
            $sql_delete_pending = "DELETE FROM pending WHERE document_number = ? AND barcode = ?";
            $stmt_delete_pending = $conn->prepare($sql_delete_pending);
            $stmt_delete_pending->bind_param("ss", $document_number, $barcode);
            $stmt_delete_pending->execute();
        }

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "✅ Data saved successfully!", "document_number" => $document_number]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
}
?>