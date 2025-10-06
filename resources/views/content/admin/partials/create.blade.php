@extends('layouts/contentNavbarLayout')

@section('title', trns('Create Admin'))

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="{{ route('admins.index') }}">{{ trns('Admins') }}</a> /
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

        {{-- User Name --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="user_name">{{ trns('username') }}</label>
          <input type="text" class="form-control @error('user_name') is-invalid @enderror" 
                 id="user_name" name="user_name" value="{{ old('user_name') }}" required>
          @error('user_name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="email">{{ trns('email') }}</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" value="{{ old('email') }}" required>
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Phone --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="phone">{{ trns('phone') }}</label>
          <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                 id="phone" name="phone" value="{{ old('phone') }}">
          @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="password">{{ trns('password') }}</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" 
                 id="password" name="password" required>
          @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password Confirmation --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="password_confirm">{{ trns('password_confirm') }}</label>
          <input type="password" class="form-control @error('password_confirm') is-invalid @enderror" 
                 id="password_confirm" name="password_confirm" required>
          @error('password_confirm')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Image --}}
        <div class="col-md-6 mb-3">
          <label class="form-label" for="image">{{ trns('image') }}</label>
          <input class="form-control @error('image') is-invalid @enderror"  accept="image/*" type="file" id="image" name="image">
          @error('image')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Role as Select --}}
        <div class="col-md-12 mb-3">
          <label class="form-label" for="role_id">{{ trns('Role') }}</label>
          <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" id="role_id" required>
            <option value="">{{ trns('Select Role') }}</option>
            @foreach($roles as $role)
              <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          @error('role_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ trns('create') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection
