
<?php $__env->startSection('title', 'تقارير المهام'); ?>

<?php $__env->startSection('content'); ?>

<style>
  .report-type-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 800; border: 1px solid;
  }
  .type-daily   { background: rgba(14,165,233,.1);  color: #0369a1; border-color: rgba(14,165,233,.25); }
  .type-weekly  { background: rgba(99,102,241,.1);  color: #4338ca; border-color: rgba(99,102,241,.25); }
  .type-monthly { background: rgba(16,185,129,.1);  color: #047857; border-color: rgba(16,185,129,.25); }

  .quick-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 20px; font-size: 13px;
    font-weight: 800; border: 1px solid rgba(226,232,240,.9);
    background: rgba(255,255,255,.85); color: #1e293b;
    text-decoration: none; transition: .15s ease;
  }
  .quick-btn:hover, .quick-btn.active {
    background: #0ea5e9; color: #fff; border-color: #0ea5e9;
  }
  .quick-btn.active { pointer-events: none; }

  .filter-card {
    background: rgba(255,255,255,.9);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 16px; padding: 18px 20px; margin-bottom: 16px;
  }

  .report-table { border-collapse: separate; border-spacing: 0; width: 100%; }
  .report-table thead th {
    background: rgba(248,250,252,.9);
    border-bottom: 1px solid rgba(226,232,240,.9);
    padding: 12px 16px; font-size: 12px; font-weight: 800;
    color: #64748b; text-transform: uppercase; letter-spacing: .4px;
    white-space: nowrap;
  }
  .report-table tbody tr {
    border-bottom: 1px solid rgba(226,232,240,.6);
    transition: background .12s;
  }
  .report-table tbody tr:hover { background: rgba(14,165,233,.04); }
  .report-table tbody td {
    padding: 13px 16px; font-size: 14px; vertical-align: middle;
  }
  .report-table tbody tr:last-child td { border-bottom: none; }

  .emp-cell { display: flex; align-items: center; gap: 10px; }
  .emp-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(14,165,233,.12); color: #0369a1;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 900; flex-shrink: 0;
  }
  .emp-name { font-weight: 700; color: #1e293b; }

  .file-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 8px; font-size: 12px;
    font-weight: 800; background: rgba(16,185,129,.1);
    color: #047857; border: 1px solid rgba(16,185,129,.25);
    text-decoration: none; transition: .15s;
  }
  .file-btn:hover { background: rgba(16,185,129,.2); color: #047857; }

  .no-file { color: #cbd5e1; font-size: 13px; }

  .clear-filters-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 20px; font-size: 13px;
    font-weight: 800; background: rgba(239,68,68,.08);
    color: #b91c1c; border: 1px solid rgba(239,68,68,.2);
    text-decoration: none; transition: .15s;
  }
  .clear-filters-btn:hover { background: rgba(239,68,68,.15); color: #b91c1c; }

  /* Mobile card view */
  @media (max-width: 767px) {
    .desktop-table { display: none; }
    .mobile-cards  { display: block; }
    .mobile-report-card {
      background: #fff; border: 1px solid rgba(226,232,240,.9);
      border-radius: 14px; padding: 14px 16px; margin-bottom: 10px;
    }
    .mobile-report-card .card-top {
      display: flex; justify-content: space-between;
      align-items: flex-start; margin-bottom: 10px;
    }
  }
  @media (min-width: 768px) {
    .mobile-cards { display: none; }
    .desktop-table { display: block; }
  }
</style>


<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
  <div>
    <h4 style="font-weight:900; margin:0;">تقارير المهام</h4>
    <div style="font-size:13px; color:#64748b; margin-top:2px;">
      إجمالي التقارير:
      <b style="color:#1e293b;"><?php echo e($reports->total()); ?></b>
    </div>
  </div>
  <a href="<?php echo e(route('reports.task.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-upload"></i> رفع تقرير
  </a>
</div>


<?php
  $quick    = request('quick');
  $hasQuick = in_array($quick, ['today','week','month']);
?>
<div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px;">
  <a href="?quick=today"  class="quick-btn <?php echo e($quick === 'today' ? 'active' : ''); ?>">
    <i class="bi bi-sun" style="font-size:13px"></i> اليوم
  </a>
  <a href="?quick=week"   class="quick-btn <?php echo e($quick === 'week'  ? 'active' : ''); ?>">
    <i class="bi bi-calendar-week" style="font-size:13px"></i> هذا الأسبوع
  </a>
  <a href="?quick=month"  class="quick-btn <?php echo e($quick === 'month' ? 'active' : ''); ?>">
    <i class="bi bi-calendar-month" style="font-size:13px"></i> هذا الشهر
  </a>
</div>


<?php
  $hasFilter = request()->hasAny(['search','report_type','employee_id','from','to']) &&
               array_filter(request()->only(['search','report_type','employee_id','from','to']));
?>
<div class="filter-card">
  <form method="GET">
    <div class="row g-2">

      <div class="col-6 col-md-3">
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="بحث بالعنوان..."
               value="<?php echo e(request('search')); ?>">
      </div>

      <div class="col-6 col-md-3">
        <select name="report_type" class="form-select form-select-sm">
          <option value="">كل الأنواع</option>
          <option value="daily"   <?php if(request('report_type')=='daily'): echo 'selected'; endif; ?>>يومي</option>
          <option value="weekly"  <?php if(request('report_type')=='weekly'): echo 'selected'; endif; ?>>أسبوعي</option>
          <option value="monthly" <?php if(request('report_type')=='monthly'): echo 'selected'; endif; ?>>شهري</option>
        </select>
      </div>

      <?php if(auth()->user()->hasRole('super_admin')): ?>
        <div class="col-6 col-md-3">
          <select name="employee_id" class="form-select form-select-sm">
            <option value="">كل الموظفين</option>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($emp->id); ?>" <?php if(request('employee_id')==$emp->id): echo 'selected'; endif; ?>>
                <?php echo e($emp->full_name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      <?php endif; ?>

      <div class="col-6 col-md-3">
        <input type="date" name="from" class="form-control form-control-sm"
               value="<?php echo e(request('from')); ?>" placeholder="من تاريخ">
      </div>

      <div class="col-6 col-md-3">
        <input type="date" name="to" class="form-control form-control-sm"
               value="<?php echo e(request('to')); ?>" placeholder="إلى تاريخ">
      </div>

    </div>

    <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap; align-items:center;">
      <button class="btn btn-namaa btn-sm px-4 fw-bold">
        <i class="bi bi-search"></i> تصفية
      </button>

      
      <?php if($hasFilter || $hasQuick): ?>
        <a href="<?php echo e(route('reports.task.index')); ?>" class="clear-filters-btn">
          <i class="bi bi-x-circle" style="font-size:13px"></i> مسح الفلترة
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>


<div class="desktop-table">
  <div style="background:#fff; border:1px solid rgba(226,232,240,.9); border-radius:16px; overflow:hidden;">
    <table class="report-table">
      <thead>
        <tr>
          <th><i class="bi bi-person" style="font-size:11px"></i> الموظف</th>
          <th><i class="bi bi-tag"    style="font-size:11px"></i> نوع التقرير</th>
          <th><i class="bi bi-calendar3" style="font-size:11px"></i> التاريخ</th>
          <th><i class="bi bi-card-text" style="font-size:11px"></i> العنوان</th>
          <th><i class="bi bi-paperclip" style="font-size:11px"></i> الملف</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td>
              <div class="emp-cell">
                <div class="emp-avatar">
                  <?php echo e(mb_substr($r->employee->full_name ?? '؟', 0, 1, 'UTF-8')); ?>

                </div>
                <span class="emp-name"><?php echo e($r->employee->full_name ?? '—'); ?></span>
              </div>
            </td>
            <td>
              <?php
                $typeClass = match($r->report_type) {
                  'daily'   => 'type-daily',
                  'weekly'  => 'type-weekly',
                  'monthly' => 'type-monthly',
                  default   => 'type-daily',
                };
                $typeLabel = match($r->report_type) {
                  'daily'   => 'يومي',
                  'weekly'  => 'أسبوعي',
                  'monthly' => 'شهري',
                  default   => $r->report_type,
                };
              ?>
              <span class="report-type-badge <?php echo e($typeClass); ?>"><?php echo e($typeLabel); ?></span>
            </td>
            <td style="color:#64748b; font-size:13px;">
              <i class="bi bi-calendar2" style="font-size:11px; margin-left:4px;"></i>
              <?php echo e($r->report_date?->format('Y/m/d') ?? '—'); ?>

            </td>
            <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
              <?php echo e($r->title ?? '—'); ?>

            </td>
            <td>
              <?php if($r->file_path): ?>
                <a href="<?php echo e(asset('storage/'.$r->file_path)); ?>" target="_blank" class="file-btn">
                  <i class="bi bi-file-earmark-pdf" style="font-size:13px"></i> فتح
                </a>
              <?php else: ?>
                <span class="no-file">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if(auth()->user()->hasRole('super_admin') || auth()->user()->employee?->id === $r->employee_id): ?>
                <form method="POST" action="<?php echo e(route('reports.task.destroy', $r)); ?>"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm" style="background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:4px 10px;">
                    <i class="bi bi-trash" style="font-size:12px"></i>
                  </button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="6" style="text-align:center; padding:40px; color:#94a3b8;">
              <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px;"></i>
              لا توجد تقارير مطابقة للفلترة المحددة
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<div class="mobile-cards">
  <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
      $typeClass = match($r->report_type) { 'daily'=>'type-daily','weekly'=>'type-weekly','monthly'=>'type-monthly',default=>'type-daily' };
      $typeLabel = match($r->report_type) { 'daily'=>'يومي','weekly'=>'أسبوعي','monthly'=>'شهري',default=>$r->report_type };
    ?>
    <div class="mobile-report-card">
      <div class="card-top">
        <div class="emp-cell">
          <div class="emp-avatar"><?php echo e(mb_substr($r->employee->full_name ?? '؟', 0, 1, 'UTF-8')); ?></div>
          <div>
            <div class="emp-name" style="font-size:14px;"><?php echo e($r->employee->full_name ?? '—'); ?></div>
            <div style="font-size:11px; color:#94a3b8;">
              <?php echo e($r->report_date?->format('Y/m/d')); ?>

            </div>
          </div>
        </div>
        <span class="report-type-badge <?php echo e($typeClass); ?>"><?php echo e($typeLabel); ?></span>
      </div>

      <?php if($r->title): ?>
        <div style="font-size:13px; color:#1e293b; font-weight:700; margin-bottom:8px;">
          <?php echo e($r->title); ?>

        </div>
      <?php endif; ?>

      <div style="display:flex; gap:8px; align-items:center; justify-content:space-between;">
        <?php if($r->file_path): ?>
          <a href="<?php echo e(asset('storage/'.$r->file_path)); ?>" target="_blank" class="file-btn">
            <i class="bi bi-file-earmark-pdf" style="font-size:13px"></i> فتح الملف
          </a>
        <?php else: ?>
          <span class="no-file" style="font-size:12px;">لا يوجد ملف</span>
        <?php endif; ?>

        <?php if(auth()->user()->hasRole('super_admin') || auth()->user()->employee?->id === $r->employee_id): ?>
          <form method="POST" action="<?php echo e(route('reports.task.destroy', $r)); ?>"
                onsubmit="return confirm('حذف التقرير؟')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm" style="background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:4px 10px;font-size:12px;">
              <i class="bi bi-trash"></i> حذف
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div style="text-align:center; padding:40px 20px; color:#94a3b8;">
      <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px;"></i>
      لا توجد تقارير
    </div>
  <?php endif; ?>
</div>


<div class="mt-3">
  <?php echo e($reports->withQueryString()->links()); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/task_reports/index.blade.php ENDPATH**/ ?>