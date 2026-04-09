<?php echo csrf_field(); ?>
<?php if(isset($task)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">
  <div class="col-12 col-md-8">
    <label class="form-label fw-bold">عنوان المهمة</label>
    <input name="title" class="form-control" required
           value="<?php echo e(old('title', $task->title ?? '')); ?>"
           placeholder="مثال: تحضير تقرير المصاريف / متابعة تسجيل الطلاب">
  </div>

  <div class="col-6 col-md-2">
    <label class="form-label fw-bold">الأولوية</label>
    <select name="priority" class="form-select" required>
      <?php $__currentLoopData = ['low'=>'منخفض','medium'=>'متوسط','high'=>'عال','urgent'=>'عاجل']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($k); ?>" <?php if(old('priority', $task->priority ?? 'medium')==$k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-6 col-md-2">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <?php $__currentLoopData = ['todo'=>'قيد الانتظار','in_progress'=>'قيد التنفيذ','done'=>'منجزة','blocked'=>'متوقفة','archived'=>'مؤرشفة']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($k); ?>" <?php if(old('status', $task->status ?? 'todo')==$k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id', $task->branch_id ?? '')==$b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">المسند له (موظف/مدرب)</label>
    <select name="assigned_to" class="form-select">
      <option value="">—</option>
      <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($e->id); ?>" <?php if(old('assigned_to', $task->assigned_to ?? '')==$e->id): echo 'selected'; endif; ?>>
          <?php echo e($e->full_name); ?> — <?php echo e($e->branch->name ?? 'بدون فرع'); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">تاريخ الاستحقاق</label>
    <input type="date" name="due_date" class="form-control"
           value="<?php echo e(old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '')); ?>">
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الوصف</label>
    <textarea name="description" rows="4" class="form-control"
              placeholder="تفاصيل المهمة..."><?php echo e(old('description', $task->description ?? '')); ?></textarea>
  </div>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
  </div>
<?php endif; ?>

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/tasks/_form.blade.php ENDPATH**/ ?>