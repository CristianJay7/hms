<?php
$reviews_res = mysqli_query($con, "SELECT * FROM reviews ORDER BY review_date DESC");
$reviews_pub = [];
while ($row = mysqli_fetch_assoc($reviews_res)) $reviews_pub[] = $row;
?>

<!-- review section start -->
<section id="review" class="review">

    <h1 class="heading">our patient review</h1>
    <h3 class="title">what patients say about us</h3>

    <div class="review-carousel-wrapper">
        <button class="review-nav prev" onclick="moveReview(-1)">&#10094;</button>

        <div class="box-container" id="reviewTrack">
            <?php if (empty($reviews_pub)): ?>
                <p style="text-align:center;color:#aaa;width:100%;">No reviews yet.</p>
            <?php else: ?>
                <?php foreach ($reviews_pub as $rv):
                    $photo = !empty($rv['photo']) ? $rv['photo'] : 'admin/images/default.jpg';
                ?>
                <div class="box">
                    <i class="fas fa-quote-left"></i>
                    <div class="review-stars">
                        <?php for($s=1;$s<=5;$s++): ?>
                            <span style="color:<?= $s <= intval($rv['rating']) ? '#f4a40a' : '#ddd' ?>;">★</span>
                        <?php endfor; ?>
                    </div>
                    <p style="text-transform: none;"><?= htmlspecialchars($rv['review_text']) ?></p>
                    <div class="images">
                        <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($rv['patient_name']) ?>"
                            onerror="this.onerror=null;this.src='admin/images/default.jpg'">
                        <div class="info">
                            <h3><?= htmlspecialchars($rv['patient_name']) ?></h3>
                            <span><?= date('M d, Y', strtotime($rv['review_date'])) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button class="review-nav next" onclick="moveReview(1)">&#10095;</button>
    </div>

    <!-- Dots -->
    <div class="review-dots" id="reviewDots"></div>
<br><br><br>
</section>
<!-- review section end -->

<style>
.review-carousel-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    overflow: hidden;
}

#reviewTrack {
    display: flex !important;
    flex-wrap: nowrap !important;
    gap: 20px;
    transition: transform 0.4s ease;
    width: 100%;
}

#reviewTrack .box {
    min-width: calc((100% - 60px) / 4);
    flex-shrink: 0;
}

.review-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: #00b6bd;
    color: #fff;
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
}
.review-nav:hover { background: #1a3c5e; }
.review-nav.prev { left: 0 }
.review-nav.next { right: 0 }

.review-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 2px;
}
.review-dots span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ddd;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-block;
}
.review-dots span.active { background: #00b6bd; }

.review-stars { margin-bottom: 8px; font-size: 1rem; }
.review-stars span {font-size: 2rem;}

@media (max-width: 991px) {
    #reviewTrack .box { min-width: calc((100% - 20px) / 2); }
}
@media (max-width: 576px) {
    #reviewTrack .box { min-width: 100%; }
}
</style>

<script>
(function() {
    const track  = document.getElementById('reviewTrack');
    const dotsEl = document.getElementById('reviewDots');
    if (!track) return;

    const boxes      = track.querySelectorAll('.box');
    const total      = boxes.length;
    let   current    = 0;
    let   perPage    = getPerPage();

    function getPerPage() {
        if (window.innerWidth <= 576)  return 1;
        if (window.innerWidth <= 991)  return 2;
        return 4;
    }

    function totalPages() { return Math.ceil(total / perPage); }

    // Build dots
    function buildDots() {
        dotsEl.innerHTML = '';
        for (let i = 0; i < totalPages(); i++) {
            const dot = document.createElement('span');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(dot);
        }
    }

    function updateDots() {
        dotsEl.querySelectorAll('span').forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    function goTo(page) {
        current = Math.max(0, Math.min(page, totalPages() - 1));
        const boxWidth  = boxes[0].offsetWidth + 20; // gap
        track.style.transform = `translateX(-${current * perPage * boxWidth}px)`;
        updateDots();
    }

    window.moveReview = function(dir) {
        goTo(current + dir);
    };

    window.addEventListener('resize', () => {
        perPage = getPerPage();
        current = 0;
        buildDots();
        goTo(0);
    });

    buildDots();
    goTo(0);
})();
</script>