<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
.why-us {
    padding: 100px 8%;
    background: #ffffff;
    display: flex;
    align-items: center;
    gap: 80px;
}

.why-us__img-wrap {
    flex: 1;
    position: relative;
    flex-shrink: 0;
    max-width: 520px;
}

.why-us__img-wrap img {
    width: 100%;
    height: 520px;
    object-fit: cover;
    border-radius: 24px;
    display: block;
    box-shadow: 0 20px 60px rgba(0,0,0,0.13);
    position: relative;
    z-index: 1;
}

.why-us__badge {
    position: absolute;
    bottom: -24px;
    right: -24px;
    background: #00b6bd;
    color: #fff;
    border-radius: 20px;
    padding: 22px 28px;
    text-align: center;
    box-shadow: 0 12px 40px rgba(0,182,189,0.35);
    z-index: 2;
}

.why-us__badge-num {
    font-size: 6.0rem;
    font-weight: 800;
    line-height: 1;
    display: block;
}

.why-us__badge-label {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    opacity: 0.88;
    margin-top: 4px;
    display: block;
}

.why-us__img-wrap::before {
    content: '';
    position: absolute;
    top: 24px; left: 24px;
    width: 100%; height: 100%;
    border-radius: 24px;
    border: 3px solid rgba(0,182,189,0.2);
    z-index: 0;
}

.why-us__content { flex: 1; }

.why-us__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #00b6bd;
    margin-bottom: 20px;
}

.why-us__eyebrow::before,
.why-us__eyebrow::after {
    content: '';
    display: inline-block;
    width: 28px; height: 2px;
    background: #00b6bd;
    border-radius: 2px;
}

.why-us__heading {
    font-size: 4.0rem;
    font-weight: 800;
    color: #0d2b3e;
    line-height: 1.15;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
}

.why-us__heading span {
    color: #00b6bd;
    position: relative;
}

.why-us__heading span::after {
    content: '';
    position: absolute;
    bottom: 2px; left: 0;
    width: 100%; height: 3px;
    background: rgba(0,182,189,0.3);
    border-radius: 2px;
}

.why-us__desc {
    font-size: 1.6rem;
    color: #666;
    line-height: 1.8;
    margin-bottom: 40px;
    max-width: 480px;
    text-transform: none;
}

.why-us__features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 44px;
}

.why-us__feature {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 16px;
    background: #f4f8f9;
    border-radius: 14px;
    transition: all 0.25s ease;
    border: 1px solid transparent;
}

.why-us__feature:hover {
    background: #e8f9f9;
    border-color: rgba(0,182,189,0.2);
    transform: translateY(-3px);
}

.why-us__feature-icon {
    width: 42px; height: 42px;
    background: #00b6bd;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #fff;
    font-size: 1.9rem;
}

.why-us__feature-text h4 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0d2b3e;
    margin-bottom: 3px;
}

.why-us__feature-text p {
    font-size: 1.1rem;
    color: #888;
    line-height: 1.5;
    text-transform: none;
}

.why-us__cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #0d2b3e;
    color: #fff;
    text-decoration: none;
    padding: 15px 32px;
    border-radius: 50px;
    font-size: 1.3rem;
    font-weight: 600;
    transition: all 0.3s ease;
    letter-spacing: 0.3px;
}

.why-us__cta:hover {
    background: #00b6bd;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,182,189,0.3);
}

.why-us__cta svg { transition: transform 0.3s ease; }
.why-us__cta:hover svg { transform: translateX(4px); }

@media (max-width: 1024px) {
    .why-us { gap: 50px; padding: 80px 6%; }
    .why-us__heading { font-size: 2.2rem; }
}

@media (max-width: 768px) {
    .why-us { flex-direction: column; padding: 70px 5%; }
    .why-us__img-wrap { max-width: 100%; width: 100%; margin-bottom: 40px; }
    .why-us__img-wrap img { height: 320px; }
    .why-us__badge { right: 12px; bottom: -16px; }
    .why-us__heading { font-size: 1.9rem; }
    .why-us__features { grid-template-columns: 1fr; }
}
</style>

<section class="why-us">

    <!-- Image -->
    <div class="why-us__img-wrap">
    <img src="./<?= $whyus_image ?>" alt="Why Choose Zamboanga Doctors Hospital">
            <div class="why-us__badge">
            <span class="why-us__badge-num"><?= $whyus_badge_num ?></span>
            <span class="why-us__badge-label">Years of Service</span>
        </div>
    </div>

    <!-- Content -->
    <div class="why-us__content">

        <p class="why-us__eyebrow">Why Choose Us</p>

        <h2 class="why-us__heading">
        <?= $whyus_heading ?>
        </h2>

        <p class="why-us__desc" >
            At Zamboanga Doctors' Hospital, we combine decades of medical expertise with 
            modern technology and a compassionate team — all dedicated to delivering 
            exceptional care to every patient who walks through our doors.
        </p>

        <div class="why-us__features">

            <div class="why-us__feature">
                <div class="why-us__feature-icon">
                    <i class="fas fa-user-doctor"></i>
                </div>
                <div class="why-us__feature-text">
                    <h4>Expert Physicians</h4>
                    <p>Board-certified doctors across all major specialties.</p>
                </div>
            </div>

            <div class="why-us__feature">
                <div class="why-us__feature-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="why-us__feature-text">
                    <h4>Modern Facilities</h4>
                    <p>State-of-the-art equipment and updated clinical spaces.</p>
                </div>
            </div>

            <div class="why-us__feature">
                <div class="why-us__feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="why-us__feature-text">
                    <h4>24/7 Emergency Care</h4>
                    <p>Round-the-clock emergency and trauma response.</p>
                </div>
            </div>

            <div class="why-us__feature">
                <div class="why-us__feature-icon">
                    <i class="fas fa-shield-heart"></i>
                </div>
                <div class="why-us__feature-text">
                    <h4>Accredited & Trusted</h4>
                    <p>Recognized by leading health and insurance organizations.</p>
                </div>
            </div>

        </div>

        <a href="aboutus.php" class="why-us__cta">
            Learn More About Us
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>

    </div>

</section>