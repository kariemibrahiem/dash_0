@extends('layouts/contentNavbarLayout')

@section('title', trns('Create Admin'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('users.index') }}">{{ trns('Users') }}</a> /
  </span> {{ trns('Create Admin') }}
</h4>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
  <div class="card-body">
    <form action="{{ route('admins.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row m-3">

        <div class="col-5">
          <label class="form-label" for="user_name">{{ trns('username') }}</label>
          <input type="text" class="form-control" id="user_name" name="user_name" value="{{ old('user_name') }}" required>
        </div>

        <div class="col-5">
          <label class="form-label" for="email">{{ trns('email') }}</label>
          <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="col-5">
          <label class="form-label" for="password">{{ trns('password') }}</label>
          <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" required>
        </div>

        <div class="col-5">
          <label class="form-label" for="password_confirm">{{ trns('password_confirm') }}</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" value="{{ old('password_confirm') }}" required>
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ trns('create') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection
