<?php $userName = htmlspecialchars($_SESSION['name'] ?? 'User'); $userRole = htmlspecialchars($_SESSION['role'] ?? ''); ?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.php" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.php" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logo/hh-icon.png" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo/houzz-hun-golden-logo.png" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text"><?php echo $userName; ?></span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle"><?php echo $userRole; ?></span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome <?php echo $userName; ?>!</h6>
            <a class="dropdown-item" href="profile.php"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="apps-chat.html"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban.html"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs.html"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="profile.php"><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="pages-profile-settings.html"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>
            <a class="dropdown-item" href="auth-logout-basic.html"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                    data-key="t-logout">Logout</span></a>
        </div>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="index.php">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="chat.php">
                        <i class="ri-chat-3-line"></i> <span data-key="t-chat">Chat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarPages">
                        <i class="ri-pages-line"></i> <span data-key="t-pages">Properties</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="all-properties.php" class="nav-link" data-key="t-starter"> All Properties </a>
                            </li>
                            <li class="nav-item">
                                <a href="add-property.php" class="nav-link" data-key="t-starter"> Add Properties </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="pages-starter.html" class="nav-link" data-key="t-starter"> View Properties </a>
                            </li> -->

                            <!-- <li class="nav-item">
                                <a href="pages-timeline.html" class="nav-link" data-key="t-timeline"> Timeline
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-faqs.html" class="nav-link" data-key="t-faqs"> FAQs </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-pricing.html" class="nav-link" data-key="t-pricing"> Pricing </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-gallery.html" class="nav-link" data-key="t-gallery"> Gallery </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-maintenance.html" class="nav-link" data-key="t-maintenance">
                                    Maintenance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-coming-soon.html" class="nav-link" data-key="t-coming-soon">
                                    Coming Soon
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-sitemap.html" class="nav-link" data-key="t-sitemap"> Sitemap </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-search-results.html" class="nav-link"
                                    data-key="t-search-results"> Search Results </a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-privacy-policy.html" class="nav-link"
                                    data-key="t-privacy-policy">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="pages-term-conditions.html" class="nav-link"
                                    data-key="t-term-conditions">Term & Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarBlogs" class="nav-link" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarBlogs">
                                    <span data-key="t-blogs">Blogs</span> <span
                                        class="badge badge-pill bg-success" data-key="t-new">New</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarBlogs">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="pages-blog-list.html" class="nav-link"
                                                data-key="t-list-view">List View</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="pages-blog-grid.html" class="nav-link"
                                                data-key="t-grid-view">Grid View</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="pages-blog-overview.html" class="nav-link"
                                                data-key="t-overview">Overview</a>
                                        </li>
                                    </ul>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="leads.php">
                        <i class="ri-contacts-line"></i> <span data-key="t-leads">Leads</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="users.php">
                        <i class="ri-account-circle-line"></i> <span data-key="t-authentication">User</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
