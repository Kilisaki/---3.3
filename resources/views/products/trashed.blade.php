@extends('layouts.app')

@section('title', 'Удалённые товары')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Удалённые товары</h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline-silver">Назад к товарам</a>
    </div>

    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="card card-gaming">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="text-silver">Автор: {{ $product->user->username ?? '—' }}</p>

                        <div class="d-flex gap-2">
                            @can('isAdmin')
                            <form action="{{ route('products.restore', $product->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-success">Восстановить</button>
                            </form>

                            <form action="{{ route('products.force-delete', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-blood-red">Удалить окончательно</button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection