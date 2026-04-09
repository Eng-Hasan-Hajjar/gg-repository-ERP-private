
<?php $__env->startSection('title','إضافة سجل نشر'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">إضافة سجل نشر جديد</h4>
    <a href="<?php echo e(route('media.publish.index')); ?>" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4">
    <form method="POST" action="<?php echo e(route('media.publish.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="row g-3">

            
            <div class="col-md-6">
                <label class="form-label fw-bold">ربط بطلب ميديا (اختياري)</label>
                <select name="media_request_id" class="form-select">
                    <option value="">— بدون ربط —</option>
                    <?php $__currentLoopData = $mediaRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mr->id); ?>" <?php echo e(old('media_request_id') == $mr->id ? 'selected' : ''); ?>>
                            #<?php echo e($mr->id); ?> — <?php echo e($mr->diploma_name ?? $mr->requester_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="col-md-6">
                <label class="form-label fw-bold">الدبلومة <span class="text-danger">*</span></label>
                <input type="text" name="diploma_name" class="form-control"
                       value="<?php echo e(old('diploma_name')); ?>" required>
            </div>

            
            <div class="col-md-4">
                <label class="form-label fw-bold">المحتوى <span class="text-danger">*</span></label>
                <select name="content_category" class="form-select" required>
                    <option value="">اختر...</option>
                    <option value="ad" <?php echo e(old('content_category') == 'ad' ? 'selected' : ''); ?>>إعلان</option>
                    <option value="invitation" <?php echo e(old('content_category') == 'invitation' ? 'selected' : ''); ?>>دعوة</option>
                    <option value="content" <?php echo e(old('content_category') == 'content' ? 'selected' : ''); ?>>محتوى</option>
                    <option value="review" <?php echo e(old('content_category') == 'review' ? 'selected' : ''); ?>>تقييم</option>
                    <option value="general_content" <?php echo e(old('content_category') == 'general_content' ? 'selected' : ''); ?>>محتوى عام</option>
                </select>
            </div>

            
            <div class="col-md-4">
                <label class="form-label fw-bold">نوع المحتوى <span class="text-danger">*</span></label>
                <select name="content_type" class="form-select" required>
                    <option value="">اختر...</option>
                    <option value="design" <?php echo e(old('content_type') == 'design' ? 'selected' : ''); ?>>تصميم</option>
                    <option value="video" <?php echo e(old('content_type') == 'video' ? 'selected' : ''); ?>>فيديو</option>
                    <option value="carousel" <?php echo e(old('content_type') == 'carousel' ? 'selected' : ''); ?>>كاروسيل</option>
                </select>
            </div>

            
            <div class="col-md-4">
                <label class="form-label fw-bold">الفرع</label>
                <input type="text" name="branch" class="form-control"
                       value="<?php echo e(old('branch')); ?>" placeholder="اسم الفرع">
            </div>

            
            <div class="col-12">
                <label class="form-label fw-bold">كابشن</label>
                <textarea name="caption" class="form-control" rows="3"
                          placeholder="نص الكابشن للنشر..."><?php echo e(old('caption')); ?></textarea>
            </div>

            
            <div class="col-md-4">
                <label class="form-label fw-bold">تاريخ النشر</label>
                <input type="date" name="publish_date" class="form-control"
                       value="<?php echo e(old('publish_date')); ?>">
            </div>

            
            <div class="col-12">
                <label class="form-label fw-bold">تم النشر</label>
                <div class="d-flex gap-4 flex-wrap">
                    <div class="form-check">
                        <input type="checkbox" name="published_meta" class="form-check-input" id="pubMeta">
                        <label class="form-check-label" for="pubMeta">
                            <i class="bi bi-meta"></i> نشر ميتا (فيسبوك / انستغرام)
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_tiktok" class="form-check-input" id="pubTiktok">
                        <label class="form-check-label" for="pubTiktok">
                            <i class="bi bi-tiktok"></i> نشر تيك توك
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_youtube" class="form-check-input" id="pubYoutube">
                        <label class="form-check-label" for="pubYoutube">
                            <i class="bi bi-youtube"></i> نشر يوتوب
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> حفظ سجل النشر
                </button>
            </div>

        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/media/publish_create.blade.php ENDPATH**/ ?>