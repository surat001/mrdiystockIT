<?php
$servername = "localhost";
$username = "root"; // แก้ไขเป็นชื่อผู้ใช้ของคุณ
$password = ""; // แก้ไขเป็นรหัสผ่านของคุณ
$dbname = "mrdiyit"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
