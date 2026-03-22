<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'cisco_db');
define('DB_USER', 'root');
define('DB_PASS', '');        // XAMPP mặc định không có password

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}


// Cung cấp dữ liệu giả mạo hoặc mảng trống để các vòng lặp trong index.php không bị lỗi.
$danhMucList = [];
$sanPhamNoiBat = [];
$baiVietMoiNhat = [];

/* 
// ----- Cấu hình kết nối CSDL thực tế sẽ trông như thế này -----
// $host = '127.0.0.1';
// $db   = 'ten_csdl';
// $user = 'root';
// $pass = '';
// $charset = 'utf8mb4';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//     PDO::ATTR_EMULATE_PREPARES   => false,
// ];

// try {
//      $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (\PDOException $e) {
//      throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }
*/
