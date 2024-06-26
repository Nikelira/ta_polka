@extends('layouts.app')

@section('content')
<div class="container">
        <!-- Баннер -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div id="carouselExampleControls" class="carousel slide mb-4" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/banner1.jpg') }}" class="d-block w-100" alt="Первый слайд">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/banner2.jpg') }}" class="d-block w-100" alt="Второй слайд">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>

        <h2 class="mb-4">Новинки</h2>
        <div class="row">
            <!-- Вывод последних 4 товаров -->
            @foreach($products as $product)
                <div class="col-6 col-md-3 mb-3 col-6-mobile">
                    <div class="card product-card">
                        <div class="square-image-wrapper">
                            <img src="{{ asset('images/')}}/{{$product->photo_path}}" class="card-img-top square-image" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->cost }} руб.</p>
                            @if(Auth::check())
                                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                    <form class="add-to-cart-form mt-auto">
                                        @csrf
                                        <input class="item_id" type="hidden" value="{{$product->id}}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-block">Добавить в корзину</button>
                                    </form>
                                @endif
                            @else
                                <form class="add-to-cart-form mt-auto">
                                    @csrf
                                    <input class="item_id" type="hidden" value="{{$product->id}}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-block">Добавить в корзину</button>
                                </form>
                            @endif
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

    <style>
        .modal-backdrop.show {
            opacity: 0; /* Делаем фон модального окна прозрачным */
        }
    </style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

