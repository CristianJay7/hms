<?php
require_once 'includes/config.php';
$rooms = [];
$result = mysqli_query($con, "SELECT * FROM roomrates ORDER BY created_at ASC");
while ($row = mysqli_fetch_assoc($result)) $rooms[] = $row;
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga Doctors' Hospital, Inc. | Room Rates</title>
    <link rel="icon" href="/hms/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hms/css/style.css">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
body { background:#f5f6f8; color:#333; }

.roomrates-section { padding: 100px 8% 70px; }

/* Page header */
.roomrates-header {
    text-align: center;
    margin-bottom: 60px;
}

.roomrates-header .eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: none;
    color: #00b6bd;
    margin-bottom: 16px;
}

.roomrates-header .eyebrow::before,
.roomrates-header .eyebrow::after {
    content: '';
    display: inline-block;
    width: 28px; height: 2px;
    background: #00b6bd;
    border-radius: 2px;
}

.roomrates-header h1 {
    font-size: 4.4rem;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 16px;
}

.roomrates-header p {
    max-width: 650px;
    margin: 0 auto;
    font-size: 2.0rem;
    line-height: 1.7;
    color: #666;
}

/* Grid */
.roomrates-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* Card */
.room-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(0,0,0,0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}

.room-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,182,189,0.15);
}

.room-card-img {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.room-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.room-card:hover .room-card-img img { transform: scale(1.06); }

.room-card-body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.room-card-body h3 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 8px;
}

.room-price {
    font-size: 1.9rem;
    font-weight: 800;
    color: #00b6bd;
    margin-bottom: 10px;
}

.room-price span {
    font-size: 0.85rem;
    font-weight: 500;
    color: #999;
}

.room-desc {
    font-size: 1.3rem;
    color: #777;
    line-height: 1.6;
    margin-bottom: 16px;
    flex: 1;
    text-transform: none;
}

.room-capacity {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 1.2rem;
    color: #888;
    margin-bottom: 18px;
}

.room-capacity i { color: #00b6bd; }

.room-btn {
    display: block;
    text-align: center;
    background: #0d2b3e;
    color: #fff;
    padding: 12px;
    border-radius: 50px;
    font-size: 1.3rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.room-btn:hover {
    background: #00b6bd;
    transform: translateY(-12px);
    box-shadow: 0 8px 20px rgba(0,182,189,0.3);
}

/* Responsive */
@media (max-width: 1024px) { .roomrates-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 600px)  { .roomrates-grid { grid-template-columns: 1fr; } .roomrates-header h1 { font-size: 2rem; } }
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="roomrates-section">

    <div class="roomrates-header">
        <p class="eyebrow">Accommodations</p>
        <h1>Our Room Rates</h1>
        <p style="text-transform: none;" >Choose from a range of comfortable accommodations designed to support your recovery and well-being.</p>
    </div>

    <div class="roomrates-grid">
        <?php if (empty($rooms)): ?>
            <p style="text-align:center;color:#aaa;grid-column:1/-1;">No rooms available at the moment.</p>
        <?php else: ?>
            <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <div class="room-card-img">
                    <img src="<?= !empty($room['image']) ? htmlspecialchars($room['image']) : '/hms/images/default.jpg' ?>"
                         alt="<?= htmlspecialchars($room['name']) ?>"
                         onerror="this.src='/hms/images/default.jpg'">
                </div>
                <div class="room-card-body">
                    <h3><?= htmlspecialchars($room['name']) ?></h3>
                    <div class="room-price">
                        ₱<?= number_format($room['price_per_night'], 2) ?>
                        <span>/ night</span>
                    </div>
                    <p class="room-desc"><?= htmlspecialchars($room['description']) ?></p>
                    <?php if (!empty($room['capacity'])): ?>
                    <div class="room-capacity">
                        <i class="fas fa-user"></i>
                        <?= htmlspecialchars($room['capacity']) ?>
                    </div>
                    <?php endif; ?>
                    <a href="room/<?= $room['id'] ?>" class="room-btn">
                        View Details →
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</section>

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