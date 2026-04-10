
<?php ($activeModule = 'audit'); ?>
<?php $__env->startSection('title', 'مركز التدقيق'); ?>

<?php $__env->startSection('content'); ?>

<style>
  .audit-stat {
    background: #fff;
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .audit-stat-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
  }
  .audit-stat-val  { font-size: 20px; font-weight: 900; line-height: 1.1; }
  .audit-stat-lbl  { font-size: 11px; color: #94a3b8; font-weight: 700; margin-top: 2px; }

  .filter-card {
    background: #fff;
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 16px;
  }

  .audit-table { border-collapse: separate; border-spacing: 0; width: 100%; }
  .audit-table thead th {
    background: rgba(248,250,252,.95);
    border-bottom: 1px solid rgba(226,232,240,.9);
    padding: 11px 14px;
    font-size: 11px; font-weight: 800;
    color: #64748b; text-transform: uppercase; letter-spacing: .4px;
    white-space: nowrap;
  }
  .audit-table tbody tr {
    border-bottom: 1px solid rgba(226,232,240,.5);
    transition: background .1s;
  }
  .audit-table tbody tr:hover { background: rgba(14,165,233,.03); }
  .audit-table tbody td { padding: 11px 14px; font-size: 13px; vertical-align: middle; }
  .audit-table tbody tr:last-child td { border-bottom: none; }

  .action-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 800; border: 1px solid;
    white-space: nowrap;
  }
  .ab-success  { background: rgba(16,185,129,.1);  color: #047857; border-color: rgba(16,185,129,.25); }
  .ab-warning  { background: rgba(245,158,11,.1);  color: #b45309; border-color: rgba(245,158,11,.25); }
  .ab-danger   { background: rgba(239,68,68,.1);   color: #b91c1c; border-color: rgba(239,68,68,.25); }
  .ab-info     { background: rgba(14,165,233,.1);  color: #0369a1; border-color: rgba(14,165,233,.25); }
  .ab-secondary{ background: rgba(100,116,139,.1); color: #475569; border-color: rgba(100,116,139,.25); }

  .user-cell { display: flex; align-items: center; gap: 8px; }
  .user-av {
    width: 30px; height: 30px; border-radius: 50%;
    background: rgba(14,165,233,.12); color: #0369a1;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 900; flex-shrink: 0;
  }

  .ip-badge {
    font-family: monospace; font-size: 11px; font-weight: 700;
    background: rgba(99,102,241,.08); color: #4338ca;
    border: 1px solid rgba(99,102,241,.2);
    padding: 3px 8px; border-radius: 6px;
  }
  .device-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; color: #64748b; font-weight: 700;
  }
  .model-pill {
    display: inline-block;
    background: rgba(248,250,252,.9);
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 6px; padding: 2px 8px;
    font-size: 11px; font-weight: 700; color: #475569;
  }

  @media (max-width: 767px) {
    .hide-mobile { display: none !important; }
    .audit-table tbody td { padding: 8px 10px; }
  }
</style>


<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
  <div>
    <h4 style="font-weight:900; margin:0;">مركز التدقيق</h4>
    <div style="font-size:13px; color:#64748b;">سجل كامل لجميع العمليات على النظام</div>
  </div>
</div>


<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px;">
  <div class="audit-stat">
    <div class="audit-stat-icon" style="background:rgba(14,165,233,.1); color:#0369a1;">
      <i class="bi bi-journal-text"></i>
    </div>
    <div>
      <div class="audit-stat-val text-primary"><?php echo e(number_format($stats['total'])); ?></div>
      <div class="audit-stat-lbl">إجمالي السجلات</div>
    </div>
  </div>
  <div class="audit-stat">
    <div class="audit-stat-icon" style="background:rgba(99,102,241,.1); color:#4338ca;">
      <i class="bi bi-calendar-day"></i>
    </div>
    <div>
      <div class="audit-stat-val" style="color:#4338ca;"><?php echo e($stats['today']); ?></div>
      <div class="audit-stat-lbl">عمليات اليوم</div>
    </div>
  </div>
  <div class="audit-stat">
    <div class="audit-stat-icon" style="background:rgba(16,185,129,.1); color:#047857;">
      <i class="bi bi-plus-circle-fill"></i>
    </div>
    <div>
      <div class="audit-stat-val text-success"><?php echo e($stats['created']); ?></div>
      <div class="audit-stat-lbl">إنشاء اليوم</div>
    </div>
  </div>
  <div class="audit-stat">
    <div class="audit-stat-icon" style="background:rgba(239,68,68,.1); color:#b91c1c;">
      <i class="bi bi-trash-fill"></i>
    </div>
    <div>
      <div class="audit-stat-val text-danger"><?php echo e($stats['deleted']); ?></div>
      <div class="audit-stat-lbl">حذف اليوم</div>
    </div>
  </div>
</div>



<div class="filter-card">
  <form method="GET">
    <div class="row g-2">

      <div class="col-12 col-md-3">
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="بحث: اسم المستخدم / الوصف / IP"
               value="<?php echo e(request('search')); ?>">
      </div>

      <div class="col-6 col-md-2">
        <select name="user_id" class="form-select form-select-sm">
          <option value="">كل المستخدمين</option>
          <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($u->id); ?>" <?php if(request('user_id') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="action" class="form-select form-select-sm">
          <option value="">كل الإجراءات</option>
          <option value="created" <?php if(request('action')=='created'): echo 'selected'; endif; ?>>إنشاء</option>
          <option value="updated" <?php if(request('action')=='updated'): echo 'selected'; endif; ?>>تعديل</option>
          <option value="deleted" <?php if(request('action')=='deleted'): echo 'selected'; endif; ?>>حذف</option>
          <option value="login"   <?php if(request('action')=='login'): echo 'selected'; endif; ?>>تسجيل دخول</option>
          <option value="logout"  <?php if(request('action')=='logout'): echo 'selected'; endif; ?>>تسجيل خروج</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="model" class="form-select form-select-sm">
          <option value="">كل النماذج</option>
          <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($m); ?>" <?php if(request('model') == $m): echo 'selected'; endif; ?>>
              <?php echo e((new \App\Models\AuditLog(['model'=>$m]))->model_label); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <div class="col-6 col-md-1">
        <input type="text" name="ip" class="form-control form-control-sm"
               placeholder="IP" value="<?php echo e(request('ip')); ?>">
      </div>

      <div class="col-6 col-md-1">
        <input type="date" name="date_from" class="form-control form-control-sm"
               value="<?php echo e(request('date_from')); ?>">
      </div>

      <div class="col-6 col-md-1">
        <input type="date" name="date_to" class="form-control form-control-sm"
               value="<?php echo e(request('date_to')); ?>">
      </div>

    </div>

    <div style="display:flex; gap:8px; margin-top:12px; align-items:center; flex-wrap:wrap;">
      <button class="btn btn-namaa btn-sm px-4 fw-bold">
        <i class="bi bi-search"></i> بحث
      </button>
      <?php if($hasFilter): ?>
        <a href="<?php echo e(route('admin.audit.index')); ?>"
           style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:800;background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);text-decoration:none;">
          <i class="bi bi-x-circle" style="font-size:13px"></i> مسح الفلترة
        </a>
      <?php endif; ?>
      <span style="font-size:12px; color:#94a3b8; margin-right:auto;">
        <?php echo e($logs->total()); ?> سجل
      </span>
    </div>
  </form>
</div>


<div style="background:#fff; border:1px solid rgba(226,232,240,.9); border-radius:16px; overflow:hidden;">
  <div class="table-responsive">
    <table class="audit-table">
      <thead>
        <tr>
          <th>الوقت</th>
          <th>المستخدم</th>
          <th>الإجراء</th>
          <th class="hide-mobile">النموذج</th>
          <th>الوصف</th>
          <th class="hide-mobile">الجهاز</th>
          <th class="hide-mobile">IP</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td style="white-space:nowrap; color:#64748b; font-size:12px;">
              <div style="font-weight:700; color:#1e293b;">
                <?php echo e($log->created_at->format('H:i')); ?>

              </div>
              <div><?php echo e($log->created_at->format('Y/m/d')); ?></div>
              <div style="font-size:11px; color:#94a3b8;">
                <?php echo e($log->created_at->diffForHumans()); ?>

              </div>
            </td>

            <td>
              <div class="user-cell">
                <div class="user-av">
                  <?php echo e(mb_substr($log->user->name ?? '؟', 0, 1, 'UTF-8')); ?>

                </div>
                <div>
                  <div style="font-weight:700; font-size:13px; color:#1e293b;">
                    <?php echo e($log->user->name ?? 'النظام'); ?>

                  </div>
                  <div style="font-size:11px; color:#94a3b8;">
                    <?php echo e($log->user->email ?? ''); ?>

                  </div>
                </div>
              </div>
            </td>

            <td>
              <span class="action-badge ab-<?php echo e($log->action_color); ?>">
                <i class="bi <?php echo e($log->action_icon); ?>" style="font-size:10px"></i>
                <?php echo e($log->action_label); ?>

              </span>
            </td>

            <td class="hide-mobile">
              <?php if($log->model): ?>
                <span class="model-pill"><?php echo e($log->model_label); ?></span>
                <?php if($log->model_id): ?>
                  <span style="font-size:11px; color:#94a3b8; margin-right:4px;">#<?php echo e($log->model_id); ?></span>
                <?php endif; ?>
              <?php else: ?>
                <span style="color:#94a3b8;">—</span>
              <?php endif; ?>
            </td>

            <td style="max-width:260px; color:#374151; font-size:13px;">
              <?php echo e($log->description ?? '—'); ?>

            </td>

            <td class="hide-mobile">
              <div class="device-badge">
                <i class="bi <?php echo e($log->device_icon); ?>" style="font-size:13px"></i>
                <?php echo e($log->device_type); ?>

              </div>
              <div style="font-size:11px; color:#94a3b8; margin-top:2px;">
                <?php echo e($log->browser); ?>

              </div>
            </td>

            <td class="hide-mobile">
              <span class="ip-badge"><?php echo e($log->ip ?? '—'); ?></span>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="7" style="text-align:center; padding:40px; color:#94a3b8;">
              <i class="bi bi-journal-x" style="font-size:28px; display:block; margin-bottom:8px;"></i>
              لا توجد سجلات مطابقة
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  <?php echo e($logs->withQueryString()->links()); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/audit/index.blade.php ENDPATH**/ ?>