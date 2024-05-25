@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Корзина</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
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
                    <div class="col-md-4 mb-4" id="cart-item-{{ $product->id }}">
                        <div class="card h-100">
                            <img src="{{ asset('images/' . $product->photo_path) }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">Цена за единицу: {{ $product->cost }} руб.</p>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-secondary" onclick="updateCart('{{ $product->id }}', 'decrease')">-</button>
                                    <p class="mx-3 mb-0">Количество: <span id="quantity-{{ $product->id }}">{{ $quantity }}</span></p>
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

            <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                @csrf
                <h3>Пункт получения</h3>
                <p>г. Пермь, ул. Ленина 76, этаж 2</p>

                <h3>Способ оплаты</h3>
                <div class="form-group">
                    <label for="payment">Выберите способ оплаты</label>
                    <select class="form-control" id="payment" name="payment_id">
                        @foreach($payments as $payment)
                            <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Оформить заказ</button>
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
@endsection