<?php
// ============================================================
// includes/config.php
// Cấu hình chung toàn website — sửa tại đây, áp dụng tất cả
// ============================================================

// ── Thông tin công ty ──────────────────────────────────────
define('CONG_TY_TEN',      'CiscoVN Network Solutions');
define('CONG_TY_DIEN_THOAI', '0901 234 567');
define('CONG_TY_EMAIL',    'info@cisco-vn.com');
define('CONG_TY_DIA_CHI',  '123 Nguyễn Huệ, Q1, TP.HCM');

// ── Danh sách nhân viên tư vấn Zalo ───────────────────────
// Sửa tại đây để cập nhật popup liên hệ trên toàn bộ website
define('ZALO_NHAN_VIEN', [
    [
        'ten'       => 'Nguyễn Thành',
        'chuc_vu'   => 'Kỹ sư CCNP · Switch & Router',
        'sdt'       => '0901234567',        // dùng cho zalo.me/SĐT
        'sdt_hien'  => '0901 234 567',      // hiển thị
        'avatar'    => 'NT',                // 2 chữ cái viết tắt
        'mau_avatar'=> '#005073',           // màu nền avatar
    ],
    [
        'ten'       => 'Phạm Linh',
        'chuc_vu'   => 'Sales · Tư vấn giải pháp',
        'sdt'       => '0902345678',
        'sdt_hien'  => '0902 345 678',
        'avatar'    => 'PL',
        'mau_avatar'=> '#0077a3',
    ],
    [
        'ten'       => 'Trần Huy',
        'chuc_vu'   => 'Kỹ sư CCNA · Wireless & Security',
        'sdt'       => '0903456789',
        'sdt_hien'  => '0903 456 789',
        'avatar'    => 'TH',
        'mau_avatar'=> '#00bceb',
    ],
]);

// ── Đường dẫn gốc website (tự động detect) ────────────────
// Dùng BASE_URL thay cho đường dẫn tương đối trong href/src
// VD: localhost/cisco-vn/ → BASE_URL = '/cisco-vn/'
//     localhost/          → BASE_URL = '/'
if (!defined('BASE_URL')) {
    $scriptDir  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $baseUrl    = rtrim($scriptDir, '/') . '/';
    // Nếu file nằm trong thư mục con (vd: includes/), lùi lại 1 cấp
    // Nhưng SCRIPT_NAME luôn trỏ đến file đang được request (index.php)
    // nên không cần xử lý thêm
    define('BASE_URL', $baseUrl);
}
