<?php
require_once __DIR__ . '/db.php';

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = get_db();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email    = trim($_POST['email'] ?? '');

    if (!$username || !$password) {
        $error = 'Username and password are required.';
    } else {
        try {
            // vuln: password stored in plaintext
            $db->exec("INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')");
            $success = 'Account created. <a href="login.php">Sign in</a>';
        } catch (Exception $e) {
            $error = 'Username already taken.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
<div class="form-wrap">
    <h1>Create account</h1>
    <?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Email</label>
        <input type="email" name="email">
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn btn-primary" style="width:100%">Create account</button>
    </form>
</div>
</div>
</body>
</html>
