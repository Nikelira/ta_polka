@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Мои заказы</h1>
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
    @if($orders->isEmpty())
        <p>У вас нет заказов.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                    <th>Товары</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->date->format('d.m.Y') }}</td>
                        <td>{{ $order->orderStatus->name }}</td>
                        <td>{{ $order->summa }} руб.</td>
                        <td>
                            <ul class="list-unstyled">
                                @foreach($order->compositions as $composition)
                                    <li>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/' . $composition->product->photo_path) }}" alt="{{ $composition->product->name }}" class="img-thumbnail mr-2" style="max-width: 100px;">
                                            <span>{{ $composition->product->name }} ({{ $composition->count }} шт.)</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($order->order_status_id == 1 || $order->order_status_id == 2)
                                <form action="{{ route('cabinet_zakaz.cancel', $order->id) }}" method="POST" onsubmit="return confirmCancel();">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Отказаться от заказа</button>
                                </form>
                            @endif
                            @if($order->order_status_id == 2)
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pickupCodeModal" onclick="showPickupCode('{{ $order->date->format('dmY') . $order->summa }}')">
                                    Код для получения товара
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="modal fade" id="pickupCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Получить заказ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="pickupCode"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
function confirmCancel() {
    return confirm('Вы точно уверены, что хотите отказаться от заказа?');
}

function showPickupCode(code) {
    document.getElementById('pickupCode').innerText = code;
}

</script>