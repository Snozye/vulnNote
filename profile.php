<?php
require_once __DIR__ . '/db.php';
$db = get_db();

// VULN #8: IDOR — no authorization check, any user can view any profile by id
// Payload: profile.php?id=1 (view admin profile)
$id = $_GET['id'] ?? 1;
$user = $db->query("SELECT * FROM users WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$user) { http_response_code(404); die('User not found'); }

// VULN #9: Insecure Deserialization — theme preference stored as serialized PHP object in cookie
// Payload: craft a serialized object with a __destruct magic method for RCE
// e.g.: O:4:"Exec":1:{s:3:"cmd";s:2:"id";}
class ThemePrefs {
    public string $theme = 'light';
    public string $font  = 'sans-serif';
    public function __destruct() {
        // Simulates a gadget chain: logs preference on destruct
        @file_put_contents('/tmp/theme.log', $this->theme . "\n", FILE_APPEND);
    }
}

$prefs = new ThemePrefs();
if (isset($_COOKIE['prefs'])) {
    // Deserializing user-controlled cookie data
    $prefs = unserialize(base64_decode($_COOKIE['prefs']));
}

$posts = $db->query("SELECT * FROM posts WHERE user_id = $id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?> · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <div class="card">
        <div class="profile-box">
            <div class="avatar"><?= strtoupper($user['username'][0]) ?></div>
            <div>
                <h2><?= htmlspecialchars($user['username']) ?></h2>
                <div class="meta"><?= htmlspecialchars($user['email']) ?> · Role: <?= htmlspecialchars($user['role']) ?></div>
                <p style="margin-top:.4rem"><?= htmlspecialchars($user['bio'] ?? 'No bio yet.') ?></p>
            </div>
        </div>
    </div>

    <h2 style="margin-bottom:.8rem">Notes by <?= htmlspecialchars($user['username']) ?></h2>
    <?php foreach ($posts as $post): ?>
    <div class="card">
        <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
        <div class="meta"><?= $post['created_at'] ?></div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
