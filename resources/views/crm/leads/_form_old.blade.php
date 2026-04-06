@csrf
@if(isset($lead)) @method('PUT') @endif

@if ($errors->any())
  <div class="alert alert-danger">
    <strong>يوجد أخطاء في الإدخال:</strong>
    <ul class="mb-0 mt-2">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif





{{-- ===== Strict Mode Checkbox ===== --}}
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

      {{-- الاسم --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">الاسم الكامل *</label>
        <input name="full_name" value="{{ old('full_name', $lead->full_name ?? '') }}"
          class="form-control @error('full_name') is-invalid @enderror">

        @error('full_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- الهاتف --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">الهاتف *</label>

        <input name="phone" value="{{ old('phone', $lead->phone ?? '') }}"
          class="form-control @error('phone') is-invalid @enderror">

        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- تاريخ التواصل --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">تاريخ أول تواصل *</label>

        <input type="date" name="first_contact_date"
          value="{{ old('first_contact_date', $lead->first_contact_date ?? '') }}"
          class="form-control @error('first_contact_date') is-invalid @enderror">

        @error('first_contact_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- العمر --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">العمر *</label>

        <input type="number" name="age" value="{{ old('age', $lead->age ?? '') }}"
          class="form-control @error('age') is-invalid @enderror">

        @error('age')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- العمل --}}
      <div class="col-md-5">
        <label class="form-label fw-bold">العمل *</label>

        <input name="job" value="{{ old('job', $lead->job ?? '') }}"
          class="form-control @error('job') is-invalid @enderror">

        @error('job')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- الفرع --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">الفرع *</label>

        <select name="branch_id" id="branch" class="form-select @error('branch_id') is-invalid @enderror">

          <option value="">اختر الفرع</option>

          @foreach($branches as $b)
            <option value="{{ $b->id }}" data-name="{{ $b->name }}" @selected(old('branch_id', $lead->branch_id ?? '') == $b->id)>
              {{ $b->name }}
            </option>
          @endforeach

        </select>

        <div class="form-text text-muted">
          عند اختيار فرع <b>أونلاين</b> سيتم إخفاء حقل مكان السكن تلقائياً.
        </div>

        @error('branch_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- البلد --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">البلد *</label>

        <select name="country" id="country" class="form-select @error('country') is-invalid @enderror">

          <option value="">اختر البلد</option>

          @php
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
          @endphp

          @foreach($countries as $c)
            <option value="{{ $c }}" @selected(old('country', $lead->country ?? '') == $c)>
              {{ $c }}
            </option>
          @endforeach

        </select>

        @error('country')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- المحافظة --}}
      <div class="col-md-4" id="province_container">

        <label class="form-label fw-bold">المحافظة *</label>

        <input name="province" id="province_input" value="{{ old('province', $lead->province ?? '') }}"
          class="form-control @error('province') is-invalid @enderror">

        <select id="province_select" class="form-select" style="display:none;">
          <option value="">اختر المدينة</option>
        </select>

        <div class="form-text text-muted">
          إذا اخترت تركيا ستظهر قائمة المدن التركية.
        </div>

        @error('province')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

      </div>


      {{-- الدراسة --}}
      <div class="col-md-4">
        <label class="form-label fw-bold">الدراسة *</label>

        <input name="study" value="{{ old('study', $lead->study ?? '') }}"
          class="form-control @error('study') is-invalid @enderror">

        @error('study')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- السكن --}}
      <div class="col-md-6" id="residence_field">

        <label class="form-label fw-bold">مكان السكن *</label>

        <input name="residence" value="{{ old('residence', $lead->residence ?? '') }}"
          class="form-control @error('residence') is-invalid @enderror">

        <div class="form-text text-muted">
          هذا الحقل يظهر فقط للفروع الحضورية.
        </div>

        @error('residence')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

      </div>


      {{-- المصدر --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">المصدر *</label>

        <select name="source" class="form-select @error('source') is-invalid @enderror">

          <option value="">اختر المصدر</option>

          <option value="ad">إعلان</option>
          <option value="referral">إحالة</option>
          <option value="social">سوشيال</option>
          <option value="website">موقع</option>
          <option value="expo">فعالية</option>
          <option value="other">أخرى</option>

        </select>

        @error('source')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>


      {{-- المرحلة --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">المرحلة *</label>

        <select name="stage" class="form-select @error('stage') is-invalid @enderror">

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

        @error('stage')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

      </div>


      {{-- الإيميل --}}
      <div class="col-md-6" id="email_field" style="display:none">

        <label class="form-label fw-bold">البريد الإلكتروني</label>

        <input name="email" value="{{ old('email', $lead->email ?? '') }}"
          class="form-control @error('email') is-invalid @enderror">

        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

      </div>


      <hr class="my-4">


      {{-- الدبلومات --}}
      <div class="col-md-6">

        <label class="form-label fw-bold">الدبلومات المطلوبة *</label>

        <select name="diploma_ids[]" multiple size="6" class="form-select" id="diplomas">

          @foreach($diplomas as $d)
            <option value="{{ $d->id }}">
              {{ $d->name }}
            </option>
          @endforeach

        </select>

        <div class="form-text text-muted">
          اختر الدبلومة المطلوبة ثم اختر المجموعة الخاصة بها.
        </div>

      </div>


      {{-- المجموعة --}}
      <div class="col-md-6">

        <label class="form-label fw-bold">المجموعة (الكود)</label>

        <select name="group_id" id="group_id" class="form-select">

          <option value="">اختر المجموعة</option>

        </select>

        <div class="form-text text-muted">
          كل دبلومة قد تحتوي على عدة مجموعات حسب الكود.
        </div>

      </div>


      {{-- ملاحظات --}}
      <div class="col-12">

        <label class="form-label fw-bold">ملاحظات *</label>

        <textarea name="notes" class="form-control" rows="4">{{ old('notes', $lead->notes ?? '') }}</textarea>

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


</script>