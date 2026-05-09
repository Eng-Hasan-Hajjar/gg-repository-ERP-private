
<?php $__env->startSection('title', 'طلبات اللوجستيات'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h4 class="fw-bold mb-0">طلبات اللوجستيات</h4>
    <div class="text-muted small">طلبات الشراء والإصلاح — مرتبة حسب الأولوية</div>
  </div>
  <?php if(auth()->user()?->hasPermission('submit_asset_request')): ?>
    <a href="<?php echo e(route('asset-requests.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-plus-circle"></i> تقديم طلب
    </a>
  <?php endif; ?>
</div>


<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="pending"  <?php if(request('status')=='pending'): echo 'selected'; endif; ?>>قيد المراجعة</option>
          <option value="approved" <?php if(request('status')=='approved'): echo 'selected'; endif; ?>>مقبول</option>
          <option value="rejected" <?php if(request('status')=='rejected'): echo 'selected'; endif; ?>>مرفوض</option>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="purchase" <?php if(request('type')=='purchase'): echo 'selected'; endif; ?>>شراء</option>
          <option value="repair"   <?php if(request('type')=='repair'): echo 'selected'; endif; ?>>إصلاح</option>
        </select>
      </div>
      
      <div class="col-6 col-md-3">
        <select name="priority" class="form-select">
          <option value="">الأولوية (الكل)</option>
          <option value="urgent" <?php if(request('priority')=='urgent'): echo 'selected'; endif; ?>>🔴 عاجل</option>
          <option value="normal" <?php if(request('priority')=='normal'): echo 'selected'; endif; ?>>➖ عادية</option>
          <option value="low"    <?php if(request('priority')=='low'): echo 'selected'; endif; ?>>🔽 منخفضة</option>
        </select>
      </div>
      <div class="col-6 col-md-2 d-grid">
        <button class="btn btn-namaa fw-bold">تصفية</button>
      </div>
      <?php if(request()->hasAny(['status','type','priority'])): ?>
        <div class="col-md-1 d-grid">
          <a href="<?php echo e(route('asset-requests.index')); ?>" class="btn btn-outline-secondary">مسح</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>العنوان</th>
          <th>النوع</th>
          <th>الأولوية</th>  
          <th>مقدم الطلب</th>
          <th>الفرع</th>
          <th>الأصل المرتبط</th>
          <th class="text-center">الحالة</th>
          <th>التاريخ</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr class="<?php echo e($r->priority === 'urgent' && $r->status === 'pending' ? 'table-danger' : ''); ?>">
            <td class="text-muted small"><?php echo e($r->id); ?></td>
            <td>
              <div class="fw-bold">
                <?php if($r->priority === 'urgent'): ?>
                  <i class="bi bi-exclamation-circle-fill text-danger me-1"></i>
                <?php endif; ?>
                <?php echo e($r->title); ?>

              </div>
              <?php if($r->description): ?>
                <div class="text-muted small"><?php echo e(Str::limit($r->description, 60)); ?></div>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge <?php echo e($r->type === 'purchase' ? 'bg-primary' : 'bg-warning text-dark'); ?>">
                <?php echo e($r->type_label); ?>

              </span>
            </td>

            
            <td>
              <span class="badge bg-<?php echo e($r->priority_color); ?>">
                <i class="bi <?php echo e($r->priority_icon); ?> me-1"></i>
                <?php echo e($r->priority_label); ?>

              </span>
            </td>

            <td class="small"><?php echo e($r->user->name ?? '-'); ?></td>
            <td class="small"><?php echo e($r->branch->name ?? '-'); ?></td>
            <td class="small text-muted"><?php echo e($r->asset->name ?? '—'); ?></td>
            <td class="text-center">
              <span class="badge bg-<?php echo e($r->status_color); ?>"><?php echo e($r->status_label); ?></span>
            </td>
            <td class="small text-muted"><?php echo e($r->created_at->format('Y-m-d')); ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end flex-wrap">

                <?php if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending'): ?>
                  <form method="POST" action="<?php echo e(route('asset-requests.approve', $r)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-success">
                      <i class="bi bi-check2-circle"></i> قبول
                    </button>
                  </form>

                  <button class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectModal<?php echo e($r->id); ?>">
                    <i class="bi bi-x-circle"></i> رفض
                  </button>
                <?php endif; ?>

                <?php if($r->user_id === auth()->id() && $r->status === 'pending'): ?>
                  <form method="POST" action="<?php echo e(route('asset-requests.destroy', $r)); ?>"
                        onsubmit="return confirm('حذف الطلب؟')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                <?php endif; ?>

                <?php if($r->status === 'rejected' && $r->manager_notes): ?>
                  <button class="btn btn-sm btn-outline-dark"
                    data-bs-toggle="tooltip"
                    title="<?php echo e($r->manager_notes); ?>">
                    <i class="bi bi-chat-text"></i>
                  </button>
                <?php endif; ?>

              </div>
            </td>
          </tr>

          <?php if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending'): ?>
            <div class="modal fade" id="rejectModal<?php echo e($r->id); ?>" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="POST" action="<?php echo e(route('asset-requests.reject', $r)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                      <h6 class="modal-title fw-bold">رفض الطلب: <?php echo e($r->title); ?></h6>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <label class="fw-bold mb-1">سبب الرفض (اختياري)</label>
                      <textarea name="manager_notes" class="form-control" rows="3"
                        placeholder="اكتب سبب الرفض..."></textarea>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                      <button class="btn btn-danger fw-bold">تأكيد الرفض</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="10" class="text-center text-muted py-4">
              <i class="bi bi-inbox fs-2 d-block mb-2"></i>
              لا توجد طلبات
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3"><?php echo e($requests->links()); ?></div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(function(el) { new bootstrap.Tooltip(el); });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/asset_requests/index.blade.php ENDPATH**/ ?>