
<?php ($activeModule = 'audit'); ?>

<?php $__env->startSection('title','مركز التدقيق'); ?>

<?php $__env->startSection('content'); ?>

<h4 class="mb-3">مركز التدقيق (Audit Center)</h4>

<form method="GET" class="row g-2 mb-3">

  <div class="col-md-3">
    <select name="user_id" class="form-select">
      <option value="">— كل المستخدمين —</option>
      <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->id); ?>" <?php if(request('user_id')==$u->id): echo 'selected'; endif; ?>>
          <?php echo e($u->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-md-3">
    <select name="action" class="form-select">
      <option value="">— كل الإجراءات —</option>
      <option value="created" <?php if(request('action')=='created'): echo 'selected'; endif; ?>>إنشاء</option>
      <option value="updated" <?php if(request('action')=='updated'): echo 'selected'; endif; ?>>تعديل</option>
      <option value="deleted" <?php if(request('action')=='deleted'): echo 'selected'; endif; ?>>حذف</option>
      <option value="login" <?php if(request('action')=='login'): echo 'selected'; endif; ?>>تسجيل دخول</option>
      <option value="logout" <?php if(request('action')=='logout'): echo 'selected'; endif; ?>>تسجيل خروج</option>
    </select>
  </div>

  <div class="col-md-3">
    <select name="model" class="form-select">
      <option value="">— كل النماذج —</option>
      <option value="User" <?php if(request('model')=='User'): echo 'selected'; endif; ?>>المستخدم</option>
      <option value="Role" <?php if(request('model')=='Role'): echo 'selected'; endif; ?>>الدور</option>
      <option value="Permission" <?php if(request('model')=='Permission'): echo 'selected'; endif; ?>>الصلاحية</option>
    </select>
  </div>

  <div class="col-md-3">
    <button class="btn btn-primary w-100">تطبيق الفلترة</button>
  </div>
</form>

<table class="table table-bordered table-sm">
<thead>
<tr>
  <th>الوقت</th>
  <th>المستخدم</th>
  <th>الإجراء</th>
  <th>النموذج</th>
  <th>الوصف</th>
  <th>IP</th>
</tr>
</thead>

<tbody>
<?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
  <td><?php echo e($log->created_at->format('Y-m-d H:i')); ?></td>
  <td><?php echo e($log->user?->name ?? '—'); ?></td>
  <td>
    <span class="badge bg-secondary"><?php echo e($log->action); ?></span>
  </td>
  <td><?php echo e($log->model); ?></td>
  <td><?php echo e($log->description); ?></td>
  <td><?php echo e($log->ip); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
  <td colspan="6" class="text-center text-muted">لا توجد سجلات</td>
</tr>
<?php endif; ?>
</tbody>
</table>

<?php echo e($logs->withQueryString()->links()); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/admin/audit/index.blade.php ENDPATH**/ ?>