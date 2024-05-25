@extends('layouts.app')
@section('content')
    <div class="container mt-5">
    <h1>О нашем магазине</h1>
    <p>Магазин «Та Полка» предоставляет уникальную возможность для мелких производителей продвигать и продавать свои товары без необходимости 
        арендовать полноценное магазинное помещение. Каждый поставщик может арендовать полки для выставки на продажу своих товаров на определенный 
        срок. Магазин предлагает широкий ассортимент товаров: от предметов интерьера и подарков до украшений и аксессуаров. В магазине можно найти 
        изделия от различных мастеров.</p>
    <h3>Контакты</h3>
    <p>Телефон: +7 (952) 333-44-34</p>
    <h3>Мы в социальных сетях</h3>
    <p><a href="https://vk.com/tapolka.shop">Мы в ВК</a></p>
    <p><a href="https://t.me/tapolkashop">Мы в Телеграм</a></p>
    <h3>Наш адрес</h3>
    <p>г. Пермь, ул. Ленина 76, 2 этаж</p>
    <div id="map"></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([58.0070, 56.2177], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([58.0070, 56.2177]).addTo(map)
            .bindPopup('г. Пермь, ул. Ленина 76')
            .openPopup();
    });
</script>
@endsection