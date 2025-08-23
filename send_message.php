<?php
include 'includes/auth.php';
include 'config.php';

$receiver_id = intval($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');
$attachmentPath = null;

if (!empty($_FILES['attachment']['name'])) {
    $uploadDir = 'uploads/chat/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['attachment']['name']);
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
        $attachmentPath = $targetPath;
    }
}

if ($receiver_id && ($message !== '' || $attachmentPath)) {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $_SESSION['user_id'], $receiver_id, $message, $attachmentPath);
    $stmt->execute();
    $response = [
        'id' => $stmt->insert_id,
        'sender_id' => $_SESSION['user_id'],
        'receiver_id' => $receiver_id,
        'message' => $message,
        'file_path' => $attachmentPath,
        'created_at' => date('Y-m-d H:i:s')
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Invalid message']);
}
