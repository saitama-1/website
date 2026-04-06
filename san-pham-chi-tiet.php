<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/config.php';

// ============================================================
// LẤY DỮ LIỆU SẢN PHẨM (TODO: query từ CSDL theo $slug)
// $slug = isset($_GET['slug']) ? lamsach($_GET['slug']) : '';
// $sanPham = ... query ...
// ============================================================

// Cấu hình header
$tieuDeTrang = 'Cisco Catalyst C9200-24T-A — CiscoVN Network Solutions';
$moTaTrang   = 'Cisco Catalyst C9200-24T-A — 24-port GE Data Switch, Network Advantage, StackWise-160';
$navActive   = 'san-pham';
$cssExtra    = ['assets/css/product.css'];
require_once 'includes/header.php';
?>

<!-- BREADCRUMB -->
<?php
// TODO: Điền dữ liệu thực tế từ biến $sanPham vào label và url khi đã kết nối CSDL
$breadcrumbs = [
    ['url' => 'danh-sach-san-pham.php', 'label' => 'Sản phẩm'],
    ['url' => 'danh-sach-san-pham.php?danh_muc=switch', 'label' => 'Switch'],
    ['url' => '#', 'label' => 'Catalyst 9200 Series'],
    ['url' => '', 'label' => 'C9200-24T-A']
];
require_once 'includes/breadcrumb.php';
?>

<!-- ========== PRODUCT MAIN ========== -->
<section class="prod-main">
  <div class="container">
    <div class="prod-main__layout">

      <!-- CỘT TRÁI: GALLERY -->
      <div class="prod-gallery">
        <!-- Ảnh lớn + zoom -->
        <div class="gallery__main-wrap" id="galleryMainWrap">
          <div class="gallery__main" id="galleryMain">
            <img
              src="https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-4.jpg"
              alt="Cisco Catalyst C9200-24T-A — Mặt trước"
              id="mainImg" draggable="false"
              onerror="this.src='https://placehold.co/600x480/e8f4ff/005073?text=Cisco+C9200-24T-A'" />
            <div class="zoom-lens" id="zoomLens"></div>
          </div>
          <div class="zoom-preview" id="zoomPreview">
            <img
              src="https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-4.jpg"
              id="zoomPreviewImg" alt="Zoom preview"
              onerror="this.src='https://placehold.co/600x480/e8f4ff/005073?text=Zoom'" />
          </div>
        </div>

        <!-- Thumbnails — JS tự render từ GALLERY_IMAGES -->
        <div class="gallery__thumbs" id="galleryThumbs"></div>
      </div>

      <!-- CỘT PHẢI: THÔNG TIN -->
      <div class="prod-info">

        <!-- Badges -->
        <div class="prod-info__badges">
          <span class="pi-badge pi-badge--cat">Switch</span>
          <span class="pi-badge pi-badge--series">Catalyst 9200 Series</span>
          <span class="pi-badge pi-badge--new">Chính hãng</span>
        </div>

        <!-- Tên sản phẩm -->
        <h1 class="prod-info__name">Cisco Catalyst C9200-24T-A</h1>

        <!-- Mã SP -->
        <div class="prod-info__code">
          <span>Mã sản phẩm:</span>
          <code>C9200-24T-A</code>
          <button class="copy-btn" id="copyCode" title="Copy mã sản phẩm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
          </button>
        </div>

        <!-- Rating -->
        <div class="prod-info__rating">
          <div class="stars" id="ratingStars">
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--half" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          </div>
          <span class="rating-score">4.5</span>
          <a href="#reviews" class="rating-count" id="scrollToReviews">(24 đánh giá)</a>
        </div>

        <!-- Mô tả ngắn -->
        <p class="prod-info__desc">
          Cisco Catalyst 9200 Series là dòng switch tầng access tiếp theo dành cho
          doanh nghiệp vừa và nhỏ với khả năng stacking, hỗ trợ PoE+ và tích hợp
          đầy đủ tính năng bảo mật của Cisco DNA.
        </p>

        <!-- Thông số nhanh -->
        <div class="prod-info__specs">
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span><strong>Catalyst 9200</strong> 24-port Data Switch, Network Advantage</span>
          </div>
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <span><strong>24</strong> × 10/100/1000 Mbps Ports Data</span>
          </div>
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
            <span>Modular uplink: <strong>4 × 1G</strong> network module</span>
          </div>
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>Switching capacity: <strong>128 Gbps</strong></span>
          </div>
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            <span>Forwarding rate: <strong>95.23 Mpps</strong></span>
          </div>
          <div class="spec-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            <span>Stacking: <strong>StackWise 160</strong></span>
          </div>
        </div>

        <!-- Nút hành động -->
        <div class="prod-info__actions">
          <button class="btn btn--primary btn--lg btn--contact"
                  data-modal="lien-he"
                  data-ma="C9200-24T-A"
                  data-ten="Cisco Catalyst C9200-24T-A">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            Liên hệ báo giá
          </button>
          <a href="tel:<?php echo CONG_TY_DIEN_THOAI; ?>" class="btn btn--outline btn--lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            Gọi ngay
          </a>
        </div>

        <!-- Cam kết -->
        <div class="prod-info__guarantees">
          <div class="guarantee-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Hàng chính hãng Cisco 100%
          </div>
          <div class="guarantee-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Giao hàng toàn quốc
          </div>
          <div class="guarantee-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3z"/><path d="M7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
            Bảo hành theo chính sách Cisco
          </div>
          <div class="guarantee-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Hỗ trợ kỹ thuật 24/7
          </div>
        </div>

      </div><!-- end prod-info -->
    </div><!-- end prod-main__layout -->
  </div>
</section>

<!-- ========== TABS ĐIỀU HƯỚNG ========== -->
<div class="prod-tabs-nav" id="prodTabsNav">
  <div class="container">
    <div class="prod-tabs-nav__list">
      <a href="#product-details" class="prod-tab-link active" data-tab="product-details">Chi tiết sản phẩm</a>
      <a href="#specs" class="prod-tab-link" data-tab="specs">Thông số kỹ thuật</a>
      <a href="#reviews" class="prod-tab-link" data-tab="reviews">
        Đánh giá
        <span class="tab-badge">24</span>
      </a>
    </div>
  </div>
</div>

<!-- ========== PRODUCT DETAILS ========== -->
<section class="section prod-detail-section" id="product-details">
  <div class="container">
    <div class="prod-detail__layout">

      <!-- NỘI DUNG BÀI VIẾT -->
      <div class="prod-detail__content">
        <h2 class="prod-detail__heading">Tổng quan Cisco Catalyst C9200-24T-A</h2>
        <p>
          Cisco Catalyst 9200 Series Switches mang lại cho các chi nhánh và doanh nghiệp
          vừa khả năng tiếp cận các tính năng cao cấp của Cisco DNA với mức chi phí hợp lý.
          Dòng switch này được thiết kế để hoạt động như một phần của Cisco DNA, cho phép
          tự động hóa mạng, phân tích dữ liệu và bảo mật tích hợp.
        </p>

        <div class="prod-detail__img-full">
          <img src="https://placehold.co/900x400/e8f4ff/005073?text=Cisco+Catalyst+9200+Overview"
               alt="Cisco Catalyst 9200 Overview" />
          <p class="img-caption">Cisco Catalyst 9200 Series — Giải pháp switch tầng access cho doanh nghiệp</p>
        </div>

        <h3>Tính năng nổi bật</h3>
        <p>
          Model <strong>C9200-24T-A</strong> cung cấp 24 cổng Gigabit Ethernet cho lưu lượng
          data (không PoE) cùng với 4 uplink SFP 1G có thể hoán đổi linh hoạt. Với license
          <strong>Network Advantage</strong>, thiết bị hỗ trợ đầy đủ các giao thức routing
          nâng cao như OSPF, EIGRP, BGP và tích hợp hoàn toàn với Cisco DNA Center.
        </p>

        <div class="feature-cards">
          <div class="feature-card">
            <div class="feature-card__icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <h4>Hiệu năng cao</h4>
            <p>Switching capacity 128 Gbps, forwarding rate 95.23 Mpps đảm bảo không bottleneck cho mạng campus</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 2 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <h4>StackWise 160</h4>
            <p>Stack tối đa 8 thiết bị với băng thông stack 160 Gbps, quản lý như một switch duy nhất</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h4>Bảo mật tích hợp</h4>
            <p>Cisco TrustSec, MACsec 128-bit, 802.1X authentication và Encrypted Traffic Analytics</p>
          </div>
          <div class="feature-card">
            <div class="feature-card__icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <h4>Cisco DNA Ready</h4>
            <p>Tích hợp hoàn toàn với Cisco DNA Center, hỗ trợ SD-Access và tự động hóa mạng</p>
          </div>
        </div>

        <h3>Ứng dụng triển khai</h3>
        <p>
          C9200-24T-A phù hợp cho tầng <strong>access layer</strong> trong các mô hình mạng
          campus 3-tier hoặc collapsed core. Thiết bị lý tưởng cho văn phòng, trường học,
          bệnh viện với số lượng endpoint vừa phải và không yêu cầu PoE.
        </p>

        <div class="prod-detail__img-grid">
          <div>
            <img src="https://placehold.co/440x300/ddf0ff/003a5c?text=Front+Panel" alt="Front Panel" />
            <p class="img-caption">Mặt trước — 24 cổng GE + 4 SFP uplink</p>
          </div>
          <div>
            <img src="https://placehold.co/440x300/cce8ff/002a44?text=Back+Panel" alt="Back Panel" />
            <p class="img-caption">Mặt sau — Kết nối nguồn và stack</p>
          </div>
        </div>

        <h3>So sánh với C9200L-24T-4G-A</h3>
        <p>
          Khác biệt chính giữa C9200-24T-A và C9200L-24T-4G-A là C9200 có uplink
          <strong>module rời</strong> (hoán đổi được) trong khi C9200L có uplink cố định.
          C9200 phù hợp hơn khi cần linh hoạt trong việc lựa chọn loại uplink (SFP 1G,
          SFP+ 10G tùy module lắp thêm).
        </p>

      </div><!-- end prod-detail__content -->

      <!-- SIDEBAR -->
      <aside class="prod-detail__sidebar">
        <div class="sidebar-card">
          <h4>Tài liệu kỹ thuật</h4>
          <ul class="doc-list">
            <li>
              <a href="#" class="doc-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Datasheet C9200-24T-A</span>
                <span class="doc-size">PDF · 2.3 MB</span>
              </a>
            </li>
            <li>
              <a href="#" class="doc-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Quick Start Guide</span>
                <span class="doc-size">PDF · 1.1 MB</span>
              </a>
            </li>
            <li>
              <a href="#" class="doc-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Configuration Guide IOS-XE</span>
                <span class="doc-size">PDF · 8.5 MB</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="sidebar-card sidebar-card--contact">
          <h4>Cần tư vấn thêm?</h4>
          <p>Liên hệ kỹ sư của chúng tôi để được hỗ trợ chọn model phù hợp.</p>
          <button class="btn btn--white btn--sm"
                  data-modal="lien-he"
                  data-ma="C9200-24T-A"
                  data-ten="Cisco Catalyst C9200-24T-A">
            Chat Zalo ngay
          </button>
          <a href="tel:<?php echo CONG_TY_DIEN_THOAI; ?>" class="btn btn--outline-white btn--sm" style="margin-top:8px">
            G&#7885;i <?php echo CONG_TY_DIEN_THOAI; ?>
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>

<!-- ========== THÔNG SỐ KỸ THUẬT ========== -->
<section class="section section--bg specs-section" id="specs">
  <div class="container">
    <h2 class="specs-heading">Thông số kỹ thuật</h2>
    <div class="specs-table-wrap">
      <table class="specs-table">
        <tbody>
          <tr class="specs-table__group-header"><td colspan="2">Tổng quan</td></tr>
          <tr><td>Số cổng downlink</td><td><strong>24 × 10/100/1000BASE-T</strong></td></tr>
          <tr><td>Số cổng uplink</td><td><strong>4 × 1G SFP (module rời)</strong></td></tr>
          <tr><td>PoE</td><td>Không hỗ trợ (phiên bản Data only)</td></tr>
          <tr><td>Stacking</td><td><strong>StackWise-160</strong> (tối đa 8 switch)</td></tr>
          <tr><td>License</td><td><strong>Network Advantage</strong></td></tr>
          <tr><td>Form factor</td><td>1U Rack-mountable</td></tr>

          <tr class="specs-table__group-header"><td colspan="2">Hiệu năng</td></tr>
          <tr><td>Switching capacity</td><td><strong>128 Gbps</strong></td></tr>
          <tr><td>Forwarding rate</td><td><strong>95.23 Mpps</strong></td></tr>
          <tr><td>MAC address table</td><td>32.768 entries</td></tr>
          <tr><td>VLAN</td><td>4.094 VLANs</td></tr>
          <tr><td>Jumbo frame</td><td>9.198 bytes</td></tr>

          <tr class="specs-table__group-header"><td colspan="2">Nguồn điện</td></tr>
          <tr><td>Nguồn điện đầu vào</td><td>AC 100–240V, 50–60 Hz</td></tr>
          <tr><td>Công suất tối đa</td><td><strong>60W</strong></td></tr>
          <tr><td>Power supply</td><td>Internal, non-redundant</td></tr>

          <tr class="specs-table__group-header"><td colspan="2">Vật lý</td></tr>
          <tr><td>Kích thước (H×W×D)</td><td>44 × 445 × 305 mm (1U)</td></tr>
          <tr><td>Khối lượng</td><td>3.6 kg</td></tr>
          <tr><td>Nhiệt độ hoạt động</td><td>0°C – 45°C</td></tr>
          <tr><td>Độ ẩm</td><td>10% – 95% (không ngưng tụ)</td></tr>
          <tr><td>MTBF</td><td>566.888 giờ</td></tr>

          <tr class="specs-table__group-header"><td colspan="2">Tính năng phần mềm</td></tr>
          <tr><td>Hệ điều hành</td><td>Cisco IOS XE</td></tr>
          <tr><td>Giao thức routing</td><td>OSPF, EIGRP, BGP, RIP, IS-IS</td></tr>
          <tr><td>IPv6</td><td>Có hỗ trợ đầy đủ</td></tr>
          <tr><td>QoS</td><td>802.1p, DSCP, 8 queues/port</td></tr>
          <tr><td>Bảo mật</td><td>802.1X, TrustSec, MACsec 128-bit</td></tr>
          <tr><td>Cisco DNA Center</td><td>Có hỗ trợ</td></tr>
          <tr><td>SD-Access</td><td>Có hỗ trợ</td></tr>
          <tr><td>SNMP</td><td>v1, v2c, v3</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ========== REVIEWS ========== -->
<section class="section reviews-section" id="reviews">
  <div class="container">
    <h2 class="reviews-heading">Đánh giá sản phẩm</h2>

    <div class="reviews-layout">

      <!-- TÓM TẮT ĐÁNH GIÁ -->
      <div class="reviews-summary">
        <div class="reviews-summary__score">
          <span class="score-big">4.5</span>
          <div class="score-stars">
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--full" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <svg class="star star--half" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          </div>
          <span class="score-total">24 đánh giá</span>
        </div>

        <!-- Biểu đồ thanh -->
        <div class="reviews-bars">
          <div class="review-bar" data-filter="5">
            <span class="bar-label">5★</span>
            <div class="bar-track"><div class="bar-fill" style="width:60%"></div></div>
            <span class="bar-count">14</span>
          </div>
          <div class="review-bar" data-filter="4">
            <span class="bar-label">4★</span>
            <div class="bar-track"><div class="bar-fill" style="width:25%"></div></div>
            <span class="bar-count">6</span>
          </div>
          <div class="review-bar" data-filter="3">
            <span class="bar-label">3★</span>
            <div class="bar-track"><div class="bar-fill" style="width:8%"></div></div>
            <span class="bar-count">2</span>
          </div>
          <div class="review-bar" data-filter="2">
            <span class="bar-label">2★</span>
            <div class="bar-track"><div class="bar-fill" style="width:4%"></div></div>
            <span class="bar-count">1</span>
          </div>
          <div class="review-bar" data-filter="1">
            <span class="bar-label">1★</span>
            <div class="bar-track"><div class="bar-fill" style="width:4%"></div></div>
            <span class="bar-count">1</span>
          </div>
        </div>

        <!-- Filter -->
        <div class="reviews-filter">
          <button class="filter-star-btn active" data-filter="all">Tất cả</button>
          <button class="filter-star-btn" data-filter="5">5★</button>
          <button class="filter-star-btn" data-filter="4">4★</button>
          <button class="filter-star-btn" data-filter="3">3★</button>
          <button class="filter-star-btn" data-filter="2">2★</button>
          <button class="filter-star-btn" data-filter="1">1★</button>
        </div>
      </div>

      <!-- DANH SÁCH REVIEW + FORM -->
      <div class="reviews-main">

        <!-- Form viết review -->
        <div class="review-form-wrap">
          <h3>Viết đánh giá của bạn</h3>
          <form class="review-form" id="reviewForm">
            <input type="hidden" name="sao" id="reviewStarInput" value="0" />
            <div class="review-form__stars">
              <span>Đánh giá:</span>
              <div class="star-picker" id="starPicker">
                <button type="button" class="star-pick" data-val="1">★</button>
                <button type="button" class="star-pick" data-val="2">★</button>
                <button type="button" class="star-pick" data-val="3">★</button>
                <button type="button" class="star-pick" data-val="4">★</button>
                <button type="button" class="star-pick" data-val="5">★</button>
              </div>
              <span class="star-label" id="starLabel">Chọn số sao</span>
            </div>
            <div class="review-form__fields">
              <div class="form-row">
                <input type="text" name="ho_ten" placeholder="Họ tên *" required />
                <input type="text" name="cong_ty" placeholder="Công ty / Tổ chức" />
              </div>
              <textarea name="nhan_xet" placeholder="Nhận xét của bạn về sản phẩm *" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn--primary">
              Gửi đánh giá
            </button>
          </form>
        </div>

        <!-- Danh sách review -->
        <div class="reviews-list" id="reviewsList"></div>

      </div>
    </div>
  </div>
</section>


<!-- FOOTER -->

<?php
$jsExtra = ['assets/js/product.js'];
require_once 'includes/footer.php';
?>
