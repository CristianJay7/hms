<!-- about section start  -->

<?php
// Fetch first 4 facilities for homepage preview
$preview = [];
$result  = mysqli_query($con, "SELECT * FROM facilities ORDER BY created_at ASC LIMIT 4");
while ($row = mysqli_fetch_assoc($result)) $preview[] = $row;
?>
<?php
$total_facilities = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM facilities"))['total'];
$remaining = max(0, $total_facilities - 4);
?>
<section id="about" class="about">

    <!-- Header -->
    <div class="about__header">
        <div>
            <p class="about__eyebrow">Inside Our Hospital</p>
            <h2 class="about__heading">Our <span>Facilities</span></h2>
            <p class="about__subtext">Explore the spaces built for your healing and comfort.</p>
        </div>
       
    </div>

    <!-- Grid -->
    <div class="about__grid">

        <?php foreach ($preview as $i => $f): ?>
        <a href="facility-details.php?id=<?= $f['id'] ?>" class="fac-card">
            <img src="<?= htmlspecialchars($f['image'] ?: '/hms/admin/images/default.jpg') ?>"
                 alt="<?= htmlspecialchars($f['name']) ?>"
                 onerror="this.src='/hms/admin/images/default.jpg'">
            <div class="fac-card__overlay">
                <?php if ($i === 0): ?>
                    <span class="fac-card__tag">Featured</span>
                <?php endif; ?>
                <h3 class="fac-card__name"><?= htmlspecialchars($f['name']) ?></h3>
                <p class="fac-card__desc"><?= htmlspecialchars(mb_strimwidth($f['description'] ?? '', 0, 100, '...')) ?></p>
                <span class="fac-card__link">
                    Learn more
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </span>
            </div>
        </a>
        <?php endforeach; ?>

        <!-- See More card -->
        <a href="/hms/facility" class="fac-card--more">
            <div class="fac-card--more__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </div>
            <p class="fac-card--more__text">See All Facilities</p>
            <p class="fac-card--more__sub"><?= $remaining ?> facilities available</p>        </a>

    </div>

</section>  
<!-- about section end  -->