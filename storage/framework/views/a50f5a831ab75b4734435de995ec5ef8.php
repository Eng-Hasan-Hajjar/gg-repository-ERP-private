
<?php $__env->startSection('title', 'إدارة البرنامج'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">إدارة البرنامج</h4>
    <div class="text-muted small"><?php echo e($diploma->name); ?> — <?php echo e($diploma->code); ?></div>
  </div>
  <a href="<?php echo e(route('programs.management.index')); ?>" class="btn btn-soft">رجوع</a>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mb-3">
    <strong>يرجى تصحيح الأخطاء التالية:</strong>
    <ul class="mb-0 mt-2">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<style>
.link-field { display:none; margin-top:8px; }
.link-field.visible { display:block; }
.link-input {
  border:1px solid rgba(14,165,233,.4); border-radius:10px;
  padding:7px 12px; font-size:13px; width:100%;
  background:rgba(14,165,233,.04);
}
.link-input:focus { outline:none; border-color:var(--namaa-blue); background:#fff; }
.link-label {
  font-size:11px; font-weight:800; color:var(--namaa-blue);
  margin-bottom:4px; display:flex; align-items:center; gap:5px;
}
.field-box {
  background:rgba(248,250,252,.9);
  border:1px solid rgba(226,232,240,.9);
  border-radius:12px; padding:14px 16px;
}
</style>

<form method="POST" action="<?php echo e(route('programs.management.update', $diploma)); ?>" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>
  <div class="row g-4">

    
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-bold">قسم البرامج</div>
        <div class="card-body">
          <div class="row g-3">

            <?php if($diploma->type === 'online'): ?>
              <div class="col-md-3 form-check">
                <input type="checkbox" name="market_study" class="form-check-input" <?php if($record->market_study): echo 'checked'; endif; ?>>
                <label class="form-check-label">دراسة السوق</label>
              </div>
            <?php endif; ?>

            <div class="col-md-3 form-check">
              <input type="checkbox" name="contracts_ready" class="form-check-input" <?php if($record->contracts_ready): echo 'checked'; endif; ?>>
              <label class="form-check-label">العقود جاهزة</label>
            </div>

            <?php if($diploma->type === 'online'): ?>
              <div class="col-md-3 form-check">
                <input type="checkbox" name="materials_ready" class="form-check-input" <?php if($record->materials_ready): echo 'checked'; endif; ?>>
                <label class="form-check-label">المادة العلمية جاهزة</label>
              </div>
              <div class="col-md-3 form-check">
                <input type="checkbox" name="sessions_uploaded" class="form-check-input" <?php if($record->sessions_uploaded): echo 'checked'; endif; ?>>
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
              <input type="text" name="certificate_source" value="<?php echo e($record->certificate_source); ?>" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">ملف التفاصيل</label>
              <input type="file" name="details_file" class="form-control">
              <?php if($record->details_file): ?>
                <a href="<?php echo e(asset('storage/' . $record->details_file)); ?>" target="_blank" class="small">عرض الملف</a>
              <?php endif; ?>
            </div>

            <div class="col-md-4">
              <label class="form-label">سعر الدبلومة</label>
              <input type="number" step="0.01" name="price" value="<?php echo e($record->price); ?>" class="form-control">
            </div>

          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-bold">قسم الميديا</div>
        <div class="card-body">
          <div class="row g-3">
            <?php
              $mediaFields = [
                'media_form_sent'    => 'تم إرسال فورم الميديا',
                'direct_ads'         => 'إعلان مباشر',
                'content_ready'      => 'المحتوى جاهز',
                'opening_invitation' => 'دعوة افتتاحية',
                'opening_snippets'   => 'مقتطفات افتتاحية',
                'carousel'           => 'كاروسيل',
                'designs'            => 'تصاميم',
                
              ];
            ?>

            <?php $__currentLoopData = $mediaFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-md-3">
                <div class="field-box">
                  <div class="form-check mb-1">
                    <input type="checkbox"
                           name="<?php echo e($field); ?>"
                           id="cb_<?php echo e($field); ?>"
                           class="form-check-input session-checkbox"
                           data-target="link_<?php echo e($field); ?>"
                           <?php if($record->$field): echo 'checked'; endif; ?>>
                    <label class="form-check-label fw-bold" for="cb_<?php echo e($field); ?>">
                      <?php echo e($label); ?>

                    </label>
                  </div>
                  <?php $linkField = $field . '_link'; ?>
                  <div id="link_<?php echo e($field); ?>" class="link-field <?php echo e($record->$field ? 'visible' : ''); ?>">
                    <div class="link-label"><i class="bi bi-link-45deg"></i> رابط <?php echo e($label); ?></div>
                    <input type="url" name="<?php echo e($linkField); ?>" class="link-input"
                           placeholder="https://..."
                           value="<?php echo e(old($linkField, $record->$linkField ?? '')); ?>">
                  </div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




<div class="col-md-3">
  <div class="field-box">
    <div class="form-check mb-2">
      <input type="checkbox"
             name="stories"
             id="cb_stories"
             class="form-check-input session-checkbox"
             data-target="link_stories"
             <?php if($record->stories): echo 'checked'; endif; ?>>
      <label class="form-check-label fw-bold" for="cb_stories">ستوريات</label>
    </div>

    
    <div class="row g-1 mb-2">
      <div class="col-6">
        <label style="font-size:11px; font-weight:800; color:#64748b;">المُنجز</label>
        <input type="number" name="stories_done" min="0"
               value="<?php echo e(old('stories_done', $record->stories_done)); ?>"
               class="form-control form-control-sm" placeholder="0">
      </div>
      <div class="col-6">
        <label style="font-size:11px; font-weight:800; color:#64748b;">الإجمالي</label>
        <input type="number" name="stories_total" min="0"
               value="<?php echo e(old('stories_total', $record->stories_total)); ?>"
               class="form-control form-control-sm" placeholder="0">
      </div>
    </div>

    
    <?php if($record->stories_total && $record->stories_done !== null): ?>
      <?php
        $sPct = min(100, round(($record->stories_done / $record->stories_total) * 100));
      ?>
      <div class="d-flex justify-content-between" style="font-size:.72rem; color:#64748b;">
        <span><?php echo e($record->stories_done); ?> / <?php echo e($record->stories_total); ?></span>
        <span><?php echo e($sPct); ?>%</span>
      </div>
      <div class="progress mt-1" style="height:4px;">
        <div class="progress-bar bg-info" style="width:<?php echo e($sPct); ?>%;"></div>
      </div>
    <?php endif; ?>

    
    <div id="link_stories" class="link-field <?php echo e($record->stories ? 'visible' : ''); ?>">
      <div class="link-label"><i class="bi bi-link-45deg"></i> رابط الستوريات</div>
      <input type="url" name="stories_link" class="link-input"
             placeholder="https://..."
             value="<?php echo e(old('stories_link', $record->stories_link ?? '')); ?>">
    </div>
  </div>
</div>



          </div>
        </div>
      </div>
    </div>



    
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-bold">قسم التسويق</div>
        <div class="card-body">
          <div class="row g-3">

            <div class="col-md-3">
              <label class="form-label">بداية الحملة</label>
              <input type="date" name="campaign_start" value="<?php echo e($record->campaign_start); ?>" class="form-control">
            </div>

            <div class="col-md-3">
              <label class="form-label">نهاية الحملة</label>
              <input type="date" name="campaign_end" value="<?php echo e($record->campaign_end); ?>" class="form-control">
            </div>

            
            <div class="col-md-3">
              <label class="form-label fw-bold">ميزانية الحملة</label>
              <input type="number" step="0.01" name="campaign_budget"
                     value="<?php echo e($record->campaign_budget); ?>" class="form-control"
                     placeholder="المبلغ المخصص">
            </div>

            
            <div class="col-md-3">
              <label class="form-label fw-bold">
                المصروف الفعلي
                <?php if($record->campaign_budget && $record->campaign_spent): ?>
                  <span class="badge ms-1"
                        style="background:rgba(14,165,233,.12); color:#0369a1; font-size:.72rem;">
                    <?php echo e(number_format($record->campaign_spent, 0)); ?> /
                    <?php echo e(number_format($record->campaign_budget, 0)); ?>

                  </span>
                <?php endif; ?>
              </label>
              <input type="number" step="0.01" name="campaign_spent"
                     value="<?php echo e($record->campaign_spent); ?>" class="form-control"
                     placeholder="المبلغ المصروف فعلياً">
              <?php if($record->campaign_budget && $record->campaign_spent): ?>
                <?php
                  $pct = min(100, round(($record->campaign_spent / $record->campaign_budget) * 100));
                  $color = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#10b981');
                ?>
                <div class="progress mt-1" style="height:5px;">
                  <div class="progress-bar" style="width:<?php echo e($pct); ?>%; background:<?php echo e($color); ?>;"></div>
                </div>
                <div class="text-muted" style="font-size:.72rem;"><?php echo e($pct); ?>% من الميزانية</div>
              <?php endif; ?>
            </div>

            <div class="col-md-3">
              <label class="form-label">مسؤول التواصل</label>
              <input type="text" name="communication_manager"
                     value="<?php echo e($record->communication_manager); ?>" class="form-control">
            </div>

            
            <div class="col-md-3">
              <label class="form-label fw-bold">عدد الطلاب المثبتين</label>
              <div class="input-group">
                <input type="number" name="confirmed_students"
                       value="<?php echo e($record->confirmed_students ?? $confirmedStudents); ?>"
                       class="form-control">
                <span class="input-group-text bg-light text-muted small"
                      title="العدد الفعلي من قسم الطلاب">
                  <i class="bi bi-people-fill"></i> <?php echo e($confirmedStudents); ?>

                </span>
              </div>
              <div class="text-muted" style="font-size:.72rem;">
                العدد الفعلي من قسم الطلاب: <strong><?php echo e($confirmedStudents); ?></strong>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-bold">قسم الامتحانات والوثائق الأكاديمية</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3 form-check">
              <input type="checkbox" name="projects" class="form-check-input" <?php if($record->projects): echo 'checked'; endif; ?>>
              <label class="form-check-label">مشاريع</label>
            </div>
            <div class="col-md-3 form-check">
              <input type="checkbox" name="attendance_certificate" class="form-check-input" <?php if($record->attendance_certificate): echo 'checked'; endif; ?>>
              <label class="form-check-label">استلام شهادة الحضور</label>
            </div>
            <div class="col-md-3 form-check">
              <input type="checkbox" name="university_certificate" class="form-check-input" <?php if($record->university_certificate): echo 'checked'; endif; ?>>
              <label class="form-check-label">استلام شهادة الجامعة</label>
            </div>
            <div class="col-md-3 form-check">
              <input type="checkbox" name="cards_ready" class="form-check-input" <?php if($record->cards_ready): echo 'checked'; endif; ?>>
              <label class="form-check-label">البطاقات جاهزة</label>
            </div>
            <div class="col-md-3">
              <label class="form-label">مدة الدبلومة (شهر)</label>
              <input type="number" name="duration_months" value="<?php echo e($record->duration_months); ?>" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label"><?php echo e($diploma->type === 'online' ? 'عدد الساعات' : 'عدد الجلسات'); ?></label>
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
        <div class="card-header bg-light fw-bold">قسم شؤون الطلاب</div>
        <div class="card-body">
          <div class="row g-3">
            <?php
              $sessionFields = [
                'admin_session_1'  => 'جلسة إدارية وتقييمية 1',
                'admin_session_2'  => 'جلسة إدارية وتقييمية 2',
                'admin_session_3'  => 'جلسة إدارية وتقييمية 3',
                'evaluations_done' => 'تقييمات بعد انتهاء البرنامج',
              ];
            ?>

            <?php $__currentLoopData = $sessionFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-md-6">
                <div class="field-box">
                  <div class="form-check mb-1">
                    <input type="checkbox" name="<?php echo e($field); ?>" id="cb_<?php echo e($field); ?>"
                           class="form-check-input session-checkbox"
                           data-target="link_<?php echo e($field); ?>"
                           <?php if($record->$field): echo 'checked'; endif; ?>>
                    <label class="form-check-label fw-bold" for="cb_<?php echo e($field); ?>"><?php echo e($label); ?></label>
                  </div>
                  <?php $linkField = $field . '_link'; ?>
                  <div id="link_<?php echo e($field); ?>" class="link-field <?php echo e($record->$field ? 'visible' : ''); ?>">
                    <div class="link-label"><i class="bi bi-link-45deg"></i> رابط <?php echo e($label); ?></div>
                    <input type="url" name="<?php echo e($linkField); ?>" class="link-input"
                           placeholder="https://..."
                           value="<?php echo e(old($linkField, $record->$linkField ?? '')); ?>">
                  </div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($diploma->type === 'online'): ?>
              <div class="col-md-3">
                <label class="form-label">عدد الخريجين</label>
                <input type="number" name="graduates_count" value="<?php echo e($record->graduates_count); ?>" class="form-control">
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
    <button class="btn btn-namaa px-5 fw-bold">حفظ جميع البيانات</button>
  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.session-checkbox').forEach(function (cb) {
    cb.addEventListener('change', function () {
      const linkDiv = document.getElementById(this.getAttribute('data-target'));
      if (this.checked) {
        linkDiv.classList.add('visible');
      } else {
        linkDiv.classList.remove('visible');
        linkDiv.querySelector('input[type="url"]').value = '';
      }
    });
  });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/programs_management/edit.blade.php ENDPATH**/ ?>