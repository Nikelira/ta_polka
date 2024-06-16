@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">О нашем магазине</h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <p class="lead">Магазин «Та Полка» предоставляет уникальную возможность для мелких производителей продвигать и продавать свои товары без необходимости 
                арендовать полноценное магазинное помещение. Каждый поставщик может арендовать полки для выставки на продажу своих товаров на определенный 
                срок. Магазин предлагает широкий ассортимент товаров: от предметов интерьера и подарков до украшений и аксессуаров. В магазине можно найти 
                изделия от различных мастеров.</p>
        </div>
        <div class="col-md-6 mb-4">
            <div class="contact-section">
                <h3><i class="fas fa-phone-alt"></i> Контакты</h3>
                <p>Телефон: +7 (952) 333-44-34</p>
            </div>
            <div class="address-section mt-4">
                <h3><i class="fas fa-map-marker-alt"></i> Наш адрес</h3>
                <p>г. Пермь, ул. Ленина 76, 2 этаж</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="map" style="height: 400px;"></div>
        </div>
    </div>
</div>

<style>
h1 {
    font-size: 2rem;
    font-weight: bold;
}

.lead {
    font-size: 1.1rem;
    line-height: 1.6;
}

.contact-section, .social-section, .address-section {
    border-left: 4px solid #aed7fc;
    padding-left: 15px;
}

h4 {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

h4 i {
    color: #e5f6ff;
    margin-right: 10px;
}

p a {
    text-decoration: none;
    color: #007bff;
    font-weight: 500;
}

p a:hover {
    text-decoration: underline;
}

#map {
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

@media (max-width: 768px) {
    h1 {
        font-size: 1.75rem;
    }

    .lead {
        font-size: 1rem;
    }

    #map {
        height: 300px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
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