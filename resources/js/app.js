import './bootstrap';

import Alpine from 'alpinejs';

// Swiper
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

Swiper.use([Navigation, Pagination, Autoplay]);

window.Alpine = Alpine;

Alpine.start();

// ============================================
// TOAST NOTIFICATION SYSTEM
// ============================================
window.showToast = function(message, type = 'info') {
    // Create or use existing toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container') || document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(toastContainer);
    }

    // Determine toast colors based on type
    const colors = {
        success: { bg: '#28a745', border: '#20c997', icon: '✓' },
        error: { bg: '#dc3545', border: '#c82333', icon: '✕' },
        warning: { bg: '#ffc107', border: '#e0a800', icon: '⚠' },
        info: { bg: '#17a2b8', border: '#138496', icon: 'ℹ' }
    };

    const config = colors[type] || colors.info;

    // Create toast element
    const toast = document.createElement('div');
    toast.style.cssText = `
        background-color: ${config.bg};
        border-left: 4px solid ${config.border};
        color: white;
        padding: 16px;
        margin-bottom: 10px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        font-size: 14px;
    `;

    toast.innerHTML = `
        <span style="font-size: 18px; font-weight: bold;">${config.icon}</span>
        <span>${message}</span>
    `;

    toastContainer.appendChild(toast);

    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
};

// Keyboard navigation between open product modals
// Keyboard navigation between open product modals (attach after DOM ready)
function attachModalKeyboardNavigation() {
    // Use capture phase and stop immediate propagation so modals take precedence over Swiper
    document.addEventListener('keydown', function(event) {
        const openModal = document.querySelector('.modal.show');
        if (!openModal) return;

        const modalId = openModal.id;
        const match = modalId.match(/(\d+)$/);
        if (!match) return;
        const currentId = parseInt(match[1], 10);

        // gather available product modal ids (unique)
        const modalNodes = Array.from(document.querySelectorAll('[id^="productModal"]'));
        const ids = Array.from(new Set(modalNodes.map(n => {
            const m = n.id.match(/(\d+)$/);
            return m ? parseInt(m[1], 10) : null;
        }).filter(Boolean))).sort((a, b) => a - b);
        if (ids.length === 0) return;

        function openById(id) {
            const elem = document.getElementById('productModal' + id);
            if (!elem) return;
            const currentInstance = window.bootstrap?.Modal?.getInstance(openModal);
            if (currentInstance) currentInstance.hide();
            window.openProductModal(id);
        }

        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            // prevent other handlers (like Swiper) from acting when a modal is open
            event.preventDefault();
            try { event.stopImmediatePropagation(); } catch (e) {}
            // navigate
            if (event.key === 'ArrowRight') {
                const idx = ids.indexOf(currentId);
                const next = ids[(idx + 1) % ids.length];
                openById(next);
            } else {
                const idx = ids.indexOf(currentId);
                const prev = ids[(idx - 1 + ids.length) % ids.length];
                openById(prev);
            }
        }
    }, true);
}

// Ensure modal close buttons always hide the modal (fallback for edge cases)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-bs-dismiss="modal"]');
    if (!btn) return;
    const modalEl = btn.closest('.modal');
    if (!modalEl) return;
    if (window.bootstrap && window.bootstrap.Modal) {
        const inst = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
        if (inst) inst.hide();
    } else {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
    }
}, false);

// Add CSS animations for toast
const style = document.createElement('style');
style.innerHTML = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// SWIPER INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const banner = document.querySelector('.banner-swiper');
    if (banner) {
        new Swiper(banner, {
            loop: true,
            autoplay: { delay: 4000 },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
    }
    
    // ============================================
    // BOOTSTRAP MODAL INITIALIZATION
    // ============================================
    if (window.bootstrap && window.bootstrap.Modal) {
        // Remove duplicate modal elements with the same id (keep first)
        const seen = new Set();
        const allModals = Array.from(document.querySelectorAll('.modal'));
        allModals.forEach(m => {
            if (!m.id) return;
            if (seen.has(m.id)) {
                m.remove();
                return;
            }
            seen.add(m.id);
        });

        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (!modal._bs_instance) {
                const inst = new window.bootstrap.Modal(modal, { backdrop: true, keyboard: true });
                modal._bs_instance = inst;
            }
        });

        // Attach keyboard navigation once modals are initialized
        attachModalKeyboardNavigation();
        // Attach keyboard navigation for product cards (left/right to move, Enter/Space to open)
        attachCardKeyboardNavigation();
    }
});

// Keyboard navigation between product cards on the listing (when no modal is open)
function attachCardKeyboardNavigation() {
    document.addEventListener('keydown', function(event) {
        // If a modal is open, let modal handlers take precedence
        if (document.querySelector('.modal.show')) return;
        // Ignore when typing in inputs
        const active = document.activeElement;
        if (!active) return;
        const tag = active.tagName;
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) return;

        const cards = Array.from(document.querySelectorAll('.product-card'));
        if (cards.length === 0) return;

        const focusedIndex = cards.indexOf(active);

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            try { event.stopImmediatePropagation(); } catch (e) {}
            const nextIndex = focusedIndex >= 0 ? (focusedIndex + 1) % cards.length : 0;
            const next = cards[nextIndex];
            if (next) {
                next.focus();
                cards.forEach(c => c.classList.remove('keyboard-focused'));
                next.classList.add('keyboard-focused');
                next.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        } else if (event.key === 'ArrowLeft') {
            event.preventDefault();
            try { event.stopImmediatePropagation(); } catch (e) {}
            const prevIndex = focusedIndex >= 0 ? (focusedIndex - 1 + cards.length) % cards.length : (cards.length - 1);
            const prev = cards[prevIndex];
            if (prev) {
                prev.focus();
                cards.forEach(c => c.classList.remove('keyboard-focused'));
                prev.classList.add('keyboard-focused');
                prev.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        } else if (event.key === 'Enter' || event.key === ' ') {
            if (focusedIndex >= 0) {
                event.preventDefault();
                const id = parseInt(cards[focusedIndex].dataset.productId, 10);
                if (id) window.openProductModal(id);
            }
        }
    });
} 

    // Global helper to open product modals by id
    window.openProductModal = function(productId) {
        const modalElement = document.getElementById('productModal' + productId);
        if (!modalElement) return;

        if (window.bootstrap && window.bootstrap.Modal) {
            let modal = window.bootstrap.Modal.getInstance(modalElement);
            if (!modal) modal = new window.bootstrap.Modal(modalElement, { backdrop: true, keyboard: true });

            // store instance to avoid double initialization
            modalElement._bs_instance = modal;

            // Initialize carousel on show
            modalElement.addEventListener('shown.bs.modal', function() {
                const carousel = modalElement.querySelector('.carousel');
                if (carousel && !carousel._carousel) {
                    const carouselInstance = new window.bootstrap.Carousel(carousel);
                    carousel._carousel = carouselInstance;
                }
            }, { once: true });

            modal.show();
        } else {
            // Fallback: simply display
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
        }
    };
