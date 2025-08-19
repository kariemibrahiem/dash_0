

<?php $__env->startSection('title', trns('Create Admin')); ?>

<?php $__env->startSection('content'); ?>
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">
    <a href="<?php echo e(route('users.index')); ?>"><?php echo e(trns('Users')); ?></a> /
  </span> <?php echo e(trns('Create Admin')); ?>

</h4>

<div class="card">
  <div class="card-body">
    <form action="<?php echo e(route('users.store')); ?>" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="row m-3">

        <!-- Username -->
        <div class="col-5">
          <label class="form-label" for="name"><?php echo e(trns('username')); ?></label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
        </div>

        <!-- Email -->
        <div class="col-5">
          <label class="form-label" for="email"><?php echo e(trns('email')); ?></label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
        </div>

        <!-- Password -->
        <div class="col-5">
          <label class="form-label" for="password"><?php echo e(trns('password')); ?></label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Image -->
        <div class="col-5">
          <label class="form-label" for="image"><?php echo e(trns('image')); ?></label>
          <input class="form-control" type="file" id="image" name="image">
        </div>

      </div>

      <!-- Submit & Cancel Buttons -->
      <button type="submit" class="btn btn-primary"><?php echo e(trns('create')); ?></button>
      <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary"><?php echo e(trns('cancel')); ?></a>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/contentNavbarLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\dash_0\resources\views/content/users/patials/create.blade.php ENDPATH**/ ?>