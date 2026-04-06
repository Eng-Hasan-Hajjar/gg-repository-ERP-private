
<?php ($activeModule='attendance'); ?>
<?php $__env->startSection('title','تقارير الدوام'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">تقارير الدوام</h4>
    <div class="text-muted fw-semibold">ساعات/تأخير/غياب حسب فترة</div>
  </div>

  <div class="d-flex gap-2 flex-wrap">

    <?php if(auth()->user()?->hasPermission('export_attendance_reports')): ?>
    <a class="btn btn-outline-success rounded-pill px-4 fw-bold"
       href="<?php echo e(route('attendance.reports.exportExcel', request()->all())); ?>">
      <i class="bi bi-file-earmark-excel"></i> Excel
    </a>


    <a class="btn btn-outline-danger rounded-pill px-4 fw-bold"
       href="<?php echo e(route('attendance.reports.exportPdf', request()->all())); ?>">
      <i class="bi bi-file-earmark-pdf"></i> PDF
    </a>

  <?php endif; ?>
    <a href="<?php echo e(route('attendance.calendar')); ?>" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
      <i class="bi bi-calendar3"></i> التقويم
    </a>
  </div>
</div>


<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <label class="form-label fw-bold">من</label>
        <input type="date" name="from" value="<?php echo e(request('from',$from)); ?>" class="form-control">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label fw-bold">إلى</label>
        <input type="date" name="to" value="<?php echo e(request('to',$to)); ?>" class="form-control">
      </div>

      <div class="col-12 col-md-4">
        <label class="form-label fw-bold">الفرع</label>
        <select name="branch_id" class="form-select">
          <option value="">كل الفروع</option>
          <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id')==$b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <label class="form-label fw-bold d-none d-md-block">&nbsp;</label>
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الموظف</th>
          <th>الفرع</th>
          <th>أيام حضور</th>
          <th>أيام غياب</th>
          <th>أيام إجازة</th>
          <th>تأخير (د)</th>
          <th>ساعات عمل</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td class="fw-bold"><?php echo e($r->employee->full_name ?? '-'); ?></td>
            <td><?php echo e($r->employee->branch->name ?? '-'); ?></td>
            <td><span class="badge bg-success"><?php echo e((int)$r->present_days); ?></span></td>
            <td><span class="badge bg-dark"><?php echo e((int)$r->absent_days); ?></span></td>
            <td><span class="badge bg-warning text-dark"><?php echo e((int)$r->leave_days); ?></span></td>
            <td><span class="badge bg-danger"><?php echo e((int)$r->late_minutes); ?></span></td>
            <td class="fw-bold"><?php echo e(round(((int)$r->worked_minutes)/60, 2)); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/attendance/reports.blade.php ENDPATH**/ ?>