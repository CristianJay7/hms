<?php
include 'includes/config.php';
include 'includes/info.php';

$gallery_res   = mysqli_query($con, "SELECT * FROM gallery ORDER BY created_at ASC");
$gallery_items = [];
while ($row = mysqli_fetch_assoc($gallery_res)) $gallery_items[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body { background: #0d2b3e; }

/* ── Page Header ── */
.gallery-page-header {
    text-align: center;
    padding: 120px 8% 60px;
    position: relative;
    z-index: 2;
}

.gallery-page-header h1 {
    font-size: 3.5rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.gallery-page-header p {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.5);
    max-width: 500px;
    margin: 0 auto;
    text-transform: none;
}

/* ── Collage Grid ── */
.collage-wrapper {
    position: relative;
    padding: 0 8% 100px;
}

.collage-grid {
    columns: 4;
    column-gap: 12px;
}

.collage-item {
    break-inside: avoid;
    margin-bottom: 12px;
    overflow: hidden;
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.6s ease, transform 0.6s ease;
    cursor: pointer;
}

.collage-item.visible {
    opacity: 1;
    transform: translateY(0);
}

.collage-item img {
    width: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.collage-item:hover img {
    transform: scale(1.04);
}

/* ── Fade overlay at bottom ── */
.collage-fade {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 220px;
    background: linear-gradient(to top, #0d2b3e 0%, transparent 100%);
    pointer-events: none;
    z-index: 10;
    transition: opacity 0.4s ease;
}

/* ── Lightbox ── */
#lightbox {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.92);
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

#lightbox img {
    max-width: 90%;
    max-height: 90vh;
    border-radius: 8px;
    object-fit: contain;
    box-shadow: 0 30px 80px rgba(0,0,0,0.5);
}

#lightbox .close-lb {
    position: fixed;
    top: 24px;
    right: 32px;
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
    background: none;
    border: none;
    line-height: 1;
    opacity: 0.7;
    transition: opacity 0.2s;
}
#lightbox .close-lb:hover { opacity: 1; }

/* ── No images ── */
.no-gallery {
    text-align: center;
    color: rgba(255,255,255,0.3);
    padding: 80px 0;
    font-size: 1.1rem;
}

/* ── Responsive ── */
@media (max-width: 991px) { .collage-grid { columns: 3; } }
@media (max-width: 640px) { .collage-grid { columns: 2; } }
@media (max-width: 400px) { .collage-grid { columns: 1; } }
</style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="gallery-page-header">
    <h1>Our Gallery</h1>
    <p>A glimpse into life at Zamboanga Doctors' Hospital</p>
</div>

<div class="collage-wrapper">
    <div class="collage-grid">
        <?php if (empty($gallery_items)): ?>
            <p class="no-gallery">No photos yet.</p>
        <?php else: ?>
            <?php foreach ($gallery_items as $item): ?>
            <div class="collage-item" onclick="openLightbox('<?= htmlspecialchars($item['image']) ?>')">
                <img src="<?= htmlspecialchars($item['image'] ?: 'admin/images/default.jpg') ?>"
                     alt="<?= htmlspecialchars($item['title'] ?? 'Gallery') ?>"
                     onerror="this.onerror=null;this.src='admin/images/default.jpg'">
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Bottom fade overlay -->
<div class="collage-fade" id="collage-fade"></div>

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()">
    <button class="close-lb" onclick="closeLightbox()">✕</button>
    <img id="lightbox-img" src="" alt="">
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>
// ── Scroll fade reveal ──
const items = document.querySelectorAll('.collage-item');
const fade  = document.getElementById('collage-fade');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

items.forEach(item => observer.observe(item));

// ── Hide bottom fade when near bottom of page ──
window.addEventListener('scroll', () => {
    const scrollBottom = window.innerHeight + window.scrollY;
    const pageHeight   = document.documentElement.scrollHeight;
    const nearBottom   = pageHeight - scrollBottom < 200;
    fade.style.opacity = nearBottom ? '0' : '1';
});

// ── Lightbox ──
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').style.display = 'flex';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.getElementById('lightbox-img').src = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
});
</script>

</body>
</html>