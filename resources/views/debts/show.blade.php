@extends('layouts.app')
@php($activeModule = 'finance')
@section('title', 'ذمة الطالب')

@section('content')

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="bi bi-person-vcard"></i> ذمة الطالب</h4>
    <div class="text-muted fw-semibold">
      {{ $student->full_name }}
      — <code>{{ $student->university_id }}</code>
      @if($student->phone) — {{ $student->phone }} @endif
    </div>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('debts.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع</a>
    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
      <i class="bi bi-person"></i> ملف الطالب
    </a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="text-muted small fw-semibold mb-1">إجمالي المستحق</div>
      <div class="fw-bold fs-5">{{ number_format($summaryTotal, 2) }}</div>
      <div class="text-muted small">{{ $summaryCurrency }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="text-muted small fw-semibold mb-1">المدفوع</div>
      <div class="fw-bold fs-5 text-success">{{ number_format($summaryPaid, 2) }}</div>
      <div class="text-muted small">{{ $summaryCurrency }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="text-muted small fw-semibold mb-1">المتبقي</div>
      <div class="fw-bold fs-5 {{ $summaryRemaining > 0 ? 'text-danger' : 'text-success' }}">
        {{ number_format(abs($summaryRemaining), 2) }}
      </div>
      <div class="text-muted small">{{ $summaryRemaining < 0 ? 'زيادة دفع' : $summaryCurrency }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="text-muted small fw-semibold mb-1">نسبة السداد</div>
      <div class="fw-bold fs-5">{{ $summaryPct }}%</div>
      <div class="progress mt-2" style="height:6px;">
        <div class="progress-bar bg-success" style="width:{{ $summaryPct }}%"></div>
      </div>
    </div>
  </div>
</div>

@forelse($debtByDiploma as $item)
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center"
         style="background:linear-gradient(135deg,#11998e,#38ef7d); color:#fff;">
      <span>
        <i class="bi bi-mortarboard"></i>
        {{ optional($item['diploma'])->name ?? 'دبلومة غير معروفة' }}
      </span>
      <span class="badge bg-white text-dark px-3">
        {{ $item['payment_type'] === 'full' ? 'دفعة كاملة' : 'دفعات' }}
      </span>
    </div>
    <div class="card-body">

      <div class="row g-2 mb-3">
        <div class="col-4 text-center">
          <div class="small text-muted">المستحق</div>
          <div class="fw-bold">{{ number_format($item['total'], 2) }} {{ $item['currency'] }}</div>
        </div>
        <div class="col-4 text-center">
          <div class="small text-muted">المدفوع</div>
          <div class="fw-bold text-success">{{ number_format($item['paid'], 2) }} {{ $item['currency'] }}</div>
        </div>
        <div class="col-4 text-center">
          <div class="small text-muted">المتبقي</div>
          <div class="fw-bold {{ $item['remaining'] > 0 ? 'text-danger' : 'text-success' }}">
            {{ number_format(abs($item['remaining']), 2) }} {{ $item['currency'] }}
          </div>
        </div>
      </div>

      <div class="progress mb-3" style="height:8px;">
        <div class="progress-bar bg-success" style="width:{{ $item['pct'] }}%"></div>
      </div>

      @if($item['payment_type'] === 'installments' && $item['installments']->count() > 0)
        <h6 class="fw-bold text-muted mb-2 small">الدفعات المجدولة</h6>
        <div class="table-responsive mb-3">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>المبلغ</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item['installments'] as $i => $inst)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td class="fw-bold">{{ number_format($inst->amount, 2) }} {{ $item['currency'] }}</td>
                  <td>{{ \Carbon\Carbon::parse($inst->due_date)->format('Y-m-d') }}</td>
                  <td>
                    <span class="badge bg-{{ $inst['inst_class'] }}">{{ $inst['inst_label'] }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif

      @if($item['transactions']->count() > 0)
        <h6 class="fw-bold text-muted mb-2 small">الدفعات المُسجَّلة في الصندوق</h6>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>الصندوق</th>
                <th>مرجع</th>
                <th>ملاحظات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item['transactions'] as $trx)
                <tr>
                  <td class="text-muted small">{{ $trx->id }}</td>
                  <td>{{ $trx->trx_date->format('Y-m-d') }}</td>
                  <td class="fw-bold text-success">
                    {{ number_format($trx->amount, 2) }} {{ $trx->currency }}
                  </td>
                  <td class="small">{{ optional($trx->cashbox)->name ?? '-' }}</td>
                  <td class="small text-muted">{{ $trx->reference ?? '-' }}</td>
                  <td class="small text-muted">{{ $trx->notes ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center text-muted small py-2">
          <i class="bi bi-inbox"></i> لا توجد دفعات مسجّلة لهذه الدبلومة
        </div>
      @endif

    </div>
  </div>
@empty
  <div class="alert alert-info">لا توجد خطط دفع مسجّلة لهذا الطالب.</div>
@endforelse

@endsection