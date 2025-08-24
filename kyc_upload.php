<?php
include 'includes/auth.php';
include 'config.php';

if ($_SESSION['role'] !== 'Channel Partner') {
    echo 'Access denied';
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['kyc_document'])) {
    $file = $_FILES['kyc_document'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $file['name']);
        $filename = time() . '_' . $safeName;
        $destDir = 'uploads/kyc';
        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }
        $path = $destDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $path)) {
            $stmt = $conn->prepare('INSERT INTO kyc_documents (user_id, document) VALUES (?, ?)');
            $stmt->bind_param('is', $user_id, $path);
            if ($stmt->execute()) {
                $message = 'Document uploaded successfully.';
            } else {
                $message = 'Failed to save document.';
            }
        } else {
            $message = 'Failed to move uploaded file.';
        }
    } else {
        $message = 'Upload error.';
    }
}
?>
<?php include 'includes/common-header.php'; ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">KYC Upload</h4>
                    </div>
                </div>
            </div>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Upload Document</label>
                    <input type="file" name="kyc_document" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/common-footer.php'; ?>
