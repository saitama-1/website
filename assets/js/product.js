/* ===========================================
   PRODUCT DETAIL — product.js
=========================================== */

'use strict';

// =====================
// DATA ẢNH THẬT C9200-24T-A
// =====================
const GALLERY_IMAGES = [
  {
    label: 'Mặt trước',
    src:       'https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-4.jpg',
    fallback:  'https://placehold.co/800x600/e8f4ff/005073?text=Image+1',
    thumb:     'https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-4.jpg',
    thumbFb:   'https://placehold.co/120x90/e8f4ff/005073?text=Image+1',
  },
  {
    label: 'Mặt sau',
    src:       'https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-5.jpg',
    fallback:  'https://placehold.co/800x600/ddeeff/003366?text=Image+2',
    thumb:     'https://cnttshop.vn//storage/images/San-pham/Switch/Cisco/110/CBS110-16T/CBS110-16T-5.jpg',
    thumbFb:   'https://placehold.co/120x90/ddeeff/003366?text=Image+2',
  },
];

// Helper: load ảnh với fallback
function loadImgWithFallback(imgEl, src, fallback) {
  imgEl.src = src;
  imgEl.onerror = () => {
    if (imgEl.src !== fallback) {
      imgEl.src = fallback;
    }
    imgEl.onerror = null;
  };
}
const REVIEWS = [
  { id:1, ten:'Nguyễn Minh Khoa', cong_ty:'Công ty TNHH ABC Tech', sao:5, noi_dung:'Switch hoạt động rất ổn định, đã triển khai cho văn phòng 50 người dùng. Hàng chính hãng, có đầy đủ tem CO/CQ. Đội ngũ kỹ thuật hỗ trợ cấu hình rất nhiệt tình. Sẽ tiếp tục hợp tác trong các dự án tới.', ngay:'20/03/2025', da_xac_nhan: true },
  { id:2, ten:'Trần Thị Hương',   cong_ty:'IT Manager – Trường ĐH XYZ', sao:5, noi_dung:'Rất hài lòng với sản phẩm. Thiết bị chạy êm, không nóng, quản lý qua Cisco DNA Center rất tiện. Giá tốt hơn các nơi khác tôi đã tham khảo.', ngay:'15/03/2025', da_xac_nhan: true },
  { id:3, ten:'Lê Văn Đức',       cong_ty:'Network Engineer – FPT',    sao:4, noi_dung:'Sản phẩm tốt, đúng hàng, đóng gói cẩn thận. Giao hàng nhanh. Chỉ tiếc là không có PoE nên phải mua thêm injector cho một số thiết bị. Sẽ chọn model 9200-48P cho dự án tiếp theo.', ngay:'10/03/2025', da_xac_nhan: false },
  { id:4, ten:'Phạm Quốc Bảo',   cong_ty:'Sysadmin – Bệnh viện Bình Dân', sao:5, noi_dung:'Đã mua 6 cái để nâng cấp hạ tầng mạng bệnh viện. Thiết bị ổn định, không có downtime sau 2 tháng vận hành. Stack với nhau rất dễ dàng. Cảm ơn đội ngũ kỹ thuật đã hỗ trợ tận tình.', ngay:'05/03/2025', da_xac_nhan: true },
  { id:5, ten:'Vũ Thị Mai',       cong_ty:'IT Dept – Vincom Retail',   sao:3, noi_dung:'Sản phẩm ổn, đúng thông số kỹ thuật. Tuy nhiên thời gian giao hàng hơi lâu hơn dự kiến một chút. Hỗ trợ kỹ thuật sau bán hàng cần cải thiện thêm.', ngay:'28/02/2025', da_xac_nhan: false },
  { id:6, ten:'Hoàng Anh Tuấn',   cong_ty:'CTO – Startup FinTech',    sao:5, noi_dung:'Rất ưng sản phẩm này. Setup StackWise cực kỳ đơn giản, chỉ mất 15 phút là xong. Performance ổn định hoàn toàn. Giá hợp lý so với thị trường.', ngay:'20/02/2025', da_xac_nhan: true },
];

// =====================
// GALLERY & ZOOM
// =====================
function initGallery() {
  const mainImg        = document.getElementById('mainImg');
  const zoomPreview    = document.getElementById('zoomPreview');
  const zoomPreviewImg = document.getElementById('zoomPreviewImg');
  const zoomLens       = document.getElementById('zoomLens');
  const galleryMain    = document.getElementById('galleryMain');
  const thumbsWrap     = document.getElementById('galleryThumbs');

  if (!mainImg || !galleryMain || !thumbsWrap) return;

  // ─── Render thumbnails từ data ảnh thật ───
  thumbsWrap.innerHTML = GALLERY_IMAGES.map((img, i) => `
    <button class="gallery__thumb ${i === 0 ? 'active' : ''}"
            data-index="${i}"
            title="${img.label}">
      <img alt="${img.label}" />
    </button>
  `).join('');

  // Load ảnh thumbnail
  const thumbBtns = thumbsWrap.querySelectorAll('.gallery__thumb');
  thumbBtns.forEach((btn, i) => {
    const thumbImg = btn.querySelector('img');
    loadImgWithFallback(thumbImg, GALLERY_IMAGES[i].thumb, GALLERY_IMAGES[i].thumbFb);
  });

  // Load ảnh chính ban đầu
  loadImgWithFallback(mainImg, GALLERY_IMAGES[0].src, GALLERY_IMAGES[0].fallback);
  if (zoomPreviewImg) {
    loadImgWithFallback(zoomPreviewImg, GALLERY_IMAGES[0].src, GALLERY_IMAGES[0].fallback);
  }

  let currentIndex = 0;

  // ─── Đổi ảnh khi click thumbnail ───
  thumbBtns.forEach((btn, i) => {
    btn.addEventListener('click', () => {
      if (i === currentIndex) return;
      currentIndex = i;

      // Active state
      thumbBtns.forEach(t => t.classList.remove('active'));
      btn.classList.add('active');

      // Scroll thumbnail vào view
      btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

      // Fade out → đổi ảnh → fade in
      mainImg.style.opacity = '0';
      hideZoom();

      setTimeout(() => {
        loadImgWithFallback(mainImg, GALLERY_IMAGES[i].src, GALLERY_IMAGES[i].fallback);
        if (zoomPreviewImg) {
          loadImgWithFallback(zoomPreviewImg, GALLERY_IMAGES[i].src, GALLERY_IMAGES[i].fallback);
        }
        mainImg.style.opacity = '1';
      }, 180);
    });
  });

  // ─── Zoom khi hover trên ảnh lớn ───
  const ZOOM = 2.5;
  let lensW = 0, lensH = 0;

  function showZoom() {
    if (!zoomPreview || window.innerWidth < 768) return;
    zoomPreview.style.display = 'block';
    if (zoomLens) zoomLens.style.display = 'block';

    const rect  = galleryMain.getBoundingClientRect();
    const prevW = zoomPreview.offsetWidth  || 340;
    const prevH = zoomPreview.offsetHeight || rect.height;

    lensW = prevW / ZOOM;
    lensH = prevH / ZOOM;

    if (zoomLens) {
      zoomLens.style.width  = lensW + 'px';
      zoomLens.style.height = lensH + 'px';
    }

    if (zoomPreviewImg) {
      zoomPreviewImg.style.width  = rect.width  * ZOOM + 'px';
      zoomPreviewImg.style.height = rect.height * ZOOM + 'px';
    }
  }

  function hideZoom() {
    if (zoomPreview) zoomPreview.style.display = 'none';
    if (zoomLens)    zoomLens.style.display    = 'none';
  }

  function moveZoom(e) {
    if (!zoomPreview || zoomPreview.style.display === 'none') return;

    const rect = galleryMain.getBoundingClientRect();
    let x = e.clientX - rect.left;
    let y = e.clientY - rect.top;

    x = Math.max(lensW / 2, Math.min(x, rect.width  - lensW / 2));
    y = Math.max(lensH / 2, Math.min(y, rect.height - lensH / 2));

    if (zoomLens) {
      zoomLens.style.left = (x - lensW / 2) + 'px';
      zoomLens.style.top  = (y - lensH / 2) + 'px';
    }

    if (!zoomPreviewImg) return;

    const ratioX = lensW < rect.width  ? (x - lensW / 2) / (rect.width  - lensW) : 0;
    const ratioY = lensH < rect.height ? (y - lensH / 2) / (rect.height - lensH) : 0;

    const imgW = rect.width  * ZOOM;
    const imgH = rect.height * ZOOM;
    const pvW  = zoomPreview.offsetWidth;
    const pvH  = zoomPreview.offsetHeight;

    zoomPreviewImg.style.left = (-ratioX * Math.max(0, imgW - pvW)) + 'px';
    zoomPreviewImg.style.top  = (-ratioY * Math.max(0, imgH - pvH)) + 'px';
  }

  // Chỉ trigger zoom khi chuột vào đúng ảnh lớn
  // galleryMain.addEventListener('mouseenter', showZoom);
  // galleryMain.addEventListener('mouseleave', hideZoom);
  // galleryMain.addEventListener('mousemove',  moveZoom);
}

// =====================
// CONTACT MODAL
// =====================
function initContactModal() {
  const modal      = document.getElementById('contactModal');
  const openBtn    = document.getElementById('openContactModal');
  const closeBtn   = document.getElementById('closeModal');

  if (!modal) return;

  const open  = () => modal.classList.add('open');
  const close = () => modal.classList.remove('open');

  openBtn?.addEventListener('click', open);
  closeBtn?.addEventListener('click', close);

  // Đóng khi click overlay
  modal.addEventListener('click', (e) => {
    if (e.target === modal) close();
  });

  // Đóng khi ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
}

// =====================
// COPY MÃ SẢN PHẨM
// =====================
function initCopyCode() {
  const btn = document.getElementById('copyCode');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const code = btn.previousElementSibling?.textContent?.trim();
    if (!code) return;

    navigator.clipboard.writeText(code).then(() => {
      btn.classList.add('copied');
      btn.title = 'Đã copy!';
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;

      setTimeout(() => {
        btn.classList.remove('copied');
        btn.title = 'Copy mã sản phẩm';
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>`;
      }, 2000);
    });
  });
}

// =====================
// TABS NAVIGATION
// =====================
function initTabs() {
  const tabLinks = document.querySelectorAll('.prod-tab-link');
  const tabsNav  = document.getElementById('prodTabsNav');

  // Scroll to section khi click tab
  tabLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = link.getAttribute('href').replace('#', '');
      const target   = document.getElementById(targetId);
      if (!target) return;

      const offset = (tabsNav?.offsetHeight || 60) + 10;
      const top    = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  // Active tab khi scroll
  const sections = ['product-details', 'specs', 'reviews']
    .map(id => document.getElementById(id))
    .filter(Boolean);

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.id;
        tabLinks.forEach(link => {
          link.classList.toggle('active', link.dataset.tab === id);
        });
      }
    });
  }, {
    rootMargin: '0px 0px -60% 0px',
    threshold: 0,
  });

  sections.forEach(s => observer.observe(s));

  // Scroll to reviews khi click rating count
  document.getElementById('scrollToReviews')?.addEventListener('click', (e) => {
    e.preventDefault();
    const target = document.getElementById('reviews');
    if (!target) return;
    const offset = 140;
    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
  });
}

// =====================
// STAR PICKER (FORM)
// =====================
function initStarPicker() {
  const stars     = document.querySelectorAll('.star-pick');
  const starLabel = document.getElementById('starLabel');
  const labels    = ['', 'Rất tệ', 'Tệ', 'Bình thường', 'Tốt', 'Xuất sắc'];
  let selectedStar = 0;

  stars.forEach(star => {
    const val = +star.dataset.val;

    star.addEventListener('mouseenter', () => {
      stars.forEach(s => s.classList.toggle('active', +s.dataset.val <= val));
      if (starLabel) starLabel.textContent = labels[val];
    });

    star.addEventListener('mouseleave', () => {
      stars.forEach(s => s.classList.toggle('active', +s.dataset.val <= selectedStar));
      if (starLabel) starLabel.textContent = selectedStar ? labels[selectedStar] : 'Chọn số sao';
    });

    star.addEventListener('click', () => {
      selectedStar = val;
      stars.forEach(s => s.classList.toggle('active', +s.dataset.val <= selectedStar));
      if (starLabel) starLabel.textContent = labels[selectedStar];
    });
  });

  // Submit form
  document.getElementById('reviewForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    if (!selectedStar) {
      alert('Vui lòng chọn số sao đánh giá!');
      return;
    }

    const form    = e.target;
    const inputs  = form.querySelectorAll('input');
    const textarea = form.querySelector('textarea');
    const ten     = inputs[0]?.value.trim();
    const cty     = inputs[1]?.value.trim();
    const nd      = textarea?.value.trim();

    if (!ten || !nd) {
      alert('Vui lòng nhập họ tên và nội dung đánh giá!');
      return;
    }

    // Thêm review mới vào đầu
    const newReview = {
      id: Date.now(),
      ten, cong_ty: cty,
      sao: selectedStar,
      noi_dung: nd,
      ngay: new Date().toLocaleDateString('vi-VN'),
      da_xac_nhan: false,
    };

    REVIEWS.unshift(newReview);
    renderReviews('all');

    // Reset form
    form.reset();
    selectedStar = 0;
    stars.forEach(s => s.classList.remove('active'));
    if (starLabel) starLabel.textContent = 'Chọn số sao';

    // Cuộn đến review vừa thêm
    document.getElementById('reviewsList')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Thông báo
    showToast('✅ Cảm ơn bạn đã đánh giá!');
  });
}

// =====================
// RENDER REVIEWS
// =====================
function renderReviews(filter = 'all') {
  const list = document.getElementById('reviewsList');
  if (!list) return;

  const filtered = filter === 'all'
    ? REVIEWS
    : REVIEWS.filter(r => r.sao === +filter);

  if (!filtered.length) {
    list.innerHTML = `<div class="reviews-empty">Chưa có đánh giá ${filter !== 'all' ? filter + '★' : ''} nào.</div>`;
    return;
  }

  list.innerHTML = filtered.map(r => `
    <div class="review-card" data-sao="${r.sao}">
      <div class="review-card__header">
        <div class="review-card__user">
          <div class="review-card__avatar">${r.ten.split(' ').slice(-2).map(w => w[0]).join('').toUpperCase()}</div>
          <div>
            <div class="review-card__name">${r.ten}</div>
            ${r.cong_ty ? `<div class="review-card__company">${r.cong_ty}</div>` : ''}
          </div>
        </div>
        <div class="review-card__meta">
          <div class="review-card__stars">
            ${Array.from({length:5}, (_,i) => `
              <svg class="star ${i < r.sao ? 'star--full' : ''}" viewBox="0 0 24 24"
                   style="fill:${i < r.sao ? '#fbbf24' : '#e5e7eb'}">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>`).join('')}
          </div>
          <div class="review-card__date">${r.ngay}</div>
        </div>
      </div>
      <p class="review-card__text">${r.noi_dung}</p>
      ${r.da_xac_nhan ? `
        <div class="review-card__verified">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Đã xác nhận mua hàng
        </div>` : ''}
    </div>
  `).join('');
}

// =====================
// FILTER REVIEWS
// =====================
function initReviewFilter() {
  document.querySelectorAll('.review-bar, .filter-star-btn').forEach(el => {
    el.addEventListener('click', () => {
      const filter = el.dataset.filter;

      // Update active button
      document.querySelectorAll('.filter-star-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.filter === filter);
      });

      renderReviews(filter);
    });
  });
}

// =====================
// TOAST
// =====================
function showToast(msg) {
  let toast = document.getElementById('toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toast';
    toast.style.cssText = `
      position:fixed; bottom:24px; left:50%; transform:translateX(-50%) translateY(20px);
      background:#1e293b; color:#fff; padding:12px 24px; border-radius:50px;
      font-size:14px; font-weight:600; z-index:9999;
      opacity:0; transition:all .3s; pointer-events:none;
      white-space:nowrap; box-shadow:0 8px 24px rgba(0,0,0,.2);
    `;
    document.body.appendChild(toast);
  }

  toast.textContent = msg;
  toast.style.opacity = '1';
  toast.style.transform = 'translateX(-50%) translateY(0)';

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(-50%) translateY(20px)';
  }, 3000);
}

// =====================
// INIT HEADER (từ main.js)
// =====================
function initProductHeader() {
  const hamburger = document.getElementById('hamburger');
  const nav       = document.getElementById('nav');
  const header    = document.getElementById('header');

  hamburger?.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  document.addEventListener('click', (e) => {
    if (nav?.classList.contains('open') && !header?.contains(e.target)) {
      nav.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  document.querySelectorAll('.has-dropdown').forEach(item => {
    const link     = item.querySelector('.nav__link--arrow');
    const dropdown = item.querySelector('.dropdown');
    const isDesktop = () => window.innerWidth >= 768;

    const show = () => { clearTimeout(item._timer); item.classList.add('open'); };
    const tryHide = (rel) => {
      if (item.contains(rel) || dropdown?.contains(rel)) return;
      item._timer = setTimeout(() => item.classList.remove('open'), 100);
    };

    item.addEventListener('mouseenter', e => { if (isDesktop()) show(); });
    item.addEventListener('mouseleave', e => { if (isDesktop()) tryHide(e.relatedTarget); });
    dropdown?.addEventListener('mouseenter', e => { if (isDesktop()) show(); });
    dropdown?.addEventListener('mouseleave', e => { if (isDesktop()) tryHide(e.relatedTarget); });

    link?.addEventListener('click', e => {
      if (isDesktop()) return;
      e.preventDefault();
      document.querySelectorAll('.has-dropdown.open').forEach(o => { if (o !== item) o.classList.remove('open'); });
      item.classList.toggle('open');
    });
  });

  // Shadow khi scroll
  window.addEventListener('scroll', () => {
    header?.classList.toggle('scrolled', window.scrollY > 60);
  }, { passive: true });

  // Back to top
  const backTop = document.getElementById('backTop');
  window.addEventListener('scroll', () => {
    backTop?.classList.toggle('show', window.scrollY > 400);
  }, { passive: true });
  backTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  // Year footer
  const yr = document.getElementById('year');
  if (yr) yr.textContent = new Date().getFullYear();
}

// =====================
// INIT
// =====================
document.addEventListener('DOMContentLoaded', () => {
  initProductHeader();
  initGallery();
  initContactModal();
  initCopyCode();
  initTabs();
  initStarPicker();
  renderReviews('all');
  initReviewFilter();

  // Year footer
  const yr = document.getElementById('year');
  if (yr) yr.textContent = new Date().getFullYear();
});
