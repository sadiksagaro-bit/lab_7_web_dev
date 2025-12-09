<?php
// Show PHP errors (helpful during lab)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';   // must contain $conn

$error = "";

// handle login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric   = trim($_POST['matric'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($matric === '' || $password === '') {
        $error = "Please enter matric number and password.";
    } else {
        $sql  = "SELECT * FROM users WHERE matric = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $error = "Database error.";
        } else {
            $stmt->bind_param("ss", $matric, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // login success
                $_SESSION['matric'] = $user['matric'];
                $_SESSION['name']   = $user['name'];
                $_SESSION['role']   = $user['role'];

                header("Location: users.php");
                exit;
            } else {
                // login fail
                $error = "Incorrect login details. Please try again.";
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
    <title>Login | Student Manager</title>
    <style>
        * { box-sizing: border-box; }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
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

        input{
            width:100%;
            padding:11px 12px;
            border-radius:12px;
            border:none;
            background:#e5edff;
            font-size:15px;
            outline:none;
            color:#111827;
        }

        input:focus{
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
            background:linear-gradient(135deg,#4f8bff,#2f62ff);
            color:white;
            cursor:pointer;
            box-shadow:0 0 26px rgba(79,139,255,0.85);
        }

        .btn:hover{
            transform:translateY(-1px);
            box-shadow:0 0 34px rgba(79,139,255,1);
        }

        .error{
            background:#b91c1c;
            color:#fee2e2;
            padding:9px 12px;
            border-radius:10px;
            margin-bottom:12px;
            font-size:14px;
            border:1px solid #fecaca;
            text-align:center;
        }

        .switch{
            margin-top:14px;
            text-align:center;
            font-size:14px;
            color:#9ca3af;
        }

        .switch a{
            color:#ffffff;
            font-weight:bold;
            text-decoration:none;
        }

        .switch a:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<div class="card">

    <!-- round logo with user icon -->
    <div class="logo-wrap">
        <div class="logo-circle">
            <img src="https://cdn-icons-png.flaticon.com/512/456/456212.png" alt="User Logo">
        </div>
    </div>

    <h2>Welcome Back</h2>
    <p class="subtitle">Please log in to continue.</p>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Matric No</label>
        <input type="text" name="matric" value="<?php echo isset($_POST['matric']) ? htmlspecialchars($_POST['matric']) : ''; ?>">

        <label>Password</label>
        <input type="password" name="password">

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="switch">
        Don’t have an account?
        <a href="register.php">Create one</a>
    </div>
</div>

</body>
</html>
