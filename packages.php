<?php
require_once 'includes/config.php';
require_once 'includes/info.php';

$res      = mysqli_query($con, "SELECT * FROM packages WHERE status='active' ORDER BY sort_order ASC, created_at DESC");
$packages = [];
while ($row = mysqli_fetch_assoc($res)) $packages[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Packages — <?= info('site_name') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ── Page Hero ── */
        .page-hero {
            background: linear-gradient(135deg, #0d2137 0%, #1a3c5e 60%, #00b6bd 100%);
            padding: 72px 24px 52px;
            text-align: center;
            color: #fff;
            
        }
        .page-hero h1 {
            font-size: clamp(1.8rem, 4vw, 2.9rem);
            font-weight: 800;
            margin: 0 0 10px;
            margin-top: 50px;
        }
        .page-hero p {
            font-size: 1.3rem;
            opacity: 0.75;
            margin: 0;
        }

        /* ── Viewer ── */
        .pkg-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 52px 24px 80px;
        }

        .pkg-viewer {
            position: relative;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            overflow: hidden;
        }

        .pkg-main-img {
            display: block;
            width: 100%;
            max-height: 80vh;
            object-fit: contain;
            background: #f4f6f9;
            transition: opacity 0.25s ease;
        }
        .pkg-main-img.fading { opacity: 0; }

        /* Controls bar */
        .pkg-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #fff;
            border-top: 1px solid #eef1f5;
        }
        .pkg-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            flex: 1;
            text-align: center;
            padding: 0 12px;
        }
        .pkg-nav-btn {
            background: #1a3c5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            width: 40px; height: 40px;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .pkg-nav-btn:hover { background: #00b6bd; }
        .pkg-nav-btn:disabled { background: #dde3ea; cursor: default; }

        /* Counter dots */
        .pkg-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 14px 0 0;
        }
        .pkg-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #dde3ea;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .pkg-dot.active {
            background: #1a3c5e;
            transform: scale(1.3);
        }

        /* Counter text */
        .pkg-counter {
            text-align: center;
            font-size: 1.2rem;
            color: #aaa;
            margin-top: 8px;
        }

        /* Thumbnail strip */
        .pkg-thumbs {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 20px 0 4px;
            scrollbar-width: thin;
        }
        .pkg-thumb {
            flex-shrink: 0;
            width: 80px; height: 56px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, transform 0.2s;
        }
        .pkg-thumb:hover { transform: translateY(-2px); }
        .pkg-thumb.active { border-color: #1a3c5e; }
        .pkg-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Empty state */
        .pkg-empty {
            text-align: center;
            padding: 80px 24px;
            color: #aaa;
        }
        .pkg-empty i { font-size: 3rem; margin-bottom: 16px; opacity: 0.3; }
        .pkg-empty p { font-size: 1.3rem; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="page-hero">
    <h1>Medical Packages</h1>
    <p>Comprehensive health packages tailored for your needs</p>
</div>

<div class="pkg-wrapper">

<?php if (empty($packages)): ?>
    <div class="pkg-empty">
        <i class="fa-solid fa-box-open"></i>
        <p>No packages available at the moment. Please check back soon.</p>
    </div>
<?php else: ?>

    <!-- Main viewer -->
    <div class="pkg-viewer">
        <img id="pkgMainImg"
             class="pkg-main-img"
             src="<?= htmlspecialchars($packages[0]['image']) ?>"
             alt="<?= htmlspecialchars($packages[0]['title'] ?: 'Medical Package') ?>"
             onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">

        <div class="pkg-controls">
            <button class="pkg-nav-btn" id="btnPrev" onclick="navigate(-1)" <?= count($packages) <= 1 ? 'disabled' : '' ?>>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="pkg-title" id="pkgTitle">
                <?= htmlspecialchars($packages[0]['title'] ?: '') ?>
            </div>
            <button class="pkg-nav-btn" id="btnNext" onclick="navigate(1)" <?= count($packages) <= 1 ? 'disabled' : '' ?>>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <?php if (count($packages) > 1): ?>
    <!-- Counter -->
    <div class="pkg-counter" id="pkgCounter">1 of <?= count($packages) ?></div>

    <!-- Dots -->
    <div class="pkg-dots" id="pkgDots">
        <?php foreach ($packages as $i => $p): ?>
        <div class="pkg-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goTo(<?= $i ?>)"></div>
        <?php endforeach; ?>
    </div>

    <!-- Thumbnail strip -->
    <div class="pkg-thumbs" id="pkgThumbs">
        <?php foreach ($packages as $i => $p): ?>
        <div class="pkg-thumb <?= $i === 0 ? 'active' : '' ?>" id="thumb-<?= $i ?>" onclick="goTo(<?= $i ?>)">
            <img src="<?= htmlspecialchars($p['image']) ?>"
                 alt="<?= htmlspecialchars($p['title'] ?: 'Package ' . ($i+1)) ?>"
                 onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<?php if (!empty($packages)): ?>
<script>
const packages = <?= json_encode(array_map(fn($p) => [
    'image' => $p['image'],
    'title' => $p['title'] ?: '',
], $packages)) ?>;

let current = 0;
const total  = packages.length;

function navigate(dir) {
    goTo((current + dir + total) % total);
}

function goTo(index) {
    if (index === current) return;
    current = index;

    const img = document.getElementById('pkgMainImg');
    img.classList.add('fading');

    setTimeout(() => {
        img.src = packages[current].image || '/hms/admin/images/default.jpg';
        img.alt = packages[current].title || 'Medical Package';
        document.getElementById('pkgTitle').textContent = packages[current].title || '';
        img.classList.remove('fading');
    }, 200);

    // Counter
    const counter = document.getElementById('pkgCounter');
    if (counter) counter.textContent = (current + 1) + ' of ' + total;

    // Dots
    document.querySelectorAll('.pkg-dot').forEach((d, i) => d.classList.toggle('active', i === current));

    // Thumbs
    document.querySelectorAll('.pkg-thumb').forEach((t, i) => t.classList.toggle('active', i === current));
    const activeThumb = document.getElementById('thumb-' + current);
    if (activeThumb) activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
}

// Keyboard nav
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  navigate(-1);
    if (e.key === 'ArrowRight') navigate(1);
});
</script>
<?php endif; ?>

</body>
</html>