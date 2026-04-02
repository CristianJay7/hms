<?php
global $con;
$reviews = [];
$result  = mysqli_query($con, "SELECT * FROM reviews ORDER BY review_date DESC");
while ($row = mysqli_fetch_assoc($result)) $reviews[] = $row;
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Review</h4>

<div id="reviewForm">
    <input type="hidden" id="reviewId">
    <input type="hidden" id="formAction" value="create">
    <input type="hidden" id="existingPhoto" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Patient Name *</label>
            <input type="text" id="patientName" placeholder="e.g. Maria Santos"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Review Date *</label>
            <input type="date" id="reviewDate"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Star Rating *</label>
            <div id="starPicker" style="display:flex;gap:6px;padding:8px 0;">
                <?php for($i=1;$i<=5;$i++): ?>
                <span class="star-pick" data-val="<?= $i ?>"
                    style="font-size:1.8rem;cursor:pointer;color:#ddd;transition:color 0.15s;"
                    onmouseover="hoverStars(<?= $i ?>)"
                    onmouseout="resetStars()"
                    onclick="selectStar(<?= $i ?>)">★</span>
                <?php endfor; ?>
            </div>
            <input type="hidden" id="reviewRating" value="5">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Patient Photo</label>
            <div id="uploadArea" onclick="document.getElementById('photoInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload photo</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP</div>
                </div>
                <img id="photoPreview" src="" alt="Preview"
                    style="display:none;width:60px;height:60px;object-fit:cover;border-radius:50%;">
            </div>
            <input type="file" id="photoInput" accept="image/jpeg,image/png,image/webp"
                style="display:none;" onchange="previewPhoto(this)">
            <button id="removePhotoBtn" onclick="removePhoto()"
                style="display:none;margin-top:6px;padding:4px 10px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                Remove photo
            </button>
        </div>

    </div>

    <div style="margin-bottom:14px;">
        <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Review Text *</label>
        <textarea id="reviewText" rows="4" placeholder="Patient's review..."
            style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveReview()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Review
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Reviews</h4>
    <span id="reviewCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($reviews) ?> Review<?= count($reviews) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Photo</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Patient</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Rating</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Review</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Date</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="reviewTableBody">
        <?php if (empty($reviews)): ?>
            <tr><td colspan="7" style="padding:40px;text-align:center;color:#aaa;">No reviews yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($reviews as $r): ?>
            <tr id="row-<?= $r['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $r['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="../<?= htmlspecialchars(!empty($r['photo']) ? $r['photo'] : 'admin/images/default.jpg') ?>"
                        style="width:44px;height:44px;object-fit:cover;border-radius:50%;"
                        onerror="this.onerror=null;this.src='../admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($r['patient_name']) ?></td>
                <td style="padding:11px 14px;font-size:1rem;color:#f4a40a;">
                    <?= str_repeat('★', $r['rating']) ?><span style="color:#ddd;"><?= str_repeat('★', 5 - $r['rating']) ?></span>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#666;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    <?= htmlspecialchars($r['review_text']) ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;">
                    <?= date('M d, Y', strtotime($r['review_date'])) ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editReview(<?= $r['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteReview(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['patient_name'])) ?>')"
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
const AJAX_URL = 'reviews-ajax.php';
let selectedRating = 5;

// ── Star picker ──
function hoverStars(val) {
    document.querySelectorAll('.star-pick').forEach((s, i) => {
        s.style.color = i < val ? '#f4a40a' : '#ddd';
    });
}

function resetStars() {
    document.querySelectorAll('.star-pick').forEach((s, i) => {
        s.style.color = i < selectedRating ? '#f4a40a' : '#ddd';
    });
}

function selectStar(val) {
    selectedRating = val;
    document.getElementById('reviewRating').value = val;
    resetStars();
}

// Init stars
selectStar(5);

// ── Photo preview ──
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('photoPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('removePhotoBtn').style.display = 'inline-block';
    };
    reader.readAsDataURL(input.files[0]);
}

function removePhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('photoPreview').src = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('removePhotoBtn').style.display = 'none';
    document.getElementById('existingPhoto').value = '';
}

// ── Save ──
function saveReview() {
    const id      = document.getElementById('reviewId').value;
    const action  = document.getElementById('formAction').value;
    let name    = document.getElementById('patientName').value.trim();
    const text    = document.getElementById('reviewText').value.trim();
    const rating  = document.getElementById('reviewRating').value;
    const date    = document.getElementById('reviewDate').value;
    const existing= document.getElementById('existingPhoto').value;
    const photo   = document.getElementById('photoInput').files[0];

    if (!name) name = 'Anonymous';
        if (!text)  { showToast('Review text is required.', false); return; }
    if (!date)  { showToast('Date is required.', false); return; }

    const fd = new FormData();
    fd.append('action',         action);
    fd.append('patient_name',   name);
    fd.append('review_text',    text);
    fd.append('rating',         rating);
    fd.append('review_date',    date);
    fd.append('existing_photo', existing);
    if (id) fd.append('id', id);
    if (photo) fd.append('photo', photo);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        });
}

// ── Edit ──
function editReview(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const r = res.review;
            document.getElementById('reviewId').value       = r.id;
            document.getElementById('formAction').value     = 'update';
            document.getElementById('patientName').value    = r.patient_name;
            document.getElementById('reviewText').value     = r.review_text;
            document.getElementById('reviewDate').value     = r.review_date;
            document.getElementById('existingPhoto').value  = r.photo || '';
            document.getElementById('formTitle').textContent = '✏️ Edit Review';
            document.getElementById('cancelBtn').style.display = 'inline-block';

            selectedRating = parseInt(r.rating);
            document.getElementById('reviewRating').value = selectedRating;
            resetStars();

            if (r.photo) {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                const preview = document.getElementById('photoPreview');
                preview.src = '../' + r.photo;
                preview.style.display = 'block';
                document.getElementById('removePhotoBtn').style.display = 'inline-block';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

// ── Delete ──
function deleteReview(id, name) {
    if (!confirm('Delete review by "' + name + '"? This cannot be undone.')) return;
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

// ── Reload table ──
function reloadTable() {
    fetch(AJAX_URL + '?action=list')
        .then(r => r.json())
        .then(rows => {
            const tbody = document.getElementById('reviewTableBody');
            const count = rows.length;
            document.getElementById('reviewCount').textContent = count + ' Review' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="7" style="padding:40px;text-align:center;color:#aaa;">No reviews yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const stars = '★'.repeat(r.rating) + '<span style="color:#ddd;">★</span>'.repeat(5 - r.rating);
                const date  = new Date(r.review_date).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'});
                return `
                <tr id="row-${r.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${r.id}</td>
                    <td style="padding:11px 14px;">
                        <img src="../${esc(r.photo || 'admin/images/default.jpg')}"
                            style="width:44px;height:44px;object-fit:cover;border-radius:50%;"
                            onerror="this.onerror=null;this.src='../admin/images/default.jpg'">
                    </td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(r.patient_name)}</td>
                    <td style="padding:11px 14px;font-size:1rem;color:#f4a40a;">${stars}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#666;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${esc(r.review_text)}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${date}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editReview(${r.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteReview(${r.id}, '${esc(r.patient_name)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

// ── Reset ──
function resetForm() {
    document.getElementById('reviewId').value            = '';
    document.getElementById('patientName').value         = '';
    document.getElementById('reviewText').value          = '';
    document.getElementById('reviewDate').value          = '';
    document.getElementById('formAction').value          = 'create';
    document.getElementById('existingPhoto').value       = '';
    document.getElementById('formTitle').textContent     = '➕ Add New Review';
    document.getElementById('cancelBtn').style.display   = 'none';
    selectedRating = 5;
    selectStar(5);
    removePhoto();
}

// ── Toast ──
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