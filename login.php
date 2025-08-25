<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Store in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect to the originally requested page, or fall back to the dashboard
            $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
            header("Location: $redirect");
            exit;
        } else {
            echo "❌ Invalid password!";
        }
    } else {
        echo "❌ User not found!";
    }
}
?>