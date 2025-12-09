<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';
$result = $conn->query("SELECT matric, name, role FROM users ORDER BY matric");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User List | Student Manager</title>
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
            width:900px;
            max-height:80vh;
            background:#04133b;
            border-radius:32px;
            padding:30px 36px 26px;
            box-shadow:0 30px 60px rgba(0,0,0,0.6);
            overflow:auto;
        }

        .card-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:18px;
        }

        .title-left{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .logo-circle{
            width:70px;
            height:70px;
            border-radius:50%;
            background:#ffffff;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 18px 30px rgba(0,0,0,0.35);
        }

        .logo-circle img{
            width:42px;
            height:42px;
            border-radius:50%;
        }

        .card-header h2{
            margin:0;
            font-size:24px;
            color:#f9fafb;
        }

        .card-header p{
            margin:2px 0 0;
            font-size:13px;
            color:#9ca3af;
        }

        .logout{
            text-decoration:none;
            padding:9px 16px;
            border-radius:999px;
            border:none;
            background:#f97316;
            color:#ffffff;
            font-size:13px;
            font-weight:bold;
            box-shadow:0 0 24px rgba(248,113,113,0.8);
        }

        .logout:hover{
            filter:brightness(1.05);
        }

        table{
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            margin-top:6px;
            background:#020826;
            border-radius:16px;
            overflow:hidden;
        }

        thead{
            background:#1d4ed8;
            color:#f9fafb;
        }

        th,td{
            padding:10px 12px;
            text-align:center;
        }

        tbody tr:nth-child(even){
            background:#04133b;
        }

        tbody tr:nth-child(odd){
            background:#020826;
        }

        tbody tr:hover{
            background:#0b1b4a;
        }

        .btn{
            display:inline-block;
            padding:5px 10px;
            border-radius:999px;
            font-size:12px;
            text-decoration:none;
            color:#ffffff;
        }

        .edit{
            background:#22c55e;
            box-shadow:0 0 16px rgba(34,197,94,0.7);
        }

        .edit:hover{ filter:brightness(1.08); }

        .delete{
            background:#ef4444;
            box-shadow:0 0 16px rgba(248,113,113,0.7);
        }

        .delete:hover{ filter:brightness(1.08); }
    </style>
</head>
<body>

<div class="card">

    <div class="card-header">
        <div class="title-left">
            <div class="logo-circle">
                <!-- group/users icon -->
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Users Logo">
            </div>
            <div>
                <h2>User List</h2>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
            </div>
        </div>

        <a href="logout.php" class="logout">Logout</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['matric']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td>
                    <a class="btn edit" href="edit_user.php?matric=<?php echo urlencode($row['matric']); ?>">Edit</a>
                    <a class="btn delete"
                       href="delete_user.php?matric=<?php echo urlencode($row['matric']); ?>"
                       onclick="return confirm('Are you sure you want to delete this user?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
