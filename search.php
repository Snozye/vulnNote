<?php
require_once __DIR__ . '/db.php';
$db = get_db();

$q = $_GET['q'] ?? '';
$results = [];

if ($q) {
    // VULN #4: Reflected XSS — $q echoed raw in results heading
    // Payload: <img src=x onerror=alert(document.cookie)>
    // Also SQLi in the LIKE clause
    $results = $db->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.title LIKE '%$q%' OR p.body LIKE '%$q%'")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">Search</h1>
    <form class="search-bar" method="get">
        <input type="text" name="q" value="<?= $q ?>" placeholder="Search notes...">
        <button class="btn btn-primary">Search</button>
    </form>

    <?php if ($q): ?>
        <!-- vuln: $q echoed without htmlspecialchars -->
        <p style="margin-bottom:1rem;color:#64748b">Results for: <strong><?= $q ?></strong></p>
        <?php if (empty($results)): ?>
            <div class="card"><p>No results found.</p></div>
        <?php else: ?>
            <?php foreach ($results as $post): ?>
            <div class="card">
                <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                <div class="meta">By <?= htmlspecialchars($post['username']) ?> · <?= $post['created_at'] ?></div>
                <p><?= htmlspecialchars(substr($post['body'], 0, 200)) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
