
<?php $__env->startSection('title','تفاصيل الطلب'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">تفاصيل طلب الميديا #<?php echo e($media->id); ?></h4>
    <a href="<?php echo e(route('media.index')); ?>" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4 mb-4">

    <div class="row g-3">

        <div class="col-md-6">
            <strong>اسم مقدم الطلب:</strong>
            <div><?php echo e($media->requester_name); ?></div>
        </div>

        <div class="col-md-6">
            <strong>رقم الهاتف:</strong>
            <div><?php echo e($media->requester_phone ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>اسم الدبلومة:</strong>
            <div><?php echo e($media->diploma_name ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>ترميز الدبلومة:</strong>
            <div><?php echo e($media->diploma_code ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>اسم المدرب:</strong>
            <div><?php echo e($media->trainer_name ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>مكان المدرب:</strong>
            <div><?php echo e($media->trainer_location ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>اعتمادية الشهادة:</strong>
            <div><?php echo e($media->certificate_accreditation ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>مسؤول خدمة العملاء:</strong>
            <div><?php echo e($media->customer_service_responsible ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>مكان تنفيذ الدبلومة:</strong>
            <div><?php echo e($media->diploma_location ?? '—'); ?></div>
        </div>

        <div class="col-md-6">
            <strong>تصوير المدرب متاح؟</strong>
            <div>
                <?php if($media->trainer_photography_available): ?>
                    <span class="badge bg-success">نعم</span>
                <?php else: ?>
                    <span class="badge bg-secondary">لا</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <strong>ملف التفاصيل:</strong>
            <div>
                <?php if($media->details_file): ?>
                    <a href="<?php echo e(asset('storage/'.$media->details_file)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-arrow-down"></i> عرض الملف
                    </a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <strong>صورة المدرب:</strong>
            <div>
                <?php if($media->trainer_image): ?>
                    <img src="<?php echo e(asset('storage/'.$media->trainer_image)); ?>"
                         width="120" class="rounded shadow-sm">
                <?php else: ?>
                    —
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12">
            <hr>
            <h5 class="fw-bold">المواد المطلوبة</h5>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php if($media->need_ad): ?>
                    <span class="badge badge-namaa">إعلان دبلومة</span>
                <?php endif; ?>
                <?php if($media->need_invitation): ?>
                    <span class="badge badge-namaa">دعوة افتتاحية</span>
                <?php endif; ?>
                <?php if($media->need_review_video): ?>
                    <span class="badge badge-namaa">فيديو تقييم</span>
                <?php endif; ?>
                <?php if($media->need_content): ?>
                    <span class="badge badge-namaa">محتوى</span>
                <?php endif; ?>
                <?php if($media->need_podcast): ?>
                    <span class="badge badge-namaa">بودكاست</span>
                <?php endif; ?>
                <?php if($media->need_carousel): ?>
                    <span class="badge badge-namaa">كاروسيل</span>
                <?php endif; ?>
                <?php if($media->need_other): ?>
                    <span class="badge badge-namaa"><?php echo e($media->need_other); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12">
            <strong>ملاحظات:</strong>
            <div><?php echo e($media->notes ?? '—'); ?></div>
        </div>

    </div>
</div>


<div class="card shadow-sm p-4 mb-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-pencil-square"></i> تحديث الحالة
    </h5>

    <form method="POST" action="<?php echo e(route('media.update', $media)); ?>">
        <?php echo csrf_field(); ?>

        <div class="row g-3">

            
            <div class="col-md-6">
                <label class="form-label fw-bold">رابط المحتوى</label>
                <input type="url" name="content_link"
                       class="form-control"
                       value="<?php echo e($media->content_link); ?>"
                       placeholder="https://docs.google.com/...">
                <small class="text-muted">يمكن لكاتبة المحتوى تعديل هذا الحقل</small>
            </div>

            
            <div class="col-md-6">
                <label class="form-label fw-bold">موعد نهاية التعديل</label>
                <input type="date" name="editing_deadline"
                       class="form-control"
                       value="<?php echo e($media->editing_deadline?->format('Y-m-d')); ?>">
            </div>

            
            <div class="col-12">
                <label class="form-label fw-bold">حالة التنفيذ</label>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="design_done" class="form-check-input"
                                   <?php echo e($media->design_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">التصميم</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="ad_done" class="form-check-input"
                                   <?php echo e($media->ad_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">الإعلان</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="invitation_done" class="form-check-input"
                                   <?php echo e($media->invitation_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">الدعوة</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="content_done" class="form-check-input"
                                   <?php echo e($media->content_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">المحتوى</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="podcast_done" class="form-check-input"
                                   <?php echo e($media->podcast_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">البودكاست</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" name="reviews_done" class="form-check-input"
                                   <?php echo e($media->reviews_done ? 'checked' : ''); ?>>
                            <label class="form-check-label">التقييمات</label>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <label class="form-label fw-bold">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2"><?php echo e($media->notes); ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> حفظ التحديث
                </button>
            </div>
        </div>
    </form>
</div>


<?php if($media->publishEntries->count()): ?>
<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-calendar2-week"></i> سجلات النشر
    </h5>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>الدبلومة</th>
                    <th>المحتوى</th>
                    <th>النوع</th>
                    <th>الفرع</th>
                    <th>ميتا</th>
                    <th>تيك توك</th>
                    <th>يوتوب</th>
                    <th>تاريخ النشر</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $media->publishEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($entry->diploma_name); ?></td>
                    <td><?php echo e($entry->content_category_label); ?></td>
                    <td><?php echo e($entry->content_type_label); ?></td>
                    <td><?php echo e($entry->branch ?? '—'); ?></td>
                    <td><?php echo $entry->published_meta ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>'; ?></td>
                    <td><?php echo $entry->published_tiktok ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>'; ?></td>
                    <td><?php echo $entry->published_youtube ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>'; ?></td>
                    <td><?php echo e($entry->publish_date?->format('Y-m-d') ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\media\show.blade.php ENDPATH**/ ?>