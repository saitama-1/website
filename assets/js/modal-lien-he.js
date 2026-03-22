/**
 * modal-lien-he.js
 * Load 1 lần, hoạt động với MỌI nút liên hệ trên toàn site.
 *
 * Cách dùng trên HTML:
 *   <button data-modal="lien-he">Liên hệ</button>
 *   <button data-modal="lien-he" data-ma="C9200-24T-A" data-ten="Cisco Catalyst C9200-24T-A">Báo giá</button>
 *   <a href="#" data-modal="lien-he">Tư vấn ngay</a>
 */

'use strict';

(function () {
  const MODAL_ID    = 'modalLienHe';
  const CLOSE_ID    = 'modalLienHeClose';
  const SP_WRAP_ID  = 'modalSanPham';
  const SP_MA_ID    = 'modalMaSanPham';
  const SP_TEN_ID   = 'modalTenSanPham';

  let overlay, modal, closeBtn, spWrap, spMa, spTen;

  // ── Khởi tạo sau khi DOM sẵn sàng ──
  document.addEventListener('DOMContentLoaded', init);

  function init() {
    overlay  = document.getElementById(MODAL_ID);
    closeBtn = document.getElementById(CLOSE_ID);
    spWrap   = document.getElementById(SP_WRAP_ID);
    spMa     = document.getElementById(SP_MA_ID);
    spTen    = document.getElementById(SP_TEN_ID);

    if (!overlay) return; // Modal chưa được include vào trang

    modal = overlay.querySelector('.modal');

    // Event delegation — bắt TẤT CẢ nút có data-modal="lien-he"
    document.addEventListener('click', function (e) {
      const trigger = e.target.closest('[data-modal="lien-he"]');
      if (trigger) {
        e.preventDefault();
        openModal(
          trigger.dataset.ma  || '',
          trigger.dataset.ten || ''
        );
      }
    });

    // Đóng bằng nút X
    closeBtn?.addEventListener('click', closeModal);

    // Đóng khi click vào overlay (ngoài modal)
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal();
    });

    // Đóng bằng ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('open')) {
        closeModal();
      }
    });
  }

  // ── Mở modal ──
  function openModal(ma, ten) {
    // Cập nhật thông tin sản phẩm nếu có
    if (ma || ten) {
      spMa.textContent  = ma;
      spTen.textContent = ten;
      spWrap.style.display = 'flex';
    } else {
      spWrap.style.display = 'none';
    }

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Focus vào modal để screen reader nhận diện
    modal?.focus();
  }

  // ── Đóng modal ──
  function closeModal() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  // Expose ra ngoài nếu cần gọi từ file JS khác
  window.openModalLienHe  = openModal;
  window.closeModalLienHe = closeModal;

})();
