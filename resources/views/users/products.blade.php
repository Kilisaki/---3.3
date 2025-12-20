@extends('layouts.app')

@section('title', 'Товары пользователя ' . ($user->username ?? ''))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Товары пользователя: {{ $user->name }} ({{ $user->username }})</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-silver">Все пользователи</a>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4">
                <div class="card card-gaming">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-silver">Просмотреть</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <h3>Товары пользователя не найдены</h3>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection