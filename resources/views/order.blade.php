@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Корзина</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <br>
    
    <div id="cart-container">
        @if(empty($cart))
            <div class="alert alert-info">
                Ваша корзина пуста.
            </div>
        @else
            <h3>Товары в корзине</h3>
            <div class="row mb-3" id="cart-items">
                @php
                    $total = 0;
                @endphp
                @foreach($products as $product)
                    @php
                        $quantity = $cart[$product->id];
                        $cost = $product->cost * $quantity;
                        $total += $cost;
                    @endphp
                    <div class="col-6 col-md-3 mb-3 col-6-mobile" id="cart-item-{{ $product->id }}">
                    <div class="card product-card">
                        <div class="square-image-wrapper">
                            <img src="{{ asset('images/' . $product->photo_path) }}" class="card-img-top square-image" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">Цена за шт: {{ $product->cost }} руб.</p>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-secondary" onclick="updateCart('{{ $product->id }}', 'decrease')">-</button>
                                    <span class="mx-3 mb-0">Количество: <span id="quantity-{{ $product->id }}">{{ $quantity }}</span></span>
                                    <button class="btn btn-secondary" onclick="updateCart('{{ $product->id }}', 'increase')">+</button>
                                </div>
                                <p class="card-text mt-2">Сумма: <span id="cost-{{ $product->id }}">{{ $cost }}</span> руб.</p>
                                <button class="btn btn-danger" onclick="removeFromCart('{{ $product->id }}')">Удалить</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3" id="cart-summary">
                <div class="fs-4 fw-bold">
                    Итого: <span id="total">{{ $total }}</span> руб.
                </div>
                <form method="POST" action="{{ route('checkout.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Очистить корзину</button>
                </form>
            </div>

            <br>
            <br>
            <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <h3>Пункт получения</h3>
                        <p>г. Пермь, ул. Ленина 76, этаж 2</p>
                    </div>
                    <div class="col-md-6">
                        <h3>Способ оплаты</h3>
                        <div class="form-group">
                            <select class="form-control" id="payment" name="payment_id">
                                @foreach($payments as $payment)
                                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <br>
                <div class="text-center">
                <button type="submit" class="btn btn-primary">Оформить заказ</button></div>
            </form>
        @endif
    </div>
</div>


<script>
function updateCart(productId, action) {
    $.ajax({
        url: '{{ route("checkout.update", ":id") }}'.replace(':id', productId),
        type: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}',
            action: action
        },
        success: function(response) {
            if (response.success) {
                if (response.cart[productId] === undefined) {
                    $('#cart-item-' + productId).remove();
                } else {
                    const quantity = response.cart[productId];
                    const cost = response.cost;
                    $('#quantity-' + productId).text(quantity);
                    $('#cost-' + productId).text(cost);
                }
                $('#total').text(response.total);

                if ($.isEmptyObject(response.cart)) {
                    $('#cart-container').html('<div class="alert alert-info">Ваша корзина пуста.</div>');
                }
            } else {
                alert('Ошибка обновления корзины');
            }
        }
    });
}

function removeFromCart(productId) {
    $.ajax({
        url: '{{ route("checkout.remove", ":id") }}'.replace(':id', productId),
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#cart-item-' + productId).remove();
                $('#total').text(response.total);

                if ($.isEmptyObject(response.cart)) {
                    $('#cart-container').html('<div class="alert alert-info">Ваша корзина пуста.</div>');
                }
            } else {
                alert('Ошибка удаления товара из корзины');
            }
        }
    });
}

$(document).ready(function() {
    if ($('#cart-items').children().length === 0) {
        $('#cart-summary').hide();
        $('#checkout-form').hide();
    }
});
</script>

<style>
    h1{
        text-align:center;
    }
    
</style>
@endsection