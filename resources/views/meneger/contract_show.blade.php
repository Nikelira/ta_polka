@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Детали договора #{{ $contract->id }}</h1>
    
    <div class="card mb-3">
        <div class="card-header">
            Информация о договоре
        </div>
        <div class="card-body">
            <p class="card-text"><strong>Дата заключения договора:</strong> {{ \Carbon\Carbon::parse($contract->date_begin)->format('d.m.Y') }}</p>
            <p class="card-text"><strong>Дата окончания договора:</strong> {{ \Carbon\Carbon::parse($contract->date_end)->format('d.m.Y') }}</p>
            <p class="card-text"><strong>Сумма договора:</strong> {{ $contract->summa_contract }} руб.</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            Информация о заявке
        </div>
        <div class="card-body">
            <h5 class="card-title">Клиент</h5>
            <p class="card-text">{{ $contract->rentalApplication->user->surname }} {{ $contract->rentalApplication->user->name }} {{ $contract->rentalApplication->user->Partonymic }}</p>

            <h5 class="card-title">Выбранные полки</h5>
            <div class="row">
                @if($contract->rentalApplication->shelves->isNotEmpty())
                        @foreach($contract->rentalApplication->shelves as $shelf)
                        <div class="col-md-3 d-flex">
                            <div class="card mb-3 w-100">
                                <div class="card-body">
                                    <h5 class="card-title">Полка {{ $shelf->number_shelv }}</h5>
                                    <p class="card-text">Шкаф: {{ $shelf->number_wardrobe }}</p>
                                    <p class="card-text">Размеры: {{ $shelf->length }}x{{ $shelf->wigth }} см</p>
                                    <p class="card-text">Стоимость: {{ $shelf->cost }} руб.</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                @else
                    <p>Полки не выбраны.</p>
                @endif
            </div>

            <h5 class="card-title">Товары</h5>
            @if($contract->rentalApplication->products->isNotEmpty())
                <div class="row">
                    @foreach($contract->rentalApplication->products as $product)
                        <div class="col-6 col-md-3 mb-3 col-6-mobile">
                            <div class="card mb-3 w-100">
                                <div class="image-container">
                                    <img src="{{ $product->photo_path ? asset('images/' . $product->photo_path) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="card-img-top">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    <p class="card-text"><strong>Цена:</strong> {{ $product->cost }} руб.</p>
                                    <p class="card-text"><strong>Количество:</strong> {{ $product->count }} шт.</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Товары не одобрены.</p>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Назад к списку</a>
        <div>
            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-warning">Редактировать</a>
        </div>
    </div>
    </div>

@endsection