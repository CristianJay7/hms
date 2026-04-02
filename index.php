<!--
  Developed by: Cristian Jay Caasi (Se7en)
  Website: TBA
  Year: 2026
  AI Assisted 
-->
<?php include 'includes/config.php'; ?>
<?php include 'includes/info.php'; ?>
<?php
// Fetch all services for navbar dropdown
global $con;
$nav_services = [];
if (isset($con)) {
    $result = mysqli_query($con, "SELECT id, name FROM services ORDER BY created_at ASC");
    while ($row = mysqli_fetch_assoc($result)) $nav_services[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga Doctors' Hospital, Inc. | Home </title>
    <!-- favicon  -->
    <link rel="icon" href="/hms/images/favicon.png" type="image/png">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="/hms/css/style.css">
    <style>

</style>
</head>

<body>
   
<!-- home section start  --> 

<!-- header navbar section start  -->

<?php include 'includes/header.php'; ?>


<!-- header navbar section start  -->



<?php if ($home_bg_type === 'video' && !empty($home_bg_video)): ?>
<section id="home" class="home" style="position:relative;overflow:hidden;">
    <video autoplay muted loop playsinline
        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;">
        <source src="<?= htmlspecialchars($home_bg_video) ?>" type="video/mp4">
    </video>
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);z-index:1;"></div>
<?php else: ?>
<section id="home" class="home" style="background-image:url('<?= $home_bg_image ?>');">
<?php endif; ?>
    <div class="row" style="position:relative;z-index:2;">
            <!-- home content -->
            <div class="content">
            <h1><?= $home_tagline ?></h1>
                <p style="text-transform: none;"><?= $home_subtext ?></p><br>
                <a href="contact"><button class="button">Get In Touch.</button></a>
            </div>
        </div>
    </section>

<!-- home section end  -->



<!-- whycoose us section start  -->

<?php include '<includes/why-section.php'; ?>

<!-- whycoose us section end  -->




<!-- about section start  -->

<?php include '<includes/faci.php'; ?>
     
<!-- about section end  -->



<!-- services section start -->
  
<?php include '<includes/services-section.php'; ?>

<!-- services section end -->





<!-- review services start -->

<?php include '<includes/review.php'; ?>
   
<!-- review section end -->






<!-- hmo section start  -->

<?php include '<includes/hmodisplay.php'; ?>

<!-- hmo section end  -->



<!-- blog section start  -->

<?php
$blogs_res = mysqli_query($con, "SELECT * FROM blogs WHERE status='published' ORDER BY published_date DESC LIMIT 3");
$blogs_pub = [];
while ($row = mysqli_fetch_assoc($blogs_res)) $blogs_pub[] = $row;
?>

<section id="blog" class="blog">
    <h1 class="heading">News & Updates</h1>
    <h3 class="title">Stay up-to-date with wellness advice, special offers, and local events crafted for you.</h3>

    <div class="box-container">
        <?php if (empty($blogs_pub)): ?>
            <p style="text-align:center;color:#aaa;width:100%;">No posts yet.</p>
        <?php else: ?>
            <?php foreach ($blogs_pub as $b):
                $image = !empty($b['image']) ? $b['image'] : '/hms/admin/images/default.jpg';
            ?>
            <div class="box">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($b['title']) ?>"
                    onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                <div class="content">
                    <a href="update/<?= $b['id'] ?>">
                        <h2><?= htmlspecialchars($b['title']) ?></h2>
                    </a>
                    <p style="text-transform: none;"><?php
    $words = explode(' ', $b['excerpt']);
    echo htmlspecialchars(implode(' ', array_slice($words, 0, 8)));
    echo count($words) > 8 ? '...' : '';
?></p>
                    <a href="update/<?= $b['id'] ?>">
                        <button class="button">Learn more</button>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if (mysqli_num_rows(mysqli_query($con, "SELECT id FROM blogs WHERE status='published'")) > 3): ?>
            <div style="text-align:center; margin-top:40px;">
                <a href="/hms/blog"><button class="button">View All Posts</button></a>
            </div>
<?php endif; ?>
</section>
<!-- blog section end  -->


<!-- footer section start  -->

<br><br><?php include '<includes/footer.php'; ?>

<!-- footer section end  -->
    

<button id="scrollTopBtn" onclick="scrollToTop()" title="Back to top">
  <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- jquery cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- custom js file link  -->
<script src="./js/main.js"></script>

 <script>
        document.addEventListener("DOMContentLoaded", () => {
  // Selecting all the boxes
        const boxes = document.querySelectorAll('.blog .box-container .box');

        const options = {
            threshold: 0.3 // Trigger when 30% of the box is visible
        };

  // IntersectionObserver callback function
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible'); // Add 'visible' class to trigger animation
                observer.unobserve(entry.target); // Stop observing once the animation is triggered
            }
        });
    }, options);

  // Start observing each box element
            boxes.forEach(box => {
                observer.observe(box);
        });
    });
</script>
</body>

</html>