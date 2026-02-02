@extends('layouts.app')
@php($activeModule = 'crm')

@section('title','CRM - العملاء المحتملين')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">قسم الاستشارات والمبيعات (CRM)</h4>
    <div class="text-muted small">Leads — متابعة — تحويل إلى طالب رسمي</div>
  </div>

  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('leads.create') }}">
    <i class="bi bi-person-plus"></i> إضافة Lead
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('leads.index') }}">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: الاسم / الهاتف / واتساب">
    </div>

    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="stage" class="form-select">
        <option value="">كل المراحل</option>
        @foreach(['new'=>'جديد','follow_up'=>'قيد المتابعة','interested'=>'مهتم','registered'=>'تم التسجيل','rejected'=>'مرفوض','postponed'=>'مؤجل'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('stage')==$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="source" class="form-select">
        <option value="">كل المصادر</option>
        @foreach(['ad'=>'إعلان','referral'=>'توصية','social'=>'سوشيال ميديا','website'=>'موقع','expo'=>'معرض','other'=>'أخرى'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('source')==$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="registration_status" class="form-select">
        <option value="">(افتراضياً: pending)</option>
        @foreach(['pending'=>'قيد الانتظار','converted'=>'تم التحويل','lost'=>'خسارة'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('registration_status')==$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-12 d-grid d-md-flex gap-2 mt-2">
      <button class="btn btn-dark fw-bold">تطبيق</button>
      <a class="btn btn-outline-secondary fw-bold" href="{{ route('leads.index') }}">تفريغ الفلاتر</a>
    </div>
  </div>
</form>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الاسم</th>
          <th>الهاتف</th>
          <th>الفرع</th>
          <th>الدبلومات</th>
          <th>المرحلة</th>
          <th>المصدر</th>
          <th>الحالة</th>
          <th>متابعات</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leads as $l)
          <tr>
            <td>{{ $l->id }}</td>
            <td class="fw-semibold">
              <a class="text-decoration-none" href="{{ route('leads.show',$l) }}">{{ $l->full_name }}</a>
            </td>
            <td>
              {{ $l->phone ?? '-' }}
              @if($l->whatsapp)
                <div class="small">
                  <a target="_blank" class="text-decoration-none fw-bold"
                     href="{{ str_starts_with($l->whatsapp,'http') ? $l->whatsapp : 'https://wa.me/'.preg_replace('/\D+/','',$l->whatsapp) }}">
                    <i class="bi bi-whatsapp"></i> واتساب
                  </a>
                </div>
              @endif
            </td>
            <td>{{ $l->branch->name ?? '-' }}</td>
            <td>
              @foreach($l->diplomas as $d)
                <span class="badge bg-light text-dark border">
                  {{ $d->name }}
                  @if($d->pivot->is_primary) <span class="text-success">★</span> @endif
                </span>
              @endforeach
            </td>
            <td><span class="badge bg-secondary">{{ $l->stage }}</span></td>
            <td><span class="badge bg-info">{{ $l->source }}</span></td>
            <td>
              @php($map=['pending'=>'warning','converted'=>'success','lost'=>'danger'])
              <span class="badge bg-{{ $map[$l->registration_status] ?? 'secondary' }}">{{ $l->registration_status }}</span>
            </td>
            <td><span class="badge bg-dark">{{ $l->followups->count() }}</span></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('leads.show',$l) }}"><i class="bi bi-eye"></i> عرض</a>
              <a class="btn btn-sm btn-outline-dark" href="{{ route('leads.edit',$l) }}"><i class="bi bi-pencil"></i> تعديل</a>

              @if($l->registration_status === 'pending')
                <form class="d-inline" method="POST" action="{{ route('leads.convert',$l) }}">
                  @csrf
                  <button class="btn btn-sm btn-success"><i class="bi bi-arrow-repeat"></i> تحويل لطالب</button>
                </form>
              @else
                @if($l->student_id)
                  <a class="btn btn-sm btn-outline-success" href="{{ route('students.show',$l->student_id) }}">
                    <i class="bi bi-person-check"></i> ملف الطالب
                  </a>
                @endif
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $leads->links() }}
</div>
@endsection
