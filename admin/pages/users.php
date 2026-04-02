<?php
global $con;
$result = mysqli_query($con, "SELECT * FROM users");
$users  = [];
while ($row = mysqli_fetch_assoc($result)) $users[] = $row;

// Handle delete
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if (mysqli_query($con, "DELETE FROM users WHERE id=$id")) {
        $success = 'User deleted successfully.';
        // Refresh list
        $result = mysqli_query($con, "SELECT * FROM users");
        $users  = [];
        while ($row = mysqli_fetch_assoc($result)) $users[] = $row;
    } else {
        $error = mysqli_error($con);
    }
}
?>

<!-- Notices -->
<?php if ($success): ?>
    <div style="background:#e6f9f0;border-left:4px solid #2ecc71;padding:10px 16px;border-radius:6px;margin-bottom:18px;color:#1a7a4a;">
        ✅ <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div style="background:#fdf0f0;border-left:4px solid #e74c3c;padding:10px 16px;border-radius:6px;margin-bottom:18px;color:#a02020;">
        ❌ <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- Table Header -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <h4 style="font-size:1rem;color:#1a3c5e;">All Users</h4>
    <span style="background:#e8f0f8;color:#1a3c5e;font-size:0.78rem;font-weight:700;padding:3px 12px;border-radius:20px;">
        <?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?>
    </span>
</div>

<!-- Table -->
<?php if (empty($users)): ?>
    <p style="color:#aaa;text-align:center;padding:40px 0;">No users found.</p>
<?php else: ?>
<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr style="background:#1a3c5e;">
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">#</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">ID</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Username</th>
            <th style="padding:11px 14px;text-align:left;font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $i => $u): ?>
        <tr style="border-bottom:1px solid #eef1f5;">
            <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $i + 1 ?></td>
            <td style="padding:11px 14px;font-size:0.85rem;color:#aaa;"><?= $u['id'] ?></td>
            <td style="padding:11px 14px;font-size:0.88rem;font-weight:600;color:#1a3c5e;"><?= htmlspecialchars($u['username']) ?></td>
            <td style="padding:11px 14px;">
                <div style="display:flex;gap:8px;">
                    <a href="edit_user.php?id=<?= $u['id'] ?>"
                        style="padding:6px 14px;background:#e8f4ff;color:#1a6fcc;border:1px solid #b8d8f8;border-radius:5px;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        Edit
                    </a>
                    <form method="POST" action="index.php?page=users"
                        onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($u['username'])) ?>? This cannot be undone.');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                       
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>