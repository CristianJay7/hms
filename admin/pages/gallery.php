<?php
global $con;

$items  = [];
$result = mysqli_query($con, "SELECT * FROM gallery ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $items[] = $row;
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Gallery Image</h4>

<div id="galleryForm">
    <input type="hidden" id="galleryId">
    <input type="hidden" id="formAction" value="add">
    <input type="hidden" id="existingImage" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Title *</label>
            <input type="text" id="title" placeholder="e.g. Hospital Lobby"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Image *</label>
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

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveItem()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Image
        </button>
        <button id="cancelBtn" onclick="resetForm()"
            style="display:none;padding:9px 22px;background:#e8edf2;color:#555;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Cancel
        </button>
    </div>
</div>

<!-- Image Grid Preview -->
<hr style="margin:28px 0;border:none;border-top:1px solid #eef1f5;">

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
    <h4 style="font-size:1rem;color:#1a3c5e;">Gallery Images</h4>
    <span id="galleryCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($items) ?> image<?= count($items) !== 1 ? 's' : '' ?>
    </span>
</div>

<!-- Grid -->
<div id="galleryGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
    <?php if (empty($items)): ?>
        <p id="emptyMsg" style="color:#aaa;font-size:0.9rem;grid-column:1/-1;padding:30px 0;text-align:center;">
            No images yet. Add one above.
        </p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
        <div id="gitem-<?= $item['id'] ?>" style="border-radius:12px;overflow:hidden;box-shadow:0 3px 12px rgba(0,0,0,0.08);background:#fff;position:relative;group">

            <!-- Image -->
            <div style="position:relative;height:140px;overflow:hidden;">
                <img src="../<?= htmlspecialchars($item['image']) ?>"
                    style="width:100%;height:100%;object-fit:cover;display:block;"
                    onerror="this.src='../admin/images/default.jpg'">
                <!-- Hover action buttons -->
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;gap:10px;opacity:0;transition:opacity 0.2s;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                    <button onclick="editItem(<?= $item['id'] ?>)"
                        style="padding:7px 14px;background:#fff;color:#1a6fcc;border:none;border-radius:20px;font-size:0.78rem;font-weight:700;cursor:pointer;">
                        Edit
                    </button>
                    <button onclick="deleteItem(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['title'])) ?>')"
                        style="padding:7px 14px;background:#fff;color:#cc2233;border:none;border-radius:20px;font-size:0.78rem;font-weight:700;cursor:pointer;">
                        Delete
                    </button>
                </div>
            </div>

            <!-- Title -->
            <div style="padding:10px 12px;">
                <p style="font-size:0.82rem;font-weight:600;color:#1a3c5e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= htmlspecialchars($item['title']) ?>
                </p>
            </div>

        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
const AJAX_URL    = 'gallery-ajax.php';
const DEFAULT_IMG = 'admin/images/default.jpg';

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
    document.getElementById('imageInput').value                    = '';
    document.getElementById('imagePreview').style.display          = 'none';
    document.getElementById('imagePreview').src                    = '';
    document.getElementById('uploadPlaceholder').style.display     = 'block';
    document.getElementById('removeImageBtn').style.display        = 'none';
    document.getElementById('existingImage').value                 = '';
}

function saveItem() {
    const id            = document.getElementById('galleryId').value;
    const action        = document.getElementById('formAction').value;
    const title         = document.getElementById('title').value.trim();
    const existingImage = document.getElementById('existingImage').value;
    const imageFile     = document.getElementById('imageInput').files[0];

    if (!title) { showToast('Title is required.', false); return; }

    const data = new FormData();
    data.append('action', action);
    data.append('title', title);
    data.append('existing_image', existingImage);
    if (id) data.append('id', id);
    if (imageFile) data.append('image', imageFile);

    fetch(AJAX_URL, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        showToast(res.message, res.success);
        if (res.success) { resetForm(); reloadGrid(); }
    });
}

function editItem(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
    .then(r => r.json())
    .then(res => {
        if (!res.success) return;
        const item = res.item;
        document.getElementById('galleryId').value      = item.id;
        document.getElementById('formAction').value     = 'edit';
        document.getElementById('title').value          = item.title;
        document.getElementById('existingImage').value  = item.image ?? '';
        document.getElementById('formTitle').textContent = '✏️ Edit Gallery Image';
        document.getElementById('cancelBtn').style.display = 'inline-block';

        document.getElementById('uploadPlaceholder').style.display = 'none';
        const preview = document.getElementById('imagePreview');
        preview.src = '../' + (item.image || DEFAULT_IMG);
        preview.style.display = 'block';
        document.getElementById('removeImageBtn').style.display = 'inline-block';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function deleteItem(id, title) {
    if (!confirm('Delete "' + title + '"? This cannot be undone.')) return;
    const data = new FormData();
    data.append('action', 'delete');
    data.append('id', id);
    fetch(AJAX_URL, { method: 'POST', body: data })
    .then(r => r.json())
    .then(res => {
        showToast(res.message, res.success);
        if (res.success) reloadGrid();
    });
}

function reloadGrid() {
    fetch(AJAX_URL + '?action=fetch')
    .then(r => r.json())
    .then(res => {
        const grid  = document.getElementById('galleryGrid');
        const count = res.gallery.length;
        document.getElementById('galleryCount').textContent = count + ' image' + (count !== 1 ? 's' : '');

        if (!count) {
            grid.innerHTML = '<p style="color:#aaa;font-size:0.9rem;grid-column:1/-1;padding:30px 0;text-align:center;">No images yet. Add one above.</p>';
            return;
        }

        grid.innerHTML = res.gallery.map(item => `
            <div id="gitem-${item.id}" style="border-radius:12px;overflow:hidden;box-shadow:0 3px 12px rgba(0,0,0,0.08);background:#fff;">
                <div style="position:relative;height:140px;overflow:hidden;">
                    <img src="../${esc(item.image || DEFAULT_IMG)}"
                        style="width:100%;height:100%;object-fit:cover;display:block;"
                        onerror="this.src='../admin/images/default.jpg'">
                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;gap:10px;opacity:0;transition:opacity 0.2s;"
                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <button onclick="editItem(${item.id})"
                            style="padding:7px 14px;background:#fff;color:#1a6fcc;border:none;border-radius:20px;font-size:0.78rem;font-weight:700;cursor:pointer;">Edit</button>
                        <button onclick="deleteItem(${item.id}, '${esc(item.title)}')"
                            style="padding:7px 14px;background:#fff;color:#cc2233;border:none;border-radius:20px;font-size:0.78rem;font-weight:700;cursor:pointer;">Delete</button>
                    </div>
                </div>
                <div style="padding:10px 12px;">
                    <p style="font-size:0.82rem;font-weight:600;color:#1a3c5e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        ${esc(item.title)}
                    </p>
                </div>
            </div>
        `).join('');
    });
}

function resetForm() {
    ['galleryId', 'title'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('formAction').value        = 'add';
    document.getElementById('existingImage').value     = '';
    document.getElementById('formTitle').textContent   = '➕ Add New Gallery Image';
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