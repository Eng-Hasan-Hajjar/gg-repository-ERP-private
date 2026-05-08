@extends('layouts.app')
@section('title', 'مجموعات الرؤية')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0">مجموعات الرؤية</h4>
    <div class="text-muted small">تحكم بمن يرى تقارير ومهام من</div>
  </div>
  <a href="{{ route('admin.visibility-groups.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> مجموعة جديدة
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>اسم المجموعة</th>
          <th class="text-center">عدد الأعضاء</th>
          <th>الملاحظات</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($groups as $g)
          <tr>
            <td>{{ $g->id }}</td>
            <td class="fw-bold">{{ $g->name }}</td>
            <td class="text-center">
              <span class="badge bg-primary">{{ $g->employees_count }}</span>
            </td>
            <td class="text-muted small">{{ $g->notes ?? '—' }}</td>
            <td class="text-end">
              <a href="{{ route('admin.visibility-groups.edit', $g) }}"
                 class="btn btn-sm btn-outline-dark">
                <i class="bi bi-pencil"></i> تعديل
              </a>
              <form method="POST" action="{{ route('admin.visibility-groups.destroy', $g) }}"
                    class="d-inline" onsubmit="return confirm('حذف المجموعة؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">لا توجد مجموعات</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection