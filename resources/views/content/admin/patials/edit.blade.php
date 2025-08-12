@extends('layouts/contentNavbarLayout')

@section('title', trns('Edit Admin'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('users.index') }}">{{ trns('Users') }}</a> /
  </span> {{ trns('Edit Admin') }}
</h4>
<div class="card">
  <div class="card-body">
    <form action="{{ route('admins.update', $obj->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row m-3">

        <div class="col-5">
          <label class="form-label" for="user_name">{{ trns('user_name') }}</label>
          <input type="text" class="form-control" id="user_name" name="user_name" 
                 value="{{ $obj->user_name }}" required>
        </div>

        <div class="col-5">
          <label class="form-label" for="email">{{ trns('email') }}</label>
          <input type="email" class="form-control" id="email" name="email" 
                 value="{{ old('email', $obj->email) }}" required>
        </div>

        <div class="col-5">
          <label class="form-label" for="password">{{ trns('password') }}</label>
          <input type="password" class="form-control" id="password" name="password">
          <small class="text-muted">{{ trns('leave_blank_to_keep_current') }}</small>
        </div>

        <div class="col-5">
          <label class="form-label" for="code">{{ trns('code') }}</label>
          <input type="code" class="form-control" value="{{$obj->code}}" disabled readonly id="code" name="code">
          <small class="text-muted">{{ trns('leave_blank_to_keep_current') }}</small>
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ trns('update') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection
