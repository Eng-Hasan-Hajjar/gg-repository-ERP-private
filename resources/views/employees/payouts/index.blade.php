@extends('layouts.app')
@section('title', 'المستحقات')

@section('content')
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">المستحقات</h4>
      <div class="text-muted fw-semibold">
        {{ $employee->full_name }} — <code>{{ $employee->code }}</code>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-arrow-right"></i> رجوع للملف
      </a>
      @if(auth()->user()?->hasPermission('manage_salaries'))
        <a href="{{ route('employees.payouts.create', $employee) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
          <i class="bi bi-plus-circle"></i> إضافة مستحق
        </a>
      @endif
    </div>
  </div>



  <form class="card border-0 shadow-sm mb-3" method="GET">
    <div class="card-body">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
          <label class="form-label fw-bold">فلترة حسب الحالة</label>
          <select name="status" class="form-select">
            <option value="">الكل</option>
            <option value="pending" @selected(request('status') == 'pending')>معلّق</option>
            <option value="paid" @selected(request('status') == 'paid')>مدفوع</option>
          </select>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <a href="{{ route('employees.payouts.index', $employee) }}" class="btn btn-outline-secondary fw-bold">مسح
            الفلتر</a>
        </div>
      </div>
    </div>
  </form>




  @php
    $paid = $employee->payouts->where('status', 'paid');
    $pending = $employee->payouts->where('status', 'pending');

    $sumPaidByCur = $paid->groupBy('currency')->map(fn($rows) => $rows->sum('amount'));
    $sumPendingByCur = $pending->groupBy('currency')->map(fn($rows) => $rows->sum('amount'));
  @endphp

  <div class="row g-3 mb-3">
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0"><i class="bi bi-check2-circle text-success"></i> المدفوع</h6>
            <span class="badge bg-success">{{ $paid->count() }}</span>
          </div>
          <hr>
          @if($sumPaidByCur->count())
            @foreach($sumPaidByCur as $cur => $sum)
              <div class="d-flex justify-content-between fw-semibold mb-1">
                <span>{{ $cur }}</span>
                <span>{{ number_format($sum, 2) }}</span>
              </div>
            @endforeach
          @else
            <div class="text-muted fw-semibold">لا يوجد مدفوعات.</div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split text-warning"></i> المعلّق</h6>
            <span class="badge bg-warning text-dark">{{ $pending->count() }}</span>
          </div>
          <hr>
          @if($sumPendingByCur->count())
            @foreach($sumPendingByCur as $cur => $sum)
              <div class="d-flex justify-content-between fw-semibold mb-1">
                <span>{{ $cur }}</span>
                <span>{{ number_format($sum, 2) }}</span>
              </div>
            @endforeach
          @else
            <div class="text-muted fw-semibold">لا يوجد مستحقات معلّقة.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>التاريخ</th>
            <th>المبلغ</th>
            <th>العملة</th>
            <th>الحالة</th>
            <th>مرجع</th>
            <th>ملاحظات</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($employee->payouts->sortByDesc('payout_date') as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td>{{ $p->payout_date?->format('Y-m-d') }}</td>
              <td class="fw-bold">{{ number_format($p->amount, 2) }}</td>
              <td><span class="badge bg-light text-dark border">{{ $p->currency }}</span></td>
              <td>
                <span class="badge bg-{{ $p->status == 'paid' ? 'success' : 'warning text-dark' }}">
                  {{ $p->status == 'paid' ? 'مدفوع' : 'معلق' }}
                </span>
              </td>
              <td>{{ $p->reference ?? '-' }}</td>
              <td class="text-muted small">{{ $p->notes ? \Illuminate\Support\Str::limit($p->notes, 60) : '-' }}</td>
              <td class="text-end">
                @if(auth()->user()?->hasPermission('manage_salaries'))
                  @if($p->status !== 'paid')
                    <form class="d-inline" method="POST" action="{{ route('employees.payouts.markPaid', [$employee, $p]) }}">
                      @csrf @method('PATCH')
                      <button class="btn btn-sm btn-outline-success" title="تحويل إلى مدفوع">
                        <i class="bi bi-check2-circle"></i>
                      </button>
                    </form>
                  @endif

                  <a class="btn btn-sm btn-outline-dark" href="{{ route('employees.payouts.edit', [$employee, $p]) }}">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form class="d-inline" method="POST" action="{{ route('employees.payouts.destroy', [$employee, $p]) }}"
                    onsubmit="return confirm('حذف المستحق؟');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد مستحقات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection