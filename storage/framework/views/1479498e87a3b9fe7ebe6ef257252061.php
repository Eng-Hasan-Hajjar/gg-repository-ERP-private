<?php echo csrf_field(); ?>

<?php if(isset($student) && $student->exists): ?>
  <?php echo method_field('PUT'); ?>
<?php endif; ?>



<?php if($errors->any()): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($e); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<?php
  $selectedDiplomas = old('diploma_ids', $studentDiplomas->pluck('id')->toArray());
?>




<style>
  /* ───── Diploma Picker ───── */
  .diploma-picker {
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    background: #f8fafc;
  }

  .diploma-search-box {
    position: relative;
    margin-bottom: 12px;
  }

  .diploma-search-box input {
    padding-right: 40px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
  }

  .diploma-search-box .search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
  }

  .diploma-list {
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
  }

  .diploma-list-item {
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
    font-size: 14px;
  }

  .diploma-list-item:last-child {
    border-bottom: none;
  }

  .diploma-list-item:hover {
    background: #eff6ff;
  }

  .diploma-list-item.disabled {
    opacity: .4;
    pointer-events: none;
    background: #f1f5f9;
  }

  .diploma-list-item .d-name {
    font-weight: 600;
    color: #1e293b;
  }

  .diploma-list-item .d-meta {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .diploma-list-empty {
    padding: 20px;
    text-align: center;
    color: #94a3b8;
    font-size: 14px;
  }

  /* ───── Selected Cards ───── */
  .selected-diplomas {
    margin-top: 16px;
  }

  .selected-diploma-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    transition: box-shadow .15s;
  }

  .selected-diploma-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
  }

  .sd-info {
    flex: 1;
    min-width: 150px;
  }

  .sd-name {
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
  }

  .sd-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
    margin-top: 4px;
  }

  .sd-badge.online {
    background: #dbeafe;
    color: #2563eb;
  }

  .sd-badge.onsite {
    background: #d1fae5;
    color: #059669;
  }

  .sd-remove {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fee2e2;
    color: #dc2626;
    cursor: pointer;
    transition: background .15s;
  }

  .sd-remove:hover {
    background: #fca5a5;
  }

  .no-diplomas-msg {
    padding: 16px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
  }
</style>




<div class="row g-3">

  
  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" value="<?php echo e(old('full_name', $student->full_name ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

    <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  
  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم</label>
    <input name="first_name" value="<?php echo e(old('first_name', $student->first_name ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>

    <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  
  <div class="col-md-4">
    <label class="form-label fw-bold">الكنية</label>
    <input name="last_name" value="<?php echo e(old('last_name', $student->last_name ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

    <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>



  
  <div class="col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" value="<?php echo e(old('phone', $student->phone ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  
  <div class="col-md-4">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" value="<?php echo e(old('whatsapp', $student->whatsapp ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

    <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>


  <div class="col-md-4">
    <label class="form-label fw-bold">اتفاق الشهادة الممنوحة</label>
    <select name="certificate_agreement" class="form-select">
      <option value="">-- لا يوجد اتفاق --</option>
      <option value="جراح باشا" <?php if(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جراح باشا'): echo 'selected'; endif; ?>>جراح باشا</option>
      <option value="بورد الماني" <?php if(old('certificate_agreement', $student->certificate_agreement ?? '') == 'بورد الماني'): echo 'selected'; endif; ?>>بورد الماني</option>
      <option value="جامعة تركية" <?php if(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جامعة تركية'): echo 'selected'; endif; ?>>جامعة تركية</option>
    </select>
  </div>


  
  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>

    <select name="branch_id" class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <option value="">-- اختر الفرع --</option>

      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($branch->id); ?>" <?php if(old('branch_id', $student->branch_id ?? '') == $branch->id): echo 'selected'; endif; ?>>
          <?php echo e($branch->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </select>

    <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>





  
  <div class="col-md-4" hidden>
    <label class="form-label fw-bold">نوع الطالب</label>
    <select name="mode" class="form-select">
      <option value="onsite" selected>حضوري</option>
      <option value="online">أونلاين</option>
    </select>
  </div>


  <div class="col-md-4">
    <label class="form-label fw-bold">حالة الطالب</label>

    <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <option value="">-- اختر حالة الطالب --</option>

      <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => $stLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($st); ?>" <?php if(old('status', $student->status ?? '') == $st): echo 'selected'; endif; ?>>
          <?php echo e($stLabel); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </select>

    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>


  
  <div class="col-12">
    <label class="form-label fw-bold fs-6">
      <i class="bi bi-mortarboard text-primary"></i> الدبلومات *
    </label>

    <div class="diploma-picker">

      
      <div class="diploma-search-box">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="diplomaSearch" class="form-control" placeholder="ابحث عن دبلومة بالاسم أو الكود...">
      </div>

      
      <div class="diploma-list" id="diplomaList">
        <div class="diploma-list-empty" id="diplomaEmpty" style="display:none">
          <i class="bi bi-search"></i> لا توجد نتائج
        </div>
      </div>

      
      <div class="selected-diplomas" id="selectedDiplomas">
        <div class="no-diplomas-msg" id="noDiplomasMsg">
          <i class="bi bi-info-circle"></i> لم يتم اختيار أي دبلومة — اختر من القائمة أعلاه
        </div>
      </div>

    </div>

    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً. الدبلومات مفلترة حسب الفرع المختار.</div>

    
    <div id="diplomaHiddenInputs"></div>
  </div>

  <div id="diplomas-details-container"></div>




  
  <?php if(auth()->user()?->hasPermission('edit_crm_in_student')): ?>
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

          <div class="row g-3  ">



            
            <div class="col-md-3">
              <label class="form-label fw-bold">الاسم باللاتيني</label>
              <input id="latin_first_name" class="form-control" placeholder="Ahmad">
            </div>

            
            <div class="col-md-3">
              <label class="form-label fw-bold">الكنية باللاتيني</label>
              <input id="latin_last_name" class="form-control" placeholder="Khalil">
            </div>

            
            <div class="col-md-6">
              <label class="form-label fw-bold">الاسم الكامل باللاتيني</label>
              <input name="profile[arabic_full_name]" id="latin_full_name"
                value="<?php echo e(old('profile.arabic_full_name', $profile['arabic_full_name'] ?? '')); ?>"
                class="form-control <?php $__errorArgs = ['profile.arabic_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" readonly>
              <?php $__errorArgs = ['profile.arabic_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


            <div class="col-md-3 d-none">
              <label class="form-label fw-bold">تاريخ أول تواصل</label>
              <input type="date" name="crm[first_contact_date]" class="form-control"
                value="<?php echo e($crm['first_contact_date'] ?? ''); ?>">
            </div>







            <div class="col-md-6">
              <label class="form-label fw-bold">الجهة/المؤسسة</label>
              <input name="crm[organization]" class="form-control <?php $__errorArgs = ['crm.organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('crm.organization', $crm['organization'] ?? '')); ?>">

              <?php $__errorArgs = ['crm.organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


            
            <div class="col-md-3">
              <label class="form-label fw-bold">تاريخ التولد</label>

              <input type="date" name="profile[birth_date]"
                value="<?php echo e(old('profile.birth_date', $profile['birth_date'] ?? '')); ?>"
                class="form-control <?php $__errorArgs = ['profile.birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


            
            <div class="col-md-3">
              <label class="form-label fw-bold">الرقم الوطني</label>

              <input name="profile[national_id]" value="<?php echo e(old('profile.national_id', $profile['national_id'] ?? '')); ?>"
                class="form-control <?php $__errorArgs = ['profile.national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


            <div class="col-md-3">
              <label class="form-label fw-bold">المصدر</label>

              <div class="has-validation">
                <select name="crm[source]" class="form-select <?php $__errorArgs = ['crm.source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                  <option value="">-- اختر المصدر --</option>

                  <?php
                    $sourceOptions = [
                      'ad' => 'إعلان مدفوع',
                      'referral' => 'إحالة / توصية',
                      'social' => 'وسائل التواصل الاجتماعي',
                      'website' => 'الموقع الإلكتروني',
                      'expo' => 'معرض / فعالية',
                      'other' => 'أخرى',
                    ];
                  ?>

                  <?php $__currentLoopData = $sourceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src => $srcLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($src); ?>" <?php if(old('crm.source', $crm['source'] ?? '') === $src): echo 'selected'; endif; ?>>
                      <?php echo e($srcLabel); ?>

                    </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php $__errorArgs = ['crm.source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>



            <div class="col-md-3">
              <label class="form-label fw-bold">المرحلة</label>

              <div class="has-validation">
                <select name="crm[stage]" class="form-select <?php $__errorArgs = ['crm.stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                  <option value="">-- اختر المرحلة --</option>

                  <?php
                    $stageOptions = [
                      'new' => 'جديد',
                      'follow_up' => 'متابعة',
                      'interested' => 'مهتم',
                      'registered' => 'مسجل',
                      'rejected' => 'مرفوض',
                      'postponed' => 'مؤجل',
                    ];
                  ?>

                  <?php $__currentLoopData = $stageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => $stageLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if(old('crm.stage', $crm['stage'] ?? '') === $st): echo 'selected'; endif; ?>>
                      <?php echo e($stageLabel); ?>

                    </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php $__errorArgs = ['crm.stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>


             
            <div class="col-md-4">
              <label class="form-label fw-bold">الدراسة</label>
              <input name="crm[study]" class="form-control <?php $__errorArgs = ['crm.study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('crm.study', $crm['study'] ?? '')); ?>">

              <?php $__errorArgs = ['crm.study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>



            <div class="col-md-4">
                <label class="form-label fw-bold">المستوى التعليمي</label>

                <select name="profile[education_level]"
                        class="form-select <?php $__errorArgs = ['profile.education_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                    <option value="">-- اختر المستوى --</option>

                    <?php
                    $educationLevels = [
                        'ابتدائي'   => 'ابتدائي',
                        'اعدادي'    => 'اعدادي',
                        'ثانوي'     => 'ثانوي',
                        'بكالوريوس' => 'بكالوريوس',
                        'ماجستير'   => 'ماجستير',
                        'دكتوراه'   => 'دكتوراه',
                        'لا يوجد'   => 'لا يوجد',
                    ];
                    ?>

                    <?php $__currentLoopData = $educationLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $eduLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>"
                            <?php if(old('profile.education_level', $profile['education_level'] ?? '') == $val): echo 'selected'; endif; ?>>
                            <?php echo e($eduLabel); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

                <?php $__errorArgs = ['profile.education_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
 


            <div class="col-12">
              <label class="form-label fw-bold">الاحتياج</label>

              <textarea name="crm[need]" class="form-control <?php $__errorArgs = ['crm.need'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                rows="2"><?php echo e(old('crm.need', $crm['need'] ?? '')); ?></textarea>

              <?php $__errorArgs = ['crm.need'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


                      <div class="col-md-4">
            <label class="form-label fw-bold">ملف الهوية</label>
            <input type="file" name="profile[identity_file]"
              class="form-control <?php $__errorArgs = ['profile.identity_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.png,.jpg">

            <?php $__errorArgs = ['profile.identity_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['identity_file_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['identity_file_path'])); ?>">عرض الهوية الحالية</a>
              </div>
            <?php endif; ?>
          </div>



            <div class="col-12">
              <label class="form-label fw-bold">ملاحظات CRM</label>

              <textarea name="crm[notes]" class="form-control <?php $__errorArgs = ['crm.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                rows="2"><?php echo e(old('crm.notes', $crm['notes'] ?? '')); ?></textarea>

              <?php $__errorArgs = ['crm.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>




          </div>
        </div>
      </div>
    </div>




  <?php endif; ?>



  
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">الملف التفصيلي للطالب</h6>

        <div class="row g-3">

          
          <div class="col-md-3">
            <label class="form-label fw-bold">الجنسية</label>

            <select name="profile[nationality]" class="form-select <?php $__errorArgs = ['profile.nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="nationalitySelect">
              <option value="">-- اختر الجنسية --</option>

              <?php
                $nationalities = [
                  'سورية',
                  'سعودية',
                  'مصرية',
                  'عراقية',
                  'أردنية',
                  'لبنانية',
                  'كويتية',
                  'إماراتية',
                  'قطرية',
                  'بحرينية',
                  'عُمانية',
                  'يمنية',
                  'ليبية',
                  'تونسية',
                  'جزائرية',
                  'مغربية',
                  'موريتانية',
                  'سودانية',
                  'صومالية',
                  'فلسطينية',
                  'قمرية',
                  'جيبوتية',
                  'تركية',
                  'إيرانية',
                  'أفغانية',
                  'باكستانية',
                  'أذربيجانية',
                  'كازاخستانية',
                  'أوزبكستانية',
                  'ألمانية',
                  'فرنسية',
                  'بريطانية',
                  'إيطالية',
                  'إسبانية',
                  'هولندية',
                  'بلجيكية',
                  'سويدية',
                  'نرويجية',
                  'دنماركية',
                  'سويسرية',
                  'نمساوية',
                  'يونانية',
                  'بولندية',
                  'رومانية',
                  'روسية',
                  'أمريكية',
                  'كندية',
                  'أسترالية',
                  'هندية',
                  'صينية',
                  'يابانية',
                  'كورية',
                  'برازيلية',
                  'إندونيسية',
                  'ماليزية',
                  'نيجيرية',
                  'إثيوبية',
                  'جنوب أفريقية',
                  'أرجنتينية',
                ];
              ?>

              <?php $__currentLoopData = $nationalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($nat); ?>" <?php if(old('profile.nationality', $profile['nationality'] ?? '') == $nat): echo 'selected'; endif; ?>>
                  <?php echo e($nat); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <?php $__errorArgs = ['profile.nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>















          <div class="col-md-3">
            <label class="form-label fw-bold">مستوى اللغة</label>

            <input name="profile[level]" value="<?php echo e(old('profile.level', $profile['level'] ?? '')); ?>"
              class="form-control <?php $__errorArgs = ['profile.level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              placeholder="مثال: مبتدئ / متوسط / متقدم">

            <?php $__errorArgs = ['profile.level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>



          
          <div class="col-md-3">
            <label class="form-label fw-bold">ستاج بالولاية</label>
            <div class="form-check mt-1">
              <input type="checkbox" class="form-check-input" id="stageCheck" <?php echo e(!empty($profile['stage_in_state'] ?? '') ? 'checked' : ''); ?>>
              <label class="form-check-label" for="stageCheck">يوجد ستاج</label>
            </div>
          </div>

          <div class="col-md-3" id="stageField"
            style="<?php echo e(!empty($profile['stage_in_state'] ?? '') ? '' : 'display:none'); ?>">
            <label class="form-label fw-bold">مرحلة/ولاية الستاج</label>
            <input name="profile[stage_in_state]"
              value="<?php echo e(old('profile.stage_in_state', $profile['stage_in_state'] ?? '')); ?>"
              class="form-control <?php $__errorArgs = ['profile.stage_in_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              placeholder="مثال: دمشق - سنة أولى">
            <?php $__errorArgs = ['profile.stage_in_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>




          <div class="col-md-3">
            <label class="form-label fw-bold">العمل</label>
            <input name="crm[job]" class="form-control <?php $__errorArgs = ['crm.job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              value="<?php echo e(old('crm.job', $crm['job'] ?? '')); ?>">


            <?php $__errorArgs = ['crm.job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-bold">مسؤول التواصل</label>
            <input class="form-control" value="<?php echo e($student->crmInfo->creator->name ?? '-'); ?>" disabled>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">البلد</label>
            <input name="crm[country]" class="form-control <?php $__errorArgs = ['crm.country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              value="<?php echo e(old('crm.country', $crm['country'] ?? '')); ?>">


            <?php $__errorArgs = ['crm.country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">المحافظة</label>
            <input name="crm[province]" class="form-control  <?php $__errorArgs = ['crm.province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              value="<?php echo e(old('crm.province', $crm['province'] ?? '')); ?>">


            <?php $__errorArgs = ['crm.province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


          </div>


          <div class="col-md-4">
            <label class="form-label fw-bold">العلامة الامتحانية</label>

            <input type="number" step="0.01" name="profile[exam_score]"
              value="<?php echo e(old('profile.exam_score', $profile['exam_score'] ?? '')); ?>"
              class="form-control <?php $__errorArgs = ['profile.exam_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

            <?php $__errorArgs = ['profile.exam_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="col-12">
            <label class="form-label fw-bold">ملاحظات</label>

            <textarea name="profile[notes]" rows="2"
              class="form-control <?php $__errorArgs = ['profile.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('profile.notes', $profile['notes'] ?? '')); ?></textarea>

            <?php $__errorArgs = ['profile.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="col-12">
            <label class="form-label fw-bold">الرسالة التي سيتم ارسالها لاحقاً للطالب</label>

            <textarea name="profile[message_to_send]" rows="2"
              class="form-control <?php $__errorArgs = ['profile.message_to_send'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('profile.message_to_send', $profile['message_to_send'] ?? '')); ?></textarea>

            <?php $__errorArgs = ['profile.message_to_send'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>






          
          
          <div class="col-12">
            <hr class="my-2">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">صورة الطالب</label>
            <input type="file" name="profile[photo]" class="form-control <?php $__errorArgs = ['profile.photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              accept="image/*">

            <?php $__errorArgs = ['profile.photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['photo_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['photo_path'])); ?>">عرض الصورة الحالية</a>
              </div>
            <?php endif; ?>
          </div>


          <div class="col-md-4">
            <label class="form-label fw-bold">ملف المعلومات</label>
            <input type="file" name="profile[info_file]"
              class="form-control <?php $__errorArgs = ['profile.info_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.doc,.docx,.png,.jpg">

            <?php $__errorArgs = ['profile.info_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['info_file_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['info_file_path'])); ?>">عرض الملف الحالي</a>
              </div>
            <?php endif; ?>
          </div>





          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">شهادة حضور</label>
            <input type="file" name="profile[attendance_certificate]"
              class="form-control <?php $__errorArgs = ['profile.attendance_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              accept=".pdf,.png,.jpg">

            <?php $__errorArgs = ['profile.attendance_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['attendance_certificate_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['attendance_certificate_path'])); ?>">عرض شهادة
                  الحضور</a>
              </div>
            <?php endif; ?>
          </div>


          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">الشهادة PDF</label>
            <input type="file" name="profile[certificate_pdf]"
              class="form-control <?php $__errorArgs = ['profile.certificate_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf">

            <?php $__errorArgs = ['profile.certificate_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['certificate_pdf_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['certificate_pdf_path'])); ?>">عرض شهادة PDF</a>
              </div>
            <?php endif; ?>
          </div>


          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">الشهادة (كرتون)</label>
            <input type="file" name="profile[certificate_card]"
              class="form-control <?php $__errorArgs = ['profile.certificate_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.png,.jpg">

            <?php $__errorArgs = ['profile.certificate_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if(!empty($profile['certificate_card_path'])): ?>
              <div class="small mt-2">
                <a target="_blank" href="<?php echo e(asset('storage/' . $profile['certificate_card_path'])); ?>">عرض شهادة
                  الكرتون</a>
              </div>
            <?php endif; ?>
          </div>




          <?php if($studentDiplomas->count()): ?>
            <hr>
            <h5 class="fw-bold">تفاصيل وملفات حسب الدبلومة</h5>

            <?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

              <div class="card p-3 mb-3 border">

                <h6 class="fw-bold"><?php echo e($d->name); ?></h6>

                <input type="hidden" name="diplomas[<?php echo e($d->id); ?>][id]" value="<?php echo e($d->id); ?>">

                <div class="row g-3">

                  <div class="col-md-3">
                    <label>الحالة في الدبلومة</label>
                    <select name="diplomas[<?php echo e($d->id); ?>][status]" class="form-select">
                      <option value="active" <?php if($d->pivot->status == 'active'): echo 'selected'; endif; ?>>نشط</option>
                      <option value="waiting" <?php if($d->pivot->status == 'waiting'): echo 'selected'; endif; ?>>بانتظار</option>
                      <option value="finished" <?php if($d->pivot->status == 'finished'): echo 'selected'; endif; ?>>منتهي</option>
                    </select>
                  </div>

                  <div class="col-md-3  d-none">
                    <label>التقييم (1–5)</label>
                    <input type="number" min="1" max="5" name="diplomas[<?php echo e($d->id); ?>][rating]"
                      value="<?php echo e($d->pivot->rating); ?>" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label>تاريخ انتهاء الدبلومة</label>
                    <input type="date" name="diplomas[<?php echo e($d->id); ?>][ended_at]" value="<?php echo e($d->pivot->ended_at); ?>"
                      class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label style="margin-top: 10%;">تم تسليم الشهادة الكرتون ؟</label>
                    <input type="checkbox" name="diplomas[<?php echo e($d->id); ?>][certificate_delivered]"
                      <?php if($d->pivot->certificate_delivered): echo 'checked'; endif; ?>>
                  </div>

                  <div class="col-md-6">
                    <label>ملاحظات خاصة بهذه الدبلومة</label>
                    <textarea name="diplomas[<?php echo e($d->id); ?>][notes]" class="form-control"
                      rows="2"><?php echo e($d->pivot->notes); ?></textarea>
                  </div>

                  <div class="col-md-4">
                    <label>شهادة الحضور</label>
                    <input type="file" name="diplomas[<?php echo e($d->id); ?>][attendance_certificate]" class="form-control">

                    <?php if($d->pivot->attendance_certificate_path): ?>
                      <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->attendance_certificate_path)); ?>">
                        عرض الحالي
                      </a>
                    <?php endif; ?>
                  </div>

                  <div class="col-md-4">
                    <label>الشهادة PDF</label>
                    <input type="file" name="diplomas[<?php echo e($d->id); ?>][certificate_pdf]" class="form-control" accept=".pdf">

                    <?php if($d->pivot->certificate_pdf_path): ?>
                      <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->certificate_pdf_path)); ?>">
                        عرض الحالي
                      </a>
                    <?php endif; ?>
                  </div>

                  <div class="col-md-4">
                    <label>كرت الشهادة</label>
                    <input type="file" name="diplomas[<?php echo e($d->id); ?>][certificate_card]" class="form-control">

                    <?php if($d->pivot->certificate_card_path): ?>
                      <a target="_blank" href="<?php echo e(asset('storage/' . $d->pivot->certificate_card_path)); ?>">
                        عرض الحالي
                      </a>
                    <?php endif; ?>
                  </div>

                </div>
              </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>







        </div>

      </div>
    </div>
  </div>


</div>



<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="<?php echo e(route('students.index')); ?>">إلغاء</a>
</div>


















<?php
  $diplomasJson = $diplomas->map(fn($d) => [
    'id' => $d->id,
    'name' => $d->name,
    'code' => $d->code,
    'type' => $d->type,
    'branch_id' => $d->branch_id,
    'branch_name' => $d->branch->name ?? '-',
  ])->values();

  $preloadedJson = $studentDiplomasJson ?? '[]';
?>

<script>
  var modeSelect = document.querySelector('[name="mode"]');
  if (modeSelect && !modeSelect.value) modeSelect.value = 'onsite';
  (function () {
    var allDiplomas = <?php echo e(Js::from($diplomasJson)); ?>;
    var preloaded = <?php echo e(Js::from(json_decode($preloadedJson))); ?>;

    var diplomasByName = {};
    allDiplomas.forEach(function (d) {
      if (!diplomasByName[d.name]) {
        diplomasByName[d.name] = { name: d.name, type: d.type, variants: [] };
      }
      diplomasByName[d.name].variants.push({ id: d.id, code: d.code, branch_id: d.branch_id, branch_name: d.branch_name });
    });

    var diplomaNames = Object.values(diplomasByName);
    var selectedDiplomas = {};

    preloaded.forEach(function (d) {
      if (diplomasByName[d.name]) {
        selectedDiplomas[d.name] = { variantId: d.id, code: d.code, branch_id: d.branch_id };
      }
    });

    var diplomaList = document.getElementById('diplomaList');
    var diplomaSearch = document.getElementById('diplomaSearch');
    var diplomaEmpty = document.getElementById('diplomaEmpty');
    var selectedContainer = document.getElementById('selectedDiplomas');
    var noDiplomasMsg = document.getElementById('noDiplomasMsg');
    var hiddenInputs = document.getElementById('diplomaHiddenInputs');
    var branchSelect = document.querySelector('[name="branch_id"]');
    var detailsContainer = document.getElementById('diplomas-details-container');

    function renderList(filter) {
      filter = filter || '';
      diplomaList.querySelectorAll('.diploma-list-item').forEach(function (el) { el.remove(); });
      var branchId = branchSelect ? branchSelect.value : '';
      var count = 0;

      diplomaNames.forEach(function (group) {
        var nameOk = !filter || group.name.indexOf(filter) !== -1 ||
          group.variants.some(function (v) { return v.code && v.code.indexOf(filter) !== -1; });

        var branchOk = !branchId ||
          group.variants.some(function (v) { return v.branch_id == branchId; });

        if (!nameOk || !branchOk) return;

        var isSelected = !!selectedDiplomas[group.name];
        var codes = group.variants.map(function (v) { return v.code; }).filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');
        var branches = group.variants.map(function (v) { return v.branch_name; }).filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');

        var item = document.createElement('div');
        item.className = 'diploma-list-item' + (isSelected ? ' disabled' : '');
        item.innerHTML =
          '<div><span class="d-name">' + group.name + '</span>' +
          '<div class="d-meta"><span><i class="bi bi-tag"></i> ' + codes + '</span>' +
          '<span><i class="bi bi-building"></i> ' + branches + '</span></div></div>' +
          '<div><span class="badge ' + (group.type === 'online' ? 'bg-primary' : 'bg-success') + '" style="font-size:11px">' +
          (group.type === 'online' ? 'أونلاين' : 'حضوري') + '</span>' +
          (isSelected ? '<i class="bi bi-check-circle-fill text-success ms-2"></i>' : '<i class="bi bi-plus-circle text-primary ms-2"></i>') +
          '</div>';

        if (!isSelected) {
          item.addEventListener('click', function () { addDiploma(group); });
        }
        diplomaList.appendChild(item);
        count++;
      });

      diplomaEmpty.style.display = count === 0 ? 'block' : 'none';
    }

    function addDiploma(group) {
      var branchId = branchSelect ? branchSelect.value : '';
      var variants = group.variants;
      if (branchId) {
        variants = variants.filter(function (v) { return v.branch_id == branchId; });
      }
      if (!variants.length) return;

      var def = variants[0];
      selectedDiplomas[group.name] = { variantId: def.id, code: def.code, branch_id: def.branch_id };

      renderSelected();
      renderList(diplomaSearch ? diplomaSearch.value : '');
      updateHidden();
      renderDetails();
    }

    function removeDiploma(name) {
      delete selectedDiplomas[name];
      renderSelected();
      renderList(diplomaSearch ? diplomaSearch.value : '');
      updateHidden();
      renderDetails();
    }

    function renderSelected() {
      selectedContainer.querySelectorAll('.selected-diploma-card').forEach(function (el) { el.remove(); });
      var names = Object.keys(selectedDiplomas);
      if (!names.length) { noDiplomasMsg.style.display = 'block'; return; }
      noDiplomasMsg.style.display = 'none';

      names.forEach(function (name, index) {
        var sel = selectedDiplomas[name];
        var group = diplomasByName[name];
        if (!group) return;

        var card = document.createElement('div');
        card.className = 'selected-diploma-card';
        card.innerHTML =
          '<div class="sd-info">' +
          '<div class="sd-name">' +
          (index === 0 ? '<span class="badge bg-warning text-dark me-1" style="font-size:10px">رئيسية</span>' : '') +
          name + '</div>' +
          '<span class="sd-badge ' + (group.type === 'online' ? 'online' : 'onsite') + '">' +
          '<i class="bi bi-' + (group.type === 'online' ? 'wifi' : 'geo-alt') + '"></i> ' +
          (group.type === 'online' ? 'أونلاين' : 'حضوري') + '</span>' +
          '<div class="d-meta mt-1" style="font-size:12px;color:#94a3b8"><i class="bi bi-tag"></i> ' + sel.code + '</div>' +
          '</div>' +
          '<div class="sd-remove" data-name="' + name + '" title="إزالة"><i class="bi bi-x-lg"></i></div>';

        card.querySelector('.sd-remove').addEventListener('click', function () {
          removeDiploma(this.dataset.name);
        });
        selectedContainer.appendChild(card);
      });
    }

    function updateHidden() {
      hiddenInputs.innerHTML = '';
      Object.values(selectedDiplomas).forEach(function (sel) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'diploma_ids[]';
        input.value = sel.variantId;
        hiddenInputs.appendChild(input);
      });
    }

    function renderDetails() {
      if (!detailsContainer) return;
      var existing = {};
      detailsContainer.querySelectorAll('[data-diploma-id]').forEach(function (el) {
        existing[el.dataset.diplomaId] = true;
      });

      Object.values(selectedDiplomas).forEach(function (sel) {
        var id = String(sel.variantId);
        if (existing[id]) return;

        var group = null;
        Object.values(diplomasByName).forEach(function (g) {
          g.variants.forEach(function (v) { if (v.id == id) group = g; });
        });
        if (!group) return;

        var div = document.createElement('div');
        div.className = 'card p-3 mb-3 border';
        div.dataset.diplomaId = id;
        div.innerHTML =
          '<h6 class="fw-bold">' + group.name + ' <small class="text-muted">(' + sel.code + ')</small></h6>' +
          '<input type="hidden" name="diplomas[' + id + '][id]" value="' + id + '">' +
          '<div class="row g-3">' +
          '<div class="col-md-3"><label>الحالة</label>' +
          '<select name="diplomas[' + id + '][status]" class="form-select">' +
          '<option value="active">نشط</option><option value="waiting">بانتظار</option><option value="finished">منتهي</option>' +
          '</select></div>' +
          '<div class="col-md-3"><label>تاريخ الانتهاء</label>' +
          '<input type="date" name="diplomas[' + id + '][ended_at]" class="form-control"></div>' +
          '<div class="col-md-6"><label>ملاحظات</label>' +
          '<textarea name="diplomas[' + id + '][notes]" class="form-control" rows="2"></textarea></div>' +
          '</div>';

        detailsContainer.appendChild(div);
      });
    }

    if (diplomaSearch) {
      diplomaSearch.addEventListener('input', function () { renderList(this.value); });
    }

    if (branchSelect) {
      branchSelect.addEventListener('change', function () {
        var modeSelect = document.querySelector('[name="mode"]');
        var text = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';
        if (modeSelect) {
          modeSelect.value = text.includes('أونلاين') ? 'online' : 'onsite';
        }


        var newBranch = this.value;
        Object.keys(selectedDiplomas).forEach(function (name) {
          if (newBranch && selectedDiplomas[name].branch_id != newBranch) {
            delete selectedDiplomas[name];
          }
        });
        renderList(diplomaSearch ? diplomaSearch.value : '');
        renderSelected();
        updateHidden();
      });
    }

    document.addEventListener('DOMContentLoaded', function () {





      var stageCheck = document.getElementById('stageCheck');
      var stageField = document.getElementById('stageField');
      var stageInput = stageField ? stageField.querySelector('input') : null;

      if (stageCheck && stageField) {
        stageCheck.addEventListener('change', function () {
          if (this.checked) {
            stageField.style.display = '';
          } else {
            stageField.style.display = 'none';
            if (stageInput) stageInput.value = ''; // مسح القيمة عند إلغاء التحديد
          }
        });
      }




      if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#nationalitySelect').select2({
          placeholder: 'ابحث عن جنسية...',
          allowClear: true,
          width: '100%',
          language: {
            noResults: function () { return 'لا توجد نتائج'; },
            searching: function () { return 'جاري البحث...'; }
          }
        });
      }







      var latinFirst = document.getElementById('latin_first_name');
      var latinLast = document.getElementById('latin_last_name');
      var latinFull = document.getElementById('latin_full_name');

      function mergeLatinName() {
        var first = latinFirst ? latinFirst.value.trim() : '';
        var last = latinLast ? latinLast.value.trim() : '';
        if (latinFull) latinFull.value = (first + ' ' + last).trim();
      }

      // عند تحميل الصفحة — تقسيم القيمة الموجودة للحقلين
      if (latinFull && latinFull.value) {
        var parts = latinFull.value.trim().split(/\s+/);
        if (latinFirst) latinFirst.value = parts[0] || '';
        if (latinLast) latinLast.value = parts.slice(1).join(' ') || '';
      }

      if (latinFirst) latinFirst.addEventListener('input', mergeLatinName);
      if (latinLast) latinLast.addEventListener('input', mergeLatinName);



      var fullName = document.querySelector('input[name="full_name"]');
      var firstName = document.querySelector('input[name="first_name"]');
      var lastName = document.querySelector('input[name="last_name"]');
      if (fullName && firstName && lastName) {
        firstName.setAttribute('readonly', true);
        lastName.setAttribute('readonly', true);
        fullName.addEventListener('input', function () {
          var parts = this.value.trim().split(/\s+/);
          firstName.value = parts[0] || '';
          lastName.value = parts.slice(1).join(' ') || '';
        });
      }

      renderList();
      renderSelected();
      updateHidden();
      renderDetails();
    });
  })();
</script><?php /**PATH C:\Users\engya\Desktop\مواقع الزبائن\namaa\laravel11-auth\resources\views/students/_form.blade.php ENDPATH**/ ?>