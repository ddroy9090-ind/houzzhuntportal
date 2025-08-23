<?php
include 'includes/auth.php';
include 'config.php';

$current = $_SESSION['user_id'];
$conn->query("UPDATE users SET last_active = NOW() WHERE id = $current");
echo "ok";
