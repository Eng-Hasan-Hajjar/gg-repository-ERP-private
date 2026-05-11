
<?php $__env->startSection('title', 'تقارير CRM'); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-3">تقارير CRM</h4>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('crm.reports.index')); ?>">
  <div class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label fw-bold">من تاريخ</label>
      <input type="date" name="from" class="form-control" value="<?php echo e($from); ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label fw-bold">إلى تاريخ</label>
      <input type="date" name="to" class="form-control" value="<?php echo e($to); ?>">
    </div>
    <div class="col-md-3 d-grid">
      <button class="btn btn-namaa fw-bold">تطبيق</button>
    </div>



    <div class="col-md-3 d-grid">
      <a href="<?php echo e(route('crm.reports.pdf', request()->query())); ?>" class="btn btn-outline-danger fw-bold">
        <i class="bi bi-file-earmark-pdf"></i>
        تصدير PDF
      </a>
    </div>



  </div>
</form>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body"><b>الإجمالي:</b> <?php echo e($summary['total']); ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body"><b>Converted:</b> <?php echo e($summary['converted']); ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body"><b>Pending:</b> <?php echo e($summary['pending']); ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body"><b>Lost:</b> <?php echo e($summary['lost']); ?></div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>الفرع</th>
          <th>إجمالي Leads</th>
          <th>Converted</th>
          <th>نسبة التحويل</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $byBranch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php ($rate = $r->total ? round(($r->converted / $r->total) * 100, 1) : 0); ?>
        <tr>
          <td><?php echo e($r->branch->name ?? '—'); ?></td>
          <td><?php echo e($r->total); ?></td>
          <td><?php echo e($r->converted); ?></td>
          <td><?php echo e($rate); ?>%</td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\crm\reports\index.blade.php ENDPATH**/ ?>