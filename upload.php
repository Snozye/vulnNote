<?php
require_once __DIR__ . '/db.php';

// VULN #5: Unrestricted File Upload — no MIME/extension validation
// Attacker uploads shell.php → accesses /uploads/shell.php → RCE
$message = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed.';
    } elseif ($file['size'] > MAX_FILE_SIZE) {
        $error = 'File too large (max 10MB).';
    } else {
        // No extension or MIME type check — any file type accepted
        $dest = UPLOAD_DIR . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $message = "File uploaded: <a href='uploads/" . htmlspecialchars(basename($file['name'])) . "'>" . htmlspecialchars($file['name']) . "</a>";
        } else {
            $error = 'Could not save file.';
        }
    }
}

// List uploaded files
$files = array_diff(scandir(UPLOAD_DIR), ['.', '..', '.gitkeep']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <h1 class="page-title">File Upload</h1>
    <div class="card">
        <?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
        <?php if ($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Choose file (any type accepted)</label>
            <input type="file" name="file" style="margin-bottom:1rem">
            <button class="btn btn-primary">Upload</button>
        </form>
    </div>

    <?php if ($files): ?>
    <div class="card">
        <h2>Uploaded files</h2>
        <ul style="margin-top:.8rem;padding-left:1.2rem">
            <?php foreach ($files as $f): ?>
            <li style="margin-bottom:.3rem">
                <a href="uploads/<?= htmlspecialchars($f) ?>"><?= htmlspecialchars($f) ?></a>
                &nbsp;<a href="download.php?file=<?= urlencode($f) ?>" style="font-size:.8rem">[download]</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
