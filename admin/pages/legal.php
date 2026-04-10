<?php
global $con;

// Fetch all
function fetch_legal($con, $table) {
    $rows = [];
    $res  = mysqli_query($con, "SELECT * FROM `$table` ORDER BY sort_order ASC, created_at ASC");
    while ($row = mysqli_fetch_assoc($res)) $rows[] = $row;
    return $rows;
}

$privacy_rows = fetch_legal($con, 'privacy_sections');
$terms_rows   = fetch_legal($con, 'terms_sections');
$rights_rows  = fetch_legal($con, 'patient_rights');
?>

<!-- Toast -->
<div id="toast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:8px;font-size:0.88rem;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    display:none;align-items:center;gap:8px;
    border-left-width:4px;border-left-style:solid;
"></div>

<!-- Tabs -->
<div style="display:flex;gap:8px;margin-bottom:28px;border-bottom:2px solid #eef1f5;padding-bottom:0;">
    <button onclick="switchTab('privacy')" id="tab-privacy"
        style="padding:10px 20px;border:none;background:none;font-size:0.88rem;font-weight:700;color:#00b6bd;border-bottom:2px solid #00b6bd;cursor:pointer;margin-bottom:-2px;">
        🔒 Privacy Notice
    </button>
    <button onclick="switchTab('terms')" id="tab-terms"
        style="padding:10px 20px;border:none;background:none;font-size:0.88rem;font-weight:700;color:#aaa;border-bottom:2px solid transparent;cursor:pointer;margin-bottom:-2px;">
        📄 Terms & Conditions
    </button>
    <button onclick="switchTab('rights')" id="tab-rights"
        style="padding:10px 20px;border:none;background:none;font-size:0.88rem;font-weight:700;color:#aaa;border-bottom:2px solid transparent;cursor:pointer;margin-bottom:-2px;">
        🏥 Patient Rights
    </button>
</div>

<!-- ── PRIVACY TAB ── -->
<div id="panel-privacy">
    <?php echo renderPanel('privacy', $privacy_rows, true); ?>
</div>

<!-- ── TERMS TAB ── -->
<div id="panel-terms" style="display:none;">
    <?php echo renderPanel('terms', $terms_rows, true); ?>
</div>

<!-- ── RIGHTS TAB ── -->
<div id="panel-rights" style="display:none;">
    <?php echo renderPanel('rights', $rights_rows, false); ?>
</div>

<?php
function renderPanel($type, $rows, $hasIcon) {
    $label = $type === 'privacy' ? 'Section' : ($type === 'terms' ? 'Section' : 'Right');
    $count = count($rows);
    ob_start();
?>
    <h4 id="formTitle-<?= $type ?>" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New <?= $label ?></h4>

    <div id="form-<?= $type ?>">
        <input type="hidden" id="<?= $type ?>-id">
        <input type="hidden" id="<?= $type ?>-action" value="create">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

            <?php if ($hasIcon): ?>
            <div>
                <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Font Awesome Icon</label>
                <input type="text" id="<?= $type ?>-icon" placeholder="e.g. fa-solid fa-shield"
                    style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
                <small style="color:#aaa;">Find icons at <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
            </div>
            <?php endif; ?>

            <div <?= $hasIcon ? '' : 'style="grid-column:1/-1;"' ?>>
                <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;"><?= $label ?> Title *</label>
                <input type="text" id="<?= $type ?>-title" placeholder="e.g. <?= $type === 'rights' ? 'Right to Quality Care' : 'Overview' ?>"
                    style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
            </div>

            <div style="grid-column:1/-1;">
                <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;"><?= $type === 'rights' ? 'Description' : 'Content' ?> *</label>
                <textarea id="<?= $type ?>-content" rows="4" placeholder="Enter content here..."
                    style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
            </div>

            <div>
                <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Sort Order</label>
                <input type="number" id="<?= $type ?>-order" value="0" min="0"
                    style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
            </div>

        </div>

        <div style="display:flex;gap:10px;">
            <button onclick="saveItem('<?= $type ?>')"
                style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                Save <?= $label ?>
            </button>
            <button id="<?= $type ?>-cancelBtn" onclick="resetForm('<?= $type ?>')"
                style="display:none;padding:9px 22px;background:#e8edf2;color:#555;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                Cancel
            </button>
        </div>
    </div>

    <hr style="margin:28px 0;border:none;border-top:1px solid #eef1f5;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h4 style="font-size:1rem;color:#1a3c5e;">All <?= $label ?>s</h4>
        <span id="<?= $type ?>-count" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
            <?= $count ?> <?= $label ?><?= $count !== 1 ? 's' : '' ?>
        </span>
    </div>

    <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#1a3c5e;">
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
                <?php if ($hasIcon): ?>
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Icon</th>
                <?php endif; ?>
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Title</th>
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Content</th>
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Order</th>
                <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody id="<?= $type ?>-tbody">
            <?php if (empty($rows)): ?>
                <tr><td colspan="<?= $hasIcon ? 6 : 5 ?>" style="padding:40px;text-align:center;color:#aaa;">No entries yet.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $r):
                    $content_col = $type === 'rights' ? $r['description'] : $r['content'];
                ?>
                <tr id="<?= $type ?>-row-<?= $r['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $r['id'] ?></td>
                    <?php if ($hasIcon): ?>
                    <td style="padding:11px 14px;font-size:1.2rem;color:#00b6bd;">
                        <i class="<?= htmlspecialchars($r['icon'] ?? '') ?>"></i>
                    </td>
                    <?php endif; ?>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($r['title']) ?></td>
                    <td style="padding:11px 14px;font-size:0.82rem;color:#778899;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= htmlspecialchars(mb_strimwidth($content_col, 0, 80, '...')) ?>
                    </td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;"><?= $r['sort_order'] ?></td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editItem('<?= $type ?>',<?= $r['id'] ?>)"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteItem('<?= $type ?>',<?= $r['id'] ?>,'<?= htmlspecialchars(addslashes($r['title'])) ?>')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
<?php
    return ob_get_clean();
}
?>

<script>
const AJAX_URL  = 'legal-ajax.php';
const HAS_ICON  = { privacy: true, terms: true, rights: false };

function switchTab(type) {
    ['privacy','terms','rights'].forEach(t => {
        document.getElementById('panel-' + t).style.display = t === type ? 'block' : 'none';
        const tab = document.getElementById('tab-' + t);
        tab.style.color       = t === type ? '#00b6bd' : '#aaa';
        tab.style.borderBottom= t === type ? '2px solid #00b6bd' : '2px solid transparent';
    });
}

function saveItem(type) {
    const id      = document.getElementById(type + '-id').value;
    const action  = document.getElementById(type + '-action').value;
    const title   = document.getElementById(type + '-title').value.trim();
    const content = document.getElementById(type + '-content').value.trim();
    const order   = document.getElementById(type + '-order').value;
    const icon    = HAS_ICON[type] ? document.getElementById(type + '-icon').value.trim() : '';

    if (!title)   { showToast('Title is required.', false); return; }
    if (!content) { showToast('Content is required.', false); return; }

    const fd = new FormData();
    fd.append('action',     action);
    fd.append('type',       type);
    fd.append('title',      title);
    fd.append('content',    content);
    fd.append('sort_order', order);
    if (icon) fd.append('icon', icon);
    if (id)   fd.append('id', id);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(type); reloadTable(type); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editItem(type, id) {
    fetch(AJAX_URL + '?action=get_one&type=' + type + '&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const item = res.item;
            document.getElementById(type + '-id').value      = item.id;
            document.getElementById(type + '-action').value  = 'update';
            document.getElementById(type + '-title').value   = item.title;
            document.getElementById(type + '-content').value = item.description || item.content || '';
            document.getElementById(type + '-order').value   = item.sort_order;
            if (HAS_ICON[type]) document.getElementById(type + '-icon').value = item.icon || '';
            document.getElementById('formTitle-' + type).textContent = '✏️ Edit Entry';
            document.getElementById(type + '-cancelBtn').style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteItem(type, id, title) {
    if (!confirm('Delete "' + title + '"?')) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('type',   type);
    fd.append('id',     id);
    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) reloadTable(type);
        });
}

function reloadTable(type) {
    fetch(AJAX_URL + '?action=list&type=' + type)
        .then(r => r.json())
        .then(rows => {
            const tbody   = document.getElementById(type + '-tbody');
            const count   = rows.length;
            const hasIcon = HAS_ICON[type];
            const cols    = hasIcon ? 6 : 5;
            const label   = type === 'rights' ? 'Right' : 'Section';
            document.getElementById(type + '-count').textContent = count + ' ' + label + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = `<tr><td colspan="${cols}" style="padding:40px;text-align:center;color:#aaa;">No entries yet.</td></tr>`;
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const content = (r.description || r.content || '').substring(0, 80);
                const iconTd  = hasIcon ? `<td style="padding:11px 14px;font-size:1.2rem;color:#00b6bd;"><i class="${esc(r.icon||'')}"></i></td>` : '';
                return `
                <tr id="${type}-row-${r.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${r.id}</td>
                    ${iconTd}
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;">${esc(r.title)}</td>
                    <td style="padding:11px 14px;font-size:0.82rem;color:#778899;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${esc(content)}${(r.description||r.content||'').length>80?'...':''}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${r.sort_order}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editItem('${type}',${r.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteItem('${type}',${r.id},'${esc(r.title)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm(type) {
    document.getElementById(type + '-id').value             = '';
    document.getElementById(type + '-title').value          = '';
    document.getElementById(type + '-content').value        = '';
    document.getElementById(type + '-order').value          = '0';
    document.getElementById(type + '-action').value         = 'create';
    document.getElementById('formTitle-' + type).textContent = '➕ Add New Entry';
    document.getElementById(type + '-cancelBtn').style.display = 'none';
    if (HAS_ICON[type]) document.getElementById(type + '-icon').value = '';
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