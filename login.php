<?php
require_once __DIR__ . '/db.php';

// VULN #1: SQL Injection — username/password concatenated directly into query
// Payload: admin' -- (bypasses password check)
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = get_db();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $user = $db->query($query)->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
<div class="form-wrap">
    <h1>Sign in</h1>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" autocomplete="off" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn btn-primary" style="width:100%">Sign in</button>
    </form>
    <p style="margin-top:1rem;font-size:.9rem">No account? <a href="register.php">Register</a></p>
</div>
</div>
</body>
</html>
