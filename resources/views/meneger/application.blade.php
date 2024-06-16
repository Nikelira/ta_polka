@extends('layouts.app')

@section('content')
<style>
    a {
    color: inherit;
}
</style>
    <div class="container mt-5">
        <h1>Управление заявками</h1>
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action {{ $status == 2 ? 'active' : '' }}">
                        <a href="{{ route('application.index', ['status' => 2]) }}">Активные заявки</a>
                    </li>
                    <li class="list-group-item list-group-item-action {{ $status == 3 ? 'active' : '' }}">
                        <a href="{{ route('application.index', ['status' => 3]) }}">Одобренные заявки</a>
                    </li>
                    <li class="list-group-item list-group-item-action {{ $status == 4 ? 'active' : '' }}">
                        <a href="{{ route('application.index', ['status' => 4]) }}">Все заявки</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Номер заявки</th>
                            <th>Дата заявки</th>
                            <th>Пользователь</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr id="application-{{ $application->id }}">
                                <td>{{ $application->id }}</td>
                                <td>{{ $application->date_application }}</td>
                                <td>{{ $application->user->surname }} {{ $application->user->name }} {{ $application->user->Partonymic }}</td>
                                <td>{{ $application->applicationStatus->name }}</td>
                                <td>
                                    @if($application->application_status_id == 2)
                                        <a href="{{ route('application.show', ['id' => $application->id, 'status' => $status]) }}" class="btn btn-info">Подробнее</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection