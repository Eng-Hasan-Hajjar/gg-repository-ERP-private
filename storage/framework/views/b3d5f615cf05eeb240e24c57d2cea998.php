<?php echo csrf_field(); ?>
<?php if(isset($asset)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">اسم الأصل</label>
    <input name="name" class="form-control" required
           value="<?php echo e(old('name', $asset->name ?? '')); ?>">
  </div>

  <div class="col-12 col-lg-3">
    <label class="form-label fw-bold">التصنيف</label>
    <select name="asset_category_id" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c->id); ?>" <?php if(old('asset_category_id', $asset->asset_category_id ?? '')==$c->id): echo 'selected'; endif; ?>>
          <?php echo e($c->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12 col-lg-3">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id', $asset->branch_id ?? '')==$b->id): echo 'selected'; endif; ?>>
          <?php echo e($b->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الوصف</label>
    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $asset->description ?? '')); ?></textarea>
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">الحالة</label>
    <select name="condition" class="form-select" required>
      <option value="good" <?php if(old('condition', $asset->condition ?? 'good')=='good'): echo 'selected'; endif; ?>>جيد</option>
      <option value="maintenance" <?php if(old('condition', $asset->condition ?? '')=='maintenance'): echo 'selected'; endif; ?>>صيانة</option>
      <option value="out_of_service" <?php if(old('condition', $asset->condition ?? '')=='out_of_service'): echo 'selected'; endif; ?>>خارج الخدمة</option>
    </select>
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">تاريخ الشراء</label>
    <input type="date" name="purchase_date" class="form-control"
           value="<?php echo e(old('purchase_date', optional($asset->purchase_date ?? null)->format('Y-m-d'))); ?>">
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">تكلفة الشراء</label>
    <input type="number" step="0.01" name="purchase_cost" class="form-control"
           value="<?php echo e(old('purchase_cost', $asset->purchase_cost ?? '')); ?>">
  </div>

  <div class="col-6 col-lg-3">
    <label class="form-label fw-bold">العملة</label>
    <input name="currency" class="form-control" maxlength="3" placeholder="USD"
           value="<?php echo e(old('currency', $asset->currency ?? 'USD')); ?>">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">Serial / رقم السيريال</label>
    <input name="serial_number" class="form-control"
           value="<?php echo e(old('serial_number', $asset->serial_number ?? '')); ?>">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label fw-bold">الموقع داخل الفرع</label>
    <input name="location" class="form-control" placeholder="غرفة / مخزن / قاعة"
           value="<?php echo e(old('location', $asset->location ?? '')); ?>">
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">صورة الأصل (اختياري)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">

    <?php if(isset($asset) && $asset->photo_path): ?>
      <div class="mt-2">
        <img src="<?php echo e(asset('storage/'.$asset->photo_path)); ?>" style="max-height:120px;border-radius:12px" class="border">
      </div>
    <?php endif; ?>
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
  <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary rounded-pill fw-bold px-4">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/assets/_form.blade.php ENDPATH**/ ?>