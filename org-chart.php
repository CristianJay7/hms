<?php
include 'includes/config.php';
include 'includes/info.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Organizational Chart | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
</head>
<body>
<?php include 'includes/header.php'; ?>



<div class="legal-page">
    <div class="legal-hero" >
        <div class="legal-hero-icon" style="margin-top: 20px;"><i class="fas fa-sitemap"></i></div>
        <h1><?= info('org_title', 'Organizational Chart') ?></h1>
        <p><?= info('org_intro') ?></p>
    </div>

    <div class="legal-container">
        <?php
        // Check if org chart image exists in siteinfo
        $org_image = info_raw('org_chart_image', '');
        ?>

        <?php if (!empty($org_image)): ?>
        <!-- Show uploaded org chart image -->
        <div style="text-align:center;margin-bottom:40px;">
            <img src="<?= htmlspecialchars($org_image) ?>"
                 alt="Organizational Chart"
                 style="max-width:100%;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.1);">
        </div>
        <?php else: ?>
        <!-- Org Chart Tree -->
        <div class="org-tree">

            <!-- Top -->
            <div class="org-level">
                <div class="org-box org-top">
                    <i class="fas fa-crown"></i>
                    <h3>Board of Directors</h3>
                </div>
            </div>

            <div class="org-connector"></div>

            <div class="org-level">

                        <div class="org-box org-president">
                            <i class="fas fa-user-tie"></i>
                            <h3>President</h3>
                            <p>Ray Chicombing, RPh</p>
                        </div>
                        
                        <div class="org-box org-president">
                            <i class="fas fa-user-tie"></i>
                            <h3>Administration</h3>
                            <p>Jocelyn Canto</p>
                        </div>

                </div>


            <div class="org-connector"></div>

            <!-- Departments -->
            <div class="org-level org-departments">
                <div class="org-box">
                    <i class="fas fa-user-doctor"></i>
                    <h3>Medical Department</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-notes-medical"></i>
                    <h3>Nursing Department</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-flask"></i>
                    <h3>Laboratory</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-x-ray"></i>
                    <h3>Radiology</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-pills"></i>
                    <h3>Pharmacy</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-calculator"></i>
                    <h3>Finance</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-people-group"></i>
                    <h3>Human Resources</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-computer"></i>
                    <h3>MIS</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-heart-pulse"></i>
                    <h3>Cardiopulmo</h3>
                </div>
                <div class="org-box">
                    <i class="fas fa-droplet"></i>
                    <h3>Dialysis</h3>
                </div>
            
            
            
            
            
            
            </div>

        </div>

        <div class="legal-card" style="margin-top:32px;text-align:center;">
            <p style="color:#888;font-size:1.1rem;">
                <i class="fas fa-info-circle" style="color:#00b6bd;"></i>
                For the complete organizational chart, please visit the hospital administration office or contact us directly.
            </p>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>window.addEventListener('DOMContentLoaded',function(){document.querySelector('header').classList.add('header-light');});</script>
</body>
</html>