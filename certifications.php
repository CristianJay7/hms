<?php
require_once 'includes/config.php';
require_once 'includes/info.php';

$res   = mysqli_query($con, "SELECT * FROM certifications WHERE status='active' ORDER BY sort_order ASC, created_at DESC");
$certs = [];
while ($row = mysqli_fetch_assoc($res)) $certs[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certifications & Permits | Zamboanga Doctors' Hospital, Inc.</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php include 'includes/favicon.php'; ?>
    <?php include 'includes/header.php'; ?>

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
        .page-hero p { font-size: 1.3rem; opacity: 0.75; margin: 0; }

        /* ── Layout ── */
        .cert-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 52px 24px 80px;
        }

        /* ── Tab navigation (if multiple certs) ── */
        .cert-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 28px;
        }
        .cert-tab {
            padding: 8px 18px;
            border-radius: 30px;
            border: 2px solid #dde3ea;
            font-size: 1.3rem;
            font-weight: 600;
            color: #778899;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .cert-tab:hover { border-color: #1a3c5e; color: #1a3c5e; }
        .cert-tab.active {
            background: #1a3c5e;
            border-color: #1a3c5e;
            color: #fff;
        }
        .cert-tab .type-dot {
            width: 10px; height: 8px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.7;
        }

        /* ── Cert card ── */
        .cert-panel { display: none; }
        .cert-panel.active { display: block; }

        .cert-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .cert-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #eef1f5;
        }
        .cert-card-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cert-type-badge {
            font-size: 0.98rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cert-type-badge.pdf  { background:#fff0f0;color:#cc2233; }
        .cert-type-badge.docx { background:#e8f0ff;color:#1a6fcc; }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 18px;
            background: #1a3c5e;
            color: #fff;
            border-radius: 8px;
            font-size: 1.3rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-download:hover { background: #00b6bd; }

        /* ── PDF embed ── */
        .pdf-embed-wrap {
            width: 100%;
            background: #2a2a2a;
        }
        .pdf-embed-wrap iframe {
            display: block;
            width: 100%;
            height: 780px;
            border: none;
        }
        @media (max-width: 640px) {
            .pdf-embed-wrap iframe { height: 500px; }
        }

        /* ── DOCX preview (fallback via Google Docs Viewer) ── */
        .docx-embed-wrap {
            width: 100%;
            background: #f4f6f9;
        }
        .docx-embed-wrap iframe {
            display: block;
            width: 100%;
            height: 780px;
            border: none;
        }
        @media (max-width: 640px) {
            .docx-embed-wrap iframe { height: 500px; }
        }

        /* ── Empty state ── */
        .cert-empty {
            text-align: center;
            padding: 80px 24px;
            color: #aaa;
        }
        .cert-empty i { font-size: 3rem; margin-bottom: 16px; opacity: 0.3; }
        .cert-empty p { font-size: 1.3rem; }
    </style>
</head>
<body>


<div class="page-hero">
    <h1>Certifications & Permits</h1>
    <p>Our accreditations, licenses, and official permits</p>
</div>

<div class="cert-wrapper">

<?php if (empty($certs)): ?>
    <div class="cert-empty">
        <i class="fa-solid fa-certificate"></i>
        <p>No certifications available at the moment.</p>
    </div>
<?php else: ?>

    <?php if (count($certs) > 1): ?>
    <!-- Tab navigation -->
    <div class="cert-tabs" id="certTabs">
        <?php foreach ($certs as $i => $c): ?>
        <div class="cert-tab <?= $i === 0 ? 'active' : '' ?>"
             onclick="switchTab(<?= $i ?>)" id="tab-<?= $i ?>">
            <span><?= htmlspecialchars($c['title']) ?></span>
            <span class="cert-type-badge <?= $c['file_type'] ?>">
                <?= strtoupper($c['file_type']) ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Panels -->
    <?php foreach ($certs as $i => $c):
        $is_pdf  = $c['file_type'] === 'pdf';
        $file_url = htmlspecialchars($c['file']);
        // For DOCX, use Google Docs Viewer for inline preview
        $viewer_url = $is_pdf
            ? $file_url
            : 'https://docs.google.com/gview?url=' . urlencode('https://' . $_SERVER['HTTP_HOST'] . $c['file']) . '&embedded=true';
    ?>
    <div class="cert-panel <?= $i === 0 ? 'active' : '' ?>" id="panel-<?= $i ?>">
        <div class="cert-card">
            <div class="cert-card-header">
                <h2>
                    <i class="fa-solid <?= $is_pdf ? 'fa-file-pdf' : 'fa-file-word' ?>"
                       style="color:<?= $is_pdf ? '#cc2233' : '#1a6fcc' ?>;font-size:1.5rem;"></i>
                    <?= htmlspecialchars($c['title']) ?>
                    <span class="cert-type-badge <?= $c['file_type'] ?>"><?= strtoupper($c['file_type']) ?></span>
                </h2>
                <a href="<?= $file_url ?>" download target="_blank" class="btn-download">
                    <i class="fa-solid fa-download"></i> Download
                </a>
            </div>

            <?php if ($is_pdf): ?>
            <div class="pdf-embed-wrap">
                <iframe src="<?= $file_url ?>#toolbar=1&navpanes=0&scrollbar=1"
                    title="<?= htmlspecialchars($c['title']) ?>">
                    <p>Your browser doesn't support PDF preview.
                       <a href="<?= $file_url ?>">Download the PDF</a> to view it.
                    </p>
                </iframe>
            </div>
            <?php else: ?>
            <div class="docx-embed-wrap">
                <iframe src="<?= htmlspecialchars($viewer_url) ?>"
                    title="<?= htmlspecialchars($c['title']) ?>">
                </iframe>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<?php if (count($certs) > 1): ?>
<script>
function switchTab(index) {
    document.querySelectorAll('.cert-tab').forEach((t, i)   => t.classList.toggle('active', i === index));
    document.querySelectorAll('.cert-panel').forEach((p, i) => p.classList.toggle('active', i === index));
}
</script>
<?php endif; ?>

</body>
</html>