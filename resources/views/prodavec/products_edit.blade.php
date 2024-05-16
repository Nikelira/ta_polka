@extends('layouts.app')
@section('content')
<div class="container">
    <br>
    <h1>Изменение товара</h1>
    <br>
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Наименование:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
        </div>
        <br>
        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control" id="description" name="description" maxlength='255'>{{ $product->description }}</textarea>
        </div>
        <br>
        <div class="form-group">
            <label for="cost">Стоимость:</label>
            <input type="text" class="form-control" id="cost" name="cost" value="{{ $product->cost }}">
        </div>
        <br>
        <div class="form-group">
            <label for="count">Количество:</label>
            <input type="text" class="form-control" id="count" name="count" value="{{ $product->count }}">
        </div>
        <br>
        <div class="form-group">
            <label for="photo">Фотография:</label><br>
            <img src="{{ asset('images/')}}/{{$product->photo_path}}" alt="{{$product->name}}" style="max-width: 200px;">
        </div>
        <br>
        <div class="form-group">
            <label for="new_photo">Новая фотография:</label>
            <input type="file" class="form-control-file" id="new_photo" name="new_photo">
        </div>
        <br>
        <div class="form-group">
            <label for="category">Категория товара:</label>
            <select class="form-control" id="category" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $product->product_category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="status">Статус товара:</label>
            <select class="form-control" id="status" name="status_id">
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ $status->id == $product->product_status_id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Изменить</button>
    </form>
</div>
@endsection

