@extends('layouts/contentNavbarLayout')

@section('title', '404 - ' . trns('Page Not Found'))

@section('content')
<div class="container text-center py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <h1 class="display-1 fw-bold text-primary">404</h1>
      <h3 class="mb-3">{{ trns('Oops! Page Not Found') }}</h3>
      <p class="text-muted mb-4">
        {{ trns("The page you're looking for doesn't exist, has been moved, or is temporarily unavailable.") }}
      </p>

      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left"></i> {{ trns('Go Back') }}
      </a>

      <a href="{{ route('dashboard-analytics') }}" class="btn btn-primary">
        <i class="fas fa-home"></i> {{ trns('Go to Homepage') }}
      </a>

    </div>
  </div>
</div>
@endsection
