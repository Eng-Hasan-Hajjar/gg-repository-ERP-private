
<?php ($activeModule = 'assets'); ?>
<?php $__env->startSection('title', 'اللوجستيات وإدارة الأصول'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
    <div>
      <h4 class="mb-1 fw-bold">
        <i class="bi bi-box-seam text-warning me-2"></i>اللوجستيات وإدارة الأصول
      </h4>
      <div class="text-muted small">إدارة الأجهزة والمعدات — تتبع الحالة والموقع والتكلفة</div>
    </div>

    <div class="d-flex gap-2 flex-wrap">

      <a href="<?php echo e(route('asset-categories.index')); ?>" class="btn btn-outline-secondary rounded-pill fw-bold px-3">
        <i class="bi bi-tags"></i>
        <span class="hide-mobile">تصنيفات</span>
      </a>

      <?php if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasRole('super_admin')): ?>
        <a href="<?php echo e(route('assets.report')); ?>" class="btn btn-outline-primary rounded-pill fw-bold px-3">
          <i class="bi bi-bar-chart-line"></i>
          <span class="hide-mobile">التقرير المالي</span>
        </a>
      <?php endif; ?>

      
     <?php if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasRole('super_admin')): ?>
        <a href="<?php echo e(route('asset-requests.index')); ?>"
          class="btn btn-outline-warning rounded-pill fw-bold px-3 position-relative">
          <i class="bi bi-inbox"></i>
          <span class="hide-mobile">الطلبات</span>
          <?php if($pendingCount > 0): ?>
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
              style="font-size:10px;"><?php echo e($pendingCount); ?></span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <?php if(auth()->user()?->hasPermission('submit_asset_request')): ?>
        <a href="<?php echo e(route('asset-requests.create')); ?>" class="btn btn-warning rounded-pill fw-bold px-3">
          <i class="bi bi-send-plus"></i>
          <span class="hide-mobile">تقديم طلب</span>
        </a>
      <?php endif; ?>

      <a href="<?php echo e(route('assets.export.excel') . '?' . http_build_query(request()->all())); ?>"
        class="btn btn-success rounded-pill fw-bold px-3">
        <i class="bi bi-file-earmark-excel"></i>
        <span class="hide-mobile">Excel</span>
      </a>

      <?php if(auth()->user()?->hasPermission('create_assets')): ?>
        <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary rounded-pill fw-bold px-4">
          <i class="bi bi-plus-lg"></i> أصل جديد
        </a>
      <?php endif; ?>

    </div>
  </div>

  
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #3b82f6 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">إجمالي القطع</div>
              <div style="font-size:1.8rem; font-weight:900; color:#3b82f6; line-height:1;">
                <?php echo e($assets->sum('quantity')); ?>

              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(59,130,246,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-layers-fill" style="font-size:1.2rem;color:#3b82f6;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #10b981 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">أنواع الأصول</div>
              <div style="font-size:1.8rem; font-weight:900; color:#10b981; line-height:1;">
                <?php echo e($assets->count()); ?>

              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(16,185,129,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-box-seam-fill" style="font-size:1.2rem;color:#10b981;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #f59e0b !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">في الصيانة</div>
              <div style="font-size:1.8rem; font-weight:900; color:#f59e0b; line-height:1;">
                <?php echo e($assets->where('condition', 'maintenance')->count()); ?>

              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(245,158,11,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-wrench-adjustable" style="font-size:1.2rem;color:#f59e0b;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #ef4444 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">خارج الخدمة</div>
              <div style="font-size:1.8rem; font-weight:900; color:#ef4444; line-height:1;">
                <?php echo e($assets->where('condition', 'out_of_service')->count()); ?>

              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(239,68,68,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-x-circle-fill" style="font-size:1.2rem;color:#ef4444;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <form class="card border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('assets.index')); ?>">
    <div class="card-body py-2">
      <div class="row g-2 align-items-center">

        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input name="search" value="<?php echo e(request('search')); ?>" class="form-control border-start-0"
              placeholder="بحث: الاسم / AST / سيريال">
          </div>
        </div>

        <div class="col-6 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="asset_category_id" class="form-select">
            <option value="">كل التصنيفات</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c->id); ?>" <?php if(request('asset_category_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="condition" class="form-select">
            <option value="">كل الحالات</option>
            <option value="good" <?php if(request('condition') == 'good'): echo 'selected'; endif; ?>>✅ جيد</option>
            <option value="maintenance" <?php if(request('condition') == 'maintenance'): echo 'selected'; endif; ?>>🔧 صيانة</option>
            <option value="out_of_service" <?php if(request('condition') == 'out_of_service'): echo 'selected'; endif; ?>>❌ خارج الخدمة</option>
          </select>
        </div>

        <div class="col-6 col-md-auto d-flex gap-1">
          <button class="btn btn-namaa fw-bold px-3">
            <i class="bi bi-funnel"></i>
            <span class="hide-mobile">تصفية</span>
          </button>
          <?php if(request()->hasAny(['search', 'branch_id', 'asset_category_id', 'condition'])): ?>
            <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary px-3" title="مسح الفلاتر">
              <i class="bi bi-x-lg"></i>
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </form>

  
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile" style="width:50px;">#</th>
            <th class="hide-mobile" style="width:130px;">التاغ</th>
            <th>الأصل</th>
            <th class="hide-mobile">التصنيف</th>
            <th class="hide-mobile">الفرع</th>
            <th style="width:90px;">الحالة</th>
            <th class="hide-mobile" style="width:70px;">العدد</th>
            <th class="hide-mobile">الموقع</th>
            <th class="hide-mobile">السعر</th>
            <th class="text-end" style="width:130px;">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr
              class="<?php echo e($a->condition === 'out_of_service' ? 'table-danger' : ($a->condition === 'maintenance' ? 'table-warning' : '')); ?>">

              <td class="hide-mobile text-muted small"><?php echo e($a->id); ?></td>

              <td class="hide-mobile">
                <code style="font-size:.78rem; color:#6366f1;"><?php echo e($a->asset_tag); ?></code>
              </td>

              <td>
                <div class="fw-bold"><?php echo e($a->name); ?></div>
                <div class="d-block d-md-none text-muted small mt-1">
                  <?php echo e($a->branch->name ?? ''); ?>

                  <?php if($a->purchase_cost): ?>
                    · <span class="text-success fw-bold">
                      <?php echo e(number_format($a->purchase_cost, 0)); ?> <?php echo e($a->currency ?? 'USD'); ?>

                    </span>
                  <?php endif; ?>
                </div>
              </td>

              <td class="hide-mobile small text-muted"><?php echo e($a->category->name ?? '-'); ?></td>

              <td class="hide-mobile small"><?php echo e($a->branch->name ?? '-'); ?></td>

              <td>
                <span class="badge <?php echo e($a->condition_badge_class); ?>" style="font-size:.72rem;">
                  <?php echo e($a->condition_label); ?>

                </span>
              </td>

              <td class="hide-mobile text-center">
                <span class="badge bg-light text-dark border" style="font-size:.8rem; font-weight:900;">
                  <?php echo e($a->quantity ?? 1); ?>

                </span>
              </td>

              <td class="hide-mobile small text-muted"><?php echo e($a->location ?? '-'); ?></td>

              <td class="hide-mobile">
                <?php if($a->purchase_cost): ?>
                  <span class="fw-bold text-success" style="font-size:.85rem;">
                    <?php echo e(number_format($a->purchase_cost, 2)); ?>

                  </span>
                  <span class="text-muted small"> <?php echo e($a->currency ?? 'USD'); ?></span>
                <?php else: ?>
                  <span class="text-muted small">-</span>
                <?php endif; ?>
              </td>

              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end">

                  <?php if(auth()->user()?->hasPermission('view_assets')): ?>
                    <a href="<?php echo e(route('assets.show', $a)); ?>" class="btn btn-sm btn-outline-primary" title="عرض">
                      <i class="bi bi-eye"></i>
                    </a>
                  <?php endif; ?>

                  <?php if(auth()->user()?->hasPermission('edit_assets')): ?>
                    <a href="<?php echo e(route('assets.edit', $a)); ?>" class="btn btn-sm btn-outline-dark hide-mobile" title="تعديل">
                      <i class="bi bi-pencil"></i>
                    </a>
                  <?php endif; ?>

                  <?php if(auth()->user()?->hasPermission('delete_assets')): ?>
                    <form method="POST" action="<?php echo e(route('assets.destroy', $a)); ?>" class="d-inline hide-mobile"
                      onsubmit="return confirm('تأكيد حذف الأصل؟')">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button class="btn btn-sm btn-outline-danger" title="حذف">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="10" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                لا يوجد أصول مطابقة للفلتر الحالي
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($assets->count() > 0): ?>
      <div class="card-footer bg-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          عرض <?php echo e($assets->firstItem() ?? 0); ?>–<?php echo e($assets->lastItem() ?? 0); ?>

          من أصل <?php echo e($assets->total()); ?> سجل
        </div>
        <div class="text-muted small hide-mobile">
          إجمالي القطع: <strong><?php echo e($assets->sum('quantity')); ?></strong>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="mt-3">
    <?php echo e($assets->links()); ?>

  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/assets/index.blade.php ENDPATH**/ ?>