@extends('layouts.app')
@php($activeModule='finance')

@section('title','الصناديق المالية')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold">الصناديق والحسابات المالية</h4>
    <div class="text-muted fw-semibold">إدارة الصناديق حسب الفرع والعملة + سجل الحركات</div>
  </div>

  <a href="{{ route('cashboxes.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> إضافة صندوق
  </a>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/كود">
      </div>

      <div class="col-6 col-md-3">
        <select name="branch_id" class="form-select">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="currency" class="form-select">
          <option value="">كل العملات</option>
          @foreach(['USD','TRY','EUR'] as $c)
            <option value="{{ $c }}" @selected(request('currency')==$c)>{{ $c }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          <option value="active" @selected(request('status')=='active')>نشط</option>
          <option value="inactive" @selected(request('status')=='inactive')>غير نشط</option>
        </select>
      </div>

      <div class="col-6 col-md-1 d-grid">
        <button class="btn btn-namaa fw-bold">تطبيق</button>
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
          <th class="hide-mobile">الكود</th>
          <th>الاسم</th>
          <th>الفرع</th>
          <th>العملة</th>
          <th>الحالة</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($cashboxes as $c)
          <tr>
            <td class="hide-mobile">{{ $c->id }}</td>
            <td class="hide-mobile"><code>{{ $c->code }}</code></td>
            <td class="fw-bold">{{ $c->name }}</td>
            <td>{{ $c->branch->name ?? '-' }}</td>
            <td><span class="badge bg-light text-dark border">{{ $c->currency }}</span></td>
            <td>
              <span class="badge bg-{{ $c->status=='active'?'success':'secondary' }}">
                {{ $c->status=='active'?'نشط':'غير نشط' }}
              </span>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('cashboxes.show',$c) }}">
                <i class="bi bi-eye"></i> عرض
              </a>
              <a class="btn btn-sm btn-outline-success" href="{{ route('cashboxes.transactions.index',$c) }}">
                <i class="bi bi-arrow-left-right"></i> الحركات
              </a>
              <a class="btn btn-sm btn-outline-dark" href="{{ route('cashboxes.edit',$c) }}">
                <i class="bi bi-pencil"></i> تعديل
              </a>


                @if($c->attachment_path)
                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ asset('storage/'.$c->attachment_path) }}">
                        <i class="bi bi-paperclip"></i> مرفق
                    </a>
                @endif




            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد صناديق</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $cashboxes->links() }}
</div>
@endsection
