<?php
$conn = new mysqli("localhost", "root", "", "mrdiyit");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลผู้ใช้และกลุ่ม
$sql = "
    SELECT 
        u.user_id, 
        u.name, 
        u.username, 
        g.group_name 
    FROM 
        users u 
    LEFT JOIN 
        groups g 
    ON 
        u.group_id = g.id 
    ORDER BY 
        u.user_id ASC
";
$result = $conn->query($sql);

$user_it = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_it[] = $row;
    }
}

$conn->close();
?>