<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Gaming Periphery Shop')</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        :root {
            --night: #0b090a;
            --eerie-black: #161a1d;
            --blood-red: #660708;
            --cornell-red: #a4161a;
            --cornell-red-2: #ba181b;
            --imperial-red: #e5383b;
            --silver: #b1a7a6;
            --timberwolf: #d3d3d3;
            --white-smoke: #f5f3f4;
            --white: #ffffff;
        }
        
        .bg-night { background-color: var(--night); }
        .bg-eerie-black { background-color: var(--eerie-black); }
        .bg-blood-red { background-color: var(--blood-red); }
        .bg-cornell-red { background-color: var(--cornell-red); }
        .text-imperial-red { color: var(--imperial-red); }
        .text-timberwolf { color: var(--timberwolf); }
        .text-white-smoke { color: var(--white-smoke); }
        
        .navbar {
            background-color: var(--eerie-black) !important;
            border-bottom: 2px solid var(--imperial-red);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .btn-gaming {
            background-color: var(--cornell-red);
            color: var(--white-smoke);
            border: none;
            transition: all 0.3s;
        }
        
        .btn-gaming:hover {
            background-color: var(--imperial-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(229, 56, 59, 0.3);
        }
        
        .card-gaming {
            background-color: var(--eerie-black);
            border: 1px solid var(--silver);
            border-radius: 8px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card-gaming:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(229, 56, 59, 0.2);
            border-color: var(--imperial-red);
        }
        
        .btn-outline-silver {
            border-color: var(--silver);
            color: var(--silver);
        }
        
        .btn-outline-silver:hover {
            background-color: var(--silver);
            color: var(--night);
        }
        
        .btn-outline-timberwolf {
            border-color: var(--timberwolf);
            color: var(--timberwolf);
        }
        
        .btn-outline-timberwolf:hover {
            background-color: var(--timberwolf);
            color: var(--night);
        }
        
        .btn-outline-blood-red {
            border-color: var(--blood-red);
            color: var(--blood-red);
        }
        
        .btn-outline-blood-red:hover {
            background-color: var(--blood-red);
            color: var(--white-smoke);
        }
        
        body {
            background-color: var(--night);
            color: var(--white-smoke);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Loading Spinner */
        #pageLoader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(11, 9, 10, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        #pageLoader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--eerie-black);
            border-top-color: var(--imperial-red);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Toast Container */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1055;
        }
        
        .toast {
            min-width: 300px;
        }
        
        .toast-body {
            background-color: var(--eerie-black) !important;
            color: var(--white-smoke) !important;
        }
        
        .toast-header {
            background-color: var(--eerie-black);
            color: var(--white-smoke);
            border-bottom: 1px solid var(--silver);
        }
        
        .toast-header.bg-success {
            background-color: #28a745 !important;
            color: white !important;
        }
        
        .toast-header.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }
        
        .toast-header.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
        
        .toast-header.bg-info {
            background-color: #17a2b8 !important;
            color: white !important;
        }
        
        /* Fix Bootstrap Alert Colors */
        .alert {
            background-color: var(--eerie-black);
            border-color: var(--silver);
            color: var(--white-smoke);
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border-color: #28a745;
            color: #90ee90;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #ff6b7a;
        }
        
        .alert-info {
            background-color: rgba(23, 162, 184, 0.2);
            border-color: #17a2b8;
            color: #7dd3e8;
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.2);
            border-color: #ffc107;
            color: #ffd966;
        }
        
        /* Fix card title text colors */
        .card-title {
            color: var(--white-smoke) !important;
        }
        
        .card-text {
            color: var(--timberwolf) !important;
        }
        
        /* Fix table text colors */
        .table {
            color: var(--white-smoke);
        }
        
        .table td, .table th {
            color: inherit;
        }
        
        /* Fix heading colors */
        h1, h2, h3, h4, h5, h6 {
            color: var(--white-smoke);
        }
        
        /* Fix pagination */
        .page-link {
            background-color: var(--eerie-black);
            border-color: var(--silver);
            color: var(--white-smoke);
        }
        
        .page-link:hover {
            background-color: var(--cornell-red);
            border-color: var(--imperial-red);
            color: var(--white-smoke);
        }
        
        .page-item.active .page-link {
            background-color: var(--imperial-red);
            border-color: var(--imperial-red);
            color: var(--white-smoke);
        }
        
        /* Fix footer */
        footer {
            color: var(--white-smoke) !important;
        }
        
        footer h5, footer p {
            color: var(--white-smoke) !important;
        }
        
        /* Modal Styles - ЖЕСТКИЕ СТИЛИ ДЛЯ МОДАЛЕЙ */
        .modal-content {
            background-color: var(--eerie-black) !important;
            color: var(--white-smoke) !important;
            border: 1px solid var(--silver) !important;
        }
        
        .modal-header {
            background-color: var(--eerie-black) !important;
            border-bottom: 1px solid var(--silver) !important;
            color: var(--white-smoke) !important;
        }
        
        .modal-header .modal-title {
            color: var(--white-smoke) !important;
            font-weight: 600;
        }
        
        .modal-body {
            background-color: var(--eerie-black) !important;
            color: var(--white-smoke) !important;
        }
        
        .modal-body * {
            background-color: transparent !important;
        }
        
        .modal-body .carousel-inner {
            background-color: var(--night) !important;
        }
        
        .modal-body .carousel-item {
            background-color: var(--night) !important;
        }
        
        .modal-footer {
            background-color: var(--eerie-black) !important;
            border-top: 1px solid var(--silver) !important;
        }
        
        .modal-backdrop {
            background-color: rgba(11, 9, 10, 0.7) !important;
        }
        
        .btn-close-white {
            filter: brightness(0) invert(1) !important;
        }
        
        /* Table в модале */
        .modal-body .table {
            color: var(--white-smoke) !important;
            background-color: var(--eerie-black) !important;
        }
        
        .modal-body .table-borderless {
            background-color: transparent !important;
        }
        
        .modal-body .table td,
        .modal-body .table th {
            color: var(--white-smoke) !important;
            border-color: var(--silver) !important;
            background-color: transparent !important;
        }
        
        /* Текст в модале */
        .modal-body h1,
        .modal-body h2,
        .modal-body h3,
        .modal-body h4,
        .modal-body h5,
        .modal-body h6 {
            color: var(--white-smoke) !important;
        }
        
        .modal-body p,
        .modal-body span,
        .modal-body div {
            color: var(--white-smoke) !important;
        }
        
        .modal-body .text-timberwolf {
            color: var(--timberwolf) !important;
        }
        
        .modal-body .text-imperial-red {
            color: var(--imperial-red) !important;
        }
        
        .modal-body .text-silver {
            color: var(--silver) !important;
        }
        
        /* Карусель в модале */
        .modal-body .carousel-control-prev-icon,
        .modal-body .carousel-control-next-icon {
            filter: brightness(0) invert(1) !important;
        }
        
        /* Бейджи в модале */
        .modal-body .badge {
            background-color: var(--cornell-red) !important;
            color: var(--white-smoke) !important;
        }
        
        /* Кнопки в модале */
        .modal-body .btn-gaming {
            background-color: var(--cornell-red) !important;
            color: var(--white-smoke) !important;
            border: none !important;
        }
        
        .modal-body .btn-gaming:hover {
            background-color: var(--imperial-red) !important;
            color: var(--white-smoke) !important;
        }
        
        .modal-body .btn-outline-silver {
            border-color: var(--silver) !important;
            color: var(--silver) !important;
            background-color: transparent !important;
        }
        
        .modal-body .btn-outline-silver:hover {
            background-color: var(--silver) !important;
            color: var(--night) !important;
        }
        
        .modal-body .btn-outline-timberwolf {
            border-color: var(--timberwolf) !important;
            color: var(--timberwolf) !important;
            background-color: transparent !important;
        }
        
        .modal-body .btn-outline-timberwolf:hover {
            background-color: var(--timberwolf) !important;
            color: var(--night) !important;
        }
        
        .modal-body .btn-outline-blood-red {
            border-color: var(--blood-red) !important;
            color: var(--blood-red) !important;
            background-color: transparent !important;
        }
        
        .modal-body .btn-outline-blood-red:hover {
            background-color: var(--blood-red) !important;
            color: var(--white-smoke) !important;
        }
        
        /* Миниатюры */
        .modal-body .img-thumbnail {
            border-color: var(--silver) !important;
            background-color: var(--night) !important;
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div id="pageLoader">
        <div class="spinner"></div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container" aria-live="polite" aria-atomic="true"></div>
    
    <!-- Навигационная панель -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold text-imperial-red" href="{{ route('home') }}">
                <i class="fas fa-gamepad me-2"></i>GamingPeriphery
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                           href="{{ route('home') }}">
                            Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                           href="{{ route('products.index') }}">
                            Магазин
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex">
                    <a href="{{ route('products.create') }}" 
                       class="btn btn-gaming">
                        <i class="fas fa-plus me-1"></i>Добавить товар
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Основной контент -->
    <main class="py-4">
        <div class="container">
            
            @yield('content')
        </div>
    </main>
    
    <!-- Футер -->
    <footer class="bg-eerie-black text-white-smoke py-4 mt-5 border-top border-imperial-red">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Gaming Periphery Shop</h5>
                    <p>Лучшая игровая периферия для настоящих геймеров</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} Все права защищены</p>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    
<script>
    // Page Loader - исправленная версия
    function hidePageLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) {
            loader.classList.add('hidden');
        }
    }

    // Скрываем loader когда страница загружена
    if (document.readyState === 'complete') {
        hidePageLoader();
    } else {
        window.addEventListener('load', hidePageLoader);
        // Таймаут на случай, если load не срабатывает
        setTimeout(hidePageLoader, 3000);
    }

    // Show loader on page navigation
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"]):not([target="_blank"])');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                // Skip if it's a form submission or external link
                if (this.closest('form') || this.hasAttribute('data-bs-toggle')) {
                    return;
                }
                const href = this.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                    const loader = document.getElementById('pageLoader');
                    if (loader) {
                        loader.classList.remove('hidden');
                    }
                }
            });
        });

        // Handle form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const loader = document.getElementById('pageLoader');
                if (loader) {
                    loader.classList.remove('hidden');
                }
            });
        });
    });

    // Toast functionality - исправленная версия
    function showToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        const toastId = 'toast-' + Date.now();
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const bgClass = type === 'error' ? 'danger' : type;
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center bg-${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-white">
                        <i class="fas ${icons[type] || icons.info} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        
        // Используем Bootstrap Toast если доступен
        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();
                
                toastElement.addEventListener('hidden.bs.toast', function() {
                    if (toastElement && toastElement.parentNode) {
                        toastElement.remove();
                    }
                });
                return;
            }
        } catch (error) {
            console.warn('Bootstrap Toast ошибка:', error);
        }
        
        // Fallback - CSS-based автоскрытие
        showFallbackToast(toastElement);
    }

    // Fallback функция для тостов
    function showFallbackToast(element) {
        element.classList.add('show');
        element.style.display = 'block';
        element.style.opacity = '1';
        element.style.transition = 'opacity 0.3s ease';
        
        // Автоматическое скрытие через 5 секунд
        const hideTimeout = setTimeout(() => {
            element.style.opacity = '0';
            setTimeout(() => {
                if (element && element.parentNode) {
                    element.remove();
                }
            }, 300);
        }, 5000);
        
        // Закрытие по кнопке
        const closeBtn = element.querySelector('[data-bs-dismiss="toast"]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(hideTimeout);
                element.style.opacity = '0';
                setTimeout(() => {
                    if (element && element.parentNode) {
                        element.remove();
                    }
                }, 300);
            });
        }
    }

    // Создание контейнера если его нет
    function createToastContainer() {
        let container = document.querySelector('.toast-container');
        if (container) {
            return container;
        }
        
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'true');
        document.body.appendChild(container);
        return container;
    }

    // Инициализируем при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        // Показываем тосты из сессии с задержкой
        setTimeout(() => {
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast('{{ $error }}', 'error');
                @endforeach
            @endif
        }, 300);
    });
</script>
</body>
</html>