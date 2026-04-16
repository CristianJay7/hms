<?php
require_once 'includes/config.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: roomrates.php'); exit; }

$result = mysqli_query($con, "SELECT * FROM roomrates WHERE id=$id");
$room   = mysqli_fetch_assoc($result);
if (!$room) { header('Location: roomrates.php'); exit; }

// All rooms for sidebar
$all    = [];
$res2   = mysqli_query($con, "SELECT id, name, price_per_night FROM roomrates ORDER BY created_at ASC");
while ($r = mysqli_fetch_assoc($res2)) $all[] = $r;
$pres   = mysqli_query($con, "SELECT * FROM room_photos WHERE room_id=$id ORDER BY created_at ASC");
$photos = [];
while ($p = mysqli_fetch_assoc($pres)) $photos[] = $p;
// Parse amenities into array
$amenities = array_filter(array_map('trim', explode(',', $room['amenities'] ?? '')));
$cover = !empty($room['image']) ? $room['image'] : '/hms/admin/images/default.jpg';
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($room['name']) ?> | Zamboanga Doctors' Hospital</title>
    <link rel="icon" href="/hms/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hms/css/style.css">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
body { background:#f5f6f8; color:#333; }

.details-section {
    padding: 110px 8% 70px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 40px;
    align-items: start;
}

/* Main content */
.details-main {}

.details-img {
    width: 90%;
    height: 620px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    margin-bottom: 32px;
}

.details-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e8f9f9;
    color: #00b6bd;
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 50px;
    margin-bottom: 14px;
}

.details-main h1 {
    font-size: 3.3rem;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 12px;
}

.details-price {
    font-size: 2.6rem;
    font-weight: 700;
    color: #00b6bd;
    margin-bottom: 20px;
}

.details-price span {
    font-size: 1.3rem;
    font-weight: 500;
    color: #999;
}

.details-capacity {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 1.2rem;
    color: #666;
    background: #f4f8f9;
    padding: 8px 16px;
    border-radius: 50px;
    margin-bottom: 24px;
}

.details-capacity i { color: #00b6bd; }

.details-desc {
    font-size: 1.8rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 32px;
    text-transform: none;
}

.amenities-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 16px;
}

.amenities-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 36px;
}

.amenity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 1.3rem;
    color: #444;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.amenity-item i { color: #00b6bd; font-size: 0.9rem; flex-shrink: 0; }

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #0d2b3e;
    color: #fff;
    padding: 13px 28px;
    border-radius: 50px;
    font-size: 1.3rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #00b6bd;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,182,189,0.3);
}

/* Sidebar */
.details-sidebar {}

.sidebar-card {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 6px 24px rgba(0,0,0,0.07);
    position: sticky;
    top: 110px;
}

.sidebar-card h4 {
    font-size: 20px;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;
}

.sidebar-room-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 1.2rem;
    color: #444;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 4px;
}

.sidebar-room-link:hover {
    background: #f0fafa;
    color: #00b6bd;
    padding-left: 18px;
}

.sidebar-room-link.active {
    background: #e8f9f9;
    color: #00b6bd;
    font-weight: 700;
}

.sidebar-room-link .price {
    font-size: 0.78rem;
    color: #00b6bd;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 900px) {
    .details-section { grid-template-columns: 1fr; }
    .details-sidebar { order: -1; }
    .sidebar-card { position: static; }
    .details-img { height: 200px; width: 100%; }
    .details-main h1 { font-size: 1.8rem; }
}

@media (max-width: 600px) {
    .amenities-grid { grid-template-columns: 1fr; }
}

html, body {
    transition: none !important;
}

.details-section {
    transition: none !important;
}

.sidebar-card {
    position: sticky;
    top: 110px;
    transition: none !important;
}
 /* ── Photo Gallery ── */
 .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 8px;
        }
        .photo-gallery-item {
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            aspect-ratio: 4/3;
            background: #eef1f5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .photo-gallery-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.14);
        }
        .photo-gallery-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .no-photos {
            color: #aaa;
            font-size: 0.88rem;
            padding: 20px 0;
        }

        
        /* ── Lightbox ── */
        #lightbox {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.92);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #lightbox.active { display: flex; }
        #lightbox img {
            max-width: 90vw;
            max-height: 88vh;
            border-radius: 10px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.6);
        }
        #lightbox-close {
            position: absolute; top: 20px; right: 28px;
            color: #fff; font-size: 2rem;
            cursor: pointer; line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        #lightbox-close:hover { opacity: 1; }
        #lightbox-prev, #lightbox-next {
            position: absolute;
            top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.12);
            border: none; color: #fff;
            font-size: 1.6rem;
            padding: 14px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        #lightbox-prev:hover, #lightbox-next:hover { background: rgba(255,255,255,0.25); }
        #lightbox-prev { left: 20px; }
        #lightbox-next { right: 20px; }
        #lightbox-counter {
            position: absolute; bottom: 20px;
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
        }
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="details-section">

    <!-- Main Content -->
    <div class="details-main">
        <img class="details-img"
             src="../<?= !empty($room['image']) ? htmlspecialchars($room['image']) : '/hms/images/default.jpg' ?>"
             alt="<?= htmlspecialchars($room['name']) ?>"
             onerror="this.onerror=null; this.src='/hms/images/default.jpg'">

        <span class="details-badge"><i class="fas fa-bed"></i> Room Type</span>
        <h1><?= htmlspecialchars($room['name']) ?></h1>

        <div class="details-price">
            ₱<?= number_format($room['price_per_night'], 2) ?>
            <span>/ night</span>
        </div>

        <?php if (!empty($room['capacity'])): ?>
        <div class="details-capacity">
            <i class="fas fa-user"></i>
            <?= htmlspecialchars($room['capacity']) ?>
        </div>
        <?php endif; ?>

        <p class="details-desc"><?= nl2br(htmlspecialchars($room['description'])) ?></p>

        <?php if (!empty($amenities)): ?>
        <h3 class="amenities-title"><i class="fas fa-list-check" style="color:#00b6bd;margin-right:8px;"></i>Amenities</h3>
        <div class="amenities-grid">
            <?php foreach ($amenities as $amenity): ?>
            <div class="amenity-item">
                <i class="fas fa-circle-check"></i>
                <?= htmlspecialchars($amenity) ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

                 <!-- Photo Gallery -->
        <div class="room-section-title">Photo Gallery</div>
        <?php if (empty($photos)): ?>
            <p class="no-photos">No additional photos available for this room.</p>
        <?php else: ?>
        <div class="photo-gallery">
            <?php foreach ($photos as $i => $p): ?>
            <div class="photo-gallery-item" onclick="openLightbox(<?= $i ?>)">
                <img src="<?= htmlspecialchars($p['photo']) ?>"
                     alt="Room photo <?= $i + 1 ?>"
                     onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
<br><br>
        <a href="/hms/roomrates" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to All Rooms
        </a>
    </div>

    <!-- Sidebar -->
    <div class="details-sidebar">
        <div class="sidebar-card">
            <h4><i class="fas fa-bed" style="color:#00b6bd;margin-right:8px;"></i>All Room Types</h4>
            <?php foreach ($all as $r): ?>
            <a href="/hms/room/<?= $r['id'] ?>"
               class="sidebar-room-link <?= $r['id'] == $id ? 'active' : '' ?>">
                <?= htmlspecialchars($r['name']) ?>
                <span class="price">₱<?= number_format($r['price_per_night'], 2) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
<!-- Lightbox -->
<?php if (!empty($photos)): ?>
<div id="lightbox">
    <span id="lightbox-close" onclick="closeLightbox()">✕</span>
    <button id="lightbox-prev" onclick="shiftPhoto(-1)">&#8249;</button>
    <img id="lightbox-img" src="" alt="">
    <button id="lightbox-next" onclick="shiftPhoto(1)">&#8250;</button>
    <span id="lightbox-counter"></span>
</div>
 
<script>
const photos  = <?= json_encode(array_values(array_column($photos, 'photo'))) ?>;
let current   = 0;
 
function openLightbox(index) {
    current = index;
    updateLightbox();
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}
 
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}
 
function shiftPhoto(dir) {
    current = (current + dir + photos.length) % photos.length;
    updateLightbox();
}
 
function updateLightbox() {
    document.getElementById('lightbox-img').src = photos[current];
    document.getElementById('lightbox-counter').textContent = (current + 1) + ' / ' + photos.length;
}
 
// Close on backdrop click
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
 
// Keyboard nav
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'ArrowLeft')  shiftPhoto(-1);
    if (e.key === 'ArrowRight') shiftPhoto(1);
    if (e.key === 'Escape')     closeLightbox();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/hms/js/main.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.querySelector('header').classList.add('header-light');
    });
</script>
<?php endif; ?>
</body>
</html>