@extends('layouts.app')
@php($activeModule = 'diplomas')
@section('title','الدبلومات')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <div>
    <h4 class="mb-1">إدارة الدبلومات</h4>
    <div class="text-muted small">إنشاء وتعديل وتفعيل الدبلومات وربطها بالطلاب.</div>
  </div>
  <a class="btn btn-primary" href="{{ route('diplomas.create') }}">
    + إضافة دبلومة
  </a>
</div>

<form class="card card-body mb-3" method="GET" action="{{ route('diplomas.index') }}">
  <div class="row g-2 align-items-end">
    <div class="col-md-6">
      <label class="form-label mb-1">بحث</label>
      <input name="search" value="{{ request('search') }}" class="form-control"
             placeholder="ابحث بالاسم / الرمز / المجال">
    </div>

    <div class="col-md-3">
      <label class="form-label mb-1">الحالة</label>
      <select name="is_active" class="form-select">
        <option value="">الكل</option>
        <option value="1" @selected(request('is_active')==='1')>مفعّلة</option>
        <option value="0" @selected(request('is_active')==='0')>غير مفعّلة</option>
      </select>
    </div>

    <div class="col-md-3 d-grid">
      <button class="btn btn-dark">تطبيق</button>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>اسم الدبلومة</th>
          <th>الرمز</th>
          <th>المجال</th>
          <th>الحالة</th>
          <th>عدد الطلاب</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($diplomas as $d)
          <tr>
            <td>{{ $d->id }}</td>
            <td class="fw-semibold">{{ $d->name }}</td>
            <td><span class="badge text-bg-secondary">{{ $d->code }}</span></td>
            <td class="text-muted">{{ $d->field ?? '-' }}</td>
            <td>
              @if($d->is_active)
                <span class="badge text-bg-success">مفعّلة</span>
              @else
                <span class="badge text-bg-danger">غير مفعّلة</span>
              @endif
            </td>
            <td>
              <span class="badge text-bg-light border">{{ $d->students()->count() }}</span>
            </td>
            <td class="text-end">
              <div class="d-inline-flex gap-1">
                <form method="POST" action="{{ route('diplomas.toggle', $d) }}">
                  @csrf
                  @method('PATCH')
                  <button class="btn btn-sm btn-outline-success">
                    {{ $d->is_active ? 'تعطيل' : 'تفعيل' }}
                  </button>
                </form>

                <a class="btn btn-sm btn-outline-dark" href="{{ route('diplomas.edit', $d) }}">تعديل</a>

                <form method="POST" action="{{ route('diplomas.destroy', $d) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف الدبلومة؟');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">حذف</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">لا يوجد دبلومات</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $diplomas->links() }}
</div>
@endsection
