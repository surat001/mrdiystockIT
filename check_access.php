<?php
session_start();

// ✅ ถ้ายังไม่ล็อกอิน ให้ redirect ไปหน้า index
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit;
}

$system = $_GET['system'] ?? '';

// ✅ ตรวจสอบว่าเป็น Admin หรือไม่
$isAdmin = $_SESSION['is_admin'] ?? false;

// ✅ ถ้าเป็น Admin สามารถเข้าได้ทุกระบบ
if ($isAdmin) {
    header("Location: dashboard.php");
    exit;
}

// ✅ ดึงสิทธิ์ของ User ปกติ
$permissions = $_SESSION['permissions'] ?? [];

// ✅ ตรวจสอบว่ามีสิทธิ์เข้าระบบ Stock หรือไม่
if ($system === 'stock') {
    if ($permissions === "all" || (isset($permissions['dashboard.php']) && $permissions['dashboard.php']['access'] == 1)) {
        header("Location: dashboard.php");
        exit;
    } else {
        // ❌ ไม่มีสิทธิ์เข้า แสดงแจ้งเตือนและ redirect
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Access Denied</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You do not have permission to access this page.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'select_system.php';
                });
            </script>
        </body>
        </html>
        ";
        exit;
    }
} else {
    // ❌ ถ้าระบบที่ระบุไม่ถูกต้อง ให้ redirect กลับไปที่หน้าเลือกระบบ
    header("Location: select_system.php");
    exit;
}

?>