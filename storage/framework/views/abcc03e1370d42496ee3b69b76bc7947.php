
<?php ($activeModule = 'reports'); ?>

<?php $__env->startSection('title','لوحة القيادة التنفيذية'); ?>

<?php $__env->startSection('content'); ?>

<h4 class="mb-3">لوحة القيادة التنفيذية</h4>

<div class="row g-3 mb-4">

  
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">إيرادات اليوم</div>
      <div class="stat-value text-success">
        <?php echo e(number_format($data['executive']['revenue_today'],2)); ?> €
      </div>
    </div>
  </div>

  
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">حالة النظام</div>
      <div class="stat-value">
        <?php if($data['executive']['health'] === 'online'): ?>
          <span class="badge bg-success">Online</span>
        <?php elseif($data['executive']['health'] === 'degraded'): ?>
          <span class="badge bg-warning">Degraded</span>
        <?php else: ?>
          <span class="badge bg-danger">Issues</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">إجمالي الطلاب</div>
      <div class="stat-value">
        <?php echo e($data['cards'][0]['value'] ?? 0); ?>

      </div>
    </div>
  </div>
</div>


<div class="glass-card p-3">
  <h6 class="card-title mb-2">آخر 5 عمليات في النظام</h6>
  <table class="table table-sm">
    <thead>
      <tr>
        <th>الوقت</th>
        <th>الإجراء</th>
        <th>النموذج</th>
        <th>الوصف</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $data['executive']['latest_audit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e(\Carbon\Carbon::parse($log['time'])->format('H:i')); ?></td>
        <td><?php echo e($log['action']); ?></td>
        <td><?php echo e($log['model']); ?></td>
        <td><?php echo e($log['description']); ?></td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>

  <div class="text-end mt-2">
    <a href="<?php echo e(route('admin.audit.index')); ?>" class="btn btn-soft">
      الذهاب إلى مركز التدقيق الكامل
    </a>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/reports/executive.blade.php ENDPATH**/ ?>