<?php
require_once __DIR__ . '/../db.php';

// VULN #11: SSRF — arbitrary URL fetched server-side, no allowlist
// Payloads:
//   http://169.254.169.254/latest/meta-data/  (AWS metadata)
//   http://localhost:8080/admin/index.php      (internal service)
//   file:///etc/passwd                         (local file via file://)
//   http://internal-db:5432/                   (port scan)
$content = $status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
    $url = $_POST['url'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // No SSRF protection — private IPs and file:// allowed
    $content = curl_exec($ch);
    $status  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Fetcher · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">URL Fetcher</h1>
    <div class="admin-grid">
        <div class="admin-menu">
            <a href="index.php">Dashboard</a>
            <a href="ping.php">Network Tools</a>
            <a href="fetch.php">URL Fetcher</a>
        </div>
        <div>
            <div class="card">
                <h2>Fetch URL</h2>
                <p style="font-size:.85rem;color:#64748b;margin-bottom:.8rem">
                    Fetches the content of any URL server-side. Useful for previewing external resources.
                </p>
                <form method="post">
                    <label>URL</label>
                    <input type="url" name="url" value="<?= htmlspecialchars($_POST['url'] ?? '') ?>" placeholder="https://example.com">
                    <button class="btn btn-primary">Fetch</button>
                </form>
            </div>
            <?php if ($content !== ''): ?>
            <div class="card">
                <h2>Response <?= $status ? "($status)" : '' ?></h2>
                <pre style="max-height:400px;overflow:auto"><?= htmlspecialchars(substr($content, 0, 4096)) ?></pre>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
