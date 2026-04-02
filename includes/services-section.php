
<html >
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

:root{
    --blue:#00b6bd;
    --black:#394044;
    --wight:#fff;
  }

.services {
  padding: 50px 20px;
  text-align: center;
  background: #f9f9f9;
  
}


.title {
  text-align: center;
    font-size: 4rem;
    padding: 1rem;
   
    color: #00b6bd;
    letter-spacing: .1rem;
    margin-bottom: 15px;
}

.container {
  max-width: 1200px;
  margin: auto;
}
.heading{
    text-align: center;
    font-size: 5rem;
    padding: 1rem;

    color: var(--blue);
    letter-spacing: .1rem;
    margin-bottom: 15px;
}
.services-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* 4 equal columns */
  gap: 30px;
  align-items: stretch;
}

.service-box {
  background: white;
  padding: 30px;
  border-radius: 10px;
  transition: 0.3s;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  height: 100%; /* ← add this */
    display: flex; /* ← add this */
    flex-direction: column; /* ← add this */
    align-items: center;
    justify-content: center; /* ← add this */
    min-height: 200px;       /* ← add this */
    text-align: center;
}

.service-box:hover {
  transform: translateY(-12px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.icon {
  font-size: 40px;
  margin-bottom: 15px;
  color: #00b6bd;
}

.service-box h3 {
    font-size: 2rem;
    color: #141313;
    margin-bottom: 10px;
    min-height: 60px;        /* ← add this — reserves space for 2 lines */
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-box p {
  font-size: 1.3rem;
    color: var(--black);
}
button {
  text-align: center;
  
}
.title{
    text-align: center;
    padding: 0rem 1rem;
    font-size: 2.5rem;
    color: #1A1717;
    font-weight: 300;
    margin-bottom: 40px;
}
.services-grid a {
    display: flex;
    text-decoration: none;
    color: inherit;
}
@media (max-width: 991px) {
    .services-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 576px) {
    .services-grid { grid-template-columns: 1fr; }
}
    </style>
</head>
<body>
<?php
$services_res = mysqli_query($con, "SELECT * FROM services ORDER BY created_at ASC LIMIT 8");
$services_pub = [];
while ($row = mysqli_fetch_assoc($services_res)) $services_pub[] = $row;
?>

<section class="services">
    <div class="container">

        <h1 class="heading">Our Services</h1>
        <h3 class="title">Delivering advanced medical care with expertise, compassion, and innovation.</h3>

        <div class="services-grid">
            <?php if (empty($services_pub)): ?>
                <p style="text-align:center;color:#aaa;grid-column:1/-1;">No services yet.</p>
            <?php else: ?>
                <?php foreach ($services_pub as $s): ?>
                  <a href="services-details/<?= $s['id'] ?>" style="text-decoration:none;color:inherit;">
                    <div class="service-box">
                            <div class="icon">
                                <i class="<?= htmlspecialchars($s['icon'] ?? 'fa-solid fa-stethoscope') ?>"></i>
                            </div>
                            <h3><?= htmlspecialchars($s['name']) ?></h3>
                            <?php
                                $words = explode(' ', $s['description']);
                                $short = implode(' ', array_slice($words, 0, 9));
                                echo '<p style="text-transform: none;">' . htmlspecialchars($short) . (count($words) > 9 ? '...' : '') . '</p>';
                            ?>
                    </div>
              </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <br><br><br>
        <?php
        $total_services = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM services"))['total'];
        if ($total_services > 8):
        ?>
        <div class="seenmore">
            <a href="services"><button class="button">See More!</button></a>
        </div>
        <?php endif; ?>

    </div>
</section>


  
</body>
</html>