<?php
include 'includes/config.php';
include 'includes/info.php';

$faqs_res = mysqli_query($con, "SELECT * FROM faqs WHERE status='published' ORDER BY sort_order ASC, created_at ASC");
$faqs     = [];
while ($row = mysqli_fetch_assoc($faqs_res)) $faqs[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
<style>
.faq-list { max-width: 800px; margin: 0 auto; }

.faq-item {
    background: #fff;
    border-radius: 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.faq-item:hover { box-shadow: 0 6px 24px rgba(0,182,189,0.1); }

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    cursor: pointer;
    gap: 16px;
    user-select: none;
}

.faq-question h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a3c5e;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.faq-icon {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #e8f9f9;
    display: flex; align-items: center; justify-content: center;
    color: #00b6bd;
    font-size: 0.85rem;
    flex-shrink: 0;
    transition: transform 0.3s ease, background 0.2s;
}

.faq-item.open .faq-icon {
    transform: rotate(45deg);
    background: #00b6bd;
    color: #fff;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.3s ease;
    padding: 0 24px;
}

.faq-item.open .faq-answer {
    max-height: 500px;
    padding: 0 24px 20px;
}

.faq-answer p {
    font-size: 0.92rem;
    color: #666;
    line-height: 1.8;
    border-top: 1px solid #eef1f5;
    padding-top: 16px;
    white-space: pre-line;
    margin: 0;
}

.faq-empty {
    text-align: center;
    padding: 60px 0;
    color: #aaa;
}
.faq-empty i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #ddd; }

.faq-contact-box {
    background: linear-gradient(135deg, #00b6bd 0%, #1a3c5e 100%);
    border-radius: 16px;
    padding: 36px;
    text-align: center;
    margin-top: 40px;
    color: #fff;
}
.faq-contact-box h3 { font-size: 1.3rem; font-weight: 700; margin-bottom: 8px; }
.faq-contact-box p  { font-size: 0.92rem; opacity: 0.85; margin-bottom: 20px; }
.faq-contact-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #fff;
    color: #1a3c5e;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
    transition: opacity 0.2s;
}
.faq-contact-btn:hover { opacity: 0.9; }
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br>

<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-icon"><i class="fas fa-circle-question"></i></div>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about our hospital, services, and policies.</p>
    </div>

    <div class="legal-container">
        <div class="faq-list">

            <?php if (empty($faqs)): ?>
                <div class="faq-empty">
                    <i class="fas fa-circle-question"></i>
                    <p>No FAQs available yet. Check back soon!</p>
                </div>
            <?php else: ?>
                <?php foreach ($faqs as $i => $f): ?>
                <div class="faq-item" id="faq-<?= $f['id'] ?>">
                    <div class="faq-question" onclick="toggleFaq(<?= $f['id'] ?>)">
                        <h3><?= htmlspecialchars($f['question']) ?></h3>
                        <div class="faq-icon"><i class="fas fa-plus"></i></div>
                    </div>
                    <div class="faq-answer">
                        <p><?= htmlspecialchars($f['answer']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Still have questions -->
            <div class="faq-contact-box">
                <h3>Still have questions?</h3>
                <p>Can't find what you're looking for? Our team is happy to help you.</p>
                <a href="contact.php" class="faq-contact-btn">
                    <i class="fas fa-headset"></i> Contact Us
                </a>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>
function toggleFaq(id) {
    const item = document.getElementById('faq-' + id);
    const isOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
    // Open clicked if it was closed
    if (!isOpen) item.classList.add('open');
}

window.addEventListener('DOMContentLoaded', function() {
    document.querySelector('header').classList.add('header-light');
    // Open first FAQ by default
    const first = document.querySelector('.faq-item');
    if (first) first.classList.add('open');
});
</script>
</body>
</html>