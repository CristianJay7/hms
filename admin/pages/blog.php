<?php
global $con;
$blogs  = [];
$result = mysqli_query($con, "SELECT * FROM blogs ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) $blogs[] = $row;
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
<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New Blog Post</h4>

<div id="blogForm">
    <input type="hidden" id="blogId">
    <input type="hidden" id="formAction" value="create">
    <input type="hidden" id="existingImage" value="">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Title *</label>
            <input type="text" id="blogTitle" placeholder="e.g. Diabetes Prevention"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Published Date *</label>
            <input type="date" id="blogDate"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Status</label>
            <select id="blogStatus"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="published">✅ Published</option>
                <option value="draft">📝 Draft</option>
            </select>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Excerpt <span style="color:#aaa;font-weight:400;">(short description shown on cards)</span></label>
            <textarea id="blogExcerpt" rows="2" placeholder="Brief summary..."
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Full Content <span style="color:#aaa;font-weight:400;">(shown on details page)</span></label>
            <textarea id="blogContent" rows="6" placeholder="Full article content..."
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Cover Image</label>
            <div id="uploadArea" onclick="document.getElementById('imageInput').click()"
                style="border:2px dashed #dde3ea;border-radius:6px;padding:14px;text-align:center;cursor:pointer;background:#fafbfc;">
                <div id="uploadPlaceholder">
                    <div style="font-size:1.4rem;margin-bottom:4px;">🖼️</div>
                    <div style="font-size:0.8rem;color:#aaa;">Click to upload image</div>
                    <div style="font-size:0.72rem;color:#ccc;margin-top:2px;">JPG, PNG, WEBP</div>
                </div>
                <img id="imagePreview" src="" alt="Preview"
                    style="display:none;max-width:200px;max-height:120px;object-fit:cover;border-radius:4px;">
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
        <button onclick="saveBlog()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save Post
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All Blog Posts</h4>
    <span id="blogCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($blogs) ?> Post<?= count($blogs) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Image</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Title</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Status</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Date</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="blogTableBody">
        <?php if (empty($blogs)): ?>
            <tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No posts yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($blogs as $b): ?>
            <tr id="row-<?= $b['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $b['id'] ?></td>
                <td style="padding:11px 14px;">
                    <img src="<?= htmlspecialchars(!empty($b['image']) ? $b['image'] : '/hms/admin/images/default.jpg') ?>"
                        style="width:70px;height:48px;object-fit:cover;border-radius:6px;"
                        onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                </td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($b['title']) ?></td>
                <td style="padding:11px 14px;">
                    <?php if ($b['status'] === 'published'): ?>
                        <span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Published</span>
                    <?php else: ?>
                        <span style="background:#fff8e6;color:#b07a00;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Draft</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;">
                    <?= $b['published_date'] ? date('M d, Y', strtotime($b['published_date'])) : '—' ?>
                </td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editBlog(<?= $b['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteBlog(<?= $b['id'] ?>, '<?= htmlspecialchars(addslashes($b['title'])) ?>')"
                            style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

<!-- Photo Gallery Manager — shown after clicking Edit -->
<div id="photoManager" style="display:none;margin-top:32px;">
    <hr style="border:none;border-top:1px solid #eef1f5;margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <h4 style="font-size:1rem;color:#1a3c5e;">📷 Photo Gallery <span id="photoPostTitle" style="color:#00b6bd;"></span></h4>
        <span id="photoCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">0 Photos</span>
    </div>
    <div style="margin-bottom:20px;">
        <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:8px;">Add Photos <span style="color:#aaa;font-weight:400;">(select multiple)</span></label>
        <input type="file" id="galleryInput" accept="image/jpeg,image/png,image/webp" multiple
            style="padding:8px;border:1px solid #dde3ea;border-radius:6px;width:100%;font-size:0.88rem;">
        <button onclick="uploadPhotos()"
            style="margin-top:10px;padding:9px 22px;background:#00b6bd;color:#fff;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Upload Photos
        </button>
    </div>
    <div id="photoGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;"></div>
</div>

<script>
const AJAX_URL = 'blog-ajax.php';

document.getElementById('blogDate').value = new Date().toISOString().split('T')[0];

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

function saveBlog() {
    const id      = document.getElementById('blogId').value;
    const action  = document.getElementById('formAction').value;
    const title   = document.getElementById('blogTitle').value.trim();
    const excerpt = document.getElementById('blogExcerpt').value.trim();
    const content = document.getElementById('blogContent').value.trim();
    const date    = document.getElementById('blogDate').value;
    const status  = document.getElementById('blogStatus').value;
    const existing= document.getElementById('existingImage').value;
    const imgFile = document.getElementById('imageInput').files[0];

    if (!title) { showToast('Title is required.', false); return; }
    if (!date)  { showToast('Published date is required.', false); return; }

    const fd = new FormData();
    fd.append('action',         action);
    fd.append('title',          title);
    fd.append('excerpt',        excerpt);
    fd.append('content',        content);
    fd.append('published_date', date);
    fd.append('status',         status);
    fd.append('existing_image', existing);
    if (id) fd.append('id', id);
    if (imgFile) fd.append('image', imgFile);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editBlog(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const b = res.blog;
            document.getElementById('blogId').value        = b.id;
            document.getElementById('formAction').value    = 'update';
            document.getElementById('blogTitle').value     = b.title;
            document.getElementById('blogExcerpt').value   = b.excerpt  || '';
            document.getElementById('blogContent').value   = b.content  || '';
            document.getElementById('blogDate').value      = b.published_date || '';
            document.getElementById('blogStatus').value    = b.status   || 'published';
            document.getElementById('existingImage').value = b.image    || '';
            document.getElementById('formTitle').textContent = '✏️ Edit Blog Post';
            document.getElementById('cancelBtn').style.display = 'inline-block';

            if (b.image) {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                const preview = document.getElementById('imagePreview');
                preview.src = b.image;
                preview.style.display = 'block';
                document.getElementById('removeImageBtn').style.display = 'inline-block';
            }

            loadPhotoManager(id);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteBlog(id, title) {
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
            const tbody = document.getElementById('blogTableBody');
            const count = rows.length;
            document.getElementById('blogCount').textContent = count + ' Post' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="6" style="padding:40px;text-align:center;color:#aaa;">No posts yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(b => {
                const date  = b.published_date ? new Date(b.published_date).toLocaleDateString('en-US', {month:'short',day:'2-digit',year:'numeric'}) : '—';
                const badge = b.status === 'published'
                    ? `<span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Published</span>`
                    : `<span style="background:#fff8e6;color:#b07a00;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Draft</span>`;
                return `
                <tr id="row-${b.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${b.id}</td>
                    <td style="padding:11px 14px;">
                        <img src="../${esc(b.image || '/hms/admin/images/default.jpg')}"
                            style="width:70px;height:48px;object-fit:cover;border-radius:6px;"
                            onerror="this.onerror=null;this.src='/hms/admin/images/default.jpg'">
                    </td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(b.title)}</td>
                    <td style="padding:11px 14px;">${badge}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${date}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editBlog(${b.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteBlog(${b.id}, '${esc(b.title)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm() {
    document.getElementById('blogId').value            = '';
    document.getElementById('blogTitle').value         = '';
    document.getElementById('blogExcerpt').value       = '';
    document.getElementById('blogContent').value       = '';
    document.getElementById('blogDate').value          = new Date().toISOString().split('T')[0];
    document.getElementById('blogStatus').value        = 'published';
    document.getElementById('formAction').value        = 'create';
    document.getElementById('existingImage').value     = '';
    document.getElementById('formTitle').textContent   = '➕ Add New Blog Post';
    document.getElementById('cancelBtn').style.display = 'none';
    document.getElementById('photoManager').style.display = 'none';
    document.getElementById('photoGrid').innerHTML     = '';
    removeImage();
}

// ── Photo Gallery ──
let currentBlogId = null;

function loadPhotoManager(blogId) {
    currentBlogId = blogId;
    document.getElementById('photoManager').style.display = 'block';

    fetch(AJAX_URL + '?action=get_photos&id=' + blogId)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            renderPhotos(res.photos);
            const titleEl = document.querySelector('#row-' + blogId + ' td:nth-child(3)');
            if (titleEl) document.getElementById('photoPostTitle').textContent = '— ' + titleEl.textContent.trim();
        });
}

function renderPhotos(photos) {
    const grid  = document.getElementById('photoGrid');
    const count = photos.length;
    document.getElementById('photoCount').textContent = count + ' Photo' + (count !== 1 ? 's' : '');

    if (!count) {
        grid.innerHTML = '<p style="color:#aaa;font-size:0.88rem;grid-column:1/-1;">No photos yet. Upload some above.</p>';
        return;
    }

    grid.innerHTML = photos.map(p => `
        <div id="photo-${p.id}" style="position:relative;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <img src="${esc(p.photo)}" style="width:100%;height:110px;object-fit:cover;display:block;"
                onerror="this.onerror=null;this.src='/hms//admin/images/default.jpg'">
            <button onclick="deletePhoto(${p.id})" style="
                position:absolute;top:6px;right:6px;
                background:rgba(231,76,60,0.85);color:#fff;
                border:none;border-radius:50%;width:26px;height:26px;
                font-size:0.75rem;cursor:pointer;line-height:1;">✕</button>
        </div>
    `).join('');
}

function uploadPhotos() {
    if (!currentBlogId) return;
    const files = document.getElementById('galleryInput').files;
    if (!files.length) { showToast('Please select at least one photo.', false); return; }

    const fd = new FormData();
    fd.append('action',  'add_photos');
    fd.append('blog_id', currentBlogId);
    for (let i = 0; i < files.length; i++) fd.append('photos[]', files[i]);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) {
                document.getElementById('galleryInput').value = '';
                loadPhotoManager(currentBlogId);
            }
        });
}

function deletePhoto(photoId) {
    if (!confirm('Delete this photo?')) return;
    const fd = new FormData();
    fd.append('action',   'delete_photo');
    fd.append('photo_id', photoId);
    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) loadPhotoManager(currentBlogId);
        });
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