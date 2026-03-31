@extends('layouts.app')
@section('title', 'CRM - تفاصيل العميل المحتمل')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-0">{{ $lead->full_name }}</h4>
      <div class="text-muted small">
        المرحلة: <b>{{ $stage_ar }}</b>
        —
        حالة التسجيل: <b>{{ $registration_ar }}</b>
      </div>
    </div>


      <div class="d-flex gap-2">

      @if($lead->registration_status === 'pending')

        <div class="alert alert-info mt-3">

          سيتم تحويل العميل إلى طالب تلقائياً بعد ترحيل الدفعة من قسم المالية.

        </div>

      @endif

      </div>
    <div class="d-flex gap-2">
  




      @if(auth()->user()?->hasPermission('convert_leads'))
        @if($lead->registration_status === 'pending')
          <form method="POST" action="{{ route('leads.convert', $lead) }}" hidden>
            @csrf
            <button class="btn btn-success fw-bold">تحويل إلى طالب</button>
          </form>
        @endif
      @endif


          @if(auth()->user()?->hasPermission('edit_leads'))
        <a class="btn btn-outline-dark" href="{{ route('leads.edit', $lead) }}">تعديل</a>
      @endif


    </div>
  </div>

  <div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
      <h6 class="fw-bold">الدبلومات</h6>
      @foreach($lead->diplomas as $d)
        <span class="badge bg-light text-dark border">{{ $d->name }}</span>
      @endforeach
      <hr>
      <div class="row g-2">
        <div class="col-md-4"><b>الهاتف:</b> {{ $lead->phone ?? '-' }}</div>
        <div class="col-md-4" hidden><b>واتساب:</b> {{ $lead->whatsapp ?? '-' }}</div>
        <div class="col-md-4"><b>الفرع:</b> {{ $lead->branch->name ?? '-' }}</div>
        <div class="col-md-4"><b>السكن:</b> {{ $lead->residence ?? '-' }}</div>
        <div class="col-md-4"><b>العمر:</b> {{ $lead->age ?? '-' }}</div>
        <div class="col-md-4"><b>المصدر:</b> {{ $source_ar }}</div>
        <div class="col-12" hidden><b>الاحتياج:</b> {{ $lead->need ?? '-' }}</div>
        <div class="col-12"><b>ملاحظات:</b> {{ $lead->notes ?? '-' }}</div>
      </div>

      <div class="col-md-4">
        <b>مسؤول التواصل:</b>
        {{ $lead->creator->name ?? $lead->creator->email ?? '-' }}
      </div>
      <div class="col-md-4"><b>العمل:</b> {{ $lead->job ?? '-' }}</div>
      <div class="col-md-4"><b>البلد:</b> {{ $lead->country ?? '-' }}</div>
      <div class="col-md-4"><b>المحافظة:</b> {{ $lead->province ?? '-' }}</div>
      <div class="col-md-4"><b>الدراسة:</b> {{ $lead->study ?? '-' }}</div>

    </div>
  </div>







  @if($lead->registration_status === 'pending')

    <hr>
    <h5 class="fw-bold">إضافة دفعة مالية</h5>

    <form method="POST" action="{{ route('financial.pay') }}">
      @csrf

      <input type="hidden" name="financial_account_id" value="{{ $lead->financialAccount?->id }}">

      <div class="row g-3">




        <div class="col-md-4">
          <label>الدبلومة</label>
          <select name="diploma_id" class="form-select" required>
            @foreach($lead->diplomas as $diploma)
              <option value="{{ $diploma->id }}">
                {{ $diploma->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label>الصندوق</label>
          <select name="cashbox_id" class="form-select" required>
            @foreach(\App\Models\Cashbox::where('status','active')
              ->where('branch_id',$lead->branch_id)
              ->get() as $box)
              <option value="{{ $box->id }}" {{ $box->branch_id == $lead->branch_id ? 'selected' : '' }}>
                {{ $box->name }} - {{ $box->currency }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label>المبلغ</label>
          <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label>ملاحظات</label>
          <input type="text" name="notes" class="form-control">
        </div>

        <div class="col-12">
          <button class="btn btn-success">تسجيل دفعة</button>
        </div>

      </div>
    </form>

  @endif







  {{-- Followups --}}
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h6 class="fw-bold mb-3">المتابعات</h6>

      <form class="row g-2 mb-3" method="POST" action="{{ route('leads.followups.store', $lead) }}">
        @csrf
        <div class="col-md-3">
          <input type="date" name="followup_date" class="form-control" value="{{ old('followup_date') }}">
        </div>
        <div class="col-md-3">
          <input name="result" class="form-control" placeholder="نتيجة المتابعة" value="{{ old('result') }}">
        </div>
        <div class="col-md-6">
          <input name="notes" class="form-control" placeholder="ملاحظات" value="{{ old('notes') }}">
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary fw-bold">إضافة متابعة</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>تاريخ</th>
              <th>نتيجة</th>
              <th>ملاحظات</th>
            </tr>
          </thead>
          <tbody>
            @forelse($lead->followups as $f)
              <tr>
                <td>{{ $f->followup_date?->format('Y-m-d') ?? '-' }}</td>
                <td>{{ $f->result ?? '-' }}</td>
                <td>{{ $f->notes ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-muted text-center py-3">لا يوجد متابعات</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection