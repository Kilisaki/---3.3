@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<div class="container">
    <h1 class="mb-4">Пользователи</h1>

    <div class="list-group">
        @foreach($users as $user)
            <a href="{{ route('users.objects', $user->username) }}" class="list-group-item list-group-item-action">
                <strong>{{ $user->name }}</strong> — <code>{{ $user->username }}</code>
            </a>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection