<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mrdiyit");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT u.*, g.group_name FROM users u 
        LEFT JOIN groups g ON u.group_id = g.id WHERE u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['group_name'] = $user['group_name'];
        $_SESSION['is_admin'] = ($user['group_id'] == 1);

        if ($_SESSION['is_admin']) {
            $_SESSION['permissions'] = "all";
        } else {
            $permissions = [];
            $perm_sql = "SELECT page_name, access FROM group_permissions WHERE group_id = ?";
            $perm_stmt = $conn->prepare($perm_sql);
            $perm_stmt->bind_param("i", $user['group_id']);
            $perm_stmt->execute();
            $perm_result = $perm_stmt->get_result();
            while ($row = $perm_result->fetch_assoc()) {
                $permissions[$row['page_name']] = [
                    'access' => $row['access']
                ];
            }
            $_SESSION['permissions'] = $permissions;
            $perm_stmt->close();
        }

        header("Location: ../select_system.php");
        exit;
    } else {
        echo "<html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script> 
            <style>
                body {
                    background-color: #01012F;
                    color: white;
                }
            </style></head><body>";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect username or password!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../index.php';
                });
            </script>";
        echo "</body></html>";
        die();
    }
} else {
    echo "<html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <style>
                body {
                    background-color: #01012F;
                    color: white;
                }
            </style></head><body>";
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'User not found!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
    echo "</body></html>";
    die();
}

$stmt->close();
$conn->close();
?>