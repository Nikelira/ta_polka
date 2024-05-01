<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <a class="nav-link" href="">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Выйти из системы</a>
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
                    <li><a href="#">Главная</a></li>
                    <li><a href="#">Каталог</a></li>
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
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

