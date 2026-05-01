@extends('layouts.app')
@php($activeModule = 'finance')
@section('title', 'كشف الحسابات الشامل')

@section('content')

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold"><i class="bi bi-receipt-cutoff"></i> كشف الحسابات الشامل</h4>
    <div class="text-muted fw-semibold small">جميع الحركات المالية في جميع الصناديق</div>
  </div>
  <a href="{{ route('accounts.statement.excel') }}?{{ http_build_query(request()->all()) }}"
     class="btn btn-success rounded-pill px-4 fw-bold">
    <i class="bi bi-file-earmark-excel"></i> تصدير Excel
  </a>
</div>

{{-- بطاقات الإجماليات --}}
<div class="row g-3 mb-3">
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #1b5e20 !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-arrow-down-circle-fill text-success fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">إجمالي المقبوض (posted)</div>
          <div class="fw-bold fs-5 text-success">{{ number_format($summaryIn, 2) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #b71c1c !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-arrow-up-circle-fill text-danger fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">إجمالي المدفوع (posted)</div>
          <div class="fw-bold fs-5 text-danger">{{ number_format($summaryOut, 2) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #b45309 !important;">
      <div class="card-body d-flex align-items-center gap-3">
        <div style="width:46px;height:46px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-calculator-fill fs-4" style="color:#b45309;"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">الصافي</div>
          <div class="fw-bold fs-5 {{ $summaryNet >= 0 ? 'text-success' : 'text-danger' }}">
            {{ number_format($summaryNet, 2) }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- فلترة --}}
<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">

      <div class="col-12 col-md-3">
        <input name="search" value="{{ request('search') }}" class="form-control"
               placeholder="بحث: اسم / مرجع / تصنيف / ملاحظات">
      </div>

      <div class="col-6 col-md-2">
        <select name="cashbox_id" class="form-select">
          <option value="">كل الصناديق</option>
          @foreach($cashboxes as $cb)
            <option value="{{ $cb->id }}" @selected(request('cashbox_id') == $cb->id)>
              {{ $cb->name }} ({{ $cb->currency }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="in"       @selected(request('type')=='in')>مقبوض</option>
          <option value="out"      @selected(request('type')=='out')>مدفوع</option>
          <option value="transfer" @selected(request('type')=='transfer')>مناقلة</option>
          <option value="exchange" @selected(request('type')=='exchange')>تصريف</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="posted" @selected(request('status')=='posted')>مُرحّل</option>
          <option value="draft"  @selected(request('status')=='draft')>معلّق</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="category" class="form-select">
          <option value="">التصنيف (الكل)</option>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(request('category') == $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="currency" class="form-select">
          <option value="">كل العملات</option>
          @foreach(['USD','EUR','TRY','GBP','SAR','AED'] as $cur)
            <option value="{{ $cur }}" @selected(request('currency') == $cur)>{{ $cur }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="form-control" placeholder="من تاريخ">
      </div>

      <div class="col-6 col-md-2">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="form-control" placeholder="إلى تاريخ">
      </div>

      <div class="col-6 col-md-2">
        <input type="number" name="amount_min" value="{{ request('amount_min') }}"
               class="form-control" placeholder="المبلغ من">
      </div>

      <div class="col-6 col-md-2">
        <input type="number" name="amount_max" value="{{ request('amount_max') }}"
               class="form-control" placeholder="المبلغ إلى">
      </div>

      <div class="col-6 col-md-2">
        <select name="sort" class="form-select">
          <option value="trx_date" @selected(request('sort','trx_date')=='trx_date')>التاريخ</option>
          <option value="amount"   @selected(request('sort')=='amount')>المبلغ</option>
          <option value="id"       @selected(request('sort')=='id')>رقم الحركة</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="direction" class="form-select">
          <option value="desc" @selected(request('direction','desc')=='desc')>تنازلي</option>
          <option value="asc"  @selected(request('direction')=='asc')>تصاعدي</option>
        </select>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-namaa fw-bold">تطبيق</button>
      </div>

      @if(request()->hasAny(['search','cashbox_id','type','status','category','currency','date_from','date_to','amount_min','amount_max']))
        <div class="col-12 col-md-2 d-grid">
          <a href="{{ route('accounts.statement.index') }}" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-x-circle"></i> مسح الفلاتر
          </a>
        </div>
      @endif

    </div>
  </div>
</form>

{{-- الجدول --}}
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0" style="font-size:13px;">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>التاريخ</th>
          <th>الصندوق</th>
          <th>الفرع</th>
          <th>النوع</th>
          <th>الشخص</th>
          <th>الدبلومة</th>
          <th>التصنيف</th>
          <th class="text-center">المبلغ</th>
          <th>العملة</th>
          <th>مرجع</th>
          <th class="text-center">الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $idx => $t)
          <tr>
            <td class="text-muted small">{{ $t->id }}</td>
            <td class="small">{{ $t->trx_date->format('Y-m-d') }}</td>
            <td class="small fw-semibold">{{ optional($t->cashbox)->name ?? '-' }}</td>
            <td class="small text-muted">{{ optional(optional($t->cashbox)->branch)->name ?? '-' }}</td>
            <td>
              <span class="badge bg-{{ $typeMeta[$t->type]['color'] ?? 'secondary' }}">
                {{ $typeMeta[$t->type]['label'] ?? $t->type }}
              </span>
            </td>
            <td class="small">{{ optional(optional($t->account)->accountable)->full_name ?? '-' }}</td>
            <td class="small text-muted">{{ optional($t->diploma)->name ?? '-' }}</td>
            <td class="small text-muted">{{ $t->category ?? '-' }}</td>
            <td class="text-center fw-bold {{ in_array($t->type,['in']) ? 'text-success' : 'text-danger' }}">
              {{ number_format($t->amount, 2) }}
              @if($t->foreign_amount && $t->foreign_currency)
                <br><small class="text-muted fw-normal">
                  ({{ number_format($t->foreign_amount,2) }} {{ $t->foreign_currency }})
                </small>
              @endif
            </td>
            <td><span class="badge bg-light text-dark border small">{{ $t->currency }}</span></td>
            <td class="small text-muted">{{ $t->reference ?? '-' }}</td>
            <td class="text-center">
              <span class="badge bg-{{ $t->status === 'posted' ? 'primary' : 'secondary' }}">
                {{ $t->status === 'posted' ? 'مُرحّل' : 'معلّق' }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('cashboxes.transactions.index', $t->cashbox_id) }}"
                 class="btn btn-sm btn-outline-secondary rounded-pill px-2"
                 title="عرض في الصندوق">
                <i class="bi bi-box-arrow-up-left"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="13" class="text-center text-muted py-4">لا توجد حركات بالفلاتر المحددة</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $transactions->links() }}
</div>

@endsection