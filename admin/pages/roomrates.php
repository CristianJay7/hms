<?php
global $con;
$rooms = [];
$result = mysqli_query($con, "SELECT * FROM roomrates ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $rooms[] = $row;
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Room</h4>

<div id="roomForm">
    <input type="hidden" id="roomId">
    <input type="hidden" id="formAction" value="create">
    <input type="hidden" id="existingImage" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Room Name *</label>
            <input type="text" id="roomName" placeholder="e.g. Deluxe Room"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Price Per Night (₱) *</label>
            <input type="number" id="roomPrice" placeholder="e.g. 3500" min="0" step="0.01"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Capacity</label>
            <input type="text" id="roomCapacity" placeholder="e.g. 1 patient"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Room Image</label>
            <div id="uploadArea" onclick="document.getElementById('imageInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload image</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP</div>
                </div>
                <img id="imagePreview" src="" alt="Preview"
                    style="display:none;max-width:120px;max-height:70px;object-fit:cover;border-radius:4px;">
            </div>
            <input type="file" id="imageInput" accept="image/jpeg,image/png,image/webp"
                style="display:none;" onchange="previewImage(this)">
            <button id="removeImageBtn" onclick="removeImage()"
                style="display:none;margin-top:6px;padding:4px 10px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                Remove image
            </button>
        </div>

    </div>

    <div style="margin-bottom:14px;">
        <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Description</label>
        <textarea id="roomDesc" rows="3" placeholder="Brief description of the room..."
            style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
    </div>

    <div style="margin-bottom:14px;">
        <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Amenities <span style="color:#aaa;font-weight:400;">(comma separated)</span></label>
        <textarea id="roomAmenities" rows="2" placeholder="e.g. TV, Air conditioning, Private bathroom..."
            style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveRoom()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Room
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Room Rates</h4>
    <span id="roomCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($rooms) ?> Room<?= count($rooms) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Image</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Room Name</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Price/Night</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Capacity</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="roomTableBody">
        <?php if (empty($rooms)): ?>
            <tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No rooms yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($rooms as $r): ?>
            <tr id="row-<?= $r['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $r['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="../<?= htmlspecialchars(!empty($r['image']) ? $r['image'] : 'admin/images/default.jpg') ?>"
                        style="width:70px;height:48px;object-fit:cover;border-radius:6px;"
                        onerror="this.onerror=null;this.src='../admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($r['name']) ?></td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:700;color:#00b6bd;">
                    ₱<?= number_format($r['price_per_night'], 2) ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#666;"><?= htmlspecialchars($r['capacity'] ?? '—') ?></td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editRoom(<?= $r['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteRoom(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['name'])) ?>')"
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
const AJAX_URL = 'roomrates-ajax.php';

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
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imagePreview').src = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('removeImageBtn').style.display = 'none';
    document.getElementById('existingImage').value = '';
}

function saveRoom() {
    const id       = document.getElementById('roomId').value;
    const action   = document.getElementById('formAction').value;
    const name     = document.getElementById('roomName').value.trim();
    const price    = document.getElementById('roomPrice').value.trim();
    const capacity = document.getElementById('roomCapacity').value.trim();
    const desc     = document.getElementById('roomDesc').value.trim();
    const amenities= document.getElementById('roomAmenities').value.trim();
    const existing = document.getElementById('existingImage').value;
    const imgFile  = document.getElementById('imageInput').files[0];

    if (!name) { showToast('Room name is required.', false); return; }

    const fd = new FormData();
    fd.append('action',       action);
    fd.append('name',         name);
    fd.append('price',        price);
    fd.append('capacity',     capacity);
    fd.append('description',  desc);
    fd.append('amenities',    amenities);
    fd.append('existing_image', existing);
    if (id) fd.append('id', id);
    if (imgFile) fd.append('image', imgFile);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        });
}

function editRoom(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const r = res.room;
            document.getElementById('roomId').value        = r.id;
            document.getElementById('formAction').value   = 'update';
            document.getElementById('roomName').value     = r.name;
            document.getElementById('roomPrice').value    = r.price_per_night;
            document.getElementById('roomCapacity').value = r.capacity || '';
            document.getElementById('roomDesc').value     = r.description || '';
            document.getElementById('roomAmenities').value= r.amenities || '';
            document.getElementById('existingImage').value= r.image || '';
            document.getElementById('formTitle').textContent = '✏️ Edit Room';
            document.getElementById('cancelBtn').style.display = 'inline-block';

            if (r.image) {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                const preview = document.getElementById('imagePreview');
                preview.src = '../' + r.image;
                preview.style.display = 'block';
                document.getElementById('removeImageBtn').style.display = 'inline-block';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteRoom(id, name) {
    if (!confirm('Delete room "' + name + '"? This cannot be undone.')) return;
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
            const tbody = document.getElementById('roomTableBody');
            const count = rows.length;
            document.getElementById('roomCount').textContent = count + ' Room' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No rooms yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => `
                <tr id="row-${r.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${r.id}</td>
                    <td style="padding:11px 14px;">
                        <img src="../${esc(r.image || 'admin/images/default.jpg')}"
                            style="width:70px;height:48px;object-fit:cover;border-radius:6px;"
                            onerror="this.onerror=null;this.src='../admin/images/default.jpg'">
                    </td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(r.name)}</td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:700;color:#00b6bd;">
                        ₱${parseFloat(r.price_per_night).toLocaleString('en-PH', {minimumFractionDigits:2})}
                    </td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#666;">${esc(r.capacity || '—')}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editRoom(${r.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteRoom(${r.id}, '${esc(r.name)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        });
}

function resetForm() {
    document.getElementById('roomId').value             = '';
    document.getElementById('roomName').value           = '';
    document.getElementById('roomPrice').value          = '';
    document.getElementById('roomCapacity').value       = '';
    document.getElementById('roomDesc').value           = '';
    document.getElementById('roomAmenities').value      = '';
    document.getElementById('formAction').value         = 'create';
    document.getElementById('existingImage').value      = '';
    document.getElementById('formTitle').textContent    = '➕ Add New Room';
    document.getElementById('cancelBtn').style.display  = 'none';
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