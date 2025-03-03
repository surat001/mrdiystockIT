<?php
include('db_connect.php'); // เรียกใช้การเชื่อมต่อฐานข้อมูล

$sql = "SELECT 
            d.id AS device_id,
            d.barcode, 
            d.picture_url, 
            d.part_name, 
            s.qube_system, 
            s.waiting_to_receive, 
            s.quantities_on_hand, 
            s.process_adjust, 
            s.claim_warranty, 
            s.borrow, 
            s.damage, 
            s.lost_items, 
            r.remark, 
            m.min_calculated, 
            m.min_manual, 
            m.max_manual, 
            s.next_orders, 
            s.reserved, 
            s.used
        FROM devices d
        LEFT JOIN stock s ON d.id = s.device_id
        LEFT JOIN remarks r ON d.id = r.device_id
        LEFT JOIN min_max_settings m ON d.id = m.device_id";

$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
         // ✅ คำนวณค่า Quantities(On Hand)
         $row['quantities_on_hand'] = $row['qube_system'] 
         + $row['waiting_to_receive'] 
         - $row['process_adjust'] 
         - $row['claim_warranty'] 
         - $row['damage'] 
         - $row['borrow'];

         // ✅ คำนวณค่า Lost Items
         $row['lost_items'] = $row['qube_system'] 
         + $row['waiting_to_receive'] 
         - $row['quantities_on_hand'] 
         - $row['process_adjust'] 
         - $row['claim_warranty'] 
         - $row['borrow'] 
         + $row['borrow'] 
         - $row['damage'];


        // ✅ อัปเดตค่า quantities_on_hand และ lost_items ลงฐานข้อมูล
        if (!empty($row['device_id'])) {
            $update_sql = "UPDATE stock 
                           SET quantities_on_hand = '{$row['quantities_on_hand']}',
                               lost_items = '{$row['lost_items']}'
                           WHERE device_id = '{$row['device_id']}'";
            $conn->query($update_sql);
        }




        // อัปเดตค่า min_calculated ให้เท่ากับ quantities_on_hand
        $row['min_calculated'] = $row['quantities_on_hand'];

        // อัปเดตค่า min_calculated ในฐานข้อมูล
        if (!empty($row['device_id'])) {  // ✅ ตรวจสอบก่อนอัปเดต
            $update_sql = "UPDATE min_max_settings 
                           SET min_calculated = '{$row['quantities_on_hand']}'
                           WHERE device_id = '{$row['device_id']}'";
            $conn->query($update_sql);
        }
        

        // เพิ่มเงื่อนไขเปรียบเทียบ min_manual และ max_manual
        if ($row['quantities_on_hand'] > $row['max_manual']) {
            $row['status'] = 'high'; // มากกว่าค่า max_manual (สีเขียว)
           
        } elseif ($row['quantities_on_hand'] < $row['min_manual']) {
            $row['status'] = 'low'; // น้อยกว่าค่า min_manual (สีแดง)
            
        } else {
            $row['status'] = 'normal'; // อยู่ในช่วงปกติ
        }

        $products[] = $row;
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่ง JSON กลับไปที่ all_product.php
echo json_encode($products);
?>
