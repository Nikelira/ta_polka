<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Магазин "Та Полка"</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('icons.png') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        nav{
            background-color: #e5f6ff;
        }
        footer{
            background-color: #e5f6ff;
        }
        .btn-danger{
            background-color: #de3b5a;
        }
        .btn-primary{
            background-color: #2e9aff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-expand-md">
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
                        <a class="nav-link" href="{{ route('about_shop')}}">О магазине</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shelf')}}">Полки</a>
                    </li>
                    @if(Auth::check())
                        @if(Auth::user()->role_id == 2)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cooperation')}}">Аренда полки</a>
                            </li>
                        @endif
                    @endif
                    @if(Auth::check())
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2) 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('checkout.index')}}">Корзина</a>
                        </li>        
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('checkout.index')}}">Корзина</a>
                        </li>     
                    @endif
                    <li class="nav-item">
                        @if(Auth::check())
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if(Auth::check())
                                        @if(Auth::user()->role_id == 1)
                                            <a class="dropdown-item" href="{{ route('cabinet_zakaz.index')}}">Мои заказы</a>
                                        @endif
                                        @if(Auth::user()->role_id == 2)
                                            <a class="dropdown-item" href="{{ route('cabinet_zakaz.index')}}">Мои заказы</a>
                                            <a class="dropdown-item" href="{{ route('postavshik.index')}}">Мои заявки</a>
                                            <a class="dropdown-item" href="{{ route('contracts_postavshik.index')}}">Мои договора</a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('profile.index')}}">Редактировать профиль</a>
                                        @if(Auth::user()->role_id == 3) <!-- Проверяем роль пользователя -->
                                            <a class="dropdown-item" href="{{ route('products.index1')}}">Управление товарами</a>
                                            <a class="dropdown-item" href="{{ route('orders_prodavec.index')}}">Управление заказами</a>
                                        @endif
                                        @if(Auth::user()->role_id == 4) <!-- Проверяем роль пользователя -->
                                            <a class="dropdown-item" href="{{ route('application.index')}}">Управление заявками</a>
                                            <a class="dropdown-item" href="{{ route('contracts.index')}}">Управление договорами</a>
                                        @endif
                                        @if(Auth::user()->role_id == 5) <!-- Проверяем роль пользователя -->
                                            <a class="dropdown-item" href="">Управление договорами</a>
                                            <a class="dropdown-item" href="{{ route('employes.index') }}">Управление сотрудниками</a>
                                        @endif
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}">Выйти</a>
                                </div>
                            </div>
                        @else
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container nav-offset">
        @yield('content')
    </div>
    <br>
    <br>
    <br>
    <footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Контакты</h5>
                <p>+7 (952) 333-44-34</p>
                <p>г. Пермь, ул. Ленина 76, 2 этаж</p>
            </div>
            <div class="col-md-4">
                <h5>Навигация</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home')}}">Главная</a></li>
                    <li><a href="{{ route('products')}}">Каталог</a></li>
                    <li><a href="{{ route('about_shop')}}">О магазине</a></li>
                    <li><a href="{{ route('shelf')}}">Полки</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <br>                
                <br>                
                <a href="https://vk.com/tapolka.shop">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="5 4 40 45">
                    <path d="M 11.5 6 C 8.4802259 6 6 8.4802259 6 11.5 L 6 36.5 C 6 39.519774 8.4802259 42 11.5 42 L 36.5 42 C 39.519774 42 42 39.519774 42 36.5 L 42 11.5 C 42 8.4802259 39.519774 6 36.5 6 L 11.5 6 z M 11.5 9 L 36.5 9 C 37.898226 9 39 10.101774 39 11.5 L 39 36.5 C 39 37.898226 37.898226 39 36.5 39 L 11.5 39 C 10.101774 39 9 37.898226 9 36.5 L 9 11.5 C 9 10.101774 10.101774 9 11.5 9 z M 13.816406 17.125 C 13.117406 17.125 13 17.467984 13 17.833984 C 13 18.494984 13.550734 22.067781 16.552734 26.175781 C 18.729734 29.154781 21.597719 30.873047 24.136719 30.873047 C 25.674719 30.873047 25.832031 30.483094 25.832031 29.871094 L 25.832031 27.146484 C 25.833031 26.412484 26.001234 26.291016 26.490234 26.291016 C 26.839234 26.291016 27.550781 26.519047 28.925781 28.123047 C 30.511781 29.973047 30.799984 30.873047 31.708984 30.873047 L 34.109375 30.873047 C 34.666375 30.873047 34.985047 30.6405 34.998047 30.1875 C 35.001047 30.0725 34.984266 29.942828 34.947266 29.798828 C 34.769266 29.270828 33.9545 27.978047 32.9375 26.748047 C 32.3735 26.067047 31.816547 25.393344 31.560547 25.027344 C 31.389547 24.788344 31.324031 24.618031 31.332031 24.457031 C 31.340031 24.287031 31.428547 24.127344 31.560547 23.902344 C 31.537547 23.902344 34.638406 19.546125 34.941406 18.078125 C 34.983406 17.940125 35.003047 17.811266 34.998047 17.697266 C 34.984047 17.365266 34.757703 17.125 34.220703 17.125 L 31.820312 17.125 C 31.214312 17.125 30.935484 17.492375 30.771484 17.859375 C 30.771484 17.859375 29.274781 20.93875 27.550781 22.96875 C 26.991781 23.55675 26.707297 23.541016 26.404297 23.541016 C 26.242297 23.541016 25.832031 23.344641 25.832031 22.806641 L 25.832031 18.054688 C 25.832031 17.418688 25.674109 17.125 25.162109 17.125 L 20.900391 17.125 C 20.527391 17.125 20.333984 17.417891 20.333984 17.712891 C 20.333984 18.323891 21.157953 18.470594 21.251953 20.183594 L 21.251953 23.505859 C 21.251953 24.312859 21.111594 24.457031 20.808594 24.457031 C 19.992594 24.457031 18.385547 21.707516 17.310547 18.103516 C 17.077547 17.394516 16.844281 17.125 16.238281 17.125 L 13.816406 17.125 z"></path>
                </svg>
                </a>
                <a href="https://t.me/tapolkashop">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 18.5">
                    <path d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM8.28672 5.90599C7.50861 6.22963 5.95346 6.8995 3.6213 7.91558C3.2426 8.06618 3.04421 8.21351 3.02615 8.35757C2.99563 8.60103 3.30052 8.6969 3.71569 8.82745C3.77216 8.8452 3.83068 8.8636 3.89067 8.8831C4.29913 9.01588 4.84859 9.17121 5.13423 9.17738C5.39334 9.18298 5.68253 9.07616 6.0018 8.85692C8.18081 7.38603 9.30563 6.64258 9.37625 6.62655C9.42607 6.61524 9.49511 6.60102 9.54188 6.6426C9.58866 6.68418 9.58406 6.76291 9.57911 6.78404C9.5489 6.9128 8.35212 8.02543 7.73279 8.60121C7.53971 8.78071 7.40276 8.90804 7.37476 8.93712C7.31204 9.00226 7.24812 9.06388 7.18669 9.1231C6.80722 9.48891 6.52265 9.76324 7.20245 10.2112C7.52913 10.4265 7.79055 10.6045 8.05134 10.7821C8.33616 10.9761 8.62023 11.1695 8.98778 11.4105C9.08142 11.4718 9.17086 11.5356 9.25796 11.5977C9.58942 11.834 9.88721 12.0463 10.2551 12.0124C10.4689 11.9928 10.6897 11.7918 10.8018 11.1923C11.0668 9.77545 11.5878 6.70567 11.7082 5.44069C11.7187 5.32986 11.7055 5.18802 11.6948 5.12576C11.6842 5.06349 11.6619 4.97478 11.581 4.90911C11.4851 4.83133 11.3371 4.81493 11.2709 4.8161C10.9701 4.8214 10.5084 4.98192 8.28672 5.90599Z" fill="black"/>
                </svg>

                </a>
            </div>
        </div>
    </div>

    </footer>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Товар успешно добавлен в корзину.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
// Получение корзины из локального хранилища при загрузке страницы
$(document).ready(function() {
    fetchCartFromSession();
});

// Функция для синхронизации корзины с сессией
function fetchCartFromSession() {
    $.ajax({
        url: "{{ route('get_cart') }}",
        method: 'GET',
        success: function(response) {
            localStorage.setItem('cart', JSON.stringify(response.cart));
            updateCartDisplay(response.cart);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

// Обработчик кнопки для очистки корзины
$(document).ready(function() {
    $('#clear-cart').click(function() {
        $.ajax({
            url: "{{ route('clearCart') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                localStorage.clear();
                updateCartDisplay({});
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

$('.plus-btn').click(function() {
    var input = $(this).closest('.input-group').find('.quantity-input');
    var currentValue = parseInt(input.val());
    input.val(currentValue + 1);
});

$('.minus-btn').click(function() {
    var input = $(this).closest('.input-group').find('.quantity-input');
    var currentValue = parseInt(input.val());
    if (currentValue > 1) {
        input.val(currentValue - 1);
    }
});

$(document).ready(function() {
    // Функция для обновления кнопок добавления в корзину в зависимости от состояния корзины
    function updateCartButtons(cart) {
        $('.add-to-cart-form').each(function() {
            var form = $(this);
            var item_id = form.find('.item_id').val();
            var button = form.find('button[type="submit"]');

            // Если товар уже есть в корзине
            if (cart[item_id]) {
                button.text('Перейти в корзину');
                button.removeClass('btn-primary').addClass('btn-success');
                button.off('click').on('click', function(event) {
                    event.preventDefault();
                    window.location.href = "{{ route('checkout.index') }}";
                });
            } else {
                // Если товара нет в корзине, вернуть кнопку в исходное состояние
                button.text('Добавить в корзину');
                button.removeClass('btn-success').addClass('btn-primary');
                button.off('click'); // Удалить любые предыдущие обработчики
            }
        });
    }

    // Проверяем состояние корзины при загрузке страницы
    $.ajax({
        url: "{{ route('check_cart_items') }}",
        method: 'GET',
        success: function(response) {
            var cart = response.cart || {};
            updateCartButtons(cart);
        },
        error: function(error) {
            console.log(error);
        }
    });

    // Обработчик добавления товара в корзину
    $('.add-to-cart-form').submit(function(event) {
        event.preventDefault();

        var form = $(this);
        var item_id = form.find('.item_id').val();
        var button = form.find('button[type="submit"]');

        // Проверка, не добавлен ли товар уже в корзину
        var cart = JSON.parse(localStorage.getItem('cart')) || {};
        if (cart[item_id]) {
            window.location.href = "{{ route('checkout.index') }}";
            return;
        }

        // Добавление товара в корзину
        $.ajax({
            url: "{{ route('add_to_cart') }}",
            type: 'POST',
            data: {
                item_id: item_id,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Добавляем товар в локальное хранилище
                cart[item_id] = 1;
                localStorage.setItem('cart', JSON.stringify(cart));

                // Обновляем кнопку
                button.text('Перейти в корзину');
                button.removeClass('btn-primary').addClass('btn-success');
                button.off('click').on('click', function(event) {
                    event.preventDefault();
                    window.location.href = "{{ route('checkout.index') }}";
                });

                // Показать сообщение о добавлении товара
                showConfirmationModal();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

// Функция для отображения модального окна
function showConfirmationModal() {
    $('#confirmationModal').modal('show');
    setTimeout(function() {
        $('#confirmationModal').modal('hide');
    }, 1000);
}

let total = 0;

function updateCartDisplay(cart) {
    $('#cart').empty();
    const promises = [];

    for (let dish_id in cart) {
        var Route = "{{ route('product_ids', ['id' => '__dish_id__']) }}";
        var url = Route.replace('__dish_id__', dish_id);

        const promise = new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    $('#cart').append(`
                    <div class="card mb-3 w-100">
                        <div class="row g-0">
                            <div class="col-md-4 border-end border-secondary-subtle">
                                <img class="img-fluid rounded-start-1" src="{{ asset('images/')}}/${response.photo_path}" alt="Изображение отсутствует">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">${response.name}</h5>
                                    <p class="card-text">${response.description}</p>
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

        promises.push(promise);
    }

    Promise.all(promises).then((cost) => {
        total = cost.reduce((a, b) => a + b, 0);
        $('#summ').empty();
        $('#summ').append(`<div>Итого: ${total} руб.</div>`);
    });
}
</script>

@stack('scripts')
</body>
</html>

