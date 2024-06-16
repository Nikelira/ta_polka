@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Детали заявки #{{ $application->id }}</h1>
    <p><strong>Дата заявки:</strong> {{ $application->date_application }}</p>
    <p><strong>Статус заявки:</strong> {{ $application->applicationStatus->name }}</p>

    @if($application->application_status_id == 4)
        <p><strong>Причина отказа:</strong> {{ $application->message }}</p>
    @endif

    <h5>Выбранные полки</h5>
    <div class="row">
        @if ($application->compositions)
            @foreach ($application->compositions as $composition)
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
        @if ($application->products)
            @foreach ($application->products as $product)
                <div class="col-6 col-md-3 mb-3">
                    <div class="card mb-3 w-100">
                        <div class="image-container">
                            <img src="{{ asset('images/' . $product->photo_path) }}" alt="{{ $product->name }}" class="card-img-top">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>Цена:</strong> {{ $product->cost }} руб.</p>
                            <p class="card-text"><strong>Количество:</strong> {{ $product->count }} шт.</p>
                            <p class="card-text"><strong>Статус товара:</strong> {{ $product->status->name }}</p>

                            @if($product->product_status_id == 5)
                                <div class="mt-2">
                                    <p><strong>Причина отказа:</strong> {{ $product->message }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>Нет добавленных продуктов</p>
        @endif
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('postavshik.index', ['status' => request('status')]) }}" class="btn btn-secondary">Назад к заявкам</a>
        @if($application->application_status_id == 2)
            <div>
                <button type="button" class="btn btn-danger" onclick="cancelApplication({{ $application->id }})">Отменить заявку</button>
            </div>
        @endif
    </div>
</div>

<script>
    function cancelApplication(applicationId) {
        if (!confirm('Вы уверены, что хотите отменить эту заявку?')) {
            return;
        }

        $.ajax({
            url: '{{ route("application.delete") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                application_id: applicationId
            },
            success: function(response) {
                if (response.success) {
                    alert('Заявка успешно отменена.');
                    window.location.href = '{{ route('postavshik.index', ['status' => 'all']) }}';
                } else {
                    alert('Произошла ошибка при отмене заявки.');
                }
            },
            error: function() {
                alert('Произошла ошибка при отправке запроса.');
            }
        });
    }
</script>
@endsection