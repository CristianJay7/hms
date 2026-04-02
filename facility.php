<?php include 'includes/config.php'; ?>

<?php
global $con;
$facilities = [];
$result     = mysqli_query($con, "SELECT * FROM facilities ORDER BY created_at ASC");
while ($row = mysqli_fetch_assoc($result)) $facilities[] = $row;
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Zamboanga Doctors' Hospital, Inc. | Our Facility</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<?php include 'includes/favicon.php'; ?>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
body { background:#f5f6f8; color:#333; }

.services-section { padding:70px 8%; text-align:center; }
.services-section h1 { font-size:42px; color:#00b6bd; font-weight:700; margin-bottom:20px; }
.services-section p { max-width:850px; margin:0 auto 20px auto; font-size:16px; line-height:1.7; color:#555; }

.services-grid { margin-top:50px; display:grid; grid-template-columns:repeat(3,1fr); gap:30px; }

.service-card { position:relative; border-radius:20px; overflow:hidden; cursor:pointer; transition:transform 0.3s ease; }
.service-card:hover { transform:translateY(-12px); }
.service-card:hover {
    background: linear-gradient(to top, rgba(0,182,189,0.85), rgba(0,0,0,0.3));
}
.service-card img { width:100%; height:280px; object-fit:cover; display:block; }

.service-overlay {
    position:absolute; inset:0;
    background:linear-gradient(to top, rgba(0,0,0,0.65), rgba(0,0,0,0.2));
    display:flex; flex-direction:column; justify-content:center;
    align-items:center; padding:20px; text-align:center;
}
.service-overlay h3 { color:#fff; font-size:22px; font-weight:600; margin-bottom:15px; }


.service-btn {
    background:#00b6bd; color:#fff; border:none;
    padding:10px 22px; border-radius:50px;
    font-size:14px; font-weight:500; cursor:pointer;
    transition:all 0.3s ease; text-decoration:none; display:inline-block;
}
.service-btn:hover { background:#fff; color:#00b6bd; }

.no-facilities { text-align:center; color:#aaa; padding:60px 0; font-size:1rem; grid-column:1/-1; }

@media(max-width:1024px) { .services-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px)  { .services-grid { grid-template-columns:1fr; } .services-section h1 { font-size:32px; } }
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br><br>

<section class="services-section">
    <h1>OUR FACILITY</h1>
    <p>At Zamboanga Doctors Hospital, we are committed to delivering exceptional
    healthcare services to the residents of the Zamboanga Peninsula, guided by excellence, compassion, and integrity.</p>

    <div class="services-grid">
        <?php if (empty($facilities)): ?>
            <p class="no-facilities">No facilities listed at the moment.</p>
        <?php else: ?>
            <?php foreach ($facilities as $f): ?>
            <div class="service-card">
                <img src="<?= htmlspecialchars($f['image'] ?: '/hms/admin/images/default.jpg') ?>"
                     alt="<?= htmlspecialchars($f['name']) ?>"
                     onerror="this.src='/hms/admin/images/default.jpg'">
                <div class="service-overlay">
                    <h3><?= htmlspecialchars($f['name']) ?></h3>
                    <a href="facility-details/<?= $f['id'] ?>" class="service-btn">View details →</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script>
    // Force dark navbar text — white background page
    document.querySelector('header').classList.add('header-light');
</script>
</body>
</html>