<?php 
include 'includes/config.php';
include 'includes/info.php';

// Get blog ID
$id  = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: index.php#blog");
    exit();
}

// Fetch the blog post
$res  = mysqli_query($con, "SELECT * FROM blogs WHERE id=$id AND status='published'");
$blog = mysqli_fetch_assoc($res);

if (!$blog) {
    header("Location: index.php#blog");
    exit();
}



$photos_res = mysqli_query($con, "SELECT * FROM blog_photos WHERE blog_id=$id ORDER BY sort_order ASC, created_at ASC");
$photos     = [];
while ($row = mysqli_fetch_assoc($photos_res)) $photos[] = $row;



// Fetch all published posts for sidebar
$sidebar_res   = mysqli_query($con, "SELECT id, title FROM blogs WHERE status='published' ORDER BY published_date DESC");
$sidebar_posts = [];
while ($row = mysqli_fetch_assoc($sidebar_res)) $sidebar_posts[] = $row;

$image = !empty($blog['image']) ? $blog['image'] : '/hms/admin/images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($blog['title']) ?> | Zamboanga Doctors' Hospital</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php include 'includes/favicon.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="/hms/css/style.css">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  background: #f2f3f5;
}

.section-wrapper {
  padding: 80px 8%;
  display: flex;
  justify-content: space-between;
  gap: 40px;
}

/* Main Content */
.content-container {
  display: flex;
  gap: 60px;
  align-items: flex-start;
  flex: 1;
}

.image-card {
  flex: 1;
  max-width: 520px;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

.image-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.text-content {
  flex: 1.2;
}

.text-content .post-meta {
  font-size: 1.3rem;
  color: #333333;
  margin-bottom: 12px;
}

.text-content h1 {
  font-size: 42px;
  font-weight: 700;
  color: #00b6bd;
  margin-bottom: 20px;
}

.text-content p {
  font-size: 16px;
  line-height: 1.8;
  color: #333;
  margin-bottom: 20px;
  white-space: pre-line;
}

/* Sidebar */
.sidebar {
  position: sticky;
  top: 100px;
  width: 280px;
  flex-shrink: 0;
  padding: 24px;
  background: #fff;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  border-radius: 12px;
  align-self: flex-start;
}

.sidebar h3 {
  font-size: 1.7rem;
  font-weight: 700;
  color: #1a3c5e;
  margin-bottom: 16px;
  padding-bottom: 10px;
  border-bottom: 2px solid #eef1f5;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar ul li {
  margin: 10px 0;
  border-bottom: 1px solid #f0f0f0;
  padding-bottom: 10px;
}

.sidebar ul li:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.sidebar ul li a {
  text-decoration: none;
  color: #444;
  font-weight: 500;
  font-size: 1.3rem;
  transition: color 0.2s;
  display: block;
  line-height: 1.4;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
  color: #00b6bd;
}

/* Back Button */
.back-btn {
  margin-top: 40px;
  display: inline-block;
  background: #00b6bd;
  color: #fff;
  padding: 14px 30px;
  border-radius: 50px;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.back-btn:hover {
  background: #1a3c5e;
  transform: translateY(-3px);
  color: #fff;
}

/* Responsive */
@media (max-width: 1024px) {
  .content-container { flex-direction: column; gap: 30px; }
  .image-card { max-width: 100%; }
  .text-content h1 { font-size: 34px; }
  .sidebar { display: none; }
}

@media (max-width: 768px) {
  .section-wrapper { padding: 60px 5%; }
  .text-content h1 { font-size: 28px; }
  .text-content p  { font-size: 15px; }
}

@media (max-width: 480px) {
  .image-card { order: -1; max-width: 100%; }
  .text-content h1 { font-size: 24px; }
  .text-content p  { font-size: 14px; }
  .back-btn { padding: 10px 20px; font-size: 12px; }
}



.blog-gallery {
    margin-top: 40px;
}
 
.gallery-heading {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 16px;
}
 
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}
 
.gallery-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 1;
}
 
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}
 
.gallery-item:hover img { transform: scale(1.05); }
 
.gallery-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: #fff;
    font-size: 1.4rem;
}
 
.gallery-item:hover .gallery-overlay { opacity: 1; }
</style>
</head>

<body>
<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br><br>

<section class="section-wrapper">

  <!-- Main Content -->
  <div class="content-container">

    <div class="image-card">
      <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($blog['title']) ?>"
           onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
    </div>

    <div class="text-content">
      <div class="post-meta">
        <?= $blog['published_date'] ? date('F d, Y', strtotime($blog['published_date'])) : '' ?>
      </div>
      <h1><?= htmlspecialchars($blog['title']) ?></h1>
      <p style="text-align: justify; hyphens: auto; text-transform: none; white-space: pre-line;"><?= htmlspecialchars($blog['content'] ?: $blog['excerpt']) ?></p>
      <a href="/hms/blog" class="back-btn">← Back to News & Updates</a> 
       <?php if (!empty($photos)): ?>
<div class="blog-gallery">
    <h3 class="gallery-heading">Photo Gallery</h3>
    <div class="gallery-grid">
        <?php foreach ($photos as $p): ?>
        <div class="gallery-item" onclick="openLightbox('<?= htmlspecialchars($p['photo']) ?>')">
            <img src="<?= htmlspecialchars($p['photo']) ?>" alt="Gallery photo"
                onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
            <div class="gallery-overlay"><i class="fas fa-expand"></i></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
 
<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()" style="
    display:none;position:fixed;inset:0;z-index:9999;
    background:rgba(0,0,0,0.9);align-items:center;justify-content:center;cursor:pointer;">
    <img id="lightboxImg" src="" style="max-width:90%;max-height:90vh;border-radius:8px;object-fit:contain;">
    <button onclick="closeLightbox()" style="
        position:fixed;top:20px;right:28px;background:none;border:none;
        color:#fff;font-size:2rem;cursor:pointer;">✕</button>
</div>
<?php endif; ?>

    </div>

  </div>





  <!-- Sidebar -->
  <div class="sidebar">
    <h3>Other Posts</h3>
    <ul>
      <?php foreach ($sidebar_posts as $sp): ?>
        <li>
          <a href="update/<?= $sp['id'] ?>"
             class="<?= $sp['id'] == $id ? 'active' : '' ?>">
            <?= htmlspecialchars($sp['title']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

</section>

<?php include 'includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/hms//js/main.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    document.querySelector('header').classList.add('header-light');
  });



function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightboxImg').src = src;
    lb.style.display = 'flex';
}
 
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.getElementById('lightboxImg').src = '';
}
 
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
});
</script>
</body>
</html>