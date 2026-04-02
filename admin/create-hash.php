<?php
include './includes/session.php';
?>
<?php
// Change "yourpassword" to the actual password you want
echo password_hash("0000", PASSWORD_DEFAULT);
?>