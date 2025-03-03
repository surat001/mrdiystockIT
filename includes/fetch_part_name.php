<?php
header('Content-Type: application/json'); // ✅ กำหนดให้ API ส่ง JSON

// ✅ เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "mrdiyit");
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// ✅ ตรวจสอบว่ามี `query` ที่ถูกส่งมา
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo json_encode(["success" => false, "message" => "Query parameter is missing"]);
    exit;
}

$query = trim($_GET['query']); // ตัดช่องว่างออกจาก input

// ✅ ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
$sql = "SELECT part_name, barcode FROM devices WHERE part_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchQuery = "%$query%";
$stmt->bind_param("s", $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

// ✅ ดึงข้อมูลจากฐานข้อมูล
$parts = [];
while ($row = $result->fetch_assoc()) {
    $parts[] = $row;
}

// ✅ ส่งข้อมูลกลับไปเป็น JSON
echo json_encode(["success" => true, "data" => $parts]);

// ✅ ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
exit;
?>
