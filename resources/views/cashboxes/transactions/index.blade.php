@extends('layouts.app')
@php($activeModule='finance')
@section('title','حركات الصندوق')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">حركات الصندوق</h4>
    <div class="text-muted fw-semibold">
      {{ $cashbox->name }} — <code>{{ $cashbox->code }}</code>
      — الرصيد الحالي: <b>{{ $cashbox->current_balance }} {{ $cashbox->currency }}</b>
    </div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('cashboxes.show',$cashbox) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">رجوع للصندوق</a>
    <a href="{{ route('cashboxes.transactions.create',$cashbox) }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-plus-circle"></i> إضافة حركة
    </a>
  </div>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-5">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: تصنيف/مرجع/ملاحظات">
      </div>

      <div class="col-6 col-md-2">
        <select name="type" class="form-select">
          <option value="">النوع (الكل)</option>
          <option value="in"  @selected(request('type')=='in')>مقبوض</option>
          <option value="out" @selected(request('type')=='out')>مدفوع</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="draft"  @selected(request('status')=='draft')>معلّق</option>
          <option value="posted" @selected(request('status')=='posted')>مُرحّل</option>
        </select>
      </div>

      <div class="col-12 col-md-3 d-grid">
        <button class="btn btn-dark fw-bold">تطبيق</button>
      </div>
    </div>

    <div class="mt-3 small text-muted fw-semibold">
      إجمالي المقبوض (posted): <b>{{ number_format($postedIn,2) }}</b>
      — إجمالي المدفوع (posted): <b>{{ number_format($postedOut,2) }}</b>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>التاريخ</th>
          <th>النوع</th>
          <th>المبلغ</th>
          <th>تصنيف</th>
          <th>مرجع</th>
          <th>حالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $t)
          <tr>
            <td>{{ $t->id }}</td>
            <td>{{ $t->trx_date->format('Y-m-d') }}</td>
            <td>
              <span class="badge bg-{{ $t->type=='in'?'success':'danger' }}">
                {{ $t->type=='in'?'مقبوض':'مدفوع' }}
              </span>
            </td>
            <td class="fw-bold">{{ $t->amount }} {{ $t->currency }}</td>
            <td>{{ $t->category ?? '-' }}</td>
            <td>{{ $t->reference ?? '-' }}</td>
            <td>
              <span class="badge bg-{{ $t->status=='posted'?'primary':'secondary' }}">
                {{ $t->status=='posted'?'مُرحّل':'معلّق' }}
              </span>
            </td>
            <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
              @if($t->status!='posted')
                <form method="POST" action="{{ route('cashboxes.transactions.post',[$cashbox,$t]) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-success">
                    <i class="bi bi-check2-circle"></i> ترحيل
                  </button>
                </form>
              @endif

              <a class="btn btn-sm btn-outline-dark" href="{{ route('cashboxes.transactions.edit',[$cashbox,$t]) }}">
                <i class="bi bi-pencil"></i> تعديل
              </a>

              <form method="POST" action="{{ route('cashboxes.transactions.destroy',[$cashbox,$t]) }}"
                    onsubmit="return confirm('حذف الحركة؟');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> حذف
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">لا يوجد حركات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $transactions->links() }}
</div>
@endsection
