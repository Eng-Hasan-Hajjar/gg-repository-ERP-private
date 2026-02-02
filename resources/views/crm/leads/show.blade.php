@extends('layouts.app')
@section('title','CRM - ملف Lead')

@section('content')

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-start mb-3">
  <div>
    <h4 class="fw-bold mb-1">ملف العميل المحتمل — {{ $lead->full_name }}</h4>
    <div class="text-muted small">
      الفرع: <b>{{ $lead->branch->name ?? '-' }}</b>
      <span class="mx-2">—</span>
      الحالة: <b>{{ $lead->registration_status }}</b>
      <span class="mx-2">—</span>
      المرحلة: <b>{{ $lead->stage }}</b>
    </div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-outline-secondary rounded-pill px-4 fw-bold" href="{{ route('leads.index') }}">
      <i class="bi bi-arrow-return-right"></i> رجوع
    </a>

    <a class="btn btn-outline-dark rounded-pill px-4 fw-bold" href="{{ route('leads.edit',$lead) }}">
      <i class="bi bi-pencil"></i> تعديل
    </a>

    @if($lead->registration_status === 'pending')
      <form method="POST" action="{{ route('leads.convert',$lead) }}">
        @csrf
        <button class="btn btn-success rounded-pill px-4 fw-bold">
          <i class="bi bi-arrow-repeat"></i> تحويل إلى طالب رسمي
        </button>
      </form>
    @elseif($lead->student_id)
      <a class="btn btn-outline-success rounded-pill px-4 fw-bold" href="{{ route('students.show',$lead->student_id) }}">
        <i class="bi bi-person-check"></i> فتح ملف الطالب
      </a>
    @endif
  </div>
</div>

<div class="row g-3">

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">بيانات العميل المحتمل</h6>

        <div class="mb-2"><b>الهاتف:</b> {{ $lead->phone ?? '-' }}</div>

        <div class="mb-2"><b>واتساب:</b>
          @if($lead->whatsapp)
            <a target="_blank" class="text-decoration-none fw-bold"
               href="{{ str_starts_with($lead->whatsapp,'http') ? $lead->whatsapp : 'https://wa.me/'.preg_replace('/\D+/','',$lead->whatsapp) }}">
              <i class="bi bi-whatsapp"></i> فتح واتساب
            </a>
          @else
            -
          @endif
        </div>

        <div class="mb-2"><b>أول تواصل:</b> {{ $lead->first_contact_date?->format('Y-m-d') ?? '-' }}</div>
        <div class="mb-2"><b>مكان السكن:</b> {{ $lead->residence ?? '-' }}</div>
        <div class="mb-2"><b>العمر:</b> {{ $lead->age ?? '-' }}</div>
        <div class="mb-2"><b>الجهة/المؤسسة:</b> {{ $lead->organization ?? '-' }}</div>
        <div class="mb-2"><b>المصدر:</b> {{ $lead->source }}</div>

        <div class="mt-3">
          <b>الدبلومات المطلوبة:</b>
          <div class="mt-2 d-flex gap-1 flex-wrap">
            @foreach($lead->diplomas as $d)
              <span class="badge bg-light text-dark border">
                {{ $d->name }}
                @if($d->pivot->is_primary) <span class="text-success">★</span> @endif
              </span>
            @endforeach
          </div>
        </div>

        <hr>

        <div class="mb-2"><b>الاحتياج:</b> {{ $lead->need ?? '-' }}</div>
        <div class="mb-0"><b>ملاحظات:</b> {{ $lead->notes ?? '-' }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">نظام المتابعة</h6>

        <form class="row g-2 mb-3" method="POST" action="{{ route('leads.followups.store',$lead) }}">
          @csrf
          <div class="col-12 col-md-4">
            <input type="date" name="followup_date" class="form-control" value="{{ old('followup_date') }}">
          </div>
          <div class="col-12 col-md-4">
            <input name="result" class="form-control" placeholder="نتيجة المتابعة" required value="{{ old('result') }}">
          </div>
          <div class="col-12 col-md-4 d-grid">
            <button class="btn btn-primary fw-bold">إضافة متابعة</button>
          </div>
          <div class="col-12">
            <textarea name="notes" class="form-control" rows="2" placeholder="ملاحظات إضافية">{{ old('notes') }}</textarea>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>التاريخ</th>
                <th>النتيجة</th>
                <th>ملاحظات</th>
                <th class="text-end">حذف</th>
              </tr>
            </thead>
            <tbody>
              @forelse($lead->followups()->latest()->get() as $f)
                <tr>
                  <td>{{ $f->followup_date?->format('Y-m-d') ?? '-' }}</td>
                  <td class="fw-semibold">{{ $f->result }}</td>
                  <td>{{ $f->notes ?? '-' }}</td>
                  <td class="text-end">
                    <form method="POST" action="{{ route('leads.followups.destroy', [$lead, $f]) }}">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-muted py-3">لا يوجد متابعات بعد</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
