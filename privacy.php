<?php
include 'includes/config.php';
include 'includes/info.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Notice | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
</head>
<body>
<?php include 'includes/header.php'; ?>


<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-icon" style="margin-top: 20px;"><i class="fas fa-shield-halved"></i></div>
        <h1><?= info('privacy_title', 'Privacy Notice') ?></h1>
        <p>Last updated: <?= date('F d, Y', strtotime(info('privacy_updated', '2024-01-01'))) ?></p>
    </div>

    <div class="legal-container">
        <div class="legal-card">
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-info-circle"></i></div>
                <div class="legal-content">
                    <h2>Overview</h2>
                    <p><?= info('privacy_intro') ?></p>
                </div>
            </div>
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-database"></i></div>
                <div class="legal-content">
                    <h2>Information We Collect</h2>
                    <p><?= info('privacy_collection') ?></p>
                </div>
            </div>
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-stethoscope"></i></div>
                <div class="legal-content">
                    <h2>How We Use Your Information</h2>
                    <p><?= info('privacy_use') ?></p>
                </div>
            </div>
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-user-check"></i></div>
                <div class="legal-content">
                    <h2>Your Rights</h2>
                    <p><?= info('privacy_rights') ?></p>
                </div>
            </div>
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-envelope"></i></div>
                <div class="legal-content">
                    <h2>Contact Us</h2>
                    <p><?= info('privacy_contact') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>window.addEventListener('DOMContentLoaded',function(){document.querySelector('header').classList.add('header-light');});</script>
</body>
</html>