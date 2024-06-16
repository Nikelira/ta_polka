@extends('layouts.app')
@section('content')
<div class="container-fluid mt-5">
        <div class="row">
            <!-- Боковая навигация -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('contracts.index', ['category' => 'active']) }}" 
                       class="list-group-item list-group-item-action {{ $category == 'active' ? 'active' : '' }}">
                       Активные договора
                    </a>
                    <a href="{{ route('contracts.index', ['category' => 'expired']) }}" 
                       class="list-group-item list-group-item-action {{ $category == 'expired' ? 'active' : '' }}">
                       Истекшие договора
                    </a>
                </div>
            </div>

            <!-- Основной контент -->
            <div class="col-md-9">
                <h1 class="mb-4">
                    @if ($category == 'active')
                        Активные договора
                    @else
                        Истекшие договора
                    @endif
                </h1>

                <!-- Кнопка для создания нового договора -->
                <a href="{{ route('contracts.create') }}" class="btn btn-primary mb-4">Новый договор</a>

                <!-- Таблица договоров -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Дата начала</th>
                            <th>Дата окончания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->id }}</td>
                                <td>{{ $contract->rentalApplication->user->surname }} {{ $contract->rentalApplication->user->name }} {{ $contract->rentalApplication->user->Partonymic }}</td>
                                <td>{{ \Carbon\Carbon::parse($contract->date_begin)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contract->date_end)->format('d.m.Y') }}</td>
                                <td>
                                    <!-- Ссылки на просмотр или редактирование договора -->
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-info btn-sm">Просмотр</a>
                                    <a href="{{ route('contracts.download', $contract->id) }}" class="btn btn-success btn-sm">Скачать</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Нет договоров в этой категории</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection