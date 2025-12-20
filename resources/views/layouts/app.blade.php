<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Loading Spinner -->
        <div id="pageLoader" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center">
            <div class="w-12 h-12 border-4 border-white/20 border-t-[#ff1f1f] rounded-full animate-spin" aria-hidden="true"></div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" aria-live="polite" aria-atomic="true"></div>

        <div class="min-h-screen bg-night">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-eerie-black border-b border-imperial-red">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
        @if (!file_exists(public_path('build/manifest.json')))
            <!-- CDN fallbacks only if Vite build doesn't exist -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
        @endif

        <!-- Global Modal Navigation Handler (uses functions from resources/js/app.js when available) -->
        <script>
            // Fallback openProductModal for when app.js not executed yet
            if (!window.openProductModal) {
                window.openProductModal = function(productId) {
                    const modalElement = document.getElementById('productModal' + productId);
                    if (!modalElement) return;
                    if (window.bootstrap && window.bootstrap.Modal) {
                        let modal = window.bootstrap.Modal.getInstance(modalElement);
                        if (!modal) modal = new window.bootstrap.Modal(modalElement);
                        modal.show();
                    } else {
                        modalElement.classList.add('show');
                        modalElement.style.display = 'block';
                    }
                };
            }

            
        </script>

        <!-- Page loader and toast helpers -->
        <script>
            function hidePageLoader() {
                const loader = document.getElementById('pageLoader');
                if (loader) loader.classList.add('hidden');
            }

            if (document.readyState === 'complete') {
                hidePageLoader();
            } else {
                window.addEventListener('load', hidePageLoader);
                setTimeout(hidePageLoader, 3000);
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Attach link click to show loader
                const links = document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.closest('form') || this.hasAttribute('data-bs-toggle')) return;
                        const href = this.getAttribute('href');
                        if (href && !href.startsWith('javascript:')) {
                            const loader = document.getElementById('pageLoader');
                            if (loader) loader.classList.remove('hidden');
                        }
                    });
                });

                // Show loader on form submit (non-AJAX)
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        if (form.hasAttribute('data-ajax') || form.hasAttribute('data-no-loader')) return;
                        const loader = document.getElementById('pageLoader');
                        if (loader) loader.classList.remove('hidden');
                    });
                });
            });
        </script>

        {{-- Render page scripts pushed from views (e.g., custom modal/form handling) --}}
        @stack('scripts')
    </body>
</html>
