@php
    use App\Models\Shelf;
@endphp

@extends('layouts.app')

@section('content')

@if(Auth::check())
    @if(Auth::user()->role_id == 2)
    <div class="container mt-5">
        <h1 class="text-center">Полки</h1>
        @if($selectedShelvesObjects->isNotEmpty())
            <div class="text-center mt-3">
                <a href="{{ route('cooperation') }}" class="btn btn-primary">Перейти к оформлению аренды</a>
            </div>
        @endif
        <div class="wardrobe-section mt-4">
            <div class="wardrobe">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $shelf = $displayShelves->firstWhere('number_shelv', $i);
                        $selectedShelf = $selectedShelvesObjects->firstWhere('number_shelv', $i);
                    @endphp

                    @if($shelf)
                        <div class="shelf free">
                            <div class="shelf-content">
                                <h5 class="shelf-title">Полка {{ $shelf->number_shelv }}</h5>
                                <p>Номер шкафа: {{ $shelf->number_wardrobe }}</p>
                                <p>Размеры: {{ $shelf->length }}x{{ $shelf->wigth }} см</p>
                                <p>Стоимость: {{ $shelf->cost }} руб.</p>
                            </div>
                            <button class="btn btn-danger btnr btn-dangerr" onclick="deselectShelf({{ $shelf->number_shelv }})">-</button>
                            <button class="btn btn-success btnr btn-successr" onclick="selectShelf({{ $shelf->id }})">+</button>
                        </div>
                    @else
                        <div class="shelf occupied">
                            <div class="shelf-content">
                                <h5 class="shelf-title">Полка {{ $i }}</h5>
                                <p>Все полки на высоте {{ $i }} были выбраны</p>
                                @if($selectedShelf)
                                    <button class="btn btn-danger btnr btn-dangerr" onclick="deselectShelf({{ $selectedShelf->number_shelv }})">-</button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </div>

<script>
function selectShelf(shelfId) {
    $.ajax({
        url: '{{ route("cooperation.select") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            shelf_id: shelfId
        },
        success: function(response) {
            alert(response.message);
            if (response.success) {
                location.reload(); // Reload the page to reflect the changes
            }
        },
        error: function(response) {
            alert('Ошибка выбора полки');
        }
    });
}

function deselectShelf(shelfNumber) {
    $.ajax({
        url: '{{ route("cooperation.deselect") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            shelf_number: shelfNumber
        },
        success: function(response) {
            if (response.success) {
                location.reload(); // Reload the page to reflect the changes
            } else {
                alert(response.message);
            }
        },
        error: function(response) {
            alert('Ошибка отмены выбора полки');
        }
    });
}
</script>
@else
<br>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Аренда полки</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ asset('images/shelf.jpg') }}" class="img-fluid" alt="Полка">
                        </div>
                        <div class="col-md-6">
                            <h3>Стоимость зависит от выбранной полки</h3>
                            <p>Преимущества аренды полки:</p>
                            <ul>
                                <li>Удобное размещение товаров</li>
                                <li>Высокая проходимость</li>
                                <li>Полка закреплена за партнером</li>
                            </ul>
                            @if(Auth::check())
                                
                            @else
                                <a href="{{ route('cooperation')}}" class="btn btn-primary">Начать сотрудничество</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@else
<br>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Аренда полки</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ asset('images/shelf.jpg') }}" class="img-fluid" alt="Полка">
                        </div>
                        <div class="col-md-6">
                            <h3>Стоимость зависит от выбранной полки</h3>
                            <p>Преимущества аренды полки:</p>
                            <ul>
                                <li>Удобное размещение товаров</li>
                                <li>Высокая проходимость</li>
                                <li>Полка закреплена за партнером</li>
                            </ul>
                            @if(Auth::check())
                                
                            @else
                                <a href="{{ route('cooperation')}}" class="btn btn-primary">Начать сотрудничество</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<style>
.wardrobe {
    position: relative;
    width: 60%; /* Ширина шкафа */
    margin: 0 auto;
    padding: 20px; /* Добавление отступов для эстетики */
    box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Легкая тень для шкафа */
    background-color: #f8f9fa; /* Светлый фон для шкафа */
    border-radius: 10px; /* Скругление углов шкафа */
}

.shelf {
    width: 100%;
    height: 120px; /* Оптимальная высота полки */
    margin: 15px 0; /* Увеличенный вертикальный отступ между полками */
    border: 1px solid #ccc; /* Границы полки */
    border-radius: 10px; /* Скругленные углы */
    padding: 10px;
    box-sizing: border-box; /* Включаем padding в общую ширину */
    position: relative;
    display: flex;
    flex-direction: column; /* Располагаем элементы вертикально */
    align-items: center;
    justify-content: center;
    background-color: #fff; /* Белый фон для полки */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Легкая тень для полки */
    transition: background-color 0.3s ease; /* Плавный переход при смене цвета */
}

.shelf.free {
    background-color: #e0f7fa; /* Цвет для свободной полки */
}

.shelf.occupied {
    background-color: #ffebee; /* Цвет для занятой полки */
}

.shelf-content {
    text-align: center; /* Центрируем текст */
    display: flex; /* Используем flex для центрирования */
    flex-direction: column; /* Вертикальная ориентация текста */
    align-items: center; /* Горизонтальное центрирование */
    justify-content: center; /* Вертикальное центрирование */
    padding: 10px; /* Добавление отступов */
}

.shelf-title {
    font-size: 1.1em; /* Увеличиваем размер шрифта */
    margin-bottom: 5px; /* Уменьшенный отступ снизу */
    font-weight: bold; /* Жирный шрифт */
    color: #333; /* Цвет текста */
}

.shelf-content p {
    margin: 2px 0; /* Уменьшенные отступы между абзацами */
    font-size: 0.9em; /* Размер текста */
    color: #555; /* Цвет текста */
}

.btnr {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2em;
    padding: 5px 10px; /* Добавляем padding для кнопок */
    border-radius: 50%; /* Круглая форма кнопок */
    box-shadow: 0 0 5px rgba(0,0,0,0.1); /* Легкая тень для кнопок */
    transition: background-color 0.3s ease; /* Плавный переход цвета фона */
}

.btnr:hover {
    background-color: rgba(0, 0, 0, 0.1); /* Легкий оттенок при наведении */
}

.btn-dangerr {
    left: -45px; /* Расположить кнопку "-" слева */
}

.btn-successr {
    right: -45px; /* Расположить кнопку "+" справа */
}

/* Адаптивные стили для различных экранов */
@media (max-width: 768px) {
    .wardrobe {
        width: 90%; /* Увеличиваем ширину для планшетов */
    }
    .shelf {
        height: 130px; /* Увеличиваем высоту для планшетов */
    }
    .btn-dangerr {
        left: -35px; /* Корректируем позицию кнопки "-" */
    }
    .btn-successr {
        right: -35px; /* Корректируем позицию кнопки "+" */
    }
}

@media (max-width: 480px) {
    .wardrobe {
        width: 100%; /* Увеличиваем ширину для телефонов */
    }
    .shelf {
        height: 140px; /* Увеличиваем высоту для телефонов */
    }
    .btn-dangerr {
        left: -30px; /* Корректируем позицию кнопки "-" */
    }
    .btn-successr {
        right: -30px; /* Корректируем позицию кнопки "+" */
    }
}
</style>
@endsection