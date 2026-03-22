'use strict';

// =============================================
// CISCO VN — MAIN.JS (PHP render version)
// PHP đã render HTML trực tiếp từ CSDL.
// JS chỉ xử lý: header, scroll, animation.
// =============================================

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initScrollTop();
  initScrollAnimation();
});

// ── Header: hamburger + dropdown + scroll ──
function initHeader() {
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

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      nav?.classList.remove('open');
      hamburger?.classList.remove('open');
      document.body.style.overflow = '';
      document.querySelectorAll('.has-dropdown.open')
        .forEach(el => el.classList.remove('open'));
    }
  });

  // Dropdown desktop hover / mobile click
  document.querySelectorAll('.has-dropdown').forEach(item => {
    const link      = item.querySelector('.nav__link--arrow');
    const dropdown  = item.querySelector('.dropdown');
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

  // Shadow + ẩn header khi scroll xuống nhanh
  let lastY = 0;
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    header?.classList.toggle('scrolled', y > 60);
    if (y > 200 && y > lastY + 8)  header?.classList.add('header--hidden');
    else if (y < lastY - 8)         header?.classList.remove('header--hidden');
    lastY = y;
  }, { passive: true });
}

// ── Scroll to top ──
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

// ── Fade-in stagger khi scroll đến phần tử ──
function initScrollAnimation() {
  const items = document.querySelectorAll(
    '.cat-card, .prod-card, .blog-card, .why-us__item, .hcard'
  );

  if (!('IntersectionObserver' in window)) {
    items.forEach(el => { el.style.opacity = '1'; });
    return;
  }

  items.forEach(el => {
    el.style.opacity    = '0';
    el.style.transform  = 'translateY(20px)';
    el.style.transition = 'opacity .4s ease, transform .4s ease';
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const idx   = Array.from(items).indexOf(entry.target);
      const delay = (idx % 4) * 70;
      setTimeout(() => {
        entry.target.style.opacity   = '1';
        entry.target.style.transform = 'translateY(0)';
      }, delay);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

  items.forEach(el => observer.observe(el));
}
