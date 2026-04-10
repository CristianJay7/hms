<?php
include 'includes/config.php';
global $con;
 
$id       = intval($_GET['id'] ?? 0);
$facility = null;
 
if ($id) {
    $r        = mysqli_query($con, "SELECT * FROM facilities WHERE id=$id");
    $facility = mysqli_fetch_assoc($r);
}
 
if (!$facility) {
    header('Location: facility.php');
    exit;
}
 
// Sidebar
$all_facilities = [];
$result         = mysqli_query($con, "SELECT id, name FROM facilities ORDER BY created_at ASC");
while ($row = mysqli_fetch_assoc($result)) $all_facilities[] = $row;
 
// Photos
$photos_res = mysqli_query($con, "SELECT * FROM facility_photos WHERE facility_id=$id ORDER BY sort_order ASC, created_at ASC");
$photos     = [];
while ($row = mysqli_fetch_assoc($photos_res)) $photos[] = $row;
 
// Services offered
$services_offered = [];
if (!empty($facility['services_offered'])) {
    $services_offered = array_filter(array_map('trim', explode("\n", $facility['services_offered'])));
}
 
// Schedules
$schedules = [];
if (!empty($facility['schedules'])) {
    $schedules = array_filter(array_map('trim', explode("\n", $facility['schedules'])));
}
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zamboanga Doctors' Hospital, Inc. | <?= htmlspecialchars($facility['name']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php include 'includes/favicon.php'; ?>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
body { background:#f2f3f5; }

.section-wrapper { padding:80px 8%; display:flex; justify-content:space-between; gap:40px; }

.content-container { display:flex; gap:60px; align-items:flex-start; width:75%; }

.image-card { flex:1; max-width:520px; border-radius:20px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.12); }
.image-card img { width:100%; height:100%; object-fit:cover; display:block; }

.text-content { flex:1.2; }
.text-content h1 { font-size:42px; font-weight:700; color:#00b6bd; margin-bottom:20px; }
.text-content p { font-size:16px; line-height:1.8; color:#333; margin-bottom:20px; }


.services-offered-box {
    background:#fff; border-radius:14px; padding:24px 28px;
    margin-bottom:24px; box-shadow:0 4px 16px rgba(0,0,0,0.06);
    border-left:4px solid #00b6bd;
}
.services-offered-box h3 { font-size:1.7rem; font-weight:700; color:#1a3c5e; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
.services-offered-box h3 i { color:#00b6bd; }
.checklist { list-style:none; padding:0; }
.checklist li { display:flex; align-items:flex-start; gap:10px; font-size:1.3rem; color:#444; padding:6px 0; border-bottom:1px solid #f5f5f5; line-height:1.5; }
.checklist li:last-child { border-bottom:none; }
.checklist li i { color:#00b6bd; font-size:1.3rem; margin-top:3px; flex-shrink:0; }
 
.schedules-box {
    background:#fff; border-radius:14px; padding:24px 28px;
    margin-bottom:24px; box-shadow:0 4px 16px rgba(0,0,0,0.06);
    border-left:4px solid #1a3c5e;
}
.schedules-box h3 { font-size:1.7rem; font-weight:700; color:#1a3c5e; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
.schedules-box h3 i { color:#1a3c5e; }
.schedule-item { display:flex; align-items:center; gap:10px; font-size:1.3rem; color:#444; padding:7px 0; border-bottom:1px solid #f5f5f5; }
.schedule-item:last-child { border-bottom:none; }
.schedule-item i { color:#1a3c5e; font-size:1.3rem; flex-shrink:0; }
 
.gallery-section { margin-top:8px; margin-bottom:24px; }
.gallery-section h3 { font-size:1.7rem; font-weight:700; color:#1a3c5e; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
.gallery-section h3 i { color:#00b6bd; }
.gallery-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); gap:10px; }
.gallery-item { border-radius:10px; overflow:hidden; aspect-ratio:1; cursor:pointer; position:relative; }
.gallery-item img { width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.3s; }
.gallery-item:hover img { transform:scale(1.05); }
.gallery-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s; color:#fff; font-size:1.2rem; }
.gallery-item:hover .gallery-overlay { opacity:1; }
 
#lightbox { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.92); align-items:center; justify-content:center; cursor:pointer; }
#lightbox img { max-width:90%; max-height:90vh; border-radius:8px; object-fit:contain; }
#lightbox .close-lb { position:fixed; top:24px; right:32px; color:#fff; font-size:2rem; cursor:pointer; background:none; border:none; opacity:0.7; }
#lightbox .close-lb:hover { opacity:1; }






.sidebar {
    position:sticky; top:80px; width:260px; flex-shrink:0;
    padding:24px 20px; background:#fff;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    border-radius:25px; align-self:flex-start;
}
.sidebar h3 { font-size:26px; font-weight:700; color:#00b6bd; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid #e8f7f8; }
.sidebar ul { list-style:none; padding:0; }
.sidebar ul li { margin:6px 0; }
.sidebar ul li a { text-decoration:none; color:#555; font-weight:600; font-size:1.2rem; padding:7px 10px; border-radius:6px; display:block; transition:all 0.2s; }
.sidebar ul li a:hover { color:#00b6bd; background:#f0fafa; }
.sidebar ul li a.active { color:#00b6bd; background:#e8f7f8; font-weight:600; }

.back-btn { margin-top:36px; display:inline-block; background:#00b6bd; color:#fff; padding:12px 28px; border-radius:50px; text-decoration:none; font-size:14px; font-weight:500; transition:all 0.3s ease; }
.back-btn:hover { background:#009aa0; transform:translateY(-2px); }

@media(max-width:1024px) {
    .section-wrapper { flex-direction:column; }
    .content-container { flex-direction:column; width:100%; }
    .image-card { max-width:100%; }
    .text-content h1 { font-size:30px; }
    .sidebar { display:none; }
}
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br><br>

<section class="section-wrapper">

    <div class="content-container">
        <div class="image-card">
            <img src="<?= htmlspecialchars($facility['image'] ?: '/hms/admin/images/default.jpg') ?>"
                 alt="<?= htmlspecialchars($facility['name']) ?>"
                 onerror="this.src='/hms/admin/images/default.jpg'">
        </div>

        <div class="text-content">
            <h1><?= htmlspecialchars($facility['name']) ?></h1>

            <?php if (!empty($facility['description'])): ?>
                <?php foreach (explode("\n", $facility['description']) as $para): ?>
                    <?php $para = trim($para); ?>
                    <?php if ($para): ?>
                        <p style="text-align: justify; hyphens: auto;"><?= htmlspecialchars($para) ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: justify; hyphens: auto;">Details about this facility are coming soon.</p>
            <?php endif; ?>

             <!-- Services Offered -->
             <?php if (!empty($services_offered)): ?>
            <div class="services-offered-box">
                <h3><i class="fas fa-list-check"></i> Services Offered</h3>
                <ul class="checklist">
                    <?php foreach ($services_offered as $item): ?>
                    <li>
                        <i class="fas fa-circle-check"></i>
                        <?= htmlspecialchars($item) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
 
            <!-- Schedules -->
            <?php if (!empty($schedules)): ?>
            <div class="schedules-box">
                <h3><i class="fas fa-clock"></i> Schedule</h3>
                <?php foreach ($schedules as $sched): ?>
                <div class="schedule-item">
                    <i class="fas fa-calendar-day"></i>
                    <?= htmlspecialchars($sched) ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
 
            <!-- Photo Gallery -->
            <?php if (!empty($photos)): ?>
            <div class="gallery-section">
                <h3><i class="fas fa-images"></i> Photo Gallery</h3>
                <div class="gallery-grid">
                    <?php foreach ($photos as $p): ?>
                    <div class="gallery-item" onclick="openLightbox('<?= htmlspecialchars($p['photo']) ?>')">
                        <img src="<?= htmlspecialchars($p['photo']) ?>" alt="Gallery"
                            onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                        <div class="gallery-overlay"><i class="fas fa-expand"></i></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>






            <p>Contact us today at <strong>(062) 991-1929.</strong></p>
            <a href="/hms/facility" class="back-btn">← Back to Facilities</a>
        </div>
    </div>

    <div class="sidebar">
        <h3>Our Facilities</h3>
        <ul>
            <?php foreach ($all_facilities as $f): ?>
                <li>
                    <a href="/hms/facility-details/<?= $f['id'] ?>"
                        class="<?= $f['id'] == $id ? 'active' : '' ?>">
                        <?= htmlspecialchars($f['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</section>

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()">
    <button class="close-lb" onclick="closeLightbox()">✕</button>
    <img id="lightbox-img" src="" alt="">
</div>
 
<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>
document.querySelector('header').classList.add('header-light');
 
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.getElementById('lightbox-img').src = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
</body>
</html>
 l>