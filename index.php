<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// ============================================================
// LẤY DỮ LIỆU TỪ CSDL
// ============================================================

// 1. Danh mục sản phẩm
$danhMucList = $pdo->query("
    SELECT *
    FROM danh_muc
    WHERE hien_thi = 1
    ORDER BY thu_tu ASC
")->fetchAll();

// 2. Sản phẩm nổi bật (từ bảng san_pham_noi_bat, lấy 8 cái đầu)
$sanPhamNoiBat = $pdo->query("
    SELECT
        sp.id,
        sp.ten,
        sp.ma_san_pham,
        sp.slug,
        sp.anh_chinh,
        sp.tinh_trang,
        sp.noi_bat,
        s.ten                                       AS ten_series,
        dm.ten                                      AS ten_danh_muc,
        dm.slug                                     AS slug_danh_muc,
        COALESCE(spnb.mo_ta_ngan, sp.mo_ta_ngan)   AS mo_ta_hien_thi
    FROM san_pham_noi_bat spnb
    JOIN san_pham    sp  ON spnb.san_pham_id   = sp.id
    JOIN series      s   ON sp.series_id        = s.id
    JOIN ho_san_pham h   ON s.ho_san_pham_id    = h.id
    JOIN danh_muc    dm  ON h.danh_muc_id       = dm.id
    WHERE spnb.hien_thi = 1
      AND sp.hien_thi   = 1
      AND dm.hien_thi   = 1
    ORDER BY dm.thu_tu ASC, spnb.thu_tu ASC
    LIMIT 8
")->fetchAll();

// 3. Sản phẩm theo từng danh mục (4 SP/danh mục cho trang chủ)
//    Bỏ ROW_NUMBER() để tương thích MariaDB 10.4
//    Lấy tất cả SP của 4 nhóm, PHP tự giới hạn 4 cái/nhóm
$sanPhamTheoNhom = $pdo->query("
    SELECT
        sp.id,
        sp.ten,
        sp.ma_san_pham,
        sp.slug,
        sp.anh_chinh,
        sp.tinh_trang,
        sp.noi_bat,
        sp.mo_ta_ngan,
        s.ten   AS ten_series,
        dm.id   AS danh_muc_id,
        dm.ten  AS ten_danh_muc,
        dm.slug AS slug_danh_muc
    FROM san_pham    sp
    JOIN series      s  ON sp.series_id      = s.id
    JOIN ho_san_pham h  ON s.ho_san_pham_id  = h.id
    JOIN danh_muc    dm ON h.danh_muc_id     = dm.id
    WHERE sp.hien_thi = 1
      AND dm.hien_thi = 1
      AND dm.slug IN ('switch', 'router', 'firewall', 'wireless')
    ORDER BY dm.thu_tu ASC, sp.noi_bat DESC, sp.thu_tu ASC
")->fetchAll();

// Group theo slug danh mục, mỗi nhóm giữ tối đa 4 sản phẩm
$sanPhamNhom = [];
foreach ($sanPhamTheoNhom as $sp) {
    $slug = $sp['slug_danh_muc'];
    if (!isset($sanPhamNhom[$slug])) {
        $sanPhamNhom[$slug] = [];
    }
    if (count($sanPhamNhom[$slug]) < 4) {
        $sanPhamNhom[$slug][] = $sp;
    }
}

// 4. Bài viết mới nhất
$baiVietMoiNhat = $pdo->query("
    SELECT id, tieu_de, slug, tom_tat, anh_dai_dien, loai, tac_gia, tao_luc
    FROM bai_viet
    WHERE trang_thai = 1
    ORDER BY tao_luc DESC
    LIMIT 3
")->fetchAll();

// ============================================================
// HELPER
// ============================================================
$tinhTrangLabel = [
    'co_hang'        => ['text' => 'Còn hàng',        'cls' => 'status--green'],
    'het_hang'       => ['text' => 'Hết hàng',         'cls' => 'status--red'],
    'ngung_san_xuat' => ['text' => 'Ngừng SX',         'cls' => 'status--gray'],
    'lien_he'        => ['text' => 'Liên hệ báo giá',  'cls' => 'status--blue'],
];

$loaiBaiViet = [
    'blog'       => 'Blog',
    'huong_dan'  => 'Hướng dẫn',
    'tin_tuc'    => 'Tin tức',
    'case_study' => 'Case Study',
];

$nhomSection = [
    'switch'   => ['icon' => '🔀', 'label' => 'Cisco Switch',   'title' => 'Thiết bị chuyển mạch'],
    'router'   => ['icon' => '🌐', 'label' => 'Cisco Router',   'title' => 'Thiết bị định tuyến'],
    'firewall' => ['icon' => '🔒', 'label' => 'Cisco Firewall', 'title' => 'Thiết bị tường lửa'],
    'wireless' => ['icon' => '📶', 'label' => 'Cisco Wireless', 'title' => 'Access Point Wi-Fi'],
];
// Cấu hình header
$tieuDeTrang = 'CiscoVN — Nhà phân phối thiết bị mạng Cisco chính hãng';
$moTaTrang   = 'Nhà phân phối thiết bị mạng Cisco chính hãng tại Việt Nam. Switch, Router, Firewall, Wireless, Module quang.';
$navActive   = 'trang-chu';
require_once 'includes/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero__grid-bg"></div>
  <div class="hero__glow-1"></div>
  <div class="hero__glow-2"></div>
  <div class="container hero__inner">
    <div class="hero__content">
      <div class="hero__pill">
        <span class="hero__pill-dot"></span>
        Nhà phân phối Cisco chính hãng tại Việt Nam
      </div>
      <h1 class="hero__title">
        Giải pháp mạng<br>
        <span class="hero__accent">Cisco</span> toàn diện
      </h1>
      <p class="hero__desc">
        Cung cấp thiết bị mạng Cisco chính hãng — Switch, Router, Firewall,
        Wireless và Module quang. Tư vấn thiết kế hạ tầng mạng chuyên nghiệp
        cho doanh nghiệp tại Việt Nam.
      </p>
      <div class="hero__btns">
        <a href="danh-sach-san-pham.php" class="btn btn--primary btn--lg">
          Xem sản phẩm
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="#" data-modal="lien-he" class="btn btn--ghost btn--lg">Yêu cầu báo giá</a>
      </div>
      <div class="hero__stats">
        <div class="hero__stat"><strong>500+</strong><span>Sản phẩm</span></div>
        <div class="hero__stat-line"></div>
        <div class="hero__stat"><strong>10+</strong><span>Năm kinh nghiệm</span></div>
        <div class="hero__stat-line"></div>
        <div class="hero__stat"><strong>1.000+</strong><span>Khách hàng</span></div>
        <div class="hero__stat-line"></div>
        <div class="hero__stat"><strong>24/7</strong><span>Hỗ trợ</span></div>
      </div>
    </div>
    <div class="hero__cards">
      <div class="hcard hcard--1">
        <span class="hcard__icon">🔀</span>
        <div class="hcard__body"><strong>Catalyst 9300-48P</strong><small>48-Port PoE+ Switch · L3</small></div>
        <span class="hcard__tag">Liên hệ</span>
      </div>
      <div class="hcard hcard--2">
        <span class="hcard__icon">🔒</span>
        <div class="hcard__body"><strong>Firepower 2110</strong><small>Next-Gen Firewall · NGFW</small></div>
        <span class="hcard__tag">Liên hệ</span>
      </div>
      <div class="hcard hcard--3">
        <span class="hcard__icon">🌐</span>
        <div class="hcard__body"><strong>ISR 4331/K9</strong><small>Enterprise Router · SD-WAN</small></div>
        <span class="hcard__tag">Liên hệ</span>
      </div>
      <div class="hcard hcard--4">
        <span class="hcard__icon">📶</span>
        <div class="hcard__body"><strong>C9120AXI-A</strong><small>Wi-Fi 6 AP · Indoor</small></div>
        <span class="hcard__tag">Liên hệ</span>
      </div>
    </div>
  </div>
  <div class="hero__scroll-hint">
    <div class="scroll-mouse"><div class="scroll-wheel"></div></div>
    <span>Cuộn xuống</span>
  </div>
</section>

<!-- ===== WHY US ===== -->
<section class="why-us">
  <div class="container why-us__grid">
    <div class="why-us__item">
      <div class="why-us__icon">✅</div>
      <div><strong>Hàng chính hãng 100%</strong><span>CO/CQ đầy đủ, bảo hành Cisco</span></div>
    </div>
    <div class="why-us__item">
      <div class="why-us__icon">🚀</div>
      <div><strong>Giao hàng toàn quốc</strong><span>Nhanh chóng, đóng gói cẩn thận</span></div>
    </div>
    <div class="why-us__item">
      <div class="why-us__icon">🛠️</div>
      <div><strong>Hỗ trợ kỹ thuật 24/7</strong><span>Kỹ sư CCNA/CCNP giàu kinh nghiệm</span></div>
    </div>
    <div class="why-us__item">
      <div class="why-us__icon">💰</div>
      <div><strong>Báo giá cạnh tranh</strong><span>Chiết khấu hấp dẫn theo số lượng</span></div>
    </div>
  </div>
</section>

<!-- ===== DANH MỤC ===== -->
<section class="section categories-section">
  <div class="container">
    <div class="section__hd">
      <div>
        <p class="section__label">Danh mục sản phẩm</p>
        <h2 class="section__title">Dòng thiết bị Cisco</h2>
      </div>
      <a href="danh-sach-san-pham.php" class="more-link">
        Xem tất cả
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="cat-grid">
      <?php foreach ($danhMucList as $dm): ?>
      <a href="danh-sach-san-pham.php?danh_muc=<?= lamsach($dm['slug']) ?>" class="cat-card">
        <div class="cat-card__icon"><?= $dm['icon'] ?></div>
        <div class="cat-card__body">
          <h3><?= lamsach($dm['ten']) ?></h3>
          <p><?= lamsach($dm['mo_ta']) ?></p>
        </div>
        <div class="cat-card__arr">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== SẢN PHẨM NỔI BẬT ===== -->
<?php if (!empty($sanPhamNoiBat)): ?>
<section class="section section--bg prod-section">
  <div class="container">
    <div class="section__hd">
      <div>
        <p class="section__label">Được quan tâm nhiều nhất</p>
        <h2 class="section__title">Sản phẩm nổi bật</h2>
      </div>
      <a href="danh-sach-san-pham.php" class="more-link">
        Xem tất cả
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="prod-grid">
      <?php foreach ($sanPhamNoiBat as $sp): ?>
      <article class="prod-card">
        <div class="prod-card__img">
          <a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>">
            <img
              src="<?= $sp['anh_chinh'] ? lamsach($sp['anh_chinh']) : 'https://placehold.co/400x280/e8f4ff/005073?text=' . urlencode($sp['ma_san_pham']) ?>"
              alt="<?= lamsach($sp['ten']) ?>"
            loading="lazy" 
              onerror="this.src='https://placehold.co/400x280/e8f4ff/005073?text=No+Image'" />
          </a>
          <div class="prod-card__badges">
            <span class="badge-dm"><?= lamsach($sp['ten_danh_muc']) ?></span>
          </div>
        </div>
        <div class="prod-card__body">
          <p class="prod-card__series"><?= lamsach($sp['ten_series']) ?></p>
          <h3 class="prod-card__name">
            <a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>">
              <?= lamsach($sp['ten']) ?>
            </a>
          </h3>
          <span class="prod-card__code"><?= lamsach($sp['ma_san_pham']) ?></span>
          <p class="prod-card__desc"><?= lamsach($sp['mo_ta_hien_thi']) ?></p>
          <div class="prod-card__foot">
            <a href="#" data-modal="lien-he" data-sp-ma="<?= lamsach($sp['ma_san_pham']) ?>"
               class="btn btn--primary btn--sm">
              Liên hệ báo giá
            </a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== SECTION THEO TỪNG DANH MỤC ===== -->
<?php
$bgToggle = false; // xen kẽ nền trắng / xám
foreach ($nhomSection as $slug => $info):
  if (empty($sanPhamNhom[$slug])) continue;
  $bgClass = $bgToggle ? 'section--bg' : '';
  $bgToggle = !$bgToggle;
?>
<section class="section <?= $bgClass ?> cat-section">
  <div class="container">
    <div class="section__hd">
      <div class="section__hd-left">
        <span class="section__icon"><?= $info['icon'] ?></span>
        <div>
          <p class="section__label"><?= $info['label'] ?></p>
          <h2 class="section__title"><?= $info['title'] ?></h2>
        </div>
      </div>
      <a href="danh-sach-san-pham.php?danh_muc=<?= $slug ?>" class="more-link">
        Xem tất cả <?= $info['label'] ?>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="prod-grid">
      <?php
      $mauAnh = [
          'switch'   => 'e8f4ff/005073',
          'router'   => 'fff4e8/7a4a00',
          'firewall' => 'f0e8ff/4a007a',
          'wireless' => 'e8fff4/007a4a',
      ];
      $mau = $mauAnh[$slug] ?? 'e8f4ff/005073';
      foreach ($sanPhamNhom[$slug] as $sp):
      ?>
      <article class="prod-card">
        <div class="prod-card__img">
          <a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>">
            <img
              src="<?= $sp['anh_chinh'] ? lamsach($sp['anh_chinh']) : 'https://placehold.co/400x280/' . $mau . '?text=' . urlencode($sp['ma_san_pham']) ?>"
              alt="<?= lamsach($sp['ten']) ?>"
              loading="lazy"
              onerror="this.src='https://placehold.co/400x280/<?= $mau ?>?text=No+Image'"
          </a>
          <?php if ($sp['noi_bat']): ?>
          <div class="prod-card__badges">
            <span class="badge-hot">Nổi bật</span>
          </div>
          <?php endif; ?>
        </div>
        <div class="prod-card__body">
          <p class="prod-card__series"><?= lamsach($sp['ten_series']) ?></p>
          <h3 class="prod-card__name">
            <a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>">
              <?= lamsach($sp['ten']) ?>
            </a>
          </h3>
          <span class="prod-card__code"><?= lamsach($sp['ma_san_pham']) ?></span>
          <p class="prod-card__desc"><?= lamsach($sp['mo_ta_ngan']) ?></p>
          <div class="prod-card__foot">
            <a href="#" data-modal="lien-he" data-sp-ma="<?= lamsach($sp['ma_san_pham']) ?>"
               class="btn btn--primary btn--sm">
              Liên hệ báo giá
            </a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- ===== CTA BANNER ===== -->
<section class="cta-section">
  <div class="cta-section__bg"></div>
  <div class="container cta-section__inner">
    <div class="cta-section__text">
      <h2>Cần tư vấn giải pháp mạng?</h2>
      <p>Đội ngũ kỹ sư Cisco sẵn sàng thiết kế hạ tầng mạng phù hợp với nhu cầu và ngân sách của doanh nghiệp bạn.</p>
    </div>
    <div class="cta-section__actions">
      <!-- Sử dụng biến hệ thống thay vì số điện thoại fix cứng -->
      <a href="tel:<?= preg_replace('/\s+/', '', CONG_TY_DIEN_THOAI) ?>" class="btn btn--white btn--lg">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
        <?= lamsach(CONG_TY_DIEN_THOAI) ?>
      </a>
      <a href="#" data-modal="lien-he" class="btn btn--ghost-white btn--lg">Gửi yêu cầu tư vấn</a>
    </div>
  </div>
</section>

<!-- ===== BLOG ===== -->
<?php if (!empty($baiVietMoiNhat)): ?>
<section class="section blog-section">
  <div class="container">
    <div class="section__hd">
      <div>
        <p class="section__label">Kiến thức & Kinh nghiệm</p>
        <h2 class="section__title">Bài viết mới nhất</h2>
      </div>
      <a href="blog.php" class="more-link">
        Xem tất cả
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="blog-grid">
      <?php foreach ($baiVietMoiNhat as $i => $bv): ?>
      <article class="blog-card <?= $i === 0 ? 'blog-card--featured' : '' ?>">
        <a href="blog/<?= lamsach($bv['slug']) ?>.php" class="blog-card__img">
          <img
            src="<?= $bv['anh_dai_dien'] ? lamsach($bv['anh_dai_dien']) : 'https://placehold.co/800x450/e8f4ff/005073?text=Blog' ?>"
            alt="<?= lamsach($bv['tieu_de']) ?>"
            loading="lazy" />
          <span class="blog-card__cat">
            <?= lamsach($loaiBaiViet[$bv['loai']] ?? 'Blog') ?>
          </span>
        </a>
        <div class="blog-card__body">
          <div class="blog-card__meta">
            <span>✍️ <?= lamsach($bv['tac_gia']) ?></span>
            <span>📅 <?= date('d/m/Y', strtotime($bv['tao_luc'])) ?></span>
          </div>
          <h3 class="blog-card__title">
            <a href="blog/<?= lamsach($bv['slug']) ?>.php">
              <?= lamsach($bv['tieu_de']) ?>
            </a>
          </h3>
          <p class="blog-card__desc">
            <?= lamsach(rutGon($bv['tom_tat'], $i === 0 ? 180 : 120)) ?>
          </p>
          <a href="blog/<?= lamsach($bv['slug']) ?>.php" class="blog-card__more">
            Đọc thêm →
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== FOOTER ===== -->

<?php
// Cấu hình footer
require_once 'includes/footer.php';
?>
