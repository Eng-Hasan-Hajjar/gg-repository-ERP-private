<?php echo csrf_field(); ?>
<?php if(isset($lead)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<?php if($errors->any()): ?>
  <div class="alert alert-danger">
    <strong>يوجد أخطاء في الإدخال:</strong>
    <ul class="mb-0 mt-2">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

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
  .diploma-list-item:last-child { border-bottom: none; }
  .diploma-list-item:hover { background: #eff6ff; }
  .diploma-list-item.disabled {
    opacity: .4;
    pointer-events: none;
    background: #f1f5f9;
  }
  .diploma-list-item .d-name { font-weight: 600; color: #1e293b; }
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

  /* ───── Selected Diplomas ───── */
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
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
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
  .sd-badge.online { background: #dbeafe; color: #2563eb; }
  .sd-badge.onsite { background: #d1fae5; color: #059669; }
  .sd-selects {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
  }
  .sd-selects select {
    min-width: 140px;
    font-size: 13px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    padding: 6px 10px;
  }
  .sd-selects label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 2px;
    display: block;
  }
  .sd-remove {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    transition: background .15s;
    flex-shrink: 0;
  }
  .sd-remove:hover {
    background: #fee2e2;
  }
  .no-diplomas-msg {
    text-align: center;
    padding: 16px;
    color: #94a3b8;
    font-size: 13px;
  }
</style>



<div class="alert alert-warning d-flex align-items-center gap-3 mb-4" id="strict_mode_alert">
    <div class="form-check form-switch mb-0">
        <input class="form-check-input"
               type="checkbox"
               role="switch"
               id="strict_mode"
               name="strict_mode"
               value="1"
               style="width:3rem; height:1.5rem; cursor:pointer;">
        <label class="form-check-label fw-bold fs-6 me-2" for="strict_mode">
            🔒 تعبئة كاملة — تفعيل هذا الخيار يجعل جميع الحقول إلزامية
        </label>
    </div>
</div>



<div class="card shadow-sm border-0">
  <div class="card-body">

    <div class="row g-4">

      
      <div class="col-md-4">
        <label class="form-label fw-bold">الاسم الكامل *</label>
        <input name="full_name" value="<?php echo e(old('full_name', $lead->full_name ?? '')); ?>"
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
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
        <label class="form-label fw-bold">الهاتف *</label>
        <input name="phone" value="<?php echo e(old('phone', $lead->phone ?? '')); ?>"
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
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
        <label class="form-label fw-bold">تاريخ أول تواصل *</label>
        <input type="date" name="first_contact_date"
          value="<?php echo e(old('first_contact_date', $lead->first_contact_date ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['first_contact_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['first_contact_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-3">
        <label class="form-label fw-bold">العمر *</label>
        <input type="number" name="age" value="<?php echo e(old('age', $lead->age ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-5">
        <label class="form-label fw-bold">العمل *</label>
        <input name="job" value="<?php echo e(old('job', $lead->job ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
        <label class="form-label fw-bold">الفرع *</label>
        <select name="branch_id" id="branch" class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <option value="">اختر الفرع</option>
          <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($b->id); ?>" data-name="<?php echo e($b->name); ?>" <?php if(old('branch_id', $lead->branch_id ?? '') == $b->id): echo 'selected'; endif; ?>>
              <?php echo e($b->name); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="form-text text-muted">عند اختيار فرع <b>أونلاين</b> سيتم إخفاء حقل مكان السكن تلقائياً.</div>
        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
        <label class="form-label fw-bold">البلد *</label>
        <select name="country" id="country" class="form-select <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <option value="">اختر البلد</option>
          <?php
            $countries = ['تركيا','العراق','ليبيا','سوريا','الأردن','لبنان','فلسطين','الإمارات','قطر','الكويت','سلطنة عمان','ألمانيا','السويد','الولايات المتحدة','المملكة المتحدة'];
          ?>
          <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c); ?>" <?php if(old('country', $lead->country ?? '') == $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4" id="province_container">
        <label class="form-label fw-bold">المحافظة *</label>
        <input name="province" id="province_input" value="<?php echo e(old('province', $lead->province ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <select id="province_select" class="form-select" style="display:none;">
          <option value="">اختر المدينة</option>
        </select>
        <div class="form-text text-muted">إذا اخترت تركيا ستظهر قائمة المدن التركية.</div>
        <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
        <label class="form-label fw-bold">الدراسة *</label>
        <input name="study" value="<?php echo e(old('study', $lead->study ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-6" id="residence_field">
        <label class="form-label fw-bold">مكان السكن *</label>
        <input name="residence" value="<?php echo e(old('residence', $lead->residence ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['residence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <div class="form-text text-muted">هذا الحقل يظهر فقط للفروع الحضورية.</div>
        <?php $__errorArgs = ['residence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-3">
        <label class="form-label fw-bold">المصدر *</label>
        <select name="source" class="form-select <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <option value="">اختر المصدر</option>
          <option value="ad">إعلان</option>
          <option value="referral">إحالة</option>
          <option value="social">سوشيال</option>
          <option value="website">موقع</option>
          <option value="expo">فعالية</option>
          <option value="other">أخرى</option>
        </select>
        <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-3">
        <label class="form-label fw-bold">المرحلة *</label>
        <select name="stage" class="form-select <?php $__errorArgs = ['stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <option value="">اختر المرحلة</option>
          <option value="new">جديد</option>
          <option value="follow_up">متابعة</option>
          <option value="interested">مهتم</option>
          <option value="registered">مسجل</option>
          <option value="rejected">لم يسجل</option>
          <option value="postponed">مؤجل</option>
        </select>
        <div class="form-text text-muted">عند اختيار "مسجل" سيظهر حقل البريد الإلكتروني.</div>
        <?php $__errorArgs = ['stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-6" id="email_field" style="display:none">
        <label class="form-label fw-bold">البريد الإلكتروني</label>
        <input name="email" value="<?php echo e(old('email', $lead->email ?? '')); ?>"
          class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <hr class="my-4">

      
      
      
      <div class="col-12">
        <label class="form-label fw-bold fs-6">
          <i class="bi bi-mortarboard text-primary"></i> الدبلومات المطلوبة *
        </label>

        <div class="diploma-picker">

          
          <div class="diploma-search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="diplomaSearch" class="form-control" placeholder="ابحث عن دبلومة بالاسم...">
          </div>

          
          <div class="diploma-list" id="diplomaList">
            <div class="diploma-list-empty" id="diplomaEmpty" style="display:none">
              <i class="bi bi-search"></i> لا توجد نتائج
            </div>
          </div>

          
          <div class="selected-diplomas" id="selectedDiplomas">
            <div class="no-diplomas-msg" id="noDiplomasMsg">
              <i class="bi bi-info-circle"></i> لم يتم اختيار أي دبلومة بعد — اختر من القائمة أعلاه
            </div>
          </div>

        </div>

        
        <div id="diplomaHiddenInputs"></div>
      </div>

      
      <div class="col-12">
        <label class="form-label fw-bold">ملاحظات </label>
        <textarea name="notes" class="form-control" rows="4"><?php echo e(old('notes', $lead->notes ?? '')); ?></textarea>
      </div>

    </div>

    <div class="text-end mt-4">
      <button class="btn btn-primary px-5">حفظ</button>
    </div>

  </div>
</div>


<?php
    $diplomasJson = $diplomas->map(function($d) {
        return [
            'id'          => $d->id,
            'name'        => $d->name,
            'code'        => $d->code,
            'type'        => $d->type,
            'branch_id'   => $d->branch_id,
            'branch_name' => $d->branch->name ?? '—',
        ];
    })->values();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const allDiplomas = <?php echo json_encode($diplomasJson, 15, 512) ?>;


    // تجميع الدبلومات بنفس الاسم (لعرض الأكواد والفروع المتعددة)
    const diplomasByName = {};
    allDiplomas.forEach(d => {
        if (!diplomasByName[d.name]) {
            diplomasByName[d.name] = {
                name: d.name,
                type: d.type,
                variants: []
            };
        }
        diplomasByName[d.name].variants.push({
            id: d.id,
            code: d.code,
            branch_id: d.branch_id,
            branch_name: d.branch_name,
        });
    });

    const diplomaNames = Object.values(diplomasByName);
    const selectedDiplomas = new Map(); // name -> { variantId, code, branch_id }

    // الدبلومات المختارة مسبقاً (عند التعديل)
    <?php if(isset($lead) && $lead->diplomas->count()): ?>
    <?php $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        const existingName_<?php echo e($ld->id); ?> = <?php echo json_encode($ld->name, 15, 512) ?>;
        const existingVariant_<?php echo e($ld->id); ?> = {
            variantId: <?php echo e($ld->id); ?>,
            code: <?php echo json_encode($ld->code, 15, 512) ?>,
            branch_id: <?php echo e($ld->branch_id ?? 'null'); ?>,
        };
        selectedDiplomas.set(existingName_<?php echo e($ld->id); ?>, existingVariant_<?php echo e($ld->id); ?>);
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    const diplomaList      = document.getElementById('diplomaList');
    const diplomaSearch    = document.getElementById('diplomaSearch');
    const diplomaEmpty     = document.getElementById('diplomaEmpty');
    const selectedContainer = document.getElementById('selectedDiplomas');
    const noDiplomasMsg    = document.getElementById('noDiplomasMsg');
    const hiddenInputs     = document.getElementById('diplomaHiddenInputs');
    const branchSelect     = document.getElementById('branch');

    // ============================================================
    // عرض قائمة الدبلومات
    // ============================================================
    function renderDiplomaList(filter = '') {
        // مسح القائمة
        diplomaList.querySelectorAll('.diploma-list-item').forEach(el => el.remove());

        const selectedBranchId = branchSelect.value;
        let visibleCount = 0;

        diplomaNames.forEach(group => {
            const nameMatch = filter === '' || group.name.includes(filter);

            // فلترة حسب فرع العميل المختار
            const hasMatchingBranch = !selectedBranchId ||
                group.variants.some(v => v.branch_id == selectedBranchId);

            if (!nameMatch || !hasMatchingBranch) return;

            const isSelected = selectedDiplomas.has(group.name);
            const branches = group.variants.map(v => v.branch_name).filter((v,i,a) => a.indexOf(v) === i).join('، ');
            const codes = group.variants.map(v => v.code).filter((v,i,a) => a.indexOf(v) === i).join('، ');

            const item = document.createElement('div');
            item.className = 'diploma-list-item' + (isSelected ? ' disabled' : '');
            item.innerHTML = `
                <div>
                    <span class="d-name">${group.name}</span>
                    <div class="d-meta">
                        <span><i class="bi bi-tag"></i> ${codes}</span>
                        <span><i class="bi bi-building"></i> ${branches}</span>
                    </div>
                </div>
                <div>
                    <span class="badge ${group.type === 'online' ? 'bg-primary' : 'bg-success'}" style="font-size:11px">
                        ${group.type === 'online' ? 'أونلاين' : 'حضوري'}
                    </span>
                    ${isSelected ? '<i class="bi bi-check-circle-fill text-success ms-2"></i>' : '<i class="bi bi-plus-circle text-primary ms-2"></i>'}
                </div>
            `;

            if (!isSelected) {
                item.addEventListener('click', () => addDiploma(group));
            }

            diplomaList.appendChild(item);
            visibleCount++;
        });

        diplomaEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // ============================================================
    // إضافة دبلومة
    // ============================================================
    function addDiploma(group) {
        const selectedBranchId = branchSelect.value;

        // فلتر الفروع المتاحة حسب فرع العميل
        let availableVariants = group.variants;
        if (selectedBranchId) {
            availableVariants = group.variants.filter(v => v.branch_id == selectedBranchId);
        }

        if (availableVariants.length === 0) return;

        // الاختيار الافتراضي = أول variant متاح
        const defaultVariant = availableVariants[0];
        selectedDiplomas.set(group.name, {
            variantId: defaultVariant.id,
            code: defaultVariant.code,
            branch_id: defaultVariant.branch_id,
        });

        renderSelectedDiplomas();
        renderDiplomaList(diplomaSearch.value);
        updateHiddenInputs();
    }

    // ============================================================
    // حذف دبلومة
    // ============================================================
    function removeDiploma(name) {
        selectedDiplomas.delete(name);
        renderSelectedDiplomas();
        renderDiplomaList(diplomaSearch.value);
        updateHiddenInputs();
    }

    // ============================================================
    // عرض الدبلومات المختارة
    // ============================================================
    function renderSelectedDiplomas() {
        // مسح الكاردات
        selectedContainer.querySelectorAll('.selected-diploma-card').forEach(el => el.remove());

        if (selectedDiplomas.size === 0) {
            noDiplomasMsg.style.display = 'block';
            return;
        }
        noDiplomasMsg.style.display = 'none';

        const selectedBranchId = branchSelect.value;

        selectedDiplomas.forEach((selection, name) => {
            const group = diplomasByName[name];
            if (!group) return;

            // الفروع المتاحة
            let availableVariants = group.variants;
            if (selectedBranchId) {
                availableVariants = group.variants.filter(v => v.branch_id == selectedBranchId);
            }

            // الأكواد الفريدة
            const uniqueCodes = [...new Map(availableVariants.map(v => [v.code, v])).values()];
            // الفروع الفريدة
            const uniqueBranches = [...new Map(availableVariants.map(v => [v.branch_id, v])).values()];

            const card = document.createElement('div');
            card.className = 'selected-diploma-card';

            // بناء select الأكواد
            let codeOptions = uniqueCodes.map(v =>
                `<option value="${v.id}" ${v.id == selection.variantId ? 'selected' : ''}>${v.code}</option>`
            ).join('');

            // بناء select الفروع
            let branchOptions = uniqueBranches.map(v =>
                `<option value="${v.branch_id}" ${v.branch_id == selection.branch_id ? 'selected' : ''}>${v.branch_name}</option>`
            ).join('');

            card.innerHTML = `
                <div class="sd-info">
                    <div class="sd-name">${name}</div>
                    <span class="sd-badge ${group.type === 'online' ? 'online' : 'onsite'}">
                        ${group.type === 'online' ? '<i class="bi bi-wifi"></i> أونلاين' : '<i class="bi bi-geo-alt"></i> حضوري'}
                    </span>
                </div>
                <div class="sd-selects">
                    <div>
                        <label><i class="bi bi-tag"></i> الرمز</label>
                        <select class="diploma-code-select" data-name="${name}">
                            ${codeOptions}
                        </select>
                    </div>
                    <div>
                        <label><i class="bi bi-building"></i> الفرع</label>
                        <select class="diploma-branch-select" data-name="${name}">
                            ${branchOptions}
                        </select>
                    </div>
                </div>
                <div class="sd-remove" data-name="${name}" title="إزالة">
                    <i class="bi bi-x-lg"></i>
                </div>
            `;

            selectedContainer.appendChild(card);

            // أحداث التغيير
            card.querySelector('.diploma-code-select').addEventListener('change', function() {
                const variant = availableVariants.find(v => v.id == this.value);
                if (variant) {
                    selection.variantId = variant.id;
                    selection.code = variant.code;
                    selection.branch_id = variant.branch_id;

                    // تحديث الفرع تلقائياً
                    const branchSel = card.querySelector('.diploma-branch-select');
                    if (branchSel) branchSel.value = variant.branch_id;

                    updateHiddenInputs();
                }
            });

            card.querySelector('.diploma-branch-select').addEventListener('change', function() {
                const branchId = this.value;
                // إيجاد variant يطابق هذا الفرع
                const variant = availableVariants.find(v => v.branch_id == branchId);
                if (variant) {
                    selection.variantId = variant.id;
                    selection.code = variant.code;
                    selection.branch_id = variant.branch_id;

                    // تحديث الكود تلقائياً
                    const codeSel = card.querySelector('.diploma-code-select');
                    if (codeSel) codeSel.value = variant.id;

                    updateHiddenInputs();
                }
            });

            card.querySelector('.sd-remove').addEventListener('click', function() {
                removeDiploma(this.dataset.name);
            });
        });
    }

    // ============================================================
    // تحديث الـ hidden inputs
    // ============================================================
    function updateHiddenInputs() {
        hiddenInputs.innerHTML = '';
        selectedDiplomas.forEach((selection, name) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'diploma_ids[]';
            input.value = selection.variantId;
            hiddenInputs.appendChild(input);
        });
    }

    // ============================================================
    // أحداث البحث
    // ============================================================
    diplomaSearch.addEventListener('input', function() {
        renderDiplomaList(this.value.trim());
    });

    // عند تغيير فرع العميل — إعادة فلترة
    branchSelect.addEventListener('change', function() {
        renderDiplomaList(diplomaSearch.value.trim());
        renderSelectedDiplomas();
        updateHiddenInputs();
    });

    // التهيئة الأولى
    renderDiplomaList();
    renderSelectedDiplomas();
    updateHiddenInputs();


    // ============================================================
    // ١. إظهار الإيميل عند اختيار "مسجل"
    // ============================================================
    const stage      = document.querySelector('[name="stage"]');
    const emailField = document.getElementById('email_field');

    function toggleEmail() {
        if (stage.value === 'registered') {
            emailField.style.display = 'block';
        } else if (!document.getElementById('strict_mode').checked) {
            emailField.style.display = 'none';
        }
    }
    stage.addEventListener('change', toggleEmail);
    toggleEmail();


    // ============================================================
    // ٢. إخفاء السكن عند اختيار فرع أونلاين
    // ============================================================
    const residenceField = document.getElementById('residence_field');

    function toggleResidence() {
        const text = branchSelect.options[branchSelect.selectedIndex]?.text ?? '';
        residenceField.style.display = text.includes('أونلاين') ? 'none' : 'block';
    }
    branchSelect.addEventListener('change', toggleResidence);
    toggleResidence();


    // ============================================================
    // ٣. مدن تركيا
    // ============================================================
    const turkeyCities = [
        'اسطنبول','مرسين','غازي عنتاب','بورصا','كيليس','ازمير',
        'قونيا','اضنة','اورفا','اسكي شهير','سكاريا','يالوفا',
        'انطاليا','الانيا','بوردور','موغلا','ماردين'
    ];
    const country        = document.getElementById('country');
    const provinceInput  = document.getElementById('province_input');
    const provinceSelect = document.getElementById('province_select');

    function handleProvince() {
        if (country.value === 'تركيا') {
            provinceInput.style.display  = 'none';
            provinceSelect.style.display = 'block';
            provinceSelect.innerHTML = '<option value="">اختر المدينة</option>';
            turkeyCities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                if ('<?php echo e(old('province', $lead->province ?? '')); ?>' === city) opt.selected = true;
                provinceSelect.appendChild(opt);
            });
            provinceSelect.setAttribute('name', 'province');
            provinceInput.removeAttribute('name');
        } else {
            provinceInput.style.display  = 'block';
            provinceSelect.style.display = 'none';
            provinceInput.setAttribute('name', 'province');
            provinceSelect.removeAttribute('name');
        }
    }
    country.addEventListener('change', handleProvince);
    handleProvince();


    // ============================================================
    // ٤. Strict Mode
    // ============================================================
    const strictCheckbox = document.getElementById('strict_mode');
    const strictFields = [
        { selector: '[name="whatsapp"]',    container: null },
        { selector: '[name="email"]',       container: 'email_field' },
        { selector: '[name="residence"]',   container: 'residence_field' },
        { selector: '[name="organization"]',container: null },
        { selector: '[name="province"]',    container: 'province_container' },
        { selector: '[name="need"]',        container: null },
    ];

    function applyStrictMode(isStrict) {
        strictFields.forEach(({ selector, container }) => {
            const input = document.querySelector(selector);
            if (!input) return;
            if (isStrict) {
                input.setAttribute('required', 'required');
                input.classList.add('border-danger');
                if (container) {
                    const el = document.getElementById(container);
                    if (el) el.style.display = 'block';
                }
            } else {
                input.removeAttribute('required');
                input.classList.remove('border-danger');
                if (container === 'email_field') toggleEmail();
                if (container === 'residence_field') toggleResidence();
            }
        });

        const alert = document.getElementById('strict_mode_alert');
        alert.classList.toggle('alert-danger', isStrict);
        alert.classList.toggle('alert-warning', !isStrict);
    }

    strictCheckbox.addEventListener('change', function () {
        applyStrictMode(this.checked);
    });
    applyStrictMode(strictCheckbox.checked);

});
</script><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/_form.blade.php ENDPATH**/ ?>