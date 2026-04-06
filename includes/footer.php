<?php
// ============================================================
// includes/footer.php
// Dùng chung cho tất cả trang
//
// Biến cần có trước khi include:
//   $danhMucList (array) — danh sách danh mục (thường đã có từ header)
//   $jsExtra     (array) — JS bổ sung, vd: ['assets/js/product.js']
//
// Ví dụ dùng:
//   $jsExtra = ['assets/js/danh-sach-san-pham.js'];
//   require_once 'includes/footer.php';
// ============================================================

if (!defined('CONG_TY_DIEN_THOAI')) {
    require_once __DIR__ . '/config.php';
}
?>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="container">
    <div class="footer__grid">

      <!-- Cột 1: Brand -->
      <div class="footer__brand">
        <a href="<?= BASE_URL ?>index.php" class="logo logo--light">
          <div class="logo__mark">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </div>
          <div class="logo__text">
            <span class="logo__brand">CiscoVN</span>
            <span class="logo__tagline">Network Solutions</span>
          </div>
        </a>
        <p class="footer__desc">
          Nhà phân phối thiết bị mạng Cisco chính hãng tại Việt Nam.
          Tư vấn, cung cấp và triển khai giải pháp mạng toàn diện cho doanh nghiệp.
        </p>
        <div class="footer__contacts">
          <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            <?= lamsach(CONG_TY_DIEN_THOAI) ?>
          </a>
          <a href="mailto:<?= lamsach(CONG_TY_EMAIL) ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <?= lamsach(CONG_TY_EMAIL) ?>
          </a>
          <a href="#">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <?= lamsach(CONG_TY_DIA_CHI) ?>
          </a>
        </div>
      </div>

      <!-- Cột 2: Sản phẩm — lấy từ CSDL -->
      <div class="footer__col">
        <h4>Sản phẩm</h4>
        <ul>
          <?php if (!empty($danhMucList)): ?>
            <?php foreach ($danhMucList as $dm): ?>
            <li>
              <a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=<?= lamsach($dm['slug']) ?>">
                <?= $dm['icon'] ?> <?= lamsach($dm['ten']) ?>
              </a>
            </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=switch"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg> Switch</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=router"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2v20"/></svg> Router</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=firewall"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Firewall</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=wireless"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0114.08 0"/><path d="M1.42 9a16 16 0 0121.16 0"/><circle cx="12" cy="20" r="1"/></svg> Wireless</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=module-quang"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/></svg> Module quang</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Cột 3: Hỗ trợ -->
      <div class="footer__col">
        <h4>Hỗ trợ</h4>
        <ul>
          <li><a href="#" data-modal="lien-he">Yêu cầu báo giá</a></li>
          <li><a href="#" data-modal="lien-he">Tư vấn kỹ thuật</a></li>
          <li><a href="<?= BASE_URL ?>blog.php">Hướng dẫn cấu hình</a></li>
          <li><a href="<?= BASE_URL ?>blog.php">Blog kỹ thuật</a></li>
          <li><a href="#">Chính sách bảo hành</a></li>
          <li><a href="#">Chính sách đổi trả</a></li>
        </ul>
      </div>

      <!-- Cột 4: Công ty + Giờ làm việc -->
      <div class="footer__col">
        <h4>Công ty</h4>
        <ul>
          <li><a href="#">Về chúng tôi</a></li>
          <li><a href="#">Đối tác Cisco</a></li>
          <li><a href="#" data-modal="lien-he">Liên hệ</a></li>
        </ul>
        <h4 style="margin-top:22px">Giờ làm việc</h4>
        <ul>
          <li><span class="footer__muted">T2–T6: 8h – 17h30</span></li>
          <li><span class="footer__muted">T7: 8h – 12h</span></li>
          <li>
            <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>"
               class="footer__hotline">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
              Hotline 24/7: <?= lamsach(CONG_TY_DIEN_THOAI) ?>
            </a>
          </li>
        </ul>
      </div>

    </div>

    <div class="footer__bottom">
      <p>© <?= date('Y') ?> <?= lamsach(CONG_TY_TEN) ?>. All rights reserved.</p>
      <div class="footer__links">
        <a href="#">Điều khoản</a>
        <a href="#">Bảo mật</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- Back to top -->
<button class="back-top" id="backTop" title="Lên đầu trang">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg>
</button>

<!-- Modal liên hệ dùng chung -->
<?php require_once __DIR__ . '/modal-lien-he.php'; ?>

<!-- JS chung -->
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
<script src="<?= BASE_URL ?>assets/js/modal-lien-he.js"></script>

<!-- JS bổ sung theo từng trang -->
<?php foreach ($jsExtra ?? [] as $js): ?>
<script src="<?= BASE_URL . lamsach($js) ?>"></script>
<?php endforeach; ?>

</body>
</html>
