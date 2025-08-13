@extends('layouts/blankLayout')

@section('title', trns('OTP Verification'))

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
<style>
  /* Extra touch for OTP input boxes on small screens */
  @media (max-width: 576px) {
    .otp-input {
      font-size: 1.2rem;
      letter-spacing: 0.2rem;
      text-align: center;
    }
  }
</style>
@endsection

@section('content')
<div class="container-xxl">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

          <!-- OTP Verification -->
          <div class="card shadow-sm">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-4">
                <a href="{{ url('/') }}" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    @include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])
                  </span>
                  <span class="app-brand-text demo text-body fw-bold">
                    {{ config('variables.templateName') }}
                  </span>
                </a>
              </div>
              <!-- /Logo -->

              <h4 class="mb-2 text-center">{{ trns('Verify Your Account') }} 🔒</h4>
              <p class="mb-4 text-center">{{ trns('Enter the OTP code sent to your email to continue.') }}</p>

              <form id="formOtpVerification" class="mb-3" action="{{ route('verify-otp') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="mb-3">
                  <label for="otp" class="form-label">{{ trns('OTP Code') }}</label>
                  <input type="text" class="form-control otp-input" id="otp" name="otp" placeholder="{{ trns('Enter your OTP') }}" autofocus>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">{{ trns('New Password') }}</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="{{ trns('Enter your password') }}">
                </div>

                <div class="mb-3">
                  <label for="password_confirmation" class="form-label">{{ trns('Confirm Password') }}</label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ trns('Confirm your password') }}">
                </div>

                <button class="btn btn-primary d-grid w-100">{{ trns('Verify OTP') }}</button>
              </form>

              <div class="text-center mt-3">
                <a href="{{ route('reset-password') }}" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-refresh bx-sm me-1"></i>
                  {{ trns('Resend OTP') }}
                </a>
              </div>

              <div class="text-center mt-2">
                <a href="{{ url('auth/login-basic') }}" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  {{ trns('Back to login') }}
                </a>
              </div>

            </div>
          </div>
          <!-- /OTP Verification -->

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
