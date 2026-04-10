<?php
include 'includes/config.php';
include 'includes/info.php';
 
$sections_res = mysqli_query($con, "SELECT * FROM terms_sections ORDER BY sort_order ASC");
$sections     = [];
while ($row = mysqli_fetch_assoc($sections_res)) $sections[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terms & Conditions | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
</head>
<body>
<?php include 'includes/header.php'; ?>


<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-icon"><i class="fas fa-file-contract"></i></div>
        <h1><?= info('terms_title', 'Terms & Conditions') ?></h1>
        <p>Last updated: <?= date('F d, Y', strtotime(info('terms_updated', date('Y-m-d')))) ?></p>
    </div>
 
    <div class="legal-container">
        <div class="legal-card">
            <?php if (empty($sections)): ?>
                <p style="text-align:center;color:#aaa;padding:40px 0;">No content yet.</p>
            <?php else: ?>
                <?php foreach ($sections as $s): ?>
                <div class="legal-section">
                    <div class="legal-icon">
                        <i class="<?= htmlspecialchars($s['icon'] ?? 'fa-solid fa-circle-info') ?>"></i>
                    </div>
                    <div class="legal-content">
                        <h2><?= htmlspecialchars($s['title']) ?></h2>
                        <p><?= htmlspecialchars($s['content']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>window.addEventListener('DOMContentLoaded',function(){document.querySelector('header').classList.add('header-light');});</script>
</body>
</html>