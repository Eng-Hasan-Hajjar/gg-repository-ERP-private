
<?php $__env->startSection('title', 'تفاصيل الطلب'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">تفاصيل الطلب #<?php echo e($assetRequest->id); ?></h4>
    <div class="text-muted small"><?php echo e($assetRequest->created_at->format('Y-m-d H:i')); ?></div>
  </div>
  <a href="<?php echo e(route('asset-requests.index')); ?>" class="btn btn-outline-secondary rounded-pill">
    <i class="bi bi-arrow-right"></i> رجوع
  </a>
</div>

<div class="card border-0 shadow-sm" style="max-width:750px;">
  <div class="card-body">

    
    <div class="mb-3">
      <?php if($assetRequest->status === 'pending'): ?>
        <span class="badge bg-warning text-dark fs-6">⏳ قيد المراجعة</span>
      <?php elseif($assetRequest->status === 'approved'): ?>
        <span class="badge bg-success fs-6">✅ مقبول</span>
      <?php else: ?>
        <span class="badge bg-danger fs-6">❌ مرفوض</span>
      <?php endif; ?>

      <?php if($assetRequest->priority === 'urgent'): ?>
        <span class="badge bg-danger me-1">🔴 عاجل</span>
      <?php elseif($assetRequest->priority === 'low'): ?>
        <span class="badge bg-secondary me-1">🔽 منخفضة</span>
      <?php else: ?>
        <span class="badge bg-secondary me-1">➖ عادية</span>
      <?php endif; ?>
    </div>

    <table class="table table-borderless">
      <tr>
        <th style="width:35%">نوع الطلب</th>
        <td>
          <?php if($assetRequest->type === 'purchase'): ?> 🛒 شراء
          <?php elseif($assetRequest->type === 'repair'): ?> 🔧 إصلاح
          <?php else: ?> 🚚 نقل
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th>العنوان</th>
        <td><?php echo e($assetRequest->title); ?></td>
      </tr>
      <tr>
        <th>مقدم الطلب</th>
        <td><?php echo e($assetRequest->user?->name); ?></td>
      </tr>

      <?php if($assetRequest->type === 'transfer'): ?>
      <tr>
        <th>من فرع</th>
        <td><?php echo e($assetRequest->fromBranch?->name ?? '—'); ?></td>
      </tr>
      <tr>
        <th>إلى فرع</th>
        <td><?php echo e($assetRequest->toBranch?->name ?? '—'); ?></td>
      </tr>
      <?php else: ?>
      <tr>
        <th>الفرع</th>
        <td><?php echo e($assetRequest->branch?->name ?? '—'); ?></td>
      </tr>
      <?php endif; ?>

      <?php if($assetRequest->asset): ?>
      <tr>
        <th>الأصل المرتبط</th>
        <td><?php echo e($assetRequest->asset->name); ?> — <code><?php echo e($assetRequest->asset->asset_tag); ?></code></td>
      </tr>
      <?php endif; ?>

      <?php if($assetRequest->description): ?>
      <tr>
        <th>التفاصيل</th>
        <td><?php echo e($assetRequest->description); ?></td>
      </tr>
      <?php endif; ?>

      <?php if($assetRequest->reviewed_by): ?>
      <tr>
        <th>راجعه</th>
        <td><?php echo e($assetRequest->reviewer?->name); ?> — <?php echo e($assetRequest->reviewed_at?->format('Y-m-d H:i')); ?></td>
      </tr>
      <?php endif; ?>

      <?php if($assetRequest->manager_notes): ?>
      <tr>
        <th>ملاحظات المدير</th>
        <td><?php echo e($assetRequest->manager_notes); ?></td>
      </tr>
      <?php endif; ?>
    </table>

  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/asset_requests/show.blade.php ENDPATH**/ ?>