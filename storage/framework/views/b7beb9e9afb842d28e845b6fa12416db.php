
<?php ($activeModule = 'finance'); ?>
<?php $__env->startSection('title', 'كشف الحسابات الشامل'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="bi bi-receipt-cutoff"></i> كشف الحسابات الشامل</h4>
    <div class="text-muted fw-semibold small">جميع الحركات المالية في جميع الصناديق</div>
  </div>
  <a href="<?php echo e(route('accounts.statement.excel')); ?>?<?php echo e(http_build_query(request()->all())); ?>"
     class="btn btn-success rounded-pill px-4 fw-bold">
    <i class="bi bi-file-earmark-excel"></i> تصدير Excel
  </a>
</div>


<div class="row g-3 mb-3">
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #1b5e20 !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-arrow-down-circle-fill text-success fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">إجمالي المقبوض (posted)</div>
          <div class="fw-bold fs-5 text-success"><?php echo e(number_format($summaryIn, 2)); ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #b71c1c !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-arrow-up-circle-fill text-danger fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">إجمالي المدفوع (posted)</div>
          <div class="fw-bold fs-5 text-danger"><?php echo e(number_format($summaryOut, 2)); ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #b45309 !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-calculator-fill fs-4" style="color:#b45309;"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">الصافي</div>
          <div class="fw-bold fs-5 <?php echo e($summaryNet >= 0 ? 'text-success' : 'text-danger'); ?>">
            <?php echo e(number_format($summaryNet, 2)); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">

      <div class="col-12 col-md-3">
        <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
               placeholder="بحث: اسم / مرجع / تصنيف / ملاحظات">
      </div>

      <div class="col-6 col-md-2">
        <select name="cashbox_id" class="form-select">
          <option value="">كل الصناديق</option>
          <?php $__currentLoopData = $cashboxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cb->id); ?>" <?php if(request('cashbox_id') == $cb->id): echo 'selected'; endif; ?>>
              <?php echo e($cb->name); ?> (<?php echo e($cb->currency); ?>)
            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="in"       <?php if(request('type')=='in'): echo 'selected'; endif; ?>>مقبوض</option>
          <option value="out"      <?php if(request('type')=='out'): echo 'selected'; endif; ?>>مدفوع</option>
          <option value="transfer" <?php if(request('type')=='transfer'): echo 'selected'; endif; ?>>مناقلة</option>
          <option value="exchange" <?php if(request('type')=='exchange'): echo 'selected'; endif; ?>>تصريف</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="posted" <?php if(request('status')=='posted'): echo 'selected'; endif; ?>>مُرحّل</option>
          <option value="draft"  <?php if(request('status')=='draft'): echo 'selected'; endif; ?>>معلّق</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="category" class="form-select">
          <option value="">التصنيف (الكل)</option>
          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('category') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="currency" class="form-select">
          <option value="">كل العملات</option>
          <?php $__currentLoopData = ['USD','EUR','TRY','GBP','SAR','AED']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cur); ?>" <?php if(request('currency') == $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
               class="form-control" placeholder="من تاريخ">
      </div>

      <div class="col-6 col-md-2">
        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
               class="form-control" placeholder="إلى تاريخ">
      </div>

      <div class="col-6 col-md-2">
        <input type="number" name="amount_min" value="<?php echo e(request('amount_min')); ?>"
               class="form-control" placeholder="المبلغ من">
      </div>

      <div class="col-6 col-md-2">
        <input type="number" name="amount_max" value="<?php echo e(request('amount_max')); ?>"
               class="form-control" placeholder="المبلغ إلى">
      </div>

      <div class="col-6 col-md-2">
        <select name="sort" class="form-select">
          <option value="trx_date" <?php if(request('sort','trx_date')=='trx_date'): echo 'selected'; endif; ?>>التاريخ</option>
          <option value="amount"   <?php if(request('sort')=='amount'): echo 'selected'; endif; ?>>المبلغ</option>
          <option value="id"       <?php if(request('sort')=='id'): echo 'selected'; endif; ?>>رقم الحركة</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="direction" class="form-select">
          <option value="desc" <?php if(request('direction','desc')=='desc'): echo 'selected'; endif; ?>>تنازلي</option>
          <option value="asc"  <?php if(request('direction')=='asc'): echo 'selected'; endif; ?>>تصاعدي</option>
        </select>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-namaa fw-bold">تطبيق</button>
      </div>

      <?php if(request()->hasAny(['search','cashbox_id','type','status','category','currency','date_from','date_to','amount_min','amount_max'])): ?>
        <div class="col-12 col-md-2 d-grid">
          <a href="<?php echo e(route('accounts.statement.index')); ?>" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-x-circle"></i> مسح الفلاتر
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</form>


<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0" style="font-size:13px;">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>التاريخ</th>
          <th>الصندوق</th>
          <th>الفرع</th>
          <th>النوع</th>
          <th>الشخص</th>
          <th>الدبلومة</th>
          <th>التصنيف</th>
          <th class="text-center">المبلغ</th>
          <th>العملة</th>
          <th>مرجع</th>
          <th class="text-center">الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="text-muted small"><?php echo e($t->id); ?></td>
            <td class="small"><?php echo e($t->trx_date->format('Y-m-d')); ?></td>
            <td class="small fw-semibold"><?php echo e(optional($t->cashbox)->name ?? '-'); ?></td>
            <td class="small text-muted"><?php echo e(optional(optional($t->cashbox)->branch)->name ?? '-'); ?></td>
            <td>
              <span class="badge bg-<?php echo e($typeMeta[$t->type]['color'] ?? 'secondary'); ?>">
                <?php echo e($typeMeta[$t->type]['label'] ?? $t->type); ?>

              </span>
            </td>
            <td class="small"><?php echo e(optional(optional($t->account)->accountable)->full_name ?? '-'); ?></td>
            <td class="small text-muted"><?php echo e(optional($t->diploma)->name ?? '-'); ?></td>
            <td class="small text-muted"><?php echo e($t->category ?? '-'); ?></td>
            <td class="text-center fw-bold <?php echo e(in_array($t->type,['in']) ? 'text-success' : 'text-danger'); ?>">
              <?php echo e(number_format($t->amount, 2)); ?>

              <?php if($t->foreign_amount && $t->foreign_currency): ?>
                <br><small class="text-muted fw-normal">
                  (<?php echo e(number_format($t->foreign_amount,2)); ?> <?php echo e($t->foreign_currency); ?>)
                </small>
              <?php endif; ?>
            </td>
            <td><span class="badge bg-light text-dark border small"><?php echo e($t->currency); ?></span></td>
            <td class="small text-muted"><?php echo e($t->reference ?? '-'); ?></td>
            <td class="text-center">
              <span class="badge bg-<?php echo e($t->status === 'posted' ? 'primary' : 'secondary'); ?>">
                <?php echo e($t->status === 'posted' ? 'مُرحّل' : 'معلّق'); ?>

              </span>
            </td>
            <td class="text-end">
              <a href="<?php echo e(route('cashboxes.transactions.index', $t->cashbox_id)); ?>"
                 class="btn btn-sm btn-outline-secondary rounded-pill px-2"
                 title="عرض في الصندوق">
                <i class="bi bi-box-arrow-up-left"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="13" class="text-center text-muted py-4">لا توجد حركات بالفلاتر المحددة</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($transactions->links()); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/accounts/statement/index.blade.php ENDPATH**/ ?>