<?php
include('./includes/session.php');
include('./includes/config.php');
/* DELETE USER */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $con->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}

/* UPDATE USER */
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare("UPDATE users SET username=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $hashed, $id);
    } else {
        $stmt = $con->prepare("UPDATE users SET username=? WHERE id=?");
        $stmt->bind_param("si", $username, $id);
    }

    $stmt->execute();
    header("Location: users.php");
    exit();
}

/* GET USERS */
$result = mysqli_query($con, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<?php include 'includes/favicon.php'; ?>
<style>
:root{
    --blue:#00b6bd;
    --black:#394044;
    --white:#fff;
}

/* FULL RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

html, body{
    height:100%;
    width:100%;
}

/* MAIN WRAPPER */
.wrapper{
    display:flex;
    height:100vh;
    width:100%;
}

/* SIDEBAR */
.sidebar{
    width:250px;
    background:var(--black);
    color:var(--white);
    display:flex;
    flex-direction:column;
    padding:30px 20px;
}

.sidebar h2{
    color:var(--blue);
    margin-bottom:40px;
}

.sidebar a{
    color:var(--white);
    text-decoration:none;
    padding:12px 15px;
    border-radius:8px;
    margin-bottom:10px;
    transition:0.3s;
}

.sidebar a:hover{
    background:var(--blue);
}

/* MAIN AREA */
.main{
    flex:1;
    display:flex;
    flex-direction:column;
    background:#f4f6f8;
}

/* TOPBAR */
.topbar{
    background:var(--white);
    padding:20px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #eee;
}

.logout{
    background:var(--blue);
    color:var(--white);
    border:none;
    padding:8px 18px;
    border-radius:20px;
    cursor:pointer;
}

/* CONTENT AREA */
.content{
    flex:1;
    padding:30px;
    overflow-y:auto;
}

/* CARD */
.card{
    background:var(--white);
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
    width:100%;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

thead{
    background:var(--blue);
    color:var(--white);
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

tbody tr:hover{
    background:#f9f9f9;
}

</style>
</head>

<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="users.php">Users</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Manage Users</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td>
                        <!-- EDIT FORM -->
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <input type="text" name="username" placeholder="New username" required>
                            <input type="password" name="password" placeholder="New password (optional)">
                            <button type="submit" name="update" class="edit-btn">Update</button>
                        </form>

                        <!-- DELETE -->
                        <a href="users.php?delete=<?= $row['id']; ?>" 
                           onclick="return confirm('Delete this user?')">
                            <button class="delete-btn">Delete</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    </div>
</div>

</body>
</html>