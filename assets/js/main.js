/* ===========================================
   CISCO VN — MAIN.JS
   Dữ liệu mẫu + Logic trang chủ
=========================================== */

'use strict';

// =====================
// DATA MẪU
// =====================

const CATEGORIES = [
  { id: 1, ten: 'Switch',          slug: 'switch',        mo_ta: 'Cisco Catalyst, Nexus, SG Business',  icon: '🔀' },
  { id: 2, ten: 'Router',          slug: 'router',        mo_ta: 'Cisco ISR 4000, ISR 1000, Cat 8000',  icon: '🌐' },
  { id: 3, ten: 'Firewall',        slug: 'firewall',      mo_ta: 'Cisco Secure Firewall, Cisco ASA',    icon: '🔒' },
  { id: 4, ten: 'Wireless',        slug: 'wireless',      mo_ta: 'Cisco Catalyst 9100, Aironet Series', icon: '📶' },
  { id: 5, ten: 'Module quang',    slug: 'module-quang',  mo_ta: 'SFP 1G, SFP+ 10G, QSFP 40G/100G',   icon: '💡' },
  { id: 6, ten: 'IP Phone',        slug: 'ip-phone',      mo_ta: 'Cisco IP Phone 7800, 8800 Series',   icon: '📞' },
  { id: 7, ten: 'Cable & Adapter', slug: 'cable-adapter', mo_ta: 'Console cable, StackWise cable',     icon: '🔌' },
];

const PRODUCTS = [
  // ===== NỔI BẬT =====
  { id:1,  ma:'C9200-24T-A',       ten:'Cisco Catalyst C9200-24T-A',       series:'Catalyst 9200 Series',        danh_muc:'Switch',   mo_ta:'24 cổng GE, 4 SFP uplink, không PoE, StackWise-80, license Advantage',         anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9200-24T', slug:'cisco-catalyst-c9200-24t-a' },
  { id:2,  ma:'C9300-48P-A',       ten:'Cisco Catalyst C9300-48P-A',       series:'Catalyst 9300 Series',        danh_muc:'Switch',   mo_ta:'48 cổng GE PoE+, 4x1G SFP uplink, 890W, StackWise-480, license Advantage',     anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9300-48P', slug:'cisco-catalyst-c9300-48p-a' },
  { id:3,  ma:'ISR4321-K9',        ten:'Cisco ISR4321/K9',                 series:'ISR 4300 Series',             danh_muc:'Router',   mo_ta:'2x WAN GE, 2x LAN GE, 1 NIM slot, throughput 50–100 Mbps, SD-WAN ready',       anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4321',   slug:'cisco-isr4321-k9' },
  { id:4,  ma:'FPR1010-NGFW-K9',  ten:'Cisco Firepower FPR1010-NGFW-K9',  series:'Secure Firewall 1000 Series', danh_muc:'Firewall', mo_ta:'8 cổng GE, throughput FW 650 Mbps, IPS 250 Mbps, FTD software',               anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR1010',  slug:'cisco-firepower-fpr1010-ngfw-k9' },
  { id:5,  ma:'C9120AXI-A',        ten:'Cisco Catalyst C9120AXI-A',        series:'Catalyst 9120 Series',        danh_muc:'Wireless', mo_ta:'Wi-Fi 6 (802.11ax) Indoor AP, 4x4 MU-MIMO, 2.4/5 GHz dual radio',             anh:'https://placehold.co/400x280/e8fff4/007a4a?text=C9120AXI',  slug:'cisco-catalyst-c9120axi-a' },
  { id:6,  ma:'WS-C2960X-48FPS-L', ten:'Cisco WS-C2960X-48FPS-L',         series:'Catalyst 2960-X Series',      danh_muc:'Switch',   mo_ta:'48 cổng GE PoE+, 4 SFP uplink, LAN Base, công suất PoE 740W',                  anh:'https://placehold.co/400x280/e8f4ff/005073?text=2960X-48FPS',slug:'cisco-catalyst-ws-c2960x-48fps-l' },
  { id:7,  ma:'FPR2110-NGFW-K9',  ten:'Cisco Firepower FPR2110-NGFW-K9',  series:'Secure Firewall 2100 Series', danh_muc:'Firewall', mo_ta:'12 cổng 1G, 4 cổng 10G SFP+, throughput FW 2 Gbps, IPS 4 Gbps',              anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR2110',  slug:'cisco-firepower-fpr2110-ngfw-k9' },
  { id:8,  ma:'ISR4331-K9',        ten:'Cisco ISR4331/K9',                 series:'ISR 4300 Series',             danh_muc:'Router',   mo_ta:'3x WAN GE, 2x LAN GE, 2 NIM slot, throughput 100 Mbps, SD-WAN',              anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4331',   slug:'cisco-isr4331-k9' },
];

// ===== DATA SWITCH =====
const SWITCHES = [
  { ma:'C9200-24T-A',        ten:'Cisco Catalyst C9200-24T-A',        series:'Catalyst 9200 Series',   mo_ta:'24 cổng GE, 4 SFP uplink, không PoE, StackWise-80, license Advantage',              anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9200-24T',    slug:'cisco-catalyst-c9200-24t-a' },
  { ma:'C9200-48P-A',        ten:'Cisco Catalyst C9200-48P-A',        series:'Catalyst 9200 Series',   mo_ta:'48 cổng GE PoE+, 4 SFP uplink, 740W, StackWise-80, license Advantage',             anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9200-48P',    slug:'cisco-catalyst-c9200-48p-a' },
  { ma:'C9300-24T-A',        ten:'Cisco Catalyst C9300-24T-A',        series:'Catalyst 9300 Series',   mo_ta:'24 cổng GE, 4x1G SFP uplink, không PoE, StackWise-480, license Advantage',         anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9300-24T',    slug:'cisco-catalyst-c9300-24t-a' },
  { ma:'C9300-48P-A',        ten:'Cisco Catalyst C9300-48P-A',        series:'Catalyst 9300 Series',   mo_ta:'48 cổng GE PoE+, 4x1G SFP uplink, 890W, StackWise-480, license Advantage',         anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9300-48P',    slug:'cisco-catalyst-c9300-48p-a' },
  { ma:'C9300-48UXM-A',      ten:'Cisco Catalyst C9300-48UXM-A',      series:'Catalyst 9300 Series',   mo_ta:'48 cổng mGig UPOE+, 4x25G SFP28 uplink, 1480W, StackWise-480',                     anh:'https://placehold.co/400x280/e8f4ff/005073?text=C9300-48UXM', slug:'cisco-catalyst-c9300-48uxm-a' },
  { ma:'WS-C2960X-24TS-L',   ten:'Cisco Catalyst WS-C2960X-24TS-L',  series:'Catalyst 2960-X Series', mo_ta:'24 cổng GE, 4 SFP uplink, LAN Base, không PoE, FlexStack-Plus',                    anh:'https://placehold.co/400x280/e8f4ff/005073?text=2960X-24TS',  slug:'cisco-catalyst-ws-c2960x-24ts-l' },
  { ma:'WS-C2960X-48PS-L',   ten:'Cisco Catalyst WS-C2960X-48PS-L',  series:'Catalyst 2960-X Series', mo_ta:'48 cổng GE PoE+, 4 SFP uplink, LAN Base, 370W, FlexStack-Plus',                   anh:'https://placehold.co/400x280/e8f4ff/005073?text=2960X-48PS',  slug:'cisco-catalyst-ws-c2960x-24ps-l' },
  { ma:'WS-C2960X-48FPS-L',  ten:'Cisco Catalyst WS-C2960X-48FPS-L', series:'Catalyst 2960-X Series', mo_ta:'48 cổng GE PoE+, 4 SFP uplink, LAN Base, 740W, FlexStack-Plus',                   anh:'https://placehold.co/400x280/e8f4ff/005073?text=2960X-48FPS', slug:'cisco-catalyst-ws-c2960x-48fps-l' },
];

// ===== DATA ROUTER =====
const ROUTERS = [
  { ma:'ISR4321-K9',   ten:'Cisco ISR4321/K9',   series:'ISR 4300 Series', mo_ta:'2x WAN GE, 2x LAN GE, 1 NIM slot, throughput 50–100 Mbps, IPsec VPN 85 Mbps',   anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4321', slug:'cisco-isr4321-k9' },
  { ma:'ISR4331-K9',   ten:'Cisco ISR4331/K9',   series:'ISR 4300 Series', mo_ta:'3x WAN GE, 2x LAN GE, 2 NIM slot, throughput 100 Mbps, IPsec VPN 200 Mbps',     anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4331', slug:'cisco-isr4331-k9' },
  { ma:'ISR4351-K9',   ten:'Cisco ISR4351/K9',   series:'ISR 4300 Series', mo_ta:'3x WAN GE, 2x LAN GE, 3 NIM slot, throughput 400 Mbps, IPsec VPN 300 Mbps',     anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4351', slug:'cisco-isr4351-k9' },
  { ma:'ISR4431-K9',   ten:'Cisco ISR4431/K9',   series:'ISR 4400 Series', mo_ta:'4x WAN GE, 2x LAN GE, 3 NIM slot, throughput 500 Mbps, multi-core CPU',         anh:'https://placehold.co/400x280/fff4e8/7a4a00?text=ISR4431', slug:'cisco-isr4431-k9' },
];

// ===== DATA FIREWALL =====
const FIREWALLS = [
  { ma:'FPR1010-NGFW-K9', ten:'Cisco Firepower FPR1010-NGFW-K9', series:'Secure Firewall 1000 Series', mo_ta:'8 cổng GE, FW throughput 650 Mbps, IPS 250 Mbps, URL filtering, Malware protection',  anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR1010', slug:'cisco-firepower-fpr1010-ngfw-k9' },
  { ma:'FPR1120-NGFW-K9', ten:'Cisco Firepower FPR1120-NGFW-K9', series:'Secure Firewall 1000 Series', mo_ta:'8 cổng GE + 4 SFP, FW throughput 1.5 Gbps, IPS 800 Mbps, HA support',                anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR1120', slug:'cisco-firepower-fpr1120-ngfw-k9' },
  { ma:'FPR2110-NGFW-K9', ten:'Cisco Firepower FPR2110-NGFW-K9', series:'Secure Firewall 2100 Series', mo_ta:'12 cổng 1G + 4 cổng 10G SFP+, FW throughput 2 Gbps, IPS 4 Gbps',                    anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR2110', slug:'cisco-firepower-fpr2110-ngfw-k9' },
  { ma:'FPR2130-NGFW-K9', ten:'Cisco Firepower FPR2130-NGFW-K9', series:'Secure Firewall 2100 Series', mo_ta:'12 cổng 1G + 4 cổng 10G SFP+, FW throughput 5 Gbps, IPS 8 Gbps, HA active/standby', anh:'https://placehold.co/400x280/f0e8ff/4a007a?text=FPR2130', slug:'cisco-firepower-fpr2130-ngfw-k9' },
];

// ===== DATA WIRELESS =====
const ACCESS_POINTS = [
  { ma:'C9120AXI-A',  ten:'Cisco Catalyst C9120AXI-A',  series:'Catalyst 9120 Series', mo_ta:'Wi-Fi 6 (802.11ax) Indoor, 4x4 MU-MIMO, 2.4/5 GHz dual radio, PoE+',              anh:'https://placehold.co/400x280/e8fff4/007a4a?text=C9120AXI',  slug:'cisco-catalyst-c9120axi-a' },
  { ma:'C9120AXE-A',  ten:'Cisco Catalyst C9120AXE-A',  series:'Catalyst 9120 Series', mo_ta:'Wi-Fi 6 (802.11ax) Outdoor, 4x4 MU-MIMO, IP67 weatherproof, PoE+',               anh:'https://placehold.co/400x280/e8fff4/007a4a?text=C9120AXE',  slug:'cisco-catalyst-c9120axe-a' },
  { ma:'C9130AXI-A',  ten:'Cisco Catalyst C9130AXI-A',  series:'Catalyst 9130 Series', mo_ta:'Wi-Fi 6E (802.11ax) Indoor, 4x4 MU-MIMO, tri-band 2.4/5/6 GHz, UPOE',           anh:'https://placehold.co/400x280/e8fff4/007a4a?text=C9130AXI',  slug:'cisco-catalyst-c9130axi-a' },
  { ma:'C9130AXE-A',  ten:'Cisco Catalyst C9130AXE-A',  series:'Catalyst 9130 Series', mo_ta:'Wi-Fi 6E (802.11ax) Outdoor, 4x4 MU-MIMO, tri-band, IP67, UPOE',                 anh:'https://placehold.co/400x280/e8fff4/007a4a?text=C9130AXE',  slug:'cisco-catalyst-c9130axe-a' },
];

const BLOG_POSTS = [
  {
    id: 1,
    tieu_de: 'So sánh Cisco Catalyst 9200 và 9300: Chọn switch nào cho doanh nghiệp của bạn?',
    slug: 'so-sanh-cisco-catalyst-9200-va-9300',
    tom_tat: 'Catalyst 9200 và 9300 đều là switch tầng access của Cisco, nhưng có nhiều điểm khác biệt quan trọng về hiệu năng, stacking và tính năng phần mềm...',
    anh: 'https://placehold.co/800x450/e8f4ff/005073?text=Catalyst+9200+vs+9300',
    loai: 'So sánh',
    tac_gia: 'Kỹ sư CCNP',
    ngay: '15/03/2025',
  },
  {
    id: 2,
    tieu_de: 'Hướng dẫn cấu hình VLAN trên Cisco Switch từ A đến Z',
    slug: 'huong-dan-cau-hinh-vlan-cisco-switch',
    tom_tat: 'VLAN (Virtual LAN) giúp phân chia mạng logic trên cùng hạ tầng vật lý. Bài viết hướng dẫn chi tiết cách tạo, cấu hình và kiểm tra VLAN trên switch Cisco...',
    anh: 'https://placehold.co/800x450/e8fff4/007a4a?text=VLAN+Config',
    loai: 'Hướng dẫn',
    tac_gia: 'Kỹ sư CCNA',
    ngay: '10/03/2025',
  },
  {
    id: 3,
    tieu_de: 'Cisco Firepower vs ASA: Nên chọn firewall nào cho doanh nghiệp SMB?',
    slug: 'cisco-firepower-vs-asa-nen-chon-cai-nao',
    tom_tat: 'Cisco đang dần thay thế dòng ASA bằng Secure Firewall (Firepower). Bài viết phân tích ưu nhược điểm và đưa ra khuyến nghị phù hợp cho từng quy mô doanh nghiệp...',
    anh: 'https://placehold.co/800x450/f0e8ff/4a007a?text=Firepower+vs+ASA',
    loai: 'So sánh',
    tac_gia: 'Kỹ sư CCNP Security',
    ngay: '05/03/2025',
  },
];

// =====================
// RENDER FUNCTIONS
// =====================

// Render danh mục
function renderCategories() {
  const grid = document.getElementById('catGrid');
  if (!grid) return;

  grid.innerHTML = CATEGORIES.map(cat => `
    <a href="san-pham.html?dm=${cat.slug}" class="cat-card">
      <div class="cat-card__icon">${cat.icon}</div>
      <div class="cat-card__body">
        <h3>${cat.ten}</h3>
        <p>${cat.mo_ta}</p>
      </div>
      <div class="cat-card__arr">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </div>
    </a>
  `).join('');
}

// =====================
// RENDER CHUNG
// =====================

function renderProdGrid(gridId, data, showBadge = true) {
  const grid = document.getElementById(gridId);
  if (!grid) return;

  grid.innerHTML = data.map(p => `
    <article class="prod-card">
      <div class="prod-card__img">
        <img src="${p.anh}" alt="${p.ten}" loading="lazy">
        <div class="prod-card__overlay">
          <a href="san-pham/${p.slug}.html" class="btn btn--white btn--sm">Xem chi tiết</a>
        </div>
        ${showBadge && p.danh_muc ? `
        <div class="prod-card__badges">
          <span class="badge-dm">${p.danh_muc}</span>
        </div>` : ''}
      </div>
      <div class="prod-card__body">
        <p class="prod-card__series">${p.series}</p>
        <h3 class="prod-card__name">
          <a href="san-pham/${p.slug}.html">${p.ten}</a>
        </h3>
        <span class="prod-card__code">${p.ma}</span>
        <p class="prod-card__desc">${p.mo_ta}</p>
        <div class="prod-card__foot">
          <a href="lien-he.html?sp=${encodeURIComponent(p.ma)}"
             class="btn btn--primary btn--sm">
            Liên hệ báo giá
          </a>
        </div>
      </div>
    </article>
  `).join('');
}

// Render sản phẩm nổi bật
function renderProducts() {
  renderProdGrid('prodGrid', PRODUCTS, true);
}

// Render các danh mục riêng
function renderCatSections() {
  renderProdGrid('switchGrid',   SWITCHES,      false);
  renderProdGrid('routerGrid',   ROUTERS,       false);
  renderProdGrid('firewallGrid', FIREWALLS,     false);
  renderProdGrid('wirelessGrid', ACCESS_POINTS, false);
}

// Render blog
function renderBlog() {
  const grid = document.getElementById('blogGrid');
  if (!grid) return;

  grid.innerHTML = BLOG_POSTS.map(post => `
    <article class="blog-card">
      <a href="blog/${post.slug}.html" class="blog-card__img">
        <img src="${post.anh}" alt="${post.tieu_de}" loading="lazy">
        <span class="blog-card__cat">${post.loai}</span>
      </a>
      <div class="blog-card__body">
        <div class="blog-card__meta">
          <span>✍️ ${post.tac_gia}</span>
          <span>📅 ${post.ngay}</span>
        </div>
        <h3 class="blog-card__title">
          <a href="blog/${post.slug}.html">${post.tieu_de}</a>
        </h3>
        <p class="blog-card__desc">${post.tom_tat}</p>
        <a href="blog/${post.slug}.html" class="blog-card__more">
          Đọc thêm →
        </a>
      </div>
    </article>
  `).join('');
}

// =====================
// HEADER LOGIC
// =====================

function initHeader() {
  const hamburger = document.getElementById('hamburger');
  const nav       = document.getElementById('nav');
  const header    = document.getElementById('header');

  // ── Hamburger toggle ──
  hamburger?.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    // Ngăn scroll body khi menu mở trên mobile
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  // ── Đóng nav khi click ra ngoài ──
  document.addEventListener('click', (e) => {
    if (nav?.classList.contains('open') && !header?.contains(e.target)) {
      nav.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  // ── Đóng nav khi nhấn ESC ──
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      nav?.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
      // Đóng cả dropdown
      document.querySelectorAll('.has-dropdown.open')
        .forEach(item => item.classList.remove('open'));
    }
  });

  // ── Dropdown mobile: click toggle ──
  document.querySelectorAll('.has-dropdown').forEach(item => {
    const link = item.querySelector('.nav__link--arrow');

    link?.addEventListener('click', (e) => {
      if (window.innerWidth >= 768) return;
      e.preventDefault();
      // Đóng các dropdown khác
      document.querySelectorAll('.has-dropdown.open').forEach(other => {
        if (other !== item) other.classList.remove('open');
      });
      item.classList.toggle('open');
    });
  });

  // ── Đóng dropdown mobile khi click ra ngoài ──
  document.addEventListener('click', (e) => {
    if (window.innerWidth >= 768) return;
    if (!e.target.closest('.has-dropdown')) {
      document.querySelectorAll('.has-dropdown.open')
        .forEach(item => item.classList.remove('open'));
    }
  });

  // ── Active link theo trang hiện tại ──
  const currentPage = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav__link').forEach(link => {
    const href = link.getAttribute('href');
    if (href && href !== '#' && currentPage.includes(href.replace('.html', ''))) {
      link.classList.add('active');
    }
  });

  // ── Header shadow khi scroll ──
  const onScroll = () => {
    header?.classList.toggle('scrolled', window.scrollY > 60);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // Gọi ngay lúc load
}

// =====================
// SCROLL TO TOP
// =====================

function initScrollTop() {
  const btn = document.getElementById('backTop');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    btn.classList.toggle('show', window.scrollY > 400);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// =====================
// FOOTER YEAR
// =====================

function setYear() {
  const el = document.getElementById('year');
  if (el) el.textContent = new Date().getFullYear();
}

// =====================
// SCROLL ANIMATION
// =====================

function initScrollAnimation() {
  const items = document.querySelectorAll(
    '.cat-card, .prod-card, .blog-card, .why-us__item, .hcard'
  );

  if (!('IntersectionObserver' in window)) {
    items.forEach(el => el.style.opacity = '1');
    return;
  }

  // Set initial style
  items.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity .4s ease, transform .4s ease';
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        // Stagger delay
        const delay = (Array.from(items).indexOf(entry.target) % 6) * 60;
        setTimeout(() => {
          entry.target.style.opacity    = '1';
          entry.target.style.transform  = 'translateY(0)';
        }, delay);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  items.forEach(el => observer.observe(el));
}

// =====================
// INIT
// =====================

document.addEventListener('DOMContentLoaded', () => {
  renderCategories();
  renderProducts();
  renderCatSections();
  renderBlog();
  initHeader();
  initScrollTop();
  setYear();

  // Chạy animation sau khi render xong
  setTimeout(initScrollAnimation, 50);
});
