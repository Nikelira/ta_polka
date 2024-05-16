@extends('layouts.app')

@section('content')
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
                            <a href="" class="btn btn-primary">Начать сотрудничество</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection