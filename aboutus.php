<?php include 'includes/config.php'; ?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga Doctors' Hospital, Inc. | About Us</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
  
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Josefin', sans-serif; }

body { background:#f5f6f8; color:#333; }

.about { padding: 80px 8%; }

/* ── Hero ── */
.about-hero {
    display: flex;
    gap: 70px;
    align-items: center;
    margin-bottom: 90px;
    flex-direction: row;
    align-items: flex-start;
}

.about-img {
    flex: 1;
    max-width: 520px;
    flex-shrink: 0;
    width: 100%;
    padding-top: 55px;
}

.about-img img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.13);
    object-fit: cover;
    height: 400px;
}

.about-text { flex: 1; }

.about-text .label {
    font-size: 1.2rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #00b6bd;
    margin-bottom: 12px;
    display: block;
}

.about-text h1 {
    font-size: 3.6rem;
    font-weight: 700;
    color: #0f2e3d;
    line-height: 1.2;
    margin-bottom: 20px;
}

.about-text p {
    font-size: 1.5rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 24px;
    text-transform: none;
}

.about-text ul { list-style: none; }
.about-text ul li {
    font-size: 1.8rem;
    color: #444;
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.about-text ul li i { color: #00b6bd; font-size: 1.2rem; }

/* ── Mission Vision ── */
.mission-vision {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    margin-bottom: 90px;
}

.mv-card {
    background: white;
    border-radius: 20px;
    padding: 40px 36px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.07);
    border-top: 4px solid #00b6bd;
    transition: transform 0.25s ease;
}

.mv-card:hover { transform: translateY(-12px); }

.mv-card .icon {
    width: 56px; height: 56px;
    background: #e8f9f9;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 18px;
}

.mv-card h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #0f2e3d;
    margin-bottom: 12px;
}

.mv-card p {
    font-size: 1.4rem;
    line-height: 1.7;
    color: #666;
}

/* ── Core Values ── */
.core-values {
    text-align: center;
    margin-bottom: 90px;
}

.core-values .section-label {
    font-size: 1.2rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #00b6bd;
    margin-bottom: 10px;
    display: block;
}

.core-values h2 {
    font-size: 3.5rem;
    font-weight: 700;
    color: #0f2e3d;
    margin-bottom: 50px;
}

.values-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 16px;
}

.value-item {
    background: white;
    border-radius: 16px;
    padding: 28px 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
    cursor: default;
    flex: 0 0 120px; /* fixed width per item */
    text-align: center;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,182,189,0.15);
    border-bottom: 3px solid #00b6bd;
}

.value-letter {
    font-size: 2.9rem;
    font-weight: 800;
    color: #00b6bd;
    display: block;
    margin-bottom: 8px;
    line-height: 1;
}

.value-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #0f2e3d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ── President Message ── */
.president {
    display: flex;
    gap: 70px;
    align-items: center;
    background: white;
    border-radius: 24px;
    padding: 60px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.07);
}

.president-text { flex: 1.3; }

.president-text .section-label {
    font-size: 1.4rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #00b6bd;
    margin-bottom: 10px;
    display: block;
}

.president-text h2 {
    font-size: 2.8rem;
    font-weight: 700;
    color: #0f2e3d;
    margin-bottom: 24px;
    line-height: 1.2;
}

.president-text p {
    font-size: 1.6rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 16px;
}

.president-text blockquote {
    border-left: 4px solid #00b6bd;
    padding-left: 20px;
    margin: 24px 0;
    font-style: italic;
    color: #444;
    font-size: 1.2rem;
    line-height: 1.7;
}

.president-sig h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0f2e3d;
    margin-top: 28px;
}

.president-sig p {
    font-size: 1.2rem;
    color: #00b6bd;
    font-weight: 500;
    margin: 0;
}

.president-img {
    flex-shrink: 0;
    width: 320px;
}

.president-img img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.13);
    object-fit: cover;
    height: 400px;
}

/* ── Responsive ── */


@media (max-width: 900px) {
    .about { padding: 50px 6%; }
    .about-hero, .president { flex-direction: column; gap: 36px; }
    .about-img, .president-img { width: 100%; max-width: 100%; }
    .about-img img, .president-img img { height: 280px; }
    .mission-vision { grid-template-columns: 1fr; }
    .values-grid { grid-template-columns: repeat(4, 1fr); }
    .president { padding: 36px 28px; }
    .about-text h1 { font-size: 1.8rem; }
    .president-text h2 { font-size: 1.6rem; }
}


</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br><br><br><br><br>

<section class="about">

    <!-- ── Hero ── -->
<div class="about-hero">
        <div class="about-img">
                <img src="<?= $about_image ?>" alt="About Zamboanga Doctors Hospital">
        </div>
        <div class="about-text">
            <span class="label">Who We Are</span>
            <h1>Zamboanga Doctors' Hospital, Inc.</h1>
            <p style="text-align: justify; hyphens: auto; white-space: pre-line;">
            <?= $about_para1 ?>
            </p>
            <p style="text-align: justify; hyphens: auto; white-space: pre-line; text-transform: none;">
            <?= $about_para2 ?>
            </p>
            <ul>
            <li><i class="fas fa-circle-check"></i> <?= $about_bullet1 ?></li>
    <li><i class="fas fa-circle-check"></i> <?= $about_bullet2 ?></li>
    <li><i class="fas fa-circle-check"></i> <?= $about_bullet3 ?></li>
    <li><i class="fas fa-circle-check"></i> <?= $about_bullet4 ?></li>
            </ul>
        </div>
    </div>

    <!-- ── Mission & Vision ── -->
    <div class="mission-vision">
        <div class="mv-card">
        <div class="icon"><i class="fas fa-eye"></i></div>
            <h3>Our Vision</h3>
            <p style="text-align: justify; hyphens: auto; text-transform: none;">
            <?= $about_vision ?>
            </p>
        </div>
        <div class="mv-card">
        <div class="icon"><i class="fas fa-heart"></i></div>
            <h3>Our Mission</h3>
            <p style="text-align: justify; hyphens: auto; white-space: pre-line; text-transform: none;">
            <?= $about_mission ?>
            </p>
        </div>
    </div>

    <!-- ── Core Values ── -->
    <div class="core-values">
        <span class="section-label">What We Stand For</span>
        <h2>Our Core Values</h2>
        <div class="values-grid">
            <div class="value-item">
                <span class="value-letter">D</span>
                <span class="value-name">Dedication</span>
            </div>
            <div class="value-item">
                <span class="value-letter">O</span>
                <span class="value-name">Openness</span>
            </div>
            <div class="value-item">
                <span class="value-letter">C</span>
                <span class="value-name">Compassion</span>
            </div>
            <div class="value-item">
                <span class="value-letter">T</span>
                <span class="value-name">Trust</span>
            </div>
            <div class="value-item">
                <span class="value-letter">O</span>
                <span class="value-name">Outstanding</span>
            </div>
            <div class="value-item">
                <span class="value-letter">R</span>
                <span class="value-name">Respect</span>
            </div>
            <div class="value-item">
                <span class="value-letter">S</span>
                <span class="value-name">Service</span>
            </div>
        </div>
    </div>

    <!-- ── President Message  hardcoded── -->
    <div class="president">
        <div class="president-text">
            <span class="section-label">A Word From Leadership</span>
            <h2>Message from Our President</h2>
            <p style="text-transform: none;">
                On behalf of the entire Zamboanga Doctors' Hospital family, I welcome you and 
                extend our deepest gratitude for placing your trust in our institution. 
                Healthcare is not merely a profession — it is a calling, and we take that 
                responsibility with the utmost seriousness.
            </p>
            <blockquote style="text-transform: none;">
                "Our commitment is simple: to treat every patient as we would want our own 
                loved ones to be treated — with dignity, compassion, and the highest quality of care."
            </blockquote>
            <p style="text-transform: none;">
                We continuously invest in our people, our facilities, and our processes to ensure 
                that Zamboanga Doctors' Hospital remains a place of healing, hope, and excellence 
                for generations to come.
            </p>
            <div class="president-sig">
                <h3>Ray Chicombing, RPh</h3>
                <p>President, Zamboanga Doctors' Hospital, Inc.</p>
            </div>
        </div>
        <div class="president-img">
            <img src="images/ray.jpg" alt="President Ray Chicombing">
        </div>
    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/main.js"></script>
<script>
    document.querySelector('header').classList.add('header-light');
</script>
</body>
</html>