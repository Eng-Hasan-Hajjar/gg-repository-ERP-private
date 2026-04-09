
<?php ($activeModule = 'tasks'); ?>
<?php $__env->startSection('title', 'تفاصيل مهمة'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold"><?php echo e($task->title); ?></h4>

      <a href="<?php echo e(route('reports.task.create',['task'=>$task->id])); ?>"
class="btn btn-outline-primary rounded-pill">

<i class="bi bi-upload"></i>
رفع تقرير لهذه المهمة

</a>

      <div class="text-muted fw-semibold">
        الفرع: <b><?php echo e($task->branch->name ?? '-'); ?></b>
        — المسند له: <b><?php echo e($task->assignee->full_name ?? '-'); ?></b>
        — أولوية: <b><?php echo e($task->priority); ?></b>
        — حالة: <b><?php echo e($task->status); ?></b>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      <?php if(auth()->user()?->hasPermission('edit_tasks')): ?>
        <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
          <i class="bi bi-pencil"></i> تعديل
        </a>
      <?php endif; ?>


      <?php if(auth()->user()?->hasPermission('complete_tasks')): ?>
        <form method="POST" action="<?php echo e(route('tasks.quickStatus', $task)); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="status" value="done">
          <button class="btn btn-success rounded-pill px-4 fw-bold">
            <i class="bi bi-check2-circle"></i> تعليم كمنجزة
          </button>
        </form>
      <?php endif; ?>
      <?php if(auth()->user()?->hasPermission('delete_tasks')): ?>
        <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" onsubmit="return confirm('حذف المهمة؟');">
          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
          <button class="btn btn-outline-danger rounded-pill px-4 fw-bold">
            <i class="bi bi-trash"></i> حذف
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-2">الوصف</h6>
          <div class="text-muted fw-semibold" style="line-height:1.9">
            <?php echo e($task->description ?? '—'); ?>

          </div>
          <hr>
          <div class="small text-muted fw-semibold">
            تاريخ الاستحقاق: <b><?php echo e($task->due_date?->format('Y-m-d') ?? '-'); ?></b>
            — أُنشئت بواسطة: <b><?php echo e($task->creator->name ?? '-'); ?></b>
            — آخر تحديث: <b><?php echo e($task->updated_at->format('Y-m-d H:i')); ?></b>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3">تغيير الحالة</h6>
          <form method="POST" action="<?php echo e(route('tasks.quickStatus', $task)); ?>" class="d-flex gap-2">
            <?php echo csrf_field(); ?>
            <select name="status" class="form-select">
              <?php $__currentLoopData = ['todo', 'in_progress', 'done', 'blocked', 'archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php if($task->status == $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn btn-namaa fw-bold">تحديث</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/tasks/show.blade.php ENDPATH**/ ?>