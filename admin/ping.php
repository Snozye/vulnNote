<?php
require_once __DIR__ . '/../db.php';

// VULN #10: Command Injection — host parameter passed directly to shell
// Payload: 127.0.0.1; cat /etc/passwd
//          127.0.0.1 && id
//          $(whoami)
$output = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    if ($host) {
        // No sanitization — OS command injection
        $output = shell_exec("ping -c 4 " . $host . " 2>&1");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Tools · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">Network Tools</h1>
    <div class="admin-grid">
        <div class="admin-menu">
            <a href="index.php">Dashboard</a>
            <a href="ping.php">Network Tools</a>
            <a href="fetch.php">URL Fetcher</a>
        </div>
        <div>
            <div class="card">
                <h2>Ping host</h2>
                <form method="post" style="margin-top:.8rem">
                    <label>Hostname or IP</label>
                    <input type="text" name="host" value="<?= htmlspecialchars($_POST['host'] ?? '') ?>" placeholder="e.g. 8.8.8.8">
                    <button class="btn btn-primary">Ping</button>
                </form>
            </div>
            <?php if ($output): ?>
            <div class="card">
                <h2>Output</h2>
                <pre><?= htmlspecialchars($output) ?></pre>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
