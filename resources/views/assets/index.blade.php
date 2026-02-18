@extends('layouts.app')
@section('title','الأصول')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">اللوجستيات وإدارة الأصول</h4>
    <div class="text-muted">إدارة الأجهزة والمعدات، تتبع الحالة والموقع والتكلفة.</div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('asset-categories.index') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
      <i class="bi bi-tags"></i> تصنيفات الأصول
    </a>
    <a href="{{ route('assets.create') }}" class="btn btn-primary rounded-pill fw-bold px-4">
      <i class="bi bi-plus-lg"></i> أصل جديد
    </a>
  </div>
</div>

<form class="card border-0 shadow-sm mb-3" method="GET" action="{{ route('assets.index') }}">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-lg-4">
        <input name="search" value="{{ request('search') }}" class="form-control"
               placeholder="بحث: الاسم / AST / سيريال">
      </div>

      <div class="col-6 col-lg-2">
        <select name="branch_id" class="form-select">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-lg-3">
        <select name="asset_category_id" class="form-select">
          <option value="">كل التصنيفات</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(request('asset_category_id')==$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-lg-2">
        <select name="condition" class="form-select">
          <option value="">كل الحالات</option>
          <option value="good" @selected(request('condition')=='good')>جيد</option>
          <option value="maintenance" @selected(request('condition')=='maintenance')>صيانة</option>
          <option value="out_of_service" @selected(request('condition')=='out_of_service')>خارج الخدمة</option>
        </select>
      </div>

      <div class="col-6 col-lg-1 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="hide-mobile">#</th>
          <th class="hide-mobile">التاغ</th>
          <th>الأصل</th>
          <th>التصنيف</th>
          <th>الفرع</th>
          <th>الحالة</th>
          <th>الموقع</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assets as $a)
          <tr>
            <td class="hide-mobile">{{ $a->id }}</td>
            <td class="hide-mobile"><code>{{ $a->asset_tag }}</code></td>
            <td class="fw-bold">{{ $a->name }}</td>
            <td>{{ $a->category->name ?? '-' }}</td>
            <td>{{ $a->branch->name ?? '-' }}</td>
            <td>
              <span class="badge {{ $a->condition_badge_class }}">
                {{ $a->condition_label }}
              </span>
            </td>
            <td>{{ $a->location ?? '-' }}</td>
            <td class="text-end">
              <a href="{{ route('assets.show',$a) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> عرض
              </a>
              <a href="{{ route('assets.edit',$a) }}" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-pencil"></i> تعديل
              </a>
              <form class="d-inline" method="POST" action="{{ route('assets.destroy',$a) }}"
                    onsubmit="return confirm('تأكيد حذف الأصل؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">لا يوجد أصول</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $assets->links() }}
</div>
@endsection
