<?php
global $con;
$jobs   = [];
$result = mysqli_query($con, "SELECT * FROM career_jobs ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $jobs[] = $row;
?>

<!-- Toast -->
<div id="toast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:8px;font-size:0.88rem;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    display:none;align-items:center;gap:8px;
    border-left-width:4px;border-left-style:solid;
"></div>

<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Post New Job</h4>

<div id="jobForm">
    <input type="hidden" id="jobId">
    <input type="hidden" id="formAction" value="create">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Job Title *</label>
            <input type="text" id="jobTitle" placeholder="e.g. Registered Nurse"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Department</label>
            <input type="text" id="jobDepartment" placeholder="e.g. Nursing Department"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Employment Type</label>
            <select id="jobType" style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Contract">Contract</option>
                <option value="Internship">Internship</option>
            </select>
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Location</label>
            <input type="text" id="jobLocation" placeholder="e.g. Zamboanga City" value="Zamboanga City"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Posted Date</label>
            <input type="date" id="jobDate"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Status</label>
            <select id="jobStatus" style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="open">✅ Open</option>
                <option value="closed">🔒 Closed</option>
            </select>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Job Description *</label>
            <textarea id="jobDesc" rows="5" placeholder="Describe the role, responsibilities, and what the candidate will be doing..."
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Requirements <span style="color:#aaa;font-weight:400;">(one per line)</span></label>
            <textarea id="jobReq" rows="4" placeholder="- Licensed Registered Nurse&#10;- At least 1 year experience&#10;- Willing to work shifting schedules"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveJob()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Post Job
        </button>
        <button id="cancelBtn" onclick="resetForm()"
            style="display:none;padding:9px 22px;background:#e8edf2;color:#555;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Cancel
        </button>
    </div>
</div>

<!-- Table -->
<hr style="margin:28px 0;border:none;border-top:1px solid #eef1f5;">

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
    <h4 style="font-size:1rem;color:#1a3c5e;">All Job Postings</h4>
    <span id="jobCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($jobs) ?> Job<?= count($jobs) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Title</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Department</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Type</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Status</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Posted</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="jobTableBody">
        <?php if (empty($jobs)): ?>
            <tr><td colspan="7" style="padding:40px;text-align:center;color:#aaa;">No job postings yet.</td></tr>
        <?php else: ?>
            <?php foreach ($jobs as $j): ?>
            <tr id="row-<?= $j['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $j['id'] ?></td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($j['title']) ?></td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#666;"><?= htmlspecialchars($j['department'] ?? '—') ?></td>
                <td style="padding:11px 14px;font-size:0.82rem;">
                    <span style="background:#e8f0f8;color:#1a3c5e;padding:3px 10px;border-radius:20px;font-weight:600;"><?= htmlspecialchars($j['type']) ?></span>
                </td>
                <td style="padding:11px 14px;">
                    <?php if ($j['status'] === 'open'): ?>
                        <span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Open</span>
                    <?php else: ?>
                        <span style="background:#f5f5f5;color:#888;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Closed</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;">
                    <?= $j['posted_date'] ? date('M d, Y', strtotime($j['posted_date'])) : '—' ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editJob(<?= $j['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteJob(<?= $j['id'] ?>, '<?= htmlspecialchars(addslashes($j['title'])) ?>')"
                            style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

<script>
const AJAX_URL = 'careers-ajax.php';

document.getElementById('jobDate').value = new Date().toISOString().split('T')[0];

function saveJob() {
    const id     = document.getElementById('jobId').value;
    const action = document.getElementById('formAction').value;
    const title  = document.getElementById('jobTitle').value.trim();
    const dept   = document.getElementById('jobDepartment').value.trim();
    const type   = document.getElementById('jobType').value;
    const loc    = document.getElementById('jobLocation').value.trim();
    const date   = document.getElementById('jobDate').value;
    const status = document.getElementById('jobStatus').value;
    const desc   = document.getElementById('jobDesc').value.trim();
    const req    = document.getElementById('jobReq').value.trim();

    if (!title) { showToast('Job title is required.', false); return; }
    if (!desc)  { showToast('Job description is required.', false); return; }

    const fd = new FormData();
    fd.append('action',      action);
    fd.append('title',       title);
    fd.append('department',  dept);
    fd.append('type',        type);
    fd.append('location',    loc);
    fd.append('posted_date', date);
    fd.append('status',      status);
    fd.append('description', desc);
    fd.append('requirements',req);
    if (id) fd.append('id', id);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editJob(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const j = res.job;
            document.getElementById('jobId').value         = j.id;
            document.getElementById('formAction').value    = 'update';
            document.getElementById('jobTitle').value      = j.title;
            document.getElementById('jobDepartment').value = j.department  || '';
            document.getElementById('jobType').value       = j.type        || 'Full-time';
            document.getElementById('jobLocation').value   = j.location    || '';
            document.getElementById('jobDate').value       = j.posted_date || '';
            document.getElementById('jobStatus').value     = j.status      || 'open';
            document.getElementById('jobDesc').value       = j.description || '';
            document.getElementById('jobReq').value        = j.requirements|| '';
            document.getElementById('formTitle').textContent = '✏️ Edit Job Posting';
            document.getElementById('cancelBtn').style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteJob(id, title) {
    if (!confirm('Delete "' + title + '"? This cannot be undone.')) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) reloadTable();
        });
}

function reloadTable() {
    fetch(AJAX_URL + '?action=list')
        .then(r => r.json())
        .then(rows => {
            const tbody = document.getElementById('jobTableBody');
            const count = rows.length;
            document.getElementById('jobCount').textContent = count + ' Job' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="7" style="padding:40px;text-align:center;color:#aaa;">No job postings yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(j => {
                const date   = j.posted_date ? new Date(j.posted_date).toLocaleDateString('en-US',{month:'short',day:'2-digit',year:'numeric'}) : '—';
                const badge  = j.status === 'open'
                    ? `<span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Open</span>`
                    : `<span style="background:#f5f5f5;color:#888;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Closed</span>`;
                return `
                <tr id="row-${j.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${j.id}</td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(j.title)}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#666;">${esc(j.department||'—')}</td>
                    <td style="padding:11px 14px;font-size:0.82rem;">
                        <span style="background:#e8f0f8;color:#1a3c5e;padding:3px 10px;border-radius:20px;font-weight:600;">${esc(j.type)}</span>
                    </td>
                    <td style="padding:11px 14px;">${badge}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${date}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editJob(${j.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteJob(${j.id},'${esc(j.title)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm() {
    document.getElementById('jobId').value            = '';
    document.getElementById('jobTitle').value         = '';
    document.getElementById('jobDepartment').value    = '';
    document.getElementById('jobType').value          = 'Full-time';
    document.getElementById('jobLocation').value      = 'Zamboanga City';
    document.getElementById('jobDate').value          = new Date().toISOString().split('T')[0];
    document.getElementById('jobStatus').value        = 'open';
    document.getElementById('jobDesc').value          = '';
    document.getElementById('jobReq').value           = '';
    document.getElementById('formAction').value       = 'create';
    document.getElementById('formTitle').textContent  = '➕ Post New Job';
    document.getElementById('cancelBtn').style.display = 'none';
}

function showToast(msg, success) {
    const t = document.getElementById('toast');
    t.textContent      = (success ? '✅ ' : '❌ ') + msg;
    t.style.background = success ? '#e6f9f0' : '#fdf0f0';
    t.style.color      = success ? '#1a7a4a' : '#a02020';
    t.style.borderLeft = '4px solid ' + (success ? '#2ecc71' : '#e74c3c');
    t.style.display    = 'flex';
    t.style.opacity    = '1';
    setTimeout(() => { t.style.opacity='0'; setTimeout(()=>t.style.display='none',300); }, 3000);
}

function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}
</script>