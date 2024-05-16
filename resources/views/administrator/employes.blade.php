@extends('layouts.app')
@section('content')
<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between"><span class="align-self-center">Список сотрудников</span>
                    
                    <button type="button" class="btn btn-primary " id="addEmployeeBtn" data-toggle="modal" data-target="#addEmployeeModal">
                        Добавить нового сотрудника
                    </button>
                </div>

                <div class="card-body">
                
                <!-- Модальное окно -->
                <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Добавление нового сотрудника</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Форма добавления сотрудника -->
                                <form action="{{ route('employes.add') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-group row">
                                            <label for="surname" class="col-md-4 col-form-label text-md-right">Фамилия</label>

                                            <div class="col-md-6">
                                                <input name="surname" id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required autocomplete="surname" autofocus>

                                                @error('surname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>

                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="Patronymic" class="col-md-4 col-form-label text-md-right">Отчество</label>

                                            <div class="col-md-6">
                                                <input id="Patronymic" type="text" class="form-control @error('Partonymic') is-invalid @enderror" name="Partonymic" value="{{ old('Patronymic') }}" required autocomplete="Patronymic" autofocus>

                                                @error('Patronymic')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="role" class="col-md-4 col-form-label text-md-right">Роль</label>
                                            <div class="col-md-6">
                                            <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                                <option value="">Выберите тип вашего профиля</option>
                                                <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Продавец</option>
                                                <option value="4" {{ old('role') == 4 ? 'selected' : '' }}>Менеджер</option>
                                                <option value="5" {{ old('role') == 5 ? 'selected' : '' }}>Администратор</option>
                                            </select>
                                                @error('role')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="login" class="col-md-4 col-form-label text-md-right">Логин</label>

                                            <div class="col-md-6">
                                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login">

                                                @error('login')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Подтвердите пароль</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                Добавить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Фамилия</th>
                                <th>Имя</th>
                                <th>Отчество</th>
                                <th>Логин</th>
                                <th>Роль</th>
                                <th>Статус аккаунта</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->Partonymic }}</td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->role->name }}</td>
                                <td>{{ $user->accountStatus->name }}</td>
                                <td>
                                    <form method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary activateEmployeeBtn" data-userid="{{ $user->id }}">Восстановить</a>
                                        
                                    </form>
                                </td>
                                <td>
                                    <form method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-danger deleteEmployeeBtn" data-userid="{{ $user->id }}">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('.deleteEmployeeBtn').click(function(e){
        e.preventDefault();
        var userId = $(this).data('userid');
        var url = '/main_administrator/employes/' + userId + '/deactivate';
        
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'POST'
            },
            success: function(data){
                alert(data.message);
                // Обновляем страницу после успешного удаления сотрудника
                location.reload();
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
                alert('Произошла ошибка при удалении сотрудника.');
            }
        });
    });
});

$(document).ready(function(){
    $('.activateEmployeeBtn').click(function(e){
        e.preventDefault();
        var userId = $(this).data('userid');
        var url = '/main_administrator/employes/' + userId + '/activate';
        
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'POST'
            },
            success: function(data){
                alert(data.message);
                // Обновляем страницу после успешного удаления сотрудника
                location.reload();
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
                alert('Произошла ошибка при восстановлении сотрудника.');
            }
        });
    });
});
</script>
@endsection