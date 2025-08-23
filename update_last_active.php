<?php
include 'includes/auth.php';
include 'config.php';

$current = $_SESSION['user_id'];

// Use a prepared statement to avoid SQL injection and ensure safe parameter handling
$stmt = $conn->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
if ($stmt) {
    $stmt->bind_param('i', $current);
    $stmt->execute();
    $stmt->close();
    echo "ok";
} else {
    http_response_code(500);
}
