<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// ============================================================
// XỬ LÝ THAM SỐ URL
// ============================================================
$danhMucSlug  = isset($_GET['danh_muc'])  ? lamsach($_GET['danh_muc'])  : 'switch';
$seriesSlug   = isset($_GET['series'])    ? lamsach($_GET['series'])    : '';
$tuKhoa       = isset($_GET['q'])         ? lamsach($_GET['q'])         : '';
$sapXep       = isset($_GET['sap_xep'])   ? lamsach($_GET['sap_xep'])   : 'mac-dinh';
$trang        = isset($_GET['trang'])     ? max(1, (int)$_GET['trang']) : 1;
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

        // Lấy danh sách series của danh mục này (để lọc)
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

        if ($seriesSlug) {
            $where[]           = 's.slug = :series_slug';
            $params[':series_slug'] = $seriesSlug;
        }
        if ($tuKhoa) {
            $where[]           = '(sp.ten LIKE :q OR sp.ma_san_pham LIKE :q2)';
            $params[':q']      = "%$tuKhoa%";
            $params[':q2']     = "%$tuKhoa%";
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
            SELECT COUNT(*) FROM san_pham sp
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
            SELECT sp.*,
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
    // ─── DATA MẪU (khi chưa có CSDL) ───────────────────────
    $danhMucHienTai = [
        'id' => 1, 'ten' => 'Switch', 'slug' => 'switch',
        'icon' => '🔀', 'mo_ta' => 'Thiết bị chuyển mạch Cisco'
    ];

    $seriesList = [
        ['id'=>1, 'ten'=>'Catalyst 9200 Series', 'slug'=>'cisco-catalyst-9200-series', 'so_san_pham'=>8],
        ['id'=>2, 'ten'=>'Catalyst 9300 Series', 'slug'=>'cisco-catalyst-9300-series', 'so_san_pham'=>5],
        ['id'=>3, 'ten'=>'Catalyst 9400 Series', 'slug'=>'cisco-catalyst-9400-series', 'so_san_pham'=>3],
        ['id'=>4, 'ten'=>'Catalyst 9500 Series', 'slug'=>'cisco-catalyst-9500-series', 'so_san_pham'=>4],
        ['id'=>5, 'ten'=>'Catalyst 2960-X Series','slug'=>'cisco-catalyst-2960-x-series','so_san_pham'=>4],
    ];

    $allSanPham = [
        // Catalyst 9200
        ['id'=>1,  'ten'=>'Cisco Catalyst C9200-24T-A',    'ma_san_pham'=>'C9200-24T-A',    'slug'=>'cisco-catalyst-c9200-24t-a',    'ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'24 cổng GE, 4 SFP uplink, không PoE, StackWise-80, license Advantage',    'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>1],
        ['id'=>2,  'ten'=>'Cisco Catalyst C9200-48T-A',    'ma_san_pham'=>'C9200-48T-A',    'slug'=>'cisco-catalyst-c9200-48t-a',    'ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'48 cổng GE, 4 SFP uplink, không PoE, StackWise-80, license Advantage',    'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>1],
        ['id'=>3,  'ten'=>'Cisco Catalyst C9200-24P-A',    'ma_san_pham'=>'C9200-24P-A',    'slug'=>'cisco-catalyst-c9200-24p-a',    'ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'24 cổng GE PoE+, 4 SFP uplink, 370W, StackWise-80, license Advantage',   'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>4,  'ten'=>'Cisco Catalyst C9200-48P-A',    'ma_san_pham'=>'C9200-48P-A',    'slug'=>'cisco-catalyst-c9200-48p-a',    'ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'48 cổng GE PoE+, 4 SFP uplink, 740W, StackWise-80, license Advantage',   'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>5,  'ten'=>'Cisco Catalyst C9200L-24T-4G-A','ma_san_pham'=>'C9200L-24T-4G-A','slug'=>'cisco-catalyst-c9200l-24t-4g-a','ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'24 cổng GE, 4 SFP uplink fixed, không PoE, license Advantage',            'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>6,  'ten'=>'Cisco Catalyst C9200L-48T-4G-A','ma_san_pham'=>'C9200L-48T-4G-A','slug'=>'cisco-catalyst-c9200l-48t-4g-a','ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'48 cổng GE, 4 SFP uplink fixed, không PoE, license Advantage',            'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>7,  'ten'=>'Cisco Catalyst C9200L-24P-4G-A','ma_san_pham'=>'C9200L-24P-4G-A','slug'=>'cisco-catalyst-c9200l-24p-4g-a','ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'24 cổng GE PoE+, 4 SFP uplink fixed, 195W, license Advantage',           'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>8,  'ten'=>'Cisco Catalyst C9200L-48P-4G-A','ma_san_pham'=>'C9200L-48P-4G-A','slug'=>'cisco-catalyst-c9200l-48p-4g-a','ten_series'=>'Catalyst 9200 Series', 'slug_series'=>'cisco-catalyst-9200-series', 'mo_ta_ngan'=>'48 cổng GE PoE+, 4 SFP uplink fixed, 370W, license Advantage',           'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        // Catalyst 9300
        ['id'=>9,  'ten'=>'Cisco Catalyst C9300-24T-A',    'ma_san_pham'=>'C9300-24T-A',    'slug'=>'cisco-catalyst-c9300-24t-a',    'ten_series'=>'Catalyst 9300 Series', 'slug_series'=>'cisco-catalyst-9300-series', 'mo_ta_ngan'=>'24 cổng GE, 4x1G SFP, không PoE, StackWise-480, license Advantage',       'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>1],
        ['id'=>10, 'ten'=>'Cisco Catalyst C9300-48T-A',    'ma_san_pham'=>'C9300-48T-A',    'slug'=>'cisco-catalyst-c9300-48t-a',    'ten_series'=>'Catalyst 9300 Series', 'slug_series'=>'cisco-catalyst-9300-series', 'mo_ta_ngan'=>'48 cổng GE, 4x1G SFP, không PoE, StackWise-480, license Advantage',       'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>11, 'ten'=>'Cisco Catalyst C9300-24P-A',    'ma_san_pham'=>'C9300-24P-A',    'slug'=>'cisco-catalyst-c9300-24p-a',    'ten_series'=>'Catalyst 9300 Series', 'slug_series'=>'cisco-catalyst-9300-series', 'mo_ta_ngan'=>'24 cổng GE PoE+, 4x1G SFP, 445W, StackWise-480, license Advantage',      'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>12, 'ten'=>'Cisco Catalyst C9300-48P-A',    'ma_san_pham'=>'C9300-48P-A',    'slug'=>'cisco-catalyst-c9300-48p-a',    'ten_series'=>'Catalyst 9300 Series', 'slug_series'=>'cisco-catalyst-9300-series', 'mo_ta_ngan'=>'48 cổng GE PoE+, 4x1G SFP, 890W, StackWise-480, license Advantage',      'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>13, 'ten'=>'Cisco Catalyst C9300-48UXM-A',  'ma_san_pham'=>'C9300-48UXM-A',  'slug'=>'cisco-catalyst-c9300-48uxm-a',  'ten_series'=>'Catalyst 9300 Series', 'slug_series'=>'cisco-catalyst-9300-series', 'mo_ta_ngan'=>'48 cổng mGig UPOE+, 4x25G SFP28, 1480W, StackWise-480',                  'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        // Catalyst 2960-X
        ['id'=>14, 'ten'=>'Cisco WS-C2960X-24TS-L',        'ma_san_pham'=>'WS-C2960X-24TS-L', 'slug'=>'cisco-catalyst-ws-c2960x-24ts-l',  'ten_series'=>'Catalyst 2960-X Series', 'slug_series'=>'cisco-catalyst-2960-x-series', 'mo_ta_ngan'=>'24 cổng GE, 4 SFP uplink, LAN Base, không PoE, FlexStack-Plus',  'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>15, 'ten'=>'Cisco WS-C2960X-48TS-L',        'ma_san_pham'=>'WS-C2960X-48TS-L', 'slug'=>'cisco-catalyst-ws-c2960x-48ts-l',  'ten_series'=>'Catalyst 2960-X Series', 'slug_series'=>'cisco-catalyst-2960-x-series', 'mo_ta_ngan'=>'48 cổng GE, 4 SFP uplink, LAN Base, không PoE, FlexStack-Plus',  'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>16, 'ten'=>'Cisco WS-C2960X-24PS-L',        'ma_san_pham'=>'WS-C2960X-24PS-L', 'slug'=>'cisco-catalyst-ws-c2960x-24ps-l',  'ten_series'=>'Catalyst 2960-X Series', 'slug_series'=>'cisco-catalyst-2960-x-series', 'mo_ta_ngan'=>'24 cổng GE PoE+, 4 SFP uplink, LAN Base, 370W, FlexStack-Plus', 'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
        ['id'=>17, 'ten'=>'Cisco WS-C2960X-48FPS-L',       'ma_san_pham'=>'WS-C2960X-48FPS-L','slug'=>'cisco-catalyst-ws-c2960x-48fps-l', 'ten_series'=>'Catalyst 2960-X Series', 'slug_series'=>'cisco-catalyst-2960-x-series', 'mo_ta_ngan'=>'48 cổng GE PoE+, 4 SFP uplink, LAN Base, 740W, FlexStack-Plus', 'anh_chinh'=>null, 'tinh_trang'=>'lien_he', 'noi_bat'=>0],
    ];

    // Lọc theo series
    if ($seriesSlug) {
        $allSanPham = array_filter($allSanPham, fn($sp) => $sp['slug_series'] === $seriesSlug);
        $allSanPham = array_values($allSanPham);
    }

    // Lọc theo từ khóa
    if ($tuKhoa) {
        $allSanPham = array_filter($allSanPham, fn($sp) =>
            str_contains(mb_strtolower($sp['ten']),          mb_strtolower($tuKhoa)) ||
            str_contains(mb_strtolower($sp['ma_san_pham']), mb_strtolower($tuKhoa))
        );
        $allSanPham = array_values($allSanPham);
    }

    // Sắp xếp
    usort($allSanPham, function($a, $b) use ($sapXep) {
        return match($sapXep) {
            'ten-az'  => strcmp($a['ten'], $b['ten']),
            'ten-za'  => strcmp($b['ten'], $a['ten']),
            default   => ($b['noi_bat'] - $a['noi_bat']) ?: strcmp($a['ten'], $b['ten']),
        };
    });

    $tongSo    = count($allSanPham);
    $tongTrang = (int)ceil($tongSo / $soTrenTrang);
    $sanPhamList = array_slice($allSanPham, $offset, $soTrenTrang);
}

// ============================================================
// HELPER: BUILD URL GIỮ NGUYÊN THAM SỐ HIỆN TẠI
// ============================================================
function buildUrl(array $override = []): string {
    $params = array_merge($_GET, $override);
    // Reset trang về 1 khi thay đổi filter
    if (array_key_exists('series', $override) || array_key_exists('q', $override) || array_key_exists('sap_xep', $override)) {
        $params['trang'] = 1;
    }
    return '?' . http_build_query(array_filter($params, fn($v) => $v !== ''));
}

// Cấu hình header
$tieuDeTrang = ($danhMucHienTai['ten'] ?? 'Sản phẩm') . ' — CiscoVN';
$moTaTrang   = 'Danh sách ' . ($danhMucHienTai['ten'] ?? '') . ' Cisco chính hãng tại Việt Nam';
$navActive   = 'san-pham';
$cssExtra    = ['assets/css/danh-sach-san-pham.css'];
require_once 'includes/header.php';
?>

<!-- ===== BREADCRUMB ===== -->
<?php
$breadcrumbs = [
    ['url' => 'danh-sach-san-pham.php', 'label' => 'Sản phẩm']
];
if ($danhMucHienTai) {
    $breadcrumbs[] = ['url' => 'danh-sach-san-pham.php?danh_muc=' . $danhMucHienTai['slug'], 'label' => $danhMucHienTai['ten']];
}
if ($seriesSlug && $seriesList) {
    $seriesHienTai = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $seriesSlug));
    if ($seriesHienTai) {
        $breadcrumbs[] = ['url' => '', 'label' => $seriesHienTai[0]['ten']];
    }
}
require_once 'includes/breadcrumb.php';
?>

<!-- ===== PAGE HEADER ===== -->
<div class="page-hero">
  <div class="container">
    <div class="page-hero__inner">
      <div class="page-hero__icon"><?= $danhMucHienTai['icon'] ?? '📦' ?></div>
      <div>
        <h1 class="page-hero__title">
          Cisco <?= lamsach($danhMucHienTai['ten'] ?? 'Sản phẩm') ?>
        </h1>
        <p class="page-hero__desc">
          <?= lamsach($danhMucHienTai['mo_ta'] ?? '') ?>
          — Hàng chính hãng, bảo hành theo chính sách Cisco
        </p>
      </div>
    </div>

    <!-- Tab danh mục nhanh -->
    <div class="cat-tabs">
      <?php
      $danhSachDM = $pdo
        ? $pdo->query("SELECT * FROM danh_muc WHERE hien_thi=1 ORDER BY thu_tu")->fetchAll()
        : [
            ['slug'=>'switch',       'ten'=>'Switch',       'icon'=>'🔀'],
            ['slug'=>'router',       'ten'=>'Router',       'icon'=>'🌐'],
            ['slug'=>'firewall',     'ten'=>'Firewall',     'icon'=>'🔒'],
            ['slug'=>'wireless',     'ten'=>'Wireless',     'icon'=>'📶'],
            ['slug'=>'module-quang', 'ten'=>'Module quang', 'icon'=>'💡'],
            ['slug'=>'ip-phone',     'ten'=>'IP Phone',     'icon'=>'📞'],
          ];
      ?>
      <?php foreach ($danhSachDM as $dm): ?>
      <a href="?danh_muc=<?= $dm['slug'] ?>"
         class="cat-tab <?= $dm['slug'] === $danhMucSlug ? 'active' : '' ?>">
        <span><?= $dm['icon'] ?></span>
        <?= lamsach($dm['ten']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== NỘI DUNG CHÍNH ===== -->
<div class="catalog-layout">
  <div class="container catalog-layout__inner">

    <!-- ── SIDEBAR LỌC ── -->
    <aside class="filter-sidebar" id="filterSidebar">
      <div class="filter-sidebar__header">
        <h3>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Lọc sản phẩm
        </h3>
        <button class="filter-sidebar__close" id="closeSidebar" aria-label="Đóng">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="filterForm" method="GET" action="">
        <input type="hidden" name="danh_muc" value="<?= lamsach($danhMucSlug) ?>" />

        <!-- Tìm kiếm -->
        <div class="filter-group">
          <label class="filter-group__label">Tìm theo tên / mã sản phẩm</label>
          <div class="filter-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="<?= lamsach($tuKhoa) ?>"
                   placeholder="VD: C9200-24T, 9300..." />
            <?php if ($tuKhoa): ?>
            <a href="<?= buildUrl(['q'=>'']) ?>" class="filter-search__clear" title="Xóa">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            <?php endif; ?>
          </div>
        </div>

        <!-- Lọc theo Series -->
        <?php if (!empty($seriesList)): ?>
        <div class="filter-group">
          <label class="filter-group__label">Dòng sản phẩm (Series)</label>
          <div class="filter-options">
            <a href="<?= buildUrl(['series'=>'']) ?>"
               class="filter-option <?= !$seriesSlug ? 'active' : '' ?>">
              Tất cả
              <span class="filter-option__count"><?= array_sum(array_column($seriesList, 'so_san_pham')) ?></span>
            </a>
            <?php foreach ($seriesList as $s): ?>
            <a href="<?= buildUrl(['series' => $s['slug']]) ?>"
               class="filter-option <?= $seriesSlug === $s['slug'] ? 'active' : '' ?>">
              <?= lamsach($s['ten']) ?>
              <span class="filter-option__count"><?= $s['so_san_pham'] ?></span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Sắp xếp -->
        <div class="filter-group">
          <label class="filter-group__label">Sắp xếp theo</label>
          <div class="filter-options">
            <?php
            $sortOptions = [
              'mac-dinh' => 'Mặc định',
              'ten-az'   => 'Tên A → Z',
              'ten-za'   => 'Tên Z → A',
              'moi-nhat' => 'Mới nhất',
            ];
            foreach ($sortOptions as $val => $label): ?>
            <a href="<?= buildUrl(['sap_xep' => $val]) ?>"
               class="filter-option <?= $sapXep === $val ? 'active' : '' ?>">
              <?= $label ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Nút reset -->
        <?php if ($seriesSlug || $tuKhoa || $sapXep !== 'mac-dinh'): ?>
        <a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="filter-reset">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
          Xóa bộ lọc
        </a>
        <?php endif; ?>
      </form>
    </aside>

    <!-- ── VÙNG SẢN PHẨM ── -->
    <div class="catalog-main">

      <!-- Toolbar -->
      <div class="catalog-toolbar">
        <div class="catalog-toolbar__left">
          <!-- Nút mở sidebar mobile -->
          <button class="btn-filter-toggle" id="openSidebar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Lọc
            <?php if ($seriesSlug || $tuKhoa): ?>
            <span class="filter-badge">!</span>
            <?php endif; ?>
          </button>

          <p class="catalog-stats">
            <?php if ($tongSo > 0): ?>
              Hiển thị <strong><?= $offset + 1 ?>–<?= min($offset + $soTrenTrang, $tongSo) ?></strong>
              trong <strong><?= $tongSo ?></strong> sản phẩm
              <?php if ($tuKhoa): ?>
                — kết quả cho "<em><?= lamsach($tuKhoa) ?></em>"
              <?php endif; ?>
            <?php else: ?>
              Không tìm thấy sản phẩm nào
            <?php endif; ?>
          </p>
        </div>

        <!-- Sort dropdown (desktop) -->
        <div class="sort-select-wrap">
          <label>Sắp xếp:</label>
          <select id="sortSelect" onchange="location.href=this.value">
            <?php foreach ($sortOptions as $val => $label): ?>
            <option value="<?= buildUrl(['sap_xep' => $val]) ?>"
                    <?= $sapXep === $val ? 'selected' : '' ?>>
              <?= $label ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Active filters -->
      <?php if ($seriesSlug || $tuKhoa): ?>
      <div class="active-filters">
        <span class="active-filters__label">Đang lọc:</span>
        <?php if ($tuKhoa): ?>
        <span class="active-filter-tag">
          Từ khóa: "<?= lamsach($tuKhoa) ?>"
          <a href="<?= buildUrl(['q'=>'']) ?>">×</a>
        </span>
        <?php endif; ?>
        <?php if ($seriesSlug): ?>
          <?php $seriesHT = array_values(array_filter($seriesList, fn($s) => $s['slug'] === $seriesSlug)); ?>
          <?php if ($seriesHT): ?>
          <span class="active-filter-tag">
            Series: <?= lamsach($seriesHT[0]['ten']) ?>
            <a href="<?= buildUrl(['series'=>'']) ?>">×</a>
          </span>
          <?php endif; ?>
        <?php endif; ?>
        <a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="active-filters__clear">Xóa tất cả</a>
      </div>
      <?php endif; ?>

      <!-- Grid sản phẩm -->
      <?php if (empty($sanPhamList)): ?>
      <div class="empty-state">
        <div class="empty-state__icon">🔍</div>
        <h3>Không tìm thấy sản phẩm</h3>
        <p>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
        <a href="?danh_muc=<?= lamsach($danhMucSlug) ?>" class="btn btn--primary">
          Xem tất cả <?= lamsach($danhMucHienTai['ten'] ?? '') ?>
        </a>
      </div>

      <?php else: ?>
      <div class="prod-grid" id="prodGrid">
        <?php foreach ($sanPhamList as $sp): ?>
        <article class="prod-card">
          <div class="prod-card__img">
            <a href="san-pham-chi-tiet.php?slug=<?= lamsach($sp['slug']) ?>">
              <img
                src="<?= $sp['anh_chinh'] ?: 'https://placehold.co/400x280/e8f4ff/005073?text=' . urlencode($sp['ma_san_pham']) ?>"
                alt="<?= lamsach($sp['ten']) ?>"
                loading="lazy"
                onerror="this.src='https://placehold.co/400x280/e8f4ff/005073?text=No+Image'" />
            </a>
            <?php if (!empty($sp['noi_bat'])): ?>
            <div class="prod-card__badges">
              <span class="badge-hot">Nổi bật</span>
            </div>
            <?php endif; ?>
          </div>

          <div class="prod-card__body">
            <p class="prod-card__series">
              <?= lamsach($sp['ten_series'] ?? '') ?>
            </p>
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

      <!-- ===== PHÂN TRANG ===== -->
      <?php if ($tongTrang > 1): ?>
      <nav class="pagination" aria-label="Phân trang">
        <!-- Prev -->
        <?php if ($trang > 1): ?>
        <a href="<?= buildUrl(['trang' => $trang - 1]) ?>" class="page-btn page-btn--arrow" aria-label="Trang trước">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
        <?php else: ?>
        <span class="page-btn page-btn--arrow page-btn--disabled">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </span>
        <?php endif; ?>

        <!-- Số trang -->
        <?php
        // Tính range trang hiển thị (luôn hiện 5 trang)
        $start = max(1, $trang - 2);
        $end   = min($tongTrang, $trang + 2);
        if ($end - $start < 4) {
            if ($start === 1) $end   = min($tongTrang, $start + 4);
            else               $start = max(1, $end - 4);
        }
        ?>

        <?php if ($start > 1): ?>
          <a href="<?= buildUrl(['trang' => 1]) ?>" class="page-btn">1</a>
          <?php if ($start > 2): ?><span class="page-ellipsis">…</span><?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
        <a href="<?= buildUrl(['trang' => $i]) ?>"
           class="page-btn <?= $i === $trang ? 'page-btn--active' : '' ?>"
           <?= $i === $trang ? 'aria-current="page"' : '' ?>>
          <?= $i ?>
        </a>
        <?php endfor; ?>

        <?php if ($end < $tongTrang): ?>
          <?php if ($end < $tongTrang - 1): ?><span class="page-ellipsis">…</span><?php endif; ?>
          <a href="<?= buildUrl(['trang' => $tongTrang]) ?>" class="page-btn"><?= $tongTrang ?></a>
        <?php endif; ?>

        <!-- Next -->
        <?php if ($trang < $tongTrang): ?>
        <a href="<?= buildUrl(['trang' => $trang + 1]) ?>" class="page-btn page-btn--arrow" aria-label="Trang sau">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <?php else: ?>
        <span class="page-btn page-btn--arrow page-btn--disabled">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
        </span>
        <?php endif; ?>

        <!-- Jump to page -->
        <div class="page-jump">
          <span>Đến trang</span>
          <input type="number" id="pageJumpInput" min="1" max="<?= $tongTrang ?>"
                 value="<?= $trang ?>" />
          <button onclick="jumpToPage(<?= $tongTrang ?>)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </nav>

      <p class="pagination-info">
        Trang <strong><?= $trang ?></strong> / <strong><?= $tongTrang ?></strong>
        &nbsp;·&nbsp; Tổng <strong><?= $tongSo ?></strong> sản phẩm
      </p>
      <?php endif; ?>
      <?php endif; ?>

    </div><!-- end catalog-main -->
  </div><!-- end catalog-layout__inner -->
</div><!-- end catalog-layout -->

<!-- Overlay sidebar mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== FOOTER ===== -->

<?php
// Cấu hình footer
$jsExtra = ['assets/js/danh-sach-san-pham.js'];
require_once 'includes/footer.php';
?>
