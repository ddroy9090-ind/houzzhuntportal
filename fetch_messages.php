<?php
include 'includes/auth.php';
include 'config.php';

$user_id = intval($_GET['user_id'] ?? 0);
$current = $_SESSION['user_id'];

if ($user_id) {
    $update = $conn->prepare("UPDATE messages SET is_read=1 WHERE sender_id=? AND receiver_id=?");
    $update->bind_param("ii", $user_id, $current);
    $update->execute();

    $stmt = $conn->prepare("SELECT sender_id, receiver_id, message, file_path, is_read, created_at FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY created_at");
    $stmt->bind_param("iiii", $current, $user_id, $user_id, $current);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
}
