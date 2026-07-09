
<?php $__env->startSection('title', 'تقارير الطلاب'); ?>

<?php $__env->startSection('content'); ?>

<style>
/* ══════════════════════════════════════════
   قائمة اختيار الأعمدة — تصميم محسّن
══════════════════════════════════════════ */
.column-picker-dropdown {
  min-width: 300px;
  padding: 0 !important;
  border: none;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0,0,0,.15);
  overflow: hidden;
}

.cp-header {
  padding: 14px 18px;
  background: linear-gradient(135deg, #0ea5e9, #06b6d4);
  color: #fff;
}

.cp-header-title {
  font-size: 14px;
  font-weight: 800;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cp-header-sub {
  font-size: 11.5px;
  opacity: .85;
  margin-top: 2px;
}

.cp-body {
  max-height: 320px;
  overflow-y: auto;
  padding: 10px 8px;
}

.cp-body::-webkit-scrollbar { width: 6px; }
.cp-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.cp-body::-webkit-scrollbar-track { background: transparent; }

.cp-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 9px 12px;
  border-radius: 10px;
  cursor: pointer;
  transition: background .15s;
  margin-bottom: 2px;
}

.cp-item:hover {
  background: #f1f5f9;
}

.cp-item-label {
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cp-item-label i {
  color: #94a3b8;
  font-size: 13px;
  width: 16px;
  text-align: center;
}

/* ── Toggle Switch ── */
.cp-switch {
  position: relative;
  width: 38px;
  height: 21px;
  flex-shrink: 0;
}

.cp-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.cp-switch-slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background: #e2e8f0;
  border-radius: 20px;
  transition: background .2s;
}

.cp-switch-slider::before {
  content: '';
  position: absolute;
  width: 15px;
  height: 15px;
  right: 3px;
  top: 3px;
  background: #fff;
  border-radius: 50%;
  transition: transform .2s;
  box-shadow: 0 1px 3px rgba(0,0,0,.2);
}

.cp-switch input:checked + .cp-switch-slider {
  background: #0ea5e9;
}

.cp-switch input:checked + .cp-switch-slider::before {
  transform: translateX(-17px);
}

.cp-footer {
  padding: 12px 16px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  gap: 8px;
}

.cp-footer .btn {
  font-size: 12.5px;
  font-weight: 700;
  border-radius: 9px;
  padding: 7px 14px;
}

.cp-count-badge {
  background: rgba(255,255,255,.25);
  padding: 2px 9px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 800;
}
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">تقارير الطلاب</h4>
    <div class="text-muted small">فلترة متقدمة + أعمدة قابلة للتخصيص + تصدير Excel ديناميكي</div>
  </div>
  <a href="<?php echo e(route('students.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
    <i class="bi bi-arrow-right"></i> رجوع لقائمة الطلاب
  </a>
</div>


<div class="row g-2 mb-3">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2" style="border-right:4px solid #3b82f6 !important;border-radius:12px;">
      <div style="font-size:1.6rem;font-weight:900;color:#3b82f6;"><?php echo e($totalCount); ?></div>
      <div style="font-size:.8rem;color:#64748b;font-weight:700;">إجمالي (حسب الفلتر)</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2" style="border-right:4px solid #10b981 !important;border-radius:12px;">
      <div style="font-size:1.6rem;font-weight:900;color:#10b981;"><?php echo e($confirmedCount); ?></div>
      <div style="font-size:.8rem;color:#64748b;font-weight:700;">مثبّتون</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2" style="border-right:4px solid #f59e0b !important;border-radius:12px;">
      <div style="font-size:1.6rem;font-weight:900;color:#f59e0b;"><?php echo e($activeCount); ?></div>
      <div style="font-size:.8rem;color:#64748b;font-weight:700;">مستمرون بالدراسة</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2" style="border-right:4px solid #8b5cf6 !important;border-radius:12px;">
      <div style="font-size:1.6rem;font-weight:900;color:#8b5cf6;"><?php echo e($todayCount); ?></div>
      <div style="font-size:.8rem;color:#64748b;font-weight:700;">أُضيفوا اليوم</div>
    </div>
  </div>
</div>


<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('students.reports.index')); ?>" id="reportFilterForm">
  <div class="row g-2 align-items-end">

    <div class="col-12 col-md-3">
      <label class="form-label fw-bold small text-muted">بحث</label>
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="الاسم / الرقم / الهاتف">
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold small text-muted">الدبلومة</label>
      <select name="diploma_id" class="form-select" id="diplomaFilterSelect">
        <option value="">كل الدبلومات</option>
        <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($d->id); ?>" data-code="<?php echo e($d->code); ?>"
            <?php if(request('diploma_id') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?> — <?php echo e($d->code); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold small text-muted">الفرع</label>
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold small text-muted">حالة الطالب</label>
      <select name="status" class="form-select">
        <option value="">الكل</option>
        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold small text-muted">التسجيل</label>
      <select name="registration_status" class="form-select">
        <option value="">الكل</option>
        <?php $__currentLoopData = $registrationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('registration_status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-1 d-grid">
      <button class="btn btn-namaa fw-bold"><i class="bi bi-funnel-fill"></i></button>
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label fw-bold small text-muted">تاريخ الإضافة من</label>
      <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-control">
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label fw-bold small text-muted">إلى</label>
      <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-control">
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label fw-bold small text-muted">نوع الطالب</label>
      <select name="mode" class="form-select">
        <option value="">الكل</option>
        <?php $__currentLoopData = $modeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('mode') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-3 d-grid">
      <a href="<?php echo e(route('students.reports.index')); ?>" class="btn btn-outline-secondary fw-bold">
        <i class="bi bi-x-circle"></i> مسح الفلاتر
      </a>
    </div>

  </div>
</form>



<div class="card border-0 shadow-sm mb-3">
<div class="card-body py-2 d-flex justify-content-start align-items-center flex-wrap gap-2">
    <div class="dropdown">
      <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button"
        id="columnsDropdownBtn" data-bs-toggle="dropdown">
        <i class="bi bi-layout-three-columns me-1"></i> الأعمدة
        <span class="badge bg-primary rounded-pill ms-1" id="colCountBadge"><?php echo e(count($defaultColumns)); ?></span>
      </button>

      <div class="dropdown-menu column-picker-dropdown" aria-labelledby="columnsDropdownBtn">

        <div class="cp-header">
          <div class="cp-header-title">
            <i class="bi bi-sliders"></i> تخصيص أعمدة الجدول
          </div>
          <div class="cp-header-sub">اختر الأعمدة التي تريد عرضها وتصديرها</div>
        </div>

        <div class="cp-body">
          <?php
            $columnIcons = [
              'id' => 'bi-hash', 'university_id' => 'bi-card-text', 'full_name' => 'bi-person',
              'latin_name' => 'bi-fonts', 'phone' => 'bi-telephone', 'whatsapp' => 'bi-whatsapp',
              'branch' => 'bi-building', 'diplomas' => 'bi-mortarboard', 'mode' => 'bi-laptop',
              'status' => 'bi-check-circle', 'registration_status' => 'bi-clipboard-check',
              'nationality' => 'bi-flag', 'birth_date' => 'bi-calendar-heart', 'national_id' => 'bi-person-badge',
              'crm_source' => 'bi-graph-up-arrow', 'crm_stage' => 'bi-signpost-split',
              'organization' => 'bi-bank', 'exam_score' => 'bi-award', 'language_level' => 'bi-translate',
              'certificate_agreement' => 'bi-patch-check', 'created_at' => 'bi-calendar-plus',
            ];
          ?>
          <?php $__currentLoopData = $availableColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="cp-item" for="col-<?php echo e($key); ?>">
              <span class="cp-item-label">
                <i class="bi <?php echo e($columnIcons[$key] ?? 'bi-dot'); ?>"></i>
                <?php echo e($label); ?>

              </span>
              <span class="cp-switch">
                <input class="column-toggle" type="checkbox" value="<?php echo e($key); ?>" id="col-<?php echo e($key); ?>"
                  <?php if(in_array($key, $defaultColumns)): echo 'checked'; endif; ?>>
                <span class="cp-switch-slider"></span>
              </span>
            </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="cp-footer">
          <button type="button" class="btn btn-outline-danger flex-fill" id="resetColumnsBtn">
            <i class="bi bi-arrow-counterclockwise"></i> إعادة تعيين
          </button>
          <button type="button" class="btn btn-primary flex-fill" id="closeColumnPickerBtn">
            <i class="bi bi-check-lg"></i> تم
            </button>
        </div>

      </div>
    </div>

    <a href="#" class="btn btn-outline-success fw-bold rounded-pill px-4" id="exportExcelBtn">
      <i class="bi bi-file-earmark-excel"></i> تصدير Excel (حسب الأعمدة المختارة)
    </a>

  </div>
</div>


<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <?php $__currentLoopData = $availableColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <th data-col="<?php echo e($key); ?>"><?php echo e($label); ?></th>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <?php $__currentLoopData = $availableColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td data-col="<?php echo e($key); ?>">
              <?php switch($key):
                case ('id'): ?> <?php echo e($s->id); ?> <?php break; ?>
                <?php case ('university_id'): ?> <code><?php echo e($s->university_id); ?></code> <?php break; ?>
                <?php case ('full_name'): ?> <span class="fw-semibold"><?php echo e($s->full_name); ?></span> <?php break; ?>
                <?php case ('latin_name'): ?> <?php echo e($s->profile->arabic_full_name ?? '-'); ?> <?php break; ?>
                <?php case ('phone'): ?> <?php echo e($s->phone ?? '-'); ?> <?php break; ?>
                <?php case ('whatsapp'): ?> <?php echo e($s->whatsapp ?? '-'); ?> <?php break; ?>
                <?php case ('branch'): ?> <?php echo e($s->branch->name ?? '-'); ?> <?php break; ?>
                <?php case ('diplomas'): ?> <?php echo e($s->diplomas->pluck('name')->implode('، ') ?: '-'); ?> <?php break; ?>
                <?php case ('mode'): ?> <?php echo e($s->mode_ar); ?> <?php break; ?>
                <?php case ('status'): ?> <span class="badge bg-secondary"><?php echo e($s->status_ar); ?></span> <?php break; ?>
                <?php case ('registration_status'): ?> <span class="badge bg-info"><?php echo e($s->registration_ar); ?></span> <?php break; ?>
                <?php case ('nationality'): ?> <?php echo e($s->profile->nationality ?? '-'); ?> <?php break; ?>
                <?php case ('birth_date'): ?> <?php echo e($s->profile->birth_date ?? '-'); ?> <?php break; ?>
                <?php case ('national_id'): ?> <?php echo e($s->profile->national_id ?? '-'); ?> <?php break; ?>
                <?php case ('crm_source'): ?> <?php echo e($s->crm_source_ar); ?> <?php break; ?>
                <?php case ('crm_stage'): ?> <?php echo e($s->crm_stage_ar); ?> <?php break; ?>
                <?php case ('organization'): ?> <?php echo e($s->crmInfo->organization ?? '-'); ?> <?php break; ?>
                <?php case ('exam_score'): ?> <?php echo e($s->profile->exam_score ?? '-'); ?> <?php break; ?>
                <?php case ('language_level'): ?> <?php echo e($s->diplomas->pluck('pivot.language_level')->filter()->implode('، ') ?: '-'); ?> <?php break; ?>
                <?php case ('certificate_agreement'): ?> <?php echo e($s->diplomas->pluck('pivot.certificate_agreement')->filter()->implode('، ') ?: '-'); ?> <?php break; ?>
                <?php case ('created_at'): ?> <?php echo e($s->created_at?->format('Y-m-d')); ?> <?php break; ?>
                <?php default: ?> -
              <?php endswitch; ?>
            </td>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="<?php echo e(count($availableColumns)); ?>" class="text-center text-muted py-5">لا يوجد نتائج</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3"><?php echo e($students->links()); ?></div>

<script>



document.getElementById('closeColumnPickerBtn')?.addEventListener('click', function () {
  const dropdownEl = document.getElementById('columnsDropdownBtn');
  const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownEl)
    || new bootstrap.Dropdown(dropdownEl);
  dropdownInstance.hide();
});
document.querySelector('.column-picker-dropdown')?.addEventListener('click', function (e) {
  e.stopPropagation();
});


document.addEventListener('DOMContentLoaded', function () {

  const STORAGE_KEY = 'namaa_student_report_columns';
  const defaultCols = <?php echo json_encode($defaultColumns, 15, 512) ?>;
  const allCols     = <?php echo json_encode(array_keys($availableColumns), 15, 512) ?>;

  function getSelectedColumns() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
      try { return JSON.parse(saved); } catch (e) {}
    }
    return defaultCols;
  }

  function saveSelectedColumns(cols) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cols));
  }

  function applyColumnVisibility() {
    const selected = getSelectedColumns();

    // تحديث الجدول
    allCols.forEach(function (col) {
      const isVisible = selected.includes(col);
      document.querySelectorAll('[data-col="' + col + '"]').forEach(function (el) {
        el.style.display = isVisible ? '' : 'none';
      });
    });

    // تحديث الـ checkboxes
    document.querySelectorAll('.column-toggle').forEach(function (chk) {
      chk.checked = selected.includes(chk.value);
    });

    // تحديث العداد
    document.getElementById('colCountBadge').textContent = selected.length;
  }

  applyColumnVisibility();

  document.querySelectorAll('.column-toggle').forEach(function (chk) {
    chk.addEventListener('change', function () {
      let selected = getSelectedColumns();
      if (chk.checked) {
        if (!selected.includes(chk.value)) selected.push(chk.value);
      } else {
        selected = selected.filter(c => c !== chk.value);
      }
      saveSelectedColumns(selected);
      applyColumnVisibility();
    });
  });

  document.getElementById('resetColumnsBtn')?.addEventListener('click', function () {
    saveSelectedColumns(defaultCols);
    applyColumnVisibility();
  });

  // ══ زر التصدير — يبني رابط Excel مع الأعمدة المختارة + كل فلاتر الفورم الحالية ══
  document.getElementById('exportExcelBtn')?.addEventListener('click', function (e) {
    e.preventDefault();

    const selected = getSelectedColumns();
    const form = document.getElementById('reportFilterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();

    for (const [key, value] of formData.entries()) {
      if (value) params.append(key, value);
    }

    selected.forEach(col => params.append('columns[]', col));

    window.location.href = '<?php echo e(route('students.reports.exportExcel')); ?>?' + params.toString();
  });

  // Select2 لفلتر الدبلومة (اختياري إن كان متاحاً)
  if (typeof $ !== 'undefined' && $.fn.select2) {
    $('#diplomaFilterSelect').select2({
      placeholder: 'ابحث بالاسم أو الرمز...',
      allowClear: true,
      width: '100%'
    });
  }

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/students/reports.blade.php ENDPATH**/ ?>