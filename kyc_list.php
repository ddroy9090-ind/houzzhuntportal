<?php
include 'includes/auth.php';
include 'config.php';

if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Manager') {
    echo 'Access denied';
    exit;
}

$query = $conn->query("SELECT k.id, u.name, u.email, k.document, k.created_at FROM kyc_documents k JOIN users u ON k.user_id = u.id ORDER BY k.created_at DESC");
?>
<?php include 'includes/common-header.php'; ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">KYC Details</h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Channel Partner</th>
                            <th>Email</th>
                            <th>Document</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($row['document']); ?>" target="_blank">View</a></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/common-footer.php'; ?>
