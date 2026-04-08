'use strict';

// =============================================
// DANH SÁCH SẢN PHẨM — danh-sach-san-pham.js
// =============================================

let isLoading = false;
let infiniteObserver = null;

if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initSidebar();
  initScrollTop();
  setYear();
  initFormSubmit();
  initFilterToggle();
  initAjaxLinks();
  initInfiniteScroll();
  
  window.addEventListener('popstate', () => {
    updateProducts(window.location.href, false, false);
  });
});

/**
 * Đồng bộ Radio Pills với các tham số hiện tại trên URL
 */
function syncFormWithUrl() {
  const form = document.getElementById('filterForm');
  if (!form) return;

  const params = new URLSearchParams(window.location.search);
  
  form.querySelectorAll('input[type="radio"]').forEach(radio => {
    const valOnUrl = params.get(radio.name);
    // Sử dụng decodeURIComponent để đảm bảo so sánh chính xác các ký tự đặc biệt
    if (valOnUrl !== null && decodeURIComponent(valOnUrl) === radio.value) {
      radio.checked = true;
      radio.setAttribute('data-was-checked', 'true');
    } else {
      radio.checked = false;
      radio.setAttribute('data-was-checked', 'false');
    }
  });
}

/**
 * Cập nhật danh sách sản phẩm qua AJAX
 */
function updateProducts(url, pushState = true, isAppend = false) {
  if (isLoading) return;
  isLoading = true;

  const ajaxContainer = document.getElementById('ajax-container');
  if (!ajaxContainer) {
    isLoading = false;
    return;
  }

  const loader = document.getElementById('infinite-loader');
  const savedScrollY = window.scrollY;

  if (isAppend) {
    if (loader) loader.style.display = 'block';
  } else {
    ajaxContainer.style.opacity = '0.5';
    ajaxContainer.style.pointerEvents = 'none';
  }

  fetch(url, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(response => {
    if (!response.ok) throw new Error('Network response was not ok');
    return response.text();
  })
  .then(html => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const newAjaxContainer = doc.getElementById('ajax-container');

    if (!newAjaxContainer) return;

    if (isAppend) {
      const grid = document.getElementById('prodGrid');
      const newCards = newAjaxContainer.querySelectorAll('.prod-card');
      if (grid && newCards.length > 0) {
        newCards.forEach(card => grid.appendChild(card));
        ajaxContainer.setAttribute('data-current-page', newAjaxContainer.getAttribute('data-current-page'));
      }
    } else {
      // Thay thế toàn bộ nội dung (bao gồm cả khi chuyển từ empty sang có hàng và ngược lại)
      ajaxContainer.innerHTML = newAjaxContainer.innerHTML;
      Array.from(newAjaxContainer.attributes).forEach(attr => {
        ajaxContainer.setAttribute(attr.name, attr.value);
      });
      initInfiniteScroll();
    }

    const newStats = doc.querySelector('.catalog-stats');
    const oldStats = document.querySelector('.catalog-stats');
    if (oldStats && newStats) oldStats.innerHTML = newStats.innerHTML;

    if (pushState) history.pushState(null, '', url);
    if (!isAppend) syncFormWithUrl();

    window.scrollTo(0, savedScrollY);
    requestAnimationFrame(() => window.scrollTo(0, savedScrollY));
  })
  .catch(err => {
    console.error('AJAX Error:', err);
    if (!isAppend && pushState) window.location.href = url;
  })
  .finally(() => {
    isLoading = false;
    if (loader) loader.style.display = 'none';
    ajaxContainer.style.opacity = '1';
    ajaxContainer.style.pointerEvents = 'auto';
  });
}

/**
 * Infinite Scroll
 */
function initInfiniteScroll() {
  const sentinel = document.getElementById('infinite-sentinel');
  if (!sentinel) return;

  if (infiniteObserver) infiniteObserver.disconnect();
  infiniteObserver = new IntersectionObserver((entries) => {
    const entry = entries[0];
    if (entry.isIntersecting && !isLoading) {
      const container = document.getElementById('ajax-container');
      const currentPage = parseInt(container?.getAttribute('data-current-page') || '1');
      const totalPages  = parseInt(container?.getAttribute('data-total-pages') || '1');

      if (currentPage < totalPages) {
        const nextPage = currentPage + 1;
        const url = new URL(window.location.href);
        url.searchParams.set('trang', nextPage);
        // Tải thêm sản phẩm nhưng không đẩy tham số trang lên URL (pushState = false)
        updateProducts(url.pathname + url.search, false, true);
      }
    }
  }, { rootMargin: '200px' });
  infiniteObserver.observe(sentinel);
}

/**
 * Xử lý click trên thẻ đang lọc (X) và Xóa tất cả
 */
function initAjaxLinks() {
  document.addEventListener('click', (e) => {
    const link = e.target.closest('.active-filter-tag a, .active-filters__clear');
    if (link && link.tagName === 'A' && link.getAttribute('href')) {
      e.preventDefault();
      updateProducts(link.getAttribute('href'), true, false);
    }
  });
}

/**
 * Xử lý Form và Toggle Radio Pills
 */
function initFormSubmit() {
  const form = document.getElementById('filterForm');
  if (!form) return;

  const triggerUpdate = () => {
    const url = new URL(window.location.href);
    const managedKeys = ['scenario', 'interface', 'dlspeed', 'poe', 'trang'];
    managedKeys.forEach(k => url.searchParams.delete(k));

    form.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
      url.searchParams.set(radio.name, radio.value);
    });

    updateProducts(url.pathname + url.search, true, false);
  };

  // Sử dụng delegation trên form để bắt sự kiện click chính xác
  form.addEventListener('click', (e) => {
    const pill = e.target.closest('.filter-pill');
    if (!pill) return;

    const radio = pill.querySelector('input[type="radio"]');
    if (!radio) return;

    // Ngăn chặn hành vi mặc định (bao gồm cả label click trigger input click)
    e.preventDefault();

    const wasChecked = radio.getAttribute('data-was-checked') === 'true';

    // 1. Reset nhóm radio
    form.querySelectorAll(`input[name="${radio.name}"]`).forEach(r => {
      r.checked = false;
      r.setAttribute('data-was-checked', 'false');
    });

    // 2. Toggle trạng thái
    if (!wasChecked) {
      radio.checked = true;
      radio.setAttribute('data-was-checked', 'true');
    } else {
      // Nếu đã chọn rồi thì bây giờ là bỏ chọn (đã reset ở trên)
      radio.checked = false;
      radio.setAttribute('data-was-checked', 'false');
    }

    triggerUpdate();
  });

  form.addEventListener('submit', (e) => e.preventDefault());
  syncFormWithUrl();
}

// ── UI Helpers ──
function initHeader() {
  const hamburger = document.getElementById('hamburger');
  const nav = document.getElementById('nav');
  const header = document.getElementById('header');
  hamburger?.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    hamburger.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  });
}
function initSidebar() {
  const sidebar = document.getElementById('filterSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (!sidebar) return;
  const close = () => { sidebar.classList.remove('open'); overlay?.classList.remove('show'); document.body.style.overflow = ''; };
  document.getElementById('openSidebar')?.addEventListener('click', () => { sidebar.classList.add('open'); overlay?.classList.add('show'); document.body.style.overflow = 'hidden'; });
  document.getElementById('closeSidebar')?.addEventListener('click', close);
  overlay?.addEventListener('click', close);
}
function initFilterToggle() {
  const toggleBtn = document.getElementById('filterToggle');
  const section = document.querySelector('.filter-section');
  if (!toggleBtn || !section) return;
  const isCollapsed = localStorage.getItem('filter_collapsed') === 'true';
  if (isCollapsed) section.classList.add('is-collapsed');
  toggleBtn.addEventListener('click', () => {
    const collapsed = section.classList.toggle('is-collapsed');
    const text = toggleBtn.querySelector('.collapse-text');
    if (text) text.textContent = collapsed ? 'Mở rộng' : 'Thu gọn';
    localStorage.setItem('filter_collapsed', collapsed);
  });
}
function initScrollTop() {
  const btn = document.getElementById('backTop');
  if (!btn) return;
  window.addEventListener('scroll', () => btn.classList.toggle('show', window.scrollY > 500), { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}
function setYear() {
  const el = document.getElementById('year');
  if (el) el.textContent = new Date().getFullYear();
}
