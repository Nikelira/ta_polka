@extends('layouts.app_administrator')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактирование сотрудника</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employees.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>
                            <div class="col-md-6">
                                <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    <br>
                    <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">Фамилия</label>
                            <div class="col-md-6">
                                <input name="surname" id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" value="{{ $user->surname }}" required autocomplete="surname" autofocus>
                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    <br>
                    <div class="form-group row">
                            <label for="Partonymic" class="col-md-4 col-form-label text-md-right">Отчество</label>
                            <div class="col-md-6">
                                <input name="Partonymic" id="Partonymic" type="text" class="form-control @error('Partonymic') is-invalid @enderror" value="{{ $user->Partonymic }}" required autocomplete="Partonymic" autofocus>
                                @error('Partonymic')
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
                                <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Продавец</option>
                                <option value="4" {{ $user->role_id == 4 ? 'selected' : '' }}>Менеджер</option>
                                <option value="5" {{ $user->role_id == 5 ? 'selected' : '' }}>Администратор</option>
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
                                <input name="login" id="login" type="text" class="form-control @error('login') is-invalid @enderror" value="{{ $user->login }}" required autocomplete="login" autofocus>
                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    <br>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                               Обновить
                            </button>
                            <a class="btn btn-primary float-right" href="{{ route('employes.index')}}">Назад</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection