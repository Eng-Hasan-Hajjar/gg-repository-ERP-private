@extends('layouts.app')
@section('title','عقود')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0 fw-bold">العقود</h4>
    <div class="text-muted fw-semibold">{{ $employee->full_name }} — <code>{{ $employee->code }}</code></div>
  </div>
  <a href="{{ route('employees.contracts.create',$employee) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> إضافة عقد
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>بداية</th>
          <th>نهاية</th>
          <th>نوع</th>
          <th>راتب</th>
          <th>ساعة</th>
          <th>عملة</th>
          <th class="text-end">إجراءات</th>

        </tr>
      </thead>
      <tbody>
        @forelse($employee->contracts as $c)
          <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->start_date?->format('Y-m-d') }}</td>
            <td>{{ $c->end_date?->format('Y-m-d') ?? '-' }}</td>
            <td>{{ $c->contract_type }}</td>
            <td>{{ $c->salary_amount ?? '-' }}</td>
            <td>{{ $c->hour_rate ?? '-' }}</td>
            <td>{{ $c->currency }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-dark" href="{{ route('employees.contracts.edit',[$employee,$c]) }}">
                <i class="bi bi-pencil"></i>
              </a>

              <form class="d-inline" method="POST" action="{{ route('employees.contracts.destroy',[$employee,$c]) }}"
                    onsubmit="return confirm('حذف العقد؟');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>

          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد عقود</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
