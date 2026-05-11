
<?php ($activeModule='users'); ?>

<?php $__env->startSection('title','تفاصيل الدور'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <h4 class="fw-bold">تفاصيل الدور</h4>

    <div class="row mt-3">
      <div class="col-md-4">
        <strong>الاسم البرمجي:</strong>
        <code><?php echo e($role->name); ?></code>
      </div>

      <div class="col-md-4">
        <strong>الاسم المعروض:</strong>
        <?php echo e($role->label); ?>

      </div>

      <div class="col-md-4">
        <strong>عدد المستخدمين:</strong>
        <span class="badge bg-secondary"><?php echo e($role->users->count()); ?></span>
      </div>
    </div>

    <hr>

    <h5 class="fw-bold">الصلاحيات (Tree View)</h5>

    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header fw-bold">
       <?php echo e(t($module)); ?>

      </div>
      <div class="card-body">
        <ul class="list-unstyled">
          <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="mb-2">
            <label>
              <input type="checkbox"
                     class="perm-toggle"
                     data-role="<?php echo e($role->id); ?>"
                     data-perm="<?php echo e($p->id); ?>"
                     <?php if($role->permissions->contains($p->id)): echo 'checked'; endif; ?>>
              <?php echo e($p->label); ?>

            </label>
          </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>






<hr>

<h5 class="fw-bold">سجل التغييرات على الصلاحيات</h5>

<table class="table table-sm table-bordered">
  <thead class="table-light">
    <tr>
      <th>التاريخ</th>
      <th>المستخدم</th>
      <th>الإجراء</th>
      <th>التفاصيل</th>
      <th>IP</th>
    </tr>
  </thead>
  <tbody>
    <?php $__currentLoopData = \App\Models\AuditLog::where('model','Role')
            ->where('model_id',$role->id)
            ->latest()->take(20)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr>
      <td><?php echo e($log->created_at->format('Y-m-d H:i')); ?></td>
      <td><?php echo e(optional($log->user)->name ?? 'System'); ?></td>
      <td>
        <span class="badge bg-<?php echo e(str_contains($log->action,'added') ? 'success' : 'danger'); ?>">
          <?php echo e($log->action); ?>

        </span>
      </td>
      <td><?php echo e($log->description); ?></td>
      <td><code><?php echo e($log->ip); ?></code></td>
    </tr>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>










<script>
document.querySelectorAll('.perm-toggle').forEach(el=>{
  el.addEventListener('change', function(){
    let role = this.dataset.role;
    let perm = this.dataset.perm;

    fetch(`/admin/roles/${role}/toggle-permission/${perm}`, {
      method: 'POST',
      headers:{
        'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>',
        'Accept':'application/json'
      }
    });
  });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\admin\roles\show.blade.php ENDPATH**/ ?>