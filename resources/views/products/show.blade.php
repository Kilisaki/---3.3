
@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('products.index') }}" class="text-silver text-decoration-none mb-3 d-inline-block">
                ← Вернуться в магазин
            </a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            @include('products.partials.show', ['product' => $product])
        </div>
    </div>
</div>
@endsection
