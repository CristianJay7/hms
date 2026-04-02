<?php

session_start();
include('./includes/config.php');

// ── Rate limiting ──────────────────────────────────────────────────────────
define('MAX_ATTEMPTS', 5);
define('LOCKOUT_TIME', 15 * 60); // 15 minutes

if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
if (!isset($_SESSION['locked_until']))   $_SESSION['locked_until']   = 0;

$now    = time();
$locked = ($now < $_SESSION['locked_until']);
$error  = '';

// Reset after lockout expires
if ($_SESSION['locked_until'] > 0 && $now >= $_SESSION['locked_until']) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['locked_until']   = 0;
    $locked = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {

    $username = strtolower(trim($_POST['username'] ?? ''));
        $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Please enter both email and password.';

    } else {
        $stmt = $con->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {

                if ($id === 1) { 
                    // ✅ Success
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['locked_until']   = 0;
                    $_SESSION['user_id']         = $id;
                    $_SESSION['username']        = $db_username;
                    $_SESSION['login_time']      = $now;
                    header("Location: admin/index.php");
                    exit();
                } else {
                    $error = 'Access denied. You do not have admin privileges.';
                    $_SESSION['login_attempts']++;
                }

            } else {
                $error = 'Incorrect email or password.';
                $_SESSION['login_attempts']++;
            }
        } else {
            $error = 'Incorrect email or password.';
            $_SESSION['login_attempts']++;
        }

        $stmt->close();

        // Check if now locked
        if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
            $_SESSION['locked_until'] = $now + LOCKOUT_TIME;
            $locked = true;
            $error  = 'Too many failed attempts. You are locked out for 15 minutes.';
        }
    }
}

$attempts_left     = max(0, MAX_ATTEMPTS - $_SESSION['login_attempts']);
$lockout_remaining = max(0, $_SESSION['locked_until'] - time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<?php include 'includes/favicon.php'; ?>
<style>

/* Reset */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* Background Image */
body{
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('./images/home.jpg') no-repeat center center/cover;
    
}

/* Dark overlay for better contrast*/
body::before{
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0, 60, 30, 0.5);
    backdrop-filter: blur(2px);
}
 
/* Glass Login Container */
.login-container{
    position: relative;
    width: 380px;
    padding: 40px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    text-align: center;
    color: #fff;
}

/* Title */
.login-container h2{
    margin-bottom: 25px;
    font-size: 28px;
    letter-spacing: 1px;
}

/* Input Fields */
.input-group{
    margin-bottom: 20px;
    text-align: left;
}

.input-group label{
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

.input-group input{
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 14px;
    border: 1px solid rgba(255,255,255,0.3);
    text-transform: lowercase;
}

.input-group input::placeholder{
    color: #e0e0e0;
}

/* Button */
button{
    width: 100%;
    padding: 12px;
    border-radius: 25px;
    border: none;
    background: #2ecc71;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover{
    background: #27ae60;
    transform: translateY(-2px);
}

button:active{
    transform: scale(0.97);
}

button:disabled {
    background: #888;
    cursor: not-allowed;
    transform: none;
}

/* Extra Links */
.extra{
    margin-top: 15px;
    font-size: 14px;
}

.extra a{
    color: #a8ffcb;
    text-decoration: none;
    transition: 0.3s;
}

.extra a:hover{
    text-decoration: underline;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed; 
    z-index: 9999; 
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto; 
    background-color: rgba(0,0,0,0.6);
}
.modal-content {
    background-color: #f44336;
    margin: 15% auto; 
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 400px;
    color: white;
    text-align: center;
    font-weight: bold;
}
.modal-content.locked {
    background-color: #c0392b;
}
.close-btn {
    margin-top: 15px;
    padding: 8px 16px;
    background: white;
    color: #f44336;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: auto;
}
.attempts-note {
    font-size: 13px;
    font-weight: normal;
    margin-top: 8px;
    opacity: 0.9;
}
.countdown {
    font-size: 22px;
    margin-top: 10px;
    letter-spacing: 2px;
}
</style>
</head>

<body>
<form action="" method="POST">
    <div class="login-container">
        <h2>Login</h2>

        <div class="input-group">
            <label>Email</label>
            <input type="text" name="username" placeholder="Enter your email"
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                <?= $locked ? 'disabled' : '' ?> required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password"
                <?= $locked ? 'disabled' : '' ?> required>
        </div>

        <button type="submit" <?= $locked ? 'disabled' : '' ?>>
            <?= $locked ? 'Locked Out' : 'Login' ?>
        </button>
    </div>
</form>

<!-- Error / Lockout Modal -->
<div id="errorModal" class="modal">
    <div class="modal-content <?= $locked ? 'locked' : '' ?>">

        <?php if ($locked): ?>
            <p>🔒 Too many failed login attempts.</p>
            <p class="attempts-note">Please wait before trying again.</p>
            <div class="countdown" id="countdown"><?= gmdate('i:s', $lockout_remaining) ?></div>
        <?php else: ?>
            <p><?= htmlspecialchars($error) ?></p>
            <?php if ($_SESSION['login_attempts'] >= 2 && $_SESSION['login_attempts'] < MAX_ATTEMPTS): ?>
                <p class="attempts-note">⚠️ <?= $attempts_left ?> attempt<?= $attempts_left !== 1 ? 's' : '' ?> left before lockout.</p>
            <?php endif; ?>
        <?php endif; ?>

        <button class="close-btn" onclick="document.getElementById('errorModal').style.display='none'">Close</button>
    </div>
</div>

<script>
<?php if (!empty($error) || $locked): ?>
    document.getElementById('errorModal').style.display = 'block';
<?php endif; ?>

<?php if ($locked && $lockout_remaining > 0): ?>
let remaining = <?= $lockout_remaining ?>;
const countdown = document.getElementById('countdown');
const timer = setInterval(() => {
    remaining--;
    if (remaining <= 0) {
        clearInterval(timer);
        location.reload();
        return;
    }
    const m = String(Math.floor(remaining / 60)).padStart(2, '0');
    const s = String(remaining % 60).padStart(2, '0');
    countdown.textContent = m + ':' + s;
}, 1000);
<?php endif; ?>

// Prevent form resubmit on refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>
</body>
</html>