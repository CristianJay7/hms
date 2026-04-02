<?php 
include 'includes/config.php';
include 'includes/info.php';

// Pagination
$per_page   = 9;
$page_num   = max(1, intval($_GET['page'] ?? 1));
$offset     = ($page_num - 1) * $per_page;

// Total count
$total_res   = mysqli_query($con, "SELECT COUNT(*) as total FROM blogs WHERE status='published'");
$total_count = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_count / $per_page);

// Fetch posts
$blogs_res = mysqli_query($con, "SELECT * FROM blogs WHERE status='published' ORDER BY published_date DESC LIMIT $per_page OFFSET $offset");
$blogs     = [];
while ($row = mysqli_fetch_assoc($blogs_res)) $blogs[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>News & Updates | Zamboanga Doctors' Hospital</title>
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
<style>
.blogs-page {
    padding: 80px 8%;
    background: #fff;
    min-height: 100vh;
}

.blogs-page .page-header {
    text-align: center;
    margin-bottom: 50px;
}

.blogs-page .page-header h1 {
    font-size: 4.5rem;
    color: #00b6bd;
    font-weight: 700;
    margin-bottom: 10px;
}

.blogs-page .page-header p {
    color: #888;
    font-size: 2.0rem;
    max-width: 600px;
    margin: 0 auto;
}

.blogs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 50px;
}

.blog-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
}

.blog-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.blog-card .card-content {
    padding: 20px;
}

.blog-card .card-date {
    font-size: 1rem;
    color: #333333;
    margin-bottom: 8px;
}

.blog-card .card-title {
    font-size: 2.05rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 10px;
    line-height: 1.4;
}

.blog-card .card-excerpt {
    font-size: 1.6rem;
    color: #666;
    line-height: 1.6;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-transform: none;
    white-space: pre-line;
}

.blog-card .card-link {
    display: inline-block;
    color: #00b6bd;
    font-size: 1.3rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.blog-card .card-link:hover { color: #1a3c5e; }
.blog-card .card-link i { margin-left: 4px; font-size: 0.75rem; }

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pagination a, .pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px; height: 40px;
    border-radius: 8px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination a {
    background: #fff;
    color: #1a3c5e;
    border: 1px solid #dde3ea;
}

.pagination a:hover { background: #00b6bd; color: #fff; border-color: #00b6bd; }
.pagination span.current { background: #00b6bd; color: #fff; border: 1px solid #00b6bd; }
.pagination span.dots { background: none; border: none; color: #aaa; }

.no-posts {
    text-align: center;
    color: #aaa;
    padding: 80px 0;
    font-size: 1rem;
    grid-column: 1 / -1;
}

@media (max-width: 991px) {
    .blogs-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 576px) {
    .blogs-page { padding: 60px 5%; }
    .blogs-grid { grid-template-columns: 1fr; }
    .blogs-page .page-header h1 { font-size: 1.8rem; }
}
</style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br>

<div class="blogs-page">

    <div class="page-header">
        <h1>News & Updates</h1>
        <p>Get the Latest Wellness Insights, Special Deals, and Local Events Tailored to You.</p>
    </div>

    <div class="blogs-grid">
        <?php if (empty($blogs)): ?>
            <p class="no-posts">No posts yet. Check back soon!</p>
        <?php else: ?>
            <?php foreach ($blogs as $b):
                $image = !empty($b['image']) ? $b['image'] : 'admin/images/default.jpg';
                $date  = $b['published_date'] ? date('M d, Y', strtotime($b['published_date'])) : '';
            ?>
            <div class="blog-card">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($b['title']) ?>"
                    onerror="this.onerror=null;this.src='admin/images/default.jpg'">
                <div class="card-content">
                    <?php if ($date): ?>
                        <div class="card-date"><i class="fas fa-calendar-alt"></i> <?= $date ?></div>
                    <?php endif; ?>
                    <div class="card-title"><?= htmlspecialchars($b['title']) ?></div>
                    <div class="card-excerpt"><?= htmlspecialchars($b['excerpt']) ?></div>
                    <a href="update-details.php?id=<?= $b['id'] ?>" class="card-link">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">

        <?php if ($page_num > 1): ?>
            <a href="blogs.php?page=<?= $page_num - 1 ?>"><i class="fas fa-chevron-left"></i></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++):
            if ($i == 1 || $i == $total_pages || abs($i - $page_num) <= 1): ?>
                <?php if ($i == $page_num): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="blogs.php?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php elseif (abs($i - $page_num) == 2): ?>
                <span class="dots">...</span>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page_num < $total_pages): ?>
            <a href="blogs.php?page=<?= $page_num + 1 ?>"><i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./js/main.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.querySelector('header').classList.add('header-light');
    });
</script>
</body>
</html> 