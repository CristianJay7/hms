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

$all_facilities = [];
$result         = mysqli_query($con, "SELECT id, name FROM facilities ORDER BY created_at ASC");
while ($row = mysqli_fetch_assoc($result)) $all_facilities[] = $row;
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

<?php include 'includes/footer.php'; ?>
<script>
    // Force dark navbar text — white background page
    document.querySelector('header').classList.add('header-light');
</script>
</body>
</html>