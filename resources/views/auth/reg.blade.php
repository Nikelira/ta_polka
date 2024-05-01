@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <br>
        <div class="card">
            <div class="card-header">Регистрация</div>

            <div class="card-body">
                <form method="POST" action="{{ route('reg.store') }}">
                    @csrf
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
                            <option value="1" title="Я хочу разместить свои товары" {{ old('role') == 1 ? 'selected' : '' }}>Поставщик</option>
                            <option value="2" title="Я хочу купить товары" {{ old('role') == 2 ? 'selected' : '' }}>Покупатель</option>
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
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree" id="agree" required>

                                <label class="form-check-label" for="agree">
                                    Я согласен с обработкой персональных данных
                                </label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                               Зарегистрироваться
                            </button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="form-group row">
                    <label for="reg" class="col-md-4 col-form-label text-md-right">У вас уже есть аккаунт?</label>
                    <div class="col-md-6">
                        <a class="col-md-4 col-form-label text-md-right" href="{{route('auth.index')}}">Войти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
