<?php
include 'includes/auth.php';
include 'config.php';

$current = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, last_active FROM users WHERE id <> ?");
$stmt->bind_param("i", $current);
$stmt->execute();
$res = $stmt->get_result();
$users = [];
while ($row = $res->fetch_assoc()) {
    $online = false;
    if ($row['last_active']) {
        $online = (strtotime($row['last_active']) > time() - 300); // 5 minutes
    }
    $users[] = ['id' => $row['id'], 'name' => $row['name'], 'online' => $online];
}
header('Content-Type: application/json');
echo json_encode($users);
