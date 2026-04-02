<?php
global $con;

$hmos   = [];
$result = mysqli_query($con, "SELECT * FROM hmos ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $hmos[] = $row;

define('HMO_DEFAULT', 'admin/images/default.jpg');
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New HMO</h4>

<div id="hmoForm">
    <input type="hidden" id="hmoId">
    <input type="hidden" id="formAction" value="add">
    <input type="hidden" id="existingLogo" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">HMO Name *</label>
            <input type="text" id="name" placeholder="e.g. Maxicare"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Logo</label>
            <div id="uploadArea" onclick="document.getElementById('logoInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload logo</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP, SVG</div>
                </div>
                <img id="logoPreview" src="" alt="Preview"
                    style="display:none;max-width:100px;max-height:60px;object-fit:contain;border-radius:4px;">
            </div>
            <input type="file" id="logoInput" accept="image/jpeg,image/png,image/webp,image/svg+xml"
                style="display:none;" onchange="previewLogo(this)">
            <button id="removeLogoBtn" onclick="removeLogo()"
                style="display:none;margin-top:6px;padding:4px 10px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                Remove logo
            </button>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveHmo()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save HMO
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All HMOs & Insurances</h4>
    <span id="hmoCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($hmos) ?> HMO<?= count($hmos) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Logo</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Name</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="hmoTableBody">
        <?php if (empty($hmos)): ?>
            <tr><td colspan="4" style="padding:40px;text-align:center;color:#aaa;">No HMOs yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($hmos as $i => $h):
                $logo = !empty($h['logo']) ? $h['logo'] : HMO_DEFAULT;
            ?>
            <tr id="row-<?= $h['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $h['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="../<?= htmlspecialchars($logo) ?>"
                        style="max-width:80px;max-height:48px;object-fit:contain;border-radius:4px;"
                        onerror="this.src='../admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($h['name']) ?></td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editHmo(<?= $h['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteHmo(<?= $h['id'] ?>, '<?= htmlspecialchars(addslashes($h['name'])) ?>')"
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
const AJAX_URL    = 'hmo-ajax.php';
const DEFAULT_LOGO = 'admin/images/default.jpg';

function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('logoPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('removeLogoBtn').style.display = 'inline-block';
    };
    reader.readAsDataURL(input.files[0]);
}

function removeLogo() {
    document.getElementById('logoInput').value        = '';
    document.getElementById('logoPreview').style.display = 'none';
    document.getElementById('logoPreview').src        = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('removeLogoBtn').style.display     = 'none';
    document.getElementById('existingLogo').value     = '';
}

function saveHmo() {
    const id            = document.getElementById('hmoId').value;
    const action        = document.getElementById('formAction').value;
    const name          = document.getElementById('name').value.trim();
    const existingLogo  = document.getElementById('existingLogo').value;
    const logoFile      = document.getElementById('logoInput').files[0];

    if (!name) { showToast('HMO name is required.', false); return; }

    const data = new FormData();
    data.append('action', action);
    data.append('name', name);
    data.append('existing_logo', existingLogo);
    if (id) data.append('id', id);
    if (logoFile) data.append('logo', logoFile);

    fetch(AJAX_URL, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        showToast(res.message, res.success);
        if (res.success) { resetForm(); reloadTable(); }
    });
}

function editHmo(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
    .then(r => r.json())
    .then(res => {
        if (!res.success) return;
        const h = res.hmo;
        document.getElementById('hmoId').value         = h.id;
        document.getElementById('formAction').value    = 'edit';
        document.getElementById('name').value          = h.name;
        document.getElementById('existingLogo').value  = h.logo ?? '';
        document.getElementById('formTitle').textContent = '✏️ Edit HMO';
        document.getElementById('cancelBtn').style.display = 'inline-block';

        const logo = h.logo || DEFAULT_LOGO;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('logoPreview');
        preview.src = '../' + logo;
        preview.style.display = 'block';
        document.getElementById('removeLogoBtn').style.display = 'inline-block';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function deleteHmo(id, name) {
    if (!confirm('Delete ' + name + '? This cannot be undone.')) return;
    const data = new FormData();
    data.append('action', 'delete');
    data.append('id', id);
    fetch(AJAX_URL, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        showToast(res.message, res.success);
        if (res.success) reloadTable();
    });
}

function reloadTable() {
    fetch(AJAX_URL + '?action=fetch')
    .then(r => r.json())
    .then(res => {
        const tbody = document.getElementById('hmoTableBody');
        const count = res.hmos.length;
        document.getElementById('hmoCount').textContent = count + ' HMO' + (count !== 1 ? 's' : '');

        if (!count) {
            tbody.innerHTML = '<tr><td colspan="4" style="padding:40px;text-align:center;color:#aaa;">No HMOs yet.</td></tr>';
            return;
        }

        tbody.innerHTML = res.hmos.map((h, i) => `
            <tr id="row-${h.id}" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${h.id}</td>
                <td style="padding:11px 14px;">
                    <img src="../${esc(h.logo || DEFAULT_LOGO)}"
                        style="max-width:80px;max-height:48px;object-fit:contain;border-radius:4px;"
                        onerror="this.src='../admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(h.name)}</td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editHmo(${h.id})"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteHmo(${h.id}, '${esc(h.name)}')"
                            style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                    </div>
                </td>
            </tr>
        `).join('');
    });
}

function resetForm() {
    document.getElementById('hmoId').value             = '';
    document.getElementById('name').value              = '';
    document.getElementById('formAction').value        = 'add';
    document.getElementById('existingLogo').value      = '';
    document.getElementById('formTitle').textContent   = '➕ Add New HMO';
    document.getElementById('cancelBtn').style.display = 'none';
    removeLogo();
}

function showToast(msg, success) {
    const t = document.getElementById('toast');
    t.textContent      = (success ? '✅ ' : '❌ ') + msg;
    t.style.background = success ? '#e6f9f0' : '#fdf0f0';
    t.style.color      = success ? '#1a7a4a' : '#a02020';
    t.style.borderLeft = '4px solid ' + (success ? '#2ecc71' : '#e74c3c');
    t.style.display    = 'flex';
    t.style.opacity    = '1';
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.style.display = 'none', 300); }, 3000);
}

function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}
</script>