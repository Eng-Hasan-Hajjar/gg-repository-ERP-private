@csrf

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم البرمجي (Unique)</label>
        <input name="name" class="form-control"
               value="{{ old('name',$role->name ?? '') }}"
               @isset($role) disabled @endisset
               required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">الاسم المعروض</label>
        <input name="label" class="form-control"
               value="{{ old('label',$role->label ?? '') }}" required>
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">الوصف</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description',$role->description ?? '') }}</textarea>
      </div>

    </div>
  </div>
</div>

<h5 class="fw-bold mb-2">الصلاحيات حسب الوحدة</h5>

@foreach($permissions as $module => $items)
<div class="card border-0 shadow-sm mb-3">
  <div class="card-header bg-light fw-bold">
  
    
    {{ t($module) }}



  </div>
  <div class="card-body">
    <div class="row">
      @foreach($items as $p)
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input"
                 type="checkbox"
                 name="permissions[]"
                 value="{{ $p->id }}"
                 @checked(in_array($p->id,$rolePermissions ?? []))>
          <label class="form-check-label">
            {{ $p->label }}
          </label>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endforeach

@if($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0">
    @foreach($errors->all() as $e)
      <li>{{ $e }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a href="{{ route('admin.roles.index') }}"
     class="btn btn-outline-secondary fw-bold px-4">إلغاء</a>
</div>
