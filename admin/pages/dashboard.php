<?php
global $con;

// ── Counts ──
function db_count($con, $table) {
    $res = mysqli_query($con, "SELECT COUNT(*) as total FROM `$table`");
    return mysqli_fetch_assoc($res)['total'] ?? 0;
}

$count_doctors    = db_count($con, 'doctors');
$count_hmos       = db_count($con, 'hmos');
$count_services   = db_count($con, 'services');
$count_facilities = db_count($con, 'facilities');
$count_rooms      = db_count($con, 'roomrates');

// ── Recent Blog Posts ──
$blogs_res   = mysqli_query($con, "SELECT id, title, status, published_date FROM blogs ORDER BY created_at DESC LIMIT 5");
$recent_blogs = [];
while ($row = mysqli_fetch_assoc($blogs_res)) $recent_blogs[] = $row;

// ── Recent Reviews ──
$reviews_res    = mysqli_query($con, "SELECT id, patient_name, rating, review_date FROM reviews ORDER BY created_at DESC LIMIT 5");
$recent_reviews = [];
while ($row = mysqli_fetch_assoc($reviews_res)) $recent_reviews[] = $row;
?>

<style>
.dash-welcome {
    margin-bottom: 28px;
}
.dash-welcome h2 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 4px;
}
.dash-welcome p {
    font-size: 0.88rem;
    color: #999;
}

/* Stat Cards */
.dash-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border-left: 4px solid transparent;
    transition: transform 0.2s;
}
.stat-card:hover { transform: translateY(-7px); }

.stat-card:nth-child(1) { border-left-color: #00b6bd; }
.stat-card:nth-child(2) { border-left-color: #3498db; }
.stat-card:nth-child(3) { border-left-color: #2ecc71; }
.stat-card:nth-child(4) { border-left-color: #e67e22; }
.stat-card:nth-child(5) { border-left-color: #9b59b6; }
.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-card:nth-child(1) .stat-icon { background: rgba(0,182,189,0.12); color: #00b6bd; }
.stat-card:nth-child(2) .stat-icon { background: rgba(52,152,219,0.12); color: #3498db; }
.stat-card:nth-child(3) .stat-icon { background: rgba(46,204,113,0.12); color: #2ecc71; }
.stat-card:nth-child(4) .stat-icon { background: rgba(230,126,34,0.12);  color: #e67e22; }
.stat-card:nth-child(5) .stat-icon { background: rgba(155,89,182,0.12);  color: #9b59b6; }

.stat-info .stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1a3c5e;
    line-height: 1;
}
.stat-info .stat-label {
    font-size: 0.75rem;
    color: #aaa;
    font-weight: 500;
    margin-top: 3px;
}

/* Recent Sections */
.dash-recent {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.dash-panel {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.dash-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eef1f5;
}

.dash-panel-header h4 {
    font-size: 0.92rem;
    font-weight: 700;
    color: #1a3c5e;
}

.dash-panel-header a {
    font-size: 0.78rem;
    color: #00b6bd;
    text-decoration: none;
    font-weight: 600;
}
.dash-panel-header a:hover { text-decoration: underline; }

.dash-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f5f6f8;
    gap: 10px;
}
.dash-row:last-child { border-bottom: none; }

.dash-row-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #333;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dash-row-meta {
    font-size: 0.75rem;
    color: #aaa;
    flex-shrink: 0;
}

.badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    flex-shrink: 0;
}
.badge-published { background: #e6f9f0; color: #1a7a4a; }
.badge-draft     { background: #fff8e6; color: #b07a00; }

.stars { color: #f4a40a; font-size: 0.8rem; }

.no-data {
    text-align: center;
    color: #ccc;
    font-size: 0.85rem;
    padding: 20px 0;
}

@media (max-width: 1100px) {
    .dash-stats { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .dash-stats  { grid-template-columns: repeat(2, 1fr); }
    .dash-recent { grid-template-columns: 1fr; }
}
</style>

<div class="dash-welcome">
    <h2>👋 Welcome back!</h2>
    <p><?= date('l, F d, Y') ?> — Here's a quick overview of your site.</p>
</div>

<!-- Stat Cards -->
<div class="dash-stats">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-doctor"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $count_doctors ?></div>
            <div class="stat-label">Doctors</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-shield-heart"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $count_hmos ?></div>
            <div class="stat-label">HMOs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $count_services ?></div>
            <div class="stat-label">Services</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-building"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $count_facilities ?></div>
            <div class="stat-label">Facilities</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-bed"></i></div>
        <div class="stat-info">
            <div class="stat-num"><?= $count_rooms ?></div>
            <div class="stat-label">Room Rates</div>
        </div>
    </div>
    



    
</div>

<!-- Recent Sections -->
<div class="dash-recent">

    <!-- Recent Blog Posts -->
    <div class="dash-panel">
        <div class="dash-panel-header">
            <h4>📰 Recent Blog Posts</h4>
            <a href="index.php?page=blog">View All →</a>
        </div>
        <?php if (empty($recent_blogs)): ?>
            <p class="no-data">No blog posts yet.</p>
        <?php else: ?>
            <?php foreach ($recent_blogs as $b): ?>
            <div class="dash-row">
                <div class="dash-row-title"><?= htmlspecialchars($b['title']) ?></div>
                <span class="badge <?= $b['status'] === 'published' ? 'badge-published' : 'badge-draft' ?>">
                    <?= ucfirst($b['status']) ?>
                </span>
                <div class="dash-row-meta">
                    <?= $b['published_date'] ? date('M d', strtotime($b['published_date'])) : '—' ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Recent Reviews -->
    <div class="dash-panel">
        <div class="dash-panel-header">
            <h4>⭐ Recent Reviews</h4>
            <a href="index.php?page=reviews">View All →</a>
        </div>
        <?php if (empty($recent_reviews)): ?>
            <p class="no-data">No reviews yet.</p>
        <?php else: ?>
            <?php foreach ($recent_reviews as $r): ?>
            <div class="dash-row">
                <div class="dash-row-title"><?= htmlspecialchars($r['patient_name']) ?></div>
                <div class="stars">
                    <?= str_repeat('★', $r['rating']) ?><span style="color:#ddd;"><?= str_repeat('★', 5 - $r['rating']) ?></span>
                </div>
                <div class="dash-row-meta">
                    <?= $r['review_date'] ? date('M d', strtotime($r['review_date'])) : '—' ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>