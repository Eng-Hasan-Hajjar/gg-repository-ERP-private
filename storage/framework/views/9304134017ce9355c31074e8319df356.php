
<?php ($activeModule = 'students'); ?>
<?php $__env->startSection('title', 'تقارير الطلاب'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold">📊 تقارير الطلاب</h4>
        <div class="text-muted small">تصفية متقدمة + تصدير Excel احترافي</div>
    </div>
    <a href="<?php echo e(route('students.index')); ?>" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-right"></i> العودة للطلاب
    </a>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary"><?php echo e($stats['total']); ?></div>
            <div class="text-muted small">إجمالي الطلاب</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success"><?php echo e($stats['active']); ?></div>
            <div class="text-muted small">مستمرون في الدراسة</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-info"><?php echo e($stats['confirmed']); ?></div>
            <div class="text-muted small">مُثبّتون</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning"><?php echo e($stats['today']); ?></div>
            <div class="text-muted small">مضافون اليوم</div>
        </div>
    </div>
</div>


<form class="card card-body border-0 shadow-sm mb-3" method="GET">
    <div class="row g-2">
        <div class="col-12 col-md-3">
            <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
                   placeholder="بحث: الاسم / الرقم / الهاتف">
        </div>
        <div class="col-6 col-md-2">
            <select name="branch_id" class="form-select">
                <option value="">كل الفروع</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="diploma_id" class="form-select">
                <option value="">كل الدبلومات</option>
                <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php if(request('diploma_id') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">كل الحالات</option>
                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-6 col-md-1 d-grid">
            <button class="btn btn-namaa fw-bold">تصفية</button>
        </div>
        <div class="col-12 col-md-2 d-grid">
            <a href="<?php echo e(route('students.reports.index')); ?>" class="btn btn-outline-secondary">إعادة تعيين</a>
        </div>
    </div>
</form>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-excel text-success"></i> تصدير التقارير — تشمل الفلاتر المطبّقة</h6>
        <div class="row g-2">

            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo e(route('students.reports.excel.list', request()->query())); ?>"
                   class="btn btn-success w-100 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">قائمة الطلاب</div>
                        <div style="font-size:11px; opacity:.85;">الاسم، الهاتف، الفرع، الحالة</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo e(route('students.reports.excel.detail', request()->query())); ?>"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#1E40AF; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">التفاصيل الكاملة</div>
                        <div style="font-size:11px; opacity:.85;">الجنسية، التولد، المستوى التعليمي...</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo e(route('students.reports.excel.diplomas', request()->query())); ?>"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#6D28D9; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">حسب الدبلومة</div>
                        <div style="font-size:11px; opacity:.85;">كل طالب مع دبلوماته وحالتها</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="<?php echo e(route('students.reports.excel.crm', request()->query())); ?>"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#DC2626; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">بيانات CRM</div>
                        <div style="font-size:11px; opacity:.85;">المصدر، المرحلة، البلد، الاحتياج</div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold border-0 pt-3">
        معاينة النتائج (<?php echo e($students->total()); ?> طالب)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>الرقم الجامعي</th>
                    <th>الاسم</th>
                    <th>الفرع</th>
                    <th>الدبلومة</th>
                    <th>الحالة</th>
                    <th>تاريخ الإضافة</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><code><?php echo e($s->university_id); ?></code></td>
                    <td class="fw-semibold"><?php echo e($s->full_name); ?></td>
                    <td><?php echo e($s->branch->name ?? '-'); ?></td>
                    <td>
                        <?php $__currentLoopData = $s->diplomas->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-primary me-1"><?php echo e($d->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($s->diplomas->count() > 2): ?>
                            <span class="badge bg-secondary">+<?php echo e($s->diplomas->count() - 2); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-secondary"><?php echo e($statusOptions[$s->status] ?? $s->status); ?></span></td>
                    <td class="text-muted small"><?php echo e($s->created_at->format('Y-m-d')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3"><?php echo e($students->links()); ?></div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/reports/index.blade.php ENDPATH**/ ?>