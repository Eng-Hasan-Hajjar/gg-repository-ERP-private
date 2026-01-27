@csrf
@if(isset($branch))
  @method('PUT')
@endif

<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">اسم الفرع</label>
    <input name="name" class="form-control" required
           value="{{ old('name', $branch->name ?? '') }}"
           placeholder="مثال: فرع ألمانيا">
  </div>

  <div class="col-md-4">
    <label class="form-label">رمز الفرع</label>
    <input name="code" class="form-control" required
           value="{{ old('code', $branch->code ?? '') }}"
           placeholder="مثال: DE">
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
  <a class="btn btn-outline-secondary" href="{{ route('branches.index') }}">إلغاء</a>
</div>
