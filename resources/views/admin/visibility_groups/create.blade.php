@extends('layouts.app')
@section('title', 'إنشاء مجموعة رؤية')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">إنشاء مجموعة رؤية جديدة</h4>
  <a href="{{ route('admin.visibility-groups.index') }}" class="btn btn-outline-secondary">رجوع</a>
</div>

<form method="POST" action="{{ route('admin.visibility-groups.store') }}">
  @csrf

  <div class="card border-0 shadow-sm mb-3">
    <div class="card-header fw-bold">معلومات المجموعة</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="fw-bold">اسم المجموعة <span class="text-danger">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}"
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="مثال: برامج الأونلاين، فرع إسطنبول">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <label class="fw-bold">ملاحظات</label>
          <input type="text" name="notes" value="{{ old('notes') }}" class="form-control"
                 placeholder="وصف اختياري للمجموعة">
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">

    {{-- المديرون --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header fw-bold d-flex align-items-center gap-2">
          <span class="badge bg-danger">مدير</span>
          المديرون
          <small class="text-muted fw-normal">(يرون تقارير الأعضاء)</small>
        </div>
        <div class="card-body" style="max-height:400px; overflow-y:auto;">
          @foreach($employees as $emp)
            <div class="form-check mb-1">
              <input type="checkbox" name="managers[]" value="{{ $emp->id }}"
                     id="mgr_{{ $emp->id }}" class="form-check-input"
                     @checked(in_array($emp->id, old('managers', [])))>
              <label for="mgr_{{ $emp->id }}" class="form-check-label">
                {{ $emp->full_name }}
                @if($emp->branch)
                  <small class="text-muted">({{ $emp->branch->name }})</small>
                @endif
              </label>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- الأعضاء --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header fw-bold d-flex align-items-center gap-2">
          <span class="badge bg-info text-dark">عضو</span>
          الأعضاء
          <small class="text-muted fw-normal">(تقاريرهم مرئية للمديرين)</small>
        </div>
        <div class="card-body" style="max-height:400px; overflow-y:auto;">
          @foreach($employees as $emp)
            <div class="form-check mb-1">
              <input type="checkbox" name="members[]" value="{{ $emp->id }}"
                     id="mem_{{ $emp->id }}" class="form-check-input"
                     @checked(in_array($emp->id, old('members', [])))>
              <label for="mem_{{ $emp->id }}" class="form-check-label">
                {{ $emp->full_name }}
                @if($emp->branch)
                  <small class="text-muted">({{ $emp->branch->name }})</small>
                @endif
              </label>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

  <div class="mt-4 text-end">
    <button class="btn btn-namaa px-5 fw-bold">
      <i class="bi bi-check-circle"></i> حفظ المجموعة
    </button>
  </div>

</form>

@endsection