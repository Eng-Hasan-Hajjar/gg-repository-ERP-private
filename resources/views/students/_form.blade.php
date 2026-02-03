@csrf
@if(isset($student)) @method('PUT') @endif

@php
  $selectedDiplomas = old('diploma_ids',
    isset($student) ? $student->diplomas->pluck('id')->toArray() : []
  );

  $crm = old('crm', isset($student) && $student->crmInfo ? $student->crmInfo->toArray() : []);
  $profile = old('profile', isset($student) && $student->profile ? $student->profile->toArray() : []);
@endphp

<div class="row g-3">

  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم</label>
    <input name="first_name" class="form-control" value="{{ old('first_name',$student->first_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الكنية</label>
    <input name="last_name" class="form-control" value="{{ old('last_name',$student->last_name ?? '-') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" value="{{ old('full_name',$student->full_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone',$student->phone ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" class="form-control" value="{{ old('whatsapp',$student->whatsapp ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email',$student->email ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$student->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">نوع الطالب</label>
    <select name="mode" class="form-select" required>
      <option value="onsite" @selected(old('mode',$student->mode ?? 'onsite')==='onsite')>حضوري</option>
      <option value="online" @selected(old('mode',$student->mode ?? '')==='online')>أونلاين</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">حالة الطالب</label>
    <select name="status" class="form-select" required>
      @foreach(['active','waiting','paid','withdrawn','failed','absent_exam','certificate_delivered','certificate_waiting','registration_ended','dismissed','frozen'] as $st)
        <option value="{{ $st }}" @selected(old('status',$student->status ?? 'waiting')===$st)>{{ $st }}</option>
      @endforeach
    </select>
  </div>

  {{-- ✅ Multi Diplomas --}}
  <div class="col-12">
    <label class="form-label fw-bold">الدبلومات (يمكن اختيار عدة دبلومات)</label>
    <select class="form-select" name="diploma_ids[]" multiple size="6">
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(in_array($d->id,$selectedDiplomas))>{{ $d->name }} ({{ $d->code }})</option>
      @endforeach
    </select>
    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً.</div>
  </div>

  {{-- ✅ CRM section --}}
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label fw-bold">تاريخ أول تواصل</label>
            <input type="date" name="crm[first_contact_date]" class="form-control" value="{{ $crm['first_contact_date'] ?? '' }}">
          </div>

          <div class="col-md-3">
            <label class="form-label fw-bold">العمر</label>
            <input type="number" name="crm[age]" class="form-control" value="{{ $crm['age'] ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-bold">السكن</label>
            <input name="crm[residence]" class="form-control" value="{{ $crm['residence'] ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-bold">الجهة/المؤسسة</label>
            <input name="crm[organization]" class="form-control" value="{{ $crm['organization'] ?? '' }}">
          </div>

          <div class="col-md-3">
            <label class="form-label fw-bold">المصدر</label>
            <select name="crm[source]" class="form-select">
              @foreach(['ad','referral','social','website','expo','other'] as $src)
                <option value="{{ $src }}" @selected(($crm['source'] ?? 'other')===$src)>{{ $src }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-bold">المرحلة</label>
            <select name="crm[stage]" class="form-select">
              @foreach(['new','follow_up','interested','registered','rejected','postponed'] as $st)
                <option value="{{ $st }}" @selected(($crm['stage'] ?? 'registered')===$st)>{{ $st }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">الاحتياج</label>
            <textarea name="crm[need]" class="form-control" rows="2">{{ $crm['need'] ?? '' }}</textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">ملاحظات CRM</label>
            <textarea name="crm[notes]" class="form-control" rows="2">{{ $crm['notes'] ?? '' }}</textarea>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ✅ Profile section --}}
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">الملف التفصيلي للطالب</h6>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">الاسم بالعربي</label>
            <input name="profile[arabic_full_name]" class="form-control" value="{{ $profile['arabic_full_name'] ?? '' }}">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">الجنسية</label>
            <input name="profile[nationality]" class="form-control" value="{{ $profile['nationality'] ?? '' }}">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">تاريخ الولادة</label>
            <input type="date" name="profile[birth_date]" class="form-control" value="{{ $profile['birth_date'] ?? '' }}">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">الرقم الوطني</label>
            <input name="profile[national_id]" class="form-control" value="{{ $profile['national_id'] ?? '' }}">
          </div>

          <div class="col-md-8">
            <label class="form-label fw-bold">العنوان</label>
            <input name="profile[address]" class="form-control" value="{{ $profile['address'] ?? '' }}">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">العلامة</label>
            <input type="number" step="0.01" name="profile[exam_score]" class="form-control" value="{{ $profile['exam_score'] ?? '' }}">
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">ملاحظات</label>
            <textarea name="profile[notes]" class="form-control" rows="2">{{ $profile['notes'] ?? '' }}</textarea>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.index') }}">إلغاء</a>
</div>
