<?php echo csrf_field(); ?>

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم البرمجي (Unique)</label>
        <input name="name" class="form-control"
               value="<?php echo e(old('name',$role->name ?? '')); ?>"
               <?php if(isset($role)): ?> disabled <?php endif; ?>
               required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم المعروض</label>
        <input name="label" class="form-control"
               value="<?php echo e(old('label',$role->label ?? '')); ?>" required>
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">الوصف</label>
        <textarea name="description" class="form-control" rows="2"><?php echo e(old('description',$role->description ?? '')); ?></textarea>
      </div>

    </div>
  </div>
</div>

<h5 class="fw-bold mb-2">الصلاحيات حسب الوحدة</h5>

<?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card border-0 shadow-sm mb-3">
  <div class="card-header bg-light fw-bold">
  
    
    <?php echo e(t($module)); ?>




  </div>
  <div class="card-body">
    <div class="row">
      <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input"
                 type="checkbox"
                 name="permissions[]"
                 value="<?php echo e($p->id); ?>"
                 <?php if(in_array($p->id,$rolePermissions ?? [])): echo 'checked'; endif; ?>>
          <label class="form-check-label">
            <?php echo e($p->label); ?>

          </label>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger">
  <ul class="mb-0">
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li><?php echo e($e); ?></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php endif; ?>

<div class="d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a href="<?php echo e(route('admin.roles.index')); ?>"
     class="btn btn-outline-secondary fw-bold px-4">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/roles/_form.blade.php ENDPATH**/ ?>