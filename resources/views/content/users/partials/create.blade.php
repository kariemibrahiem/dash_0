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

    <!-- Validation Errors -->
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Session Error Message -->
    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <!-- Session Success Message -->
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row m-3">

        <!-- Username -->
        <div class="col-5 mb-3">
          <label class="form-label" for="name">{{ trns('username') }}</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" 
                 id="name" name="name" value="{{ old('name') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="col-5 mb-3">
          <label class="form-label" for="email">{{ trns('email') }}</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" 
                 id="email" name="email" value="{{ old('email') }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Phone -->
        <div class="col-5 mb-3">
          <label class="form-label" for="phone">{{ trns('phone') }}</label>
          <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                 id="phone" name="phone" value="{{ old('phone') }}">
          @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="col-5 mb-3">
          <label class="form-label" for="password">{{ trns('password') }}</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" 
                 id="password" name="password" required>
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password Confirmation -->
        <div class="col-5 mb-3">
          <label class="form-label" for="password_confirmation">{{ trns('password_confirmation') }}</label>
          <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                 id="password_confirmation" name="password_confirmation" required>
          @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Image -->
        <div class="col-12 mb-3">
          <label class="form-label" for="image">{{ trns('image') }}</label>
          <input  accept="image/*"  class="form-control @error('image') is-invalid @enderror" 
                 type="file" id="image" name="image">
          @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>
      
      <!-- Submit & Cancel Buttons -->
      <button type="submit" class="btn btn-primary">{{ trns('create') }}</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ trns('cancel') }}</a>
    </form>
  </div>
</div>
@endsection
