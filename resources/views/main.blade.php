@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Баннер -->
        <div id="carouselExampleControls" class="carousel slide mb-4" data-ride="carousel">
            <div class="carousel-inner">
                <!-- Здесь будут слайды баннера, их можно подгрузить из базы данных -->
                <div class="carousel-item active">
                    <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Первый слайд">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100" alt="Второй слайд">
                </div>
                <!-- Добавьте дополнительные слайды по мере необходимости -->
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>
        <!-- Товары -->
        <h2 class="mb-4">Новинки</h2>
        <div class="row">
            <!-- Вывод последних 4 товаров -->
            @foreach($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card ">
                        <div class="square-image-wrapper">
                            <img src="{{ asset('images/')}}/{{$product->photo_path}}" class="card-img-top square-image" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->cost }} руб.</p>
                            <form class="add-to-cart-form" data-available-quantity="{{ $product->count }}">
                                @csrf
                                <input class="item_id" type="hidden" value="{{$product->id}}">
                                <label for="quantity">Количество в шт.</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary minus-btn">-</button>
                                    <input type="number" min="1" class="form-control quantity-input" id="quantity" name="quantity" value="1">
                                    <button type="button" class="btn btn-outline-secondary plus-btn">+</button>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Кнопка для перехода в каталог -->
        <div class="text-center mt-4">
            <a href="{{ route('products') }}" class="btn btn-outline-primary">Перейти в каталог</a>
        </div>
        <br>
    </div>                    
@endsection
