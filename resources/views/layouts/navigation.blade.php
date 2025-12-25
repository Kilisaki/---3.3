<nav class="navbar navbar-dark bg-eerie-black">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container d-flex justify-content-between align-items-center">

        <!-- Бренд -->
        <a class="navbar-brand fw-bold text-imperial-red d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fas fa-gamepad me-2"></i>GamingPeriphery
        </a>

        <!-- Основные ссылки -->
        <ul class="navbar-nav d-flex flex-row gap-3 m-0 p-0 list-unstyled">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} text-white-smoke" href="{{ route('home') }}">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }} text-white-smoke" href="{{ route('products.index') }}">Магазин</a>
            </li>
        </ul>

        <!-- Действия пользователя -->
        <div class="d-flex align-items-center gap-3">

            @auth
                <!-- Добавить товар -->
                <a href="{{ route('products.create') }}" class="btn btn-gaming d-flex align-items-center">
                    <i class="fas fa-plus me-1"></i>Добавить товар
                </a>

                <!-- Иконка профиля -->
                <a href="{{ route('profile.edit') }}" class="nav-profile-link d-flex align-items-center" title="Профиль" aria-label="Профиль">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle nav-profile-img" width="36" height="36" alt="{{ Auth::user()->username }}">
                    @else
                        <i class="fas fa-user-circle fa-2x text-white-smoke"></i>
                    @endif
                </a>

                <!-- Dropdown с именем пользователя -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle text-white-smoke d-flex align-items-center" type="button" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ Auth::user()->username }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-eerie-black border-silver" aria-labelledby="profileMenu">
                        <li><a class="dropdown-item text-white-smoke" href="{{ route('profile.edit') }}">Профиль</a></li>
                        <li><a class="dropdown-item text-white-smoke" href="{{ route('users.objects', Auth::user()->username) }}">Мои товары</a></li>
                        <li><hr class="dropdown-divider border-silver"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-white-smoke">Выйти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-gaming d-flex align-items-center" title="Войдите, чтобы добавить товар">
                    <i class="fas fa-plus me-1"></i>Добавить товар
                </a>
                <a class="btn btn-outline-silver" href="{{ route('login') }}">Войти</a>
            @endguest

        </div>

    </div>
</nav>
