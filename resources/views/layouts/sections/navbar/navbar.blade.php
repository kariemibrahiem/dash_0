@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<style>
  /* Logout hover */
  .logout-link:hover {
    color: red !important;
  }

  /* RTL fixes */
  @if(app()->getLocale() == 'ar')
  body, html {
    direction: rtl;
  }

  /* Navbar collapse & flex adjustments */
  #navbar-collapse {
    direction: rtl;
  }

  .navbar-nav .nav-item {
    text-align: right;
  }

  /* Swap margins for RTL */
  .me-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
  .me-3 { margin-left: 1rem !important; margin-right: 0 !important; }
  .me-4 { margin-left: 1.5rem !important; margin-right: 0 !important; }
  .ms-auto { margin-left: 0 !important; margin-right: auto !important; }

  /* Dropdown menus align right */
  .dropdown-menu, .dropdown-menu-end {
    text-align: right;
  }

  /* Search input */
  .navbar-nav .nav-item input[type="text"] {
    text-align: right;
    padding-left: 0.5rem;
    padding-right: 1rem;
  }
  @endif
</style>

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
@else
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="{{$containerNav}}">
@endif

  <!-- Brand (display only for navbar-full and hidden below xl) -->
  @if(isset($navbarFull))
  <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo">
        @include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])
      </span>
      <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
    </a>
  </div>
  @endif

  <!-- Toggle -->
  @if(!isset($navbarHideToggle))
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>
  @endif

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
{{--    <div class="navbar-nav align-items-center">--}}
{{--      <div class="nav-item d-flex align-items-center">--}}
{{--        <i class="bx bx-search fs-4 lh-0"></i>--}}
{{--        <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..." aria-label="Search...">--}}
{{--      </div>--}}
{{--    </div>--}}

      <div class="app-brand demo">
          <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo">
        @include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])
      </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2">{{config('variables.templateName')}}</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
      </div>

    <!-- Navbar items -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <!-- Notifications -->


      <!-- Language dropdown -->
    <li class="nav-item dropdown ms-2"
        @if(app()->getLocale() == "ar")
            style="z-index: 2000 !important; position: absolute; left: 130px;"
        @endif
    >
      <a class="nav-link dropdown-toggle btn btn-sm btn-light py-1" href="#" id="langDropdown"
        role="button" data-bs-toggle="dropdown" aria-expanded="false">
        🌐 {{ strtoupper(app()->getLocale()) }}
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown" style="z-index: 2000;">
        <li><a class="dropdown-item" href="{{ route('change_language', 'en') }}">English</a></li>
        <li><a class="dropdown-item" href="{{ route('change_language', 'ar') }}">العربية</a></li>
      </ul>
    </li>



      <!-- User dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown ms-2"
      @if(app()->getLocale() == "ar")
          style=" z-index: 2000 !important; position: absolute; left: 40px;"
      @endif
      >
        <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="avatar avatar-online">
              @php
                  $adminImage = auth('admin')->user()->image;
                  $imagePath = $adminImage && file_exists(public_path($adminImage))
                      ? asset($adminImage)
                      : asset('assets/img/avatars/1.png');
              @endphp

              <img alt="{{ trns('admin_image') }}" src="{{ $imagePath }}" class="w-px-40 h-auto rounded-circle">
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="javascript:void(0);">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                      @php
                          $adminImage = auth('admin')->user()->image;
                          $imagePath = $adminImage && file_exists(public_path($adminImage))
                              ? asset($adminImage)
                              : asset('assets/img/avatars/1.png');
                      @endphp

                      <img alt="{{ trns('admin_image') }}" src="{{ $imagePath }}" class="w-px-40 h-auto rounded-circle">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-medium d-block">{{ auth('admin')->user()->user_name }}</span>
                </div>
              </div>
            </a>
          </li>

          <li><div class="dropdown-divider"></div></li>

          <li>
            <a class="dropdown-item" href="{{route('admins.profile')}}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">My Profile</span>
            </a>
          </li>

          <li>
            <a class="dropdown-item" href="javascript:void(0);">
              <i class='bx bx-cog me-2'></i>
              <span class="align-middle">Settings</span>
            </a>
          </li>

{{--          <li>--}}
{{--            <a class="dropdown-item" href="javascript:void(0);">--}}
{{--              <span class="d-flex align-items-center align-middle">--}}
{{--                <i class="flex-shrink-0 bx bx-credit-card me-2 pe-1"></i>--}}
{{--                <span class="flex-grow-1 align-middle">Billing</span>--}}
{{--                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>--}}
{{--              </span>--}}
{{--            </a>--}}
{{--          </li>--}}

          <li><div class="dropdown-divider"></div></li>

          <li>
            <a class="dropdown-item logout-link" href="{{ route('admin.logout') }}">
              <i class='bx bx-power-off me-2'></i>
              {{ trns('logout') }}
            </a>
          </li>
        </ul>
      </li>

    </ul>
  </div>

  @if(!isset($navbarDetached))
  </div>
  @endif
</nav>

<!-- Bootstrap JS fallback + dropdown initializer -->
<script>
(function () {
  function initDropdowns() {
    try {
      if (typeof bootstrap === 'undefined') {
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
        s.crossOrigin = 'anonymous';
        s.defer = true;
        s.onload = function () {
          document.querySelectorAll('.dropdown-toggle').forEach(function (el) {
            if (!bootstrap.Dropdown.getInstance(el)) {
              bootstrap.Dropdown.getOrCreateInstance(el);
            }
          });
        };
        document.head.appendChild(s);
      } else {
        document.querySelectorAll('.dropdown-toggle').forEach(function (el) {
          if (!bootstrap.Dropdown.getInstance(el)) {
            bootstrap.Dropdown.getOrCreateInstance(el);
          }
        });
      }
    } catch (err) {
      console.error('Dropdown init error', err);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDropdowns);
  } else {
    initDropdowns();
  }
})();
</script>
