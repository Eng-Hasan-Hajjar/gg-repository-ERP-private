
<?php ($activeModule = 'assets'); ?>
<?php $__env->startSection('title', 'التقرير المالي للأصول'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-0">التقرير المالي للأصول</h4>
      <div class="text-muted small">إجمالي قيم الأصول حسب العملة والفرع</div>
    </div>
    <a href="<?php echo e(route('assets.report.export', request()->query())); ?>" class="btn btn-success fw-bold rounded-pill px-4">
      <i class="bi bi-file-earmark-excel"></i> تصدير Excel
    </a>
  </div>

  
  <form class="card border-0 shadow-sm mb-3" method="GET">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-6 col-md-3">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>>
                <?php echo e($b->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <select name="currency" class="form-select">
            <option value="">كل العملات</option>
            <?php $__currentLoopData = ['USD', 'EUR', 'TRY', 'GBP', 'SAR', 'AED']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c); ?>" <?php if(request('currency') == $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          
          <select name="condition" class="form-select">
            <option value="">كل الحالات</option>
            <option value="good" <?php if(request('condition') == 'good'): echo 'selected'; endif; ?>>جيد</option>
            <option value="maintenance" <?php if(request('condition') == 'maintenance'): echo 'selected'; endif; ?>>صيانة</option>
            <option value="retired" <?php if(request('condition') == 'retired'): echo 'selected'; endif; ?>>خارج الخدمة</option>
          </select>
        </div>
        <div class="col-6 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">تصفية</button>
        </div>
        <?php if(request()->hasAny(['branch_id', 'currency', 'condition'])): ?>
          <div class="col-md-1 d-grid">
            <a href="<?php echo e(route('assets.report')); ?>" class="btn btn-outline-secondary">مسح</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </form>

  
  <div class="row g-3 mb-4">
    <?php $__empty_1 = true; $__currentLoopData = $totalByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3 px-2"
          style="border-top:4px solid #10b981 !important; border-radius:14px;">
          <div style="font-size:1.5rem; font-weight:900; color:#10b981;">
            <?php echo e(number_format($t->total, 2)); ?>

          </div>
          <div style="font-size:1rem; font-weight:900; color:#334155;">
            <?php echo e($t->currency); ?>

          </div>
          <div style="font-size:.75rem; color:#94a3b8;">
            <?php echo e($t->count); ?> أصل
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-12 text-center text-muted py-3">لا توجد بيانات مالية</div>
    <?php endif; ?>
  </div>

  
  <?php if($byBranchAndCurrency->count()): ?>
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white fw-bold border-0 pt-3">
        <i class="bi bi-building text-primary"></i> الإجمالي حسب الفرع والعملة
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>الفرع</th>
              <th>العملة</th>
              <th>عدد الأصول</th>
              <th>الإجمالي</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $byBranchAndCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branchId => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <?php if($loop->first): ?>
                    <td rowspan="<?php echo e($rows->count()); ?>" class="fw-bold align-middle">
                      <?php echo e($row->branch->name ?? 'غير محدد'); ?>

                    </td>
                  <?php endif; ?>
                  <td><span class="badge bg-primary"><?php echo e($row->currency); ?></span></td>
                  <td><?php echo e($row->count); ?></td>
                  <td class="fw-bold text-success">
                    <?php echo e(number_format($row->total, 2)); ?> <?php echo e($row->currency); ?>

                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

  
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold border-0 pt-3 d-flex justify-content-between">
      <span><i class="bi bi-box-seam text-warning"></i> تفاصيل الأصول</span>
      <span class="badge bg-secondary"><?php echo e($assets->count()); ?> أصل</span>
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>الأصل</th>
            <th>الفرع</th>
            <th>التصنيف</th>
            <th>الحالة</th>
            <th>الكمية</th>
            <th>سعر الشراء</th>
            <th>تاريخ الشراء</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="text-muted small"><?php echo e($i + 1); ?></td>
              <td>
                <div class="fw-bold"><?php echo e($a->name); ?></div>
                <?php if($a->asset_tag): ?>
                  <code class="small"><?php echo e($a->asset_tag); ?></code>
                <?php endif; ?>
              </td>
              <td class="small"><?php echo e($a->branch->name ?? '-'); ?></td>
              <td class="small"><?php echo e($a->category->name ?? '-'); ?></td>
              <td>
                
                <span class="badge <?php echo e($a->condition_badge_class); ?>">
                  <?php echo e($a->condition_label); ?>

                </span>
              </td>
              <td class="text-center"><?php echo e($a->quantity ?? 1); ?></td>
              <td class="fw-bold text-success">
                <?php echo e(number_format($a->purchase_cost, 2)); ?> 
                <span class="text-muted small"><?php echo e($a->currency ?? 'USD'); ?></span>
              </td>
              <td class="small text-muted">
                <?php echo e($a->purchase_date?->format('Y-m-d') ?? '-'); ?>

              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                لا توجد أصول بهذه المعايير
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/assets/report.blade.php ENDPATH**/ ?>