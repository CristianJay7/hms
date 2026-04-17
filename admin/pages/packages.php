<?php
global $con;
$packages = [];
$res      = mysqli_query($con, "SELECT * FROM packages ORDER BY sort_order ASC, created_at DESC");
while ($row = mysqli_fetch_assoc($res)) $packages[] = $row;
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Package</h4>

<div id="packageForm">
    <input type="hidden" id="pkgId">
    <input type="hidden" id="formAction" value="add">
    <input type="hidden" id="existingImage" value="">
    <input type="hidden" id="removeImageFlag" value="0">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Title / Label <span style="color:#aaa;font-weight:400;">(optional)</span></label>
            <input type="text" id="pkgTitle" placeholder="e.g. Executive Health Package"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Sort Order</label>
            <input type="number" id="pkgSort" value="0" min="0"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Status</label>
            <select id="pkgStatus"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="active">✅ Active</option>
                <option value="inactive">🚫 Inactive</option>
            </select>
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Package Image <span style="color:#cc2233;">*</span></label>
            <div id="uploadArea" onclick="document.getElementById('imageInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload image</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP</div>
                </div>
                <img id="imagePreview" src="" alt="Preview"
                    style="display:none;max-width:100%;max-height:100px;object-fit:contain;border-radius:4px;">
            </div>
            <input type="file" id="imageInput" accept="image/jpeg,image/png,image/webp"
                style="display:none;" onchange="previewImage(this)">
            <button id="removeImageBtn" onclick="removeImage()"
                style="display:none;margin-top:6px;padding:4px 10px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                Remove image
            </button>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="savePkg()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Package
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Packages</h4>
    <span id="pkgCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($packages) ?> Package<?= count($packages) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Image</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Title</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Order</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Status</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="pkgTableBody">
        <?php if (empty($packages)): ?>
            <tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No packages yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($packages as $p): ?>
            <tr id="row-<?= $p['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $p['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="<?= htmlspecialchars(!empty($p['image']) ? $p['image'] : '/hms/admin/images/default.jpg') ?>"
                        style="width:80px;height:52px;object-fit:cover;border-radius:6px;"
                        onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">
                    <?= htmlspecialchars($p['title'] ?: '—') ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;"><?= $p['sort_order'] ?></td>
                <td style="padding:11px 14px;">
                    <?php if ($p['status'] === 'active'): ?>
                        <span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Active</span>
                    <?php else: ?>
                        <span style="background:#f5f5f5;color:#999;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Inactive</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editPkg(<?= $p['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deletePkg(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['title'] ?: 'this package')) ?>')"
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
const AJAX_URL = 'packages-ajax.php';

function previewImage(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('imagePreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('removeImageBtn').style.display = 'inline-block';
    };
    reader.readAsDataURL(input.files[0]);
}

function removeImage() {
    document.getElementById('imageInput').value            = '';
    document.getElementById('imagePreview').style.display  = 'none';
    document.getElementById('imagePreview').src            = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('removeImageBtn').style.display    = 'none';
    document.getElementById('existingImage').value         = '';
    document.getElementById('removeImageFlag').value       = '1';
}

function savePkg() {
    const id      = document.getElementById('pkgId').value;
    const action  = document.getElementById('formAction').value;
    const title   = document.getElementById('pkgTitle').value.trim();
    const sort    = document.getElementById('pkgSort').value;
    const status  = document.getElementById('pkgStatus').value;
    const existing= document.getElementById('existingImage').value;
    const imgFile = document.getElementById('imageInput').files[0];

    if (action === 'add' && !imgFile) { showToast('Please upload an image.', false); return; }

    const fd = new FormData();
    fd.append('action',        action);
    fd.append('title',         title);
    fd.append('sort_order',    sort);
    fd.append('status',        status);
    fd.append('existing_image',existing);
    fd.append('remove_image',  document.getElementById('removeImageFlag').value);
    if (id)      fd.append('id',    id);
    if (imgFile) fd.append('image', imgFile);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editPkg(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const p = res.package;
            document.getElementById('pkgId').value          = p.id;
            document.getElementById('formAction').value     = 'edit';
            document.getElementById('pkgTitle').value       = p.title  || '';
            document.getElementById('pkgSort').value        = p.sort_order || 0;
            document.getElementById('pkgStatus').value      = p.status || 'active';
            document.getElementById('existingImage').value  = p.image  || '';
            document.getElementById('removeImageFlag').value= '0';
            document.getElementById('formTitle').textContent = '✏️ Edit Package';
            document.getElementById('cancelBtn').style.display = 'inline-block';

            if (p.image) {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                const preview = document.getElementById('imagePreview');
                preview.src = p.image;
                preview.style.display = 'block';
                document.getElementById('removeImageBtn').style.display = 'inline-block';
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deletePkg(id, title) {
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
            const tbody = document.getElementById('pkgTableBody');
            const count = rows.length;
            document.getElementById('pkgCount').textContent = count + ' Package' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No packages yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(p => {
                const badge = p.status === 'active'
                    ? `<span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Active</span>`
                    : `<span style="background:#f5f5f5;color:#999;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Inactive</span>`;
                return `
                <tr id="row-${p.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${p.id}</td>
                    <td style="padding:11px 14px;">
                        <img src="${esc(p.image || '/hms/admin/images/default.jpg')}"
                            style="width:80px;height:52px;object-fit:cover;border-radius:6px;"
                            onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                    </td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(p.title || '—')}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${p.sort_order}</td>
                    <td style="padding:11px 14px;">${badge}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editPkg(${p.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deletePkg(${p.id}, '${esc(p.title || 'this package')}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm() {
    document.getElementById('pkgId').value              = '';
    document.getElementById('pkgTitle').value           = '';
    document.getElementById('pkgSort').value            = '0';
    document.getElementById('pkgStatus').value          = 'active';
    document.getElementById('formAction').value         = 'add';
    document.getElementById('existingImage').value      = '';
    document.getElementById('removeImageFlag').value    = '0';
    document.getElementById('formTitle').textContent    = '➕ Add New Package';
    document.getElementById('cancelBtn').style.display  = 'none';
    removeImage();
    document.getElementById('removeImageFlag').value    = '0'; // reset after removeImage()
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