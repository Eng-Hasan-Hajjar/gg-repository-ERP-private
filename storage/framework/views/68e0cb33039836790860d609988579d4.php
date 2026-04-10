
<?php $__env->startSection('title', 'إدارة البرنامج'); ?>






<?php $__env->startSection('content'); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                إدارة البرنامج
            </h4>
            <div class="text-muted small">
                <?php echo e($diploma->name); ?> — <?php echo e($diploma->code); ?>

            </div>
        </div>

        <a href="<?php echo e(route('programs.management.index')); ?>" class="btn btn-soft">
            رجوع
        </a>


        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <strong>يرجى تصحيح الأخطاء التالية قبل الحفظ:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


    </div>

    <form method="POST" action="<?php echo e(route('programs.management.update', $diploma)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="row g-4">

            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم البرامج
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <!-- دراسة السوق – تظهر فقط للحضوري -->
                            <?php if($diploma->type === 'online'): ?>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="market_study" class="form-check-input"
                                        <?php if($record->market_study): echo 'checked'; endif; ?>>
                                    <label class="form-check-label">دراسة السوق</label>
                                </div>
                            <?php endif; ?>
                           

                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="contracts_ready" class="form-check-input"
                                    <?php if($record->contracts_ready): echo 'checked'; endif; ?>>
                                <label class="form-check-label">العقود جاهزة</label>
                            </div>
                            <?php if($diploma->type === 'online'): ?>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="materials_ready" class="form-check-input"
                                        <?php if($record->materials_ready): echo 'checked'; endif; ?>>
                                    <label class="form-check-label">المادة العلمية جاهزة</label>
                                </div>

                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="sessions_uploaded" class="form-check-input"
                                        <?php if($record->sessions_uploaded): echo 'checked'; endif; ?>>
                                    <label class="form-check-label">رفع الجلسات على الموقع</label>
                                </div>
                            <?php endif; ?>


                             <div class="col-md-4">
                                <label class="form-label">المدرب</label>

                                <select name="trainer_id" class="form-control">

                                    <option value="">-- اختر المدرب --</option>

                                    <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($trainer->id); ?>" <?php if($record->trainer_id == $trainer->id): echo 'selected'; endif; ?>>

                                            <?php echo e($trainer->full_name); ?>


                                        </option>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">مصدر الشهادة</label>
                                <input type="text" name="certificate_source" value="<?php echo e($record->certificate_source); ?>"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">

                                <label class="form-label">ملف التفاصيل</label>

                                <input type="file" name="details_file" class="form-control">

                                <?php if($record->details_file): ?>

                                    <a href="<?php echo e(asset('storage/' . $record->details_file)); ?>" target="_blank" class="small">

                                        عرض الملف

                                    </a>

                                <?php endif; ?>

                            </div>

                            <div class="col-md-4">
                                <label class="form-label">سعر الدبلومة</label>
                                <input type="number" step="0.01" name="price" value="<?php echo e($record->price); ?>"
                                    class="form-control">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم الميديا
                    </div>
                    <div class="card-body">
                        <div class="row g-3">






                            <?php
                                $mediaFields = [
                                    'media_form_sent' => 'تم إرسال فورم الميديا',
                                    'direct_ads' => 'إعلان مباشر',
                                    'content_ready' => 'المحتوى جاهز',
                                    'opening_invitation' => 'دعوة افتتاحية',
                                    'opening_snippets' => 'مقتطفات افتتاحية',
                                    'carousel' => 'كاروسيل',
                                    'designs' => 'تصاميم',
                                    'stories' => 'ستوريات'
                                ];
                            ?>

                            <?php $__currentLoopData = $mediaFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="<?php echo e($field); ?>" class="form-check-input"
                                        <?php if($record->$field): echo 'checked'; endif; ?>>
                                    <label class="form-check-label">
                                        <?php echo e($label); ?>

                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم التسويق
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">بداية الحملة</label>
                                <input type="date" name="campaign_start" value="<?php echo e($record->campaign_start); ?>"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">نهاية الحملة</label>
                                <input type="date" name="campaign_end" value="<?php echo e($record->campaign_end); ?>"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">صرف الحملة </label>
                                <input type="number" step="0.01" name="campaign_budget"
                                    value="<?php echo e($record->campaign_budget); ?>" class="form-control">
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">مسؤول التواصل</label>

                                <input type="text" name="communication_manager" value="<?php echo e($record->communication_manager); ?>"
                                    class="form-control">
                            </div>


                            <div class="col-md-3">
                                <label class="form-label">عدد الطلاب المثبتين</label>
                                <input type="number" name="confirmed_students" value="<?php echo e($record->confirmed_students); ?>"
                                    class="form-control">
                            </div>



                        </div>

                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم الامتحانات والوثائق الأكاديمية
                    </div>
                    <div class="card-body">

                        <div class="row g-3">



                            
                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="projects" class="form-check-input" <?php if($record->projects): echo 'checked'; endif; ?>>
                                <label class="form-check-label">
                                    مشاريع
                                </label>
                            </div>



                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="attendance_certificate" class="form-check-input"
                                    <?php if($record->attendance_certificate): echo 'checked'; endif; ?>>
                                <label class="form-check-label">
                                    استلام شهادة الحضور </label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="university_certificate" class="form-check-input"
                                    <?php if($record->university_certificate): echo 'checked'; endif; ?>>
                                <label class="form-check-label">
                                    استلام شهادة الجامعة
                                </label>
                            </div>



                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="cards_ready" class="form-check-input"
                                    <?php if($record->cards_ready): echo 'checked'; endif; ?>>
                                <label class="form-check-label">
                                    البطاقات جاهزة
                                </label>
                            </div>



                            <div class="col-md-3">
                                <label class="form-label">مدة الدبلومة (شهر)</label>
                                <input type="number" name="duration_months" value="<?php echo e($record->duration_months); ?>"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                <?php if($diploma->type === 'online'): ?>
                                    <label class="form-label">عدد الساعات</label>
                                <?php endif; ?>
                                <?php if($diploma->type === 'onsite'): ?>
                                    <label class="form-label">عدد الجلسات</label>
                                <?php endif; ?>
                                <input type="number" name="hours" value="<?php echo e($record->hours); ?>" class="form-control">
                            </div>



                            <div class="col-md-3">
                                <label class="form-label">البداية</label>
                                <input type="date" name="start_date" value="<?php echo e($record->start_date); ?>" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">النهاية</label>
                                <input type="date" name="end_date" value="<?php echo e($record->end_date); ?>" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الامتحان النصفي</label>
                                <input type="date" name="mid_exam" value="<?php echo e($record->mid_exam); ?>" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الامتحان النهائي</label>
                                <input type="date" name="final_exam" value="<?php echo e($record->final_exam); ?>" class="form-control">
                            </div>





                        </div>

                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم شؤون الطلاب
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <?php
                                $studentFields = [

                                    'admin_session_1' => 'جلسة ادارية 1',
                                    'admin_session_2' => 'جلسة ادارية 2',
                                    'admin_session_3' => 'جلسة ادارية 3',
                                    'evaluations_done' => 'التقييمات'
                                ];
                            ?>

                            <?php $__currentLoopData = $studentFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="<?php echo e($field); ?>" class="form-check-input"
                                        <?php if($record->$field): echo 'checked'; endif; ?>>
                                    <label class="form-check-label">
                                        <?php echo e($label); ?>

                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                             <?php if($diploma->type === 'online'): ?>
                            <div class="col-md-3">
                                <label class="form-label">عدد الخريجين</label>
                                <input type="number" name="graduates_count" value="<?php echo e($record->graduates_count); ?>"
                                    class="form-control">
                            </div>
<?php endif; ?>
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" rows="3" class="form-control"><?php echo e($record->notes); ?></textarea>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-namaa px-5 fw-bold">
                حفظ جميع البيانات
            </button>
        </div>

    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/programs_management/edit.blade.php ENDPATH**/ ?>