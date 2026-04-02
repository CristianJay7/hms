<?php
include 'includes/config.php';
global $con;

$hmos   = [];
$result = mysqli_query($con, "SELECT * FROM hmos ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($result)) $hmos[] = $row;
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Zamboanga Doctors' Hospital, Inc. | Accredited HMOs & Insurance</title>
<?php include 'includes/favicon.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('header').classList.add('header-light');
    });
</script>
<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #fff;
    }

    .container {
        max-width: 1300px;
        margin: 50px auto;
        padding: 20px;
    }

    .logo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 30px;
        align-items: center;
        justify-items: center;
    }

    .logo-card {
        background: #ffffff;
        border: 1px solid #e0dede;
        border-radius: 4px;
        width: 100%;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.4s ease;
    }

    .logo-card:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transform: translateY(-10px);
    }

    .logo-card img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }

    .footer-note {
        text-align: center;
        margin-top: 60px;
        color: #666;
        font-size: 14px;
        line-height: 1.6;
        
    }

    .footer-note p {
        text-transform: none;
    }


    .page-title {
        text-align: center;
        margin-bottom: 20px;
    }

    .page-title h1 {
        font-size: 32px;
        margin: 0;
        color: #00b6bd;
        letter-spacing: 1px;
    }

    .no-hmos {
        text-align: center;
        color: #aaa;
        padding: 60px 0;
        font-size: 1rem;
    }
</style>
</head>
<?php include 'includes/header.php'; ?>
<body>
<br><br><br><br><br><br>

<div class="container">

    <div class="page-title">
        <h1>HMOs and Insurances</h1>
    </div>

    <br><br>

    <div class="logo-grid">
        <?php if (empty($hmos)): ?>
            <p class="no-hmos">No accredited HMOs listed at the moment.</p>
        <?php else: ?>
            <?php foreach ($hmos as $h): ?>
                <div class="logo-card">
                    <img src="<?= htmlspecialchars($h['logo'] ?: 'admin/images/default.jpg') ?>"
                         alt="<?= htmlspecialchars($h['name']) ?>"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span style="display:none;font-size:0.8rem;color:#888;text-align:center;padding:8px;">
                        <?= htmlspecialchars($h['name']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="footer-note">
        <p>The list of accredited HMOs and insurances may change without prior notice.</p>
        <p>For the latest information, please confirm with our Information Desk or directly with your provider.</p>
    </div>

</div>

<footer> 
  <div class="container">



 
  <div class="logo-wrapper" style="display: flex; justify-content: flex-start; align-items: flex-start; margin-bottom: 12px; margin-top: 15px; margin-right: 30px;">
    <img src="./images/logo.png" alt="Zamboanga Doctors' Hospital Logo" id="footer-logo" style="width: 210px; height: auto;">
  </div>
 

    
    <!-- About Us -->
    <div class="box">

   
    <h5>About Us</h5>
<p style="text-align: justify; hyphens: auto; text-transform: none; ">
Our hospital is dedicated to delivering comprehensive medical care through experienced professionals, modern facilities, and a commitment to patient safety and wellness.
</p>

      <div class="social-links">
        <a href="<?= $social_facebook ?>" target="_blank" class="facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" class="twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" class="instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" class="linkedin" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="box">
      <h6>Quick Links</h6>
      <ul>
        <li><a href="aboutus.php">About</a></li>
        <li><a href="careers.php">Careers</a></li>        
        <li><a href="org-chart.php">Organizational Chart</a></li>
        <li><a href="privacy.php">Privacy Notice</a></li>
        <li><a href="patient-rights.php">Patient Right</a></li>
        <li><a href="terms.php">Terms and Condition</a></li>
      </ul>
    </div>

    <!-- Services --> 
    <div class="box">
      <h6>Services</h6>
      <ul> 
        
        <li><a href="doctors.php">Find a Doctor</a></li>
        <li><a href="hmo.php">HMO and Insurances</a></li>
        <li><a href="#">Medical Packages</a></li>
        <li><a href="services-details.php?id=6">Rehabilitation</a></li>
        <li><a href="roomrates.php">Room Rates</a></li>
        <li><a href="#">Standard Services</a></li>
        
        
        
        
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="box">
      <h6>Contact Info</h6>
      <ul>
      <li><i class="fa-solid fa-phone" style="margin-right:8px;"></i><a href="" style="color:394044; text-decoration:none;"></a><?=$contact_phone?></li>
        <li><i class="fas fa-map-marker-alt" style="margin-right:8px;"></i><a href="" style="color:394044; text-decoration:none;"><?=$contact_address?></a></li>
        <li><i class="fa-solid fa-tty" style="margin-right:8px;"></i><a href="" style="color:394044; text-decoration:none;"><?=$contact_telephone?></a></li>
        <li><i class="fas fa-envelope" style="margin-right:8px;"></i><a href="" style="color:394044; text-decoration:none;"><?=$contact_email?></a></li>
      </ul>
    </div>
  </div>

  <hr>

  <div class="copyright">
    &copy; <span id="year"></span> Zamboanga Doctors' Hospital, Inc. All rights reserved.
  </div>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>


<script>
  const logo = document.getElementById('footer-logo');
  const copyright = document.querySelector('.copyright');

  function moveLogo() {
    if (window.innerWidth <= 768) {
      // Move logo into copyright
      if (!copyright.contains(logo)) {
        copyright.prepend(logo);
      }
    } else {
      // Move logo back to original wrapper on desktop
      const wrapper = document.querySelector('.logo-wrapper');
      if (!wrapper.contains(logo)) {
        wrapper.appendChild(logo);
      }
    }
  }

  // Run on load and resize
  window.addEventListener('load', moveLogo);
  window.addEventListener('resize', moveLogo);
</script>
</footer>
</body>
</html>