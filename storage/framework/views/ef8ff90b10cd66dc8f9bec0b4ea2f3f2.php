

<?php $__env->startSection('title', 'تقارير الموظفين'); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex justify-content-between mb-3">

        <h4 class="fw-bold">تقارير المهام</h4>

        <a href="<?php echo e(route('reports.task.create')); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">

            <i class="bi bi-upload"></i>
            رفع تقرير

        </a>

    </div>


    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control" placeholder="بحث..."
                            value="<?php echo e(request('search')); ?>">
                    </div>


                    <div class="col-md-2">

                        <select name="report_type" class="form-select">

                            <option value="">نوع التقرير</option>

                            <option value="daily" <?php if(request('report_type') == 'daily'): echo 'selected'; endif; ?>>
                                يومي
                            </option>

                            <option value="weekly" <?php if(request('report_type') == 'weekly'): echo 'selected'; endif; ?>>
                                أسبوعي
                            </option>

                            <option value="monthly" <?php if(request('report_type') == 'monthly'): echo 'selected'; endif; ?>>
                                شهري
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <select name="task_id" class="form-select">

                            <option value="">المهمة</option>

                            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($task->id); ?>" <?php if(request('task_id') == $task->id): echo 'selected'; endif; ?>>

                                    <?php echo e($task->title); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                    </div>


                    <?php if(auth()->user()->hasRole('super_admin')): ?>

                        <div class="col-md-2">

                            <select name="employee_id" class="form-select">

                                <option value="">الموظف</option>

                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <option value="<?php echo e($emp->id); ?>" <?php if(request('employee_id') == $emp->id): echo 'selected'; endif; ?>>

                                        <?php echo e($emp->full_name); ?>


                                    </option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>

                        </div>

                    <?php endif; ?>


                    <div class="col-md-2">

                        <input type="date" name="from" class="form-control" value="<?php echo e(request('from')); ?>">

                    </div>


                    <div class="col-md-2">

                        <input type="date" name="to" class="form-control" value="<?php echo e(request('to')); ?>">

                    </div>

                </div>


                <div class="mt-2 d-flex gap-2">

                    <button class="btn btn-namaa px-4 fw-bold">

                        تصفية

                    </button>

                    <a href="<?php echo e(route('reports.task.index')); ?>" class="btn btn-outline-secondary">

                        إعادة ضبط

                    </a>

                </div>

            </form>

        </div>
    </div>






    <div class="mb-3 d-flex gap-2">

<a href="?quick=today"
class="btn btn-outline-dark">

تقارير اليوم

</a>

<a href="?quick=week"
class="btn btn-outline-dark">

هذا الأسبوع

</a>

<a href="?quick=month"
class="btn btn-outline-dark">

هذا الشهر

</a>

</div>




    <table class="table">

        <thead>
            <tr>

                <th>الموظف</th>
                <th>المهمة</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>الملف</th>

            </tr>
        </thead>

        <tbody>

            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr>

                    <td><?php echo e($r->employee->full_name); ?></td>

                    <td><?php echo e($r->task->title ?? '-'); ?></td>

                    <td><?php echo e($r->report_type); ?></td>

                    <td><?php echo e($r->report_date); ?></td>

                    <td>

                        <?php if($r->file_path): ?>

                            <a href="<?php echo e(asset('storage/' . $r->file_path)); ?>" target="_blank">

                                تحميل

                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>

    </table>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/task_reports/index.blade.php ENDPATH**/ ?>