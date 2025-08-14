<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="<?php echo e(url('/')); ?>" class="app-brand-link">
      <span class="app-brand-logo demo">
        <?php echo $__env->make('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2"><?php echo e(config('variables.templateName')); ?></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <?php
    $menuData = include resource_path('views/layouts/sections/menu/verticalMenu.php');
  ?>

  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">

    <?php $__currentLoopData = $menuData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      
      <?php if(isset($menu->menuHeader)): ?>
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text"><?php echo e(trns($menu->menuHeader)); ?></span>
        </li>
      <?php else: ?>
        <?php
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
        ?>

        <?php if($menu->permissions === 'dashboard' || auth()->user()->can($menu->permissions)): ?>
        <li class="menu-item <?php echo e($activeClass); ?>">
          <a href="<?php echo e(isset($menu->url) ? route($menu->url) : 'javascript:void(0);'); ?>"
             class="<?php echo e(isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link'); ?>"
             <?php if(!empty($menu->target)): ?> target="_blank" <?php endif; ?>>
            <?php if(isset($menu->icon)): ?>
              <i class="<?php echo e($menu->icon); ?>"></i>
            <?php endif; ?>
            <div><?php echo e(trns($menu->name ?? '')); ?></div>
            <?php if(isset($menu->badge)): ?>
              <div class="badge bg-<?php echo e($menu->badge[0]); ?> rounded-pill ms-auto"><?php echo e($menu->badge[1]); ?></div>
            <?php endif; ?>
          </a>

          <?php if(isset($menu->submenu)): ?>
            <?php echo $__env->make('layouts.sections.menu.submenu',['menu' => $menu->submenu], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php endif; ?>
        </li>
        <?php endif; ?>
      <?php endif; ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</aside>
<?php /**PATH /home/kariem/Desktop/Projects/dash_0/resources/views/layouts/sections/menu/verticalMenu.blade.php ENDPATH**/ ?>