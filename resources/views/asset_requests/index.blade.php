@extends('layouts.app')
@section('title', 'طلبات اللوجستيات')

@section('content')

<div class="d-flex justify-content-between align-items-center gap-2 mb-3">
  <div>
    <h4 class="fw-bold mb-0">طلبات اللوجستيات</h4>
    <div class="text-muted small">طلبات الشراء والإصلاح</div>
  </div>
  @if(auth()->user()?->hasPermission('submit_asset_request'))
    <a href="{{ route('asset-requests.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-plus-circle"></i> تقديم طلب
    </a>
  @endif
</div>

{{-- فلاتر --}}
<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="pending"  @selected(request('status')=='pending')>قيد المراجعة</option>
          <option value="approved" @selected(request('status')=='approved')>مقبول</option>
          <option value="rejected" @selected(request('status')=='rejected')>مرفوض</option>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="purchase" @selected(request('type')=='purchase')>شراء</option>
          <option value="repair"   @selected(request('type')=='repair')>إصلاح</option>
        </select>
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-namaa fw-bold">تصفية</button>
      </div>
      @if(request()->hasAny(['status','type']))
        <div class="col-md-2 d-grid">
          <a href="{{ route('asset-requests.index') }}" class="btn btn-outline-secondary">مسح</a>
        </div>
      @endif
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>العنوان</th>
          <th>النوع</th>
          <th>مقدم الطلب</th>
          <th>الفرع</th>
          <th>الأصل المرتبط</th>
          <th class="text-center">الحالة</th>
          <th>التاريخ</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($requests as $r)
          <tr>
            <td class="text-muted small">{{ $r->id }}</td>
            <td>
              <div class="fw-bold">{{ $r->title }}</div>
              @if($r->description)
                <div class="text-muted small">{{ Str::limit($r->description, 60) }}</div>
              @endif
            </td>
            <td>
              <span class="badge {{ $r->type === 'purchase' ? 'bg-primary' : 'bg-warning text-dark' }}">
                {{ $r->type_label }}
              </span>
            </td>
            <td class="small">{{ $r->user->name ?? '-' }}</td>
            <td class="small">{{ $r->branch->name ?? '-' }}</td>
            <td class="small text-muted">{{ $r->asset->name ?? '—' }}</td>
            <td class="text-center">
              <span class="badge bg-{{ $r->status_color }}">{{ $r->status_label }}</span>
            </td>
            <td class="small text-muted">{{ $r->created_at->format('Y-m-d') }}</td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end flex-wrap">

                {{-- مدير اللوجستيات يقبل أو يرفض --}}
                @if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending')
                  <form method="POST" action="{{ route('asset-requests.approve', $r) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">
                      <i class="bi bi-check2-circle"></i> قبول
                    </button>
                  </form>

                  {{-- زر الرفض مع ملاحظة --}}
                  <button class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectModal{{ $r->id }}">
                    <i class="bi bi-x-circle"></i> رفض
                  </button>
                @endif

                {{-- حذف للمالك إذا كان pending --}}
                @if($r->user_id === auth()->id() && $r->status === 'pending')
                  <form method="POST" action="{{ route('asset-requests.destroy', $r) }}"
                        onsubmit="return confirm('حذف الطلب؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                @endif

                {{-- ملاحظات الرفض --}}
                @if($r->status === 'rejected' && $r->manager_notes)
                  <button class="btn btn-sm btn-outline-dark"
                    data-bs-toggle="tooltip"
                    title="{{ $r->manager_notes }}">
                    <i class="bi bi-chat-text"></i>
                  </button>
                @endif

              </div>
            </td>
          </tr>

          {{-- Modal الرفض --}}
          @if(auth()->user()?->hasPermission('manage_assets') && $r->status === 'pending')
            <div class="modal fade" id="rejectModal{{ $r->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="POST" action="{{ route('asset-requests.reject', $r) }}">
                    @csrf
                    <div class="modal-header">
                      <h6 class="modal-title fw-bold">رفض الطلب: {{ $r->title }}</h6>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <label class="fw-bold mb-1">سبب الرفض (اختياري)</label>
                      <textarea name="manager_notes" class="form-control" rows="3"
                        placeholder="اكتب سبب الرفض..."></textarea>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                      <button class="btn btn-danger fw-bold">تأكيد الرفض</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endif

        @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">
              <i class="bi bi-inbox fs-2 d-block mb-2"></i>
              لا توجد طلبات
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $requests->links() }}</div>

@endsection