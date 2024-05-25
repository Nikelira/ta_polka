@php
    use App\Models\Shelf;
@endphp

@extends('layouts.app')

@section('content')

@if(Auth::check())
    @if(Auth::user()->role_id == 2)
    <div class="container mt-5">
        <h1>Сотрудничество</h1>
        @if($selectedShelvesObjects->isNotEmpty())
            <div class="mt-3">
                <a href="{{ route('cooperation') }}" class="btn btn-primary">Перейти к оформлению аренды</a>
            </div>
        @endif
        <div class="wardrobe-section">
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
    @endif

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

@endsection