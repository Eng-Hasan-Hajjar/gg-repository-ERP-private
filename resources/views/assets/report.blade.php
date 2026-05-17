@extends('layouts.app')
@php($activeModule = 'assets')
@section('title', 'التقرير المالي للأصول')

@section('content')

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-0">التقرير المالي للأصول</h4>
      <div class="text-muted small">إجمالي قيم الأصول حسب العملة والفرع</div>
    </div>
    <a href="{{ route('assets.report.export', request()->query()) }}" class="btn btn-success fw-bold rounded-pill px-4">
      <i class="bi bi-file-earmark-excel"></i> تصدير Excel
    </a>
  </div>

  {{-- فلاتر --}}
  <form class="card border-0 shadow-sm mb-3" method="GET">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-6 col-md-3">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3">
          <select name="currency" class="form-select">
            <option value="">كل العملات</option>
            @foreach(['USD', 'EUR', 'TRY', 'GBP', 'SAR', 'AED'] as $c)
              <option value="{{ $c }}" @selected(request('currency') == $c)>{{ $c }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3">
          {{-- ✅ condition وليس status --}}
          <select name="condition" class="form-select">
            <option value="">كل الحالات</option>
            <option value="good" @selected(request('condition') == 'good')>جيد</option>
            <option value="maintenance" @selected(request('condition') == 'maintenance')>صيانة</option>
            <option value="retired" @selected(request('condition') == 'retired')>خارج الخدمة</option>
          </select>
        </div>
        <div class="col-6 col-md-2 d-grid">
          <button class="btn btn-namaa fw-bold">تصفية</button>
        </div>
        @if(request()->hasAny(['branch_id', 'currency', 'condition']))
          <div class="col-md-1 d-grid">
            <a href="{{ route('assets.report') }}" class="btn btn-outline-secondary">مسح</a>
          </div>
        @endif
      </div>
    </div>
  </form>

  {{-- ══ إجمالي حسب العملة ══ --}}
  <div class="row g-3 mb-4">
    @forelse($totalByCurrency as $t)
      <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3 px-2"
          style="border-top:4px solid #10b981 !important; border-radius:14px;">
          <div style="font-size:1.5rem; font-weight:900; color:#10b981;">
            {{ number_format($t->total, 2) }}
          </div>
          <div style="font-size:1rem; font-weight:900; color:#334155;">
            {{ $t->currency }}
          </div>
          <div style="font-size:.75rem; color:#94a3b8;">
            {{ $t->count }} أصل
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-3">لا توجد بيانات مالية</div>
    @endforelse
  </div>

  {{-- ══ إجمالي حسب الفرع والعملة ══ --}}
  @if($byBranchAndCurrency->count())
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white fw-bold border-0 pt-3">
        <i class="bi bi-building text-primary"></i> الإجمالي حسب الفرع والعملة
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>الفرع</th>
              <th>العملة</th>
              <th>عدد الأصول</th>
              <th>الإجمالي</th>
            </tr>
          </thead>
          <tbody>
            @foreach($byBranchAndCurrency as $branchId => $rows)
              @foreach($rows as $row)
                <tr>
                  @if($loop->first)
                    <td rowspan="{{ $rows->count() }}" class="fw-bold align-middle">
                      {{ $row->branch->name ?? 'غير محدد' }}
                    </td>
                  @endif
                  <td><span class="badge bg-primary">{{ $row->currency }}</span></td>
                  <td>{{ $row->count }}</td>
                  <td class="fw-bold text-success">
                    {{ number_format($row->total, 2) }} {{ $row->currency }}
                  </td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- ══ قائمة الأصول التفصيلية ══ --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold border-0 pt-3 d-flex justify-content-between">
      <span><i class="bi bi-box-seam text-warning"></i> تفاصيل الأصول</span>
      <span class="badge bg-secondary">{{ $assets->count() }} أصل</span>
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>الأصل</th>
            <th>الفرع</th>
            <th>التصنيف</th>
            <th>الحالة</th>
            <th>الكمية</th>
            <th>سعر الشراء</th>
            <th>تاريخ الشراء</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assets as $i => $a)
            <tr>
              <td class="text-muted small">{{ $i + 1 }}</td>
              <td>
                <div class="fw-bold">{{ $a->name }}</div>
                @if($a->asset_tag)
                  <code class="small">{{ $a->asset_tag }}</code>
                @endif
              </td>
              <td class="small">{{ $a->branch->name ?? '-' }}</td>
              <td class="small">{{ $a->category->name ?? '-' }}</td>
              <td>
                {{-- ✅ condition_badge_class و condition_label من الـ Model --}}
                <span class="badge {{ $a->condition_badge_class }}">
                  {{ $a->condition_label }}
                </span>
              </td>
              <td class="text-center">{{ $a->quantity ?? 1 }}</td>
              <td class="fw-bold text-success">
                {{ number_format($a->purchase_cost, 2) }} {{-- ✅ purchase_cost --}}
                <span class="text-muted small">{{ $a->currency ?? 'USD' }}</span>
              </td>
              <td class="small text-muted">
                {{ $a->purchase_date?->format('Y-m-d') ?? '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                لا توجد أصول بهذه المعايير
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection