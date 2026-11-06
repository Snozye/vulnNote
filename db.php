<?php
require_once __DIR__ . '/config.php';

function get_db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $init = !file_exists(DB_PATH);
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($init) {
        $pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                email TEXT,
                role TEXT DEFAULT 'user',
                bio TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                title TEXT NOT NULL,
                body TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                post_id INTEGER,
                author TEXT,
                body TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            INSERT INTO users (username, password, email, role) VALUES
                ('admin', 'admin123', 'admin@vulnnote.local', 'admin'),
                ('alice', 'password1', 'alice@vulnnote.local', 'user'),
                ('bob',   'qwerty',   'bob@vulnnote.local',   'user');

            INSERT INTO posts (user_id, title, body) VALUES
                (1, 'Welcome to VulnNote', 'This is the first post on VulnNote, a developer notes platform.'),
                (2, 'How to use this platform', 'Create an account, write your notes, share with the team.'),
                (1, 'Admin notes (private)', 'Server root password: toor123. SSH key at /root/.ssh/id_rsa.');

            INSERT INTO comments (post_id, author, body) VALUES
                (1, 'alice', 'Great platform!'),
                (2, 'bob',   'Very useful, thanks.');
        ");
    }

    return $pdo;
}
