<?php if($results->isEmpty()): ?>
    <div class="alert alert-info">لا توجد نتائج امتحانات مسجلة لهذا الطالب.</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>الامتحان</th>
                <th>الدبلومة</th>
                <th>التاريخ</th>
                <th>الدرجة</th>
                <th>النتيجة</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($r->exam->title ?? '-'); ?></td>
                <td><?php echo e($r->exam->diploma->name ?? '-'); ?></td>
                <td><?php echo e($r->exam->exam_date ?? '-'); ?></td>
                <td class="fw-bold"><?php echo e($r->score ?? '-'); ?></td>
                <td>
                    <?php if($r->passed): ?>
                        <span class="badge bg-success">ناجح</span>
                    <?php else: ?>
                        <span class="badge bg-danger">راسب</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php endif; ?><?php /**PATH C:\Users\engya\Desktop\مواقع الزبائن\namaa\laravel11-auth\resources\views/students/modals/exams.blade.php ENDPATH**/ ?>