<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-smoke leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-eerie-black shadow sm:rounded-lg text-white-smoke">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-eerie-black shadow sm:rounded-lg text-white-smoke">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-sm text-white"
                >
                    Logout
                </button>
            </form>
            <div class="p-4 sm:p-8 bg-eerie-black shadow sm:rounded-lg text-white-smoke">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
