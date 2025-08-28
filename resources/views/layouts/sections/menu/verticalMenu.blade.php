<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
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

  @php
    $menuData = include resource_path('views/layouts/sections/menu/verticalMenu.php');
  @endphp

  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">

    @foreach ($menuData as $menu)

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ trns($menu->menuHeader) }}</span>
        </li>
      @else
        @php
          $activeClass = null;
          $currentRouteName = Route::currentRouteName();

          if ($currentRouteName === $menu->url) {
              $activeClass = 'active';
          } elseif (isset($menu->submenu)) {
              if (is_array($menu->slug)) {
                  foreach($menu->slug as $slug){
                      if (str_starts_with($currentRouteName, $slug)) {
                          $activeClass = 'active open';
                      }
                  }
              } else {
                  if (str_starts_with($currentRouteName, $menu->slug)) {
                      $activeClass = 'active open';
                  }
              }
          }
        @endphp

        @if($menu->permissions === 'dashboard' || auth()->user()->can($menu->permissions))
        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($menu->url) ? route($menu->url) : 'javascript:void(0);' }}"
             class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
             @if (!empty($menu->target)) target="_blank" @endif>
            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ trns($menu->name ?? '') }}</div>
            @isset($menu->badge)
              <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
            @endisset
          </a>

          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
          @endisset
        </li>
        @endcan
      @endif

    @endforeach
  </ul>
</aside>