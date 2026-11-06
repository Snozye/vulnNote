<?php
require_once __DIR__ . '/../db.php';
// No auth check — any user can access admin panel
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">Admin Panel</h1>
    <div class="admin-grid">
        <div class="admin-menu">
            <a href="index.php">Dashboard</a>
            <a href="ping.php">Network Tools</a>
            <a href="fetch.php">URL Fetcher</a>
        </div>
        <div class="card">
            <h2>Quick stats</h2>
            <?php
            $db = get_db();
            $users = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $posts = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
            ?>
            <p style="margin-top:.8rem">Users: <strong><?= $users ?></strong></p>
            <p>Posts: <strong><?= $posts ?></strong></p>
            <p style="margin-top:.8rem;font-size:.85rem;color:#64748b">
                Server: <?= php_uname() ?><br>
                PHP: <?= phpversion() ?>
            </p>
        </div>
    </div>
</div>
</body>
</html>
