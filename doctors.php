<?php
require_once 'includes/config.php';

$doctors = [];
$result  = mysqli_query($con, "SELECT * FROM doctors ORDER BY created_at ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row;
}

// Get unique specializations for filter dropdown
$specs   = [];
$sresult = mysqli_query($con, "SELECT DISTINCT specialization FROM doctors ORDER BY specialization ASC");
while ($row = mysqli_fetch_assoc($sresult)) {
    $specs[] = $row['specialization'];
}
?>
<?php include 'includes/info.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga Doctors' Hospital, Inc. | Doctor</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
  
<style>
/* ── Search & Filter Bar ── */
.doctor-search-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    margin: 30px auto 40px auto;
    max-width: 800px;
    padding: 0 20px;
}

.doctor-search-bar .search-wrap {
    flex: 1;
    min-width: 220px;
    position: relative;
}

.doctor-search-bar .search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 1.3rem;
}

.doctor-search-bar input[type="text"] {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 2px solid #dde3ea;
    border-radius: 50px;
    font-size: 1.3rem;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
    color: #333;
    background: #fff;
}

.doctor-search-bar input[type="text"]:focus {
    border-color: #00b6bd;
}

.doctor-search-bar select {
    padding: 12px 20px;
    border: 2px solid #dde3ea;
    border-radius: 50px;
    font-size: 1.3rem;
    font-family: inherit;
    outline: none;
    cursor: pointer;
    color: #555;
    background: #fff;
    transition: border-color 0.2s;
    min-width: 200px;
}

.doctor-search-bar select:focus {
    border-color: #00b6bd;
}

.clear-btn {
    padding: 12px 22px;
    background: transparent;
    border: 2px solid #dde3ea;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 600;
    color: #888;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.clear-btn:hover {
    border-color: #cc2233;
    color: #cc2233;
}

/* Results count */
.doctor-results-count {
    text-align: center;
    font-size: 1.2rem;
    color: #888;
    margin-bottom: 20px;
}

.doctor-results-count span {
    font-weight: 700;
    color: #00b6bd;
}

/* No results message */
.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #aaa;
    font-size: 1rem;
    display: none;
    width: 100%;
}

.no-results i {
    font-size: 2.5rem;
    display: block;
    margin-bottom: 14px;
    color: #ddd;
}
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br>
<section id="doctor" class="card">
    <div class="container">
        <h1 class="heading">Meet Our Doctors</h1>
        <h3 class="title">Explore our directory of expert Physicians, each dedicated to providing top-tier care.</h3>
        <!-- Search & Filter -->
        <div class="doctor-search-bar">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name...">
            </div>
            <select id="specFilter">
                <option value="">All Specializations</option>
                <?php foreach ($specs as $spec): ?>
                    <option value="<?= htmlspecialchars($spec) ?>">
                        <?= htmlspecialchars($spec) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="clear-btn" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>

        <!-- Results Count -->
        <p class="doctor-results-count">
            Showing <span id="resultCount"><?= count($doctors) ?></span> doctor<?= count($doctors) !== 1 ? 's' : '' ?>
        </p>

        <div class="box-container" id="doctorGrid">
            <?php if (empty($doctors)): ?>
                <p style="color:#888;">No doctors available at the moment.</p>
            <?php else: ?>
                <?php foreach ($doctors as $doctor): ?>
                <div class="box"
                    data-name="<?= strtolower(htmlspecialchars($doctor['name'])) ?>"
                    data-spec="<?= htmlspecialchars($doctor['specialization']) ?>">
                    <?php if (!empty($doctor['image'])): ?>
                        <img src="<?= htmlspecialchars($doctor['image']) ?>" alt="<?= htmlspecialchars($doctor['name']) ?>">
                    <?php endif; ?>
                    <div class="content">
                        <a href="#"><h2><?= htmlspecialchars($doctor['name']) ?></h2></a>
                        <p class="specialization"><strong><?= htmlspecialchars($doctor['specialization']) ?></strong></p>
                        <p class="clinic-info">
                            <strong>🕐 <?= htmlspecialchars($doctor['clinic_hours']) ?></strong><br>
                            <?= htmlspecialchars($doctor['availability']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- No results message -->
                <div class="no-results" id="noResults">
                    <i class="fas fa-user-doctor"></i>
                    No doctors found matching your search.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/main.js"></script>
<script>
    // Must run AFTER main.js to override detectPageBackground()
    window.addEventListener('DOMContentLoaded', function () {
        document.querySelector('header').classList.add('header-light');
    });
</script>
<script>
const searchInput = document.getElementById('searchInput');
const specFilter  = document.getElementById('specFilter');
const noResults   = document.getElementById('noResults');
const resultCount = document.getElementById('resultCount');

function filterDoctors() {
    const name = searchInput.value.toLowerCase().trim();
    const spec = specFilter.value.toLowerCase().trim();

    const boxes   = document.querySelectorAll('#doctorGrid .box');
    let visible   = 0;

    boxes.forEach(box => {
        const boxName = box.dataset.name || '';
        const boxSpec = box.dataset.spec.toLowerCase() || '';

        const nameMatch = !name || boxName.includes(name);
        const specMatch = !spec || boxSpec === spec;

        if (nameMatch && specMatch) {
            box.style.display = '';
            visible++;
        } else {
            box.style.display = 'none';
        }
    });

    // Update count
    resultCount.textContent = visible;

    // Show/hide no results
    noResults.style.display = visible === 0 ? 'block' : 'none';
}

function clearFilters() {
    searchInput.value = '';
    specFilter.value  = '';
    filterDoctors();
}

searchInput.addEventListener('input', filterDoctors);
specFilter.addEventListener('change', filterDoctors);




</script>

   

</body>
</html>