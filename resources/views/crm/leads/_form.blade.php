@csrf
@if(isset($lead)) @method('PUT') @endif

@php
  $stageMap = ['new'=>'جديد','follow_up'=>'قيد المتابعة','interested'=>'مهتم','registered'=>'تم التسجيل','rejected'=>'مرفوض','postponed'=>'مؤجل'];
  $sourceMap = ['ad'=>'إعلان','referral'=>'توصية','social'=>'سوشيال ميديا','website'=>'موقع','expo'=>'معرض','other'=>'أخرى'];
  $statusMap = ['pending'=>'قيد الانتظار','converted'=>'تم التحويل','lost'=>'خسارة'];
  $selectedDiplomas = old('diploma_ids', isset($lead) ? $lead->diplomas->pluck('id')->toArray() : []);
  $primaryDiplomaId = old('primary_diploma_id', isset($lead) ? optional($lead->diplomas->firstWhere('pivot.is_primary', true))->id : null);
@endphp

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" value="{{ old('full_name', $lead->full_name ?? '') }}" required>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone', $lead->phone ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">واتساب (رقم أو رابط)</label>
    <input name="whatsapp" class="form-control" value="{{ old('whatsapp', $lead->whatsapp ?? '') }}" placeholder="+90... أو https://wa.me/...">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">تاريخ أول تواصل</label>
    <input type="date" name="first_contact_date" class="form-control" value="{{ old('first_contact_date', optional($lead->first_contact_date ?? null)->format('Y-m-d')) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">مكان السكن</label>
    <input name="residence" class="form-control" value="{{ old('residence', $lead->residence ?? '') }}">
  </div>

  <div class="col-md-2">
    <label class="form-label fw-bold">العمر</label>
    <input type="number" name="age" class="form-control" value="{{ old('age', $lead->age ?? '') }}" min="1" max="120">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الجهة / المؤسسة</label>
    <input name="organization" class="form-control" value="{{ old('organization', $lead->organization ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $lead->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">مصدر العميل</label>
    <select name="source" class="form-select" required>
      @foreach($sourceMap as $k=>$v)
        <option value="{{ $k }}" @selected(old('source', $lead->source ?? 'other')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">مرحلة العميل</label>
    <select name="stage" class="form-select" required>
      @foreach($stageMap as $k=>$v)
        <option value="{{ $k }}" @selected(old('stage', $lead->stage ?? 'new')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  @if(isset($lead))
  <div class="col-md-4">
    <label class="form-label fw-bold">حالة التسجيل</label>
    <select name="registration_status" class="form-select" required>
      @foreach($statusMap as $k=>$v)
        <option value="{{ $k }}" @selected(old('registration_status', $lead->registration_status ?? 'pending')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">تاريخ التسجيل</label>
    <input type="date" name="registered_at" class="form-control" value="{{ old('registered_at', optional($lead->registered_at ?? null)->format('Y-m-d')) }}">
  </div>
  @endif

  <div class="col-12">
    <label class="form-label fw-bold">احتياج العميل</label>
    <textarea name="need" class="form-control" rows="2">{{ old('need', $lead->need ?? '') }}</textarea>
  </div>

  {{-- ✅ عدة دبلومات --}}
  <div class="col-md-8">
    <label class="form-label fw-bold">الدبلومات المطلوبة (يمكن اختيار عدة)</label>
    <select name="diploma_ids[]" class="form-select" multiple required>
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(in_array($d->id, $selectedDiplomas))>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
    <div class="text-muted small mt-1">استخدم Ctrl لاختيار أكثر من دبلومة</div>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الدبلومة الرئيسية (اختياري)</label>
    <select name="primary_diploma_id" class="form-select">
      <option value="">— تلقائيًا أول اختيار —</option>
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected((string)$primaryDiplomaId === (string)$d->id)>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات عامة</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead->notes ?? '') }}</textarea>
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
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('leads.index') }}">إلغاء</a>
</div>
