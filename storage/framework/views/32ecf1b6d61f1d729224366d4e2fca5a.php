
<?php ($activeModule = 'students'); ?>

<?php $__env->startSection('title', 'ملف الطالب'); ?>

<?php $__env->startPush('styles'); ?>
  <style>
    :root {
      --namaa-blue: #0ea5e9;
      --namaa-green: #10b981;
      --namaa-ink: #0f172a;
      --namaa-muted: #64748b;
      --namaa-soft-bg: #f8fafc;
    }

    .page-head {
      background: rgba(255, 255, 255, .75);
      border: 1px solid rgba(226, 232, 240, .92);
      border-radius: 18px;
      backdrop-filter: blur(8px);
      box-shadow: 0 20px 60px rgba(2, 6, 23, .08);
      padding: 16px;
    }

    .glass-card {
      background: rgba(255, 255, 255, .85);
      border: 1px solid rgba(226, 232, 240, .95);
      border-radius: 18px;
      backdrop-filter: blur(8px);
      box-shadow: 0 18px 55px rgba(2, 6, 23, .08);
      overflow: hidden;
    }

    .section-header {
      background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
      color: #fff;
      padding: 12px 16px;
      font-weight: 900;
      border-radius: 16px 16px 0 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .kv {
      display: grid;
      grid-template-columns: 170px 1fr;
      gap: 10px;
      padding: 10px 0;
      border-bottom: 1px dashed rgba(226, 232, 240, .95);
    }
    .kv:last-child { border-bottom: 0; }
    .k { color: var(--namaa-muted); font-weight: 800; }
    .v { font-weight: 800; color: var(--namaa-ink); }

    .avatar {
      width: 74px; height: 74px;
      border-radius: 999px; overflow: hidden;
      border: 1px solid rgba(226, 232, 240, .95);
      background: rgba(248, 250, 252, .9);
      display: grid; place-items: center;
    }

    .btn-pill { border-radius: 999px; font-weight: 900; }

    .btn-namaa {
      border: 0;
      background: linear-gradient(90deg, var(--namaa-blue), var(--namaa-green));
      color: #fff !important;
      box-shadow: 0 18px 35px rgba(16, 185, 129, .18);
    }

    .badge-soft {
      border-radius: 999px; padding: 7px 10px; font-weight: 900;
      border: 1px solid rgba(226, 232, 240, .95); background: #fff;
    }
    .badge-soft.success { background: rgba(16, 185, 129, .12); color: #0f766e; }
    .badge-soft.warn    { background: rgba(245, 158, 11, .12);  color: #92400e; }
    .badge-soft.gray    { background: rgba(100, 116, 139, .12); color: #334155; }

    .finance-timeline { position: relative; padding-right: 30px; }
    .finance-timeline::before {
      content: ""; position: absolute; top: 0; right: 10px;
      width: 3px; height: 100%;
      background: linear-gradient(var(--namaa-blue), var(--namaa-green));
      border-radius: 10px;
    }

    .timeline-item { position: relative; margin-bottom: 25px; }

    .timeline-dot {
      position: absolute; right: -2px; top: 8px;
      width: 16px; height: 16px; border-radius: 50%;
      border: 3px solid #fff; box-shadow: 0 0 0 3px rgba(0,0,0,.05);
    }
    .timeline-dot.in  { background: var(--namaa-green); }
    .timeline-dot.out { background: #f59e0b; }

    .timeline-card {
      background: rgba(248, 250, 252, .9);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 14px; padding: 14px 16px;
      margin-right: 35px; transition: .2s ease;
    }
    .timeline-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(2,6,23,.08); }

    /* ── ستايلات التحقق ── */
    .installment-input.invalid {
      border-color: #dc3545 !important;
      box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.25) !important;
    }
    .installment-input.valid { border-color: #198754 !important; }
    .inst-error-msg {
      color: #dc3545; font-size: 12px; font-weight: 700;
      margin-top: 4px; display: none;
    }
    .inst-error-msg.show { display: block; }
    .summary-box {
      border-radius: 12px; padding: 14px;
      margin-top: 16px; border: 2px solid;
    }
    .summary-box.ok   { background: rgba(16, 185, 129, 0.08); border-color: #10b981; }
    .summary-box.bad  { background: rgba(220, 53, 69, 0.08);  border-color: #dc3545; }
    .summary-row { display: flex; justify-content: space-around; text-align: center; }
    .summary-row .item small { display: block; color: #64748b; font-size: 11px; font-weight: 700; }
    .summary-row .item strong { font-size: 16px; font-weight: 800; }
    .summary-box .warning-msg {
      text-align: center; color: #dc3545;
      font-weight: 800; font-size: 13px; margin-top: 8px;
      padding-top: 8px; border-top: 1px dashed rgba(220, 53, 69, 0.3);
    }

    @media (max-width: 575.98px) {
      .kv { grid-template-columns: 1fr; gap: 6px; }
      .page-head { padding: 14px; }
    }
  </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <?php if(session('currency_error')): ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
          icon: 'error',
          title: 'اختلاف العملة',
          html: `لا يمكن تسجيل الدفعة لأن عملة الصندوق لا تطابق عملة خطة الدفع.<br><br>
                 <b>عملة الخطة هي: <?php echo e(session('currency_error')); ?></b>`,
          confirmButtonText: 'حسناً',
          confirmButtonColor: '#3085d6'
        });
      });
    </script>
  <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="page-head mb-3">
    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
      <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar">
            <?php if(!empty($files['photo']['exists']) && $files['photo']['exists']): ?>
              <img src="<?php echo e($files['photo']['url']); ?>" alt="photo">
            <?php else: ?>
              <i class="bi bi-person fs-2 text-muted"></i>
            <?php endif; ?>
          </div>
          <div class="flex-grow-1">
            <h4 class="page-title"><?php echo e($student->full_name); ?></h4>
            <div class="meta-line small">
              رقم جامعي: <code><?php echo e($student->university_id); ?></code>
              <span class="mx-2">—</span>
              الفرع: <b><?php echo e($student->branch->name ?? '-'); ?></b>
            </div>
            <div class="mt-2 d-flex flex-wrap gap-2">
              <?php if($student->is_confirmed): ?>
                <span class="badge-soft success">
                  <i class="bi bi-check2-circle"></i> مثبّت
                  <span class="text-muted fw-bold">(<?php echo e(optional($student->confirmed_at)->format('Y-m-d H:i')); ?>)</span>
                </span>
              <?php else: ?>
                <span class="badge-soft gray"><i class="bi bi-hourglass-split"></i> غير مثبّت</span>
              <?php endif; ?>
              <span class="badge-soft"><i class="bi bi-diagram-3"></i> دبلومات: <?php echo e($student->diplomas->count()); ?></span>
              <span class="badge-soft warn"><i class="bi bi-info-circle"></i> الحالة: <?php echo e($status_ar); ?></span>
              <span class="badge-soft"><i class="bi bi-shield-check"></i> التسجيل: <?php echo e($registration_ar ?? '-'); ?></span>
            </div>
            <div class="mt-2">
              <b>الدبلومات:</b>
              <?php $__empty_1 = true; $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <span class="badge bg-light text-dark border"><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span class="text-muted">لا يوجد دبلومات</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-dark btn-pill" href="<?php echo e(route('students.edit', $student)); ?>">
          <i class="bi bi-pencil"></i> تعديل أساسي
        </a>
        <?php if($student->is_confirmed): ?>
          <a class="btn btn-namaa btn-pill" href="<?php echo e(route('students.profile.edit', $student)); ?>">
            <i class="bi bi-person-vcard"></i> الملف التفصيلي
          </a>
        <?php endif; ?>
        <?php if($waLink): ?>
          <a class="btn btn-success btn-pill" target="_blank" href="<?php echo e($waLink); ?>">
            <i class="bi bi-whatsapp"></i> واتساب الطالب
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="row g-3">

    
    <div class="col-12 col-lg-6">
      <div class="glass-card h-100">
        <div class="section-header"><i class="bi bi-person-vcard"></i> البيانات الأساسية</div>
        <div class="p-3 p-md-4">
          <div class="kv"><div class="k">الاسم</div><div class="v"><?php echo e($student->first_name ?? '-'); ?></div></div>
          <div class="kv"><div class="k">الكنية</div><div class="v"><?php echo e($student->last_name ?? '-'); ?></div></div>
          <div class="kv"><div class="k">الاسم والكنية</div><div class="v"><?php echo e($student->full_name ?? '-'); ?></div></div>
          <div class="kv"><div class="k">الهاتف</div><div class="v"><?php echo e($student->phone ?? '-'); ?></div></div>
          <div class="kv">
            <div class="k">واتساب</div>
            <div class="v">
              <?php if($waLink): ?>
                <a class="fw-bold" target="_blank" href="<?php echo e($waLink); ?>">
                  <i class="bi bi-whatsapp"></i> <?php echo e($student->whatsapp); ?>

                </a>
              <?php else: ?> -
              <?php endif; ?>
            </div>
          </div>
          <div class="kv"><div class="k">الفرع</div><div class="v"><?php echo e($student->branch->name ?? '-'); ?></div></div>
          <div class="kv"><div class="k">نوع الطالب</div><div class="v"><?php echo e($mode_ar ?? '-'); ?></div></div>
          <div class="kv"><div class="k">حالة الطالب</div><div class="v"><?php echo e($status_ar ?? '-'); ?></div></div>
          <div class="kv"><div class="k">حالة التسجيل</div><div class="v"><?php echo e($registration_ar ?? '-'); ?></div></div>
          <div class="kv"><div class="k">الرقم الجامعي</div><div class="v"><code><?php echo e($student->university_id); ?></code></div></div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-lg-6">
      <div class="glass-card h-100">
        <div class="section-header"><i class="bi bi-headset"></i> بيانات CRM</div>
        <div class="p-3 p-md-4">
          <?php if(!$student->crmInfo): ?>
            <div class="alert alert-info mb-0 fw-semibold">
              <i class="bi bi-info-circle"></i> لا يوجد بيانات CRM لهذا الطالب.
            </div>
          <?php else: ?>
            <div class="kv"><div class="k">تاريخ أول تواصل</div><div class="v"><?php echo e($student->crmInfo->first_contact_date?->format('Y-m-d') ?? '-'); ?></div></div>
            <div class="kv"><div class="k">السكن</div><div class="v"><?php echo e($student->crmInfo->residence ?? '-'); ?></div></div>
            <div class="kv"><div class="k">العمر</div><div class="v"><?php echo e($student->crmInfo->age ?? '-'); ?></div></div>
            <div class="kv"><div class="k">الجهة/المؤسسة</div><div class="v"><?php echo e($student->crmInfo->organization ?? '-'); ?></div></div>
            <div class="kv"><div class="k">المصدر</div><div class="v"><?php echo e($crm_source_ar ?? '-'); ?></div></div>
            <div class="kv"><div class="k">المرحلة</div><div class="v"><?php echo e($crm_stage_ar ?? '-'); ?></div></div>
            <div class="kv"><div class="k">الإيميل</div><div class="v"><?php echo e($student->crmInfo->email ?? '-'); ?></div></div>
            <div class="kv"><div class="k">العمل</div><div class="v"><?php echo e($student->crmInfo->job ?? '-'); ?></div></div>
            <div class="kv"><div class="k">البلد</div><div class="v"><?php echo e($student->crmInfo->country ?? '-'); ?></div></div>
            <div class="kv"><div class="k">المحافظة</div><div class="v"><?php echo e($student->crmInfo->province ?? '-'); ?></div></div>
            <div class="kv"><div class="k">الدراسة</div><div class="v"><?php echo e($student->crmInfo->study ?? '-'); ?></div></div>
            <div class="kv"><div class="k">مسؤول التواصل</div><div class="v"><?php echo e($student->crmInfo->creator->name ?? $student->crmInfo->creator->email ?? '-'); ?></div></div>
            <div class="kv"><div class="k">الاحتياج</div><div class="v"><?php echo e($student->crmInfo->need ?? '-'); ?></div></div>
            <div class="kv"><div class="k">ملاحظات CRM</div><div class="v"><?php echo e($student->crmInfo->notes ?? '-'); ?></div></div>
            <div class="kv"><div class="k">تاريخ التحويل</div><div class="v"><?php echo e($student->crmInfo->converted_at?->format('Y-m-d H:i') ?? '-'); ?></div></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    
    <div class="col-12">
      <div class="glass-card">
        <div class="section-header"><i class="bi bi-file-earmark-person"></i> الملف التفصيلي</div>
        <?php if(!$p): ?>
          <div class="alert alert-warning m-3 fw-semibold">
            <i class="bi bi-exclamation-triangle"></i> لا يوجد ملف تفصيلي لهذا الطالب بعد.
          </div>
        <?php else: ?>
          <div class="row g-3 p-3">
            <div class="col-12 col-lg-6">
              <div class="glass-card h-100">
                <div class="p-3">
                  <h6 class="fw-bold">بيانات شخصية</h6>
                  <div class="kv"><div class="k">الاسم بالعربي</div><div class="v"><?php echo e($p->arabic_full_name ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">الجنسية</div><div class="v"><?php echo e($p->nationality ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">تاريخ التولد</div><div class="v"><?php echo e($p->birth_date?->format('Y-m-d') ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">الرقم الوطني</div><div class="v"><?php echo e($p->national_id ?? '-'); ?></div></div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="glass-card h-100">
                <div class="p-3">
                  <h6 class="fw-bold">معلومات إضافية</h6>
                  <div class="kv"><div class="k">مستوى اللغة</div><div class="v"><?php echo e($p->level ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">ستاج/مرحلة بالولاية</div><div class="v"><?php echo e($p->stage_in_state ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">المستوى التعليمي</div><div class="v"><?php echo e($p->education_level ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">العلامة الامتحانية</div><div class="v"><?php echo e($p->exam_score ?? '-'); ?></div></div>
                </div>
              </div>
            </div>

            
            <div class="col-12">
              <div class="glass-card">
                <div class="section-header"><i class="bi bi-mortarboard"></i> الوثائق والملفات</div>
                <div class="row g-2 p-3">
                  <?php $__currentLoopData = [
                    ['info',             'ملف المعلومات',   'btn-outline-primary',  'bi-file-earmark-text'],
                    ['identity',         'ملف الهوية',      'btn-outline-dark',     'bi-person-badge'],
                    ['attendance',       'شهادة الحضور',    'btn-outline-success',  'bi-file-earmark-check'],
                    ['certificate_pdf',  'الشهادة PDF',     'btn-outline-danger',   'bi-file-earmark-pdf'],
                    ['certificate_card', 'الشهادة (كرتون)', 'btn-outline-primary',  'bi-file-earmark-image'],
                  ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $btnClass, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-md-4">
                      <div class="kv">
                        <div class="k"><?php echo e($label); ?></div>
                        <div class="v">
                          <?php if(!empty($files[$key]['exists']) && $files[$key]['exists']): ?>
                            <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجود</span>
                            <div class="mt-2">
                              <a class="btn <?php echo e($btnClass); ?> btn-sm" target="_blank" href="<?php echo e($files[$key]['url']); ?>">
                                <i class="bi <?php echo e($icon); ?>"></i> فتح
                              </a>
                            </div>
                          <?php else: ?>
                            <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجود</span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </div>
            </div>

            
            <div class="col-12 mt-3">
              <div class="section-header"><i class="bi bi-mortarboard"></i> تفاصيل الدبلومات</div>
            </div>
            <?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-12">
                <div class="glass-card mb-3 p-3">
                  <h6 class="fw-bold mb-2"><?php echo e($d->name); ?></h6>
                  <div class="kv"><div class="k">الحالة</div><div class="v"><?php echo e($d->pivot->status_ar); ?></div></div>
                  <div class="kv"><div class="k">تاريخ الانتهاء</div><div class="v"><?php echo e($d->pivot->ended_at ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">تسليم الشهادة كرتون</div><div class="v"><?php echo e($d->pivot->certificate_delivered ? 'نعم' : 'لا'); ?></div></div>
                  <div class="kv">
                    <div class="k">شهادة الحضور</div>
                    <div class="v">
                      <?php if($d->pivot->attendance_certificate_path): ?>
                        <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->attendance_certificate_path)); ?>">فتح الملف</a>
                      <?php else: ?> غير موجودة <?php endif; ?>
                    </div>
                  </div>
                  <div class="kv">
                    <div class="k">الشهادة PDF</div>
                    <div class="v">
                      <?php if($d->pivot->certificate_pdf_path): ?>
                        <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->certificate_pdf_path)); ?>">فتح الملف</a>
                      <?php else: ?> غير موجودة <?php endif; ?>
                    </div>
                  </div>
                  <div class="kv">
                    <div class="k">كرت الشهادة</div>
                    <div class="v">
                      <?php if($d->pivot->certificate_card_path): ?>
                        <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->certificate_card_path)); ?>">فتح الملف</a>
                      <?php else: ?> غير موجود <?php endif; ?>
                    </div>
                  </div>
                  <div class="kv"><div class="k">ملاحظات</div><div class="v"><?php echo e($d->pivot->notes ?? '-'); ?></div></div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="col-12">
              <div class="glass-card">
                <div class="p-3">
                  <h6 class="fw-bold mb-2">ملاحظات ورسالة</h6>
                  <div class="kv"><div class="k">ملاحظات</div><div class="v"><?php echo e($p->notes ?? '-'); ?></div></div>
                  <div class="kv"><div class="k">رسالة لاحقة للطالب</div><div class="v"><?php echo e($p->message_to_send ?? '-'); ?></div></div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="section-header"><i class="bi bi-journal-check"></i> العلامات الامتحانية</div>
    <div class="row g-3">
      <div class="p-3 p-md-4">
        <?php if($results->count() == 0): ?>
          <div class="alert alert-info mb-0 fw-semibold">
            <i class="bi bi-info-circle"></i> لم يقدم أي امتحان حتى الآن.
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="small text-muted">
                <tr>
                  <th>الامتحان</th><th>الدبلومة</th>
                  <th class="hide-mobile">التاريخ</th>
                  <th>العلامة</th>
                  <th class="hide-mobile">الحالة</th>
                  <th class="text-center">إجراء</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td class="fw-bold"><?php echo e($r->exam->title); ?></td>
                    <td>
                      <span class="badge-soft hide-mobile"><?php echo e($r->exam->diploma->name); ?></span>
                      <span class="text-muted">(<?php echo e($r->exam->diploma->code); ?>)</span>
                    </td>
                    <td class="text-muted hide-mobile"><?php echo e(\Carbon\Carbon::parse($r->exam->exam_date)->format('Y-m-d')); ?></td>
                    <td class="fw-bold"><?php echo e($r->score ?? '—'); ?></td>
                    <td class="hide-mobile">
                      <?php if($r->status === 'passed'): ?>
                        <span class="badge-soft success"><i class="bi bi-check-circle"></i> ناجح</span>
                      <?php elseif($r->status === 'failed'): ?>
                        <span class="badge-soft warn"><i class="bi bi-x-circle"></i> راسب</span>
                      <?php else: ?>
                        <span class="badge-soft gray">غير محدد</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a class="btn btn-sm btn-outline-primary btn-pill"
                         href="<?php echo e(route('exams.results.edit', $r->exam)); ?>?student_id=<?php echo e($student->id); ?>">
                        <i class="bi bi-pencil-square"></i> مراجعة
                      </a>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="section-header"><i class="bi bi-wallet2"></i> إنشاء خطة دفع</div>

    <div class="glass-card p-3">
      <form method="POST" action="<?php echo e(route('payment.plan.store')); ?>" id="plan-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
        <input type="hidden" name="diploma_id" id="selected_diploma">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="fw-bold">الدبلومة</label>
            <select class="form-select" id="diploma_preview">
              <?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">المبلغ الإجمالي</label>
            <input type="number" step="0.01" min="0.01" name="total_amount"
                   id="total_amount" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label class="fw-bold">العملة</label>
            <select name="currency" class="form-select">
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="TRY">TRY</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">نوع الدفع</label>
            <select name="payment_type" id="payment_type" class="form-select">
              <option value="full">كامل</option>
              <option value="installments">دفعات</option>
            </select>
          </div>

          <div class="col-md-4 installments-box d-none">
            <label class="fw-bold">عدد الدفعات</label>
            <select name="installments_count" id="installments_count" class="form-select">
              <option value="">اختر</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </div>
        </div>

        <hr>

        
        <div id="installments_container"></div>
        <div id="installments-summary"></div>

        <div class="row mt-4">
          <?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
              <div class="glass-card p-3 text-center">
                <h6 class="fw-bold mb-3"><?php echo e($d->name); ?></h6>
                <?php if(isset($plansByDiploma[$d->id])): ?>
                  <?php if($plansByDiploma[$d->id]->payments_count > 1): ?>
                    <button type="button" class="btn btn-warning btn-pill"
                            onclick="cannotEditPlan('<?php echo e($d->name); ?>')">
                      <i class="bi bi-lock"></i> تعديل خطة <?php echo e($d->name); ?>

                    </button>
                  <?php else: ?>
                    <a href="<?php echo e(route('payment.plan.edit', $plansByDiploma[$d->id]->id)); ?>"
                       class="btn btn-warning btn-pill">
                      <i class="bi bi-pencil"></i> تعديل خطة <?php echo e($d->name); ?>

                    </a>
                  <?php endif; ?>
                <?php else: ?>
                  <button type="submit" class="btn btn-namaa btn-pill plan-save-btn"
                          onclick="selectDiploma(<?php echo e($d->id); ?>)">
                    <i class="bi bi-check2-circle"></i> حفظ خطة <?php echo e($d->name); ?>

                  </button>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </form>
    </div>

    
    <?php if($paymentPlans->count()): ?>
      <div class="section-header"><i class="bi bi-calendar-check"></i> خطط الدفع</div>
      <div class="row g-3 p-3">
        <?php $__currentLoopData = $paymentPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-md-6">
            <div class="glass-card p-3">
              <h6 class="fw-bold mb-2"><?php echo e($plan->diploma->name); ?></h6>
              <div class="text-muted small mb-2">
                العملة المعتمدة: <span class="badge bg-info"><?php echo e($plan->currency); ?></span>
              </div>
              <div class="kv"><div class="k">المبلغ الإجمالي</div><div class="v"><?php echo e(number_format($plan->total_amount, 2)); ?></div></div>
              <div class="kv"><div class="k">نوع الدفع</div><div class="v"><?php echo e($plan->payment_type == 'full' ? 'كامل' : 'دفعات'); ?></div></div>
              <?php if($plan->payment_type == 'installments'): ?>
                <div class="kv"><div class="k">عدد الدفعات</div><div class="v"><?php echo e($plan->installments_count); ?></div></div>
              <?php endif; ?>
              <div class="kv"><div class="k">المقبوض</div><div class="v text-success"><?php echo e(number_format($plan->paid, 2)); ?></div></div>
              <div class="kv"><div class="k">المتبقي</div><div class="v text-warning"><?php echo e(number_format(max($plan->remaining,0), 2)); ?></div></div>
              <?php if($plan->installments->count()): ?>
                <hr>
                <h6 class="fw-bold">تواريخ الأقساط</h6>
                <?php $__currentLoopData = $plan->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="kv">
                    <div class="k">الدفعة <?php echo e($loop->iteration); ?></div>
                    <div class="v"><?php echo e(number_format($i->amount, 2)); ?> <span class="text-muted">(<?php echo e($i->due_date->format('Y-m-d')); ?>)</span></div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>

    
    <div class="section-header"><i class="bi bi-cash-coin"></i> المعلومات المالية</div>
    <div class="row g-3">
      <div class="p-3 p-md-4">
        <?php if($financial): ?>
          <?php if(!empty($balancesByCurrency)): ?>
            <div class="row g-3 mb-4">
              <?php $__currentLoopData = $balancesByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                  <div class="timeline-card text-center">
                    <div class="text-muted small">الرصيد — <?php echo e($currency); ?></div>
                    <div class="fs-4 fw-bold <?php echo e($amount >= 0 ? 'text-success' : 'text-warning'); ?>">
                      <?php echo e(number_format($amount, 2)); ?>

                    </div>
                    <span class="badge-soft <?php echo e($amount >= 0 ? 'success' : 'warn'); ?>">
                      <?php echo e($amount >= 0 ? 'رصيد موجب' : 'رصيد مستحق'); ?>

                    </span>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>

          <?php if($financial->transactions->count()): ?>
            <div class="finance-timeline">
              <?php $__currentLoopData = $financial->transactions->sortByDesc('trx_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="timeline-item">
                  <div class="timeline-dot <?php echo e($trx->type == 'in' ? 'in' : 'out'); ?>"></div>
                  <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                      <div>
                        <div class="fw-bold"><?php echo e($trx->type == 'in' ? 'دفعة مقبوضة' : 'دفعة مدفوعة'); ?></div>
                        <div class="small text-muted">
                          <?php echo e($trx->trx_date->format('Y-m-d')); ?> — <?php echo e($trx->cashbox->name ?? '-'); ?>

                          <?php if($trx->diploma): ?>
                            <br>الدبلومة:
                            <span class="badge text-light border" style="background-color: var(--namaa-green)">
                              <?php echo e($trx->diploma->name); ?>

                            </span>
                          <?php endif; ?>
                          <br>العملة: <span class="badge bg-info"><?php echo e($trx->currency); ?></span>
                        </div>
                        <?php if($trx->notes): ?>
                          <div class="mt-2 small text-muted"><?php echo e($trx->notes); ?></div>
                        <?php endif; ?>
                      </div>
                      <div class="text-end">
                        <div class="fw-bold fs-5 <?php echo e($trx->type == 'in' ? 'text-success' : 'text-warning'); ?>">
                          <?php echo e($trx->type == 'in' ? '+' : '-'); ?><?php echo e(number_format($trx->amount, 2)); ?>

                        </div>
                        <span class="badge-soft <?php echo e($trx->type == 'in' ? 'success' : 'warn'); ?>">
                          <?php echo e($trx->type == 'in' ? 'مقبوض' : 'مدفوع'); ?>

                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php else: ?>
            <div class="alert alert-info mb-0 fw-semibold">
              <i class="bi bi-info-circle"></i> لا توجد حركات مالية حتى الآن.
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-warning mb-0 fw-semibold">
            <i class="bi bi-exclamation-triangle"></i> لا يوجد سجل مالي لهذا الطالب
          </div>
        <?php endif; ?>
      </div>
    </div>

    
    <?php if(auth()->user()?->hasPermission('view_leads') && $financial): ?>
      <div class="row g-3">
        <div class="section-header"><i class="bi bi-plus-circle"></i> إضافة دفعة جديدة</div>
        <div class="p-3 p-md-4">
          <form method="POST" action="<?php echo e(route('financial.pay')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="financial_account_id" value="<?php echo e($financial->id); ?>">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="fw-bold mb-1">الدبلومة</label>
                <select name="diploma_id" class="form-select" required>
                  <?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="fw-bold mb-1">الصندوق</label>
                <select name="cashbox_id" class="form-select" required>
                  <?php $__currentLoopData = \App\Models\Cashbox::where('status','active')->where('branch_id',$student->branch_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $box): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($box->id); ?>"><?php echo e($box->name); ?> - <?php echo e($box->currency); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="fw-bold mb-1">المبلغ</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
              </div>
              <div class="col-12 text-end">
                <button class="btn btn-namaa btn-pill">
                  <i class="bi bi-check2-circle"></i> تسجيل دفعة
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>

  </div>

  
  <div class="modal fade" id="planLockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title"><i class="bi bi-shield-lock"></i> لا يمكن تعديل الخطة</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <p class="fw-bold mb-2">لا يمكن تعديل خطة الدفع الخاصة بدبلومة</p>
          <h6 class="text-primary mb-3" id="modalDiplomaName"></h6>
          <p class="text-muted">لأن الطالب قام بدفع دفعة ضمن هذه الخطة.</p>
          <p class="text-muted small">لحماية سلامة السجلات المالية لا يسمح النظام بتعديل الخطة بعد بدء الدفع.</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary btn-pill" data-bs-dismiss="modal">إغلاق</button>
        </div>
      </div>
    </div>
  </div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
  'use strict';

  const $totalInput  = document.getElementById('total_amount');
  const $paymentType = document.getElementById('payment_type');
  const $countSelect = document.getElementById('installments_count');
  const $container   = document.getElementById('installments_container');
  const $summary     = document.getElementById('installments-summary');
  const $installBox  = document.querySelector('.installments-box');

  // ══ بناء حقل دفعة واحدة ══
  function buildInstallmentRow(index) {
    return `
      <div class="row g-3 mt-1 installment-row" data-row="${index}">
        <div class="col-md-6">
          <label class="fw-bold">قيمة الدفعة ${index}</label>
          <input type="number" step="0.01" min="0.01"
                 name="installments[${index}][amount]"
                 class="form-control installment-input"
                 data-index="${index}"
                 placeholder="0.00"
                 required>
          <div class="inst-error-msg" data-error="${index}"></div>
        </div>
        <div class="col-md-6">
          <label class="fw-bold">تاريخ الدفعة ${index}</label>
          <input type="date" name="installments[${index}][due_date]"
                 class="form-control" required>
        </div>
      </div>`;
  }

  // ══ دالة التحقق المركزية ══
  function runValidation() {
    const total  = parseFloat($totalInput.value) || 0;
    const inputs = document.querySelectorAll('.installment-input');
    const btns   = document.querySelectorAll('.plan-save-btn');

    if (inputs.length === 0) {
      $summary.innerHTML = '';
      btns.forEach(b => { b.disabled = false; b.classList.remove('opacity-50'); });
      return true;
    }

    let sum      = 0;
    let hasError = false;

    // حساب المجموع
    inputs.forEach(inp => { sum += parseFloat(inp.value) || 0; });

    // التحقق الفردي لكل حقل
    inputs.forEach(inp => {
      const idx   = inp.dataset.index;
      const val   = parseFloat(inp.value) || 0;
      const errEl = document.querySelector(`[data-error="${idx}"]`);

      if (inp.value === '') {
        inp.classList.remove('invalid', 'valid');
        errEl.classList.remove('show');
        return;
      }

      if (val <= 0) {
        inp.classList.add('invalid');
        inp.classList.remove('valid');
        errEl.textContent = '⚠️ القيمة يجب أن تكون أكبر من صفر';
        errEl.classList.add('show');
        hasError = true;
        return;
      }

      if (total > 0 && val > total) {
        inp.classList.add('invalid');
        inp.classList.remove('valid');
        errEl.textContent = `⚠️ قيمة الدفعة (${val.toFixed(2)}) أكبر من الإجمالي (${total.toFixed(2)})`;
        errEl.classList.add('show');
        hasError = true;
        return;
      }

      inp.classList.add('valid');
      inp.classList.remove('invalid');
      errEl.classList.remove('show');
    });

    const overLimit = total > 0 && sum > total;
    const remaining = total - sum;
    const boxClass  = (overLimit || hasError) ? 'bad' : 'ok';
    const remainClr = remaining < 0 ? 'text-danger' : 'text-success';
    const sumClr    = overLimit ? 'text-danger' : '';

    $summary.innerHTML = `
      <div class="summary-box ${boxClass}">
        <div class="summary-row">
          <div class="item">
            <small>المبلغ الإجمالي</small>
            <strong>${total.toFixed(2)}</strong>
          </div>
          <div class="item">
            <small>مجموع الدفعات</small>
            <strong class="${sumClr}">${sum.toFixed(2)}</strong>
          </div>
          <div class="item">
            <small>المتبقي</small>
            <strong class="${remainClr}">${remaining.toFixed(2)}</strong>
          </div>
        </div>
        ${overLimit ? '<div class="warning-msg">⚠️ مجموع الدفعات يتجاوز المبلغ الإجمالي!</div>' : ''}
        ${hasError && !overLimit ? '<div class="warning-msg">⚠️ يوجد أخطاء في القيم المدخلة</div>' : ''}
      </div>`;

    const isValid = !hasError && !overLimit;
    btns.forEach(btn => {
      btn.disabled = !isValid;
      btn.classList.toggle('opacity-50', !isValid);
      btn.style.cursor = isValid ? 'pointer' : 'not-allowed';
    });

    return isValid;
  }

  // ══ ربط الأحداث ══
  function attachInputListeners() {
    document.querySelectorAll('.installment-input').forEach(inp => {
      inp.addEventListener('input', runValidation);
    });
  }

  $paymentType.addEventListener('change', function () {
    const isInst = this.value === 'installments';
    $installBox.classList.toggle('d-none', !isInst);
    if (!isInst) {
      $container.innerHTML = '';
      $summary.innerHTML   = '';
      $countSelect.value   = '';
      runValidation();
    }
  });

  $countSelect.addEventListener('change', function () {
    const count = parseInt(this.value);
    $container.innerHTML = '';
    $summary.innerHTML   = '';

    if (!count) { runValidation(); return; }

    let html = '';
    for (let i = 1; i <= count; i++) html += buildInstallmentRow(i);
    $container.innerHTML = html;

    attachInputListeners();
    runValidation();
  });

  $totalInput.addEventListener('input', runValidation);

  // ══ حماية إضافية عند submit ══
  document.getElementById('plan-form').addEventListener('submit', function (e) {
    if (!runValidation()) {
      e.preventDefault();
      alert('⚠️ يرجى تصحيح الأخطاء قبل الحفظ:\n' +
            '- مجموع الدفعات لا يجوز أن يتجاوز المبلغ الإجمالي\n' +
            '- كل دفعة يجب أن تكون أكبر من صفر\n' +
            '- كل دفعة يجب ألا تتجاوز المبلغ الإجمالي');
      return false;
    }
  });

  // ══ دوال عامة ══
  window.selectDiploma = function (id) {
    document.getElementById('selected_diploma').value = id;
  };

  window.cannotEditPlan = function (diploma) {
    document.getElementById('modalDiplomaName').innerText = diploma;
    new bootstrap.Modal(document.getElementById('planLockModal')).show();
  };

  runValidation();
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/show.blade.php ENDPATH**/ ?>