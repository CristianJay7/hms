<?php
include 'includes/config.php';
include 'includes/info.php';

// Fetch open jobs
$jobs_res = mysqli_query($con, "SELECT * FROM career_jobs WHERE status='open' ORDER BY posted_date DESC");
$jobs     = [];
while ($row = mysqli_fetch_assoc($jobs_res)) $jobs[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Careers | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<?php include 'includes/legal_style.php'; ?>
<style>
/* Job listings */
.jobs-section { margin-top: 40px; }

.jobs-section h2 {
    font-size: 2.4rem;
    font-weight: 800;
    color: #1a3c5e;
    margin-bottom: 8px;
}

.jobs-section .jobs-sub {
    font-size: 1.3rem;
    color: #999;
    margin-bottom: 28px;
}

.job-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px 32px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    margin-bottom: 16px;
    border-left: 4px solid #00b6bd;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0,182,189,0.12);
}

.job-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 12px;

}

.job-card-header h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1a3c5e;
    margin: 0;
    margin-bottom: 10px;
}

.job-open-badge {
    background: #e6f9f0;
    color: #1a7a4a;
    font-size: 1rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    flex-shrink: 0;
}

.job-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.job-meta span {
    font-size: 1.3rem;
    color: #888;
    display: flex;
    align-items: center;
    gap: 5px;
}

.job-meta span i { color: #00b6bd; }

.job-desc {
    font-size: 1.3rem;
    color: #555;
    line-height: 1.7;
    margin-bottom: 16px;
    white-space: pre-line;
}

.job-requirements {
    background: #f8fafc;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 16px;
}

.job-requirements h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.job-requirements ul {
    list-style: none;
    padding: 0;
}

.job-requirements ul li {
    font-size: 1.2rem;
    color: #555;
    padding: 4px 0;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
}

.job-requirements ul li::before {
    content: '✓';
    color: #00b6bd;
    font-weight: 700;
    flex-shrink: 0;
}

.job-apply-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: #1a3c5e;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.3rem;
    transition: background 0.2s;
}
.job-apply-btn:hover { background: #00b6bd; }

.job-body { display: none; }
.job-card.expanded .job-body { display: block; }

.job-toggle {
    font-size: 1.2rem;
    color: #00b6bd;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    border: none;
    background: none;
    padding: 0;
    width: auto;
}

.no-jobs {
    text-align: center;
    padding: 60px 20px;
    color: #aaa;
}
.no-jobs i { font-size: 3.0rem; margin-bottom: 12px; display: block; color: #ddd; }
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>


<div class="legal-page">
    <div class="legal-hero" style="background: linear-gradient(135deg, #0d2b3e 0%, #1a3c5e 100%);">
        <div class="legal-hero-icon" style="margin-top: 20px;"><i class="fas fa-briefcase-medical"></i></div>
        <h1><?= info('careers_title', 'Careers') ?></h1>
        <p><?= info('careers_intro') ?></p>
    </div>

    <div class="legal-container">

        <!-- Why Work With Us -->
        <div class="careers-why">
            <div class="careers-why-icon"><i class="fas fa-star"></i></div>
            <div>
                <h2><?= info('careers_why_title', 'Why Work With Us?') ?></h2>
                <p><?= info('careers_why_desc') ?></p>
            </div>
        </div>

        <!-- Perks -->
        <div class="careers-perks">
            <div class="perk-item"><i class="fas fa-heart-pulse"></i><span>Health Benefits</span></div>
            <div class="perk-item"><i class="fas fa-graduation-cap"></i><span>Training & Development</span></div>
            <div class="perk-item"><i class="fas fa-users"></i><span>Collaborative Team</span></div>
            <div class="perk-item"><i class="fas fa-chart-line"></i><span>Career Growth</span></div>
            <div class="perk-item"><i class="fas fa-hand-holding-dollar"></i><span>Competitive Pay</span></div>
        </div>

        <!-- Job Listings -->
        <div class="jobs-section">
            <h2>
                <?php if (!empty($jobs)): ?>
                    <span style="color:#00b6bd;"><?= count($jobs) ?></span> Open Position<?= count($jobs) !== 1 ? 's' : '' ?>
                <?php else: ?>
                    Open Positions
                <?php endif; ?>
            </h2>
            <p class="jobs-sub">Click on a position to see full details and requirements.</p>

            <?php if (empty($jobs)): ?>
                <div class="no-jobs">
                    <i class="fas fa-briefcase"></i>
                    <p>No open positions at the moment.</p>
                    <p style="font-size:1.3rem;margin-top:8px;">Check back soon or send your resume to <a href="mailto:<?= info('careers_email') ?>" style="color:#00b6bd;"><?= info('careers_email') ?></a></p>
                </div>
            <?php else: ?>
                <?php foreach ($jobs as $j): ?>
                <div class="job-card" id="job-<?= $j['id'] ?>">
                    <div class="job-card-header">
                        <div>
                            <h3><?= htmlspecialchars($j['title']) ?></h3>
                            <div class="job-meta">
                                <?php if ($j['department']): ?>
                                    <span><i class="fas fa-building"></i><?= htmlspecialchars($j['department']) ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-clock"></i><?= htmlspecialchars($j['type']) ?></span>
                                <span><i class="fas fa-location-dot"></i><?= htmlspecialchars($j['location']) ?></span>
                                <?php if ($j['posted_date']): ?>
                                    <span><i class="fas fa-calendar"></i>Posted <?= date('M d, Y', strtotime($j['posted_date'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="job-open-badge">Now Hiring</span>
                    </div>

                    <button class="job-toggle" onclick="toggleJob(<?= $j['id'] ?>)">
                        <span id="toggle-text-<?= $j['id'] ?>">View Details</span>
                        <i class="fas fa-chevron-down" id="toggle-icon-<?= $j['id'] ?>"></i>
                    </button>

                    <div class="job-body" id="job-body-<?= $j['id'] ?>">
                        <br>
                        <?php if ($j['description']): ?>
                        <div class="job-desc"><?= htmlspecialchars($j['description']) ?></div>
                        <?php endif; ?>

                        <?php if ($j['requirements']): ?>
                        <div class="job-requirements">
                            <h4>Requirements</h4>
                            <ul>
                                <?php
                                $reqs = array_filter(array_map('trim', explode("\n", $j['requirements'])));
                                foreach ($reqs as $req):
                                    $req = ltrim($req, '-• ');
                                    if ($req):
                                ?>
                                <li><?= htmlspecialchars($req) ?></li>
                                <?php endif; endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <a href="https://mail.google.com/mail/?view=cm&to=<?= urlencode(info('careers_email')) ?>&su=<?= urlencode('Application: ' . $j['title']) ?>" target="_blank" class="job-apply-btn">
    <i class="fas fa-paper-plane"></i> Apply for this Position
</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- How to Apply -->
        <div class="legal-card" style="margin-top:32px;">
            <div class="legal-section">
                <div class="legal-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="legal-content">
                    <h2>How to Apply</h2>
                    <p ><?= info('careers_note') ?></p>
                    <a href="https://mail.google.com/mail/?view=cm&to=<?= urlencode(info('careers_email')) ?>" class="careers-btn" target="_blank" style="margin-top:14px;">
                        <i class="fas fa-envelope"></i> <?= info('careers_email') ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>
function toggleJob(id) {
    const body = document.getElementById('job-body-' + id);
    const card = document.getElementById('job-' + id);
    const text = document.getElementById('toggle-text-' + id);
    const icon = document.getElementById('toggle-icon-' + id);
    const open = card.classList.toggle('expanded');
    text.textContent = open ? 'Hide Details' : 'View Details';
    icon.style.transform = open ? 'rotate(180deg)' : 'rotate(0)';
    icon.style.transition = 'transform 0.3s ease';
    if (open) body.style.display = 'block';
    else body.style.display = 'none';
}

window.addEventListener('DOMContentLoaded',function(){
    document.querySelector('header').classList.add('header-light');
});
</script>
</body>
</html>