<?php
session_start();
require_once __DIR__ . "/db.php";

$error = "";

if (!empty($_SESSION["verona_admin"])) {
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, username, password_hash
         FROM admins
         WHERE username = ?
         LIMIT 1"
    );

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin["password_hash"])) {
            session_regenerate_id(true);

            $_SESSION["verona_admin"] = true;
            $_SESSION["verona_admin_id"] = (int) $admin["id"];
            $_SESSION["verona_admin_username"] = $admin["username"];

            $stmt->close();

            header("Location: admin_dashboard.php");
            exit;
        }

        $stmt->close();
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | VERONA</title>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #20211f;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 430px;
            padding: 38px;
        }

        .login-logo {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            margin-bottom: 8px;
        }

        .login-logo span {
            color: #c6a56a;
        }

        .login-subtitle {
            color: #777870;
            font-size: 13px;
            margin-bottom: 28px;
        }

        .login-card label {
            display: block;
            margin: 16px 0 7px;
            font-size: 13px;
            font-weight: 600;
        }

        .login-card input {
            width: 100%;
            padding: 13px;
            border: 1px solid #dedbd2;
            font: inherit;
        }

        .login-card button {
            width: 100%;
            margin-top: 23px;
            cursor: pointer;
        }

        .login-error {
            background: #fbe9e7;
            color: #922d24;
            padding: 12px;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .back-link {
            display: block;
            margin-top: 22px;
            text-align: center;
            color: #777870;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">VERONA<span>.</span></div>
    <p class="login-subtitle">Administrator access</p>

    <?php if ($error !== ""): ?>
        <div class="login-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="admin_login.php">
        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit" class="btn btn-gold">Log In</button>
    </form>

    <a class="back-link" href="index.php">← Back to website</a>
</div>
</body>
</html>