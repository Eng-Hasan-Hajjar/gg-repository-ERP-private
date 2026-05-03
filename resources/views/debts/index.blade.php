@extends('layouts.app')
@php($activeModule = 'finance')
@section('title', 'الذمم المالية للطلاب')

@section('content')

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="bi bi-wallet2"></i> الذمم المالية للطلاب</h4>
    <div class="text-muted fw-semibold small">إجمالي المستحق — المدفوع — المتبقي لجميع الطلاب</div>
  </div>
  <a href="{{ route('debts.excel') }}?{{ http_build_query(request()->all()) }}"
     class="btn btn-success rounded-pill px-4 fw-bold">
    <i class="bi bi-file-earmark-excel"></i> تصدير Excel
  </a>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #11998e !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي المستحق</div>
        <div class="fw-bold fs-4">{{ number_format($totalAmount, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #1976d2 !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي المدفوع</div>
        <div class="fw-bold fs-4 text-primary">{{ number_format($totalPaid, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #c62828 !important;">
      <div class="card-body">
        <div class="text-muted small fw-semibold mb-1">إجمالي الذمم المتبقية</div>
        <div class="fw-bold fs-4 text-danger">{{ number_format($totalDebt, 2) }}</div>
      </div>
    </div>
  </div>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="{{ request('search') }}" class="form-control"
               placeholder="بحث: اسم / هاتف / رقم جامعي">
      </div>
      <div class="col-6 col-md-3">
        <select name="debt_status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="has_debt" @selected(request('debt_status')=='has_debt')>عليه ذمة</option>
          <option value="paid"     @selected(request('debt_status')=='paid')>مسدّد</option>
          <option value="overpaid" @selected(request('debt_status')=='overpaid')>زيادة دفع</option>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <select name="sort" class="form-select">
          <option value="remaining" @selected(request('sort','remaining')=='remaining')>الأعلى ذمةً</option>
          <option value="name"      @selected(request('sort')=='name')>الاسم</option>
          <option value="total"     @selected(request('sort')=='total')>الإجمالي</option>
          <option value="paid"      @selected(request('sort')=='paid')>المدفوع</option>
        </select>
      </div>
      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-namaa fw-bold">تطبيق</button>
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
          <th class="hide-mobile">الرقم الجامعي</th>
          <th>اسم الطالب</th>
          <th class="hide-mobile">الهاتف</th>
          <th class="text-center hide-mobile">الدبلومات</th>
          <th class="text-center hide-mobile">إجمالي المستحق</th>
          <th class="text-center hide-mobile">المدفوع</th>
          <th class="text-center">المتبقي</th>
          <th class="text-center hide-mobile">الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($paginated as $idx => $d)
          <tr>
            <td class="text-muted small hide-mobile">{{ $paginated->firstItem() + $idx }}</td>
            <td><code class="small hide-mobile">{{ $d['university_id'] }}</code></td>
            <td class="fw-semibold">{{ $d['name'] }}</td>
            <td class="small text-muted hide-mobile">{{ $d['phone'] }}</td>
            <td class="text-center hide-mobile">
              <span class="badge bg-secondary rounded-pill">{{ $d['diplomas_count'] }}</span>
            </td>
            <td class="text-center fw-bold hide-mobile hide-mobile">{{ number_format($d['total'], 2) }}</td>
            <td class="text-center fw-bold text-primary hide-mobile">{{ number_format($d['paid'], 2) }}</td>
            <td class="text-center fw-bold text-{{ $d['rem_class'] }}">
              {{ number_format(abs($d['remaining']), 2) }}
              @if($d['remaining'] < 0)
                <small class="d-block fw-normal text-muted">(زيادة)</small>
              @endif
            </td>
            <td class="text-center hide-mobile">
              <span class="badge bg-{{ $d['status_class'] }} rounded-pill px-3">
                {{ $d['status_label'] }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('debts.show', $d['student_id']) }}"
                 class="btn btn-sm btn-outline-info rounded-pill px-3">
                <i class="bi bi-eye"></i> تفاصيل
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $paginated->links() }}
</div>

@endsection