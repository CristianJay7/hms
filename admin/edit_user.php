<?php
include('./includes/config.php');
include('./includes/session.php');
global $con;

$message = '';
$success = false;

if (!isset($_GET['id'])) {
    header('Location: index.php?page=users');
    exit;
}

$user_id = intval($_GET['id']);
$query   = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
$user    = mysqli_fetch_assoc($query);

if (!$user) {
    header('Location: index.php?page=users');
    exit;
}

// Handle form submission
if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $password      = password_hash($password, PASSWORD_DEFAULT);
        $update_query  = mysqli_query($con, "UPDATE users SET username='$username', password='$password' WHERE id='$user_id'");
    } else {
        $update_query  = mysqli_query($con, "UPDATE users SET username='$username' WHERE id='$user_id'");
    }

    if ($update_query) {
        $message = 'User updated successfully!';
        $success = true;
        // Refresh user data
        $query = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
        $user  = mysqli_fetch_assoc($query);
    } else {
        $message = 'Error updating user: ' . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User — Admin</title>
    <?php include 'includes/favicon.php'; ?>
    <style>
        :root {
            --blue: #00b6bd;
            --black: #394044;
            --white: #fff;
        }

        * { margin:0; padding:0; box-sizing:border-box; font-family: Segoe UI, sans-serif; }

        body { background: #f4f6f8; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Top bar ── */
        .topbar {
            background: white;
            padding: 18px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 16px;
            background: #e8f0f8; color: #1a3c5e;
            border-radius: 6px; text-decoration: none;
            font-size: 0.85rem; font-weight: 600;
            transition: background 0.2s;
        }
        .back-btn:hover { background: #d0e4f5; }
        .topbar h3 { font-size: 1rem; color: #1a3c5e; font-weight: 600; }

        /* ── Page body ── */
        .page-body {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
        }

        /* ── Card ── */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
            width: 100%;
            max-width: 520px;
            overflow: hidden;
        }

        .card-header {
            background: #1a3c5e;
            padding: 22px 28px;
        }
        .card-header h2 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .card-header p {
            color: rgba(255,255,255,0.5);
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .card-body { padding: 28px; }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #dde3ea;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: inherit;
            color: #333;
            outline: none;
            transition: border 0.2s;
        }
        .form-group input:focus { border-color: var(--blue); }
        .form-group .hint {
            font-size: 0.76rem;
            color: #aaa;
            margin-top: 5px;
        }

        .form-actions { display: flex; gap: 10px; margin-top: 8px; }
        .btn-save {
            flex: 1; padding: 11px;
            background: #1a3c5e; color: white;
            border: none; border-radius: 6px;
            font-size: 0.9rem; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-save:hover { background: #244e7c; }
        .btn-cancel {
            padding: 11px 20px;
            background: #e8edf2; color: #555;
            border: none; border-radius: 6px;
            font-size: 0.9rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center;
        }
        .btn-cancel:hover { background: #d8dfe8; }

        /* ── Notice ── */
        .notice {
            padding: 11px 16px;
            border-radius: 6px;
            font-size: 0.88rem;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .notice.success { background: #e6f9f0; border-left: 4px solid #2ecc71; color: #1a7a4a; }
        .notice.error   { background: #fdf0f0; border-left: 4px solid #e74c3c; color: #a02020; }
    </style>
</head>
<body>

<!-- Top bar -->
<div class="topbar">
    <div class="topbar-left">
        <a href="index.php?page=users" class="back-btn">← Back</a>
        <h3>Edit User</h3>
    </div>
    <div style="font-size:0.85rem;color:#778899;">
        Hello, <?= htmlspecialchars($_SESSION['username']) ?>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="card">

        <div class="card-header">
            <h2>✏️ Edit User #<?= $user_id ?></h2>
            <p>Update username or change password below.</p>
        </div>

        <div class="card-body">

            <?php if (!empty($message)): ?>
                <div class="notice <?= $success ? 'success' : 'error' ?>">
                    <?= $success ? '✅' : '❌' ?> <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"
                        value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Leave blank to keep current password">
                    <span class="hint">Only fill this in if you want to change the password.</span>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update" class="btn-save">Save Changes</button>
                    <a href="index.php?page=users" class="btn-cancel">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>