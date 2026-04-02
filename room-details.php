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

// Parse amenities into array
$amenities = array_filter(array_map('trim', explode(',', $room['amenities'] ?? '')));
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
    width: 100%;
    height: 420px;
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
    .details-img { height: 280px; }
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
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="details-section">

    <!-- Main Content -->
    <div class="details-main">
        <img class="details-img"
             src="<?= !empty($room['image']) ? htmlspecialchars($room['image']) : '/hms/admin/images/default.jpg' ?>"
             alt="<?= htmlspecialchars($room['name']) ?>"
             onerror="this.onerror=null; this.src='/hmsadmin/images/default.jpg'">

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/hms/js/main.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.querySelector('header').classList.add('header-light');
    });
</script>
</body>
</html>