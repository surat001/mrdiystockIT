<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupId = $_POST['editGroupId'] ?? null;
    $groupName = trim($_POST['editGroupName'] ?? '');
    $permissionsAccess = $_POST['access'] ?? [];

    if (!$groupId || empty($groupName)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "mrdiyit");
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Connection failed']));
    }

    $stmt = $conn->prepare("UPDATE groups SET group_name = ? WHERE id = ?");
    $stmt->bind_param("si", $groupName, $groupId);
    $stmt->execute();

    $deleteStmt = $conn->prepare("DELETE FROM group_permissions WHERE group_id = ?");
    $deleteStmt->bind_param("i", $groupId);
    $deleteStmt->execute();

    foreach ($permissionsAccess as $page => $value) {
        $access = isset($permissionsAccess[$page]) ? 1 : 0;

        $sql = "INSERT INTO group_permissions (group_id, page_name, access) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE access = VALUES(access)";
        
        $stmt_perm = $conn->prepare($sql);
        $stmt_perm->bind_param("isi", $groupId, $page, $access);
        $stmt_perm->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Group updated successfully']);

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>