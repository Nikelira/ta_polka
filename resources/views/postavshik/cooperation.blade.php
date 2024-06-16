@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Оформление аренды</h1>

    <!-- Раздел для выбранных полок -->
    <div class="row mb-5">
        <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
            <h2>Выбранные полки</h2>
        </div>
        @forelse ($selectedShelvesObjects as $shelf)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Полка {{ $shelf->number_shelv }}</h5>
                        <p class="card-text">Шкаф: {{ $shelf->number_wardrobe }}</p>
                        <p class="card-text">Размеры: {{ $shelf->length }}x{{ $shelf->wigth }} см</p>
                        <p class="card-text">Стоимость: {{ $shelf->cost }} руб.</p>
                        <button class="btn btn-danger" onclick="deselectShelf({{ $shelf->id }})">Удалить</button>
                    </div>
                </div>
            </div>
        @empty
            <p>У вас нет выбранных полок</p>
        @endforelse 

        <div class="col-12">
            <h2>Ваши товары</h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Добавить товар</button>
            <br>
            <br>
        </div>
        @forelse (session('products', []) as $product)
            <div class="col-6 col-md-3 mb-3 col-6-mobile">
                <div class="card product-card">
                    <img src="{{ asset('images/' . $product['photo']) }}" class="card-img-top" alt="{{ $product['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text">{{ $product['description'] }}</p>
                        <p class="card-text">Категория: {{ $productCategories->firstWhere('id', $product['product_category_id'])->name }}</p>
                        <p class="card-text">Стоимость: {{ $product['cost'] }} руб.</p>
                        <p class="card-text">Количество: {{ $product['count'] }}</p>
                    </div>
                    <button class="btn btn-primary edit-product-btn" data-product-id="{{ $product['id'] }}">Редактировать</button>
                    <button class="btn btn-danger delete-product-btn" data-product-id="{{ $product['id'] }}">Удалить</button>
                </div>
            </div>
                   
        @empty
            <p>У вас нет добавленных товаров</p>
        @endforelse
    </div>

    @if (!empty(session('products', [])) && !empty($selectedShelvesObjects))
        <div class="row mb-5">
            <div class="col-12 text-center">
                <button class="btn btn-primary" onclick="submitRentalRequest()">Оформить заявку на аренду полок</button>
            </div>
        </div>
    @endif
</div>

<!-- Модальное окно для добавления товара -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addProductForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Добавить товар</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productName">Название товара</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="productCategory">Категория товара</label>
                        <select class="form-control" id="productCategory" name="product_category_id" required>
                            @foreach ($productCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productDescription">Описание</label>
                        <textarea class="form-control" id="productDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="productPhoto">Фото</label>
                        <input type="file" class="form-control-file" id="productPhoto" name="photo" required>
                    </div>
                    <div class="form-group">
                        <label for="productCost">Стоимость</label>
                        <input type="number" class="form-control" id="productCost" name="cost" required>
                    </div>
                    <div class="form-group">
                        <label for="productCount">Количество</label>
                        <input type="number" class="form-control" id="productCount" name="count" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editProductForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Редактировать товар</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="editProductId">
                    <div class="form-group">
                        <label for="editProductName">Название товара</label>
                        <input type="text" class="form-control" id="editProductName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductCategory">Категория товара</label>
                        <select class="form-control" id="editProductCategory" name="product_category_id" required>
                            @foreach ($productCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editProductDescription">Описание</label>
                        <textarea class="form-control" id="editProductDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editProductCost">Стоимость</label>
                        <input type="number" class="form-control" id="editProductCost" name="cost" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductCount">Количество</label>
                        <input type="number" class="form-control" id="editProductCount" name="count" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    h1{
        text-align:center;
    }
</style>
@push('scripts')
<script>
function deselectShelf(shelfId) {
    $.ajax({
        url: '{{ route("cooperation.deselects") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            shelf_id: shelfId
        },
        success: function(response) {
            if (response.success) {
                location.reload(); // Reload the page to reflect the changes
            } else {
                alert(response.message);
            }
        },
        error: function(response) {
            alert('Ошибка отмены выбора полки');
        }
    });
}

$(document).ready(function() {
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("cooperation.addProduct") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert(response.message);
                }
            },
            error: function(response) {
                alert('Ошибка добавления товара');
            }
        });
    });
});

$(document).ready(function() {
    $('.edit-product-btn').click(function() {
        var productId = $(this).data('product-id');
        var product = getProductById(productId);

        // Заполняем поля модального окна данными о товаре для редактирования
        $('#editProductModal #editProductId').val(productId);
        $('#editProductModal #editProductName').val(product.name);
        $('#editProductModal #editProductCategory').val(product.product_category_id);
        $('#editProductModal #editProductDescription').val(product.description);
        $('#editProductModal #editProductCost').val(product.cost);
        $('#editProductModal #editProductCount').val(product.count);

        $('#editProductModal').modal('show');
    });

    function getProductById(productId) {
        var products = @json(session('products', [])); // Получаем данные о товарах из сессии

        // Ищем товар с указанным ID в массиве товаров
        var product = products.find(function(item) {
            return item.id == productId;
        });

        return product; // Возвращаем найденный товар или null, если товар не найден
    }
});

$(document).ready(function() {
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();

        var productId = $('#editProductId').val();
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("cooperation.update", ["id" => ":id"]) }}'.replace(':id', productId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(response) {
                alert('Ошибка обновления товара');
            }
        });
    });
});

$(document).ready(function() {
    $('.delete-product-btn').click(function() {
        var productId = $(this).data('product-id');

        if (confirm('Вы уверены, что хотите удалить этот товар?')) {
            $.ajax({
                url: '{{ route("cooperation.deleteProduct", ":id") }}'.replace(':id', productId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert(response.message);
                    }
                },
                error: function(response) {
                    alert('Ошибка удаления товара');
                }
            });
        }
    });
});

function submitRentalRequest() {
    $.ajax({
        url: '{{ route("cooperation.submit") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload(); // Reload the page to reflect the changes
            } else {
                alert(response.message);
            }
        },
        error: function(response) {
            alert('Ошибка при отправке заявки');
        }
    });
}
</script>
@endpush
@endsection

