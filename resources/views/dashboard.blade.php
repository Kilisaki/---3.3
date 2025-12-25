<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white-smoke leading-tight">
                Dashboard
            </h2>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-sm text-white"
                >
                    Logout
                </button>
            </form>
        </div>
    </x-slot>           

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Информация о пользователе --}}
            <div class="bg-eerie-black p-6 rounded-lg text-white-smoke">
                <h3 class="text-lg font-semibold mb-2">Ваш профиль</h3>

                <div class="text-timberwolf space-y-1">
                    <p><strong>Имя:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    {{-- Без упоминания админа --}}
                </div>
            </div>

            {{-- Навигация --}}
            <div class="bg-eerie-black p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-white-smoke mb-4">
                    Навигация
                </h3>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('home') }}" class="nav-btn">Главная</a>
                    <a href="{{ route('products.index') }}" class="nav-btn">Товары</a>
                    <a href="{{ route('products.create') }}" class="nav-btn">Создать товар</a>
                    <a href="{{ route('users.index') }}" class="nav-btn">Пользователи</a>
                    <a href="{{ route('profile.edit') }}" class="nav-btn">Профиль</a>
                    
                    {{-- Ссылка для администратора --}}
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('products.trashed') }}" class="nav-btn bg-gray-700 hover:bg-gray-600">Архив товаров</a>
                    @endif
                </div>
            </div>

            {{-- Блок для администратора: Архив товаров --}}
            @if(auth()->user()->is_admin)
                <div class="bg-eerie-black p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-white-smoke mb-4">
                        Архив товаров
                    </h3>

                    <div class="mb-4">
                        <p class="text-timberwolf mb-4">
                            Просмотр и управление архивными записями товаров.
                        </p>
                        <a
                            href="{{ route('products.trashed') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-white font-medium"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            Перейти к архиву
                        </a>
                    </div>
                </div>
            @endif

            {{-- Список пользователей --}}
            <div class="bg-eerie-black p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-white-smoke mb-4">
                    Все пользователи
                </h3>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($users as $user)
                        <div class="bg-raisin-black p-4 rounded-lg">
                            <div class="mb-2">
                                <p class="text-white-smoke font-medium">
                                    {{ $user->name }}
                                </p>
                            </div>

                            <p class="text-timberwolf text-sm mb-3">
                                {{ '@' . $user->username }}
                            </p>

                            <a
                                href="{{ route('users.objects', $user->username) }}"
                                class="inline-block px-3 py-1 text-sm bg-indigo-600 hover:bg-indigo-700 rounded text-white"
                            >
                                Перейти в профиль
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>