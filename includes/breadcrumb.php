<?php
// ============================================================
// includes/breadcrumb.php
// Dùng chung toàn website để render thanh điều hướng (Breadcrumb)
// Yêu cầu: Khai báo mảng $breadcrumbs trước khi include
// ============================================================
if (!isset($breadcrumbs) || !is_array($breadcrumbs)) {
    $breadcrumbs = [];
}
?>
<div class="breadcrumb-bar">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <!-- Về trang chủ -->
      <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>index.php" class="breadcrumb__item">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Trang chủ
      </a>
      <?php foreach ($breadcrumbs as $index => $item): ?>
        <span class="breadcrumb__sep">›</span>
        <?php if (!empty($item['url']) && $index < count($breadcrumbs) - 1): ?>
          <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?><?= $item['url'] ?>" class="breadcrumb__item"><?= function_exists('lamsach') ? lamsach($item['label']) : htmlspecialchars($item['label']) ?></a>
        <?php else: ?>
          <span class="breadcrumb__item breadcrumb__item--active" aria-current="page"><?= function_exists('lamsach') ? lamsach($item['label']) : htmlspecialchars($item['label']) ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
  </div>
</div>