'use strict';

// =============================================
// DANH SÁCH SẢN PHẨM — danh-sach-san-pham.js
// =============================================

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initSidebar();
  initScrollTop();
  setYear();
  initFormSubmit();
  initFilterToggle();
});

// ── Header (hamburger + dropdown + scroll) ──
function initHeader() {
  const hamburger = document.getElementById('hamburger');
  const nav       = document.getElementById('nav');
  const header    = document.getElementById('header');

  hamburger?.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    hamburger.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  });

  document.addEventListener('click', (e) => {
    if (nav?.classList.contains('open') && !header?.contains(e.target)) {
      nav.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      nav?.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  // Dropdown
  document.querySelectorAll('.has-dropdown').forEach(item => {
    const link     = item.querySelector('.nav__link--arrow');
    const dropdown = item.querySelector('.dropdown');
    const isDesktop = () => window.innerWidth >= 768;

    const show = () => { clearTimeout(item._t); item.classList.add('open'); };
    const tryHide = (rel) => {
      if (item.contains(rel) || dropdown?.contains(rel)) return;
      item._t = setTimeout(() => item.classList.remove('open'), 120);
    };

    item.addEventListener('mouseenter', e => { if (isDesktop()) show(); });
    item.addEventListener('mouseleave', e => { if (isDesktop()) tryHide(e.relatedTarget); });
    dropdown?.addEventListener('mouseenter', e => { if (isDesktop()) show(); });
    dropdown?.addEventListener('mouseleave', e => { if (isDesktop()) tryHide(e.relatedTarget); });

    link?.addEventListener('click', e => {
      if (isDesktop()) return;
      e.preventDefault();
      document.querySelectorAll('.has-dropdown.open')
        .forEach(o => { if (o !== item) o.classList.remove('open'); });
      item.classList.toggle('open');
    });
  });

  // Header shadow + ẩn khi scroll xuống
  let lastY = 0;
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    header?.classList.toggle('scrolled', y > 60);
    if (y > 200 && y > lastY + 8)      header?.classList.add('header--hidden');
    else if (y < lastY - 8)             header?.classList.remove('header--hidden');
    lastY = y;
  }, { passive: true });
}

// ── Sidebar filter (mobile drawer) ──
function initSidebar() {
  const sidebar = document.getElementById('filterSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const openBtn = document.getElementById('openSidebar');
  const closeBtn = document.getElementById('closeSidebar');

  if (!sidebar) return;

  const open = () => {
    sidebar.classList.add('open');
    overlay?.classList.add('show');
    document.body.style.overflow = 'hidden';
  };

  const close = () => {
    sidebar.classList.remove('open');
    overlay?.classList.remove('show');
    document.body.style.overflow = '';
  };

  openBtn?.addEventListener('click', open);
  closeBtn?.addEventListener('click', close);
  overlay?.addEventListener('click', close);

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') close();
  });

  const mq = window.matchMedia('(min-width: 1024px)');
  const onResize = (e) => {
    if (e.matches) close();
  };
  mq.addEventListener('change', onResize);
}

// ── Form submit ──
function initFormSubmit() {
  const form = document.getElementById('filterForm');
  if (!form) return;

  form.querySelectorAll('input[type="checkbox"]').forEach(el => {
    el.addEventListener('change', () => {
      form.submit();
    });
  });
}

// ── Filter Toggle (Collapse/Expand) ──
function initFilterToggle() {
  const toggleBtn = document.getElementById('filterToggle');
  const section   = document.querySelector('.filter-section');
  const text      = toggleBtn?.querySelector('.collapse-text');

  if (!toggleBtn || !section) return;

  const isCollapsed = localStorage.getItem('filter_collapsed') === 'true';
  if (isCollapsed) {
    section.classList.add('is-collapsed');
    if (text) text.textContent = 'Mở rộng';
  }

  toggleBtn.addEventListener('click', () => {
    const collapsed = section.classList.toggle('is-collapsed');
    if (text) text.textContent = collapsed ? 'Mở rộng' : 'Thu gọn';
    localStorage.setItem('filter_collapsed', collapsed);
  });
}

// ── Jump to page ──
function jumpToPage(maxPage) {
  const input = document.getElementById('pageJumpInput');
  if (!input) return;

  let page = parseInt(input.value);
  if (isNaN(page) || page < 1) page = 1;
  if (page < 1) page = 1;
  if (page > maxPage) page = maxPage;

  const url   = new URL(window.location.href);
  url.searchParams.set('trang', page);
  window.location.href = url.toString();
}

// ── Scroll to top ──
function initScrollTop() {
  const btn = document.getElementById('backTop');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    btn.classList.toggle('show', window.scrollY > 500);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// ── Footer year ──
function setYear() {
  const el = document.getElementById('year');
  if (el) el.textContent = new Date().getFullYear();
}
