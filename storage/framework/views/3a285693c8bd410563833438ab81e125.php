<?php echo csrf_field(); ?>
<?php if(isset($item)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">
  <div class="col-12 col-lg-8">
    <label class="form-label fw-bold">اسم التصنيف</label>
    <input name="name" class="form-control" required
           value="<?php echo e(old('name', $item->name ?? '')); ?>">
  </div>

  <div class="col-12 col-lg-4">
    <label class="form-label fw-bold">الكود</label>
    <input name="code" class="form-control" required placeholder="IT / FURN / NET"
           value="<?php echo e(old('code', $item->code ?? '')); ?>">
  </div>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary rounded-pill fw-bold px-4">
    <i class="bi bi-save2"></i> حفظ
  </button>
  <a href="<?php echo e(route('asset-categories.index')); ?>" class="btn btn-outline-secondary rounded-pill fw-bold px-4">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\asset_categories\_form.blade.php ENDPATH**/ ?>