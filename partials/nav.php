<nav>
    <a class="brand" href="index.php"><span>&lt;/&gt;</span> VulnNote</a>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="search.php">Search</a></li>
        <li><a href="upload.php">Upload</a></li>
        <li><a href="include.php?page=about">About</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php?id=<?= $_SESSION['user_id'] ?>">Profile</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="admin/index.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
