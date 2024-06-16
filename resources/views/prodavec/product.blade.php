@extends('layouts.app')

@section('content')
<div class="container-fluid"> <!-- Используем container-fluid для максимального использования пространства -->
    <br>
    <div class="card-header d-flex justify-content-between">
        <span class="align-self-center">Список товаров</span>
        <a type="button" class="btn btn-primary" href="{{ route('products.create') }}">
            Добавить новый товар
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Фото</th>
                    <th class="d-none d-md-table-cell">Описание</th> <!-- Скрываем на маленьких экранах -->
                    <th>Цена</th>
                    <th>Кол-во</th> <!-- Сокращаем текст -->
                    <th class="d-none d-lg-table-cell">Категория</th> <!-- Скрываем на маленьких экранах -->
                    <th class="d-none d-lg-table-cell">Статус</th> <!-- Скрываем на маленьких экранах -->
                    <th class="d-none d-xl-table-cell">Заявка №</th> <!-- Скрываем на маленьких экранах -->
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>
                        <img src="{{ asset('images/')}}/{{$product->photo_path}}" 
                             alt="{{$product->name}}" 
                             style="max-width: 50px; max-height: 50px; object-fit: cover;"> <!-- Уменьшаем размер изображения -->
                    </td>
                    <td class="d-none d-md-table-cell text-truncate" style="max-width: 150px;"> <!-- Ограничиваем ширину -->
                        {{ $product->description }}
                    </td>
                    <td>{{ $product->cost }}</td>
                    <td>{{ $product->count }}</td>
                    <td class="d-none d-lg-table-cell">{{ $product->category->name }}</td>
                    <td class="d-none d-lg-table-cell">{{ $product->status->name }}</td>
                    <td class="d-none d-xl-table-cell text-truncate" style="max-width: 150px;"> <!-- Ограничиваем ширину -->
                        {{ $product->rental_application_id }} от {{ $product->rentalApplication->date_application }}
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal" data-id="{{ $product->id }}">Удалить</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно для подтверждения удаления -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Удаление аккаунта</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Вы уверены, что хотите удалить товар? Это действие нельзя отменить.
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route('products.destroy', $product->id) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Удалить товар</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
      </div>
    </div>
  </div>
</div>
@endsection