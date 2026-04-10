

<?php $__env->startSection('title', 'رفع تقرير'); ?>

<?php $__env->startSection('content'); ?>



    <h4 class="fw-bold mb-3">رفع تقرير جديد</h4>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(!auth()->user()->employee): ?>

        <div class="alert alert-warning">

            هذا الحساب غير مرتبط بموظف، لذلك لا يمكن رفع تقرير.

        </div>

    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('reports.task.store')); ?>" enctype="multipart/form-data">

        <?php echo csrf_field(); ?>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">نوع التقرير</label>
                <select name="report_type" class="form-select" required>

                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly">شهري</option>

                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">تاريخ التقرير</label>
                <input type="date" name="report_date" class="form-control" required>
            </div>

            <div class="col-md-4" hidden>
                <label class="form-label">المهمة</label>

                <select name="task_id" class="form-select">

                    <option value="">بدون مهمة</option>

                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($task->id); ?>">
                            <?php echo e($task->title); ?>

                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">عنوان التقرير</label>
                <input name="title" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="4"></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">ملف التقرير</label>
                <input type="file" name="file" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary mt-3">
            رفع التقرير
        </button>

    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/task_reports/create.blade.php ENDPATH**/ ?>