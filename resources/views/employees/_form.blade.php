@csrf
@if(isset($employee)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" required value="{{ old('full_name', $employee->full_name ?? '') }}">
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select" required>
      <option value="trainer" @selected(old('type', $employee->type ?? 'trainer')=='trainer')>مدرب</option>
      <option value="employee" @selected(old('type', $employee->type ?? '')=='employee')>موظف</option>
    </select>
  </div>

  <div class="col-6 col-md-3">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <option value="active" @selected(old('status', $employee->status ?? 'active')=='active')>نشط</option>
      <option value="inactive" @selected(old('status', $employee->status ?? '')=='inactive')>غير نشط</option>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}">
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}">
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $employee->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">المسمى الوظيفي</label>
    <input name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title ?? '') }}">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label fw-bold">الدبلومات المرتبطة</label>
    <select name="diploma_ids[]" multiple class="form-select" style="min-height:120px">
      @php($selected = collect(old('diploma_ids', isset($employee) ? $employee->diplomas->pluck('id')->all() : [])))
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected($selected->contains($d->id))>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
    <div class="small text-muted mt-1">اضغط Ctrl لاختيار أكثر من دبلومة.</div>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $employee->notes ?? '') }}</textarea>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
