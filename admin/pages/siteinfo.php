<?php global $con; ?>
<?php
// Fetch all site info from DB into an associative array
$_info = [];
$_info_result = mysqli_query($con, "SELECT key_name, value FROM siteinfo");
while ($row = mysqli_fetch_assoc($_info_result)) {
    $_info[$row['key_name']] = $row['value'];
}

// Helper — get value or fallback
function info($key, $fallback = '') {
    global $_info;
    return htmlspecialchars($_info[$key] ?? $fallback);
}

function info_raw($key, $fallback = '') {
    global $_info;
    return $_info[$key] ?? $fallback;
}
$home_bg_image = info('home_bg_image', 'images/home.jpg');
// ── Home ──

?>
<!-- Toast -->
<div id="toast" style="
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:12px 20px;border-radius:8px;font-size:0.88rem;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    display:none;align-items:center;gap:8px;
    border-left-width:4px;border-left-style:solid;
"></div>

<?php
// Fetch current values
$_si = [];
$r = mysqli_query($con, "SELECT key_name, value FROM siteinfo");
while ($row = mysqli_fetch_assoc($r)) $_si[$row['key_name']] = $row['value'];
function si($key, $fallback = '') { global $_si; return htmlspecialchars($_si[$key] ?? $fallback); }
?>

<style>
.si-section { margin-bottom: 36px; }
.si-section h4 {
    font-size: 0.8rem; font-weight: 700; color: #fff;
    background: #1a3c5e; padding: 8px 14px; border-radius: 6px;
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 16px;
}
.si-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.si-field label {
    font-size: 0.78rem; font-weight: 600; color: #555;
    display: block; margin-bottom: 5px;
}
.si-field input, .si-field textarea {
    width: 100%; padding: 9px 12px;
    border: 1px solid #dde3ea; border-radius: 6px;
    font-size: 0.9rem; font-family: inherit; outline: none;
    background: #fff; color: #333;
}
.si-field textarea { resize: vertical; }
.si-field input:focus, .si-field textarea:focus { border-color: #00b6bd; }
.si-full { grid-column: 1 / -1; }
</style>

<div class="si-section">
    <h4>🏠 Home Section</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Tagline</label>
            <input type="text" id="home_tagline" value="<?= si('home_tagline') ?>">
        </div>
        <div class="si-field si-full">
            <label>Sub Text</label>
            <textarea id="home_subtext" rows="2"><?= si('home_subtext') ?></textarea>
        </div>
        <div class="si-field si-full">
    <label>Background Type</label>
    <select id="home_bg_type" style="width:100%;padding:9px 12px;border:1px solid #dde3ea;border-radius:6px;font-size:0.9rem;font-family:inherit;outline:none;background:#fff;">
        <option value="image" <?= si('home_bg_type') === 'image' ? 'selected' : '' ?>>🖼️ Image</option>
        <option value="video" <?= si('home_bg_type') === 'video' ? 'selected' : '' ?>>🎬 Video</option>
    </select>
    <small style="color:#aaa;">Choose whether to show an image or video as the home background.</small>
</div>

<div class="si-field si-full">
    <label>Background Video <span style="color:#aaa;font-weight:400;">(MP4 recommended)</span></label>
    <?php if (!empty($_si['home_bg_video'])): ?>
        <div style="margin-bottom:8px;">
            <video src="../<?= si('home_bg_video') ?>" style="max-width:200px;border-radius:6px;" controls muted></video>
        </div>
    <?php endif; ?>
    <input type="file" id="home_bg_video_file" accept="video/mp4,video/webm,video/ogg">
    <small style="color:#aaa;">Leave empty to keep current video. MP4, WEBM, OGG supported.</small>
</div>

    </div>
</div>

<!-- ADD THIS inside the Home Section in admin/pages/siteinfo.php -->
<!-- Place it after the existing home_bg_image file upload field -->











<div class="si-section">
    <h4>📞 Contact Details</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Address</label>
            <input type="text" id="contact_address" value="<?= si('contact_address') ?>">
        </div>
        <div class="si-field">
            <label>Phone</label>
            <input type="text" id="contact_phone" value="<?= si('contact_phone') ?>">
        </div>
        <div class="si-field">
            <label>Email</label>
            <input type="text" id="contact_email" value="<?= si('contact_email') ?>">
        </div>
        <div class="si-field">
            <label>Telephone</label>
            <input type="text" id="contact_telephone" value="<?= si('contact_telephone') ?>">
         </div>
    </div>
</div>

<div class="si-section">
    <h4>🔗 Social Media Links</h4>
    <div class="si-grid">
        <div class="si-field">
            <label>Facebook</label>
            <input type="text" id="social_facebook" value="<?= si('social_facebook') ?>">
        </div>
       
       
       
    </div>
</div>
<br>

<div class="si-section">
    <h4>⭐ Why Choose Us</h4>
    
    <div class="si-grid">
        <div class="si-field">
            <label>Years Badge (e.g. 50+)</label>
            <input type="text" id="whyus_badge_num" value="<?= si('whyus_badge_num') ?>">
        </div>
        <div class="si-field si-full">
    <label>Heading</label>
    <input type="text" id="whyus_heading" value="<?= si('whyus_heading') ?>">
    <small style="color:#aaa;">💡 First word and first word after each comma will be highlighted. e.g. "Your Health Is Our, Highest Priority"</small>
        </div>
        <div class="si-field si-full">
                <label>Description</label>
                <textarea id="whyus_desc" rows="3"><?= si('whyus_desc') ?></textarea>
        </div>
    </div>

    <div class="si-field si-full">
    <label>Why us Image</label>
    <?php if (!empty($_si['whyus_image'])): ?>
        <div style="margin-bottom:8px;">
            <img src="../<?= si('whyus_image') ?>" style="max-width:200px;border-radius:6px;border:1px solid #dde3ea;">
        </div>
    <?php endif; ?>
    <input type="file" id="whyus_image_file" accept="image/jpeg,image/png,image/webp">
    <small style="color:#aaa;">Leave empty to keep current image.</small>
</div>
<br>
<div class="si-section">
    <h4>🏥 About Us</h4>
    <div class="si-grid">

        <div class="si-field si-full">
            <label>Paragraph 1</label>
            <textarea id="about_para1" rows="4"><?= si('about_para1') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Paragraph 2</label>
            <textarea id="about_para2" rows="3"><?= si('about_para2') ?></textarea>
        </div>

        <div class="si-field">
            <label>Bullet 1</label>
            <input type="text" id="about_bullet1" value="<?= si('about_bullet1') ?>">
        </div>
        <div class="si-field">
            <label>Bullet 2</label>
            <input type="text" id="about_bullet2" value="<?= si('about_bullet2') ?>">
        </div>
        <div class="si-field">
            <label>Bullet 3</label>
            <input type="text" id="about_bullet3" value="<?= si('about_bullet3') ?>">
        </div>
        <div class="si-field">
            <label>Bullet 4</label>
            <input type="text" id="about_bullet4" value="<?= si('about_bullet4') ?>">
        </div>

        <div class="si-field si-full">
            <label>Vision</label>
            <textarea id="about_vision" rows="3"><?= si('about_vision') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Mission</label>
            <textarea id="about_mission" rows="3"><?= si('about_mission') ?></textarea>
        </div>

        <div class="si-field si-full">
            <label>About Image</label>
            <?php if (!empty($_si['about_image'])): ?>
                <div style="margin-bottom:8px;">
                    <img src="../<?= si('about_image') ?>" style="max-width:200px;border-radius:6px;border:1px solid #dde3ea;">
                </div>
            <?php endif; ?>
            <input type="file" id="about_image_file" accept="image/jpeg,image/png,image/webp">
            <small style="color:#aaa;">Leave empty to keep current image.</small>
        </div>

    </div>
</div>









 
<!-- ── Privacy Notice ── -->
<div class="si-section">
    <h4>🔒 Privacy Notice</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Introduction</label>
            <textarea id="privacy_intro" rows="3"><?= si('privacy_intro') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Information We Collect</label>
            <textarea id="privacy_collection" rows="2"><?= si('privacy_collection') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>How We Use Your Information</label>
            <textarea id="privacy_use" rows="2"><?= si('privacy_use') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Your Rights</label>
            <textarea id="privacy_rights" rows="2"><?= si('privacy_rights') ?></textarea>
        </div>
        <div class="si-field">
            <label>Contact Info</label>
            <input type="text" id="privacy_contact" value="<?= si('privacy_contact') ?>">
        </div>
        <div class="si-field">
            <label>Last Updated</label>
            <input type="date" id="privacy_updated" value="<?= si('privacy_updated') ?>">
        </div>
    </div>
</div>
 
<!-- ── Terms & Conditions ── -->
<div class="si-section">
    <h4>📄 Terms & Conditions</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Introduction</label>
            <textarea id="terms_intro" rows="2"><?= si('terms_intro') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Services</label>
            <textarea id="terms_services" rows="2"><?= si('terms_services') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Liability</label>
            <textarea id="terms_liability" rows="2"><?= si('terms_liability') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Payment</label>
            <textarea id="terms_payment" rows="2"><?= si('terms_payment') ?></textarea>
        </div>
        <div class="si-field">
            <label>Last Updated</label>
            <input type="date" id="terms_updated" value="<?= si('terms_updated') ?>">
        </div>
    </div>
</div>
 
<!-- ── Patient Rights ── -->
<div class="si-section">
    <h4>🏥 Patient Rights & Responsibilities</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Introduction</label>
            <textarea id="rights_intro" rows="2"><?= si('rights_intro') ?></textarea>
        </div>
        <div class="si-field">
            <label>Right 1 Title</label>
            <input type="text" id="rights_r1_title" value="<?= si('rights_r1_title') ?>">
        </div>
        <div class="si-field">
            <label>Right 1 Description</label>
            <input type="text" id="rights_r1_desc" value="<?= si('rights_r1_desc') ?>">
        </div>
        <div class="si-field">
            <label>Right 2 Title</label>
            <input type="text" id="rights_r2_title" value="<?= si('rights_r2_title') ?>">
        </div>
        <div class="si-field">
            <label>Right 2 Description</label>
            <input type="text" id="rights_r2_desc" value="<?= si('rights_r2_desc') ?>">
        </div>
        <div class="si-field">
            <label>Right 3 Title</label>
            <input type="text" id="rights_r3_title" value="<?= si('rights_r3_title') ?>">
        </div>
        <div class="si-field">
            <label>Right 3 Description</label>
            <input type="text" id="rights_r3_desc" value="<?= si('rights_r3_desc') ?>">
        </div>
        <div class="si-field">
            <label>Right 4 Title</label>
            <input type="text" id="rights_r4_title" value="<?= si('rights_r4_title') ?>">
        </div>
        <div class="si-field">
            <label>Right 4 Description</label>
            <input type="text" id="rights_r4_desc" value="<?= si('rights_r4_desc') ?>">
        </div>
        <div class="si-field">
            <label>Right 5 Title</label>
            <input type="text" id="rights_r5_title" value="<?= si('rights_r5_title') ?>">
        </div>
        <div class="si-field">
            <label>Right 5 Description</label>
            <input type="text" id="rights_r5_desc" value="<?= si('rights_r5_desc') ?>">
        </div>
        <div class="si-field si-full">
            <label>Responsibilities Text</label>
            <textarea id="rights_resp_intro" rows="2"><?= si('rights_resp_intro') ?></textarea>
        </div>
    </div>
</div>
 
<!-- ── Careers ── -->
<div class="si-section">
    <h4>💼 Careers</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Introduction</label>
            <textarea id="careers_intro" rows="2"><?= si('careers_intro') ?></textarea>
        </div>
        <div class="si-field">
            <label>Why Work With Us — Title</label>
            <input type="text" id="careers_why_title" value="<?= si('careers_why_title') ?>">
        </div>
        <div class="si-field">
            <label>Careers Email</label>
            <input type="text" id="careers_email" value="<?= si('careers_email') ?>">
        </div>
        <div class="si-field si-full">
            <label>Why Work With Us — Description</label>
            <textarea id="careers_why_desc" rows="2"><?= si('careers_why_desc') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>How to Apply Note</label>
            <textarea id="careers_note" rows="2"><?= si('careers_note') ?></textarea>
        </div>
    </div>
</div>
 
<!-- ── Org Chart ── -->
<div class="si-section">
    <h4>🏢 Organizational Chart</h4>
    <div class="si-grid">
        <div class="si-field si-full">
            <label>Introduction</label>
            <textarea id="org_intro" rows="2"><?= si('org_intro') ?></textarea>
        </div>
        <div class="si-field si-full">
            <label>Org Chart Image <span style="color:#aaa;font-weight:400;">(upload to replace the default tree)</span></label>
            <?php if (!empty($_si['org_chart_image'])): ?>
                <div style="margin-bottom:8px;">
                    <img src="<?= si('org_chart_image') ?>" style="max-width:200px;border-radius:6px;border:1px solid #dde3ea;">
                </div>
            <?php endif; ?>
            <input type="file" id="org_chart_image_file" accept="image/jpeg,image/png,image/webp">
            <small style="color:#aaa;">Upload a photo/scan of your org chart. Leave empty to use the default layout.</small>
        </div>
    </div>
</div>













</div>
<button onclick="saveSiteInfo()"
    style="padding:10px 28px;background:#1a3c5e;color:#fff;border:none;border-radius:6px;font-size:0.88rem;font-weight:600;cursor:pointer;">
    💾 Save All Changes
</button>

<script>// showToast FIRST
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

function saveSiteInfo() {
    const fields = [
        'home_tagline','home_subtext',
        'contact_address','contact_phone','contact_email','contact_telephone',
        'social_facebook','whyus_badge_num','whyus_heading','whyus_desc','about_para1','about_para2',
'about_bullet1','about_bullet2','about_bullet3','about_bullet4',
'about_vision','about_mission', 'privacy_intro','privacy_collection','privacy_use','privacy_rights','privacy_contact','privacy_updated',
'terms_intro','terms_services','terms_liability','terms_payment','terms_updated',
'rights_intro','rights_r1_title','rights_r1_desc','rights_r2_title','rights_r2_desc',
'rights_r3_title','rights_r3_desc','rights_r4_title','rights_r4_desc','rights_r5_title','rights_r5_desc','rights_resp_intro',
'careers_intro','careers_why_title','careers_why_desc','careers_email','careers_note',
'org_intro','home_bg_type'
    ];


   


    const fd = new FormData();
    fields.forEach(f => {
        const el = document.getElementById(f);
        if (el) fd.append(f, el.value);
    });

    const orgFile = document.getElementById('org_chart_image_file');
    if (orgFile && orgFile.files[0]) fd.append('org_chart_image_file', orgFile.files[0]);


    const bgFile = document.getElementById('home_bg_image_file');
    if (bgFile && bgFile.files[0]) fd.append('home_bg_image_file', bgFile.files[0]);

    const whyusFile = document.getElementById('whyus_image_file');
    if (whyusFile && whyusFile.files[0]) fd.append('whyus_image_file', whyusFile.files[0]);

    const aboutFile = document.getElementById('about_image_file');
    if (aboutFile && aboutFile.files[0]) fd.append('about_image_file', aboutFile.files[0]);

    const videoFile = document.getElementById('home_bg_video_file');
    if (videoFile && videoFile.files[0]) fd.append('home_bg_video_file', videoFile.files[0]);

    fetch('siteinfo-ajax.php', { method: 'POST', body: fd })
        .then(r => r.text())
        .then(text => {
            console.log('Raw response:', text);
            const res = JSON.parse(text);
            showToast(res.message, res.success);
        })
        .catch(err => showToast('Something went wrong.', false));
}
</script>