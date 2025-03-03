<?php
$conn = new mysqli("localhost", "root", "", "mrdiyit");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูล Groups
$sql_groups = "SELECT id, group_name FROM groups ORDER BY id ASC";
$result_groups = $conn->query($sql_groups);

$groups_data = [];
if ($result_groups && $result_groups->num_rows > 0) {
    while ($row = $result_groups->fetch_assoc()) {
        $groups_data[] = $row;
    }
}

$conn->close();
?>