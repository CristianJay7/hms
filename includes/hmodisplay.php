<?php
$hmo_res   = mysqli_query($con, "SELECT * FROM hmos ORDER BY created_at ASC");
$hmos_pub  = [];
while ($row = mysqli_fetch_assoc($hmo_res)) $hmos_pub[] = $row;
$hmo_scroll = array_merge($hmos_pub, $hmos_pub); // duplicate for seamless loop
?>

<?php if (!empty($hmos_pub)): ?>
<section class="hmo-section">

    <h1 class="heading">HMO & Insurance Partners</h1>

    <div class="hmo-track-wrapper">
        <!-- Left fade -->
        <div class="hmo-fade hmo-fade-left"></div>

        <!-- Scrolling track -->
        <div class="hmo-track">
            <?php foreach ($hmo_scroll as $h):
                $logo = !empty($h['logo']) ? $h['logo'] : 'admin/images/default.jpg';
            ?>
            <div class="hmo-card">
                <img src="<?= htmlspecialchars($logo) ?>"
                     alt="<?= htmlspecialchars($h['name']) ?>"
                     onerror="this.onerror=null;this.src='admin/images/default.jpg'">
                
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Right fade -->
        <div class="hmo-fade hmo-fade-right"></div>
    </div>

</section>

<style>
.hmo-section {
    padding: 60px 0;
    background: #fff;
    overflow: hidden;
    text-align: center;
}

.hmo-track-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin-top: 40px;
}

/* Fade edges */
.hmo-fade {
    position: absolute;
    top: 0;
    height: 100%;
    width: 180px;
    z-index: 2;
    pointer-events: none;
}

.hmo-fade-left {
    left: 0;
    background: linear-gradient(to right, #f9f9f9 0%, transparent 100%);
}

.hmo-fade-right {
    right: 0;
    background: linear-gradient(to left, #f9f9f9 0%, transparent 100%);
}

/* Scrolling track */
.hmo-track {
    display: flex;
    gap: 28px;
    width: max-content;
    animation: hmo-scroll <?= count($hmos_pub) * 3 ?>s linear infinite;
}

.hmo-track:hover {
    animation-play-state: paused;
}

@keyframes hmo-scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* HMO Card */
.hmo-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #fff;
    border-radius: 14px;
    padding: 18px 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    min-width: 130px;
    max-width: 130px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    flex-shrink: 0;
}

.hmo-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 28px rgba(0,182,189,0.15);
}

.hmo-card img {
    width: 90px;
    height: 70px;
    object-fit: contain;
}

.hmo-card span {
    font-size: 0.99rem;
    font-weight: 550;
    color: #1a3c5e;
    text-align: center;
    line-height: 1.3;
}

@media (max-width: 768px) {
    .heading { font-size: 1.5rem; }
    .title   { font-size: 1.1rem; }
    h1       { font-size: 2rem; }
    p        { font-size: 1rem; }
}
</style>
<?php endif; ?>