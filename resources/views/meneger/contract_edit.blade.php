@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Редактирование договора #{{ $contract->id }}</h1>

    <form action="{{ route('contracts.update', $contract->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Поле одобренной заявки (нельзя изменить) -->
        <div class="form-group">
            <label for="application_id">Одобренная заявка</label>
            <input type="text" class="form-control" id="application_id" name="application_id" value="{{ $approvedApplication->id }} - {{ $approvedApplication->user->surname}} {{ $approvedApplication->user->name}} {{ $approvedApplication->user->Partonymic}}" readonly>
        </div>

        <!-- Дата начала договора -->
        <div class="form-group">
            <label for="date_start">Дата начала</label>
            <input type="date" class="form-control" id="date_start" name="date_start" value="{{ $contract->date_begin->format('Y-m-d') }}" required>
        </div>

        <!-- Срок договора -->
        <div class="form-group">
            <label for="contract_duration">Срок договора</label>
            <select class="form-control" id="contract_duration" name="contract_duration" required>
                <option value="1" {{ $contract->date_end->diffInMonths($contract->date_begin) == 1 ? 'selected' : '' }}>1 месяц</option>
                <option value="2" {{ $contract->date_end->diffInMonths($contract->date_begin) == 2 ? 'selected' : '' }}>2 месяца</option>
                <option value="3" {{ $contract->date_end->diffInMonths($contract->date_begin) == 3 ? 'selected' : '' }}>3 месяца</option>
            </select>
        </div>

        <h5>Выбранные полки</h5>
        <div class="row">
            @if ($approvedApplication->compositions)
                @foreach ($approvedApplication->compositions as $composition)
                <div class="col-md-3 d-flex">
                    <div class="card mb-3 w-100">
                        <div class="card-body">
                            <h5 class="card-title">Полка {{ $composition->shelf->number_shelv }}</h5>
                            <p class="card-text">Шкаф: {{ $composition->shelf->number_wardrobe }}</p>
                            <p class="card-text">Размеры: {{ $composition->shelf->length }}x{{ $composition->shelf->wigth }} см</p>
                            <p class="card-text">Стоимость: {{ $composition->shelf->cost }} руб.</p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p>Нет выбранных полок</p>
            @endif
            </div>
        <h5>Товары</h5>
        <div class="row">
            @if ($approvedApplication->products)
                @foreach ($approvedApplication->products as $product)
                <div class="col-6 col-md-3 mb-3 col-6-mobile">
                    <div class="card mb-3 w-100">
                        <div class="image-container">
                            <img src="{{ asset('images/' . $product->photo_path) }}" alt="{{ $product->name }}" class="card-img-top">
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
            @else
            <p>Нет добавленных продуктов</p>
            @endif
            </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Назад к списку</a>

            <div>
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>

            </div>
        </div>
    </form>

</div>
@endsection