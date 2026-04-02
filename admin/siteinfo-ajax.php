<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$allowed_keys = [
    'home_tagline','home_subtext','home_btn_text','home_btn_link',
    'home_bg_image',
    'contact_address','contact_phone','contact_email','contact_telephone','contact_hours',
    'social_facebook','social_instagram','social_twitter','social_youtube',
    'whyus_badge_num','whyus_heading','whyus_desc',
    'about_para1','about_para2',
    'about_bullet1','about_bullet2','about_bullet3','about_bullet4',
    'about_vision','about_mission','about_image', 'privacy_intro','privacy_collection','privacy_use','privacy_rights','privacy_contact','privacy_updated',
    'terms_intro','terms_services','terms_liability','terms_payment','terms_updated',
    'rights_intro','rights_r1_title','rights_r1_desc','rights_r2_title','rights_r2_desc',
    'rights_r3_title','rights_r3_desc','rights_r4_title','rights_r4_desc','rights_r5_title','rights_r5_desc','rights_resp_intro',
    'careers_intro','careers_why_title','careers_why_desc','careers_email','careers_note',
    'org_intro','org_chart_image', 'home_bg_type', 'home_bg_video'
];

$errors = 0;

// Handle text fields from $_POST
foreach ($_POST as $key => $value) {
    if (!in_array($key, $allowed_keys)) continue;
    $k = mysqli_real_escape_string($con, $key);
    $v = mysqli_real_escape_string($con, trim($value));
    $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('$k','$v')
            ON DUPLICATE KEY UPDATE value='$v'";
    if (!mysqli_query($con, $sql)) $errors++;
}

// Handle home background image upload
if (!empty($_FILES['home_bg_image_file']['name'])) {
    $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/jpg'
    ];
    $file    = $_FILES['home_bg_image_file'];

    if (in_array(strtolower($file['type']), $allowed)) {
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dest = 'admin/images/home/home_bg.' . $ext;

        if (move_uploaded_file($file['tmp_name'], '../' . $dest)) {
            $v   = mysqli_real_escape_string($con, $dest);
            $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('home_bg_image','$v')
                    ON DUPLICATE KEY UPDATE value='$v'";
            if (!mysqli_query($con, $sql)) $errors++;
        } else {
            $errors++;
        }
    }
}





// ── 4. ADD video upload block in siteinfo-ajax.php ──
if (!empty($_FILES['home_bg_video_file']['name'])) {
    $allowed_vid = ['video/mp4','video/webm','video/ogg'];
    $file        = $_FILES['home_bg_video_file'];
    if (in_array(strtolower($file['type']), $allowed_vid)) {
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dest = 'admin/images/home/home_bg_video.' . $ext;
        if (move_uploaded_file($file['tmp_name'], '../' . $dest)) {
            $v   = mysqli_real_escape_string($con, $dest);
            $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('home_bg_video','$v')
                    ON DUPLICATE KEY UPDATE value='$v'";
            if (!mysqli_query($con, $sql)) $errors++;
        } else {
            $errors++;
        }
    }
}
 







// Handle whyus image upload
if (!empty($_FILES['whyus_image_file']['name'])) {
    $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
    $file    = $_FILES['whyus_image_file'];

    if (in_array(strtolower($file['type']), $allowed)) {
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dest = 'admin/images/why/whyus_bg.' . $ext;

        if (move_uploaded_file($file['tmp_name'], '../' . $dest)) {
            $v   = mysqli_real_escape_string($con, $dest);
            $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('whyus_image','$v')
                    ON DUPLICATE KEY UPDATE value='$v'";
            if (!mysqli_query($con, $sql)) $errors++;
        } else {
            $errors++;
        }
    }
}

// Handle about image upload
if (!empty($_FILES['about_image_file']['name'])) {
    $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
    $file    = $_FILES['about_image_file'];
    if (in_array(strtolower($file['type']), $allowed)) {
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dest = 'admin/images/about/about_img.' . $ext;
        if (move_uploaded_file($file['tmp_name'], '../' . $dest)) {
            $v   = mysqli_real_escape_string($con, $dest);
            $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('about_image','$v')
                    ON DUPLICATE KEY UPDATE value='$v'";
            if (!mysqli_query($con, $sql)) $errors++;
        } else {
            $errors++;
        }
    }
}



if (!empty($_FILES['org_chart_image_file']['name'])) {
    $allowed = ['image/jpeg','image/png','image/webp','image/jpg'];
    $file    = $_FILES['org_chart_image_file'];
    if (in_array(strtolower($file['type']), $allowed)) {
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dest = 'images/org_chart.' . $ext;
        if (move_uploaded_file($file['tmp_name'], '../' . $dest)) {
            $v   = mysqli_real_escape_string($con, $dest);
            $sql = "INSERT INTO siteinfo (key_name, value) VALUES ('org_chart_image','$v')
                    ON DUPLICATE KEY UPDATE value='$v'";
            if (!mysqli_query($con, $sql)) $errors++;
        }
    }
}
 







echo $errors === 0
    ? json_encode(['success' => true,  'message' => 'All changes saved successfully!'])
    : json_encode(['success' => false, 'message' => 'Some fields failed to save.']);