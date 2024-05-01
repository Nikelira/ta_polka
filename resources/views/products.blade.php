@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <h2 class="mb-4">Товары</h2>
        <div class="row">
        @foreach($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="{{ asset('images/')}}/{{$product->photo_path}}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->cost }} руб.</p>
                            <form class="add-to-cart-form">
                                @csrf
                                <input class="item_id" type="hidden" value=" {{$product->id}}">
                                <label for="quantity">Количество в шт.</label>
                                <input type="number" min="1" class="form-control" placeholder="Leave a comment here" id="quantity" name="quantity" value="1">
                                <br>
                                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
