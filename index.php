<?php
require_once __DIR__ . '/db.php';
$db = get_db();
$posts = $db->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">Recent Notes</h1>

    <form class="search-bar" action="search.php" method="get">
        <input type="text" name="q" placeholder="Search notes...">
        <button class="btn btn-primary">Search</button>
    </form>

    <?php foreach ($posts as $post): ?>
    <div class="card">
        <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
        <div class="meta">By <?= htmlspecialchars($post['username']) ?> · <?= $post['created_at'] ?></div>
        <p><?= htmlspecialchars(substr($post['body'], 0, 180)) ?>...</p>
    </div>
    <?php endforeach; ?>
</div>
<footer>&copy; 2024 <?= APP_NAME ?> v<?= APP_VERSION ?></footer>
</body>
</html>
