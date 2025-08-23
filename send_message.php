<?php
include 'includes/auth.php';
include 'config.php';

$receiver_id = intval($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($receiver_id && $message !== '') {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message);
    $stmt->execute();
    echo "success";
} else {
    echo "error";
}
