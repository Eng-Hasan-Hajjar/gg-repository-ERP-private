@csrf
@if(isset($diploma))
  @method('PUT')
@endif

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم الدبلومة</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $diploma->name ?? '') }}"
           placeholder="مثال: دبلوم البرمجة الاحترافية">
  </div>

  <div class="col-md-3">
    <label class="form-label">رمز الدبلومة</label>
    <input name="code" class="form-control" required
           value="{{ old('code', $diploma->code ?? '') }}"
           placeholder="مثال: PROG-01">
    <div class="form-text">يسمح: حروف/أرقام/شرطة (-) وشرطة سفلية (_).</div>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="is_active" class="form-select">
      <option value="1" @selected(old('is_active', ($diploma->is_active ?? true)) == true)>مفعّلة</option>
      <option value="0" @selected(old('is_active', ($diploma->is_active ?? true)) == false)>غير مفعّلة</option>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">مجال الدبلومة</label>
    <input name="field" class="form-control"
           value="{{ old('field', $diploma->field ?? '') }}"
           placeholder="مثال: تقنية معلومات / لغات / إدارة أعمال ...">
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
  <button class="btn btn-primary">حفظ</button>
  <a class="btn btn-outline-secondary" href="{{ route('diplomas.index') }}">إلغاء</a>
</div>
