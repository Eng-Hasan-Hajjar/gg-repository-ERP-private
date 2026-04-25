
<?php $__env->startSection('title', 'عرض أصل'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold"><?php echo e($asset->name); ?></h4>
      <div class="text-muted">
        التاغ: <code><?php echo e($asset->asset_tag); ?></code>
        — الحالة:
        <span class="badge <?php echo e($asset->condition_badge_class); ?>"><?php echo e($asset->condition_label); ?></span>
      </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <?php if(auth()->user()->hasPermission('edit_assets')): ?>
        <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-outline-dark rounded-pill fw-bold px-4">
          <i class="bi bi-pencil"></i> تعديل
        </a>
      <?php endif; ?>
      <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary rounded-pill fw-bold px-4">
        رجوع
      </a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3">تفاصيل الأصل</h6>

          <div class="row g-2 small">
            <div class="col-6"><b>التصنيف:</b> <?php echo e($asset->category->name ?? '-'); ?></div>
            <div class="col-6"><b>الفرع:</b> <?php echo e($asset->branch->name ?? '-'); ?></div>
            <div class="col-6"><b>السيريال:</b> <?php echo e($asset->serial_number ?? '-'); ?></div>
            <div class="col-6"><b>الموقع:</b> <?php echo e($asset->location ?? '-'); ?></div>
            <div class="col-6"><b>تاريخ الشراء:</b> <?php echo e($asset->purchase_date?->format('Y-m-d') ?? '-'); ?></div>
            <div class="col-6"><b>التكلفة:</b> <?php echo e($asset->purchase_cost ?? '-'); ?> <?php echo e($asset->currency); ?></div>
            <div class="col-12"><b>الوصف:</b> <?php echo e($asset->description ?? '-'); ?></div>
            
            <div class="col-6"><b>العدد الإجمالي:</b>
              <span class="badge bg-primary"><?php echo e($asset->quantity ?? 1); ?></span> قطعة
            </div>

            <div class="col-12"><b>الوصف:</b> <?php echo e($asset->description ?? '-'); ?></div>


          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="fw-bold mb-3">صورة الأصل</h6>

          <?php if($asset->photo_path): ?>
            <img src="<?php echo e(asset('storage/' . $asset->photo_path)); ?>" class="w-100 border"
              style="border-radius:16px;object-fit:cover;max-height:320px">
          <?php else: ?>
            <div class="text-muted">لا يوجد صورة.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/assets/show.blade.php ENDPATH**/ ?>