import './bootstrap';

import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Инициализация Swiper для баннеров
const initSwiper = () => {
    if (document.querySelector('.banner-swiper')) {
        new Swiper('.banner-swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    }
};

// Инициализация Bootstrap компонентов
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initSwiper();
    
    // Инициализация всех всплывающих подсказок
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Инициализация всех модальных окон
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });
});