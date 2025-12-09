<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$success = "";
$error   = "";
$matric = $name = $role = "";
$original_matric = "";

// first load
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_GET['matric'])) {
        header("Location: users.php");
        exit;
    }

    $original_matric = trim($_GET['matric']);

    $sql  = "SELECT matric, name, role FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $original_matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header("Location: users.php");
        exit;
    }

    $matric = $user['matric'];
    $name   = $user['name'];
    $role   = $user['role'];
}

// submit update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $original_matric = trim($_POST['original_matric'] ?? '');
    $matric          = trim($_POST['matric'] ?? '');
    $name            = trim($_POST['name'] ?? '');
    $role            = trim($_POST['role'] ?? '');

    if ($original_matric === '' || $matric === '' || $name === '' || $role === '') {
        $error = "All fields are required.";
    } else {
        $sql  = "UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $error = "Database error.";
        } else {
            $stmt->bind_param("ssss", $matric, $name, $role, $original_matric);

            if (!$stmt->execute()) {
                if ($stmt->errno == 1062) {
                    $error = "This matric number is already used by another user.";
                } else {
                    $error = "Update failed: " . $stmt->error;
                }
            } else {
                $success = "User details updated successfully.";
                $original_matric = $matric;
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit User | Student Manager</title>
    <style>
        * { box-sizing:border-box; }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            /* SAME background as login */
            background: radial-gradient(circle at top, #1b44ff, #000c2b);
            color:#e5e7eb;
        }

        .card{
            width:480px;
            background:#04133b;
            border-radius:32px;
            padding:40px 48px 32px;
            box-shadow:0 30px 60px rgba(0,0,0,0.6);
        }

        .logo-wrap{
            display:flex;
            justify-content:center;
            margin-bottom:24px;
        }

        .logo-circle{
            width:90px;
            height:90px;
            border-radius:50%;
            background:#ffffff;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 18px 30px rgba(0,0,0,0.35);
        }

        .logo-circle img{
            width:52px;
            height:52px;
            border-radius:50%;
        }

        h2{
            margin:4px 0 4px;
            text-align:center;
            font-size:30px;
            color:#f9fafb;
        }

        .subtitle{
            text-align:center;
            margin:0 0 20px;
            font-size:14px;
            color:#9ca3af;
        }

        label{
            display:block;
            margin-top:12px;
            margin-bottom:4px;
            font-size:14px;
        }

        input,select{
            width:100%;
            padding:11px 12px;
            border-radius:12px;
            border:none;
            background:#e5edff;
            font-size:15px;
            outline:none;
            color:#111827;
        }

        input:focus,select:focus{
            box-shadow:0 0 0 2px rgba(59,130,246,0.7);
        }

        .btn{
            width:100%;
            margin-top:22px;
            padding:13px;
            border-radius:999px;
            border:none;
            font-size:16px;
            font-weight:bold;
            background:#22c55e;
            color:white;
            cursor:pointer;
            box-shadow:0 0 26px rgba(34,197,94,0.85);
        }

        .btn:hover{
            transform:translateY(-1px);
            box-shadow:0 0 34px rgba(34,197,94,1);
        }

        .message{
            margin-top:10px;
            padding:9px 12px;
            border-radius:10px;
            font-size:14px;
        }

        .success{
            background:#14532d;
            border:1px solid #4ade80;
            color:#bbf7d0;
        }

        .error{
            background:#b91c1c;
            border:1px solid #fecaca;
            color:#fee2e2;
        }

        .back-link{
            margin-top:14px;
            text-align:center;
            font-size:14px;
        }

        .back-link a{
            color:#ffffff;
            text-decoration:none;
            font-weight:bold;
        }

        .back-link a:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<div class="card">

    <!-- Edit user icon -->
    <div class="logo-wrap">
        <div class="logo-circle">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828911.png" alt="Edit Logo">
        </div>
    </div>

    <h2>Edit User</h2>
    <p class="subtitle">Update matric, name or role, then save changes.</p>

    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="original_matric" value="<?php echo htmlspecialchars($original_matric); ?>">

        <label>Matric No</label>
        <input type="text" name="matric" value="<?php echo htmlspecialchars($matric); ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">

        <label>Role</label>
        <select name="role">
            <option value="student"  <?php if ($role==='student')  echo 'selected'; ?>>student</option>
            <option value="lecturer" <?php if ($role==='lecturer') echo 'selected'; ?>>lecturer</option>
        </select>

        <button type="submit" class="btn">Save Changes</button>
    </form>

    <div class="back-link">
        <a href="users.php">← Back to User List</a>
    </div>
</div>

</body>
</html>
