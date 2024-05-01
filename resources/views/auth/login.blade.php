@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
        <br>
            <div class="card">
                <div class="card-header">Вход</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('auth.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="login" class="col-md-4 col-form-label text-md-right">Логин</label>

                            <div class="col-md-6">
                                <input id="login" type="login" class="form-control @error('login') is-invalid @enderror" name="login"  required autocomplete="login" autofocus>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="Password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                            <div class="col-md-6">
                                <input id="Password" type="Password" class="form-control @error('Password') is-invalid @enderror" name="Password"  required autocomplete="Password" autofocus>

                                @error('Password')
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
                                    Войти
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="form-group row">
                        <label for="reg" class="col-md-4 col-form-label text-md-right">У вас нет аккаунта?</label>

                        <div class="col-md-6">
                            <a class="col-md-4 col-form-label text-md-right" href="{{route('reg.index')}}">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
