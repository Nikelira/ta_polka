@extends('layouts.app')
@section('content')
<div class="container mt-5">
        <h1>Создание нового договора</h1>

        <!-- Форма создания нового договора -->
        <form action="{{ route('contracts.store') }}" method="POST">
            @csrf

            <!-- Выбор одобренной заявки -->
            <div class="form-group">
                <label for="application_id">Одобренная заявка</label>
                <select class="form-control" id="application_id" name="application_id">
                    <option value="">Выберите заявку</option>
                    @foreach($approvedApplications as $application)
                        <option value="{{ $application->id }}">{{ $application->id }} - {{ $application->user->surname}} {{ $application->user->name}} {{ $application->user->Partonymic}}</option>
                    @endforeach
                </select>
            </div>

            <!-- Дата начала договора -->
            <div class="form-group">
                <label for="date_start">Дата начала</label>
                <input type="date" class="form-control" id="date_start" name="date_start" required>
            </div>

            <!-- Срок договора -->
            <div class="form-group">
                <label for="contract_duration">Срок договора</label>
                <select class="form-control" id="contract_duration" name="contract_duration" required>
                    <option value="1">1 месяц</option>
                    <option value="2">2 месяца</option>
                    <option value="3">3 месяца</option>
                </select>
            </div>

            <!-- Дата окончания договора -->
            <div class="form-group" id="end_date_container" style="display: none;">
                <label for="date_end">Дата окончания</label>
                <input type="text" class="form-control" id="date_end" readonly>
            </div>

            <!-- Данные по выбранной заявке -->
            <div id="application-details" style="display: none;">
                <h4>Детали заявки</h4>

                <!-- Полки -->
                <h5>Выбранные полки</h5>
                <div class="row" id="shelves-container">
                    <!-- Полки будут динамически загружены сюда -->
                </div>
                <br>
                 <!-- Общая сумма аренды полок -->
                <h5>Общая стоимость аренды полок: <span id="total-shelf-cost">0</span> руб.</h5>
                <br>

                <!-- Товары -->
                <h5>Товары</h5>
                <div class="row" id="products-container">
                    <!-- Товары будут динамически загружены сюда -->
                </div>
                <!-- Общая сумма товаров -->
                <h5>Общая стоимость товаров: <span id="total-product-cost">0</span> руб.</h5>
                <br>
            </div>

            <!-- Кнопка для отправки формы -->

            <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('contracts.index')}}" class="btn btn-secondary">Назад к договорам</a>
            <div>
                <button type="submit" class="btn btn-primary">Создать договор</button>
            </div>
        </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
    <script>
       $(document).ready(function () {
    // Функция для вычисления и отображения даты окончания договора
    function calculateEndDate() {
        var startDate = $('#date_start').val();
        var durationMonths = parseInt($('#contract_duration').val());

        if (startDate && durationMonths) {
            var endDate = moment(startDate).add(durationMonths, 'months').format('YYYY-MM-DD');
            $('#date_end').val(endDate);
            $('#end_date_container').show();
        } else {
            $('#end_date_container').hide();
        }
    }

    // Функция для подсчета общей стоимости аренды полок
    function calculateTotalShelfCost(shelves) {
        let totalCost = shelves.reduce((sum, shelf) => sum + shelf.cost, 0);
        $('#total-shelf-cost').text(totalCost);
    }

    // Функция для подсчета общей стоимости товаров
    function calculateTotalProductCost(products) {
        let totalCost = products.reduce((sum, product) => sum + (product.cost * product.count), 0);
        $('#total-product-cost').text(totalCost);
    }

    // Устанавливаем текущую дату как значение по умолчанию для поля date_start и вызываем расчет даты окончания
    var today = moment().format('YYYY-MM-DD');
    $('#date_start').val(today);
    calculateEndDate();  // Вызов расчета даты окончания при загрузке страницы

    // Обработка изменений в выборе даты начала или срока
    $('#date_start, #contract_duration').change(function () {
        calculateEndDate();
    });

    // Обработка изменения выбранной заявки
    $('#application_id').change(function () {
        var applicationId = $(this).val();

        if (applicationId) {
            $.ajax({
                url: '{{ route('contracts.applicationData', '') }}/' + applicationId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#application-details').show();

                    // Отобразить полки
                    var shelvesContainer = $('#shelves-container');
                    shelvesContainer.empty();
                    if (data.shelves.length > 0) {
                        data.shelves.forEach(function (shelf) {
                            shelvesContainer.append(`
                                <div class="col-md-3 d-flex">
                                    <div class="card mb-3 w-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Полка ${shelf.number_shelv}</h5>
                                            <p class="card-text">Шкаф: ${shelf.number_wardrobe}</p>
                                            <p class="card-text">Размеры: ${shelf.length}x${shelf.width} см</p>
                                            <p class="card-text">Стоимость: ${shelf.cost} руб.</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                        // Вычислить и отобразить общую стоимость аренды полок
                        calculateTotalShelfCost(data.shelves);
                    } else {
                        shelvesContainer.append('<p>Нет выбранных полок</p>');
                        $('#total-shelf-cost').text(0); // Обнулить сумму, если нет полок
                    }

                    // Отобразить товары
                    var productsContainer = $('#products-container');
                    productsContainer.empty();
                    if (data.approvedProducts.length > 0) {
                        data.approvedProducts.forEach(function (product) {
                            productsContainer.append(`
                                <div class="col-6 col-md-3 mb-3 col-6-mobile">
                                    <div class="card mb-3 w-100">
                                        <div class="image-container">
                                            <img src="${product.photo_path ? '{{ asset('images/') }}/' + product.photo_path : 'https://via.placeholder.com/150'}" alt="${product.name}" class="card-img-top">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text">${product.description}</p>
                                            <p class="card-text"><strong>Цена:</strong> ${product.cost} руб.</p>
                                            <p class="card-text"><strong>Количество:</strong> ${product.count} шт.</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                        // Вычислить и отобразить общую стоимость товаров
                        calculateTotalProductCost(data.approvedProducts);
                    } else {
                        productsContainer.append('<p>Нет одобренных товаров</p>');
                        $('#total-product-cost').text(0); // Обнулить сумму, если нет товаров
                    }
                },
                error: function () {
                    $('#application-details').hide();
                    alert('Произошла ошибка при загрузке данных заявки.');
                }
            });
        } else {
            $('#application-details').hide();
        }
    });
});
    </script>

     
@endsection