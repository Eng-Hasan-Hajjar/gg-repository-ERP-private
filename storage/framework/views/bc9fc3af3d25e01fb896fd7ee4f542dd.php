
<?php ($activeModule = 'attendance'); ?>
<?php $__env->startSection('title', 'تقويم الدوام'); ?>

<?php $__env->startPush('styles'); ?>
  <style>
    .calendar-wrap {
      border-radius: 18px;
      overflow: hidden;
    }

    .cell {
      width: 34px;
      min-width: 34px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      font-weight: 900;
      font-size: .85rem;
    }

    .st-present {
      background: rgba(16, 185, 129, .14);
      color: #065f46;
      border: 1px solid rgba(16, 185, 129, .25);
    }

    .st-late {
      background: rgba(245, 158, 11, .16);
      color: #7c2d12;
      border: 1px solid rgba(245, 158, 11, .28);
    }

    .st-absent {
      background: rgba(239, 68, 68, .14);
      color: #7f1d1d;
      border: 1px solid rgba(239, 68, 68, .25);
    }

    .st-leave {
      background: rgba(59, 130, 246, .14);
      color: #1e3a8a;
      border: 1px solid rgba(59, 130, 246, .25);
    }

    .st-off {
      background: rgba(148, 163, 184, .18);
      color: #0f172a;
      border: 1px solid rgba(148, 163, 184, .28);
    }

    .st-sched {
      background: rgba(99, 102, 241, .12);
      color: #312e81;
      border: 1px solid rgba(99, 102, 241, .22);
    }

    .calendar-table {
      table-layout: fixed;
    }

    .calendar-table thead th {
      position: sticky;
      top: 0;
      z-index: 6;
      background: #f8fafc;
      white-space: nowrap;
    }

    .calendar-table th.emp-col,
    .calendar-table td.emp-col {
      position: sticky;
      right: 0;
      z-index: 4;
      background: #fff;
      min-width: 200px;
      width: 200px;
      box-shadow: -6px 0 14px rgba(2, 6, 23, .06);
      white-space: normal;
      word-break: keep-all;
      overflow-wrap: break-word;
      vertical-align: middle;
      padding: 10px 12px;
    }

    .calendar-table thead th.emp-col {
      z-index: 7;
      background: #f8fafc;
    }

    .calendar-table tbody tr:hover td {
      background: rgba(2, 6, 23, .015);
    }

    .legend-chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 12px;
      border-radius: 999px;
      border: 1px solid rgba(226, 232, 240, .95);
      background: rgba(255, 255, 255, .85);
      font-weight: 900;
      font-size: .88rem;
      white-space: nowrap;
    }

    .dot {
      width: 11px;
      height: 11px;
      border-radius: 50%;
      display: inline-block;
    }

    /* ═══ MOBILE CARD VIEW ═══ */
    .mob-emp-card {
      background: #fff;
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 14px;
      padding: 14px 15px;
      margin-bottom: 10px;
    }

    .mob-emp-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .mob-emp-name {
      font-weight: 900;
      font-size: 14px;
      color: #1e293b;
    }

    .mob-emp-branch {
      font-size: 11px;
      color: #94a3b8;
      margin-top: 1px;
    }

    .mob-summary {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 6px;
      margin-bottom: 10px;
    }

    .mob-stat {
      text-align: center;
      padding: 6px 4px;
      border-radius: 10px;
      border: 1px solid;
    }

    .mob-stat-val {
      font-size: 16px;
      font-weight: 900;
      line-height: 1.1;
    }

    .mob-stat-lbl {
      font-size: 10px;
      font-weight: 700;
      margin-top: 1px;
    }

    .mob-days {
      display: flex;
      flex-wrap: wrap;
      gap: 4px;
    }

    .mob-day {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      min-width: 32px;
    }

    .mob-day-num {
      font-size: 10px;
      color: #94a3b8;
      font-weight: 700;
    }

    .mob-day-cell {
      width: 30px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      font-weight: 900;
      font-size: 12px;
    }

    @media (max-width: 767px) {
      .desktop-calendar {
        display: none !important;
      }
    }

    @media (min-width: 768px) {
      .mobile-calendar {
        display: none !important;
      }
    }
  </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="fw-bold mb-1">تقويم الدوام الشهري</h4>
      <div class="text-muted small">عرض حالات الدوام حسب اليوم</div>
    </div>
    <div class="d-flex flex-wrap gap-2 w-100 w-lg-auto justify-content-start justify-content-lg-end">
      <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-return-right"></i> رجوع
      </a>
      <div class="dropdown d-sm-none w-100">
        <button class="btn btn-namaa w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-download"></i> تصدير
        </button>
        <ul class="dropdown-menu w-100">
          <li>
            <a class="dropdown-item" href="<?php echo e(route('attendance.calendar.exportExcel', request()->query())); ?>">
              <i class="bi bi-file-earmark-excel text-success"></i> تصدير Excel
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="<?php echo e(route('attendance.calendar.exportPdf', request()->query())); ?>">
              <i class="bi bi-file-earmark-pdf text-danger"></i> تصدير PDF
            </a>
          </li>
        </ul>
      </div>
      <a class="btn btn-success d-none d-sm-inline-flex"
        href="<?php echo e(route('attendance.calendar.exportExcel', request()->query())); ?>">
        <i class="bi bi-file-earmark-excel"></i> Excel
      </a>
      <a class="btn btn-danger d-none d-sm-inline-flex"
        href="<?php echo e(route('attendance.calendar.exportPdf', request()->query())); ?>">
        <i class="bi bi-file-earmark-pdf"></i> PDF
      </a>
    </div>
  </div>

  
  <form class="card mb-3" method="GET" action="<?php echo e(route('attendance.calendar')); ?>">
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4 col-lg-3">
          <label class="form-label fw-bold mb-1">الشهر</label>
          <input type="month" name="month" value="<?php echo e($month); ?>" class="form-control">
        </div>
        <div class="col-12 col-md-4 col-lg-3">
          <label class="form-label fw-bold mb-1">الفرع</label>
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(($filters['branch_id'] ?? '') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
          <button class="btn btn-namaa w-100">
            <i class="bi bi-funnel"></i> تطبيق
          </button>
        </div>
      </div>
    </div>
  </form>

  
  <div class="desktop-calendar">
    <div class="calendar-wrap border">
      <div class="table-responsive" style="max-height:68vh;">
        <table class="table table-bordered align-middle mb-0 calendar-table">
          <thead class="table-light">
            <tr>
              <th class="emp-col">الموظف</th>
              <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th class="text-center" style="min-width:40px;">
                  <?php echo e(\Carbon\Carbon::parse($date)->day); ?>

                </th>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="emp-col">
                  <div class="fw-bold" style="font-size:14px;"><?php echo e($emp->full_name); ?></div>
                  <div class="small text-muted"><?php echo e($emp->branch->name ?? '-'); ?></div>
                </td>
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <td class="text-center">
                    <span class="cell <?php echo e($statusCssMap[$recordsMap[$emp->id][$date] ?? null] ?? ''); ?>"
                      data-bs-toggle="tooltip" data-bs-placement="top"
                      title="<?php echo e($recordsMap[$emp->id][$date] ?? 'لا يوجد سجل'); ?>">
                      <?php echo e($letterMap[$recordsMap[$emp->id][$date] ?? null] ?? '-'); ?>

                    </span>
                  </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="<?php echo e(1 + count($days)); ?>" class="text-center text-muted py-4">
                  لا يوجد موظفون مطابقون.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  
  <div class="mobile-calendar">
    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="mob-emp-card">

        <div class="mob-emp-header">
          <div>
            <div class="mob-emp-name"><?php echo e($emp->full_name); ?></div>
            <div class="mob-emp-branch"><?php echo e($emp->branch->name ?? '—'); ?></div>
          </div>
          <div style="font-size:12px; color:#94a3b8; font-weight:700;">
            <?php echo e(\Carbon\Carbon::parse($month)->format('m/Y')); ?>

          </div>
        </div>

        
        <div class="mob-summary">
          <div class="mob-stat st-present" style="border-color:rgba(16,185,129,.25);">
            <div class="mob-stat-val" style="color:#065f46;"><?php echo e($employeeSummary[$emp->id]['present']); ?></div>
            <div class="mob-stat-lbl" style="color:#065f46;">حضور</div>
          </div>
          <div class="mob-stat st-absent" style="border-color:rgba(239,68,68,.25);">
            <div class="mob-stat-val" style="color:#7f1d1d;"><?php echo e($employeeSummary[$emp->id]['absent']); ?></div>
            <div class="mob-stat-lbl" style="color:#7f1d1d;">غياب</div>
          </div>
          <div class="mob-stat st-late" style="border-color:rgba(245,158,11,.28);">
            <div class="mob-stat-val" style="color:#7c2d12;"><?php echo e($employeeSummary[$emp->id]['late']); ?></div>
            <div class="mob-stat-lbl" style="color:#7c2d12;">تأخير</div>
          </div>
          <div class="mob-stat st-leave" style="border-color:rgba(59,130,246,.25);">
            <div class="mob-stat-val" style="color:#1e3a8a;"><?php echo e($employeeSummary[$emp->id]['leave']); ?></div>
            <div class="mob-stat-lbl" style="color:#1e3a8a;">إجازة</div>
          </div>
          <div class="mob-stat st-off" style="border-color:rgba(148,163,184,.28);">
            <div class="mob-stat-val" style="color:#0f172a;"><?php echo e($employeeSummary[$emp->id]['off']); ?></div>
            <div class="mob-stat-lbl" style="color:#0f172a;">عطلة</div>
          </div>
          <div class="mob-stat" style="background:rgba(248,250,252,.9); border-color:rgba(226,232,240,.9);">
            <div class="mob-stat-val" style="color:#1e293b;"><?php echo e(count($days)); ?></div>
            <div class="mob-stat-lbl" style="color:#64748b;">يوم</div>
          </div>
        </div>

        
        <div class="mob-days">
          <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mob-day">
              <div class="mob-day-num"><?php echo e(\Carbon\Carbon::parse($date)->day); ?></div>
              <div class="mob-day-cell <?php echo e($statusCssMap[$recordsMap[$emp->id][$date] ?? null] ?? ''); ?>">
                <?php echo e($letterMap[$recordsMap[$emp->id][$date] ?? null] ?? '·'); ?>

              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div style="text-align:center; padding:40px 20px; color:#94a3b8;">
        <i class="bi bi-calendar-x" style="font-size:28px; display:block; margin-bottom:8px;"></i>
        لا يوجد موظفون مطابقون.
      </div>
    <?php endif; ?>
  </div>

  
  <div class="d-flex flex-wrap gap-2 mt-3">
    <span class="legend-chip"><span class="dot" style="background:#10b981"></span> P = حضور</span>
    <span class="legend-chip"><span class="dot" style="background:#f59e0b"></span> L = تأخير</span>
    <span class="legend-chip"><span class="dot" style="background:#ef4444"></span> A = غياب</span>
    <span class="legend-chip"><span class="dot" style="background:#3b82f6"></span> V = إجازة</span>
    <span class="legend-chip"><span class="dot" style="background:#94a3b8"></span> O = عطلة</span>
    <span class="legend-chip"><span class="dot" style="background:#6366f1"></span> S = مجدول</span>
  </div>

  <?php $__env->startPush('scripts'); ?>
    <script>
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
      });
    </script>
  <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/attendance/calendar.blade.php ENDPATH**/ ?>