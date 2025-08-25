<?php
declare(strict_types=1);

use App\Db;

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../src/Db.php';

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['Admin', 'Manager'], true)) {
    http_response_code(403);
    echo 'Access denied';
    exit;
}

$pdo = Db::pdo();
$stmt = $pdo->query('SELECT id, user_id, email, status, created_at FROM kyc_submissions ORDER BY created_at DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KYC Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>KYC Submissions</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$r['id']) ?></td>
                    <td><?= htmlspecialchars((string)$r['user_id']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['status']) ?></td>
                    <td><?= htmlspecialchars($r['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
