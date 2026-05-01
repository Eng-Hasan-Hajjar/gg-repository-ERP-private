
<?php ($activeModule = 'finance'); ?>
<?php $__env->startSection('title', 'الذمم المالية للطلاب'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="bi bi-wallet2"></i> الذمم المالية للطلاب</h4>
    <div class="text-muted fw-semibold small">إجمالي المستحق — المدفوع — المتبقي لجميع الطلاب</div>
  </div>
  <a href="<?php echo e(route('debts.excel')); ?>?<?php echo e(http_build_query(request()->all())); ?>"
     class="btn btn-success rounded-pill px-4 fw-bold">
    <i class="bi bi-file-earmark-excel"></i> تصدير Excel
  </a>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #11998e !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي المستحق</div>
        <div class="fw-bold fs-4"><?php echo e(number_format($totalAmount, 2)); ?></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #1976d2 !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي المدفوع</div>
        <div class="fw-bold fs-4 text-primary"><?php echo e(number_format($totalPaid, 2)); ?></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #c62828 !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي الذمم المتبقية</div>
        <div class="fw-bold fs-4 text-danger"><?php echo e(number_format($totalDebt, 2)); ?></div>
      </div>
    </div>
  </div>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
               placeholder="بحث: اسم / هاتف / رقم جامعي">
      </div>
      <div class="col-6 col-md-3">
        <select name="debt_status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="has_debt" <?php if(request('debt_status')=='has_debt'): echo 'selected'; endif; ?>>عليه ذمة</option>
          <option value="paid"     <?php if(request('debt_status')=='paid'): echo 'selected'; endif; ?>>مسدّد</option>
          <option value="overpaid" <?php if(request('debt_status')=='overpaid'): echo 'selected'; endif; ?>>زيادة دفع</option>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <select name="sort" class="form-select">
          <option value="remaining" <?php if(request('sort','remaining')=='remaining'): echo 'selected'; endif; ?>>الأعلى ذمةً</option>
          <option value="name"      <?php if(request('sort')=='name'): echo 'selected'; endif; ?>>الاسم</option>
          <option value="total"     <?php if(request('sort')=='total'): echo 'selected'; endif; ?>>الإجمالي</option>
          <option value="paid"      <?php if(request('sort')=='paid'): echo 'selected'; endif; ?>>المدفوع</option>
        </select>
      </div>
      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الرقم الجامعي</th>
          <th>اسم الطالب</th>
          <th>الهاتف</th>
          <th class="text-center">الدبلومات</th>
          <th class="text-center">إجمالي المستحق</th>
          <th class="text-center">المدفوع</th>
          <th class="text-center">المتبقي</th>
          <th class="text-center">الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $paginated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="text-muted small"><?php echo e($paginated->firstItem() + $idx); ?></td>
            <td><code class="small"><?php echo e($d['university_id']); ?></code></td>
            <td class="fw-semibold"><?php echo e($d['name']); ?></td>
            <td class="small text-muted"><?php echo e($d['phone']); ?></td>
            <td class="text-center">
              <span class="badge bg-secondary rounded-pill"><?php echo e($d['diplomas_count']); ?></span>
            </td>
            <td class="text-center fw-bold"><?php echo e(number_format($d['total'], 2)); ?></td>
            <td class="text-center fw-bold text-primary"><?php echo e(number_format($d['paid'], 2)); ?></td>
            <td class="text-center fw-bold text-<?php echo e($d['rem_class']); ?>">
              <?php echo e(number_format(abs($d['remaining']), 2)); ?>

              <?php if($d['remaining'] < 0): ?>
                <small class="d-block fw-normal text-muted">(زيادة)</small>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <span class="badge bg-<?php echo e($d['status_class']); ?> rounded-pill px-3">
                <?php echo e($d['status_label']); ?>

              </span>
            </td>
            <td class="text-end">
              <a href="<?php echo e(route('debts.show', $d['student_id'])); ?>"
                 class="btn btn-sm btn-outline-info rounded-pill px-3">
                <i class="bi bi-eye"></i> تفاصيل
              </a>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($paginated->links()); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/debts/index.blade.php ENDPATH**/ ?>