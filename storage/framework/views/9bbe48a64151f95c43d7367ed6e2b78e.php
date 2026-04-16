
<?php $__env->startSection('title','تعديل سجل نشر'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">تعديل سجل النشر #<?php echo e($publish->id); ?></h4>
    <a href="<?php echo e(route('media.publish.index')); ?>" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4">
    <form method="POST" action="<?php echo e(route('media.publish.update', $publish)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">ربط بطلب ميديا (اختياري)</label>
                <select name="media_request_id" class="form-select">
                    <option value="">— بدون ربط —</option>
                    <?php $__currentLoopData = $mediaRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mr->id); ?>" <?php echo e($publish->media_request_id == $mr->id ? 'selected' : ''); ?>>
                            #<?php echo e($mr->id); ?> — <?php echo e($mr->diploma_name ?? $mr->requester_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">الدبلومة <span class="text-danger">*</span></label>
                <input type="text" name="diploma_name" class="form-control"
                       value="<?php echo e(old('diploma_name', $publish->diploma_name)); ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">المحتوى <span class="text-danger">*</span></label>
                <select name="content_category" class="form-select" required>
                    <option value="ad" <?php echo e($publish->content_category == 'ad' ? 'selected' : ''); ?>>إعلان</option>
                    <option value="invitation" <?php echo e($publish->content_category == 'invitation' ? 'selected' : ''); ?>>دعوة</option>
                    <option value="content" <?php echo e($publish->content_category == 'content' ? 'selected' : ''); ?>>محتوى</option>
                    <option value="review" <?php echo e($publish->content_category == 'review' ? 'selected' : ''); ?>>تقييم</option>
                    <option value="general_content" <?php echo e($publish->content_category == 'general_content' ? 'selected' : ''); ?>>محتوى عام</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">نوع المحتوى <span class="text-danger">*</span></label>
                <select name="content_type" class="form-select" required>
                    <option value="design" <?php echo e($publish->content_type == 'design' ? 'selected' : ''); ?>>تصميم</option>
                    <option value="video" <?php echo e($publish->content_type == 'video' ? 'selected' : ''); ?>>فيديو</option>
                    <option value="carousel" <?php echo e($publish->content_type == 'carousel' ? 'selected' : ''); ?>>كاروسيل</option>
                </select>
            </div>

<div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch" class="form-select">
        <option value="">— اختر الفرع —</option>
        <?php $__currentLoopData = [
            'DE'  => 'ألمانيا',
            'IST' => 'اسطنبول',
            'MRS' => 'مرسين',
            'BRS' => 'بورصة',
            'KLS' => 'كليس',
            'ANT' => 'عنتاب',
            'ONL' => 'أونلاين',
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($code); ?>" <?php if(old('branch', $publish->branch) == $code): echo 'selected'; endif; ?>>
                <?php echo e($name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

           
<div class="col-md-6">
    <label class="form-label fw-bold">رابط المحتوى</label>
    <input type="url" name="content_link" class="form-control"
           value="<?php echo e(old('content_link', $publish->content_link)); ?>"
           placeholder="https://...">
</div>




            <div class="col-12">
                <label class="form-label fw-bold">كابشن</label>
                <textarea name="caption" class="form-control" rows="3"><?php echo e(old('caption', $publish->caption)); ?></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">تاريخ النشر</label>
                <input type="date" name="publish_date" class="form-control"
                       value="<?php echo e(old('publish_date', $publish->publish_date?->format('Y-m-d'))); ?>">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">تم النشر</label>
                <div class="d-flex gap-4 flex-wrap">
                    <div class="form-check">
                        <input type="checkbox" name="published_meta" class="form-check-input" id="pubMeta"
                               <?php echo e($publish->published_meta ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="pubMeta">
                            <i class="bi bi-meta"></i> نشر ميتا
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_tiktok" class="form-check-input" id="pubTiktok"
                               <?php echo e($publish->published_tiktok ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="pubTiktok">
                            <i class="bi bi-tiktok"></i> نشر تيك توك
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_youtube" class="form-check-input" id="pubYoutube"
                               <?php echo e($publish->published_youtube ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="pubYoutube">
                            <i class="bi bi-youtube"></i> نشر يوتوب
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> تحديث سجل النشر
                </button>
            </div>

        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/media/publish_edit.blade.php ENDPATH**/ ?>