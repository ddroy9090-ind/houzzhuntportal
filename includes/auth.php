<?php
session_start();

// If the user is not logged in, redirect to the sign-in page and include the
// originally requested URL so they can be sent back after a successful login.
if (!isset($_SESSION['user_id'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
    header("Location: signin.php?redirect=$redirect");
    exit();
}
?>

