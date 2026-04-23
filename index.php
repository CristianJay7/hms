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

/* ── VERSION A: Contact Topbar ── */
/* (styles are inside topbar-contact.php) */

/* ── VERSION B: Hero Contact Strip ── */
#hero-contact-strip {
    display: inline-flex;
    align-items: stretch;
    gap: 0;
    margin-top: 32px;
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 2px solid rgba(255,255,255,0.22);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}

.hcs-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 22px;
    text-decoration: none;
    color: #fff;
    transition: background 0.2s;
    cursor: default;
}
a.hcs-item { cursor: pointer; }
a.hcs-item:hover {
    background: rgba(0,182,189,0.18);
}

.hcs-icon {
    width: 36px; height: 36px;
    background: rgba(0,182,189,0.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1.5rem;
    color: #00b6bd;
    border: 1px solid rgba(0,182,189,0.4);
}

.hcs-text {
    display: flex;
    flex-direction: column;
    text-align: left;
}
.hcs-label {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.55);
    line-height: 1;
    margin-bottom: 3px;
}
.hcs-value {
    font-size: 1.5rem;
    font-weight: 500;
    color: #fff;
    white-space: nowrap;
}

.hcs-divider {
    width: 2px;
    background: rgba(255,255,255,0.14);
    flex-shrink: 0;
}

/* Mobile stacking */
@media (max-width: 680px) {
    #hero-contact-strip {
        flex-direction: column;
        width: 100%;
        max-width: 320px;
    }
    .hcs-divider {
        width: 100%; height: 1px;
    }
    .hcs-item { padding: 12px 18px; }
}
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
               <!--<a href="contact"><button class="button" style="margin-right: 10px; border-radius: 12px;">Keep In Touch.</button></a> -->

                <div id="hero-contact-strip">
                <a class="hcs-item" href="tel:+639272753029">
                    <span class="hcs-icon"><i class="fa-solid fa-phone"></i></span>
                    <span class="hcs-text">
                        <span class="hcs-label">Call Us</span>
                        <span class="hcs-value">+63927-275-3097</span>
                    </span>
                </a>
                <div class="hcs-divider"></div>
                <a class="hcs-item" href="https://mail.google.com/mail/?view=cm&to=zdh1964@yahoo.com&su=Inquiry&body=Hello, I would like to inquire about..." target="_blank">
                    <span class="hcs-icon"><i class="fa-solid fa-envelope"></i></span>
                    <span class="hcs-text">
                        <span class="hcs-label">Email Us</span>
                        <span class="hcs-value">zdh1964@yahoo.com</span>
                    </span>
                </a>
                <div class="hcs-divider"></div>
                <a class="hcs-item" href="#">
                    <span class="hcs-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <span class="hcs-text">
                        <span class="hcs-label">Find Us</span>
                        <span class="hcs-value">Veteranz Ave., Zamboanga City</span>
                    </span>
                </a>
            </div>


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