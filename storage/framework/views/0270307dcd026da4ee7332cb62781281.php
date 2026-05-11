
<?php $__env->startSection('title', 'التقويم'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-calendar3 text-primary"></i> التقويم</h4>
        <div class="text-muted small">الجلسات — الحملات — أعياد الميلاد — التذكيرات</div>
    </div>
    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="bi bi-plus-lg"></i> حدث جديد
    </button>
</div>


<div class="d-flex align-items-center gap-3 mb-3">
    <?php
        $prev = \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m');
        $next = \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m');
    ?>
    <a href="<?php echo e(route('calendar.index', ['month' => $prev])); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-right"></i>
    </a>
    <h5 class="mb-0 fw-bold"><?php echo e($monthObj->locale('ar')->translatedFormat('F Y')); ?></h5>
    <a href="<?php echo e(route('calendar.index', ['month' => $next])); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-left"></i>
    </a>
    <a href="<?php echo e(route('calendar.index')); ?>" class="btn btn-outline-primary btn-sm">اليوم</a>
</div>


<div class="d-flex flex-wrap gap-2 mb-3">
    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="badge rounded-pill" style="background:<?php echo e($type['color']); ?>; font-size:12px; padding:6px 12px;">
            <i class="bi <?php echo e($type['icon']); ?>"></i> <?php echo e($type['label']); ?>

        </span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-body p-2">
        <div class="row g-0" style="display:grid; grid-template-columns: repeat(7, 1fr);">
            
            <?php $__currentLoopData = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center fw-bold py-2 border-bottom text-muted" style="font-size:12px;"><?php echo e($day); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php for($i = 0; $i < $monthObj->copy()->startOfMonth()->dayOfWeek; $i++): ?>
                <div class="border p-1" style="min-height:100px; background:#f8fafc;"></div>
            <?php endfor; ?>

            
            <?php for($d = 1; $d <= $monthObj->daysInMonth; $d++): ?>
                <?php
                    $dateStr  = $monthObj->format('Y-m') . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
                    $isToday  = $dateStr === now()->toDateString();
                    $dayEvents = $eventsByDay[$dateStr] ?? [];
                ?>
                <div class="border p-1 <?php echo e($isToday ? 'bg-primary bg-opacity-10 border-primary' : ''); ?>"
                     style="min-height:100px;">
                    <div class="fw-bold mb-1 <?php echo e($isToday ? 'text-primary' : 'text-muted'); ?>" style="font-size:13px;">
                        <?php echo e($d); ?>

                        <?php if($isToday): ?>
                            <span class="badge bg-primary" style="font-size:9px;">اليوم</span>
                        <?php endif; ?>
                    </div>
                    <?php $__currentLoopData = $dayEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded px-1 py-0 mb-1 d-flex align-items-center justify-content-between"
                             style="background:<?php echo e($ev->color); ?>22; border-right:3px solid <?php echo e($ev->color); ?>; font-size:11px; cursor:pointer;"
                             title="<?php echo e($ev->description); ?>"
                             data-bs-toggle="modal"
                             data-bs-target="#eventDetailModal"
                             data-id="<?php echo e($ev->id); ?>"
                             data-title="<?php echo e($ev->title); ?>"
                             data-desc="<?php echo e($ev->description); ?>"
                             data-type="<?php echo e($types[$ev->type]['label']); ?>"
                             data-date="<?php echo e($ev->start_date->format('Y-m-d')); ?>"
                             data-time="<?php echo e($ev->start_time ?? ''); ?>"
                             data-creator="<?php echo e($ev->creator->name ?? '-'); ?>">
                            <span style="color:<?php echo e($ev->color); ?>; font-weight:700;">
                                <?php echo e(Str::limit($ev->title, 15)); ?>

                            </span>
                            <form method="POST" action="<?php echo e(route('calendar.destroy', $ev)); ?>" class="d-inline"
                                  onsubmit="return confirm('حذف الحدث؟')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-link p-0 text-danger" style="font-size:10px;" title="حذف">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mt-4">
    <div class="card-header fw-bold bg-white border-0">
        <i class="bi bi-list-ul text-primary"></i> أحداث هذا الشهر
    </div>
    <div class="card-body p-0">
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:36px; height:36px; background:<?php echo e($ev->color); ?>22;">
                <i class="bi <?php echo e($types[$ev->type]['icon']); ?>" style="color:<?php echo e($ev->color); ?>;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold" style="font-size:14px;"><?php echo e($ev->title); ?></div>
                <div class="text-muted small">
                    <?php echo e($ev->start_date->format('d/m/Y')); ?>

                    <?php if($ev->start_time): ?> — <?php echo e($ev->start_time); ?> <?php endif; ?>
                    • <?php echo e($types[$ev->type]['label']); ?>

                </div>
            </div>
            <form method="POST" action="<?php echo e(route('calendar.destroy', $ev)); ?>"
                  onsubmit="return confirm('حذف الحدث؟')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-4">لا توجد أحداث هذا الشهر</div>
        <?php endif; ?>
    </div>
</div>


<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary"></i> إضافة حدث</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('calendar.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الحدث *</label>
                        <input name="title" class="form-control" required placeholder="مثال: جلسة دبلومة X">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">النوع *</label>
                        <select name="type" class="form-select" id="eventTypeSelect">
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($type['label']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">تاريخ البداية *</label>
                            <input type="date" name="start_date" class="form-control" required
                                   value="<?php echo e(now()->toDateString()); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">تاريخ النهاية</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">وقت البداية</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">وقت النهاية</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="تفاصيل إضافية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-lg"></i> حفظ
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="eventDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>النوع:</strong> <span id="detailType"></span></p>
                <p><strong>التاريخ:</strong> <span id="detailDate"></span></p>
                <p><strong>الوقت:</strong> <span id="detailTime"></span></p>
                <p><strong>الوصف:</strong> <span id="detailDesc"></span></p>
                <p><strong>أضافه:</strong> <span id="detailCreator"></span></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-target="#eventDetailModal"]').forEach(function(el) {
        el.addEventListener('click', function() {
            document.getElementById('detailTitle').textContent   = this.dataset.title;
            document.getElementById('detailType').textContent    = this.dataset.type;
            document.getElementById('detailDate').textContent    = this.dataset.date;
            document.getElementById('detailTime').textContent    = this.dataset.time || '—';
            document.getElementById('detailDesc').textContent    = this.dataset.desc || '—';
            document.getElementById('detailCreator').textContent = this.dataset.creator;
        });
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/calendar/index.blade.php ENDPATH**/ ?>