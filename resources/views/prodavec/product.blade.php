@extends('layouts.app')
@section('content')
<div class="container">
    <br>
    <br>
    <div class="card-header d-flex justify-content-between"><span class="align-self-center">Список товаров</span>
        <a type="button" class="btn btn-primary"  href="{{ route('products.create') }}">
                        Добавить новый товар
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Фотография</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Категория</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td><img src="{{ asset('images/')}}/{{$product->photo_path}}" alt="{{$product->name}}" style="max-width: 100px;"></td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->cost }}</td>
                    <td>{{ $product->count }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->status->name }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
