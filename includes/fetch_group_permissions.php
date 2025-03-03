<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "mrdiyit");

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed']));
}

$groupId = $_GET['group_id'] ?? null;

if (!$groupId) {
    echo json_encode(['status' => 'error', 'message' => 'No group ID provided']);
    exit;
}

$sql = "SELECT page_name, access FROM group_permissions WHERE group_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $groupId);
$stmt->execute();
$result = $stmt->get_result();

$permissions = [];
while ($row = $result->fetch_assoc()) {
    $permissions[$row['page_name']] = [
        'access' => $row['access']
    ];
}

echo json_encode(['status' => 'success', 'permissions' => $permissions]);

$stmt->close();
$conn->close();
?>