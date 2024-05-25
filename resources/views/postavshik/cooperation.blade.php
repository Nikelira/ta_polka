@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Оформление аренды</h1>
    <div class="row">
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
    </div>
</div>

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
</script>
@endsection