<?php
include 'includes/auth.php';
include 'config.php';

$user_id = intval($_GET['user_id'] ?? 0);
$current = $_SESSION['user_id'];

if ($user_id) {
    $conn->query("UPDATE messages SET is_read=1 WHERE sender_id=$user_id AND receiver_id=$current");
    $result = $conn->query("SELECT sender_id, receiver_id, message, is_read, created_at FROM messages WHERE (sender_id=$current AND receiver_id=$user_id) OR (sender_id=$user_id AND receiver_id=$current) ORDER BY created_at");
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
}
