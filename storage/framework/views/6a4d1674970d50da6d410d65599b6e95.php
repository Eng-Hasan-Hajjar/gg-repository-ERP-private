
<?php ($activeModule = 'students'); ?>
<?php $__env->startSection('title', 'الطلاب'); ?>

<?php $__env->startSection('content'); ?>

<style>
  /* ══════════════════════════════════════════
     Select2 تخصيص للثيم
  ══════════════════════════════════════════ */
  .select2-container--default .select2-selection--single {
    height: 38px !important;
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
    display: flex;
    align-items: center;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px !important;
    padding-right: 12px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
  }
  .select2-dropdown {
    border-radius: 8px !important;
    border-color: #ced4da !important;
  }
  .diploma-option-code {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    background: #eff6ff;
    color: #2563eb;
    padding: 1px 8px;
    border-radius: 6px;
    margin-right: 6px;
  }

  /* ── فلاتر متقدمة ── */
  .advanced-filters-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: background .15s, color .15s;
    user-select: none;
  }
  .advanced-filters-toggle:hover { background: #e2e8f0; color: #1e293b; }
  .advanced-filters-toggle .af-icon { transition: transform .3s ease; font-size: 11px; }
  .advanced-filters-toggle.open .af-icon { transform: rotate(180deg); }

  .advanced-filters-panel {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height .35s ease, opacity .25s ease, margin .25s ease;
  }
  .advanced-filters-panel.open {
    max-height: 600px;
    opacity: 1;
    margin-top: 14px;
  }

  .filter-section-label {
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .active-filters-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }
  .active-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: 4px 6px 4px 12px;
    font-size: 12px;
    font-weight: 700;
  }
  .active-filter-chip .remove-chip {
    width: 18px; height: 18px;
    border-radius: 50%;
    background: rgba(37,99,235,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px;
    cursor: pointer;
    transition: background .15s;
  }
  .active-filter-chip .remove-chip:hover { background: rgba(220,38,38,.15); color: #dc2626; }

  .sort-select {
    min-width: 160px;
  }
</style>


<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والدبلومة والحالات</div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-primary rounded-pill px-4 fw-bold" href="<?php echo e(route('students.create')); ?>">
      <i class="bi bi-person-plus"></i> طالب جديد
    </a>
    <a class="btn btn-outline-success rounded-pill px-4 fw-bold" href="<?php echo e(route('students.reports.index')); ?>">
      <i class="bi bi-file-earmark-excel"></i> التقارير
    </a>
  </div>
</div>


<div class="row g-2 mb-3">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #3b82f6 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#3b82f6;"><?php echo e($totalCount); ?></div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">إجمالي الطلاب</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #10b981 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#10b981;"><?php echo e($confirmedCount); ?></div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">مثبّتون</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #f59e0b !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#f59e0b;"><?php echo e($pendingCount); ?></div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">قيد الانتظار</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #8b5cf6 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#8b5cf6;"><?php echo e($myCount); ?></div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">أضفتهم أنا</div>
    </div>
  </div>

  <?php if($needsVerificationCount > 0): ?>
    <div class="col-12">
      <div class="alert border-0 shadow-sm mb-0 d-flex align-items-center gap-3"
        style="background:rgba(245,158,11,.08); border-right:4px solid #f59e0b !important; border-radius:12px;">
        <i class="bi bi-exclamation-triangle-fill fs-4" style="color:#f59e0b;"></i>
        <div>
          <div class="fw-bold" style="color:#92400e;">
            <?php echo e($needsVerificationCount); ?> طالب يحتاج مراجعة بيانات
          </div>
          <div class="small text-muted">
            ينقصهم: الاسم اللاتيني أو تاريخ الميلاد أو رقم الوثيقة
          </div>
        </div>
        <a href="<?php echo e(route('students.index', ['needs_verification' => 1])); ?>" class="btn btn-sm fw-bold ms-auto"
          style="background:#f59e0b; color:#fff; border-radius:8px;">
          عرض القائمة
        </a>
      </div>
    </div>
  <?php endif; ?>
</div>


<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('students.index')); ?>" id="filterForm">

  
  <?php if(count($activeFilters)): ?>
    <div class="active-filters-bar">
      <span class="small fw-bold text-muted"><i class="bi bi-funnel-fill"></i> الفلاتر النشطة:</span>
      <?php $__currentLoopData = $activeFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $af): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="active-filter-chip">
          <?php echo e($af['label']); ?>

          <a href="<?php echo e(request()->fullUrlWithQuery([$af['key'] => null, 'page' => null])); ?>"
            class="remove-chip text-decoration-none" title="إزالة">
            <i class="bi bi-x"></i>
          </a>
        </span>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('students.index')); ?>" class="small text-danger fw-bold text-decoration-none ms-1">
        مسح الكل
      </a>
    </div>
  <?php endif; ?>

  <div class="row g-2 align-items-end">

    
    <?php if($showMyOnly): ?>
      <div class="col-auto">
        <label class="form-label fw-bold d-block" style="font-size:.75rem; color:#64748b;">عرض</label>
        <a href="<?php echo e(request()->boolean('my_only')
      ? request()->fullUrlWithQuery(['my_only' => 0, 'page' => null])
      : request()->fullUrlWithQuery(['my_only' => 1, 'page' => null])); ?>"
          class="btn fw-bold <?php echo e(request()->boolean('my_only') ? 'btn-primary' : 'btn-outline-secondary'); ?>">
          <i class="bi bi-person-fill"></i>
          <?php echo e(request()->boolean('my_only') ? '✓ طلابي فقط' : 'كل الطلاب'); ?>

        </a>
      </div>
    <?php endif; ?>

    <div class="col-12 col-md-3">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">بحث</label>
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
        placeholder="الاسم / الرقم الجامعي / الهاتف">
    </div>

    
    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">الدبلومة</label>
      <select name="diploma_id" class="form-select" id="diplomaFilterSelect">
        <option value="">كل الدبلومات</option>
        <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($d->id); ?>"
            data-code="<?php echo e($d->code); ?>"
            data-branch="<?php echo e($d->branch->name ?? ''); ?>"
            <?php if(request('diploma_id') == $d->id): echo 'selected'; endif; ?>>
            <?php echo e($d->name); ?> — <?php echo e($d->code); ?>

          </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">الفرع</label>
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">حالة الطالب</label>
      <select name="status" class="form-select">
        <option value="">كل الحالات</option>
        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-1">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">التسجيل</label>
      <select name="registration_status" class="form-select">
        <option value="">الكل</option>
        <?php $__currentLoopData = $registrationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('registration_status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-auto d-flex gap-1">
      <button class="btn btn-namaa fw-bold px-3">
        <i class="bi bi-search"></i> تطبيق
      </button>
      <?php if(request()->hasAny(['search', 'diploma_id', 'branch_id', 'status', 'registration_status', 'my_only'])): ?>
        <a href="<?php echo e(route('students.index')); ?>" class="btn btn-outline-secondary fw-bold px-3" title="مسح الفلاتر">
          <i class="bi bi-x-lg"></i>
        </a>
      <?php endif; ?>
    </div>

  </div>

  
  <div class="d-flex align-items-center justify-content-between mt-3">
    <button type="button" class="advanced-filters-toggle" id="advFiltersToggle">
      <i class="bi bi-sliders"></i>
      <span>فلاتر متقدمة</span>
      <i class="bi bi-chevron-down af-icon"></i>
    </button>

    <div class="d-flex align-items-center gap-2">
      <label class="small fw-bold text-muted mb-0">ترتيب حسب</label>
      <select name="sort_by" class="form-select form-select-sm sort-select">
        <option value="created_at" <?php if($sortBy == 'created_at'): echo 'selected'; endif; ?>>تاريخ الإضافة</option>
        <option value="updated_at" <?php if($sortBy == 'updated_at'): echo 'selected'; endif; ?>>آخر تحديث</option>
        <option value="full_name" <?php if($sortBy == 'full_name'): echo 'selected'; endif; ?>>الاسم</option>
        <option value="university_id" <?php if($sortBy == 'university_id'): echo 'selected'; endif; ?>>الرقم الجامعي</option>
      </select>
      <select name="sort_dir" class="form-select form-select-sm" style="width:auto">
        <option value="desc" <?php if($sortDir == 'desc'): echo 'selected'; endif; ?>>تنازلي</option>
        <option value="asc" <?php if($sortDir == 'asc'): echo 'selected'; endif; ?>>تصاعدي</option>
      </select>
    </div>
  </div>

  
  <div class="advanced-filters-panel <?php echo e(request()->hasAny(['nationality','crm_source','crm_stage','language_level','certificate_agreement','date_from','date_to','exam_score_min','exam_score_max','has_message','needs_update','needs_verification','mode']) ? 'open' : ''); ?>"
    id="advFiltersPanel">
    <hr class="my-3">
    <div class="row g-3">

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-laptop"></i> نوع الطالب</div>
        <select name="mode" class="form-select">
          <option value="">الكل</option>
          <?php $__currentLoopData = $modeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('mode') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-flag"></i> الجنسية</div>
        <select name="nationality" class="form-select" id="nationalityFilterSelect">
          <option value="">كل الجنسيات</option>
          <?php $__currentLoopData = $nationalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($nat); ?>" <?php if(request('nationality') == $nat): echo 'selected'; endif; ?>><?php echo e($nat); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      
      <?php if(auth()->user()?->hasPermission('edit_crm_in_student')): ?>
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-graph-up-arrow"></i> مصدر CRM</div>
        <select name="crm_source" class="form-select">
          <option value="">الكل</option>
          <?php $__currentLoopData = $crmSourceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('crm_source') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-signpost-split"></i> مرحلة CRM</div>
        <select name="crm_stage" class="form-select">
          <option value="">الكل</option>
          <?php $__currentLoopData = $crmStageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(request('crm_stage') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <?php endif; ?>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-translate"></i> مستوى اللغة</div>
        <select name="language_level" class="form-select">
          <option value="">الكل</option>
          <?php $__currentLoopData = ['A1','A2','B1','B2','C1','C2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lvl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($lvl); ?>" <?php if(request('language_level') == $lvl): echo 'selected'; endif; ?>><?php echo e($lvl); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-patch-check"></i> اتفاق الشهادة</div>
        <select name="certificate_agreement" class="form-select">
          <option value="">الكل</option>
          <?php $__currentLoopData = ['جراح باشا','بورد الماني','جامعة تركية','ميديبول']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($agr); ?>" <?php if(request('certificate_agreement') == $agr): echo 'selected'; endif; ?>><?php echo e($agr); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-calendar-event"></i> تاريخ الإضافة من</div>
        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-control">
      </div>
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-calendar-event"></i> إلى</div>
        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-control">
      </div>

      
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-award"></i> العلامة (من)</div>
        <input type="number" step="0.01" name="exam_score_min" value="<?php echo e(request('exam_score_min')); ?>"
          class="form-control" placeholder="0">
      </div>
      <div class="col-6 col-md-2">
        <div class="filter-section-label"><i class="bi bi-award"></i> العلامة (إلى)</div>
        <input type="number" step="0.01" name="exam_score_max" value="<?php echo e(request('exam_score_max')); ?>"
          class="form-control" placeholder="100">
      </div>

      
      <div class="col-12">
        <div class="filter-section-label"><i class="bi bi-toggles"></i> خيارات إضافية</div>
        <div class="d-flex gap-3 flex-wrap">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="has_message" value="1" id="hasMsgCheck"
              <?php if(request('has_message')): echo 'checked'; endif; ?>>
            <label class="form-check-label small fw-bold" for="hasMsgCheck">لديه رسالة معلقة</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="needs_update" value="1" id="needsUpdateCheck"
              <?php if(request('needs_update')): echo 'checked'; endif; ?>>
            <label class="form-check-label small fw-bold" for="needsUpdateCheck">يحتاج تحديث (7+ أيام)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="needs_verification" value="1" id="needsVerifyCheck"
              <?php if(request('needs_verification')): echo 'checked'; endif; ?>>
            <label class="form-check-label small fw-bold" for="needsVerifyCheck">يحتاج مراجعة بيانات</label>
          </div>
        </div>
      </div>

      <div class="col-12">
        <button class="btn btn-namaa fw-bold px-4">
          <i class="bi bi-funnel-fill"></i> تطبيق الفلاتر المتقدمة
        </button>
      </div>

    </div>
  </div>

  
  <?php if(request()->boolean('my_only')): ?>
    <input type="hidden" name="my_only" value="1">
  <?php endif; ?>
</form>


<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th class="hide-mobile">#</th>
          <th>الرقم الجامعي</th>
          <th>الاسم</th>
          <th class="hide-mobile">الفرع</th>
          <th class="hide-mobile">الحالة</th>
          <th class="hide-mobile">التسجيل</th>
          <th class="hide-mobile">مثبّت؟</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td class="hide-mobile"><?php echo e($s->id); ?></td>
          <td><code><?php echo e($s->university_id); ?></code></td>
          <td class="fw-semibold">
            <?php echo e($s->full_name); ?>

            <?php if(!empty($s->profile?->message_to_send)): ?>
              <span class="badge bg-warning text-dark ms-1" title="<?php echo e($s->profile->message_to_send); ?>"
                data-bs-toggle="tooltip">📩</span>
            <?php endif; ?>

            <?php if(
                        empty($s->profile?->arabic_full_name) ||
                        empty($s->profile?->birth_date) ||
                        empty($s->profile?->national_id)
                      ): ?>
                      <span class="badge ms-1" style="background:rgba(245,158,11,.15); color:#92400e; font-size:.7rem;" title="يحتاج مراجعة: <?php echo e(collect([
                empty($s->profile?->arabic_full_name) ? 'اسم لاتيني' : null,
                empty($s->profile?->birth_date) ? 'تاريخ ميلاد' : null,
                empty($s->profile?->national_id) ? 'رقم وثيقة' : null,
              ])->filter()->implode(' · ')); ?>" data-bs-toggle="tooltip">
                        ⚠️
                      </span>
            <?php endif; ?>

            <?php if($s->created_by === auth()->id()): ?>
              <span class="badge ms-1" style="background:rgba(139,92,246,.12); color:#7c3aed; font-size:.7rem;">أنا</span>
            <?php endif; ?>
          </td>
          <td class="hide-mobile"><?php echo e($s->branch->name ?? '-'); ?></td>
          <td class="hide-mobile">
            <span class="badge bg-secondary"><?php echo e($s->status_ar); ?></span>
          </td>
          <td class="hide-mobile">
            <?php ($map = ['pending' => 'warning', 'confirmed' => 'success', 'archived' => 'secondary', 'dismissed' => 'danger', 'frozen' => 'info']); ?>
            <span class="badge bg-<?php echo e($map[$s->registration_status] ?? 'secondary'); ?>">
              <?php echo e($s->registration_ar); ?>

            </span>
          </td>
          <td class="hide-mobile">
            <span class="badge bg-<?php echo e($s->is_confirmed ? 'success' : 'secondary'); ?>">
              <?php echo e($s->is_confirmed ? 'نعم' : 'لا'); ?>

            </span>
          </td>
          <td class="text-end">

            <?php if(auth()->user()?->hasPermission('view_student_financials')): ?>
              <button class="btn btn-sm btn-outline-success"
                onclick="showFinancial(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->full_name)); ?>')" title="التفاصيل المالية">
                <i class="bi bi-cash-coin"></i>
              </button>
            <?php endif; ?>

            <button class="btn btn-sm btn-outline-info"
              onclick="showExams(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->full_name)); ?>')" title="نتائج الامتحانات">
              <i class="bi bi-journal-check"></i>
            </button>

            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('students.show', $s)); ?>">
                <i class="bi bi-eye"></i>
              </a>
            <?php endif; ?>

            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('students.edit', $s)); ?>">
                <i class="bi bi-pencil"></i>
              </a>
            <?php endif; ?>

            <?php if(auth()->user()?->hasPermission('delete_students')): ?>
              <form method="POST" action="<?php echo e(route('students.destroy', $s)); ?>" class="d-inline"
                onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            <?php endif; ?>

            <?php if(!$s->is_confirmed): ?>
              <form method="POST" action="<?php echo e(route('students.confirm', $s)); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-success">تثبيت</button>
              </form>
            <?php endif; ?>

          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="8" class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
            لا يوجد طلاب مطابقون للفلتر الحالي
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div class="text-muted small">
    عرض <?php echo e($students->firstItem() ?? 0); ?>–<?php echo e($students->lastItem() ?? 0); ?>

    من أصل <?php echo e($students->total()); ?> طالب
  </div>
  <?php echo e($students->links()); ?>

</div>


<div class="modal fade" id="studentInfoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalTitle">تفاصيل الطالب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody">
        <div class="text-center py-4">
          <div class="spinner-border text-primary"></div>
          <div class="mt-2 text-muted">جاري التحميل...</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function showFinancial(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-cash-coin text-success me-2"></i> التفاصيل المالية — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
    new bootstrap.Modal(document.getElementById('studentInfoModal')).show();
    fetch('/students/' + id + '/modal/financial').then(r => r.text()).then(html => {
      document.getElementById('modalBody').innerHTML = html;
    });
  }
  function showExams(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-journal-check text-info me-2"></i> نتائج الامتحانات — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info"></div></div>';
    new bootstrap.Modal(document.getElementById('studentInfoModal')).show();
    fetch('/students/' + id + '/modal/exams').then(r => r.text()).then(html => {
      document.getElementById('modalBody').innerHTML = html;
    });
  }

  document.addEventListener('DOMContentLoaded', function () {

    /* ══ Select2 لفلتر الدبلومة — يعرض الاسم + الرمز، بحث ذكي ══ */
    if (typeof $ !== 'undefined' && $.fn.select2) {
      $('#diplomaFilterSelect').select2({
        placeholder: 'ابحث بالاسم أو الرمز...',
        allowClear: true,
        width: '100%',
        templateResult: function (data) {
          if (!data.id) return data.text;
          var code = $(data.element).data('code');
          var branch = $(data.element).data('branch');
          var $result = $(
            '<div>' +
              '<span class="diploma-option-code">' + (code || '') + '</span>' +
              '<span>' + data.element.text.split(' — ')[0] + '</span>' +
              (branch ? '<div class="small text-muted"><i class="bi bi-building"></i> ' + branch + '</div>' : '') +
            '</div>'
          );
          return $result;
        },
        templateSelection: function (data) {
          if (!data.id) return data.text;
          var code = $(data.element).data('code');
          return data.element.text.split(' — ')[0] + (code ? ' (' + code + ')' : '');
        },
        language: {
          noResults: function () { return 'لا توجد دبلومات مطابقة'; },
          searching: function () { return 'جاري البحث...'; }
        }
      });

      $('#nationalityFilterSelect').select2({
        placeholder: 'ابحث عن جنسية...',
        allowClear: true,
        width: '100%',
        language: {
          noResults: function () { return 'لا توجد نتائج'; },
          searching: function () { return 'جاري البحث...'; }
        }
      });
    }

    /* ══ طي/توسيع الفلاتر المتقدمة ══ */
    var toggle = document.getElementById('advFiltersToggle');
    var panel  = document.getElementById('advFiltersPanel');

    if (panel.classList.contains('open')) {
      toggle.classList.add('open');
    }

    toggle.addEventListener('click', function () {
      panel.classList.toggle('open');
      toggle.classList.toggle('open');
    });

    /* ══ تفعيل tooltips ══ */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) {
      new bootstrap.Tooltip(el);
    });

    /* ══ إرسال تلقائي عند تغيير الترتيب ══ */
    document.querySelector('select[name="sort_by"]')?.addEventListener('change', function () {
      document.getElementById('filterForm').submit();
    });
    document.querySelector('select[name="sort_dir"]')?.addEventListener('change', function () {
      document.getElementById('filterForm').submit();
    });

  });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/students/index.blade.php ENDPATH**/ ?>