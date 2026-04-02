<?php
global $con;

$facilities = [];
$result     = mysqli_query($con, "SELECT * FROM facilities ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $facilities[] = $row;

define('FAC_DEFAULT', '/hms/admin/images/default.jpg');
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Facility</h4>

<div id="facilityForm">
    <input type="hidden" id="facilityId">
    <input type="hidden" id="formAction" value="add">
    <input type="hidden" id="existingImage" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Facility Name *</label>
            <input type="text" id="name" placeholder="e.g. Operating Theaters"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Image</label>
            <div id="uploadArea" onclick="document.getElementById('imageInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload image</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP</div>
                </div>
                <img id="imagePreview" src="" alt="Preview"
                    style="display:none;width:100%;max-height:80px;object-fit:cover;border-radius:6px;">
            </div>
            <input type="file" id="imageInput" accept="image/jpeg,image/png,image/webp"
                style="display:none;" onchange="previewImage(this)">
            <button id="removeImageBtn" onclick="removeImage()"
                style="display:none;margin-top:6px;padding:4px 10px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                Remove image
            </button>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Description</label>
            <textarea id="description" placeholder="Brief description of this facility..."
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;resize:vertical;min-height:80px;outline:none;"></textarea>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveFacility()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Facility
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Facilities</h4>
    <span id="facilityCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($facilities) ?> facilit<?= count($facilities) !== 1 ? 'ies' : 'y' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Image</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Name</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Description</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="facilityTableBody">
        <?php if (empty($facilities)): ?>
            <tr><td colspan="5" style="padding:40px;text-align:center;color:#aaa;">No facilities yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($facilities as $i => $f):
                $img = !empty($f['image']) ? $f['image'] : FAC_DEFAULT;
            ?>
            <tr id="row-<?= $f['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $f['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="<?= htmlspecialchars($img) ?>"
                        style="width:60px;height:40px;object-fit:cover;border-radius:5px;"
                        onerror="this.src='/hms/admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($f['name']) ?></td>
                <td style="padding:11px 14px;font-size:0.82rem;color:#778899;max-width:260px;">
                    <?= htmlspecialchars(mb_strimwidth($f['description'] ?? '', 0, 80, '...')) ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editFacility(<?= $f['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteFacility(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes($f['name'])) ?>')"
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
const AJAX_URL    = '/hms/admin/facility-ajax.php';
const DEFAULT_IMG = '/hms/admin/images/default.jpg';

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
    document.getElementById('imageInput').value           = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imagePreview').src           = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('removeImageBtn').style.display    = 'none';
    document.getElementById('existingImage').value        = '';
}

function saveFacility() {
    const id            = document.getElementById('facilityId').value;
    const action        = document.getElementById('formAction').value;
    const name          = document.getElementById('name').value.trim();
    const description   = document.getElementById('description').value.trim();
    const existingImage = document.getElementById('existingImage').value;
    const imageFile     = document.getElementById('imageInput').files[0];

    if (!name) { showToast('Facility name is required.', false); return; }

    const data = new FormData();
    data.append('action', action);
    data.append('name', name);
    data.append('description', description);
    data.append('existing_image', existingImage);
    if (id) data.append('id', id);
    if (imageFile) data.append('image', imageFile);

    fetch(AJAX_URL, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        showToast(res.message, res.success);
        if (res.success) { resetForm(); reloadTable(); }
    });
}

function editFacility(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
    .then(r => r.json())
    .then(res => {
        if (!res.success) return;
        const f = res.facility;
        document.getElementById('facilityId').value     = f.id;
        document.getElementById('formAction').value     = 'edit';
        document.getElementById('name').value           = f.name;
        document.getElementById('description').value    = f.description ?? '';
        document.getElementById('existingImage').value  = f.image ?? '';
        document.getElementById('formTitle').textContent = '✏️ Edit Facility';
        document.getElementById('cancelBtn').style.display = 'inline-block';

        const img = f.image || DEFAULT_IMG;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('imagePreview');
        preview.src =   img;
        preview.style.display = 'block';
        document.getElementById('removeImageBtn').style.display = 'inline-block';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function deleteFacility(id, name) {
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
        const tbody = document.getElementById('facilityTableBody');
        const count = res.facilities.length;
        document.getElementById('facilityCount').textContent = count + ' facilit' + (count !== 1 ? 'ies' : 'y');

        if (!count) {
            tbody.innerHTML = '<tr><td colspan="5" style="padding:40px;text-align:center;color:#aaa;">No facilities yet.</td></tr>';
            return;
        }

        tbody.innerHTML = res.facilities.map((f, i) => `
            <tr id="row-${f.id}" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${f.id}</td>
                <td style="padding:11px 14px;">
                    <img src="../${esc(f.image || DEFAULT_IMG)}"
                        style="width:60px;height:40px;object-fit:cover;border-radius:5px;"
                        onerror="this.src='/hms/admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(f.name)}</td>
                <td style="padding:11px 14px;font-size:0.82rem;color:#778899;max-width:260px;">
                    ${esc((f.description || '').substring(0, 80))}${(f.description || '').length > 80 ? '...' : ''}
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editFacility(${f.id})"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteFacility(${f.id}, '${esc(f.name)}')"
                            style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                    </div>
                </td>
            </tr>
        `).join('');
    });
}

function resetForm() {
    ['facilityId','name','description'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('formAction').value        = 'add';
    document.getElementById('existingImage').value     = '';
    document.getElementById('formTitle').textContent   = '➕ Add New Facility';
    document.getElementById('cancelBtn').style.display = 'none';
    removeImage();
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