@csrf
@if(isset($exam)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-bold">اسم الامتحان</label>
    <input name="title" class="form-control" required value="{{ old('title',$exam->title ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الكود (اختياري)</label>
    <input name="code" class="form-control" value="{{ old('code',$exam->code ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="exam_date" class="form-control"
      value="{{ old('exam_date', optional($exam->exam_date ?? null)->format('Y-m-d')) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select">
      @foreach(['quiz','midterm','final','practical','other'] as $t)
        <option value="{{ $t }}" @selected(old('type',$exam->type ?? 'other')==$t)>{{ $t }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الحد الأعلى</label>
    <input type="number" step="0.01" name="max_score" class="form-control"
      value="{{ old('max_score',$exam->max_score ?? 100) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">حد النجاح (اختياري)</label>
    <input type="number" step="0.01" name="pass_score" class="form-control"
      value="{{ old('pass_score',$exam->pass_score ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">الدبلومة</label>
    <select name="diploma_id" class="form-select" required>
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(old('diploma_id',$exam->diploma_id ?? '')==$d->id)>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$exam->branch_id ?? '')==$b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">المدرب المسؤول (اختياري)</label>
    <select name="trainer_id" class="form-select">
      <option value="">-</option>
      @foreach($trainers as $t)
        <option value="{{ $t->id }}" @selected(old('trainer_id',$exam->trainer_id ?? '')==$t->id)>
          {{ $t->full_name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes',$exam->notes ?? '') }}</textarea>
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
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('exams.index') }}">إلغاء</a>
</div>
