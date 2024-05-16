@extends('layouts.app')
@section('content')
<div class="container">
    <br>
    <h1>Создание товара</h1>
    <br>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Наименование:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" maxlength='255' required autofocus>{{ old('description') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <label for="cost">Стоимость:</label>
            <input type="text" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost') }}" required autofocus>
            @error('cost')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <label for="count">Количество:</label>
            <input type="number" class="form-control" id="count" name="count" value="{{ old('count') }}" required min="0" autofocus inputmode="numeric">
        </div>
        <br>
        <div class="form-group">
            <label for="photo">Фотография:</label><br>
            <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" required autofocus>
            @error('photo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <label for="category">Категория товара:</label>
            <select class="form-control" id="category" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="status">Статус товара:</label>
            <select class="form-control" id="status" name="status_id">
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
</div>
@endsection
