<?php
include 'includes/config.php';
include 'includes/info.php';
 
$rights_res = mysqli_query($con, "SELECT * FROM patient_rights ORDER BY sort_order ASC");
$rights     = [];
while ($row = mysqli_fetch_assoc($rights_res)) $rights[] = $row;
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
        <div class="legal-hero-icon"><i class="fas fa-hand-holding-heart"></i></div>
        <h1><?= info('rights_title', 'Patient Rights & Responsibilities') ?></h1>
        <p><?= info('rights_intro') ?></p>
    </div>
 
    <div class="legal-container">
 
        <h2 style="text-align:center;color:#1a3c5e;font-size:1.6rem;margin-bottom:28px;font-weight:700;">Your Rights</h2>
 
        <?php if (empty($rights)): ?>
            <p style="text-align:center;color:#aaa;padding:40px 0;">No rights listed yet.</p>
        <?php else: ?>
        <div class="rights-grid">
            <?php foreach ($rights as $i => $r): ?>
            <div class="rights-card">
                <div class="rights-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                <h3><?= htmlspecialchars($r['title']) ?></h3>
                <p><?= htmlspecialchars($r['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
 
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