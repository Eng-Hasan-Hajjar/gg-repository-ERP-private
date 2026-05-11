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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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

        <div class="form-text text-muted">
          عند اختيار فرع <b>أونلاين</b> سيتم إخفاء حقل مكان السكن تلقائياً.
        </div>

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
            $countries = [
              'تركيا',
              'العراق',
              'ليبيا',
              'سوريا',
              'الأردن',
              'لبنان',
              'فلسطين',
              'الإمارات',
              'قطر',
              'الكويت',
              'سلطنة عمان',
              'ألمانيا',
              'السويد',
              'الولايات المتحدة',
              'المملكة المتحدة'
            ];
          ?>

          <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c); ?>" <?php if(old('country', $lead->country ?? '') == $c): echo 'selected'; endif; ?>>
              <?php echo e($c); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

        <?php $__errorArgs = ['country'];
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

        <div class="form-text text-muted">
          إذا اخترت تركيا ستظهر قائمة المدن التركية.
        </div>

        <?php $__errorArgs = ['province'];
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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

        <div class="form-text text-muted">
          هذا الحقل يظهر فقط للفروع الحضورية.
        </div>

        <?php $__errorArgs = ['residence'];
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
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

        <div class="form-text text-muted">
          عند اختيار "مسجل" سيظهر حقل البريد الإلكتروني.
        </div>

        <?php $__errorArgs = ['stage'];
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
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

      </div>


      <hr class="my-4">


      
      <div class="col-md-6">

        <label class="form-label fw-bold">الدبلومات المطلوبة *</label>

        <select name="diploma_ids[]" multiple size="6" class="form-select" id="diplomas">

          <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($d->id); ?>">
              <?php echo e($d->name); ?>

            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

        <div class="form-text text-muted">
          اختر الدبلومة المطلوبة ثم اختر المجموعة الخاصة بها.
        </div>

      </div>


      
      <div class="col-md-6">

        <label class="form-label fw-bold">المجموعة (الكود)</label>

        <select name="group_id" id="group_id" class="form-select">

          <option value="">اختر المجموعة</option>

        </select>

        <div class="form-text text-muted">
          كل دبلومة قد تحتوي على عدة مجموعات حسب الكود.
        </div>

      </div>


      
      <div class="col-12">

        <label class="form-label fw-bold">ملاحظات *</label>

        <textarea name="notes" class="form-control" rows="4"><?php echo e(old('notes', $lead->notes ?? '')); ?></textarea>

      </div>


    </div>


    <div class="text-end mt-4">
      <button class="btn btn-primary px-5">
        حفظ
      </button>
    </div>

  </div>
</div>






<script>
document.addEventListener('DOMContentLoaded', function () {

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
    const branch        = document.getElementById('branch');
    const residenceField = document.getElementById('residence_field');

    function toggleResidence() {
        const text = branch.options[branch.selectedIndex]?.text ?? '';
        residenceField.style.display = text.includes('أونلاين') ? 'none' : 'block';
    }

    branch.addEventListener('change', toggleResidence);
    toggleResidence();


    // ============================================================
    // ٣. مدن تركيا
    // ============================================================
    const turkeyCities = [
        'اسطنبول', 'مرسين', 'غازي عنتاب', 'بورصا', 'كيليس', 'ازمير',
        'قونيا', 'اضنة', 'اورفا', 'اسكي شهير', 'سكاريا', 'يالوفا',
        'انطاليا', 'الانيا', 'بوردور', 'موغلا', 'ماردين'
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
                provinceSelect.appendChild(opt);
            });
        } else {
            provinceInput.style.display  = 'block';
            provinceSelect.style.display = 'none';
        }
    }

    country.addEventListener('change', handleProvince);
    handleProvince();


    // ============================================================
    // ٤. حذف الدبلومات المكررة
    // ============================================================
    const diplomas = document.getElementById('diplomas');
    const seen = new Set();

    for (let i = diplomas.options.length - 1; i >= 0; i--) {
        const text = diplomas.options[i].text.trim();
        if (seen.has(text)) {
            diplomas.remove(i);
        } else {
            seen.add(text);
        }
    }


    // ============================================================
    // ٥. تحميل المجموعات عند اختيار دبلومة
    // ============================================================
    diplomas.addEventListener('change', function () {
        const id = this.value;
        if (!id) return;

        fetch(`/diplomas/${id}/groups`)
            .then(res => res.json())
            .then(data => {
                const group = document.getElementById('group_id');
                group.innerHTML = '<option value="">اختر المجموعة</option>';
                data.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.id;
                    opt.textContent = g.code;
                    group.appendChild(opt);
                });
            });
    });


    // ============================================================
    // ٦. Strict Mode — الـ Checkbox الرئيسي
    // ============================================================
    const strictCheckbox = document.getElementById('strict_mode');

    // الحقول التي تصبح required عند تفعيل strict mode
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

                // إظهار الحاويات المخفية
                if (container) {
                    const el = document.getElementById(container);
                    if (el) el.style.display = 'block';
                }

            } else {

                input.removeAttribute('required');
                input.classList.remove('border-danger');

                // إعادة الحالة الأصلية للحاويات
                if (container === 'email_field') {
                    toggleEmail(); // يعيد منطق الإيميل الأصلي
                }
                if (container === 'residence_field') {
                    toggleResidence(); // يعيد منطق السكن الأصلي
                }

            }

        });

        // تغيير مظهر تنبيه الـ checkbox
        const alert = document.getElementById('strict_mode_alert');
        if (isStrict) {
            alert.classList.remove('alert-warning');
            alert.classList.add('alert-danger');
        } else {
            alert.classList.remove('alert-danger');
            alert.classList.add('alert-warning');
        }

    }

    strictCheckbox.addEventListener('change', function () {
        applyStrictMode(this.checked);
    });

    // تطبيق الحالة الأولية عند تحميل الصفحة
    applyStrictMode(strictCheckbox.checked);

});


const provSelect = document.getElementById('province_select');
if (provSelect) {
    isStrict
        ? provSelect.setAttribute('required', 'required')
        : provSelect.removeAttribute('required');
}


</script><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\crm\leads\_form_old.blade.php ENDPATH**/ ?>