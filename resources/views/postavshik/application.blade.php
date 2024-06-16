@extends('layouts.app')

@section('content')
<style>
    a {
        color: inherit;
        text-decoration: none;
    }
    a:hover {
        color: inherit;
        text-decoration: underline;
    }
    .list-group-item.active {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
</style>
<div class="container mt-5">
    <h1>Управление заявками</h1>
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item list-group-item-action {{ $status == 2 ? 'active' : '' }}">
                    <a href="{{ route('postavshik.index', ['status' => 2]) }}">Активные заявки</a>
                </li>
                <li class="list-group-item list-group-item-action {{ $status == 3 ? 'active' : '' }}">
                    <a href="{{ route('postavshik.index', ['status' => 3]) }}">Одобренные заявки</a>
                </li>
                <li class="list-group-item list-group-item-action {{ $status == 4 ? 'active' : '' }}">
                    <a href="{{ route('postavshik.index', ['status' => 4]) }}">Отказанные заявки</a>
                </li>
                <li class="list-group-item list-group-item-action {{ $status == 5 ? 'active' : '' }}">
                    <a href="{{ route('postavshik.index', ['status' => 5]) }}">Отмененные заявки</a>
                </li>
                <li class="list-group-item list-group-item-action {{ !in_array($status, [2, 3, 4, 5]) ? 'active' : '' }}">
                    <a href="{{ route('postavshik.index', ['status' => 'all']) }}">История заявок</a>
                </li>
            </ul>
        </div>

        <!-- Контент заявок -->
        <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <th>Номер заявки</th>
                        <th>Дата заявки</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr id="application-{{ $application->id }}">
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->date_application }}</td>
                            <td>{{ $application->applicationStatus->name }}</td>
                            <td>
                                <!-- Кнопка "Подробнее" -->
                                <a href="{{ route('postavshik.show', ['id' => $application->id, 'status' => $status]) }}" class="btn btn-info">Подробнее</a>
                                
                                <!-- Кнопка "Отменить" для активных заявок -->
                                @if($application->application_status_id == 2)
                                    <button type="button" class="btn btn-danger" onclick="deleteApplication({{ $application->id }})">Отменить заявку</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function deleteApplication(applicationId) {
        if (!confirm('Вы уверены, что хотите отменить эту заявку?')) {
            return;
        }

        $.ajax({
            url: '{{ route("application.delete") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                application_id: applicationId
            },
            success: function(response) {
                if (response.success) {
                    $('#status-' + applicationId).text('Отменено'); // Обновляем статус текста
                    $('#application-' + applicationId).find('button').remove(); // Удаляем кнопку отмены
                    alert('Заявка успешно отменена.');
                } else {
                    alert('Произошла ошибка при отмене заявки.');
                }
            },
            error: function() {
                alert('Произошла ошибка при отправке запроса.');
            }
        });
    }
</script>
@endsection