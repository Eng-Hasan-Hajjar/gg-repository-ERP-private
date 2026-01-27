@extends('layouts.app')
@php($activeModule = 'students')
@section('title','الطلاب')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والحالات</div>
  </div>


    <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('students.create') }}">
      <i class="bi bi-person-plus"></i> طالب جديد
    </a>
 
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('students.index') }}">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: الاسم / الرقم الجامعي / الهاتف / رمز الدبلومة">
    </div>

    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-3">
      <select name="status" class="form-select">
        <option value="">كل حالات الطالب</option>
        @foreach(['active','waiting','paid','withdrawn','failed','absent_exam','certificate_delivered','certificate_waiting','registration_ended','dismissed','frozen'] as $st)
          <option value="{{ $st }}" @selected(request('status')==$st)>{{ $st }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-2">
      <select name="registration_status" class="form-select">
        <option value="">حالة التسجيل</option>
        @foreach(['pending'=>'قيد الانتظار','confirmed'=>'مثبت','archived'=>'مؤرشف','dismissed'=>'مفصول','frozen'=>'مجمّد'] as $key=>$label)
          <option value="{{ $key }}" @selected(request('registration_status')==$key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-1 d-grid">
      <button class="btn btn-dark fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الرقم الجامعي</th>
          <th>الاسم</th>
          <th>الدبلومة</th>
          <th>الفرع</th>
          <th>المستوى</th>
          <th>الحالة</th>
          <th>التسجيل</th>
          <th>مثبّت؟</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td><code>{{ $s->university_id }}</code></td>
            <td class="fw-semibold">{{ $s->full_name }}</td>
            <td>
              <div class="col-lg-2">
                <select name="diploma_id" class="form-select">
                  <option value="">كل الدبلومات</option>
                  @foreach($diplomas as $d)
                    <option value="{{ $d->id }}" @selected(request('diploma_id')==$d->id)>{{ $d->name }} ({{ $d->code }})</option>
                  @endforeach
                </select>
              </div>
            </td>
            <td>{{ $s->branch->name ?? '-' }}</td>
            <td>{{ $s->level ?? '-' }}</td>
            <td><span class="badge bg-secondary">{{ $s->status }}</span></td>
            <td>
              @php($map = ['pending'=>'warning','confirmed'=>'success','archived'=>'secondary','dismissed'=>'danger','frozen'=>'info'])
              <span class="badge bg-{{ $map[$s->registration_status] ?? 'secondary' }}">
                {{ $s->registration_status }}
              </span>
            </td>
            <td>
              @if($s->is_confirmed)
                <span class="badge bg-success">نعم</span>
              @else
                <span class="badge bg-secondary">لا</span>
              @endif
        
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('students.show',$s) }}">
                <i class="bi bi-eye"></i> عرض
              </a>
             
                <a class="btn btn-sm btn-outline-dark" href="{{ route('students.edit',$s) }}">
                  <i class="bi bi-pencil"></i> تعديل
                </a>
           

              @if(!$s->is_confirmed)
            
                <form method="POST" action="{{ route('students.confirm', $s) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-sm btn-success">تثبيت</button>
                </form>
            
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
  {{ $students->links() }}
</div>
@endsection
