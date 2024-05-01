<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Магазин "Та Полка"</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home')}}"><img class="logo img-fluid" src="{{ asset('images/logo.jpg') }}"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home')}}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products')}}">Каталог</a>
                </li>
                <li class="nav-item">
                <button type="button" class="btn nav-link " data-bs-toggle="modal" data-bs-target="#basketModal">
                                        Корзина
                                    </button>
                    <!-- Модальное окно корзины-->
        <div class="modal fade" id="basketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-4 fw-bold"  id="exampleModalLabel">Корзина</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class = "container-fluid">
                            <div id="cart">
                                <!-- Контейнер, куда будут добавляться товары в корзине -->
                            </div>
                        </div>
                    </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="clear-cart">Очистить корзину</button>
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button> -->
                            <div id="summ"></div>
                            <button type="button" class="btn btn-primary">Перейти к оформлению заказа</button>
                            </div>
                        </div> 
                    </div>
                </div>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.index') }}">Вход</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container">
        @yield('content')
    </div>
    <br>
    <br>
    <br>
    <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Контакты</h5>
                <p>+7 (952) 333-44-34</p>
                <p>г. Пермь, ул. Ленина 76</p>
            </div>
            <div class="col-md-4">
                <h5>Навигация</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home')}}">Главная</a></li>
                    <li><a href="{{ route('products')}}">Каталог</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Мы в социальных сетях</h5>
                <a href="https://vk.com/tapolka.shop">Мы в ВК</a>
                <!-- Форма подписки или другие элементы -->
            </div>
        </div>
    </div>
    </footer>
        <!-- Не работают!!-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <!--  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Получение корзины из локального хранилища при загрузке страницы
        $(document).ready(function() {
            var cart = JSON.parse(localStorage.getItem('cart')) || {};
            total = 0;
            updateCartDisplay(cart);
        });

        // Обработчик кнопки для очистки корзины
        $(document).ready(function() {
            $('#clear-cart').click(function() {
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: "{{ route('clearCart') }}",
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        localStorage.clear();
                        updateCartDisplay({}); // Обновление отображения корзины
                    },
                    error: function(error) {
                        console.log(error); // Обработка ошибок
                    }
                });
            });
        });

        // Отправка блюда в корзину через AJAX
        $('.add-to-cart-form').submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var item_id = form.find('.item_id').val();
            var quantity = form.find('#quantity').val();

            $.ajax({
                url: "{{ route('add_to_cart') }}",
                type: 'POST',
                data: {
                    item_id: item_id,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    localStorage.setItem('cart', JSON.stringify(response.cart)); // Сохранение корзины в локальное хранилище
                    updateCartDisplay(response.cart);
                    alert('Товар добавлен в корзину');
                }
            });
        });
        
        let total = 0;
        // Обновление отображения корзины
        function updateCartDisplay(cart) {
        $('#cart').empty();

        // Создаем массив промисов
        const promises = [];

        for (let dish_id in cart) {
        var Route = "{{ route('product_ids', ['id' => '__dish_id__']) }}";
        var url = Route.replace('__dish_id__', dish_id);

        // Создаем промис для каждого аякс-запроса
        const promise = new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    $('#cart').append(`
                    <div class="card mb-3 w-100" style="height: 12rem;">
                        <div class="row g-0">
                            <div class="col-md-4 border-end border-secondary-subtle">
                                <img src="{{ asset('images/')}}/${response.photo_path}" class="img-fluid rounded-start-1"  alt="Изображение отсутствует">
                            </div>
                            <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">${response.name}</h5>
                                <p class="card-text">Это более широкая карточка с вспомогательным текстом ниже в качестве естественного перехода к дополнительному контенту. Этот контент немного длиннее.</p>
                                <p class="card-text"><small class="text-body-secondary">Цена: ${response.cost} Количество: ${cart[dish_id]}</small></p>
                            </div>
                            </div>
                        </div>
                    </div>
                    `);

                    resolve(response.cost * cart[dish_id]);
                }
            });
        });

            // Добавляем промис в массив
            promises.push(promise);
        }

        // Ждем завершения всех аякс-запросов
        Promise.all(promises).then((cost) => {
            // Суммируем цены
            total = cost.reduce((a, b) => a + b, 0);

            // Обновляем содержимое элемента #summ
            $('#summ').empty();
            $('#summ').append(`
                <div>Итого: ${total} руб.</div>
            `);
        });
    }
    </script>

</body>
</html>

