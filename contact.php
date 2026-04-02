<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga Doctors' Hospital, Inc. | Contact</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php include 'includes/favicon.php'; ?>
    <?php include 'includes/header.php'; ?>
     <?php include 'includes/info.php'; ?>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }

body {
 
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    position: relative;
}

body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
    z-index: 0;
}

.contact-card {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1000px;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    margin-top: 45px;
}

.contact-card h1 { font-size: 40px; margin-bottom: 30px; color: #00b6bd; }

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.info-box h4 { font-size: 16px; font-weight: 600; margin-bottom: 8px; color: #222; }
.info-box p  { font-size: 15px; color: #555; line-height: 1.6; }

/* ── Direct Contact Buttons ── */
.contact-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 12px;
}

.contact-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 12px 20px;
    border-radius: 50px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.contact-btn--email {
    background: #00b6bd;
    color: #fff;
    box-shadow: 0 6px 20px rgba(0,182,189,0.3);
}
.contact-btn--email:hover {
    background: #009ca2;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0,182,189,0.4);
}

.contact-btn--call {
    background: #0d2b3e;
    color: #fff;
    box-shadow: 0 6px 20px rgba(13,43,62,0.2);
}
.contact-btn--call:hover {
    background: #1a4a6e;
    transform: translateY(-2px);
}

.contact-btn--sms {
    background: #fff;
    color: #0d2b3e;
    border: 2px solid #0d2b3e;
}
.contact-btn--sms:hover {
    background: #0d2b3e;
    color: #fff;
    transform: translateY(-2px);
}

.contact-btn--messenger {
    background: #0084ff;
    color: #fff;
    box-shadow: 0 6px 20px rgba(0,132,255,0.25);
}
.contact-btn--messenger:hover {
    background: #0070d8;
    transform: translateY(-2px);
}

.contact-btn i { font-size: 0.95rem; }

.section-divider { border: none; border-top: 1px solid rgba(0,0,0,0.08); margin: 30px 0; }

.map-container { margin-top: 20px; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.map-container iframe { width: 100%; height: 350px; border: 0; }

.social-links { display: flex; gap: 18px; margin-top: 12px; }
.social-links a {
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    background: #00b6bd;
    color: #fff;
    font-size: 18px;
    transition: all 0.3s ease;
}
.social-links a:hover { background: #009ca2; transform: translateY(-3px); }

@media (max-width: 768px) {
    .info-grid       { grid-template-columns: 1fr; }
    .contact-card    { padding: 30px; }
    .contact-card h1 { font-size: 32px; }
    .contact-actions { flex-direction: column;padding-right: 30px;!important   padding-left: 0;!important justify-content: center;}
    .contact-btn     { justify-content: center; padding-left: 10px;!important}
}
</style>
</head>
<body style="background: url('images/home.jpg') center/cover no-repeat fixed;">
<div class="contact-card">

    <h1>Contact Us</h1>

    <div class="info-grid">

        <div class="info-box">
            <h4><i class="fa-solid fa-location-dot"></i> Address</h4>
            <p>
                Zamboanga Doctors Hospital<br>
                <?=$contact_address?>   
            </p>
        </div>

        <div class="info-box">
            <h4><i class="fa-solid fa-phone"></i> Contact Information</h4>
            <p><?=$contact_telephone?></p>
            <p><?=$contact_phone?></p>
            <p><?=$contact_email?></p>
        </div>

        <div class="info-box">
            <h4><i class="fa-solid fa-clock"></i> Office Hours</h4>
            <p>
                Monday – Friday: 8:00 AM – 5:00 PM<br>
                Emergency Room: Open 24 Hours
            </p>
        </div>

        <div class="info-box">
            <h4><i class="fa-solid fa-hospital"></i> Outpatient Clinics</h4>
            <p>Main Building, Ground Floor</p>
        </div>

        <div class="info-box">
            <h4><i class="fab fa-facebook"></i> Follow Us</h4>
            <div class="social-links">
                <a href="<?= $social_facebook?>" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>

        <!-- ── Direct Contact Buttons ── -->
        <div class="info-box">
            <h4><i class="fa-solid fa-paper-plane"></i> Get In Touch</h4>
            <div class="contact-actions">

            <a href="https://mail.google.com/mail/?view=cm&to=zdh1964@yahoo.com&su=Inquiry&body=Hello, I would like to inquire about..." target="_blank"
                   class="contact-btn contact-btn--email">
                    <i class="fas fa-envelope"></i> Send Email
                </a>

               
                <a href="https://m.me/Zamboanga.Doctors" target="_blank"
                   class="contact-btn contact-btn--messenger">
                    <i class="fab fa-facebook-messenger"></i> Message Us
                </a>

            </div>
        </div>

    </div>

    <hr class="section-divider">

    <!-- Google Map -->
    <div class="map-container">
        <iframe
            src="https://www.google.com/maps?q=Zamboanga+Doctors+Hospital&output=embed"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>

</div>


</body>
</html>