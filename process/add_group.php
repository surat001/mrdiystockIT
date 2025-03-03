<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = trim($_POST['groupName'] ?? '');
    $permissionsAccess = $_POST['access'] ?? [];

    if (empty($groupName)) {
        echo json_encode(['status' => 'error', 'message' => 'Group name cannot be empty!']);
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "mrdiyit");
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
    }

    $checkStmt = $conn->prepare('SELECT id FROM groups WHERE group_name = ?');
    $checkStmt->bind_param('s', $groupName);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Group name already exists.']);
    } else {
        $stmt = $conn->prepare('INSERT INTO groups (group_name) VALUES (?)');
        $stmt->bind_param('s', $groupName);

        if ($stmt->execute()) {
            $groupId = $stmt->insert_id;

            $deletePermissionsStmt = $conn->prepare("DELETE FROM group_permissions WHERE group_id = ?");
            $deletePermissionsStmt->bind_param('i', $groupId);
            $deletePermissionsStmt->execute();

            foreach ($permissionsAccess as $page => $value) {
                $access = isset($permissionsAccess[$page]) ? 1 : 0;

                $sql = "INSERT INTO group_permissions (group_id, page_name, access) 
                        VALUES (?, ?, ?) 
                        ON DUPLICATE KEY UPDATE access = VALUES(access)";

                $stmt_perm = $conn->prepare($sql);
                $stmt_perm->bind_param('isi', $groupId, $page, $access);
                $stmt_perm->execute();
            }

            echo json_encode(['status' => 'success', 'message' => 'Group added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>