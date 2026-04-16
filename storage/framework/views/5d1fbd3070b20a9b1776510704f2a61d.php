
<?php $__env->startSection('title','قائمة النشر'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-calendar2-week"></i> قائمة النشر
    </h4>

    <div class="d-flex gap-2">
        <a href="<?php echo e(route('media.index')); ?>" class="btn btn-soft">
            <i class="bi bi-arrow-right"></i> طلبات الميديا
        </a>
        <a href="<?php echo e(route('media.publish.create')); ?>" class="btn btn-namaa">
            <i class="bi bi-plus-lg"></i> إضافة سجل نشر
        </a>
    </div>
</div>


<div class="card shadow-sm p-3 mb-3">
    <form method="GET" action="<?php echo e(route('media.publish.index')); ?>" class="row g-2 align-items-end">

        <div class="col-md-3">
            <label class="form-label small fw-bold">الدبلومة</label>
            <input type="text" name="diploma_name" class="form-control form-control-sm"
                   value="<?php echo e(request('diploma_name')); ?>" placeholder="بحث...">
        </div>

        <div class="col-md-2">
            <label class="form-label small fw-bold">المحتوى</label>
            <select name="content_category" class="form-select form-select-sm">
                <option value="">الكل</option>
                <option value="ad" <?php echo e(request('content_category') == 'ad' ? 'selected' : ''); ?>>إعلان</option>
                <option value="invitation" <?php echo e(request('content_category') == 'invitation' ? 'selected' : ''); ?>>دعوة</option>
                <option value="content" <?php echo e(request('content_category') == 'content' ? 'selected' : ''); ?>>محتوى</option>
                <option value="review" <?php echo e(request('content_category') == 'review' ? 'selected' : ''); ?>>تقييم</option>
                <option value="general_content" <?php echo e(request('content_category') == 'general_content' ? 'selected' : ''); ?>>محتوى عام</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label small fw-bold">النوع</label>
            <select name="content_type" class="form-select form-select-sm">
                <option value="">الكل</option>
                <option value="design" <?php echo e(request('content_type') == 'design' ? 'selected' : ''); ?>>تصميم</option>
                <option value="video" <?php echo e(request('content_type') == 'video' ? 'selected' : ''); ?>>فيديو</option>
                <option value="carousel" <?php echo e(request('content_type') == 'carousel' ? 'selected' : ''); ?>>كاروسيل</option>
            </select>
        </div>

       <div class="col-md-2">
    <label class="form-label small fw-bold">الفرع</label>
    <select name="branch" class="form-select form-select-sm">
        <option value="">الكل</option>
        <?php $__currentLoopData = [
            'DE'  => 'ألمانيا',
            'IST' => 'اسطنبول',
            'MRS' => 'مرسين',
            'BRS' => 'بورصة',
            'KLS' => 'كليس',
            'ANT' => 'عنتاب',
            'ONL' => 'أونلاين',
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($code); ?>" <?php echo e(request('branch') == $code ? 'selected' : ''); ?>>
                <?php echo e($name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

        <div class="col-md-auto">
            <button type="submit" class="btn btn-sm btn-namaa">
                <i class="bi bi-funnel"></i> تصفية
            </button>
            <a href="<?php echo e(route('media.publish.index')); ?>" class="btn btn-sm btn-soft">مسح</a>
        </div>

    </form>
</div>


<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الدبلومة</th>
                    <th>المحتوى</th>
                    <th>النوع</th>
                    <th>الفرع</th>
                    <th class="hide-mobile">كابشن</th>
                    <th>ميتا</th>
                    <th>تيك توك</th>
                    <th>يوتوب</th>
                    <th>تاريخ النشر</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($entry->id); ?></td>
                    <td><?php echo e($entry->diploma_name); ?></td>
                    <td>
                        <span class="badge badge-namaa"><?php echo e($entry->content_category_label); ?></span>
                    </td>
                    <td><?php echo e($entry->content_type_label); ?></td>
                    <td>
    <?php
        $branchNames = [
            'DE'  => 'ألمانيا',
            'IST' => 'اسطنبول',
            'MRS' => 'مرسين',
            'BRS' => 'بورصة',
            'KLS' => 'كليس',
            'ANT' => 'عنتاب',
            'ONL' => 'أونلاين',
        ];
    ?>
    <?php echo e($branchNames[$entry->branch] ?? '—'); ?>

</td>?? '—' }}</td>
                    <td class="hide-mobile">
                        <?php echo e(Str::limit($entry->caption, 40) ?? '—'); ?>

                    </td>
                    <td><?php echo $entry->published_meta ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-circle text-muted"></i>'; ?></td>
                    <td><?php echo $entry->published_tiktok ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-circle text-muted"></i>'; ?></td>
                    <td><?php echo $entry->published_youtube ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-circle text-muted"></i>'; ?></td>
                    <td><?php echo e($entry->publish_date?->format('Y-m-d') ?? '—'); ?></td>
                    <td class="actions-cell">
                        <div class="d-flex gap-1 flex-nowrap">
                            
                            <button type="button"
                                    class="btn btn-sm btn-outline-info btn-show-details"
                                    title="عرض التفاصيل"
                                    data-bs-toggle="modal"
                                    data-bs-target="#publishModal"
                                    data-id="<?php echo e($entry->id); ?>"
                                    data-diploma="<?php echo e($entry->diploma_name); ?>"
                                    data-category="<?php echo e($entry->content_category_label); ?>"
                                    data-type="<?php echo e($entry->content_type_label); ?>"
                                    data-branch="<?php echo e($branchNames[$entry->branch] ?? '—'); ?>"
                                    data-caption="<?php echo e($entry->caption ?? '—'); ?>"
                                    data-meta="<?php echo e($entry->published_meta ? '1' : '0'); ?>"
                                    data-tiktok="<?php echo e($entry->published_tiktok ? '1' : '0'); ?>"
                                    data-youtube="<?php echo e($entry->published_youtube ? '1' : '0'); ?>"
                                    data-date="<?php echo e($entry->publish_date?->format('Y-m-d') ?? '—'); ?>"
                                    data-created="<?php echo e($entry->created_at?->format('Y-m-d H:i')); ?>"
                                    data-request-id="<?php echo e($entry->media_request_id); ?>"
                                    data-request-name="<?php echo e($entry->mediaRequest?->requester_name ?? '—'); ?>"
                                    data-content-link="<?php echo e($entry->content_link ?? ''); ?>">
                                <i class="bi bi-eye"></i>
                            </button>

                            <a href="<?php echo e(route('media.publish.edit', $entry)); ?>"
                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST" action="<?php echo e(route('media.publish.destroy', $entry)); ?>"
                                  class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                        لا توجد سجلات نشر حالياً
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($entries->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($entries->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>
</div>





<div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:22px; overflow:hidden; box-shadow: 0 30px 80px rgba(2,6,23,.18);">

            
            <div class="modal-header border-0 text-white px-4 py-3"
                 style="background: linear-gradient(90deg, #0ea5e9 0%, #10b981 100%);">
                <h5 class="modal-title fw-bold" id="publishModalLabel">
                    <i class="bi bi-megaphone-fill me-2"></i>
                    تفاصيل سجل النشر
                    <span class="badge bg-white text-dark rounded-pill ms-2 fw-bold" id="modalId"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>

            
            <div class="modal-body p-4">

                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-mortarboard-fill text-primary"></i> الدبلومة
                            </div>
                            <div class="publish-detail-value" id="modalDiploma"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-link-45deg text-info"></i> طلب الميديا المرتبط
                            </div>
                            <div class="publish-detail-value" id="modalRequest"></div>
                        </div>
                    </div>
                </div>

                
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-tag-fill text-success"></i> المحتوى
                            </div>
                            <div class="publish-detail-value" id="modalCategory"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-palette-fill" style="color:#6366f1"></i> نوع المحتوى
                            </div>
                            <div class="publish-detail-value" id="modalType"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-building text-warning"></i> الفرع
                            </div>
                            <div class="publish-detail-value" id="modalBranch"></div>
                        </div>
                    </div>
                </div>

                
                <div class="mb-3">
                    <div class="publish-detail-box">
                        <div class="publish-detail-label">
                            <i class="bi bi-chat-quote-fill text-secondary"></i> كابشن
                        </div>
                        <div class="publish-detail-value publish-caption" id="modalCaption"></div>
                    </div>
                </div>



                
            <div class="mb-3" id="contentLinkBox">
                <div class="publish-detail-box">
                    <div class="publish-detail-label">
                        <i class="bi bi-link-45deg text-primary"></i> رابط المحتوى
                    </div>
                    <div class="publish-detail-value" id="modalContentLink"></div>
                </div>
            </div>
                
                <div class="mb-3">
                    <div class="publish-detail-label mb-2" style="font-size:.85rem; font-weight:800; color:#64748b; padding-right:4px;">
                        <i class="bi bi-broadcast text-danger"></i> حالة النشر على المنصات
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="publish-platform-chip" id="chipMeta">
                            <span class="publish-platform-icon"><i class="bi bi-meta"></i></span>
                            <span class="publish-platform-name">ميتا</span>
                            <span class="publish-platform-status" id="statusMeta"></span>
                        </div>
                        <div class="publish-platform-chip" id="chipTiktok">
                            <span class="publish-platform-icon"><i class="bi bi-tiktok"></i></span>
                            <span class="publish-platform-name">تيك توك</span>
                            <span class="publish-platform-status" id="statusTiktok"></span>
                        </div>
                        <div class="publish-platform-chip" id="chipYoutube">
                            <span class="publish-platform-icon"><i class="bi bi-youtube"></i></span>
                            <span class="publish-platform-name">يوتوب</span>
                            <span class="publish-platform-status" id="statusYoutube"></span>
                        </div>
                    </div>
                </div>

                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-calendar-event text-primary"></i> تاريخ النشر
                            </div>
                            <div class="publish-detail-value" id="modalDate"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="publish-detail-box">
                            <div class="publish-detail-label">
                                <i class="bi bi-clock-history text-muted"></i> تاريخ الإنشاء
                            </div>
                            <div class="publish-detail-value" id="modalCreated"></div>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <a href="#" class="btn btn-namaa btn-sm" id="modalEditBtn">
                    <i class="bi bi-pencil-square"></i> تعديل السجل
                </a>
                <button type="button" class="btn btn-soft btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> إغلاق
                </button>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
<style>
    /* ===== Publish Modal Detail Boxes ===== */
    .publish-detail-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        height: 100%;
        transition: .15s ease;
    }
    .publish-detail-box:hover {
        border-color: rgba(14, 165, 233, .3);
        background: #f0f9ff;
    }
    .publish-detail-label {
        font-size: .78rem;
        font-weight: 800;
        color: #94a3b8;
        margin-bottom: 6px;
        letter-spacing: .2px;
    }
    .publish-detail-value {
        font-weight: 800;
        font-size: 1rem;
        color: #0b1220;
    }
    .publish-caption {
        font-weight: 700;
        line-height: 1.9;
        white-space: pre-wrap;
        max-height: 120px;
        overflow-y: auto;
    }

    /* ===== Platform Chips ===== */
    .publish-platform-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 14px;
        font-weight: 800;
        font-size: .88rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: .2s ease;
    }
    .publish-platform-chip.published {
        background: rgba(16, 185, 129, .08);
        border-color: rgba(16, 185, 129, .3);
    }
    .publish-platform-chip.not-published {
        background: rgba(239, 68, 68, .05);
        border-color: rgba(239, 68, 68, .2);
    }
    .publish-platform-icon {
        font-size: 1.1rem;
    }
    .publish-platform-status {
        font-size: .82rem;
    }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.btn-show-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id       = this.dataset.id;
            var diploma  = this.dataset.diploma;
            var category = this.dataset.category;
            var type     = this.dataset.type;
            var branch   = this.dataset.branch;
            var caption  = this.dataset.caption;
            var meta     = this.dataset.meta === '1';
            var tiktok   = this.dataset.tiktok === '1';
            var youtube  = this.dataset.youtube === '1';
            var date     = this.dataset.date;
            var created  = this.dataset.created;
            var reqId    = this.dataset.requestId;
            var reqName  = this.dataset.requestName;



var contentLink = this.dataset.contentLink;

            // رابط المحتوى
var linkBox = document.getElementById('contentLinkBox');
var linkEl  = document.getElementById('modalContentLink');
if (contentLink && contentLink !== '') {
    linkEl.innerHTML = '<a href="' + contentLink + '" target="_blank" class="text-decoration-none fw-bold">' +
                       '<i class="bi bi-box-arrow-up-right me-1"></i>' + contentLink + '</a>';
    linkBox.style.display = 'block';
} else {
    linkEl.textContent    = '—';
    linkBox.style.display = 'block';
}




            document.getElementById('modalId').textContent       = '#' + id;
            document.getElementById('modalDiploma').textContent   = diploma;
            document.getElementById('modalCategory').textContent  = category;
            document.getElementById('modalType').textContent      = type;
            document.getElementById('modalBranch').textContent    = branch;
            document.getElementById('modalCaption').textContent   = caption;
            document.getElementById('modalDate').textContent      = date;
            document.getElementById('modalCreated').textContent   = created;

            // طلب الميديا المرتبط
            if (reqId && reqId !== '') {
                document.getElementById('modalRequest').innerHTML =
                    '<a href="/media-requests/' + reqId + '" class="text-decoration-none fw-bold">' +
                    '#' + reqId + ' — ' + reqName + '</a>';
            } else {
                document.getElementById('modalRequest').textContent = 'غير مرتبط';
            }

            // حالة المنصات
            setPlatformStatus('chipMeta',    'statusMeta',    meta);
            setPlatformStatus('chipTiktok',  'statusTiktok',  tiktok);
            setPlatformStatus('chipYoutube', 'statusYoutube', youtube);

            // رابط التعديل
            document.getElementById('modalEditBtn').href = '/media-publish/' + id + '/edit';
        });
    });

    function setPlatformStatus(chipId, statusId, isPublished) {
        var chip   = document.getElementById(chipId);
        var status = document.getElementById(statusId);

        if (isPublished) {
            chip.className   = 'publish-platform-chip published';
            status.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> تم النشر';
        } else {
            chip.className   = 'publish-platform-chip not-published';
            status.innerHTML = '<i class="bi bi-x-circle text-danger"></i> لم يُنشر';
        }
    }

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/media/publish_index.blade.php ENDPATH**/ ?>