
<?php $__env->startSection('title','طلب ميديا جديد'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">إرسال طلب لقسم الميديا</h4>
    <a href="<?php echo e(route('media.index')); ?>" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4">

    <form method="POST" action="<?php echo e(route('media.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">اسم مقدم الطلب <span class="text-danger">*</span></label>
                <input type="text" name="requester_name" class="form-control"
                       value="<?php echo e(old('requester_name')); ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="requester_phone" class="form-control"
                       value="<?php echo e(old('requester_phone')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">اسم الدبلومة</label>
                <input type="text" name="diploma_name" class="form-control"
                       value="<?php echo e(old('diploma_name')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">ترميز الدبلومة</label>
                <input type="text" name="diploma_code" class="form-control"
                       value="<?php echo e(old('diploma_code')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">اسم المدرب</label>
                <input type="text" name="trainer_name" class="form-control"
                       value="<?php echo e(old('trainer_name')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">مكان تواجد المدرب</label>
                <input type="text" name="trainer_location" class="form-control"
                       value="<?php echo e(old('trainer_location')); ?>">
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="trainer_photography_available"
                           class="form-check-input" id="trainerPhoto">
                    <label class="form-check-label" for="trainerPhoto">هل تصوير المدرب متاح؟</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">اعتمادية الشهادة</label>
                <input type="text" name="certificate_accreditation" class="form-control"
                       value="<?php echo e(old('certificate_accreditation')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">مسؤول خدمة العملاء</label>
                <input type="text" name="customer_service_responsible" class="form-control"
                       value="<?php echo e(old('customer_service_responsible')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">مكان تنفيذ الدبلومة</label>
                <input type="text" name="diploma_location" class="form-control"
                       value="<?php echo e(old('diploma_location')); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">رفع ملف التفاصيل</label>
                <input type="file" name="details_file" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">رفع صورة المدرب</label>
                <input type="file" name="trainer_image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6" hidden>
                <label class="form-label">موعد نهاية التعديل</label>
                <input type="date" name="editing_deadline" class="form-control"
                       value="<?php echo e(old('editing_deadline')); ?>">
            </div>

            <div class="col-12"><hr></div>

            <div class="col-12">
                <h6 class="fw-bold">المواد المطلوبة</h6>
            </div>

            <div class="col-12">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_ad" class="form-check-input" id="needAd">
                            <label class="form-check-label" for="needAd">إعلان دبلومة</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_invitation" class="form-check-input" id="needInv">
                            <label class="form-check-label" for="needInv">دعوة جلسة افتتاحية</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_review_video" class="form-check-input" id="needRev">
                            <label class="form-check-label" for="needRev">فيديو تقييم</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_content" class="form-check-input" id="needCont">
                            <label class="form-check-label" for="needCont">محتوى</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_podcast" class="form-check-input" id="needPod">
                            <label class="form-check-label" for="needPod">بودكاست قصير</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="need_carousel" class="form-check-input" id="needCar">
                            <label class="form-check-label" for="needCar">كاروسيل</label>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <input type="text" name="need_other" class="form-control"
                               placeholder="أخرى" value="<?php echo e(old('need_other')); ?>">
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">ملاحظات إضافية</label>
                <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes')); ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa w-100">
                    <i class="bi bi-send"></i> إرسال الطلب
                </button>
            </div>

        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/media/create.blade.php ENDPATH**/ ?>