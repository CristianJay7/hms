<?php
include 'includes/config.php';
include 'includes/info.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Rights | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
</head>
<body>
<?php include 'includes/header.php'; ?>


<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-icon" style="margin-top: 20px;"><i class="fas fa-hand-holding-heart"></i></div>
        <h1><?= info('rights_title', 'Patient Rights & Responsibilities') ?></h1>
        <p><?= info('rights_intro') ?></p>
    </div>

    <div class="legal-container">

        <h2 style="text-align:center;color:#1a3c5e;font-size:1.6rem;margin-bottom:28px;font-weight:700;">Your Rights</h2>

        <div class="rights-grid">
            <div class="rights-card">
                <div class="rights-num">01</div>
                <h3><?= info('rights_r1_title') ?></h3>
                <p><?= info('rights_r1_desc') ?></p>
            </div>
            <div class="rights-card">
                <div class="rights-num">02</div>
                <h3><?= info('rights_r2_title') ?></h3>
                <p><?= info('rights_r2_desc') ?></p>
            </div>
            <div class="rights-card">
                <div class="rights-num">03</div>
                <h3><?= info('rights_r3_title') ?></h3>
                <p><?= info('rights_r3_desc') ?></p>
            </div>
            <div class="rights-card">
                <div class="rights-num">04</div>
                <h3><?= info('rights_r4_title') ?></h3>
                <p><?= info('rights_r4_desc') ?></p>
            </div>
            <div class="rights-card">
                <div class="rights-num">05</div>
                <h3><?= info('rights_r5_title') ?></h3>
                <p><?= info('rights_r5_desc') ?></p>
            </div>
        </div>

        <div class="legal-card" style="margin-top:32px;">
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-user-check"></i></div>
                <div class="legal-content">
                    <h2>Your Responsibilities</h2>
                    <p><?= info('rights_resp_intro') ?></p>
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