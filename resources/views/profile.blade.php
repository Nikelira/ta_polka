@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <br>
        <div class="card">
            <div class="card-header">Профиль пользователя</div>

            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="surname" class="col-md-4 col-form-label text-md-right">Фамилия</label>
                        <div class="col-md-6">
                            <input id="surname" type="text" class="form-control" name="surname" value="{{ $user->surname }}" required autocomplete="surname" autofocus>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="Partonymic" class="col-md-4 col-form-label text-md-right">Отчество</label>
                        <div class="col-md-6">
                            <input id="Partonymic" type="text" class="form-control" name="Partonymic" value="{{ $user->Partonymic }}" required autocomplete="Partonymic" autofocus>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="login" class="col-md-4 col-form-label text-md-right">Логин</label>
                        <div class="col-md-6">
                            <input id="login" type="text" class="form-control" name="login" value="{{ $user->login }}" required autocomplete="login">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Новый пароль</label>
                        <div class="col-md-6">
                            <input id="password" class="form-control" name="password" value="" autocomplete="new-password">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                               Сохранить изменения
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                                Удалить аккаунт
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
        Вы уверены, что хотите удалить свой аккаунт? Это действие нельзя отменить.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <form method="POST" action="{{ route('profile.delete') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить аккаунт</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection