<?php
global $con;
$faqs   = [];
$result = mysqli_query($con, "SELECT * FROM faqs ORDER BY sort_order ASC, created_at ASC");
while ($row = mysqli_fetch_assoc($result)) $faqs[] = $row;
?>

<!-- Toast -->
<div id="toast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:8px;font-size:0.88rem;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    display:none;align-items:center;gap:8px;
    border-left-width:4px;border-left-style:solid;
"></div>

<h4 id="formTitle" style="margin-bottom:16px;font-size:1rem;color:#1a3c5e;">➕ Add New FAQ</h4>

<div id="faqForm">
    <input type="hidden" id="faqId">
    <input type="hidden" id="formAction" value="create">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Question *</label>
            <input type="text" id="faqQuestion" placeholder="e.g. What are your visiting hours?"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div style="grid-column:1/-1;">
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Answer *</label>
            <textarea id="faqAnswer" rows="4" placeholder="Type the answer here..."
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;resize:vertical;"></textarea>
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Sort Order <span style="color:#aaa;font-weight:400;">(lower = appears first)</span></label>
            <input type="number" id="faqOrder" value="0" min="0"
                style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;">
        </div>

        <div>
            <label style="font-size:0.78rem;font-weight:600;color:#555;display:block;margin-bottom:5px;">Status</label>
            <select id="faqStatus" style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
                <option value="published">✅ Published</option>
                <option value="draft">📝 Draft</option>
            </select>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button onclick="saveFaq()"
            style="padding:9px 22px;background:#1a3c5e;color:white;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
            Save FAQ
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
    <h4 style="font-size:1rem;color:#1a3c5e;">All FAQs</h4>
    <span id="faqCount" style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($faqs) ?> FAQ<?= count($faqs) !== 1 ? 's' : '' ?>
    </span>
</div>

<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Question</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Status</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Order</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody id="faqTableBody">
        <?php if (empty($faqs)): ?>
            <tr><td colspan="5" style="padding:40px;text-align:center;color:#aaa;">No FAQs yet. Add one above.</td></tr>
        <?php else: ?>
            <?php foreach ($faqs as $f): ?>
            <tr id="row-<?= $f['id'] ?>" style="border-bottom:1px solid #eef1f5;">
                <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $f['id'] ?></td>
                <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;max-width:400px;">
                    <?= htmlspecialchars(mb_strimwidth($f['question'], 0, 80, '...')) ?>
                </td>
                <td style="padding:11px 14px;">
                    <?php if ($f['status'] === 'published'): ?>
                        <span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Published</span>
                    <?php else: ?>
                        <span style="background:#fff8e6;color:#b07a00;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Draft</span>
                    <?php endif; ?>
                </td>
                <td style="padding:11px 14px;font-size:0.85rem;color:#888;"><?= $f['sort_order'] ?></td>
                <td style="padding:11px 14px;">
                    <div style="display:flex;gap:8px;">
                        <button onclick="editFaq(<?= $f['id'] ?>)"
                            style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                        <button onclick="deleteFaq(<?= $f['id'] ?>, '<?= htmlspecialchars(addslashes(mb_strimwidth($f['question'],0,40,'...'))) ?>')"
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
const AJAX_URL = 'faq-ajax.php';

function saveFaq() {
    const id       = document.getElementById('faqId').value;
    const action   = document.getElementById('formAction').value;
    const question = document.getElementById('faqQuestion').value.trim();
    const answer   = document.getElementById('faqAnswer').value.trim();
    const order    = document.getElementById('faqOrder').value;
    const status   = document.getElementById('faqStatus').value;

    if (!question) { showToast('Question is required.', false); return; }
    if (!answer)   { showToast('Answer is required.', false); return; }

    const fd = new FormData();
    fd.append('action',     action);
    fd.append('question',   question);
    fd.append('answer',     answer);
    fd.append('sort_order', order);
    fd.append('status',     status);
    if (id) fd.append('id', id);

    fetch(AJAX_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            showToast(res.message, res.success);
            if (res.success) { resetForm(); reloadTable(); }
        })
        .catch(() => showToast('Something went wrong.', false));
}

function editFaq(id) {
    fetch(AJAX_URL + '?action=get_one&id=' + id)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const f = res.faq;
            document.getElementById('faqId').value       = f.id;
            document.getElementById('formAction').value  = 'update';
            document.getElementById('faqQuestion').value = f.question;
            document.getElementById('faqAnswer').value   = f.answer;
            document.getElementById('faqOrder').value    = f.sort_order;
            document.getElementById('faqStatus').value   = f.status;
            document.getElementById('formTitle').textContent = '✏️ Edit FAQ';
            document.getElementById('cancelBtn').style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}

function deleteFaq(id, question) {
    if (!confirm('Delete "' + question + '"?')) return;
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
            const tbody = document.getElementById('faqTableBody');
            const count = rows.length;
            document.getElementById('faqCount').textContent = count + ' FAQ' + (count !== 1 ? 's' : '');

            if (!count) {
                tbody.innerHTML = '<tr><td colspan="5" style="padding:40px;text-align:center;color:#aaa;">No FAQs yet.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(f => {
                const badge = f.status === 'published'
                    ? `<span style="background:#e6f9f0;color:#1a7a4a;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Published</span>`
                    : `<span style="background:#fff8e6;color:#b07a00;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Draft</span>`;
                const q = (f.question||'').substring(0,80) + ((f.question||'').length > 80 ? '...' : '');
                return `
                <tr id="row-${f.id}" style="border-bottom:1px solid #eef1f5;">
                    <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;">${f.id}</td>
                    <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;max-width:400px;">${esc(q)}</td>
                    <td style="padding:11px 14px;">${badge}</td>
                    <td style="padding:11px 14px;font-size:0.85rem;color:#888;">${f.sort_order}</td>
                    <td style="padding:11px 14px;">
                        <div style="display:flex;gap:8px;">
                            <button onclick="editFaq(${f.id})"
                                style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Edit</button>
                            <button onclick="deleteFaq(${f.id},'${esc(q)}')"
                                style="padding:6px 14px;background:#fff0f0;color:#cc2233;border:1px solid #f8c8cc;border-radius:5px;font-size:0.8rem;font-weight:600;cursor:pointer;">Delete</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
}

function resetForm() {
    document.getElementById('faqId').value            = '';
    document.getElementById('faqQuestion').value      = '';
    document.getElementById('faqAnswer').value        = '';
    document.getElementById('faqOrder').value         = '0';
    document.getElementById('faqStatus').value        = 'published';
    document.getElementById('formAction').value       = 'create';
    document.getElementById('formTitle').textContent  = '➕ Add New FAQ';
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