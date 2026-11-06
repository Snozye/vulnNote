<?php
require_once __DIR__ . '/db.php';

// VULN #7: Local File Inclusion (LFI)
// Payload: include.php?page=../config
//          include.php?page=/etc/passwd%00  (null-byte on older PHP)
//          include.php?page=php://filter/convert.base64-encode/resource=config
$page = $_GET['page'] ?? 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page · <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav.php'; ?>
<div class="container">
    <div class="card">
        <?php
        // No path sanitization — includes arbitrary files
        include('pages/' . $page . '.php');
        ?>
    </div>
</div>
</body>
</html>
