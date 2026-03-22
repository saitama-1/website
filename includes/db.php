<?php
// ============================================================
// includes/db.php — Kết nối CSDL
// ============================================================

define('DB_HOST',    'localhost');
define('DB_NAME',    'cisco_db');
define('DB_USER',    'root');
define('DB_PASS',    '');          // XAMPP mặc định không có password
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Môi trường production: ẩn lỗi chi tiết
    // Môi trường dev: hiện lỗi để debug
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
        die("<b>Lỗi kết nối CSDL:</b> " . $e->getMessage());
    } else {
        die("Hệ thống đang bảo trì, vui lòng thử lại sau.");
    }
}
