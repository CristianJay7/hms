<?php
require_once 'includes/config.php';

// Specializations for dropdown
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
    <title>Zamboanga Doctors' Hospital, Inc. | Doctors</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">

<style>
:root {
    --teal:       #00b6bd;
    --teal-dark:  #008e94;
    --red:        #cc2233;
    --border:     #dde3ea;
    --bg-head:    #f4f8fb;
    --text-main:  #2c3e50;
    --text-muted: #888;
    --radius:     10px;
}

/* ── Filter bar ── */
.doctor-search-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin: 28px auto 18px;
    max-width: 900px;
    padding: 0 16px;
}

.search-wrap {
    flex: 1 1 100%;          /* full width on mobile */
    position: relative;
}
.search-wrap i.fa-search {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    pointer-events: none;
}
.search-wrap i.fa-spinner {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--teal);
    display: none;
}
.search-wrap input[type="text"] {
    width: 100%;
    padding: 11px 36px 11px 38px;
    border: 2px solid var(--border);
    border-radius: 50px;
    font-size: 1.6rem;
    outline: none;
    transition: border-color .2s;
    color: var(--text-main);
    background: #fff;
    box-sizing: border-box;
}
.search-wrap input[type="text"]:focus { border-color: var(--teal); }

.doctor-search-bar select {
    flex: 1 1 auto;
    padding: 11px 14px;
    border: 2px solid var(--border);
    border-radius: 50px;
    font-size: 1.3rem;
    outline: none;
    cursor: pointer;
    color: #555;
    background: #fff;
    transition: border-color .2s;
    min-width: 0;
    width: 100%;
}
.doctor-search-bar select:focus { border-color: var(--teal); }

.btn-clear, .btn-export {
    flex: 1 1 auto;
    padding: 11px 16px;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    border: 2px solid transparent;
    white-space: nowrap;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-clear  { background: transparent; color: #888; border-color: var(--border); }
.btn-clear:hover { border-color: var(--red); color: var(--red); }
.btn-export { background: #fff; color: #27ae60; border-color: #27ae60; }
.btn-export:hover { background: #27ae60; color: #fff; }

/* ── Meta row ── */
.table-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto 10px;
    padding: 0 16px;
    font-size: 1.3rem;
    color: var(--text-muted);
    flex-wrap: wrap;
    gap: 6px;
    min-height: 24px;
}
.table-meta span { font-weight: 700; color: var(--teal); }

/* ── Table wrapper ── */
.table-wrap {
    max-width: 1100px;
    margin: 0 auto 30px;
    padding: 0 16px;
    overflow-x: auto;          /* horizontal scroll fallback on very small screens */
    -webkit-overflow-scrolling: touch;
}

/* ── Desktop table ── */
table.doctor-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1.3rem;
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
}
table.doctor-table thead { background: var(--bg-head); }
table.doctor-table th {
    padding: 13px 14px;
    text-align: left;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-main);
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
    transition: background .15s;
}
table.doctor-table th:hover { background: #e8f5f6; }
table.doctor-table th.th-num { cursor: default; width: 40px; text-align: center; }
table.doctor-table th .sort-icon { font-size: .8rem; opacity: .45; margin-left: 4px; }
table.doctor-table th.active-sort .sort-icon { opacity: 1; color: var(--teal); }

table.doctor-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #eef1f4;
    color: var(--text-main);
    vertical-align: middle;
    font-size: 1.4rem;
}
table.doctor-table tbody tr:last-child td { border-bottom: none; }
table.doctor-table tbody tr:hover { background: #f0fbfc; }

.td-num  { color: var(--text-muted); font-size: 1.3rem; text-align: center; }
.td-name { font-weight: 600; }

.spec-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 50px;
    background: #e6f9fa;
    color: var(--teal-dark);
    font-size: 1rem;
    font-weight: 600;
}

.avail-badge {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
}
.avail-yes   { background: #e8f8ee; color: #27ae60; }
.avail-no    { background: #fdecea; color: var(--red); }
.avail-other { background: #fef9e7; color: #e67e22; }

/* ── Skeleton loader ── */
.skeleton-row td { padding: 12px 14px; border-bottom: 1px solid #eef1f4; }
.skel {
    height: 14px;
    border-radius: 6px;
    background: linear-gradient(90deg, #eee 25%, #f5f5f5 50%, #eee 75%);
    background-size: 200% 100%;
    animation: shimmer 1.2s infinite;
}
@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.empty-row td {
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
    font-size: 1rem;
}

/* ── Pagination ── */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
    margin: 0 auto 40px;
    padding: 0 16px;
}
.pagination button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 8px;
    border-radius: 8px;
    border: 2px solid var(--border);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    background: #fff;
    color: var(--text-main);
    transition: all .18s;
}
.pagination button:hover:not(:disabled) { border-color: var(--teal); color: var(--teal); }
.pagination button.active  { background: var(--teal); color: #fff; border-color: var(--teal); }
.pagination button:disabled { opacity: .35; cursor: default; }
.pagination .dots { border: none; background: none; cursor: default; }

/* ═══════════════════════════════════════════════
   MOBILE — card layout (≤ 640px)
   Each <tr> becomes a card; <td> shows a label
   via data-label attribute set in JS renderTable()
   ═══════════════════════════════════════════════ */
@media (max-width: 640px) {

    /* Filter bar: stack everything full-width */
    .doctor-search-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    .search-wrap { flex: 1 1 100%; }
    .doctor-search-bar select,
    .btn-clear, .btn-export { width: 100%; }

    /* Hide traditional table header */
    table.doctor-table thead { display: none; }

    /* Make table, tbody, tr, td all block */
    table.doctor-table,
    table.doctor-table tbody,
    table.doctor-table tr,
    table.doctor-table td {
        display: block;
        width: 100%;
    }

    /* Each row = a card */
    table.doctor-table tbody tr {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        margin-bottom: 12px;
        padding: 14px 16px;
        border-bottom: none !important;
        position: relative;
    }
    table.doctor-table tbody tr:hover { background: #f8fffe; }

    /* Row number badge top-right */
    table.doctor-table td.td-num {
        position: absolute;
        top: 12px;
        right: 14px;
        width: auto;
        padding: 0;
        font-size: 0.8rem;
        color: var(--text-muted);
        text-align: right;
        border: none;
    }

    /* Each cell: label on left, value on right */
    table.doctor-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        border-bottom: 1px solid #f0f3f6 !important;
        font-size: 1.2rem;
        gap: 10px;
    }
    table.doctor-table td:last-child { border-bottom: none !important; }

    /* Label from data-label attr */
    table.doctor-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: var(--text-muted);
        font-size: 1.09rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
        flex-shrink: 0;
    }
    /* Hide label for the # cell since we use absolute */
    table.doctor-table td.td-num::before { display: none; }

    /* Skeleton cards on mobile */
    .skeleton-row {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        margin-bottom: 12px;
        padding: 14px 16px;
    }
    .skeleton-row td { border-bottom: 1px solid #f0f3f6 !important; padding: 8px 0; }
    .skeleton-row td:last-child { border-bottom: none !important; }

    /* Pagination: bigger tap targets */
    .pagination button {
        min-width: 40px;
        height: 40px;
        font-size: 0.95rem;
    }

    /* Meta: stack */
    .table-meta { flex-direction: column; align-items: flex-start; gap: 2px; }
}
</style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<br><br>

<section id="doctor">
    <div class="container">
        <h1 class="heading">Meet Our Doctors</h1>
        <h3 class="title">Explore our directory of expert Physicians, each dedicated to providing top-tier care.</h3>

        <!-- ── Filter bar — no submit button needed ── -->
        <div class="doctor-search-bar">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name…" autocomplete="off">
                <i class="fas fa-spinner fa-spin" id="loadingIcon"></i>
            </div>

            <select id="specFilter">
                <option value="">All Specializations</option>
                <?php foreach ($specs as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>

            <button class="btn-clear" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
            <button class="btn-export" onclick="exportCSV()">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>

        <!-- ── Meta ── -->
        <div class="table-meta" id="tableMeta"></div>

        <!-- ── Table ── -->
        <div class="table-wrap">
            <table class="doctor-table" id="doctorTable">
                <thead>
                    <tr>
                        <th class="th-num">#</th>
                        <th data-col="name">Doctor Name <span class="sort-icon">⇅</span></th>
                        <th data-col="specialization">Specialization <span class="sort-icon">⇅</span></th>
                        <th data-col="clinic_hours">Clinic Hours <span class="sort-icon">⇅</span></th>
                        <th data-col="availability">Information <span class="sort-icon">⇅</span></th>
                    </tr>
                </thead>
                <tbody id="doctorBody"></tbody>
            </table>
        </div>

        <!-- ── Pagination ── -->
        <div class="pagination" id="pagination"></div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/main.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function () {
    document.querySelector('header').classList.add('header-light');
});
</script>

<script>
// ── State ─────────────────────────────────────────────────────
const state = { search: '', spec: '', sort: 'name', dir: 'asc', page: 1 };
let debounceTimer = null;

// ── DOM refs ──────────────────────────────────────────────────
const searchInput  = document.getElementById('searchInput');
const specFilter   = document.getElementById('specFilter');
const tbody        = document.getElementById('doctorBody');
const metaEl       = document.getElementById('tableMeta');
const paginationEl = document.getElementById('pagination');
const loadingIcon  = document.getElementById('loadingIcon');

// ── Live search — fires 300ms after user stops typing ─────────
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        state.search = searchInput.value.trim();
        state.page   = 1;
        fetchDoctors();
    }, 300);
});

// ── Dropdown — fires instantly on change ──────────────────────
specFilter.addEventListener('change', () => {
    state.spec = specFilter.value;
    state.page = 1;
    fetchDoctors();
});

// ── Column sort ───────────────────────────────────────────────
document.querySelectorAll('#doctorTable th[data-col]').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.dataset.col;
        if (state.sort === col) {
            state.dir = state.dir === 'asc' ? 'desc' : 'asc';
        } else {
            state.sort = col;
            state.dir  = 'asc';
        }
        state.page = 1;
        fetchDoctors();
    });
});

// ── Clear all filters ─────────────────────────────────────────
function clearFilters() {
    searchInput.value = '';
    specFilter.value  = '';
    Object.assign(state, { search: '', spec: '', sort: 'name', dir: 'asc', page: 1 });
    fetchDoctors();
}

// ── AJAX fetch ────────────────────────────────────────────────
function fetchDoctors() {
    loadingIcon.style.display = 'block';
    showSkeleton();
    updateSortHeaders();

    const params = new URLSearchParams({
        search : state.search,
        spec   : state.spec,
        sort   : state.sort,
        dir    : state.dir,
        page   : state.page,
    });

    fetch('doctors-ajax.php?' + params)
        .then(r => r.json())
        .then(data => {
            loadingIcon.style.display = 'none';
            renderTable(data);
            renderMeta(data);
            renderPagination(data);
        })
        .catch(() => {
            loadingIcon.style.display = 'none';
            tbody.innerHTML = `<tr class="empty-row"><td colspan="5">
                <i class="fas fa-triangle-exclamation" style="font-size:2rem;display:block;margin-bottom:10px;color:#ddd;"></i>
                Failed to load data. Please try again.</td></tr>`;
        });
}

// ── Skeleton while loading ────────────────────────────────────
function showSkeleton() {
    const widths = ['40%','60%','50%','55%','35%'];
    tbody.innerHTML = Array.from({length: 5}, () =>
        `<tr class="skeleton-row">
            <td><div class="skel" style="width:24px;margin:0 auto;"></div></td>
            ${widths.map(w => `<td><div class="skel" style="width:${w};"></div></td>`).join('')}
        </tr>`
    ).join('');
}

// ── Render rows ───────────────────────────────────────────────
function renderTable({ doctors, page, perPage }) {
    if (!doctors.length) {
        tbody.innerHTML = `<tr class="empty-row"><td colspan="5">
            <i class="fas fa-user-doctor" style="font-size:2rem;display:block;margin-bottom:10px;color:#ddd;"></i>
            No doctors found matching your search.</td></tr>`;
        return;
    }
    const offset = (page - 1) * perPage;
    tbody.innerHTML = doctors.map((d, i) => {
        const avail = d.availability.toLowerCase();
        let badge = 'avail-other';
        if (avail.includes('available') && !avail.includes('not') && !avail.includes('un')) badge = 'avail-yes';
        else if (avail.includes('not') || avail.includes('unavail')) badge = 'avail-no';
        return `<tr>
            <td class="td-num" data-label="#">${offset + i + 1}</td>
            <td class="td-name" data-label="Doctor">${esc(d.name)}</td>
            <td data-label="Specialization"><span class="spec-pill">${esc(d.specialization)}</span></td>
            <td data-label="Clinic Hours">🕐 ${esc(d.clinic_hours)}</td>
            <td data-label="Information"><span class="avail-badge ${badge}">${esc(d.availability)}</span></td>
        </tr>`;
    }).join('');
}

// ── Render meta ───────────────────────────────────────────────
function renderMeta({ total, page, perPage, totalPages }) {
    if (!total) { metaEl.innerHTML = ''; return; }
    const from = (page - 1) * perPage + 1;
    const to   = Math.min(page * perPage, total);
    metaEl.innerHTML = `
        <div>Showing <span>${from}–${to}</span> of <span>${total}</span> doctor${total !== 1 ? 's' : ''}</div>
        <div>Page <span>${page}</span> of <span>${totalPages}</span></div>`;
}

// ── Render pagination ─────────────────────────────────────────
function renderPagination({ totalPages, page }) {
    if (totalPages <= 1) { paginationEl.innerHTML = ''; return; }
    let html = `<button ${page===1?'disabled':''} onclick="goPage(${page-1})"><i class="fas fa-chevron-left"></i></button>`;
    const start = Math.max(1, page-2), end = Math.min(totalPages, page+2);
    if (start > 1) {
        html += `<button onclick="goPage(1)">1</button>`;
        if (start > 2) html += `<button class="dots" disabled>…</button>`;
    }
    for (let p = start; p <= end; p++)
        html += `<button class="${p===page?'active':''}" onclick="goPage(${p})">${p}</button>`;
    if (end < totalPages) {
        if (end < totalPages-1) html += `<button class="dots" disabled>…</button>`;
        html += `<button onclick="goPage(${totalPages})">${totalPages}</button>`;
    }
    html += `<button ${page===totalPages?'disabled':''} onclick="goPage(${page+1})"><i class="fas fa-chevron-right"></i></button>`;
    paginationEl.innerHTML = html;
}

function goPage(p) { state.page = p; fetchDoctors(); }

// ── Update sort header icons ──────────────────────────────────
function updateSortHeaders() {
    document.querySelectorAll('#doctorTable th[data-col]').forEach(th => {
        const icon = th.querySelector('.sort-icon');
        th.classList.remove('active-sort');
        if (th.dataset.col === state.sort) {
            th.classList.add('active-sort');
            icon.textContent = state.dir === 'asc' ? '▲' : '▼';
        } else {
            icon.textContent = '⇅';
        }
    });
}

// ── CSV export (current page) ─────────────────────────────────
function exportCSV() {
    const rows = document.querySelectorAll('#doctorTable tr');
    const lines = [];
    rows.forEach(row => {
        const cols = [...row.querySelectorAll('th, td')].map(cell =>
            '"' + cell.innerText.replace(/[⇅▲▼]/g,'').trim().replace(/"/g,'""') + '"'
        );
        lines.push(cols.join(','));
    });
    const blob = new Blob([lines.join('\n')], { type: 'text/csv' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'doctors.csv';
    a.click();
}

// ── HTML escape ───────────────────────────────────────────────
function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Initial load ──────────────────────────────────────────────
fetchDoctors();
</script>
</body>
</html>