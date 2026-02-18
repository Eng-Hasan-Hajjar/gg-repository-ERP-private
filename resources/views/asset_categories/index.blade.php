@extends('layouts.app')
@section('title','تصنيفات الأصول')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-1 fw-bold">تصنيفات الأصول</h4>
    <div class="text-muted">إضافة/تعديل تصنيفات مثل: أجهزة، أثاث، شبكات...</div>
  </div>
  <a href="{{ route('asset-categories.create') }}" class="btn btn-primary rounded-pill fw-bold px-4">
    <i class="bi bi-plus-lg"></i> تصنيف جديد
  </a>
</div>

<form class="card border-0 shadow-sm mb-3" method="GET">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-lg-10">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: الاسم أو الكود">
      </div>
      <div class="col-12 col-lg-2 d-grid">
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
          <th>#</th>
          <th>الاسم</th>
          <th>الكود</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $it)
          <tr>
            <td>{{ $it->id }}</td>
            <td class="fw-bold">{{ $it->name }}</td>
            <td><code>{{ $it->code }}</code></td>
            <td class="text-end">
              <a href="{{ route('asset-categories.edit',$it) }}" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-pencil"></i> تعديل
              </a>
              <form class="d-inline" method="POST" action="{{ route('asset-categories.destroy',$it) }}"
                    onsubmit="return confirm('تأكيد حذف التصنيف؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $items->links() }}
</div>
@endsection
