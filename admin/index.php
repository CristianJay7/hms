<?php
include './includes/session.php';
include_once __DIR__ . '/includes/config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$allowed_pages = ['dashboard','users','projects','doctors','info','hmo','services','facility','gallery','roomrates', 'reviews', 'siteinfo', 'blog', 'careers', 'faq', 'legal', 'certifications','packages'

                ];
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ZDH ADMIN PANEL</title>
<?php include 'includes/favicon.php'; ?>
<style>
:root {
    --blue:       #00b6bd;
    --blue-dark:  #008f95;
    --navy:       #1a3c5e;
    --black:      #394044;
    --white:      #fff;
    --bg:         #f4f6f8;
    --border:     #eef1f5;
}

* { margin:0; padding:0; box-sizing:border-box; font-family: Segoe UI, sans-serif; }

.wrapper { display:flex; height:100vh; }

/* ── Sidebar ── */
.sidebar {
    width: 240px;
    background: var(--black);
    color: white;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
}

.sidebar-brand {
    color: var(--blue);
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 36px;
    padding-left: 4px;
    letter-spacing: 0.5px;
}

.sidebar nav { flex: 1; }

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.65);
    text-decoration: none;
    padding: 11px 14px;
    border-radius: 8px;
    margin-bottom: 4px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.08);
    color: white;
}

.sidebar a.active {
    background: var(--navy);
    color: white;
    border-left: 3px solid var(--blue);
}

.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 16px;
}

.sidebar-footer a {
    color: rgba(255,255,255,0.45) !important;
    font-size: 0.85rem !important;
}

.sidebar-footer a:hover {
    color: white !important;
    background: rgba(255,0,0,0.1) !important;
}

/* ── Main ── */
.main { flex:1; display:flex; flex-direction:column; background: var(--bg); overflow:hidden; }

/* ── Topbar ── */
.topbar {
    background: white;
    padding: 0 30px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    flex-shrink: 0;
}

.topbar-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--navy);
}

.topbar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
    color: #778899;
}

.topbar-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--navy);
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 700;
}

/* ── Content ── */
.content {
    flex: 1;
    padding: 30px;
    overflow: auto;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
</style>
</head>
<body>

<div class="wrapper">

    <!-- Sidebar⚕  -->
    <div class="sidebar">
        <div class="sidebar-brand"><i class="fa-solid fa-staff-snake"></i>  Admin</div>

        <nav>
            <a href="index.php?page=dashboard" <?= $page === 'dashboard' ? 'class="active"' : '' ?>>
                <i class="fas fa-chart-bar"></i> Dashboard
            </a>



            <a href="index.php?page=siteinfo" <?= $page==='siteinfo' ? 'class="active"' : '' ?>>
                <i class="fas fa-cog"></i> Information
            </a>  

            
            <a href="index.php?page=users" <?= $page === 'users' ? 'class="active"' : '' ?>>
                <i class="fas fa-user-tie"></i> Users
            </a>
          
            <a href="index.php?page=doctors" <?= $page === 'doctors' ? 'class="active"' : '' ?>>
                <i class="fas fa-user-doctor"></i>Doctors
            </a>

            <a href="index.php?page=hmo" <?= $page === 'hmo' ? 'class="active"' : '' ?>>
                 <i class="fas fa-shield-heart"></i> HMOs
            </a>

            <a href="index.php?page=services" <?= $page === 'services' ? 'class="active"' : '' ?>>
                <i class="fas fa-stethoscope"></i> Services
            </a>

            <a href="index.php?page=facility" <?= $page === 'facility' ? 'class="active"' : '' ?>>
                 <i class="fas fa-hospital"></i> Facility
            </a>

            <a href="index.php?page=gallery" <?= $page === 'gallery' ? 'class="active"' : '' ?>>
                 <i class="fas fa-images"></i> Gallery
            </a>

            <a href="index.php?page=roomrates" <?= $page==='roomrates' ? 'class="active"' : '' ?>>
                <i class="fas fa-bed"></i> Room Rates
            </a>

            <a href="index.php?page=reviews" <?= $page==='reviews' ? 'class="active"' : '' ?>>
                <i class="fas fa-star"></i> Reviews
            </a>    

            <a href="index.php?page=blog" <?= $page==='blog' ? 'class="active"' : '' ?>>
                <i class="fas fa-newspaper"></i> Blogs
            </a>    

            <a href="index.php?page=legal"  <?= $page==='legal' ? 'class="active"' : '' ?>>
                            <i class="fas fa-scale-balanced"></i> Legal Pages
            </a>


            <a href="index.php?page=careers" <?= $page==='careers' ? 'class="active"' : '' ?>>
                <i class="fas fa-briefcase-medical"></i> Careers & Job Posting
            </a> 

            <a href="index.php?page=faq" <?= $page==='faq' ? 'class="active"' : '' ?>>
                <i class="fas fa-circle-question"></i> FAQ
            </a> 

            <a href="index.php?page=packages" <?= $page==='packages' ? 'class="active"' : '' ?>>
                <i class="fas fa-circle-question"></i>Packages
            </a>
            <a href="index.php?page=certifications" <?= $page==='certifications' ? 'class="active"' : '' ?>>
                <i class="fas fa-circle-question"></i> Certifications
            </a>

        </nav>

        <div class="sidebar-footer">
        <a href="#" onclick="document.getElementById('logoutModal').style.display='flex'">🚪 Logout</a>        </div>
    </div>

    <!-- Main -->
    <div class="main">

        <div class="topbar">
            <div class="topbar-title"><?= ucfirst($page) ?></div>
            <div class="topbar-user">
                <div class="topbar-avatar">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                </div>
                Hello, <?= htmlspecialchars($_SESSION['username']) ?>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <?php include("pages/" . $page . ".php"); ?>
            </div>
        </div>

    </div>

</div>




<!-- Logout Confirmation Modal -->
<div id="logoutModal" style="
    display:none; position:fixed; inset:0; z-index:2;
    background:rgba(0,0,0,0.35); backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
    align-items:center; justify-content:center;">
    <div style="
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 25px; padding: 36px 40px;
        text-align: center; max-width: 360px; width: 90%;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.15);">
        <div style="font-size:2.5rem; margin-bottom:12px;">👋</div>
        <h3 style="color:#fff; margin-bottom:8px; font-size:1.1rem; font-weight:600;">Log out?</h3>
        <p style="color:rgba(255,255,255,0.6); font-size:0.88rem; margin-bottom:24px;">Are you sure you want to end your session?</p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <a href="logout.php" style="
                padding:10px 28px;
                background:rgba(231,76,60,0.6);
                backdrop-filter:blur(10px);
                color:#fff; border-radius:8px;
                text-decoration:none; font-weight:600; font-size:0.88rem;
                border:1px solid rgba(231,76,60,0.4);">
                Yes, Logout
            </a>
            <button onclick="document.getElementById('logoutModal').style.display='none'" style="
                padding:10px 28px;
                background:rgba(255,255,255,0.1);
                backdrop-filter:blur(10px);
                color:#fff; border:1px solid rgba(255,255,255,0.15);
                border-radius:8px; font-weight:600;
                font-size:0.88rem; cursor:pointer; width:auto;">
                Cancel
            </button>
        </div>
    </div>
</div>
</body>
</html>