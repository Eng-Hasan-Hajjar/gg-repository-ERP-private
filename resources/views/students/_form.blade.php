@csrf
@if(isset($student))
  @method('PUT')
@endif

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم</label>
    <input name="first_name" class="form-control" value="{{ old('first_name', $student->first_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الكنية</label>
    <input name="last_name" class="form-control" value="{{ old('last_name', $student->last_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" value="{{ old('full_name', $student->full_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الدبلومة</label>
    <select name="diploma_id" class="form-select">
      <option value="">— اختر الدبلومة —</option>
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(old('diploma_id', $student->diploma_id ?? '')==$d->id)>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone', $student->phone ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" class="form-control" value="{{ old('whatsapp', $student->whatsapp ?? '') }}" placeholder="مثال: +49... أو رابط wa.me">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email', $student->email ?? '') }}" placeholder="example@mail.com">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $student->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">نوع الطالب</label>
    <select name="mode" class="form-select" required>
      <option value="onsite" @selected(old('mode', $student->mode ?? 'onsite')=='onsite')>حضوري</option>
      <option value="online" @selected(old('mode', $student->mode ?? '')=='online')>أونلاين</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">حالة الطالب</label>
    <select name="status" class="form-select" required>
      @foreach(['active','waiting','paid','withdrawn','failed','absent_exam','certificate_delivered','certificate_waiting','registration_ended','dismissed','frozen'] as $st)
        <option value="{{ $st }}" @selected(old('status', $student->status ?? 'waiting')==$st)>{{ $st }}</option>
      @endforeach
    </select>
  </div>
</div>

<hr class="my-4">
<h6 class="fw-bold mb-3">بيانات الاستشارات (CRM) — اختياري</h6>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label fw-bold">تاريخ أول تواصل</label>
    <input type="date" name="crm[first_contact_date]" class="form-control"
      value="{{ old('crm.first_contact_date') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">السكن</label>
    <input name="crm[residence]" class="form-control" value="{{ old('crm.residence') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">العمر</label>
    <input type="number" min="10" max="80" name="crm[age]" class="form-control"
      value="{{ old('crm.age') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">الجهة / المؤسسة</label>
    <input name="crm[organization]" class="form-control" value="{{ old('crm.organization') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">المصدر</label>
    <select name="crm[source]" class="form-select">
      @foreach(['ad'=>'إعلان','referral'=>'توصية','social'=>'سوشيال','website'=>'موقع','expo'=>'معرض','other'=>'أخرى'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('crm.source','other')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">مرحلة العميل</label>
    <select name="crm[stage]" class="form-select">
      @foreach(['new'=>'جديد','follow_up'=>'قيد المتابعة','interested'=>'مهتم','registered'=>'تم التسجيل','rejected'=>'مرفوض','postponed'=>'مؤجل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('crm.stage','new')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الاحتياج</label>
    <textarea name="crm[need]" class="form-control" rows="2">{{ old('crm.need') }}</textarea>
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
