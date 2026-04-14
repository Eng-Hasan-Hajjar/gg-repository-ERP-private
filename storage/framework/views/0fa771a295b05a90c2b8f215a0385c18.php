
<?php $__env->startSection('title', 'لوحة البرنامج'); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <?php echo e($diploma->name); ?>

            </h4>
            <div class="text-muted small">
                لوحة متابعة شاملة للبرنامج
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('programs.management.edit', $diploma)); ?>" class="btn btn-namaa">
                تعديل البيانات
            </a>

            <a href="<?php echo e(route('programs.management.index')); ?>" class="btn btn-soft">
                رجوع
            </a>
        </div>
    </div>

    <?php

        $fields = collect($record->getAttributes())
            ->only([
                'market_study',
                'trainer_assigned',
                'contracts_ready',
                'materials_ready',
                'sessions_uploaded',
                'media_form_sent',
                'direct_ads',
                'content_ready',
                'opening_invitation',
                'opening_snippets',
                'carousel',
                'designs',
                'stories',
                'projects',
                'attendance_certificate',
                'university_certificate',
                'cards_ready',
                'admin_session_1',
                'admin_session_2',
                'admin_session_3',
                'evaluations_done'
            ]);

        $total = $fields->count();
        $done = $fields->filter(fn($v) => $v == 1)->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

    ?>


    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">
                <strong>نسبة إنجاز البرنامج</strong>
                <span class="fw-bold"><?php echo e($progress); ?>%</span>
            </div>

            <div class="progress" style="height:10px;">
                <div class="progress-bar bg-success" style="width: <?php echo e($progress); ?>%">
                </div>
            </div>

        </div>
    </div>


    
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">السعر</div>
                <div class="fw-bold fs-5">
                    <?php echo e($record->price ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">الطلاب المثبتين</div>
                <div class="fw-bold fs-5">
                    <?php echo e($record->confirmed_students ?? 0); ?>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">الخريجين</div>
                <div class="fw-bold fs-5">
                    <?php echo e($record->graduates_count ?? 0); ?>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">مدة الدبلومة</div>
                <div class="fw-bold fs-5">
                    <?php echo e($record->duration_months ?? '-'); ?> شهر
                </div>
            </div>
        </div>

    </div>


    
    <div class="row g-4">

        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم البرامج
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            دراسة السوق :
                            <strong><?php echo e($record->market_study ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">

                            المدرب :

                            <strong>

                                <?php echo e($record->trainer?->full_name ?? '-'); ?>


                            </strong>

                        </li>

                        <li class="list-group-item">
                            العقود :
                            <strong><?php echo e($record->contracts_ready ? 'جاهزة' : 'غير جاهزة'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            المادة العلمية :
                            <strong><?php echo e($record->materials_ready ? 'جاهزة' : 'غير جاهزة'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            رفع الجلسات :
                            <strong><?php echo e($record->sessions_uploaded ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            مصدر الشهادة :
                            <strong><?php echo e($record->certificate_source ?? '-'); ?></strong>
                        </li>

                        <?php if($record->details_file): ?>

                            <a href="<?php echo e(asset('storage/' . $record->details_file)); ?>" target="_blank"
                                class="btn btn-sm btn-outline-success">

                                تحميل ملف التفاصيل

                            </a>

                        <?php else: ?>

                            <span>-</span>

                        <?php endif; ?>

                    </ul>

                </div>
            </div>
        </div>


        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم الميديا
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            فورم الميديا :
                            <strong><?php echo e($record->media_form_sent ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            إعلانات :
                            <strong><?php echo e($record->direct_ads ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            المحتوى :
                            <strong><?php echo e($record->content_ready ? 'جاهز' : 'غير جاهز'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            دعوة افتتاحية :
                            <strong><?php echo e($record->opening_invitation ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            كاروسيل :
                            <strong><?php echo e($record->carousel ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            تصاميم :
                            <strong><?php echo e($record->designs ? 'تم' : 'لا'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            ستوريات :
                            <strong><?php echo e($record->stories ? 'تم' : 'لا'); ?></strong>
                        </li>

                    </ul>

                </div>
            </div>
        </div>


        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم التسويق
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            بداية الحملة :
                            <strong><?php echo e($record->campaign_start ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            نهاية الحملة :
                            <strong><?php echo e($record->campaign_end ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            ميزانية الحملة :
                            <strong><?php echo e($record->campaign_budget ?? '-'); ?></strong>
                        </li>

                    </ul>

                </div>
            </div>
        </div>


        
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    الامتحانات
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            البداية :
                            <strong><?php echo e($record->start_date ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            النهاية :
                            <strong><?php echo e($record->end_date ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            الامتحان النصفي :
                            <strong><?php echo e($record->mid_exam ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            الامتحان النهائي :
                            <strong><?php echo e($record->final_exam ?? '-'); ?></strong>
                        </li>

                        <li class="list-group-item">
                            مشاريع :
                            <strong><?php echo e($record->projects ? 'نعم' : 'لا'); ?></strong>
                        </li>

                    </ul>

                </div>
            </div>
        </div>


        
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">شؤون الطلاب</div>
                <div class="card-body small">

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            الحضور: <strong><?php echo e($record->attendance_certificate ? 'تم' : 'لا'); ?></strong>
                        </div>
                        <div class="col-md-3">
                            شهادة الجامعة: <strong><?php echo e($record->university_certificate ? 'تم' : 'لا'); ?></strong>
                        </div>
                        <div class="col-md-3">
                            البطاقات: <strong><?php echo e($record->cards_ready ? 'جاهزة' : 'لا'); ?></strong>
                        </div>
                    </div>

                    
                    <?php
                        $sessionShowFields = [
                            'admin_session_1' => ['label' => 'جلسة ادارية و تقييمية  1', 'link' => 'admin_session_1_link'],
                            'admin_session_2' => ['label' => 'جلسة ادارية و تقييمية  2', 'link' => 'admin_session_2_link'],
                            'admin_session_3' => ['label' => 'جلسة ادارية و تقييمية  3', 'link' => 'admin_session_3_link'],
                            'evaluations_done' => ['label' => 'جلسة ادارية و تقييمية', 'link' => 'evaluations_done_link'],
                        ];
                      ?>

                    <div class="row g-3 mb-3">
                        <?php $__currentLoopData = $sessionShowFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3">
                                <div
                                    style="background:rgba(248,250,252,.9); border:1px solid rgba(226,232,240,.9); border-radius:10px; padding:10px 12px;">
                                    <div style="font-weight:800; font-size:13px; margin-bottom:4px;">
                                        <?php echo e($info['label']); ?>

                                    </div>
                                    <div style="font-size:13px;">
                                        <?php if($record->$field): ?>
                                            <span class="badge bg-success">تم ✓</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">لا</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($record->$field && $record->{$info['link']}): ?>
                                        <div style="margin-top:6px;">
                                            <a href="<?php echo e($record->{$info['link']}); ?>" target="_blank"
                                                style="font-size:11px; font-weight:800; color:#0ea5e9; text-decoration:none;">
                                                <i class="bi bi-link-45deg"></i> فتح الرابط
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-2">
                        <strong>ملاحظات:</strong><br>
                        <?php echo e($record->notes ?? '-'); ?>

                    </div>

                </div>
            </div>
        </div>



    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/programs_management/show.blade.php ENDPATH**/ ?>