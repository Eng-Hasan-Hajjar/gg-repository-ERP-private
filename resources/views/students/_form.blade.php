@csrf

@if(isset($student) && $student->exists)
  @method('PUT')
@endif

@if ($errors->any())
  <div class="alert alert-danger border-0 rounded-3 shadow-sm">
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-exclamation-triangle-fill text-danger"></i>
      <strong>يرجى تصحيح الأخطاء التالية:</strong>
    </div>
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

@php
  $selectedDiplomas = old('diploma_ids', $studentDiplomas->pluck('id')->toArray());
@endphp

<style>
  /* ══════════════════════════════════════════
     متغيرات النظام
  ══════════════════════════════════════════ */
  :root {
    --nma-primary:    #2563eb;
    --nma-primary-lt: #eff6ff;
    --nma-success:    #059669;
    --nma-success-lt: #d1fae5;
    --nma-warning:    #d97706;
    --nma-warning-lt: #fef3c7;
    --nma-danger:     #dc2626;
    --nma-danger-lt:  #fee2e2;
    --nma-slate-50:   #f8fafc;
    --nma-slate-100:  #f1f5f9;
    --nma-slate-200:  #e2e8f0;
    --nma-slate-400:  #94a3b8;
    --nma-slate-600:  #475569;
    --nma-slate-800:  #1e293b;
    --nma-radius-sm:  8px;
    --nma-radius:     12px;
    --nma-radius-lg:  16px;
    --nma-shadow:     0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
    --nma-shadow-md:  0 4px 12px rgba(0,0,0,.08);
  }

  /* ══════════════════════════════════════════
     بطاقة القسم العامة
  ══════════════════════════════════════════ */
  .nma-section-card {
    background: #fff;
    border: 1px solid var(--nma-slate-200);
    border-radius: var(--nma-radius-lg);
    overflow: hidden;
    box-shadow: var(--nma-shadow);
    margin-bottom: 1.25rem;
  }

  .nma-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    background: var(--nma-slate-50);
    border-bottom: 1px solid var(--nma-slate-200);
  }

  .nma-section-icon {
    width: 34px;
    height: 34px;
    border-radius: var(--nma-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
  }

  .nma-section-icon.blue   { background: var(--nma-primary-lt); color: var(--nma-primary); }
  .nma-section-icon.green  { background: var(--nma-success-lt); color: var(--nma-success); }
  .nma-section-icon.amber  { background: var(--nma-warning-lt); color: var(--nma-warning); }

  .nma-section-title {
    font-weight: 700;
    font-size: 14px;
    color: var(--nma-slate-800);
    margin: 0;
  }

  .nma-section-body {
    padding: 20px;
  }

  /* ══════════════════════════════════════════
     Label محسّن
  ══════════════════════════════════════════ */
  .nma-label {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--nma-slate-600);
    margin-bottom: 5px;
    display: block;
    letter-spacing: .01em;
  }

  /* ══════════════════════════════════════════
     Diploma Picker
  ══════════════════════════════════════════ */
  .diploma-picker {
    border: 2px solid var(--nma-slate-200);
    border-radius: var(--nma-radius-lg);
    background: var(--nma-slate-50);
    overflow: hidden;
  }

  .diploma-picker-search {
    padding: 14px 16px 0;
  }

  .diploma-search-wrap {
    position: relative;
  }

  .diploma-search-wrap input {
    padding-right: 38px;
    border-radius: var(--nma-radius-sm);
    border: 1px solid var(--nma-slate-200);
    background: #fff;
    font-size: 13.5px;
  }

  .diploma-search-wrap input:focus {
    border-color: var(--nma-primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
  }

  .diploma-search-wrap .si {
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--nma-slate-400);
    pointer-events: none;
    font-size: 13px;
  }

  .diploma-list {
    max-height: 210px;
    overflow-y: auto;
    margin: 12px 16px;
    border: 1px solid var(--nma-slate-200);
    border-radius: var(--nma-radius-sm);
    background: #fff;
  }

  .diploma-list-item {
    padding: 9px 13px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--nma-slate-100);
    transition: background .12s;
    font-size: 13.5px;
  }

  .diploma-list-item:last-child { border-bottom: none; }
  .diploma-list-item:hover      { background: var(--nma-primary-lt); }

  .diploma-list-item.disabled {
    opacity: .4;
    pointer-events: none;
    background: var(--nma-slate-100);
  }

  .diploma-list-item .d-name {
    font-weight: 600;
    color: var(--nma-slate-800);
  }

  .diploma-list-item .d-meta {
    font-size: 11.5px;
    color: var(--nma-slate-400);
    display: flex;
    gap: 8px;
  }

  .diploma-list-empty {
    padding: 18px;
    text-align: center;
    color: var(--nma-slate-400);
    font-size: 13px;
  }

  /* ── الدبلومات المختارة ── */
  .selected-diplomas-wrap {
    border-top: 1px solid var(--nma-slate-200);
    background: #fff;
    padding: 14px 16px;
    min-height: 60px;
  }

  .selected-diploma-card {
    background: var(--nma-slate-50);
    border: 1px solid var(--nma-slate-200);
    border-radius: var(--nma-radius-sm);
    padding: 10px 13px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    transition: box-shadow .12s;
  }

  .selected-diploma-card:hover {
    box-shadow: var(--nma-shadow);
  }

  .sd-info { flex: 1; min-width: 140px; }

  .sd-name {
    font-weight: 700;
    font-size: 13.5px;
    color: var(--nma-slate-800);
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
  }

  .sd-badge {
    font-size: 10.5px;
    padding: 2px 7px;
    border-radius: 5px;
    font-weight: 600;
  }

  .sd-badge.online { background: var(--nma-primary-lt); color: var(--nma-primary); }
  .sd-badge.onsite { background: var(--nma-success-lt); color: var(--nma-success); }

  .sd-meta {
    font-size: 11.5px;
    color: var(--nma-slate-400);
    margin-top: 3px;
  }

  .sd-remove {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--nma-danger-lt);
    color: var(--nma-danger);
    cursor: pointer;
    transition: background .12s;
    font-size: 11px;
    flex-shrink: 0;
  }

  .sd-remove:hover { background: #fca5a5; }

  .no-diplomas-msg {
    text-align: center;
    color: var(--nma-slate-400);
    font-size: 13px;
    padding: 8px 0;
  }

  /* ══════════════════════════════════════════
     بطاقة الدبلومة التفصيلية
  ══════════════════════════════════════════ */
  .diploma-detail-card {
    background: #fff;
    border: 1px solid var(--nma-slate-200);
    border-radius: var(--nma-radius);
    overflow: hidden;
    margin-bottom: 12px;
    box-shadow: var(--nma-shadow);
  }

  .diploma-detail-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: linear-gradient(135deg, var(--nma-primary-lt) 0%, #fff 100%);
    border-bottom: 1px solid var(--nma-slate-200);
  }

  .diploma-detail-header h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--nma-primary);
  }

  .diploma-detail-body {
    padding: 16px;
  }

  /* ══════════════════════════════════════════
     زر التوليد + النسخ
  ══════════════════════════════════════════ */
  .msg-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
  }

  /* ══════════════════════════════════════════
     حقل اللغة مع Badge
  ══════════════════════════════════════════ */
  .lang-check-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 11px;
    background: var(--nma-slate-50);
    border: 1px solid var(--nma-slate-200);
    border-radius: var(--nma-radius-sm);
    cursor: pointer;
    transition: border-color .12s;
  }

  .lang-check-row:has(input:checked) {
    border-color: var(--nma-primary);
    background: var(--nma-primary-lt);
  }

  /* ══════════════════════════════════════════
     Responsive input
  ══════════════════════════════════════════ */
  .form-control, .form-select {
    font-size: 13.5px;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--nma-primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
  }

  /* ══════════════════════════════════════════
     فاصل القسم
  ══════════════════════════════════════════ */
  .nma-divider {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--nma-slate-400);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    margin: 6px 0 12px;
  }

  .nma-divider::before,
  .nma-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--nma-slate-200);
  }
</style>

{{-- ════════════════════════════════════════════════════════════
     ① البيانات الأساسية
════════════════════════════════════════════════════════════ --}}
<div class="nma-section-card">
  <div class="nma-section-header">
    <div class="nma-section-icon blue"><i class="bi bi-person-fill"></i></div>
    <h6 class="nma-section-title">البيانات الأساسية</h6>
  </div>
  <div class="nma-section-body">
    <div class="row g-3">

      {{-- الاسم الكامل --}}
      <div class="col-md-3">
        <label class="nma-label">الاسم الكامل</label>
        <input name="full_name" value="{{ old('full_name', $student->full_name ?? '') }}"
          class="form-control @error('full_name') is-invalid @enderror"
          placeholder="أدخل الاسم الكامل">
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- hidden fields --}}
      <div hidden>
        <input name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" class="form-control" required>
        <input name="last_name"  value="{{ old('last_name',  $student->last_name  ?? '') }}" class="form-control">
      </div>

      {{-- الهاتف --}}
      <div class="col-md-3">
        <label class="nma-label">الهاتف</label>
        <input name="phone" value="{{ old('phone', $student->phone ?? '') }}"
          class="form-control @error('phone') is-invalid @enderror"
          placeholder="+90 5xx xxx xxxx">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- واتساب --}}
      <div class="col-md-3">
        <label class="nma-label">واتساب</label>
        <input name="whatsapp" value="{{ old('whatsapp', $student->whatsapp ?? '') }}"
          class="form-control @error('whatsapp') is-invalid @enderror"
          placeholder="+90 5xx xxx xxxx">
        @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- الفرع --}}
      <div class="col-md-3">
        <label class="nma-label">الفرع</label>
        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
          <option value="">-- اختر الفرع --</option>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}" @selected(old('branch_id', $student->branch_id ?? '') == $branch->id)>
              {{ $branch->name }}
            </option>
          @endforeach
        </select>
        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- hidden fields --}}
      <div hidden>
        <select name="mode" class="form-select">
          <option value="onsite" selected>حضوري</option>
          <option value="online">أونلاين</option>
        </select>
        <select name="certificate_agreement" class="form-select">
          <option value="">-- لا يوجد اتفاق --</option>
          <option value="جراح باشا"   @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جراح باشا')>جراح باشا</option>
          <option value="بورد الماني" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'بورد الماني')>بورد الماني</option>
          <option value="جامعة تركية" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جامعة تركية')>جامعة تركية</option>
          <option value="ميديبول"     @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'ميديبول')>ميديبول</option>
        </select>
      </div>

    </div>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     ② اختيار الدبلومات
════════════════════════════════════════════════════════════ --}}
<div class="nma-section-card">
  <div class="nma-section-header">
    <div class="nma-section-icon blue"><i class="bi bi-mortarboard-fill"></i></div>
    <h6 class="nma-section-title">الدبلومات <span class="text-danger">*</span></h6>
    <span class="ms-auto text-muted" style="font-size:12px">أول دبلومة تُعتبر رئيسية تلقائياً</span>
  </div>
  <div class="nma-section-body p-0">

    <div class="diploma-picker">

      {{-- بحث --}}
      <div class="diploma-picker-search">
        <div class="diploma-search-wrap">
          <i class="bi bi-search si"></i>
          <input type="text" id="diplomaSearch" class="form-control"
            placeholder="ابحث بالاسم أو الكود...">
        </div>
      </div>

      {{-- قائمة --}}
      <div class="diploma-list" id="diplomaList">
        <div class="diploma-list-empty" id="diplomaEmpty" style="display:none">
          <i class="bi bi-search"></i> لا توجد نتائج
        </div>
      </div>

      {{-- المختارة --}}
      <div class="selected-diplomas-wrap" id="selectedDiplomas">
        <div class="no-diplomas-msg" id="noDiplomasMsg">
          <i class="bi bi-info-circle"></i> لم يتم اختيار أي دبلومة — اختر من القائمة أعلاه
        </div>
      </div>

    </div>

    <div id="diplomaHiddenInputs"></div>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     ③ بيانات CRM
════════════════════════════════════════════════════════════ --}}
@if(auth()->user()?->hasPermission('edit_crm_in_student'))
<div class="nma-section-card">
  <div class="nma-section-header">
    <div class="nma-section-icon amber"><i class="bi bi-graph-up-arrow"></i></div>
    <h6 class="nma-section-title">بيانات CRM (الاستشارات)</h6>
  </div>
  <div class="nma-section-body">
    <div class="row g-3">

      {{-- الاسم باللاتيني --}}
      <div class="col-md-3">
        <label class="nma-label">الاسم باللاتيني</label>
        <input id="latin_first_name" class="form-control" placeholder="Ahmad">
      </div>

      <div class="col-md-3">
        <label class="nma-label">الكنية باللاتيني</label>
        <input id="latin_last_name" class="form-control" placeholder="Khalil">
      </div>

      <div class="col-md-6">
        <label class="nma-label">الاسم الكامل باللاتيني</label>
        <input name="profile[arabic_full_name]" id="latin_full_name"
          value="{{ old('profile.arabic_full_name', $profile['arabic_full_name'] ?? '') }}"
          class="form-control @error('profile.arabic_full_name') is-invalid @enderror"
          readonly style="background:var(--nma-slate-50)">
        @error('profile.arabic_full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- الجهة --}}
      <div class="col-md-6">
        <label class="nma-label">الجهة / المؤسسة</label>
        <input name="crm[organization]"
          class="form-control @error('crm.organization') is-invalid @enderror"
          value="{{ old('crm.organization', $crm['organization'] ?? '') }}"
          placeholder="اسم الجهة أو المؤسسة">
        @error('crm.organization')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- تاريخ التولد --}}
      <div class="col-md-3">
        <label class="nma-label">تاريخ التولد</label>
        <input type="date" name="profile[birth_date]"
          value="{{ old('profile.birth_date', $profile['birth_date'] ?? '') }}"
          class="form-control @error('profile.birth_date') is-invalid @enderror">
        @error('profile.birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- الرقم الوطني --}}
      <div class="col-md-3">
        <label class="nma-label">الرقم الوطني</label>
        <input name="profile[national_id]"
          value="{{ old('profile.national_id', $profile['national_id'] ?? '') }}"
          class="form-control @error('profile.national_id') is-invalid @enderror"
          placeholder="أدخل الرقم الوطني">
        @error('profile.national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- المصدر --}}
      <div class="col-md-3">
        <label class="nma-label">المصدر</label>
        <select name="crm[source]" class="form-select @error('crm.source') is-invalid @enderror">
          <option value="">-- اختر المصدر --</option>
          @php $sourceOptions = ['ad'=>'إعلان مدفوع','referral'=>'إحالة / توصية','social'=>'وسائل التواصل','website'=>'الموقع الإلكتروني','expo'=>'معرض / فعالية','other'=>'أخرى']; @endphp
          @foreach($sourceOptions as $src => $srcLabel)
            <option value="{{ $src }}" @selected(old('crm.source', $crm['source'] ?? '') === $src)>{{ $srcLabel }}</option>
          @endforeach
        </select>
        @error('crm.source')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>

      {{-- المرحلة --}}
      <div class="col-md-3">
        <label class="nma-label">المرحلة</label>
        <select name="crm[stage]" class="form-select @error('crm.stage') is-invalid @enderror">
          <option value="">-- اختر المرحلة --</option>
          @php $stageOptions = ['new'=>'جديد','follow_up'=>'متابعة','interested'=>'مهتم','registered'=>'مسجل','rejected'=>'مرفوض','postponed'=>'مؤجل']; @endphp
          @foreach($stageOptions as $st => $stLabel)
            <option value="{{ $st }}" @selected(old('crm.stage', $crm['stage'] ?? '') === $st)>{{ $stLabel }}</option>
          @endforeach
        </select>
        @error('crm.stage')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>

      {{-- الدراسة --}}
      <div class="col-md-6">
        <label class="nma-label">الدراسة</label>
        <input name="crm[study]"
          class="form-control @error('crm.study') is-invalid @enderror"
          value="{{ old('crm.study', $crm['study'] ?? '') }}"
          placeholder="التخصص أو المجال الدراسي">
        @error('crm.study')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
   {{-- ملف الهوية --}}
      <div class="col-md-3">
        <label class="nma-label">ملف الهوية</label>
        <input type="file" name="profile[identity_file]"
          class="form-control @error('profile.identity_file') is-invalid @enderror"
          accept=".pdf,.png,.jpg">
        @error('profile.identity_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(!empty($profile['identity_file_path']))
          <a target="_blank" href="{{ asset('storage/' . $profile['identity_file_path']) }}"
            class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
            <i class="bi bi-eye"></i> عرض الهوية الحالية
          </a>
        @endif
      </div>
      {{-- البلد --}}
      <div class="col-md-3">
        <label class="nma-label">البلد</label>
        <input name="crm[country]"
          class="form-control @error('crm.country') is-invalid @enderror"
          value="{{ old('crm.country', $crm['country'] ?? '') }}"
          placeholder="البلد">
        @error('crm.country')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- المحافظة --}}
      <div class="col-md-3">
        <label class="nma-label">المحافظة</label>
        <input name="crm[province]"
          class="form-control @error('crm.province') is-invalid @enderror"
          value="{{ old('crm.province', $crm['province'] ?? '') }}"
          placeholder="المحافظة / المنطقة">
        @error('crm.province')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- المستوى التعليمي --}}
      <div class="col-md-3">
        <label class="nma-label">المستوى التعليمي</label>
        <select name="profile[education_level]"
          class="form-select @error('profile.education_level') is-invalid @enderror">
          <option value="">-- اختر المستوى --</option>
          @php $educationLevels = ['ابتدائي','اعدادي','ثانوي','بكالوريوس','ماجستير','دكتوراه','لا يوجد']; @endphp
          @foreach($educationLevels as $val)
            <option value="{{ $val }}" @selected(old('profile.education_level', $profile['education_level'] ?? '') == $val)>{{ $val }}</option>
          @endforeach
        </select>
        @error('profile.education_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- الاحتياج --}}
      <div class="col-12">
        <label class="nma-label">الاحتياج</label>
        <textarea name="crm[need]"
          class="form-control @error('crm.need') is-invalid @enderror"
          rows="2" placeholder="صف احتياج الطالب أو الهدف من التسجيل">{{ old('crm.need', $crm['need'] ?? '') }}</textarea>
        @error('crm.need')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

   

      {{-- ملاحظات CRM --}}
      <div class="col-12">
        <label class="nma-label">ملاحظات CRM</label>
        <textarea name="crm[notes]"
          class="form-control @error('crm.notes') is-invalid @enderror"
          rows="2" placeholder="ملاحظات إضافية...">{{ old('crm.notes', $crm['notes'] ?? '') }}</textarea>
        @error('crm.notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

    </div>
  </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     ④ الملف التفصيلي
════════════════════════════════════════════════════════════ --}}
<div class="nma-section-card">
  <div class="nma-section-header">
    <div class="nma-section-icon green"><i class="bi bi-person-lines-fill"></i></div>
    <h6 class="nma-section-title">الملف التفصيلي للطالب</h6>
  </div>
  <div class="nma-section-body">
    <div class="row g-3">

      {{-- الجنسية --}}
      <div class="col-md-3">
        <label class="nma-label">الجنسية</label>
        <select name="profile[nationality]"
          class="form-select @error('profile.nationality') is-invalid @enderror"
          id="nationalitySelect">
          <option value="">-- اختر الجنسية --</option>
          @php
            $nationalities = ['سورية','سعودية','مصرية','عراقية','أردنية','لبنانية','كويتية','إماراتية','قطرية','بحرينية','عُمانية','يمنية','ليبية','تونسية','جزائرية','مغربية','موريتانية','سودانية','صومالية','فلسطينية','قمرية','جيبوتية','تركية','إيرانية','أفغانية','باكستانية','أذربيجانية','كازاخستانية','أوزبكستانية','ألمانية','فرنسية','بريطانية','إيطالية','إسبانية','هولندية','بلجيكية','سويدية','نرويجية','دنماركية','سويسرية','نمساوية','يونانية','بولندية','رومانية','روسية','أمريكية','كندية','أسترالية','هندية','صينية','يابانية','كورية','برازيلية','إندونيسية','ماليزية','نيجيرية','إثيوبية','جنوب أفريقية','أرجنتينية'];
          @endphp
          @foreach($nationalities as $nat)
            <option value="{{ $nat }}" @selected(old('profile.nationality', $profile['nationality'] ?? '') == $nat)>{{ $nat }}</option>
          @endforeach
        </select>
        @error('profile.nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- ستاج بالولاية --}}
      <div class="col-md-3">
        <label class="nma-label">ستاج بالولاية</label>
        <label class="lang-check-row">
          <input type="checkbox" class="form-check-input m-0" id="stageCheck"
            {{ !empty($profile['stage_in_state'] ?? '') ? 'checked' : '' }}>
          <span style="font-size:13px;color:var(--nma-slate-600)">يوجد ستاج</span>
        </label>
      </div>

      <div class="col-md-3" id="stageField"
        style="{{ !empty($profile['stage_in_state'] ?? '') ? '' : 'display:none' }}">
        <label class="nma-label">ولاية الستاج</label>
        <select name="profile[stage_in_state]"
          class="form-select @error('profile.stage_in_state') is-invalid @enderror">
          <option value="">-- اختر الولاية --</option>
          @foreach(['بوصة','عنتاب','كلس','اسطنبول','مرسين'] as $city)
            <option value="{{ $city }}" @selected(old('profile.stage_in_state', $profile['stage_in_state'] ?? '') == $city)>{{ $city }}</option>
          @endforeach
          <option value="أخرى" @selected(!empty($profile['stage_in_state'] ?? '') && !in_array($profile['stage_in_state'] ?? '', ['بوصة','عنتاب','كلس','اسطنبول','مرسين']))>أخرى</option>
        </select>
        @error('profile.stage_in_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- مستوى اللغة العام --}}
      <div class="col-md-3" hidden>
        <label class="nma-label">مستوى اللغة العام</label>
        <label class="lang-check-row mb-2">
          <input type="checkbox" class="form-check-input m-0" id="hasLangCheck"
            @if(!empty($profile['level'] ?? '')) checked @endif>
          <span style="font-size:13px;color:var(--nma-slate-600)">يوجد مستوى لغة</span>
        </label>
        <div id="langLevelField" style="{{ empty($profile['level'] ?? '') ? 'display:none' : '' }}">
          <select name="profile[level]"
            class="form-select @error('profile.level') is-invalid @enderror">
            <option value="">-- اختر المستوى --</option>
            @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
              <option value="{{ $lvl }}" @selected(old('profile.level', $profile['level'] ?? '') == $lvl)>{{ $lvl }}</option>
            @endforeach
          </select>
          @error('profile.level')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <input type="hidden" name="_lang_none" id="langNoneSignal" value="1">
      </div>

      {{-- مسؤول التواصل --}}
      <div class="col-md-3">
        <label class="nma-label">مسؤول التواصل</label>
        <input class="form-control" value="{{ $student->crmInfo->creator->name ?? '-' }}"
          disabled style="background:var(--nma-slate-50);color:var(--nma-slate-600)">
      </div>

      {{-- العلامة الامتحانية --}}
      <div class="col-md-3">
        <label class="nma-label">العلامة الامتحانية</label>
        <input type="number" step="0.01" name="profile[exam_score]"
          value="{{ old('profile.exam_score', $profile['exam_score'] ?? '') }}"
          class="form-control @error('profile.exam_score') is-invalid @enderror"
          placeholder="0.00">
        @error('profile.exam_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- حالة الطالب (hidden) --}}
      <div hidden>
        <select name="status" class="form-select">
          <option value="">-- اختر حالة الطالب --</option>
          @foreach($statusOptions as $st => $stLabel)
            <option value="{{ $st }}" @selected(old('status', $student->status ?? '') == $st)>{{ $stLabel }}</option>
          @endforeach
        </select>
      </div>

      {{-- ملاحظات --}}
      <div class="col-12">
        <label class="nma-label">ملاحظات</label>
        <textarea name="profile[notes]" rows="2"
          class="form-control @error('profile.notes') is-invalid @enderror"
          placeholder="أي ملاحظات إضافية عن الطالب...">{{ old('profile.notes', $profile['notes'] ?? '') }}</textarea>
        @error('profile.notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- رسالة الطالب --}}
      <div class="col-12">
        <div class="msg-toolbar">
          <label class="nma-label mb-0">الرسالة التي سيتم إرسالها لاحقاً للطالب</label>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
              id="generateMessageBtn" style="font-size:12.5px">
              <i class="bi bi-magic"></i> توليد تلقائي
            </button>
            <button type="button" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1"
              id="copyMessageBtn" style="font-size:12.5px">
              <i class="bi bi-clipboard"></i> نسخ
            </button>
          </div>
        </div>
        <textarea name="profile[message_to_send]" id="messageToSend" rows="6"
          class="form-control @error('profile.message_to_send') is-invalid @enderror"
          placeholder="ستُملأ تلقائياً عند الضغط على «توليد تلقائي»...">{{ old('profile.message_to_send', $profile['message_to_send'] ?? '') }}</textarea>
        @error('profile.message_to_send')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      {{-- ────── ملفات الطالب ────── --}}
      <div class="col-12">
        <div class="nma-divider">ملفات الطالب</div>
      </div>

      <div class="col-md-4">
        <label class="nma-label">صورة الطالب</label>
        <input type="file" name="profile[photo]"
          class="form-control @error('profile.photo') is-invalid @enderror" accept="image/*">
        @error('profile.photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(!empty($profile['photo_path']))
          <a target="_blank" href="{{ asset('storage/' . $profile['photo_path']) }}"
            class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
            <i class="bi bi-image"></i> عرض الصورة الحالية
          </a>
        @endif
      </div>

      <div class="col-md-4">
        <label class="nma-label">ملف المعلومات</label>
        <input type="file" name="profile[info_file]"
          class="form-control @error('profile.info_file') is-invalid @enderror"
          accept=".pdf,.doc,.docx,.png,.jpg">
        @error('profile.info_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(!empty($profile['info_file_path']))
          <a target="_blank" href="{{ asset('storage/' . $profile['info_file_path']) }}"
            class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
            <i class="bi bi-file-earmark"></i> عرض الملف الحالي
          </a>
        @endif
      </div>

      {{-- hidden file fields --}}
      <div hidden>
        <input type="file" name="profile[attendance_certificate]" accept=".pdf,.png,.jpg">
        <input type="file" name="profile[certificate_pdf]" accept=".pdf">
        <input type="file" name="profile[certificate_card]" accept=".pdf,.png,.jpg">
      </div>

    </div>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     ⑤ تفاصيل الدبلومات (pivot)
════════════════════════════════════════════════════════════ --}}
@if($studentDiplomas->count())
<div class="nma-section-card">
  <div class="nma-section-header">
    <div class="nma-section-icon blue"><i class="bi bi-card-list"></i></div>
    <h6 class="nma-section-title">تفاصيل وملفات حسب الدبلومة</h6>
  </div>
  <div class="nma-section-body">

    @foreach($student->diplomas as $d)
    <div class="diploma-detail-card">

      {{-- رأس الكارد --}}
      <div class="diploma-detail-header">
        <i class="bi bi-mortarboard text-primary"></i>
        <h6>{{ $d->name }}</h6>
        @if($d->pivot->status)
          @php
            $statusColors = [
              'active'               => 'success',
              'waiting'              => 'warning',
              'withdrawn'            => 'secondary',
              'failed'               => 'danger',
              'absent_exam'          => 'danger',
              'certificate_delivered'=> 'primary',
              'certificate_waiting'  => 'info',
              'registration_ended'   => 'secondary',
              'dismissed'            => 'dark',
              'frozen'               => 'secondary',
            ];
            $statusLabels = [
              'active'               => 'مستمر في الدراسة',
              'waiting'              => 'قيد الانتظار',
              'withdrawn'            => 'منسحب',
              'failed'               => 'راسب',
              'absent_exam'          => 'لم يتقدّم',
              'certificate_delivered'=> 'تم التسليم',
              'certificate_waiting'  => 'بانتظار الشهادة',
              'registration_ended'   => 'انتهى التسجيل',
              'dismissed'            => 'فُصل',
              'frozen'               => 'مجمّد',
            ];
          @endphp
          <span class="badge bg-{{ $statusColors[$d->pivot->status] ?? 'secondary' }} ms-auto"
            style="font-size:11px">
            {{ $statusLabels[$d->pivot->status] ?? $d->pivot->status }}
          </span>
        @endif
      </div>

      <div class="diploma-detail-body">
        <input type="hidden" name="diplomas[{{ $d->id }}][id]" value="{{ $d->id }}">
        <div class="row g-3">

          {{-- الحالة --}}
          <div class="col-md-4">
            <label class="nma-label">الحالة في الدبلومة</label>
            <select name="diplomas[{{ $d->id }}][status]" class="form-select">
              <option value="">-- اختر الحالة --</option>
              <option value="active"                @selected($d->pivot->status == 'active')>مستمر في الدراسة</option>
              <option value="waiting"               @selected($d->pivot->status == 'waiting')>قيد الانتظار</option>
              <option value="withdrawn"             @selected($d->pivot->status == 'withdrawn')>منسحب</option>
              <option value="failed"                @selected($d->pivot->status == 'failed')>راسب</option>
              <option value="absent_exam"           @selected($d->pivot->status == 'absent_exam')>لم يتقدّم للامتحان</option>
              <option value="certificate_delivered" @selected($d->pivot->status == 'certificate_delivered')>تم تسليم الشهادة</option>
              <option value="certificate_waiting"   @selected($d->pivot->status == 'certificate_waiting')>بانتظار الشهادة</option>
              <option value="registration_ended"    @selected($d->pivot->status == 'registration_ended')>انتهى التسجيل</option>
              <option value="dismissed"             @selected($d->pivot->status == 'dismissed')>فُصل الطالب</option>
              <option value="frozen"                @selected($d->pivot->status == 'frozen')>تم تجميد القيد الدراسي</option>
            </select>
          </div>

         {{-- مستوى اللغة --}}
          <div class="col-md-3">
              <label class="nma-label">مستوى اللغة</label>
              <label class="lang-check-row mb-2">
                  <input type="checkbox" class="form-check-input m-0"
                      id="hasLangCheck_{{ $d->id }}"
                      @if(!empty($d->pivot->language_level)) checked @endif>
                  <span style="font-size:13px;color:var(--nma-slate-600)">يوجد مستوى لغة</span>
              </label>
              <div id="langLevelField_{{ $d->id }}"
                  style="{{ empty($d->pivot->language_level) ? 'display:none' : '' }}">
                  <select name="diplomas[{{ $d->id }}][language_level]" class="form-select">
                      <option value="">-- اختر المستوى --</option>
                      @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
                          <option value="{{ $lvl }}" @selected($d->pivot->language_level == $lvl)>{{ $lvl }}</option>
                      @endforeach
                  </select>
              </div>
          </div>

          {{-- تاريخ الانتهاء --}}
          <div class="col-md-3">
            <label class="nma-label">تاريخ انتهاء الدبلومة</label>
            <input type="date" name="diplomas[{{ $d->id }}][ended_at]"
              value="{{ $d->pivot->ended_at }}" class="form-control">
          </div>

          {{-- تسليم الشهادة الكرتون --}}
          <div class="col-md-2 d-flex flex-column justify-content-end">
            <label class="nma-label">تسليم الشهادة</label>
            <label class="lang-check-row">
              <input type="checkbox" class="form-check-input m-0"
                name="diplomas[{{ $d->id }}][certificate_delivered]"
                @checked($d->pivot->certificate_delivered)>
              <span style="font-size:12.5px;color:var(--nma-slate-600)">تم التسليم</span>
            </label>
          </div>

          {{-- اتفاق الشهادة --}}
          <div class="col-md-4">
            <label class="nma-label">اتفاق الشهادة الممنوحة</label>
            <select name="diplomas[{{ $d->id }}][certificate_agreement]" class="form-select">
              <option value="">-- لا يوجد اتفاق --</option>
              <option value="جراح باشا"   @selected($d->pivot->certificate_agreement == 'جراح باشا')>جراح باشا</option>
              <option value="بورد الماني" @selected($d->pivot->certificate_agreement == 'بورد الماني')>بورد الماني</option>
              <option value="جامعة تركية" @selected($d->pivot->certificate_agreement == 'جامعة تركية')>جامعة تركية</option>
              <option value="ميديبول"     @selected($d->pivot->certificate_agreement == 'ميديبول')>ميديبول</option>
            </select>
          </div>

          {{-- ملاحظات الدبلومة --}}
          <div class="col-md-8">
            <label class="nma-label">ملاحظات خاصة بهذه الدبلومة</label>
            <textarea name="diplomas[{{ $d->id }}][notes]" class="form-control"
              rows="2" placeholder="أي ملاحظات...">{{ $d->pivot->notes }}</textarea>
          </div>

          {{-- فاصل الملفات --}}
          <div class="col-12">
            <div class="nma-divider">ملفات الدبلومة</div>
          </div>

          {{-- شهادة الحضور --}}
          <div class="col-md-4">
            <label class="nma-label">شهادة الحضور</label>
            <input type="file" name="diplomas[{{ $d->id }}][attendance_certificate]" class="form-control">
            @if($d->pivot->attendance_certificate_path)
              <a target="_blank" href="{{ asset('storage/' . $d->pivot->attendance_certificate_path) }}"
                class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
                <i class="bi bi-eye"></i> عرض الحالي
              </a>
            @endif
          </div>

          {{-- الشهادة PDF --}}
          <div class="col-md-4">
            <label class="nma-label">الشهادة PDF</label>
            <input type="file" name="diplomas[{{ $d->id }}][certificate_pdf]" class="form-control" accept=".pdf">
            @if($d->pivot->certificate_pdf_path)
              <a target="_blank" href="{{ asset('storage/' . $d->pivot->certificate_pdf_path) }}"
                class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
                <i class="bi bi-file-pdf"></i> عرض الحالي
              </a>
            @endif
          </div>

          {{-- كرت الشهادة --}}
          <div class="col-md-4">
            <label class="nma-label">كرت الشهادة</label>
            <input type="file" name="diplomas[{{ $d->id }}][certificate_card]" class="form-control">
            @if($d->pivot->certificate_card_path)
              <a target="_blank" href="{{ asset('storage/' . $d->pivot->certificate_card_path) }}"
                class="d-inline-flex align-items-center gap-1 mt-1 small text-primary">
                <i class="bi bi-card-image"></i> عرض الحالي
              </a>
            @endif
          </div>

        </div>
      </div>
    </div>
    @endforeach

  </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     أزرار الحفظ
════════════════════════════════════════════════════════════ --}}
<div class="d-flex gap-2 pt-1 pb-3">
  <button class="btn btn-primary fw-bold px-4 d-flex align-items-center gap-2">
    <i class="bi bi-check-lg"></i> حفظ البيانات
  </button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.index') }}">
    إلغاء
  </a>
</div>

{{-- ════════════════════════════════════════════════════════════
     JavaScript
════════════════════════════════════════════════════════════ --}}
@php
  $diplomasJson = $diplomas->map(fn($d) => [
    'id'          => $d->id,
    'name'        => $d->name,
    'code'        => $d->code,
    'type'        => $d->type,
    'branch_id'   => $d->branch_id,
    'branch_name' => $d->branch->name ?? '-',
  ])->values();
  $preloadedJson = $studentDiplomasJson ?? '[]';
@endphp

<script>
(function () {
  /* ───── Diploma Picker ───── */
  var allDiplomas  = {{ Js::from($diplomasJson) }};
  var preloaded    = {{ Js::from(json_decode($preloadedJson)) }};
  var modeSelect   = document.querySelector('[name="mode"]');

  if (modeSelect && !modeSelect.value) modeSelect.value = 'onsite';

  var diplomasByName = {};
  allDiplomas.forEach(function (d) {
    if (!diplomasByName[d.name]) {
      diplomasByName[d.name] = { name: d.name, type: d.type, variants: [] };
    }
    diplomasByName[d.name].variants.push({
      id: d.id, code: d.code, branch_id: d.branch_id, branch_name: d.branch_name
    });
  });

  var diplomaNames      = Object.values(diplomasByName);
  var selectedDiplomas  = {};

  preloaded.forEach(function (d) {
    if (diplomasByName[d.name]) {
      selectedDiplomas[d.name] = { variantId: d.id, code: d.code, branch_id: d.branch_id };
    }
  });

  var diplomaList       = document.getElementById('diplomaList');
  var diplomaSearch     = document.getElementById('diplomaSearch');
  var diplomaEmpty      = document.getElementById('diplomaEmpty');
  var selectedContainer = document.getElementById('selectedDiplomas');
  var noDiplomasMsg     = document.getElementById('noDiplomasMsg');
  var hiddenInputs      = document.getElementById('diplomaHiddenInputs');
  var branchSelect      = document.querySelector('[name="branch_id"]');

  function renderList(filter) {
    filter = filter || '';
    diplomaList.querySelectorAll('.diploma-list-item').forEach(function (el) { el.remove(); });
    var branchId = branchSelect ? branchSelect.value : '';
    var count = 0;

    diplomaNames.forEach(function (group) {
      var nameOk = !filter
        || group.name.indexOf(filter) !== -1
        || group.variants.some(function (v) { return v.code && v.code.indexOf(filter) !== -1; });

      var branchOk = !branchId
        || group.variants.some(function (v) { return v.branch_id == branchId; });

      if (!nameOk || !branchOk) return;

      var isSelected = !!selectedDiplomas[group.name];
      var codes = group.variants.map(function (v) { return v.code; })
        .filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');
      var branches = group.variants.map(function (v) { return v.branch_name; })
        .filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');

      var item = document.createElement('div');
      item.className = 'diploma-list-item' + (isSelected ? ' disabled' : '');
      item.innerHTML =
        '<div>' +
          '<span class="d-name">' + group.name + '</span>' +
          '<div class="d-meta">' +
            '<span><i class="bi bi-tag"></i> ' + codes + '</span>' +
            '<span><i class="bi bi-building"></i> ' + branches + '</span>' +
          '</div>' +
        '</div>' +
        '<div class="d-flex align-items-center gap-2">' +
          '<span class="badge ' + (group.type === 'online' ? 'bg-primary' : 'bg-success') + '" style="font-size:10.5px">' +
            (group.type === 'online' ? 'أونلاين' : 'حضوري') +
          '</span>' +
          (isSelected
            ? '<i class="bi bi-check-circle-fill text-success"></i>'
            : '<i class="bi bi-plus-circle text-primary"></i>') +
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
  }

  function removeDiploma(name) {
    delete selectedDiplomas[name];
    renderSelected();
    renderList(diplomaSearch ? diplomaSearch.value : '');
    updateHidden();
  }

  function renderSelected() {
    selectedContainer.querySelectorAll('.selected-diploma-card').forEach(function (el) { el.remove(); });
    var names = Object.keys(selectedDiplomas);
    if (!names.length) { noDiplomasMsg.style.display = 'block'; return; }
    noDiplomasMsg.style.display = 'none';

    names.forEach(function (name, index) {
      var sel   = selectedDiplomas[name];
      var group = diplomasByName[name];
      if (!group) return;

      var card = document.createElement('div');
      card.className = 'selected-diploma-card';
      card.innerHTML =
        '<div class="sd-info">' +
          '<div class="sd-name">' +
            (index === 0 ? '<span class="badge bg-warning text-dark" style="font-size:10px">رئيسية</span>' : '') +
            name +
          '</div>' +
          '<div class="d-flex align-items-center gap-2 mt-1">' +
            '<span class="sd-badge ' + (group.type === 'online' ? 'online' : 'onsite') + '">' +
              '<i class="bi bi-' + (group.type === 'online' ? 'wifi' : 'geo-alt') + '"></i> ' +
              (group.type === 'online' ? 'أونلاين' : 'حضوري') +
            '</span>' +
            '<span class="sd-meta"><i class="bi bi-tag"></i> ' + sel.code + '</span>' +
          '</div>' +
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
      input.type  = 'hidden';
      input.name  = 'diploma_ids[]';
      input.value = sel.variantId;
      hiddenInputs.appendChild(input);
    });
  }

  if (diplomaSearch) {
    diplomaSearch.addEventListener('input', function () { renderList(this.value); });
  }

  if (branchSelect) {
    branchSelect.addEventListener('change', function () {
      var text = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';
      if (modeSelect) modeSelect.value = text.includes('أونلاين') ? 'online' : 'onsite';

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
    renderList();
    renderSelected();
    updateHidden();

    /* ── ستاج ── */
    var stageCheck = document.getElementById('stageCheck');
    var stageField = document.getElementById('stageField');
    if (stageCheck && stageField) {
      stageCheck.addEventListener('change', function () {
        stageField.style.display = this.checked ? '' : 'none';
        if (!this.checked) {
          var sel = stageField.querySelector('select');
          if (sel) sel.value = '';
        }
      });
    }

    /* ── مستوى اللغة العام ── */
    var hasLangCheck   = document.getElementById('hasLangCheck');
    var langLevelField = document.getElementById('langLevelField');
    if (hasLangCheck && langLevelField) {
      hasLangCheck.addEventListener('change', function () {
        langLevelField.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
          var sel = langLevelField.querySelector('select');
          if (sel) sel.value = '';
        }
      });
    }

    /* ── select2 الجنسية ── */
    if (typeof $ !== 'undefined' && $.fn.select2) {
      $('#nationalitySelect').select2({
        placeholder: 'ابحث عن جنسية...',
        allowClear: true,
        width: '100%',
        language: {
          noResults:  function () { return 'لا توجد نتائج'; },
          searching:  function () { return 'جاري البحث...'; }
        }
      });
    }

    /* ── الاسم اللاتيني ── */
    var latinFirst = document.getElementById('latin_first_name');
    var latinLast  = document.getElementById('latin_last_name');
    var latinFull  = document.getElementById('latin_full_name');

    function mergeLatinName() {
      var first = latinFirst ? latinFirst.value.trim() : '';
      var last  = latinLast  ? latinLast.value.trim()  : '';
      if (latinFull) latinFull.value = (first + ' ' + last).trim();
    }

    if (latinFull && latinFull.value) {
      var parts = latinFull.value.trim().split(/\s+/);
      if (latinFirst) latinFirst.value = parts[0] || '';
      if (latinLast)  latinLast.value  = parts.slice(1).join(' ') || '';
    }

    if (latinFirst) latinFirst.addEventListener('input', mergeLatinName);
    if (latinLast)  latinLast.addEventListener('input',  mergeLatinName);

    /* ── منع الإدخال العربي في الاسم اللاتيني ── */
    var arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/;

    function enforceLatinOnly(input) {
      var fbId     = input.id + '_feedback';
      var feedback = document.getElementById(fbId);
      if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = fbId;
        feedback.style.cssText = 'color:#dc2626;font-size:12px;margin-top:4px;display:none';
        feedback.textContent = 'يُسمح بالحروف اللاتينية (الإنجليزية) فقط';
        input.parentNode.appendChild(feedback);
      }
      input.addEventListener('input', function () {
        if (arabicPattern.test(this.value)) {
          this.value = this.value.replace(/[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/g, '');
          feedback.style.display = 'block';
          this.classList.add('is-invalid');
        } else {
          feedback.style.display = 'none';
          this.classList.remove('is-invalid');
        }
      });
      input.addEventListener('blur', function () {
        if (!arabicPattern.test(this.value)) {
          feedback.style.display = 'none';
          this.classList.remove('is-invalid');
        }
      });
    }

    if (latinFirst) enforceLatinOnly(latinFirst);
    if (latinLast)  enforceLatinOnly(latinLast);

    /* ── الاسم الكامل → first + last ── */
    var fullName  = document.querySelector('input[name="full_name"]');
    var firstName = document.querySelector('input[name="first_name"]');
    var lastName  = document.querySelector('input[name="last_name"]');
    if (fullName && firstName && lastName) {
      firstName.setAttribute('readonly', true);
      lastName.setAttribute('readonly', true);
      fullName.addEventListener('input', function () {
        var parts = this.value.trim().split(/\s+/);
        firstName.value = parts[0] || '';
        lastName.value  = parts.slice(1).join(' ') || '';
      });
    }

    /* ── توليد رسالة الطالب ── */
    var generateBtn = document.getElementById('generateMessageBtn');
    var copyBtn     = document.getElementById('copyMessageBtn');
    var msgArea     = document.getElementById('messageToSend');

    if (generateBtn) {
      generateBtn.addEventListener('click', function () {
        var fullName    = (document.querySelector('input[name="full_name"]')?.value || '').trim();
        var latinFull   = (document.getElementById('latin_full_name')?.value || '').trim();
        var phone       = (document.querySelector('input[name="phone"]')?.value || '').trim();
        var whatsapp    = (document.querySelector('input[name="whatsapp"]')?.value || '').trim();
        var birthDate   = (document.querySelector('input[name="profile[birth_date]"]')?.value || '').trim();
        var nationalId  = (document.querySelector('input[name="profile[national_id]"]')?.value || '').trim();
        var nationality = (document.querySelector('select[name="profile[nationality]"]')?.value || '').trim();
        var country     = (document.querySelector('input[name="crm[country]"]')?.value || '').trim();
        var province    = (document.querySelector('input[name="crm[province]"]')?.value || '').trim();
        var org         = (document.querySelector('input[name="crm[organization]"]')?.value || '').trim();
        var study       = (document.querySelector('input[name="crm[study]"]')?.value || '').trim();
        var eduLevel    = (document.querySelector('select[name="profile[education_level]"]')?.value || '').trim();
        var examScore   = (document.querySelector('input[name="profile[exam_score]"]')?.value || '').trim();

        var diplomaCards = document.querySelectorAll('.selected-diploma-card .sd-name');
        var diplomaList  = [];
        diplomaCards.forEach(function (el) {
          var text = el.innerText.replace('رئيسية', '').trim();
          if (text) diplomaList.push(text);
        });

        var lines = [];
        lines.push('📋 بيانات الطالب');
        lines.push('─────────────────');
        if (fullName)    lines.push('• الاسم الكامل: '       + fullName);
        if (latinFull)   lines.push('• الاسم باللاتيني: '    + latinFull);
        if (phone)       lines.push('• الهاتف: '             + phone);
        if (whatsapp && whatsapp !== phone) lines.push('• واتساب: ' + whatsapp);
        if (birthDate)   lines.push('• تاريخ التولد: '       + birthDate);
        if (nationalId)  lines.push('• الرقم الوطني: '       + nationalId);
        if (nationality) lines.push('• الجنسية: '            + nationality);
        if (country)     lines.push('• البلد: '              + country);
        if (province)    lines.push('• المحافظة: '           + province);

        if (diplomaList.length) {
          lines.push('─────────────────');
          lines.push('📚 الدبلومات:');
          diplomaList.forEach(function (d, i) {
            lines.push('  ' + (i + 1) + '. ' + d);
          });
        }

        if (org || study || eduLevel || examScore) {
          lines.push('─────────────────');
          if (org)       lines.push('• الجهة/المؤسسة: '      + org);
          if (study)     lines.push('• الدراسة: '            + study);
          if (eduLevel)  lines.push('• المستوى التعليمي: '   + eduLevel);
          if (examScore) lines.push('• العلامة الامتحانية: ' + examScore);
        }

        msgArea.value = lines.join('\n');
      });
    }

    if (copyBtn && msgArea) {
      copyBtn.addEventListener('click', function () {
        if (!msgArea.value.trim()) {
          alert('الحقل فارغ — اضغط «توليد تلقائي» أولاً');
          return;
        }
        navigator.clipboard.writeText(msgArea.value).then(function () {
          copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> تم النسخ';
          copyBtn.classList.replace('btn-outline-success', 'btn-success');
          setTimeout(function () {
            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i> نسخ';
            copyBtn.classList.replace('btn-success', 'btn-outline-success');
          }, 2000);
        });
      });
    }




/* ── مستوى اللغة لكل دبلومة ── */
document.querySelectorAll('[id^="hasLangCheck_"]').forEach(function (chk) {
    var id    = chk.id.replace('hasLangCheck_', '');
    var field = document.getElementById('langLevelField_' + id);
    if (!field) return;

    chk.addEventListener('change', function () {
        field.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            var sel = field.querySelector('select');
            if (sel) sel.value = '';
        }
    });
});


  }); // DOMContentLoaded
})();







</script>