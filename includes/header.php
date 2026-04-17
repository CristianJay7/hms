<?php
// Fetch all services for navbar dropdown
global $con;
$nav_services = [];
if (isset($con)) {
    $result = mysqli_query($con, "SELECT id, name FROM services ORDER BY created_at ASC");
    while ($row = mysqli_fetch_assoc($result)) $nav_services[] = $row;
}
?>
<link rel="stylesheet" href="/hms/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<header>

    <!-- Logo -->
    <a href="/hms" class="logo">
        <img src="/hms/images/logo.png" alt="Zamboanga Doctors Hospital">
        <span class="logo-text">Zamboanga Doctors' Hospital, Inc.</span>
    </a>

    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="/hms">Home</a></li>

            <!-- Services Dropdown -->
            <li class="dropdown">
                <a href="#">Services <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="/hms/hmo">HMO and Insurances</a></li>
                    <li><a href="/hms/packages">Medical Packages</a></li>
                    <li><a href="/hms/roomrates">Room Rates</a></li>

                    <li class="dropdown-submenu">
                        <a href="/hms/services">Standard Services <i class="fas fa-caret-right"></i></a>
                        <ul class="dropdown-submenu-menu">
                            <?php if (!empty($nav_services)): ?>
                                <?php foreach ($nav_services as $s): ?>
                                    <li>
                                        <a href="/hms/services-details/<?= $s['id'] ?>">
                                            <?= htmlspecialchars($s['name']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><a href="services">View All Services</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>                   
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">About ZDH <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="/hms/aboutus">About us</a></li>  
                    <li><a href="/hms/careers">Careers</a></li>
                    <li><a href="/hms/gallery">Gallery</a></li>
                    <li><a href="/hms/org-chart">Organizational Chart</a></li>
                </ul>
            </li>


            <li><a href="/hms/doctor">Find a Doctor</a></li>
            <li><a href="/hms/contact">Contact</a></li>
            <li><a href="/hms/blog">Updates</a></li>
        </ul>
    </nav>

    <!-- Hamburger — uses id for reliable JS targeting -->
    <i class="fas fa-bars" id="menu-btn"></i>

</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/hms/js/main.js"></script>