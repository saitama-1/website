<?php
// ============================================================
// includes/header.php
// Dùng chung cho tất cả trang
//
// Biến cần khai báo TRƯỚC khi include file này:
//   $tieuDeTrang  (string) — title tab trình duyệt
//   $moTaTrang    (string) — meta description
//   $cssExtra     (array)  — CSS bổ sung, vd: ['assets/css/product.css']
//   $navActive    (string) — trang đang active: 'trang-chu' | 'san-pham' | 'blog' | 'lien-he'
//
// Ví dụ dùng:
//   $tieuDeTrang = 'Switch Cisco — CiscoVN';
//   $moTaTrang   = 'Danh sách switch Cisco chính hãng';
//   $cssExtra    = ['assets/css/danh-sach-san-pham.css'];
//   $navActive   = 'san-pham';
//   require_once 'includes/header.php';
// ============================================================

if (!defined('CONG_TY_DIEN_THOAI')) {
    require_once __DIR__ . '/config.php';
}
if (!function_exists('lamsach')) {
    require_once __DIR__ . '/functions.php';
}

// Lấy danh mục cho dropdown nav (nếu chưa có)
if (empty($danhMucList) && !empty($pdo)) {
    $danhMucList = $pdo->query("
        SELECT id, ten, slug, icon
        FROM danh_muc
        WHERE hien_thi = 1
        ORDER BY thu_tu ASC
    ")->fetchAll();
}

$navActive = $navActive ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= lamsach($tieuDeTrang ?? 'CiscoVN — Nhà phân phối thiết bị mạng Cisco') ?></title>
  <meta name="description" content="<?= lamsach($moTaTrang ?? 'Nhà phân phối thiết bị mạng Cisco chính hãng tại Việt Nam.') ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/modal-lien-he.css?v=<?= time() ?>" />
  <?php foreach ($cssExtra ?? [] as $css): ?>
  <link rel="stylesheet" href="<?= BASE_URL . lamsach($css) ?>?v=<?= time() ?>" />
  <?php endforeach; ?>
</head>
<body>

<!-- ===== TOP BAR ===== -->
<div class="topbar">
  <div class="container topbar__inner">
    <div class="topbar__left">
      <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>" class="topbar__item">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
        <?= lamsach(CONG_TY_DIEN_THOAI) ?>
      </a>
      <a href="mailto:<?= lamsach(CONG_TY_EMAIL) ?>" class="topbar__item">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <?= lamsach(CONG_TY_EMAIL) ?>
      </a>
      <span class="topbar__item topbar__item--hidden-mobile">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Thứ 2–6: 8h–17h30 &nbsp;|&nbsp; Thứ 7: 8h–12h
      </span>
    </div>
    <a href="#" data-modal="lien-he" class="topbar__cta">
      Yêu cầu báo giá
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</div>

<!-- ===== HEADER ===== -->
<header class="header" id="header">
  <div class="container header__inner">

    <a href="<?= BASE_URL ?>index.php" class="logo">
      <div class="logo__mark">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      </div>
      <div class="logo__text">
        <span class="logo__brand">CiscoVN</span>
        <span class="logo__tagline">Network Solutions</span>
      </div>
    </a>

    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

    <nav class="nav" id="nav">
      <ul class="nav__list">
        <li>
          <a href="<?= BASE_URL ?>index.php"
             class="nav__link <?= $navActive === 'trang-chu' ? 'active' : '' ?>">
            Trang chủ
          </a>
        </li>
        <li>
          <a href="<?= BASE_URL ?>danh-sach-san-pham.php"
             class="nav__link <?= $navActive === 'san-pham' ? 'active' : '' ?>">
            Sản phẩm
          </a>
        </li>
        <li class="has-dropdown">
          <a href="#" class="nav__link nav__link--arrow">
            Giải pháp
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
          <div class="dropdown">
            <div class="dropdown__inner">
              <div class="dropdown__header"><p>Giải pháp theo ngành</p></div>
              <a href="#" class="dropdown__item">
                <span class="dropdown__icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
                </span>
                <span class="dropdown__content"><strong>Enterprise Network</strong><small>Hạ tầng mạng doanh nghiệp lớn</small></span>
                <svg class="dropdown__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
              <a href="#" class="dropdown__item">
                <span class="dropdown__icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </span>
                <span class="dropdown__content"><strong>Campus Network</strong><small>Mạng trường học, tòa nhà văn phòng</small></span>
                <svg class="dropdown__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
              <a href="#" class="dropdown__item">
                <span class="dropdown__icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
                <span class="dropdown__content"><strong>Network Security</strong><small>Bảo mật hạ tầng & tường lửa NGFW</small></span>
                <svg class="dropdown__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
              <a href="#" class="dropdown__item">
                <span class="dropdown__icon">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 109 20h9A5 5 0 0018 10z"/></svg>
                </span>
                <span class="dropdown__content"><strong>SD-WAN & Cloud</strong><small>Kết nối chi nhánh, hybrid cloud</small></span>
                <svg class="dropdown__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
              <div class="dropdown__footer">
                <a href="#" data-modal="lien-he">
                  Tư vấn giải pháp miễn phí
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
              </div>
            </div>
          </div>
        </li>
        <li>
          <a href="<?= BASE_URL ?>blog.php"
             class="nav__link <?= $navActive === 'blog' ? 'active' : '' ?>">
            Blog
          </a>
        </li>
        <li>
          <a href="#" data-modal="lien-he"
             class="nav__link <?= $navActive === 'lien-he' ? 'active' : '' ?>">
            Liên hệ
          </a>
        </li>
      </ul>
    </nav>

    <div class="header__actions">
      <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>" class="header__phone">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
        <?= lamsach(CONG_TY_DIEN_THOAI) ?>
      </a>
      <a href="#" data-modal="lien-he" class="btn btn--primary btn--sm">Báo giá</a>
    </div>

  </div>
</header>
