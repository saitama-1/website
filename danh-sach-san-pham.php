<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// ============================================================
// XỬ LÝ THAM SỐ URL
// ============================================================
$danhMucSlug  = isset($_GET['danh_muc'])  ? lamsach($_GET['danh_muc'])  : 'switch';
$seriesFilter = isset($_GET['series'])    ? (array)$_GET['series']      : [];
$sapXep       = isset($_GET['sap_xep'])   ? lamsach($_GET['sap_xep'])   : 'mac-dinh';
$trang        = isset($_GET['trang'])     ? max(1, (int)$_GET['trang']) : 1;

// Các tham số lọc đặc thù Switch
$scenarioFilter  = isset($_GET['scenario'])  ? (array)$_GET['scenario']  : [];
$interfaceFilter = isset($_GET['interface']) ? (array)$_GET['interface'] : [];
$dlspeedFilter   = isset($_GET['dlspeed'])   ? (array)$_GET['dlspeed']   : [];
$poeFilter       = isset($_GET['poe'])       ? (array)$_GET['poe']       : [];

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

        // Build WHERE
        $where  = ['sp.hien_thi = 1', 'dm.id = :dm_id'];
        $params = [':dm_id' => $dmId];

        if (!empty($seriesFilter)) {
            $sPlaceholders = [];
            foreach ($seriesFilter as $i => $sSlug) {
                $pName = ":s_" . $i;
                $sPlaceholders[] = $pName;
                $params[$pName] = $sSlug;
            }
            $where[] = "s.slug IN (" . implode(',', $sPlaceholders) . ")";
        }

        // --- Lọc theo thông số kỹ thuật (Switch) ---
        $specFilters = [
            'Scenario'           => $scenarioFilter,
            'Downlink interface' => $interfaceFilter,
            'Downlink speed'     => $dlspeedFilter,
            'PoE'                => $poeFilter
        ];

        foreach ($specFilters as $specName => $values) {
            if (!empty($values)) {
                $placeholders = [];
                foreach ($values as $i => $v) {
                    $pName = ":" . str_replace(' ', '_', $specName) . "_" . $i;
                    $placeholders[] = $pName;
                    $params[$pName] = $v;
                }
                $where[] = "sp.id IN (
                    SELECT san_pham_id FROM thong_so_ky_thuat 
                    WHERE ten = '$specName' AND gia_tri IN (" . implode(',', $placeholders) . ")
                )";
            }
        }

        $whereStr = 'WHERE ' . implode(' AND ', $where);

        $orderBy = match($sapXep) {
            'ten-az'  => 'sp.ten ASC',
            'ten-za'  => 'sp.ten DESC',
            'moi-nhat'=> 'sp.tao_luc DESC',
            default   => 'sp.noi_bat DESC, sp.thu_tu ASC, sp.ten ASC',
        };

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
            LIMIT :limit OFFSET :offset
        ");
        $stmtSP->execute(array_merge($params, [
            ':limit'  => $soTrenTrang,
            ':offset' => $offset,
        ]));
        $sanPhamList = $stmtSP->fetchAll();
    }
} else {
    $danhMucHienTai = ['id' => 1, 'ten' => 'Switch', 'slug' => 'switch', 'icon' => '🔀', 'mo_ta' => 'Thiết bị chuyển mạch Cisco'];
    $seriesList = [];
    $sanPhamList = [];
}

// ============================================================
// HELPER: BUILD URL
// ============================================================
function buildUrl(array $override = []): string {
    $params = array_merge($_GET, $override);
    $filterKeys = ['series', 'q', 'sap_xep', 'scenario', 'interface', 'dlspeed', 'poe'];
    foreach ($filterKeys as $key) { if (array_key_exists($key, $override)) { $params['trang'] = 1; break; } }
    return '?' . http_build_query(array_filter($params, function($v) { return $v !== '' && (!is_array($v) || !empty($v)); }));
}

$tieuDeTrang = ($danhMucHienTai['ten'] ?? 'Sản phẩm') . ' — CiscoVN';
$moTaTrang   = 'Danh sách ' . ($danhMucHienTai['ten'] ?? '') . ' Cisco chính hãng tại Việt Nam';
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
      <div class="page-hero__icon"><?= $danhMucHienTai['icon'] ?? '📦' ?></div>
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
            <?php if (!empty($seriesList)): ?>
            <div class="filter-group">
              <label class="filter-group__label">Dòng sản phẩm:</label>
              <div class="filter-pills">
                <?php foreach ($seriesList as $s): ?>
                <label class="filter-pill">
                  <input type="checkbox" name="series[]" value="<?= $s['slug'] ?>" <?= in_array($s['slug'], $seriesFilter) ? 'checked' : '' ?>>
                  <span><?= lamsach($s['ten']) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if ($danhMucSlug === 'switch'): ?>
              <div class="filter-group">
                <label class="filter-group__label">Scenario:</label>
                <div class="filter-pills">
                  <?php foreach (['SMB', 'Enterprise', 'Data Center', 'Industrial'] as $opt): ?>
                  <label class="filter-pill"><input type="checkbox" name="scenario[]" value="<?= $opt ?>" <?= in_array($opt, $scenarioFilter) ? 'checked' : '' ?>><span><?= $opt ?></span></label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">Downlink Ports:</label>
                <div class="filter-pills">
                  <?php foreach (['<24', '24-48', '>48'] as $opt): ?>
                  <label class="filter-pill"><input type="checkbox" name="interface[]" value="<?= $opt ?>" <?= in_array($opt, $interfaceFilter) ? 'checked' : '' ?>><span><?= $opt ?></span></label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">Downlink Speed:</label>
                <div class="filter-pills">
                  <?php foreach (['1Gb copper', '1Gb SFP', 'MultiGigabit', '10Gb copper', '10Gb SFP+', '25Gb SFP28'] as $opt): ?>
                  <label class="filter-pill"><input type="checkbox" name="dlspeed[]" value="<?= $opt ?>" <?= in_array($opt, $dlspeedFilter) ? 'checked' : '' ?>><span><?= $opt ?></span></label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="filter-group">
                <label class="filter-group__label">POE:</label>
                <div class="filter-pills">
                  <?php foreach (['PoE(<=15W)', 'PoE+ (<=30W)', 'UPoE (<=60W)', 'UPoE+ (<=90W)'] as $opt): ?>
                  <label class="filter-pill"><input type="checkbox" name="poe[]" value="<?= $opt ?>" <?= in_array($opt, $poeFilter) ? 'checked' : '' ?>><span><?= $opt ?></span></label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($seriesFilter) || !empty($scenarioFilter) || !empty($interfaceFilter) || !empty($dlspeedFilter) || !empty($poeFilter)): ?>
            <a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="btn-clear-all">Xóa lọc</a>
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

      <!-- Active filters -->
      <?php 
      $hasSpecs = !empty($scenarioFilter) || !empty($interfaceFilter) || !empty($dlspeedFilter) || !empty($poeFilter);
      if (!empty($seriesFilter) || $hasSpecs): 
      ?>
      <div class="active-filters">
        <span class="active-filters__label">Đang lọc:</span>
        <?php if (!empty($seriesFilter)): foreach ($seriesFilter as $sSlug): 
            $sHT = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $sSlug));
            if ($sHT): ?><span class="active-filter-tag">Series: <?= lamsach($sHT[0]['ten']) ?><a href="<?= buildUrl(['series' => array_diff($seriesFilter, [$sSlug])]) ?>">×</a></span><?php endif; endforeach; endif; ?>
        <?php
        $allSpecTags = ['scenario' => $scenarioFilter, 'interface' => $interfaceFilter, 'dlspeed' => $dlspeedFilter, 'poe' => $poeFilter];
        foreach ($allSpecTags as $key => $vals): foreach ($vals as $v): ?>
          <span class="active-filter-tag"><?= lamsach($v) ?><a href="<?= buildUrl([$key => array_diff($vals, [$v])]) ?>">×</a></span>
        <?php endforeach; endforeach; ?>
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
            <span class="prod-card__code"><?= lamsach($sp['ma_san_pham']) ?></span>
            <p class="prod-card__desc"><?= lamsach($sp['mo_ta_ngan']) ?></p>
            <div class="prod-card__foot"><a href="#" data-modal="lien-he" data-sp-ma="<?= lamsach($sp['ma_san_pham']) ?>" class="btn btn--primary btn--sm">Liên hệ báo giá</a></div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <!-- PHÂN TRANG -->
      <?php if ($tongTrang > 1): ?>
      <nav class="pagination">
        <?php if ($trang > 1): ?><a href="<?= buildUrl(['trang' => $trang - 1]) ?>" class="page-btn page-btn--arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></a>
        <?php else: ?><span class="page-btn page-btn--arrow page-btn--disabled"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></span><?php endif; ?>
        <?php for ($i = 1; $i <= $tongTrang; $i++): ?>
        <a href="<?= buildUrl(['trang' => $i]) ?>" class="page-btn <?= $i === $trang ? 'page-btn--active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($trang < $tongTrang): ?><a href="<?= buildUrl(['trang' => $trang + 1]) ?>" class="page-btn page-btn--arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg></a>
        <?php else: ?><span class="page-btn page-btn--arrow page-btn--disabled"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg></span><?php endif; ?>
        <div class="page-jump"><span>Đến trang</span><input type="number" id="pageJumpInput" min="1" max="<?= $tongTrang ?>" value="<?= $trang ?>" /><button onclick="jumpToPage(<?= $tongTrang ?>)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button></div>
      </nav>
      <p class="pagination-info">Trang <strong><?= $trang ?></strong> / <strong><?= $tongTrang ?></strong></p>
      <?php endif; ?>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php $jsExtra = ['assets/js/danh-sach-san-pham.js']; require_once 'includes/footer.php'; ?>
