@extends('layouts/contentNavbarLayout')

@section('title', trns('Create Admin'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('users.index') }}">{{ trns('Users') }}</a> /
  </span> {{ trns('Create Admin') }}
</h4>

<div class="card">
  <div class="card-body">
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row m-3">

        <!-- Username -->
        <div class="col-5">
          <label class="form-label" for="name">{{ trns('username') }}</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <!-- Email -->
        <div class="col-5">
          <label class="form-label" for="email">{{ trns('email') }}</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <!-- Password -->
        <div class="col-5">
          <label class="form-label" for="password">{{ trns('password') }}</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Image -->
        <div class="col-5">
          <label class="form-label" for="image">{{ trns('image') }}</label>
          <input class="form-control" type="file" id="image" name="image">
        </div>

      </div>

      <!-- Submit & Cancel Buttons -->
      <button type="submit" class="btn btn-primary">{{ trns('create') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection
