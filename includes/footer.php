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
            📞 <?= lamsach(CONG_TY_DIEN_THOAI) ?>
          </a>
          <a href="mailto:<?= lamsach(CONG_TY_EMAIL) ?>">
            ✉️ <?= lamsach(CONG_TY_EMAIL) ?>
          </a>
          <a href="#">📍 <?= lamsach(CONG_TY_DIA_CHI) ?></a>
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
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=switch">🔀 Switch</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=router">🌐 Router</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=firewall">🔒 Firewall</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=wireless">📶 Wireless</a></li>
            <li><a href="<?= BASE_URL ?>danh-sach-san-pham.php?danh_muc=module-quang">💡 Module quang</a></li>
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
              📞 Hotline 24/7: <?= lamsach(CONG_TY_DIEN_THOAI) ?>
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
