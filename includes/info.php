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

// ── Home ──
$raw_tagline  = info_raw('home_tagline', 'Covenant to Heal, Commitment to Care.');
$home_tagline = preg_replace_callback('/(?:^|,\s*)(\w+)/', function($m) {
    return str_replace($m[1], '<span>' . $m[1] . '</span>', $m[0]);
}, $raw_tagline);$home_subtext   = info('home_subtext',      'Serving our community with excellence.');
$home_bg_image = info('home_bg_image', 'images/home.jpg');
$home_bg_type  = info('home_bg_type',  'image');
$home_bg_video = info('home_bg_video', '');
// ── Contact ──
$contact_address = info('contact_address', 'Zamboanga City, Philippines');
$contact_phone   = info('contact_phone',   '+63 62 123 4567');
$contact_email   = info('contact_email',   'zdh1964@yahoo.com');
$contact_telephone = info('contact_telephone', '');


// ── Social ──
$social_facebook  = info('social_facebook',  '#');
$social_instagram = info('social_instagram', '#');
$social_twitter   = info('social_twitter',   '#');
$social_youtube   = info('social_youtube',   '#');

// ── Why Us ──
$whyus_badge_num = info('whyus_badge_num', '50+');
$whyus_image = info('whyus_image', '');
$raw_whyus_heading = info_raw('whyus_heading', 'Your Health Is Our, Highest Priority');
$whyus_heading     = preg_replace_callback('/([^,]+)(?:,\s*)?/', function($m) {
    return preg_replace('/(\w+)/', '<span>$1</span>', trim($m[1]), 1) . (strpos($m[0], ',') !== false ? '<br>' : '');
}, $raw_whyus_heading);
$whyus_desc      = info('whyus_desc', 'At Zamboanga Doctors\' Hospital...');

// ── About ──
$about_para1   = info('about_para1',   '');
$about_para2   = info('about_para2',   '');
$about_bullet1 = info('about_bullet1', 'Quality healthcare services');
$about_bullet2 = info('about_bullet2', 'Compassionate patient care');
$about_bullet3 = info('about_bullet3', 'Professional and skilled staff');
$about_bullet4 = info('about_bullet4', 'Modern facilities and equipment');
$about_vision  = info('about_vision',  '');
$about_mission = info('about_mission', '');
$about_image   = info('about_image',   'images/aboutus.JPG');





?>