@extends('layouts.app')
@php($activeModule = 'branches')
@section('title','الفروع')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <div>
    <h4 class="mb-1">إدارة الفروع</h4>
    <div class="text-muted small">إضافة وتعديل الفروع وربطها بالطلاب.</div>
  </div>
  <a class="btn btn-primary" href="{{ route('branches.create') }}">
    + إضافة فرع
  </a>
</div>

<form class="card card-body mb-3" method="GET" action="{{ route('branches.index') }}">
  <div class="row g-2 align-items-end">
    <div class="col-md-9">
      <label class="form-label mb-1">بحث</label>
      <input name="search" value="{{ request('search') }}" class="form-control"
             placeholder="ابحث بالاسم أو الرمز">
    </div>
    <div class="col-md-3 d-grid">
      <button class="btn btn-namaa">تطبيق</button>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>اسم الفرع</th>
          <th>الرمز</th>
          <th>عدد الطلاب</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($branches as $b)
          <tr>
            <td>{{ $b->id }}</td>
            <td class="fw-semibold">{{ $b->name }}</td>
            <td><span class="badge text-bg-secondary">{{ $b->code }}</span></td>
            <td><span class="badge text-bg-light border">{{ $b->students()->count() }}</span></td>
            <td class="text-end">
              <div class="d-inline-flex gap-1">
                <a class="btn btn-sm btn-outline-dark" href="{{ route('branches.edit', $b) }}">تعديل</a>
                <form method="POST" action="{{ route('branches.destroy', $b) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف الفرع؟');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">حذف</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">لا يوجد فروع</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $branches->links() }}
</div>
@endsection
