
<!-- Include Font Awesome in your <head> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
 

<!-- custom css file link  -->
<link rel="stylesheet" href="/hms/css/style.css">
<style>

</style>
<footer> 
  <div class="container">



 
  <div class="logo-wrapper" style="display: flex; justify-content: flex-start; align-items: flex-start; margin-bottom: 12px; margin-top: 15px; margin-right: 30px;">
    <img src="/hms/images/logo.png" alt="Zamboanga Doctors' Hospital Logo" id="footer-logo" style="width: 250px; height: auto;">
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
        <li><a href="/hms/aboutus">About</a></li>
        <li><a href="/hms/careers">Careers</a></li>        
        <li><a href="/hms/certifications">Permit and Certifications</a></li>
        <li><a href="/hms/privacy">Privacy Notice</a></li>
        <li><a href="/hms/patient-rights">Patient Right</a></li>
        <li><a href="/hms/terms">Terms and Condition</a></li>
        <li><a href="/hms/faq">Frequently Ask Questions</a></li>
      </ul>
    </div>

    <!-- Services --> 
    <div class="box">
      <h6>Services</h6>
      <ul> 
        
        <li><a href="/hms/doctor">Find a Doctor</a></li>
        <li><a href="/hmshmo">HMO and Insurances</a></li>
        <li><a href="#">Medical Packages</a></li>
        <li><a href="/hms/services-details/6">Rehabilitation</a></li>
        <li><a href="/hms/roomrates">Room Rates</a></li>
        <li><a href="/hms/services">Standard Services</a></li>
        
        
        
        
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
