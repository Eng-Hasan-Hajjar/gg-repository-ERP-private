
<?php ($activeModule = 'attendance'); ?>
<?php $__env->startSection('title', 'تقارير الدوام'); ?>

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

  
  <div class="d-flex gap-2 mb-3 flex-wrap">
    <a href="<?php echo e(route('attendance.reports', array_merge(request()->except('period'), ['period' => 'daily']))); ?>"
      class="btn btn-sm <?php echo e(request('period') == 'daily' ? 'btn-primary' : 'btn-outline-secondary'); ?>">
      <i class="bi bi-calendar-day"></i> اليوم
    </a>
    <a href="<?php echo e(route('attendance.reports', array_merge(request()->except('period'), ['period' => 'weekly']))); ?>"
      class="btn btn-sm <?php echo e(request('period') == 'weekly' ? 'btn-primary' : 'btn-outline-secondary'); ?>">
      <i class="bi bi-calendar-week"></i> هذا الأسبوع
    </a>
    <a href="<?php echo e(route('attendance.reports', array_merge(request()->except('period'), ['period' => 'monthly']))); ?>"
      class="btn btn-sm <?php echo e(request('period') == 'monthly' ? 'btn-primary' : 'btn-outline-secondary'); ?>">
      <i class="bi bi-calendar-month"></i> هذا الشهر
    </a>
  </div>

  
  <form class="card border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('attendance.reports')); ?>">
    <div class="card-body">
      <div class="row g-2">

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">من</label>
          <input type="date" name="from" value="<?php echo e(request('from', $from)); ?>" class="form-control">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">إلى</label>
          <input type="date" name="to" value="<?php echo e(request('to', $to)); ?>" class="form-control">
        </div>

        <div class="col-6 col-md-2">
          <label class="form-label fw-bold">الفرع</label>
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label fw-bold">الموظف</label>
          <select name="employee_id" class="form-select">
            <option value="">كل الموظفين</option>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($e->id); ?>" <?php if(request('employee_id') == $e->id): echo 'selected'; endif; ?>><?php echo e($e->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-12 col-md-3 d-grid align-self-end">
          <button class="btn btn-namaa fw-bold">
            <i class="bi bi-funnel"></i> تطبيق
          </button>
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
            <th class="hide-mobile">الفرع</th>           
            <th>أيام حضور</th>                           
            <th>أيام غياب</th>                           
            <th class="hide-mobile">أيام إجازة</th>      
            <th class="hide-mobile">تأخير (د)</th>       
            <th class="hide-mobile">استراحة (د)</th>     
            <th class="hide-mobile">ساعات عمل</th>       
            <th>صافي ساعات</th>                          
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="fw-bold"><?php echo e($r->employee->full_name ?? '-'); ?></td>
              <td class="hide-mobile"><?php echo e($r->employee->branch->name ?? '-'); ?></td>
              <td><span class="badge bg-success"><?php echo e((int) $r->present_days); ?></span></td>
              <td><span class="badge bg-dark"><?php echo e((int) $r->absent_days); ?></span></td>
              <td class="hide-mobile"><span class="badge bg-warning text-dark"><?php echo e((int) $r->leave_days); ?></span></td>
              <td class="hide-mobile"><span class="badge bg-danger"><?php echo e((int) $r->late_minutes); ?></span></td>
              <td class="hide-mobile"><span class="badge bg-secondary"><?php echo e((int) $r->break_minutes); ?></span></td>
              <td class="hide-mobile fw-bold"><?php echo e(round($r->worked_minutes / 60, 2)); ?></td>
              <td>
                <span class="badge bg-info text-dark">
                  <?php echo e(round(max(0, $r->worked_minutes - $r->break_minutes) / 60, 2)); ?>

                </span>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">لا يوجد بيانات</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/attendance/reports.blade.php ENDPATH**/ ?>