@csrf
@if(isset($lead)) @method('PUT') @endif
{{-- عرض جميع الأخطاء --}}
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

<div class="row g-3">
  {{-- الاسم --}}
  <div class="col-md-6">
    <label class="form-label fw-bold">الاسم الكامل *</label>
    <input name="full_name"
           value="{{ old('full_name',$lead->full_name ?? '') }}"
           class="form-control @error('full_name') is-invalid @enderror">
    @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>



  {{-- الهاتف --}}
  <div class="col-md-3">
    <label class="form-label fw-bold">الهاتف *</label>
    <input name="phone"
           value="{{ old('phone',$lead->phone ?? '') }}"
           class="form-control @error('phone') is-invalid @enderror">
    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- واتساب --}}
  <div class="col-md-3">
    <label class="form-label fw-bold">واتساب *</label>
    <input name="whatsapp"
           value="{{ old('whatsapp',$lead->whatsapp ?? '') }}"
           class="form-control @error('whatsapp') is-invalid @enderror">
    @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- التاريخ --}}
  <div class="col-md-3">
    <label class="form-label fw-bold">تاريخ أول تواصل *</label>
    <input type="date" name="first_contact_date"
           value="{{ old('first_contact_date',$lead->first_contact_date ?? '') }}"
           class="form-control @error('first_contact_date') is-invalid @enderror">
    @error('first_contact_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- العمر --}}
  <div class="col-md-3">
    <label class="form-label fw-bold">العمر *</label>
    <input type="number" name="age"
           value="{{ old('age',$lead->age ?? '') }}"
           class="form-control @error('age') is-invalid @enderror">
    @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- الإيميل --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الإيميل *</label>
    <input name="email"
           value="{{ old('email',$lead->email ?? '') }}"
           class="form-control @error('email') is-invalid @enderror">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>


  {{-- العمل --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">العمل *</label>
    <input name="job"
           value="{{ old('job',$lead->job ?? '') }}"
           class="form-control @error('job') is-invalid @enderror">
    @error('job') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- البلد --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">البلد *</label>
    <input name="country"
           value="{{ old('country',$lead->country ?? '') }}"
           class="form-control @error('country') is-invalid @enderror">
    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- المحافظة --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">المحافظة *</label>
    <input name="province"
           value="{{ old('province',$lead->province ?? '') }}"
           class="form-control @error('province') is-invalid @enderror">
    @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- الدراسة --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الدراسة *</label>
    <input name="study"
           value="{{ old('study',$lead->study ?? '') }}"
           class="form-control @error('study') is-invalid @enderror">
    @error('study') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- السكن --}}
  <div class="col-md-6">
    <label class="form-label fw-bold">مكان السكن *</label>
    <input name="residence"
           value="{{ old('residence',$lead->residence ?? '') }}"
           class="form-control @error('residence') is-invalid @enderror">
    @error('residence') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- الجهة --}}
  <div class="col-md-6">
    <label class="form-label fw-bold">الجهة *</label>
    <input name="organization"
           value="{{ old('organization',$lead->organization ?? '') }}"
           class="form-control @error('organization') is-invalid @enderror">
    @error('organization') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>





  {{-- الفرع --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع *</label>
    <select name="branch_id"
            class="form-select @error('branch_id') is-invalid @enderror">
      <option value="">اختر الفرع</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$lead->branch_id ?? '')==$b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- المصدر --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">المصدر *</label>
    <select name="source"
            class="form-select @error('source') is-invalid @enderror">
      <option value="">اختر المصدر</option>
      <option value="ad">إعلان</option>
      <option value="referral">إحالة</option>
      <option value="social">سوشيال</option>
      <option value="website">موقع</option>
      <option value="expo">فعالية</option>
      <option value="other">أخرى</option>
    </select>
    @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- المرحلة --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">المرحلة *</label>
    <select name="stage"
            class="form-select @error('stage') is-invalid @enderror">
      <option value="">اختر المرحلة</option>
      <option value="new">جديد</option>
      <option value="follow_up">متابعة</option>
      <option value="interested">مهتم</option>
      <option value="registered">مسجل</option>
      <option value="rejected">مرفوض</option>
      <option value="postponed">مؤجل</option>
    </select>
    @error('stage') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>



<div class="col-12">
  <label class="form-label fw-bold">الدبلومات المطلوبة *</label>

  <select name="diploma_ids[]"
          multiple
          size="6"
          class="form-select @error('diploma_ids') is-invalid @enderror">

      @foreach($diplomas as $d)
        <option value="{{ $d->id }}"
          {{ collect(old('diploma_ids'))->contains($d->id) ? 'selected' : '' }}>
          {{ $d->name }}
        </option>
      @endforeach

  </select>

  @error('diploma_ids')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>


<div class="col-12">
  <label class="form-label fw-bold">الاحتياج *</label>

  <textarea name="need"
            class="form-control @error('need') is-invalid @enderror">{{ old('need',$lead->need ?? '') }}</textarea>

  @error('need')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>




<div class="col-12">
  <label class="form-label fw-bold">ملاحظات *</label>

  <textarea name="notes"
            class="form-control @error('notes') is-invalid @enderror">{{ old('notes',$lead->notes ?? '') }}</textarea>

  @error('notes')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>





</div>

<div class="mt-4">
  <button class="btn btn-primary px-5">حفظ</button>
</div>