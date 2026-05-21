
<?php $__env->startSection('title', 'لوحة البرنامج'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1"><?php echo e($diploma->name); ?></h4>
    <div class="text-muted small">لوحة متابعة شاملة للبرنامج</div>
  </div>
  <div class="d-flex gap-2">
    <a href="<?php echo e(route('programs.management.edit', $diploma)); ?>" class="btn btn-namaa">
      تعديل البيانات
    </a>
    <a href="<?php echo e(route('programs.management.index')); ?>" class="btn btn-soft">رجوع</a>
  </div>
</div>

<?php
  $fields = collect($record->getAttributes())->only([
    'market_study','trainer_assigned','contracts_ready','materials_ready',
    'sessions_uploaded','media_form_sent','direct_ads','content_ready',
    'opening_invitation','opening_snippets','carousel','designs','stories',
    'projects','attendance_certificate','university_certificate','cards_ready',
    'admin_session_1','admin_session_2','admin_session_3','evaluations_done',
  ]);
  $total    = $fields->count();
  $done     = $fields->filter(fn($v) => $v == 1)->count();
  $progress = $total > 0 ? round(($done / $total) * 100) : 0;
?>


<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <strong>نسبة إنجاز البرنامج</strong>
      <span class="fw-bold"><?php echo e($progress); ?>%</span>
    </div>
    <div class="progress" style="height:10px;">
      <div class="progress-bar bg-success" style="width:<?php echo e($progress); ?>%;"></div>
    </div>
  </div>
</div>


<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">السعر</div>
      <div class="fw-bold fs-5"><?php echo e($record->price ?? '-'); ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">الطلاب المثبتين</div>
      <div class="fw-bold fs-5"><?php echo e($record->confirmed_students ?? 0); ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">الخريجين</div>
      <div class="fw-bold fs-5"><?php echo e($record->graduates_count ?? 0); ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">مدة الدبلومة</div>
      <div class="fw-bold fs-5"><?php echo e($record->duration_months ?? '-'); ?> شهر</div>
    </div>
  </div>
</div>

<div class="row g-4">

  
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم البرامج</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">دراسة السوق : <strong><?php echo e($record->market_study ? 'تم' : 'لا'); ?></strong></li>
          <li class="list-group-item">المدرب : <strong><?php echo e($record->trainer?->full_name ?? '-'); ?></strong></li>
          <li class="list-group-item">العقود : <strong><?php echo e($record->contracts_ready ? 'جاهزة' : 'غير جاهزة'); ?></strong></li>
          <li class="list-group-item">المادة العلمية : <strong><?php echo e($record->materials_ready ? 'جاهزة' : 'غير جاهزة'); ?></strong></li>
          <li class="list-group-item">رفع الجلسات : <strong><?php echo e($record->sessions_uploaded ? 'تم' : 'لا'); ?></strong></li>
          <li class="list-group-item">مصدر الشهادة : <strong><?php echo e($record->certificate_source ?? '-'); ?></strong></li>
          <?php if($record->details_file): ?>
            <li class="list-group-item">
              <a href="<?php echo e(asset('storage/' . $record->details_file)); ?>" target="_blank"
                 class="btn btn-sm btn-outline-success">تحميل ملف التفاصيل</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم الميديا</div>
      <div class="card-body small">
        <?php
          $mediaShowFields = [
            'media_form_sent'    => ['label' => 'فورم الميديا',     'link' => 'media_form_sent_link'],
            'direct_ads'         => ['label' => 'إعلانات',          'link' => 'direct_ads_link'],
            'content_ready'      => ['label' => 'المحتوى',          'link' => 'content_ready_link'],
            'opening_invitation' => ['label' => 'دعوة افتتاحية',    'link' => 'opening_invitation_link'],
            'opening_snippets'   => ['label' => 'مقتطفات افتتاحية', 'link' => 'opening_snippets_link'],
            'carousel'           => ['label' => 'كاروسيل',          'link' => 'carousel_link'],
            'designs'            => ['label' => 'تصاميم',           'link' => 'designs_link'],
          ];
        ?>
        <ul class="list-group list-group-flush">
          <?php $__currentLoopData = $mediaShowFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><?php echo e($info['label']); ?> : <strong><?php echo e($record->$field ? 'تم' : 'لا'); ?></strong></span>
              <?php if($record->$field && $record->{$info['link']}): ?>
                <a href="<?php echo e($record->{$info['link']}); ?>" target="_blank"
                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                  <i class="bi bi-link-45deg"></i> الرابط
                </a>
              <?php endif; ?>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          
          <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
              <span>
                ستوريات : <strong><?php echo e($record->stories ? 'تم' : 'لا'); ?></strong>
                <?php if($record->stories_total): ?>
                  <?php
                    $sPct = $record->stories_total > 0
                      ? min(100, round(($record->stories_done ?? 0) / $record->stories_total * 100))
                      : 0;
                  ?>
                  <span class="badge ms-1"
                        style="background:rgba(14,165,233,.12); color:#0369a1; font-size:.78rem;">
                    <?php echo e($record->stories_done ?? 0); ?> / <?php echo e($record->stories_total); ?>

                  </span>
                <?php endif; ?>
              </span>
              <?php if($record->stories && $record->stories_link): ?>
                <a href="<?php echo e($record->stories_link); ?>" target="_blank"
                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                  <i class="bi bi-link-45deg"></i> الرابط
                </a>
              <?php endif; ?>
            </div>
            <?php if(($record->stories_total ?? 0) > 0): ?>
              <div class="progress mt-2" style="height:5px;">
                <div class="progress-bar bg-info" style="width:<?php echo e($sPct); ?>%;"></div>
              </div>
              <div class="text-muted mt-1" style="font-size:.72rem;">
                <?php echo e($sPct); ?>% مكتملة
              </div>
            <?php endif; ?>
          </li>

        </ul>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم التسويق</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">بداية الحملة : <strong><?php echo e($record->campaign_start ?? '-'); ?></strong></li>
          <li class="list-group-item">نهاية الحملة : <strong><?php echo e($record->campaign_end ?? '-'); ?></strong></li>
          <li class="list-group-item">ميزانية الحملة : <strong><?php echo e($record->campaign_budget ?? '-'); ?></strong></li>
          <li class="list-group-item">
            المصروف الفعلي : <strong><?php echo e($record->campaign_spent ?? '-'); ?></strong>
            <?php if($record->campaign_budget && $record->campaign_spent): ?>
              <?php
                $pct   = min(100, round(($record->campaign_spent / $record->campaign_budget) * 100));
                $color = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#10b981');
              ?>
              <span class="badge ms-1" style="background:rgba(0,0,0,.06); color:#374151;">
                <?php echo e(number_format($record->campaign_spent, 0)); ?> / <?php echo e(number_format($record->campaign_budget, 0)); ?>

              </span>
              <div class="progress mt-1" style="height:5px;">
                <div class="progress-bar" style="width:<?php echo e($pct); ?>%; background:<?php echo e($color); ?>;"></div>
              </div>
              <div class="text-muted" style="font-size:.72rem;"><?php echo e($pct); ?>% من الميزانية</div>
            <?php endif; ?>
          </li>
          <li class="list-group-item">مسؤول التواصل : <strong><?php echo e($record->communication_manager ?? '-'); ?></strong></li>
        </ul>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">الامتحانات</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">البداية : <strong><?php echo e($record->start_date ?? '-'); ?></strong></li>
          <li class="list-group-item">النهاية : <strong><?php echo e($record->end_date ?? '-'); ?></strong></li>
          <li class="list-group-item">الامتحان النصفي : <strong><?php echo e($record->mid_exam ?? '-'); ?></strong></li>
          <li class="list-group-item">الامتحان النهائي : <strong><?php echo e($record->final_exam ?? '-'); ?></strong></li>
          <li class="list-group-item">مشاريع : <strong><?php echo e($record->projects ? 'نعم' : 'لا'); ?></strong></li>
        </ul>
      </div>
    </div>
  </div>

  
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">شؤون الطلاب</div>
      <div class="card-body small">
        <div class="row g-3 mb-3">
          <div class="col-md-3">الحضور: <strong><?php echo e($record->attendance_certificate ? 'تم' : 'لا'); ?></strong></div>
          <div class="col-md-3">شهادة الجامعة: <strong><?php echo e($record->university_certificate ? 'تم' : 'لا'); ?></strong></div>
          <div class="col-md-3">البطاقات: <strong><?php echo e($record->cards_ready ? 'جاهزة' : 'لا'); ?></strong></div>
        </div>

        <?php
          $sessionShowFields = [
            'admin_session_1'  => ['label' => 'جلسة إدارية وتقييمية 1', 'link' => 'admin_session_1_link'],
            'admin_session_2'  => ['label' => 'جلسة إدارية وتقييمية 2', 'link' => 'admin_session_2_link'],
            'admin_session_3'  => ['label' => 'جلسة إدارية وتقييمية 3', 'link' => 'admin_session_3_link'],
            'evaluations_done' => ['label' => 'تقييمات بعد انتهاء البرنامج', 'link' => 'evaluations_done_link'],
          ];
        ?>

        <div class="row g-3 mb-3">
          <?php $__currentLoopData = $sessionShowFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
              <div style="background:rgba(248,250,252,.9); border:1px solid rgba(226,232,240,.9);
                          border-radius:10px; padding:10px 12px;">
                <div style="font-weight:800; font-size:13px; margin-bottom:4px;"><?php echo e($info['label']); ?></div>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/programs_management/show.blade.php ENDPATH**/ ?>