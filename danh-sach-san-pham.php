<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// ============================================================
// XỬ LÝ THAM SỐ URL
// ============================================================
$danhMucSlug  = isset($_GET['danh_muc'])  ? lamsach($_GET['danh_muc'])  : 'switch';
$seriesFilter = isset($_GET['series'])    ? lamsach($_GET['series'])    : '';
$trang        = isset($_GET['trang'])     ? max(1, (int)$_GET['trang']) : 1;

// Các tham số lọc đặc thù Switch (Single Choice)
$scenarioFilter  = isset($_GET['scenario'])  ? lamsach($_GET['scenario'])  : '';
$interfaceFilter = isset($_GET['interface']) ? $_GET['interface']          : '';
$dlspeedFilter   = isset($_GET['dlspeed'])   ? $_GET['dlspeed']            : '';
$poeFilter       = isset($_GET['poe'])       ? $_GET['poe']                : '';

$soTrenTrang  = 12;
$offset       = ($trang - 1) * $soTrenTrang;

// ============================================================
// LẤY DỮ LIỆU TỪ DATABASE
// ============================================================
$danhMucHienTai = null;
$seriesList     = [];
$sanPhamList    = [];
$tongSo         = 0;
$tongTrang      = 0;

if ($pdo) {
    // Lấy danh mục hiện tại
    $stmt = $pdo->prepare("SELECT * FROM danh_muc WHERE slug = ? AND hien_thi = 1");
    $stmt->execute([$danhMucSlug]);
    $danhMucHienTai = $stmt->fetch();

    if ($danhMucHienTai) {
        $dmId = $danhMucHienTai['id'];

        // Lấy danh sách series
        $stmt = $pdo->prepare("
            SELECT s.id, s.ten, s.slug, COUNT(sp.id) AS so_san_pham
            FROM series s
            JOIN ho_san_pham h ON s.ho_san_pham_id = h.id
            LEFT JOIN san_pham sp ON sp.series_id = s.id AND sp.hien_thi = 1
            WHERE h.danh_muc_id = ? AND s.hien_thi = 1
            GROUP BY s.id, s.ten, s.slug
            ORDER BY s.thu_tu, s.ten
        ");
        $stmt->execute([$dmId]);
        $seriesList = $stmt->fetchAll();

        // Lấy danh sách scenario (Kịch bản sử dụng)
        $scenarioList = $pdo->query("SELECT * FROM scenario WHERE hien_thi = 1 ORDER BY thu_tu")->fetchAll();

        // Build WHERE
        $where  = ['sp.hien_thi = 1', 'dm.id = :dm_id'];
        $params = [':dm_id' => $dmId];

        if ($seriesFilter) {
            $where[] = "s.slug = :s_slug";
            $params[':s_slug'] = $seriesFilter;
        }

        if ($scenarioFilter) {
            $where[] = "s.scenario_id IN (SELECT id FROM scenario WHERE slug = :sc_slug)";
            $params[':sc_slug'] = $scenarioFilter;
        }

        // --- Lọc theo thông số chuẩn hóa (Single Choice) ---
        
        // 1. Lọc theo số cổng Downlink
        if ($interfaceFilter) {
            if ($interfaceFilter === '8-16') $where[] = "sp.so_cong_downlink BETWEEN 8 AND 16";
            elseif ($interfaceFilter === '24') $where[] = "sp.so_cong_downlink = 24";
            elseif ($interfaceFilter === '48') $where[] = "sp.so_cong_downlink = 48";
        }

        // 2. Lọc theo tốc độ Downlink
        if ($dlspeedFilter) {
            if (str_contains($dlspeedFilter, '10Gb')) {
                $where[] = "sp.toc_do_downlink LIKE '%10G%'";
            } elseif (str_contains($dlspeedFilter, '1Gb')) {
                $where[] = "sp.toc_do_downlink LIKE '%1G%' AND sp.toc_do_downlink NOT LIKE '%10G%'";
            } elseif ($dlspeedFilter === 'MultiGigabit') {
                $where[] = "sp.toc_do_downlink LIKE '%mGig%' OR sp.toc_do_downlink LIKE '%Multi%'";
            } else {
                $where[] = "sp.toc_do_downlink LIKE :dls";
                $params[':dls'] = '%' . $dlspeedFilter . '%';
            }
        }

        // 3. Lọc theo PoE (Lọc theo tên loại PoE chính xác)
        if ($poeFilter) {
            // Tách chuỗi để lấy phần loại PoE (ví dụ: "PoE" từ "PoE (<=15W)")
            $parts = explode(' ', $poeFilter);
            $cleanVal = $parts[0]; // PoE, PoE+, UPoE, UPoE+
            
            if ($cleanVal === 'PoE') {
                $where[] = "sp.loai_poe = 'PoE'";
            } elseif ($cleanVal === 'PoE+') {
                $where[] = "sp.loai_poe = 'PoE+'";
            } else {
                $where[] = "sp.loai_poe LIKE :poe";
                $params[':poe'] = '%' . $cleanVal . '%';
            }
        }

        $whereStr = 'WHERE ' . implode(' AND ', $where);

        $orderBy = 'sp.noi_bat DESC, sp.thu_tu ASC, sp.ten ASC';

        // Đếm tổng
        $stmtCount = $pdo->prepare("
            SELECT COUNT(DISTINCT sp.id) FROM san_pham sp
            JOIN series s       ON sp.series_id     = s.id
            JOIN ho_san_pham h  ON s.ho_san_pham_id = h.id
            JOIN danh_muc dm    ON h.danh_muc_id    = dm.id
            $whereStr
        ");
        $stmtCount->execute($params);
        $tongSo    = (int)$stmtCount->fetchColumn();
        $tongTrang = (int)ceil($tongSo / $soTrenTrang);

        // Lấy sản phẩm
        $stmtSP = $pdo->prepare("
            SELECT DISTINCT sp.*,
                   s.ten  AS ten_series,
                   s.slug AS slug_series,
                   h.ten  AS ten_ho,
                   dm.ten AS ten_danh_muc
            FROM san_pham sp
            JOIN series s       ON sp.series_id     = s.id
            JOIN ho_san_pham h  ON s.ho_san_pham_id = h.id
            JOIN danh_muc dm    ON h.danh_muc_id    = dm.id
            $whereStr
            ORDER BY $orderBy
            LIMIT $soTrenTrang OFFSET $offset
        ");
        $stmtSP->execute($params);
        $sanPhamList = $stmtSP->fetchAll();
    }
} else {
    $danhMucHienTai = ['id' => 1, 'ten' => 'Switch', 'slug' => 'switch', 'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>', 'mo_ta' => 'Thiết bị chuyển mạch Cisco'];
    $seriesList = [];
    $sanPhamList = [];
}

// ============================================================
// HELPER: BUILD URL
// ============================================================
function buildUrl(array $override = []): string {
    $params = array_merge($_GET, $override);
    $filterKeys = ['series', 'q', 'scenario', 'interface', 'dlspeed', 'poe'];
    foreach ($filterKeys as $key) { if (array_key_exists($key, $override)) { $params['trang'] = 1; break; } }
    return '?' . http_build_query(array_filter($params, function($v) { return $v !== '' && (!is_array($v) || !empty($v)); }));
}

// ============================================================
// XỬ LÝ SEO ĐỘNG
// ============================================================
$seoParts = [];

// 1. Thêm Scenario (SMB, Enterprise...)
if ($scenarioFilter) {
    $scHT = array_values(array_filter($scenarioList, fn($sc) => $sc['slug'] === $scenarioFilter));
    if ($scHT) $seoParts[] = $scHT[0]['ten'];
}

// 2. Thêm Series (C9200, C9300...)
if ($seriesFilter) {
    $sHT = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $seriesFilter));
    if ($sHT) $seoParts[] = $sHT[0]['ten'];
}

// 3. Thêm thông số kỹ thuật
if ($interfaceFilter) $seoParts[] = $interfaceFilter . ' Ports';
if ($dlspeedFilter)   $seoParts[] = $dlspeedFilter;
if ($poeFilter) {
    $parts = explode(' ', $poeFilter);
    $seoParts[] = $parts[0]; // Chỉ lấy "PoE+" hoặc "UPoE" cho gọn
}

$dmTen = $danhMucHienTai['ten'] ?? 'Sản phẩm';
$filterStr = !empty($seoParts) ? ' ' . implode(', ', $seoParts) : '';

$tieuDeTrang = $dmTen . ' Cisco' . $filterStr . ' — CiscoVN';
$moTaTrang   = 'Danh sách ' . $dmTen . ' Cisco' . $filterStr . ' chính hãng tại Việt Nam. Hàng mới 100%, đầy đủ CO/CQ, giá tốt nhất thị trường.';

$navActive   = 'san-pham';
$cssExtra    = ['assets/css/danh-sach-san-pham.css'];
require_once 'includes/header.php';
?>

<!-- ===== BREADCRUMB ===== -->
<?php
$breadcrumbs = [['url' => 'danh-sach-san-pham.php', 'label' => 'Sản phẩm']];
if ($danhMucHienTai) { $breadcrumbs[] = ['url' => 'danh-sach-san-pham.php?danh_muc=' . $danhMucHienTai['slug'], 'label' => $danhMucHienTai['ten']]; }
if (!empty($seriesFilter) && count($seriesFilter) === 1 && $seriesList) {
    $sSlug = $seriesFilter[0];
    $sHT = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $sSlug));
    if ($sHT) { $breadcrumbs[] = ['url' => '', 'label' => $sHT[0]['ten']]; }
}
require_once 'includes/breadcrumb.php';
?>

<!-- ===== PAGE HEADER ===== -->
<div class="page-hero">
  <div class="container">
    <div class="page-hero__inner">
      <div class="page-hero__icon"><?= $danhMucHienTai['icon'] ?? '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>' ?></div>
      <div>
        <h1 class="page-hero__title">Cisco <?= lamsach($danhMucHienTai['ten'] ?? 'Sản phẩm') ?></h1>
        <p class="page-hero__desc"><?= lamsach($danhMucHienTai['mo_ta'] ?? '') ?> — Hàng chính hãng Cisco tại Việt Nam</p>
      </div>
    </div>
    <div class="cat-tabs">
      <?php
      $danhSachDM = $pdo ? $pdo->query("SELECT * FROM danh_muc WHERE hien_thi=1 ORDER BY thu_tu")->fetchAll() : [];
      foreach ($danhSachDM as $dm): ?>
      <a href="?danh_muc=<?= $dm['slug'] ?>" class="cat-tab <?= $dm['slug'] === $danhMucSlug ? 'active' : '' ?>">
        <span><?= $dm['icon'] ?></span><?= lamsach($dm['ten']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="catalog-layout">
  <div class="container catalog-layout__inner">
    <div class="catalog-main">

      <!-- ── BỘ LỌC SẢN PHẨM ── -->
      <section class="filter-section">
        <div class="filter-header" id="filterToggle">
          <div class="filter-header__title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            <span>Bộ lọc sản phẩm</span>
          </div>
          <button type="button" class="btn-filter-collapse">
            <span class="collapse-text">Thu gọn</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="chevron-icon"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>
        </div>

        <form id="filterForm" method="GET" action="" class="horizontal-filters collapsible-content">
          <input type="hidden" name="danh_muc" value="<?= lamsach($danhMucSlug) ?>" />
          <div class="h-filter-row">
            <?php if ($danhMucSlug === 'switch'): ?>
              <div class="filter-group">
                <label class="filter-group__label">Scenario:</label>
                <div class="filter-pills">
                  <?php foreach ($scenarioList as $sc): ?>
                  <label class="filter-pill">
                    <input type="radio" name="scenario" value="<?= $sc['slug'] ?>" <?= $sc['slug'] === $scenarioFilter ? 'checked' : '' ?>>
                    <span><?= lamsach($sc['ten']) ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">Downlink Ports:</label>
                <div class="filter-pills">
                  <?php foreach (['8-16', '24', '48'] as $opt): ?>
                  <label class="filter-pill">
                    <input type="radio" name="interface" value="<?= $opt ?>" <?= $opt === $interfaceFilter ? 'checked' : '' ?>>
                    <span><?= $opt ?> Ports</span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">Downlink Speed:</label>
                <div class="filter-pills">
                  <?php foreach (['1Gb copper', '1Gb SFP', 'MultiGigabit', '10Gb copper', '10Gb SFP+', '25Gb SFP28'] as $opt): ?>
                  <label class="filter-pill">
                    <input type="radio" name="dlspeed" value="<?= $opt ?>" <?= $opt === $dlspeedFilter ? 'checked' : '' ?>>
                    <span><?= $opt ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">POE:</label>
                <div class="filter-pills">
                  <?php foreach (['PoE (<=15W)', 'PoE+ (<=30W)', 'UPoE (<=60W)', 'UPoE+ (<=90W)'] as $opt): ?>
                  <label class="filter-pill">
                    <input type="radio" name="poe" value="<?= $opt ?>" <?= $opt === $poeFilter ? 'checked' : '' ?>>
                    <span><?= $opt ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </form>
      </section>

      <!-- Toolbar -->
      <div class="catalog-toolbar">
        <div class="catalog-toolbar__left">
          <p class="catalog-stats">
            <?php if ($tongSo > 0): ?>
              Hiển thị <strong><?= $offset + 1 ?>–<?= min($offset + $soTrenTrang, $tongSo) ?></strong> trong <strong><?= $tongSo ?></strong> sản phẩm
            <?php else: ?>
              Không tìm thấy sản phẩm nào
            <?php endif; ?>
          </p>
        </div>
      </div>

      <div id="ajax-container" data-total-pages="<?= $tongTrang ?>" data-current-page="<?= $trang ?>">
        <!-- Active filters -->
        <?php 
        $hasSpecs = $scenarioFilter || $interfaceFilter || $dlspeedFilter || $poeFilter;
        if ($seriesFilter || $hasSpecs): 
        ?>
        <div class="active-filters">
          <span class="active-filters__label">Đang lọc:</span>
          
          <?php if ($seriesFilter): 
              $sHT = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $seriesFilter));
              if ($sHT): ?><span class="active-filter-tag">Series: <?= lamsach($sHT[0]['ten']) ?><a href="<?= buildUrl(['series' => '']) ?>">×</a></span><?php endif; endif; ?>
          
          <?php if ($scenarioFilter): 
              $scHT = array_values(array_filter($scenarioList, fn($sc) => $sc['slug'] === $scenarioFilter));
              if ($scHT): ?><span class="active-filter-tag">Scenario: <?= lamsach($scHT[0]['ten']) ?><a href="<?= buildUrl(['scenario' => '']) ?>">×</a></span><?php endif; endif; ?>

          <?php if ($interfaceFilter): ?><span class="active-filter-tag"><?= lamsach($interfaceFilter) ?> Ports<a href="<?= buildUrl(['interface' => '']) ?>">×</a></span><?php endif; ?>
          <?php if ($dlspeedFilter): ?><span class="active-filter-tag"><?= lamsach($dlspeedFilter) ?><a href="<?= buildUrl(['dlspeed' => '']) ?>">×</a></span><?php endif; ?>
          <?php if ($poeFilter): ?><span class="active-filter-tag"><?= lamsach($poeFilter) ?><a href="<?= buildUrl(['poe' => '']) ?>">×</a></span><?php endif; ?>

          <a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="active-filters__clear">Xóa tất cả</a>
        </div>
        <?php endif; ?>

        <!-- Grid sản phẩm -->
        <?php if (empty($sanPhamList)): ?>
        <div class="empty-state"><div class="empty-state__icon">🔍</div><h3>Không tìm thấy sản phẩm</h3><p>Thử thay đổi bộ lọc</p><a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="btn btn--primary">Xem tất cả</a></div>
        <?php else: ?>
        <div class="prod-grid" id="prodGrid">
          <?php foreach ($sanPhamList as $sp): ?>
          <article class="prod-card">
            <div class="prod-card__img"><a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>"><img src="<?= $sp['anh_chinh'] ?: 'https://placehold.co/400x280?text=' . urlencode($sp['ma_san_pham']) ?>" alt="<?= lamsach($sp['ten']) ?>" loading="lazy" /></a></div>
            <div class="prod-card__body">
              <p class="prod-card__series"><?= lamsach($sp['ten_series'] ?? '') ?></p>
              <h3 class="prod-card__name"><a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>"><?= lamsach($sp['ten']) ?></a></h3>
              
              <p class="prod-card__desc"><?= lamsach($sp['mo_ta_ngan']) ?></p>
              <div class="prod-card__foot"><a href="#" data-modal="lien-he" data-sp-ma="<?= lamsach($sp['ma_san_pham']) ?>" class="btn btn--primary btn--sm">Liên hệ báo giá</a></div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <!-- Sentinel for Infinite Scroll -->
        <div id="infinite-sentinel" style="height: 50px; margin-top: 20px;"></div>
        <div id="infinite-loader" style="display: none; text-align: center; padding: 20px; color: var(--gray-500); font-weight: 600;">
           Đang tải thêm sản phẩm...
        </div>
        <?php endif; ?>
      </div><!-- End #ajax-container -->

    </div>
  </div>
</div>

<?php $jsExtra = ['assets/js/danh-sach-san-pham.js']; require_once 'includes/footer.php'; ?>
