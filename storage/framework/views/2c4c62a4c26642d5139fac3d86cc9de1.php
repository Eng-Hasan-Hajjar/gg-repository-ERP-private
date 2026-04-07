
<?php ($activeModule='users'); ?>

<?php $__env->startSection('title','إسناد مستخدمين للدور'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <h4 class="fw-bold">إسناد مستخدمين للدور: <?php echo e($role->label); ?></h4>

    <form method="POST" action="<?php echo e(route('admin.roles.attachUser',$role)); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-2">
        <div class="col-md-8">
          <select name="user_id" class="form-select">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($u->id); ?>">
              <?php echo e($u->name); ?> — <?php echo e($u->email); ?>

            </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-md-4 d-grid">
          <button class="btn btn-primary">إسناد المستخدم</button>
        </div>
      </div>
    </form>

    <hr>

    <h5 class="fw-bold">المستخدمون الحاليون في هذا الدور</h5>

    <ul class="list-group">
      <?php $__currentLoopData = $role->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li class="list-group-item d-flex justify-content-between">
        <span><?php echo e($u->name); ?></span>
        <span class="badge bg-secondary"><?php echo e($u->email); ?></span>
      </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/roles/users.blade.php ENDPATH**/ ?>