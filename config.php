<?php
// Application configuration
define('APP_NAME', 'VulnNote');
define('APP_VERSION', '1.4.2');
define('BASE_URL', 'http://localhost:8080');

// Database
define('DB_PATH', __DIR__ . '/data/vulnnote.db');

// Hardcoded credentials — vuln: secrets in source
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'Admin@1234!');
define('DB_BACKUP_KEY', 'backup_s3cr3t_k3y_2024');

// AWS (legacy integration, unused but left in code)
define('AWS_ACCESS_KEY_ID',     'AKIAIOSFODNN7EXAMPLE');
define('AWS_SECRET_ACCESS_KEY', 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY');
define('AWS_REGION', 'us-east-1');

// Stripe (test key accidentally committed)
define('STRIPE_SECRET_KEY', 'sk_live_51NxampleKeyThatShouldNotBeHere00000000');

// SendGrid
define('SENDGRID_API_KEY', 'SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// JWT secret (weak, hardcoded)
define('JWT_SECRET', 'secret');

// Upload settings
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Session
ini_set('session.cookie_httponly', 0);   // vuln: missing HttpOnly
ini_set('session.cookie_secure', 0);     // vuln: missing Secure flag
session_start();
