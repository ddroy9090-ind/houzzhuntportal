<?php
include 'includes/auth.php';
include 'config.php';

$totalProperties = $conn->query("SELECT COUNT(*) AS c FROM properties")->fetch_assoc()['c'];
$totalLeads      = $conn->query("SELECT COUNT(*) AS c FROM leads")->fetch_assoc()['c'];
$totalUsers      = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$todayLeads      = $conn->query("SELECT COUNT(*) AS c FROM leads WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'];

$recentProperties = $conn->query("SELECT id, project_name, location, starting_price FROM properties ORDER BY created_at DESC LIMIT 5");
$recentLeads = $conn->query("SELECT leads.name, leads.email, properties.project_name, leads.created_at FROM leads LEFT JOIN properties ON leads.property_id = properties.id ORDER BY leads.created_at DESC LIMIT 5");
?>

<?php include 'includes/common-header.php'; ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Dashboard</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="mb-0">Total Properties</p>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $totalProperties; ?>">0</span></h4>
                                    <a href="all-properties.php" class="text-decoration-underline">View properties</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-buildings text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="mb-0">Total Leads</p>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $totalLeads; ?>">0</span></h4>
                                    <a href="leads.php" class="text-decoration-underline">View leads</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="bx bx-user-voice text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="mb-0">Channel Partners</p>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $totalUsers; ?>">0</span></h4>
                                    <a href="users.php" class="text-decoration-underline">View users</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-user-circle text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="mb-0">Leads Today</p>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo $todayLeads; ?>">0</span></h4>
                                    <a href="leads.php" class="text-decoration-underline">View leads</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-trending-up text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Latest Properties</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-centered table-nowrap mb-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($p = $recentProperties->fetch_assoc()): ?>
                                            <tr>
                                                <td><a href="property-details.php?id=<?php echo $p['id']; ?>" class="text-reset"><?php echo htmlspecialchars($p['project_name']); ?></a></td>
                                                <td><?php echo htmlspecialchars($p['location']); ?></td>
                                                <td><?php echo htmlspecialchars($p['starting_price']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Leads</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-centered table-nowrap mb-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                        <th>Property</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($l = $recentLeads->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($l['name']); ?></td>
                                                <td><?php echo htmlspecialchars($l['email']); ?></td>
                                                <td><?php echo htmlspecialchars($l['project_name']); ?></td>
                                                <td><?php echo htmlspecialchars($l['created_at']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/common-footer.php'; ?>

