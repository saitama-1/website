<?php
// ============================================================
// includes/modal-lien-he.php
// Include file này 1 lần trước </body> trên mọi trang
// ============================================================
if (!defined('ZALO_NHAN_VIEN')) {
    require_once __DIR__ . '/config.php';
}
?>

<!-- ===== MODAL LIÊN HỆ TƯ VẤN (dùng chung toàn site) ===== -->
<div class="modal-overlay" id="modalLienHe" role="dialog" aria-modal="true" aria-labelledby="modalLienHeTitle">
  <div class="modal">

    <!-- Nút đóng -->
    <button class="modal__close" id="modalLienHeClose" aria-label="Đóng">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>

    <!-- Header -->
    <div class="modal__header">
      <div class="modal__icon">💬</div>
      <h3 id="modalLienHeTitle">Liên hệ tư vấn & báo giá</h3>
      <p>Chọn nhân viên để chat Zalo ngay</p>
    </div>

    <!-- Thông tin sản phẩm (JS sẽ cập nhật nếu có) -->
    <div class="modal__product" id="modalSanPham" style="display:none">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="2" y="3" width="20" height="14" rx="2"/>
        <line x1="8" y1="21" x2="16" y2="21"/>
        <line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
      <code id="modalMaSanPham"></code>
      <span id="modalTenSanPham"></span>
    </div>

    <!-- Danh sách Zalo — từ config.php -->
    <div class="zalo-list">
      <?php foreach (ZALO_NHAN_VIEN as $nv): ?>
      <a href="https://zalo.me/<?= $nv['sdt'] ?>"
         target="_blank"
         rel="noopener noreferrer"
         class="zalo-item">

        <div class="zalo-item__avatar"
             style="background:<?= htmlspecialchars($nv['mau_avatar']) ?>">
          <?= htmlspecialchars($nv['avatar']) ?>
        </div>

        <div class="zalo-item__info">
          <strong><?= htmlspecialchars($nv['ten']) ?></strong>
          <span><?= htmlspecialchars($nv['chuc_vu']) ?></span>
          <span class="zalo-item__phone"><?= htmlspecialchars($nv['sdt_hien']) ?></span>
        </div>

        <div class="zalo-item__action">
          <!-- Zalo icon -->
          <svg width="28" height="28" viewBox="0 0 48 48" fill="none">
            <circle cx="24" cy="24" r="24" fill="#0068FF"/>
            <path d="M24 10C16.27 10 10 15.82 10 23c0 4.14 2.07 7.83 5.3 10.3L14 38l5.1-2.6c1.5.42 3.1.65 4.9.65 7.73 0 14-5.82 14-13S31.73 10 24 10z" fill="white"/>
          </svg>
          Chat Zalo
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Đường phân cách -->
    <div class="modal__or">hoặc gọi hotline</div>

    <!-- Nút gọi hotline -->
    <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>"
       class="btn btn--primary"
       style="width:100%;justify-content:center">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
      </svg>
      Gọi ngay <?= htmlspecialchars(CONG_TY_DIEN_THOAI) ?>
    </a>

  </div>
</div>
