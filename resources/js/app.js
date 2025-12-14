import './bootstrap';

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

// Экспортируем Swiper для использования в blade-шаблонах
window.Swiper = Swiper;

// Инициализация Swiper для баннеров
const initSwiper = () => {
    const bannerSwiper = document.querySelector('.banner-swiper');
    if (bannerSwiper && !bannerSwiper.swiper) {
        new Swiper('.banner-swiper', {
            modules: [Navigation, Pagination, Autoplay, EffectFade],
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
            effect: 'fade',
            fadeEffect: {
                crossFade: true
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