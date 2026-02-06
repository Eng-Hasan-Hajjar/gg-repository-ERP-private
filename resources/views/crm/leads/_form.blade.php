@csrf
@if(isset($lead)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" value="{{ old('full_name',$lead->full_name ?? '') }}" required>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone',$lead->phone ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" class="form-control" value="{{ old('whatsapp',$lead->whatsapp ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">تاريخ أول تواصل</label>
    <input type="date" name="first_contact_date" class="form-control" value="{{ old('first_contact_date',$lead->first_contact_date ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">العمر</label>
    <input type="number" name="age" class="form-control" value="{{ old('age',$lead->age ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email',$lead->email ?? '') }}">
  </div>

    <div class="col-md-3">
      <label class="form-label fw-bold">العمل</label>
     <input name="job" class="form-control"
       value="{{ old('job', $lead->job ?? '') }}">

    </div>
    <div class="col-md-4">
  <label class="form-label fw-bold">البلد</label>
  <input name="country" class="form-control" value="{{ old('country',$lead->country ?? '') }}">
</div>

<div class="col-md-4">
  <label class="form-label fw-bold">المحافظة</label>
  <input name="province" class="form-control" value="{{ old('province',$lead->province ?? '') }}">
</div>

<div class="col-md-4">
  <label class="form-label fw-bold">الدراسة</label>
  <input name="study" class="form-control" value="{{ old('study',$lead->study ?? '') }}">
</div>

  <div class="col-md-6">
    <label class="form-label fw-bold">مكان السكن</label>
    <input name="residence" class="form-control" value="{{ old('residence',$lead->residence ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">الجهة / المؤسسة</label>
    <input name="organization" class="form-control" value="{{ old('organization',$lead->organization ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$lead->branch_id ?? '')==$b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">المصدر</label>
    <select name="source" class="form-select" required>
      @foreach([
        'ad'       => 'إعلان مدفوع',
        'referral' => 'إحالة / توصية',
        'social'   => 'وسائل التواصل الاجتماعي',
        'website'  => 'الموقع الإلكتروني',
        'expo'     => 'معرض / فعالية',
        'other'    => 'أخرى'
      ] as $value => $label)
        <option value="{{ $value }}" @selected(old('source',$lead->source ?? 'other')==$value)>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">المرحلة</label>
    <select name="stage" class="form-select" required>
      @foreach([
        'new'         => 'جديد',
        'follow_up'   => 'متابعة',
        'interested'  => 'مهتم',
        'registered'  => 'مسجل',
        'rejected'    => 'مرفوض',
        'postponed'   => 'مؤجل'
      ] as $value => $label)
        <option value="{{ $value }}" @selected(old('stage',$lead->stage ?? 'new')==$value)>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">حالة التسجيل</label>
    <select name="registration_status" class="form-select">
      @foreach([
        'pending'   => 'قيد الانتظار',
        'converted' => 'تم التحويل',
        'lost'      => 'مفقود'
      ] as $value => $label)
        <option value="{{ $value }}" @selected(old('registration_status',$lead->registration_status ?? 'pending')==$value)>
          {{ $label }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- ✅ multi diplomas --}}
  <div class="col-12">
    <label class="form-label fw-bold">الدبلومات المطلوبة (يمكن اختيار عدة دبلومات)</label>
    @php($selected = old('diploma_ids', isset($lead)? $lead->diplomas->pluck('id')->toArray() : []))
    <select name="diploma_ids[]" class="form-select" multiple size="6">
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(in_array($d->id,$selected))>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
    <div class="text-muted small mt-1">
      أول خيار يعتبر دبلومة رئيسية عند التحويل.
    </div>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">الاحتياج</label>
    <textarea name="need" class="form-control" rows="2">{{ old('need',$lead->need ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes',$lead->notes ?? '') }}</textarea>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('leads.index') }}">
    إلغاء
  </a>
</div>
