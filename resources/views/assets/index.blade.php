@extends('layouts.app')
@php($activeModule = 'assets')
@section('title', 'اللوجستيات وإدارة الأصول')

@section('content')

  {{-- ══ Header ══ --}}
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
    <div>
      <h4 class="mb-1 fw-bold">
        <i class="bi bi-box-seam text-warning me-2"></i>اللوجستيات وإدارة الأصول
      </h4>
      <div class="text-muted small">إدارة الأجهزة والمعدات — تتبع الحالة والموقع والتكلفة</div>
    </div>

    <div class="d-flex gap-2 flex-wrap">

      <a href="{{ route('asset-categories.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-3">
        <i class="bi bi-tags"></i>
        <span class="hide-mobile">تصنيفات</span>
      </a>

      @if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasRole('super_admin'))
        <a href="{{ route('assets.report') }}" class="btn btn-outline-primary rounded-pill fw-bold px-3">
          <i class="bi bi-bar-chart-line"></i>
          <span class="hide-mobile">التقرير المالي</span>
        </a>
      @endif

      {{-- ✅ استخدم $pendingCount مباشرة من الـ Controller --}}
     @if(auth()->user()?->hasPermission('manage_assets') || auth()->user()?->hasRole('super_admin'))
        <a href="{{ route('asset-requests.index') }}"
          class="btn btn-outline-warning rounded-pill fw-bold px-3 position-relative">
          <i class="bi bi-inbox"></i>
          <span class="hide-mobile">الطلبات</span>
          @if($pendingCount > 0)
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
              style="font-size:10px;">{{ $pendingCount }}</span>
          @endif
        </a>
      @endif

      @if(auth()->user()?->hasPermission('submit_asset_request'))
        <a href="{{ route('asset-requests.create') }}" class="btn btn-warning rounded-pill fw-bold px-3">
          <i class="bi bi-send-plus"></i>
          <span class="hide-mobile">تقديم طلب</span>
        </a>
      @endif

      <a href="{{ route('assets.export.excel') . '?' . http_build_query(request()->all()) }}"
        class="btn btn-success rounded-pill fw-bold px-3">
        <i class="bi bi-file-earmark-excel"></i>
        <span class="hide-mobile">Excel</span>
      </a>

      @if(auth()->user()?->hasPermission('create_assets'))
        <a href="{{ route('assets.create') }}" class="btn btn-primary rounded-pill fw-bold px-4">
          <i class="bi bi-plus-lg"></i> أصل جديد
        </a>
      @endif

    </div>
  </div>

  {{-- ══ إحصائيات سريعة ══ --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #3b82f6 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">إجمالي القطع</div>
              <div style="font-size:1.8rem; font-weight:900; color:#3b82f6; line-height:1;">
                {{ $assets->sum('quantity') }}
              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(59,130,246,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-layers-fill" style="font-size:1.2rem;color:#3b82f6;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #10b981 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">أنواع الأصول</div>
              <div style="font-size:1.8rem; font-weight:900; color:#10b981; line-height:1;">
                {{ $assets->count() }}
              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(16,185,129,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-box-seam-fill" style="font-size:1.2rem;color:#10b981;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #f59e0b !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">في الصيانة</div>
              <div style="font-size:1.8rem; font-weight:900; color:#f59e0b; line-height:1;">
                {{ $assets->where('condition', 'maintenance')->count() }}
              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(245,158,11,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-wrench-adjustable" style="font-size:1.2rem;color:#f59e0b;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100" style="border-right:4px solid #ef4444 !important; border-radius:14px;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-muted small fw-bold mb-1">خارج الخدمة</div>
              <div style="font-size:1.8rem; font-weight:900; color:#ef4444; line-height:1;">
                {{ $assets->where('condition', 'out_of_service')->count() }}
              </div>
            </div>
            <div style="width:42px;height:42px;background:rgba(239,68,68,.1);border-radius:12px;"
              class="d-flex align-items-center justify-content-center">
              <i class="bi bi-x-circle-fill" style="font-size:1.2rem;color:#ef4444;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══ فلاتر ══ --}}
  <form class="card border-0 shadow-sm mb-3" method="GET" action="{{ route('assets.index') }}">
    <div class="card-body py-2">
      <div class="row g-2 align-items-center">

        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input name="search" value="{{ request('search') }}" class="form-control border-start-0"
              placeholder="بحث: الاسم / AST / سيريال">
          </div>
        </div>

        <div class="col-6 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">كل الفروع</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="asset_category_id" class="form-select">
            <option value="">كل التصنيفات</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(request('asset_category_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="condition" class="form-select">
            <option value="">كل الحالات</option>
            <option value="good" @selected(request('condition') == 'good')>✅ جيد</option>
            <option value="maintenance" @selected(request('condition') == 'maintenance')>🔧 صيانة</option>
            <option value="out_of_service" @selected(request('condition') == 'out_of_service')>❌ خارج الخدمة</option>
          </select>
        </div>

        <div class="col-6 col-md-auto d-flex gap-1">
          <button class="btn btn-namaa fw-bold px-3">
            <i class="bi bi-funnel"></i>
            <span class="hide-mobile">تصفية</span>
          </button>
          @if(request()->hasAny(['search', 'branch_id', 'asset_category_id', 'condition']))
            <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary px-3" title="مسح الفلاتر">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>

      </div>
    </div>
  </form>

  {{-- ══ الجدول ══ --}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile" style="width:50px;">#</th>
            <th class="hide-mobile" style="width:130px;">التاغ</th>
            <th>الأصل</th>
            <th class="hide-mobile">التصنيف</th>
            <th class="hide-mobile">الفرع</th>
            <th style="width:90px;">الحالة</th>
            <th class="hide-mobile" style="width:70px;">العدد</th>
            <th class="hide-mobile">الموقع</th>
            <th class="hide-mobile">السعر</th>
            <th class="text-end" style="width:130px;">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assets as $a)
            <tr
              class="{{ $a->condition === 'out_of_service' ? 'table-danger' : ($a->condition === 'maintenance' ? 'table-warning' : '') }}">

              <td class="hide-mobile text-muted small">{{ $a->id }}</td>

              <td class="hide-mobile">
                <code style="font-size:.78rem; color:#6366f1;">{{ $a->asset_tag }}</code>
              </td>

              <td>
                <div class="fw-bold">{{ $a->name }}</div>
                <div class="d-block d-md-none text-muted small mt-1">
                  {{ $a->branch->name ?? '' }}
                  @if($a->purchase_cost)
                    · <span class="text-success fw-bold">
                      {{ number_format($a->purchase_cost, 0) }} {{ $a->currency ?? 'USD' }}
                    </span>
                  @endif
                </div>
              </td>

              <td class="hide-mobile small text-muted">{{ $a->category->name ?? '-' }}</td>

              <td class="hide-mobile small">{{ $a->branch->name ?? '-' }}</td>

              <td>
                <span class="badge {{ $a->condition_badge_class }}" style="font-size:.72rem;">
                  {{ $a->condition_label }}
                </span>
              </td>

              <td class="hide-mobile text-center">
                <span class="badge bg-light text-dark border" style="font-size:.8rem; font-weight:900;">
                  {{ $a->quantity ?? 1 }}
                </span>
              </td>

              <td class="hide-mobile small text-muted">{{ $a->location ?? '-' }}</td>

              <td class="hide-mobile">
                @if($a->purchase_cost)
                  <span class="fw-bold text-success" style="font-size:.85rem;">
                    {{ number_format($a->purchase_cost, 2) }}
                  </span>
                  <span class="text-muted small"> {{ $a->currency ?? 'USD' }}</span>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end">

                  @if(auth()->user()?->hasPermission('view_assets'))
                    <a href="{{ route('assets.show', $a) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                      <i class="bi bi-eye"></i>
                    </a>
                  @endif

                  @if(auth()->user()?->hasPermission('edit_assets'))
                    <a href="{{ route('assets.edit', $a) }}" class="btn btn-sm btn-outline-dark hide-mobile" title="تعديل">
                      <i class="bi bi-pencil"></i>
                    </a>
                  @endif

                  @if(auth()->user()?->hasPermission('delete_assets'))
                    <form method="POST" action="{{ route('assets.destroy', $a) }}" class="d-inline hide-mobile"
                      onsubmit="return confirm('تأكيد حذف الأصل؟')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" title="حذف">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                لا يوجد أصول مطابقة للفلتر الحالي
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($assets->count() > 0)
      <div class="card-footer bg-white border-0 py-2 px-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          عرض {{ $assets->firstItem() ?? 0 }}–{{ $assets->lastItem() ?? 0 }}
          من أصل {{ $assets->total() }} سجل
        </div>
        <div class="text-muted small hide-mobile">
          إجمالي القطع: <strong>{{ $assets->sum('quantity') }}</strong>
        </div>
      </div>
    @endif
  </div>

  <div class="mt-3">
    {{ $assets->links() }}
  </div>

@endsection