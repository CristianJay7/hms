<?php
global $con;
$certs = [];
$res   = mysqli_query($con, "SELECT * FROM certifications ORDER BY sort_order ASC, created_at DESC");
while ($row = mysqli_fetch_assoc($res)) $certs[] = $row;
?>

<!-- Toast -->
<div id="toast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:8px;font-size:0.88rem;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    display:none;align-items:center;gap:8px;
    border-left-width:4px;border-left-style:solid;
"></div>

<!-- Form -->
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Certification / Permit</h4>

<div id="certForm">
    <input type="hidden" id="certId">
    <input type="hidden" id="formAction" value="add">
    <input type="hidden" id="existingFile" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Title <span style="color:#cc2233;">*</span></label>
            <input type="text" id="certTitle" placeholder="e.g. PhilHealth Accreditation Certificate"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Sort Order</label>
            <input type="number" id="certSort" value="0" min="0"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Status</label>
            <select id="certStatus"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="active">✅ Active</option>
                <option value="inactive">🚫 Inactive</option>
            </select>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">
                Upload File <span style="color:#cc2233;">*</span>
                <span style="color:#aaa;font-weight:400;">(PDF or DOCX)</span>
            </label>
            <div id="fileDropArea" onclick="document.getElementById('fileInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:20px;text-align:center;cursor:pointer;background:#fafbfc;transition:border-color 0.2s;">
                <div id="filePlaceholder">
                    <div style="font-size:1.6rem;margin-bottom:6px;">📄</div>
                    <div style="font-size:0.85rem;color:#888;">Click to upload PDF or DOCX</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">Max 20MB</div>
                </div>
                <div id="fileChosen" style="display:none;">
                    <div id="fileIcon" style="font-size:1.6rem;margin-bottom:6px;">📄</div>
                    <div id="fileName" style="font-size:0.88rem;font-weight:600;color:#1a3c5e;word-break:break-all;"></div>
                </div>
            </div>
            <input type="file" id="fileInput" accept=".pdf,.docx"
                style="display:none;" onchange="previewFile(this)">
            <div id="currentFileNote" style="display:none;margin-top:8px;padding:8px 12px;background:#f0f7ff;border-radius:6px;font-size:0.8rem;color:#1a6fcc;">
                📎 Current file: <span id="currentFileName" style="font-weight:600;"></span>
                <span style="color:#aaa;"> — upload a new file to replace it</span>
            </div>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveCert()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Certification
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Certifications & Permits</h4>
    <span id="certCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($certs) ?> Item<?= count($certs) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Title</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Type</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Order</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Status</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="certTableBody">
        <?php if (empty($certs)): ?>
            <tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No certifications yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($certs as $c): ?>
            <tr id="row-<?= $c['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $c['id'] ?></td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($c['title']) ?></td>
                <td style="padding:11px 14px;">
                    <?php if ($c['file_type'] === 'pdf'): ?>
                        <span style="background:#fff0f0;color:#cc2233;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">PDF</span>
                    <?php else: ?>
                        <span style="background:#e8f0ff;color:#1a6fcc;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">DOCX</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;"><?= $c['sort_order'] ?></td>
                <td style="padding:11px 14px;">
                    <?php if ($c['status'] === 'active'): ?>
                        <span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Active</span>
                    <?php else: ?>
                        <span style="background:#f5f5f5;color:#999;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Inactive</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editCert(<?= $c['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteCert(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['title'])) ?>')"
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
const AJAX_URL = 'certifications-ajax.php';

function previewFile(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const ext  = file.name.split('.').pop().toLowerCase();
    document.getElementById('filePlaceholder').style.display = 'none';
    document.getElementById('fileChosen').style.display      = 'block';
    document.getElementById('fileIcon').textContent = ext === 'pdf' ? '📕' : '📘';
    document.getElementById('fileName').textContent = file.name;
}

function saveCert() {
    const id      = document.getElementById('certId').value;
    const action  = document.getElementById('formAction').value;
    const title   = document.getElementById('certTitle').value.trim();
    const sort    = document.getElementById('certSort').value;
    const status  = document.getElementById('certStatus').value;
    const existing= document.getElementById('existingFile').value;
    const fileEl  = document.getElementById('fileInput').files[0];

    if (!title) { showToast('Title is required.', false); return; }
    if (action === 'add' && !fileEl) { showToast('Please upload a file.', false); return; }

    const fd = new FormData();
    fd.append('action',        action);
    fd.append('title',         title);
    fd.append('sort_order',    sort);
    fd.append('status',        status);
    fd.append('existing_file', existing);
    if (id)     fd.append('id',   id);
    if (fileEl) fd.append('file', fileEl);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editCert(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const c = res.cert;
            document.getElementById('certId').value         = c.id;
            document.getElementById('formAction').value     = 'edit';
            document.getElementById('certTitle').value      = c.title  || '';
            document.getElementById('certSort').value       = c.sort_order || 0;
            document.getElementById('certStatus').value     = c.status || 'active';
            document.getElementById('existingFile').value   = c.file   || '';
            document.getElementById('formTitle').textContent = '✏️ Edit Certification';
            document.getElementById('cancelBtn').style.display = 'inline-block';

            // Show current file note
            if (c.file) {
                const fname = c.file.split('/').pop();
                document.getElementById('currentFileName').textContent = fname;
                document.getElementById('currentFileNote').style.display = 'block';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteCert(id, title) {
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
            const tbody = document.getElementById('certTableBody');
            const count = rows.length;
            document.getElementById('certCount').textContent = count + ' Item' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No certifications yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(c => {
                const typeBadge = c.file_type === 'pdf'
                    ? `<span style="background:#fff0f0;color:#cc2233;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">PDF</span>`
                    : `<span style="background:#e8f0ff;color:#1a6fcc;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">DOCX</span>`;
                const statusBadge = c.status === 'active'
                    ? `<span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Active</span>`
                    : `<span style="background:#f5f5f5;color:#999;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Inactive</span>`;
                return `
                <tr id="row-${c.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${c.id}</td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(c.title)}</td>
                    <td style="padding:11px 14px;">${typeBadge}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${c.sort_order}</td>
                    <td style="padding:11px 14px;">${statusBadge}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editCert(${c.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteCert(${c.id}, '${esc(c.title)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm() {
    document.getElementById('certId').value             = '';
    document.getElementById('certTitle').value          = '';
    document.getElementById('certSort').value           = '0';
    document.getElementById('certStatus').value         = 'active';
    document.getElementById('formAction').value         = 'add';
    document.getElementById('existingFile').value       = '';
    document.getElementById('formTitle').textContent    = '➕ Add New Certification / Permit';
    document.getElementById('cancelBtn').style.display  = 'none';
    document.getElementById('fileInput').value          = '';
    document.getElementById('filePlaceholder').style.display = 'block';
    document.getElementById('fileChosen').style.display      = 'none';
    document.getElementById('currentFileNote').style.display = 'none';
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