@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <h2 class="mb-4">Товары</h2>
        <div class="row">
            <div class="col-md-3">
                <!-- Фиксированные категории товара -->
                <div class="card mb-4">
                    <div class="card-header">Категории товара</div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="{{ route('products')}}">Все товары</a></li>
                        @foreach($productCategories as $category)
                            <li class="list-group-item"><a href="{{ route('products.category', ['category_id' => $category->id]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Поисковик по товарам -->
                <div class="input-group mb-3">
                <form action="{{ route('products')}}" method="GET" class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Поиск по товарам" aria-label="Поиск по всем товарам" aria-describedby="button-addon2" name="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Поиск</button>
                        <a href="{{ route('products') }}" class="btn btn-outline-secondary">Очистить</a>
                    </div>
                </form>
                </div>
                <div class="row">    
                    @foreach($products as $product)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 d-flex flex-column">
                            <img src="{{ asset('images/')}}/{{$product->photo_path}}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->cost }} руб.</p>
                                <form class="add-to-cart-form flex-fill">
                                    @csrf
                                    <input class="item_id" type="hidden" value=" {{$product->id}}">
                                    <label for="quantity">Количество в шт.</label>
                                    <input type="number" min="1" class="form-control" placeholder="Leave a comment here" id="quantity" name="quantity" value="1">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-auto">Добавить в корзину</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    
@endsection
