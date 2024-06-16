@extends('layouts.app')

@section('content')
<div class="container mt-5">
        <h1>Детали заявки #{{ $application->id }}</h1>
        <p><strong>Дата заявки:</strong> {{ $application->date_application }}</p>
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
                            <div class="form-group mt-auto">
                                <label for="product_status_{{ $product->id }}">Статус товара</label>
                                <select class="form-control product-status-select" id="product_status_{{ $product->id }}" data-product-id="{{ $product->id }}">
                                    <option value="4" {{ $product->product_status_id == 4 ? 'selected' : '' }}>Одобрено</option>
                                    <option value="5" {{ $product->product_status_id == 5 ? 'selected' : '' }}>Отказано</option>
                                </select>
                                <div class="mt-2 message-field" style="display: {{ $product->product_status_id == 5 ? 'block' : 'none' }};">
                                    <label for="message_{{ $product->id }}">Причина отказа</label>
                                    <textarea class="form-control" id="message_{{ $product->id }}" rows="2">{{ $product->message }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
                @endforeach
            @else
            <p>Нет добавленных продуктов</p>
            @endif
            </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('application.index', ['status' => $status]) }}" class="btn btn-secondary">Назад к заявкам</a>
            <div>
                <button type="button" class="btn btn-success" id="approve-application">Одобрить заявку</button>
                <button type="button" class="btn btn-danger" id="reject-application">Отклонить заявку</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Функция для показа/скрытия поля "Причина отказа" в зависимости от выбранного статуса
        function toggleMessageField(productId) {
            var status = $('#product_status_' + productId).val();
            var messageField = $('#message_' + productId).closest('.message-field');
            
            if (status == 5) {
                messageField.show();
            } else {
                messageField.hide();
            }
        }

        // Проверка всех полей статуса при загрузке страницы
        $('.product-status-select').each(function() {
            var productId = $(this).data('product-id');
            toggleMessageField(productId);
        });

        // Обработка изменения статуса товара
        $('.product-status-select').on('change', function() {
            var productId = $(this).data('product-id');
            toggleMessageField(productId);
        });

        // Обработка кнопки "Одобрить заявку"
        $('#approve-application').on('click', function() {
            var products = [];
            var allProductsHaveStatus = true;
            var allProductsRejected = true;
            var allRejectionsHaveMessages = true;

            $('.product-status-select').each(function() {
                var productId = $(this).data('product-id');
                var status = $(this).val();
                var message = status == 5 ? $('#message_' + productId).val() : null;

                if (status != 4 && status != 5) {
                    allProductsHaveStatus = false;
                    return false; // Выйти из each цикла
                }

                if (status == 4) {
                    allProductsRejected = false;
                }

                if (status == 5 && (!message || message.trim() === '')) {
                    allRejectionsHaveMessages = false;
                    return false; // Выйти из each цикла
                }

                products.push({
                    id: productId,
                    status: status,
                    message: message
                });
            });

            if (!allProductsHaveStatus) {
                alert('Пожалуйста, выберите статус для всех товаров.');
                return;
            }

            if (!allRejectionsHaveMessages) {
                alert('Пожалуйста, укажите причину отказа для всех отклоненных товаров.');
                return;
            }

            if (allProductsRejected) {
                alert('Нельзя одобрить заявку, если все товары отклонены.');
                return;
            }

            $.ajax({
                url: '{{ route("manager.rental-applications.approve", ["id" => $application->id]) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    products: products
                },
                success: function(response) {
                    if (response.success) {
                        alert('Заявка успешно одобрена.');
                        window.location.href = '{{ route("application.index", ["status" => $status]) }}';
                    } else {
                        alert('Произошла ошибка при одобрении заявки.');
                    }
                },
                error: function() {
                    alert('Произошла ошибка при отправке запроса.');
                }
            });
        });

        // Обработка кнопки "Отклонить заявку"
        $('#reject-application').on('click', function() {
            var reason = prompt('Укажите причину отклонения заявки:');
            if (reason === null || reason.trim() === '') {
                alert('Причина отклонения не может быть пустой.');
                return;
            }

            $.ajax({
                url: '{{ route("manager.rental-applications.reject", ["id" => $application->id]) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        alert('Заявка успешно отклонена.');
                        window.location.href = '{{ route("application.index", ["status" => $status]) }}';
                    } else {
                        alert('Произошла ошибка при отклонении заявки.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Произошла ошибка при отправке запроса.');
                }
            });
        });
    });
</script>
@endsection