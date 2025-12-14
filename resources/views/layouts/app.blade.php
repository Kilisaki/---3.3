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
        
        body {
            background-color: var(--night);
            color: var(--white-smoke);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
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
</body>
</html>