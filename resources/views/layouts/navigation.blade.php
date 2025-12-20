<nav class="navbar navbar-expand-lg navbar-dark bg-eerie-black">
    <div class="container">
        <a class="navbar-brand fw-bold text-imperial-red" href="{{ route('home') }}">
            <i class="fas fa-gamepad me-2"></i>GamingPeriphery
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Магазин</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-gaming">
                        <i class="fas fa-plus me-1"></i>Добавить товар
                    </a>

                    <!-- Direct profile icon that navigates to profile.edit -->
                    <a href="{{ route('profile.edit') }}" class="ms-3 d-flex align-items-center nav-profile-link" title="Профиль" aria-label="Профиль">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle nav-profile-img" width="36" height="36" alt="{{ Auth::user()->username }}">
                        @else
                            <i class="fas fa-user-circle fa-2x text-white-smoke"></i>
                        @endif
                    </a>

                    <div class="dropdown ms-2 d-none d-md-block">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-white-smoke p-0" href="#" role="button" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="ms-2 d-none d-md-inline">{{ Auth::user()->username }}</span>
                        </a>
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
                @else
                    <a href="{{ route('login') }}" class="btn btn-gaming ms-1" title="Войдите, чтобы добавить товар">
                        <i class="fas fa-plus me-1"></i>Добавить товар
                    </a>
                    <a class="btn btn-outline-silver ms-3" href="{{ route('login') }}">Войти</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
