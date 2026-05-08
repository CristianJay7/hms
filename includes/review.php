<?php
$reviews_res = mysqli_query($con, "SELECT * FROM reviews ORDER BY review_date DESC");
$reviews_pub = [];
while ($row = mysqli_fetch_assoc($reviews_res)) $reviews_pub[] = $row;
?>

<section id="review" class="review">

    <h1 class="heading">Our Patient Reviews</h1>
    <h3 class="title">What patients say about us</h3>

    <?php if (empty($reviews_pub)): ?>
        <p style="text-align:center;color:#aaa;padding:40px 0;">No reviews yet.</p>
    <?php else: ?>

    <div class="rv-outer">

        <button class="rv-arrow rv-prev" onclick="moveReview(-1)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="rv-track-wrap">
            <div class="rv-track" id="reviewTrack">

                <?php foreach ($reviews_pub as $rv):
                    $photo   = !empty($rv['photo']) ? htmlspecialchars($rv['photo']) : '';
                    $name    = htmlspecialchars($rv['patient_name']);
                    $initial = strtoupper(mb_substr($rv['patient_name'], 0, 1));
                    $rating  = intval($rv['rating']);
                    $date    = date('M d, Y', strtotime($rv['review_date']));
                ?>

                <div class="rv-card">

                    <div class="rv-stars">
                        <?php for ($s=1;$s<=5;$s++): ?>
                            <i class="fa-solid fa-star<?= $s <= $rating ? '' : ' rv-star-empty' ?>"></i>
                        <?php endfor; ?>
                        <span class="rv-rating-num"><?= $rating ?>.0</span>
                    </div>

                    <div class="rv-quote"><i class="fa-solid fa-quote-left"></i></div>

                    <p class="rv-text"><?= htmlspecialchars($rv['review_text']) ?></p>

                    <div class="rv-sep"></div>

                    <div class="rv-patient">
                        <?php if ($photo): ?>
                            <img class="rv-avatar" src="<?= $photo ?>">
                        <?php else: ?>
                            <div class="rv-avatar-init"><?= $initial ?></div>
                        <?php endif; ?>

                        <div class="rv-patient-info">
                            <strong><?= $name ?></strong>
                            <span><?= $date ?></span>
                        </div>
                    </div>

                </div>

                <?php endforeach; ?>

            </div>
        </div>

        <button class="rv-arrow rv-next" onclick="moveReview(1)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </div>

    <div class="rv-dots" id="reviewDots"></div>

    <?php endif; ?>

</section>

<style>
#review.review {
    background: linear-gradient(160deg, #f0f7f8 0%, #e8f4f5 50%, #f4f6f9 100%);
    padding: 70px 24px 60px;
}

.rv-outer {
    position: relative;
    display: flex;
    align-items: center;
    max-width: 1200px;
    margin: 40px auto 0;
}

.rv-track-wrap {
    overflow: hidden;
    flex: 1;
    padding: 16px 4px 20px;
}

.rv-track {
    display: flex;
    gap: 22px;
    transition: transform 0.45s ease;
}

/* ✅ FIXED CARD WIDTH (NO MORE JS WIDTH BUG) */
.rv-card {
    flex: 0 0 calc((100% - 44px) / 3);
    max-width: calc((100% - 44px) / 3);
    background: #fff;
    border-radius: 18px;
    padding: 28px 26px 24px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
    border-top: 4px solid #00b6bd;
    display: flex;
    flex-direction: column;
}

.rv-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 36px rgba(0, 182, 189, 0.15);
}

.rv-stars {
    display: flex;
    gap: 3px;
    margin-bottom: 14px;
}
.rv-stars .fa-star { color: #f4a40a; }
.rv-stars .rv-star-empty { color: #ddd; }

.rv-rating-num {
    margin-left: 6px;
    font-size: 1.3rem;
    font-weight: 700;
    color: #aaa;
}

.rv-quote {
    font-size: 2.5rem;
    color: #00b6bd;
    opacity: 0.25;
    margin-bottom: 10px;
}

/* ✅ FIX: NO TEXT CUTTING */
.rv-text {
    font-size: 1.2rem;
    color: #556677;
    line-height: 1.75;
    flex-grow: 1;
    margin-bottom: 20px;
}

.rv-sep {
    height: 1px;
    background: #eef1f5;
    margin-bottom: 18px;
}

.rv-patient {
    display: flex;
    align-items: center;
    gap: 12px;
}
.rv-avatar {
    width: 46px;
    height: 46px;
    min-width: 46px;   /* prevents shrinking */
    min-height: 46px;
    border-radius: 50%;
    object-fit: cover;
    object-position: center;
    flex-shrink: 0;    /* VERY IMPORTANT */
}   
.rv-avatar,
.rv-avatar-init {
    width: 46px;
    height: 46px;
    min-width: 46px;
    min-height: 46px;
    border-radius: 50%;
    flex-shrink: 0;
    font-size: 1.9rem;
    font-weight: 500;
}

.rv-avatar-init {
    background: linear-gradient(135deg, #1a3c5e, #00b6bd);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rv-patient-info strong {
    font-size: 1.3rem;
    color: #1a3c5e;
}
.rv-patient-info span {
    font-size: .99rem;
    color: #aab;
}

.rv-arrow {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid #00b6bd;
    background: #fff;
    color: #00b6bd;
    cursor: pointer;
}

.rv-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 28px;
}

/* responsive */
@media (max-width: 900px) {
    .rv-card {
        flex: 0 0 calc((100% - 22px) / 2);
        max-width: calc((100% - 22px) / 2);
    }
}

@media (max-width: 576px) {
    .rv-card {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
@media (max-width: 576px) {
    .rv-avatar,
    .rv-avatar-init {
        width: 50px;
        height: 50px;
    }
}
@media (max-width: 576px) {
    .rv-arrow {
        display: none;
    }
}
@media (max-width: 576px) {
    .rv-outer {
        padding: 0 6px;
    }
}
</style>

<script>
(function () {
    const track  = document.getElementById('reviewTrack');
    const dotsEl = document.getElementById('reviewDots');
    if (!track) return;

    const cards = Array.from(track.querySelectorAll('.rv-card'));
    let current = 0;

    function perPage() {
        if (window.innerWidth <= 576) return 1;
        if (window.innerWidth <= 900) return 2;
        return 3;
    }

    function totalPages() {
        return Math.ceil(cards.length / perPage());
    }

    function goTo(page) {
    const pp  = perPage();
    const max = totalPages() - 1;
    current   = Math.max(0, Math.min(page, max));

    const gap   = 20;
    const cardW = cards[0].offsetWidth;

    // exact shift based on actual card width + gap
    const shift = current * pp * (cardW + gap);

    track.style.transform = `translateX(-${shift}px)`;
}

    window.moveReview = (dir) => goTo(current + dir);

    /* ✅ MOBILE SWIPE ONLY */
    let startX = 0;

    track.addEventListener('touchstart', e => {
        if (window.innerWidth > 576) return;
        startX = e.touches[0].clientX;
    });

    track.addEventListener('touchend', e => {
        if (window.innerWidth > 576) return;

        let endX = e.changedTouches[0].clientX;
        let diff = startX - endX;

        if (Math.abs(diff) > 50) {
            if (diff > 0) moveReview(1);
            else moveReview(-1);
        }
    });

})();
</script>