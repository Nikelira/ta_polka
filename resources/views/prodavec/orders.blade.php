@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('orders_prodavec.index', ['category' => 'active']) }}" class="list-group-item list-group-item-action {{ $category == 'active' ? 'active' : '' }}">Активные заказы</a>
                <a href="{{ route('orders_prodavec.index', ['category' => 'to_issue']) }}" class="list-group-item list-group-item-action {{ $category == 'to_issue' ? 'active' : '' }}">На выдачу</a>
                <a href="{{ route('orders_prodavec.index', ['category' => 'issued']) }}" class="list-group-item list-group-item-action {{ $category == 'issued' ? 'active' : '' }}">Выданные заказы</a>
                <a href="{{ route('orders_prodavec.index', ['category' => 'cancelled']) }}" class="list-group-item list-group-item-action {{ $category == 'cancelled' ? 'active' : '' }}">Отказано в заказе</a>
                <a href="{{ route('orders_prodavec.index', ['category' => 'all']) }}" class="list-group-item list-group-item-action {{ $category == 'all' ? 'active' : '' }}">Все заказы</a>
            </div>
        </div>
        <div class="col-md-9">
            <h1>
                @switch($category)
                    @case('active')
                        Активные заказы
                        @break
                    @case('to_issue')
                        Заказы на выдачу
                        @break
                    @case('issued')
                        Выданные заказы
                        @break
                    @case('cancelled')
                        Отказано в заказе
                        @break
                    @default
                        Все заказы
                @endswitch
            </h1>
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
                <p>У вас нет заказов в этой категории.</p>
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
                                    @if ($order->order_status_id == 1)
                                        <form action="{{ route('orders_prodavec.confirm', $order->id) }}" method="POST" onsubmit="return confirmAction('Одобрить заказ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Одобрить</button>
                                        </form>
                                    @endif
                                    @if($order->order_status_id == 2)
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pickupCodeModal" onclick="showPickupCode('{{ $order->date->format('dmY') . $order->summa }}')">
                                            Код для выдачи
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->order_status_id == 1)
                                        <form action="{{ route('orders_prodavec.cancel', $order->id) }}" method="POST" onsubmit="return confirmAction('Отказаться от заказа?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Отказать</button>
                                        </form>
                                    @endif
                                    @if($order->order_status_id == 2)
                                        <form action="{{ route('orders_prodavec.finish', $order->id) }}" method="POST" onsubmit="return confirmAction('Заказ выдан?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Заказ выдан</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
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

<script>
function confirmAction(message) {
    return confirm(message);
}

function showPickupCode(code) {
    document.getElementById('pickupCode').innerText = code;
}
</script>
@endsection
