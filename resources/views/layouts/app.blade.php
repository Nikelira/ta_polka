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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top navbar-expand-md">
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
                                            <a class="dropdown-item" href="">Мои заявки</a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('profile.index')}}">Редактировать профиль</a>
                                        @if(Auth::user()->role_id == 3) <!-- Проверяем роль пользователя -->
                                            <a class="dropdown-item" href="{{ route('products.index1')}}">Управление товарами</a>
                                            <a class="dropdown-item" href="{{ route('orders_prodavec.index')}}">Управление заказами</a>
                                        @endif
                                        @if(Auth::user()->role_id == 4) <!-- Проверяем роль пользователя -->
                                            <a class="dropdown-item" href="">Управление заявками</a>
                                            <a class="dropdown-item" href="">Управление договорами</a>
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
    <footer class="footer mt-auto py-3 bg-light">
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
                <h5>Мы в социальных сетях</h5>
                <a href="https://vk.com/tapolka.shop">Мы в ВК</a>
                <p><a href="https://t.me/tapolkashop">Мы в Телеграм</a></p>
            </div>
        </div>
    </div>

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

    <div class="modal fade" id="confirmationModal1" tabindex="-1" aria-labelledby="confirmationModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Нельзя добавить больше, чем доступно на складе.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            </div>
        </div>
    </div>
    </footer>
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

// Обработчик кнопки для добавления товара в корзину
$('.add-to-cart-form').submit(function(event) {
    event.preventDefault();

    var form = $(this);
    var item_id = form.find('.item_id').val();
    var quantity = parseInt(form.find('#quantity').val());
    var availableQuantity = parseInt(form.data('available-quantity'));

    var cart = JSON.parse(localStorage.getItem('cart')) || {};
    var currentQuantityInCart = cart[item_id] ? cart[item_id] : 0;

    if (currentQuantityInCart + quantity > availableQuantity) {
        $('#confirmationModal1').modal('show'); // Показываем модальное окно

            // Скрытие модального окна через 3 секунды (3000 миллисекунд)
            setTimeout(function() {
                $('#confirmationModal1').modal('hide');
            }, 1000);
        return;
    }

    $.ajax({
        url: "{{ route('add_to_cart') }}",
        type: 'POST',
        data: {
            item_id: item_id,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (cart[item_id]) {
                cart[item_id] += quantity;
            } else {
                cart[item_id] = quantity;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay(cart);
            $('#confirmationModal').modal('show'); // Показываем модальное окно

            // Скрытие модального окна через 3 секунды (3000 миллисекунд)
            setTimeout(function() {
                $('#confirmationModal').modal('hide');
            }, 1000);
        },
        error: function(error) {
            console.log(error);
        }
    });
});

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

