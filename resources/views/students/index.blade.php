@extends('layouts.app')
@php($activeModule = 'students')

@section('title', 'الطلاب')




@section('content')


<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والحالات</div>
  </div>


  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('students.create') }}">
    <i class="bi bi-person-plus"></i> طالب جديد
  </a>

  <a class="btn btn-outline-success rounded-pill px-4 fw-bold" href="{{ route('students.reports.index') }}">
    <i class="bi bi-file-earmark-excel"></i> التقارير
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('students.index') }}">
  <div class="row g-2">

  {{-- يظهر فقط للموظف العادي --}}

@if( !auth()->user()->hasRole('super_admin')
               && !auth()->user()->hasRole('manager_student_affairs')
               && !auth()->user()->hasPermission('view_all_students'))
<div class="col-auto">
    <a href="{{ request()->fullUrlWithQuery(['my_only' => request()->boolean('my_only') ? 0 : 1, 'page' => null]) }}"
       class="btn fw-bold {{ request()->boolean('my_only') ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bi bi-person-fill"></i>
        {{ request()->boolean('my_only') ? 'طلابي فقط ✓' : 'كل الطلاب' }}
    </a>
</div>
@endif

    <div class="col-6 col-md-4">
      <input name="search" value="{{ request('search') }}" class="form-control"
        placeholder="بحث: الاسم / الرقم الجامعي / الهاتف / رمز الدبلومة">
    </div>


    <div class="col-6 col-md-2">
      <select name="diploma_id" class="form-select">
        <option value="">كل الدبلومات</option>
        @foreach($diplomas as $d)
          <option value="{{ $d->id }}" @selected(request('diploma_id') == $d->id)>{{ $d->name }}</option>
        @endforeach
      </select>
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
      <select name="status" class="form-select">
        <option value="">كل حالات الطالب</option>
        @foreach($statusOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
        @endforeach

      </select>
    </div>

    <div class="col-6 col-md-1">
      <select name="registration_status" class="form-select">
        <option value="">حالة التسجيل</option>
        @foreach($registrationOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('registration_status') == $key)>{{ $label }}</option>
        @endforeach

      </select>
    </div>

    <div class="col-6 col-md-1 d-grid">
      <button class="btn btn-namaa fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">






    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>

          <th class="hide-mobile">#</th>
          <th>الرقم الجامعي</th>
          <th>الاسم</th>

          <th class="hide-mobile">الفرع</th>

          <th class="hide-mobile">الحالة</th>
          <th class="hide-mobile">التسجيل</th>
          <th class="hide-mobile">مثبّت؟</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $s)
        <tr>

          <td class="hide-mobile">{{ $s->id }}</td>
          <td><code>{{ $s->university_id }}</code></td>
          <td class="fw-semibold">{{ $s->full_name }}


            @if(!empty($s->profile?->message_to_send))
              <span class="badge bg-warning text-dark ms-1" title="{{ $s->profile->message_to_send }}"
                data-bs-toggle="tooltip">
                📩
              </span>
            @endif

          </td>

          <td class="hide-mobile">{{ $s->branch->name ?? '-' }}</td>

          <td class="hide-mobile"><span class="badge bg-secondary">{{ $s->status_ar }}</span></td>
          <td class="hide-mobile">
            @php($map = ['pending' => 'warning', 'confirmed' => 'success', 'archived' => 'secondary', 'dismissed' => 'danger', 'frozen' => 'info'])
            <span class="badge bg-{{ $map[$s->registration_ar] ?? 'secondary' }}">
              {{ $s->registration_ar }}
            </span>
          </td>
          <td class="hide-mobile">
            @if($s->is_confirmed)
              <span class="badge bg-success">نعم</span>
            @else
              <span class="badge bg-secondary">لا</span>
            @endif

          </td>
          <td class="text-end">




            {{-- ✅ زران جديدان --}}
            @if(auth()->user()?->hasPermission('view_student_financials') && !$s->is_readonly)
              <button class="btn btn-sm btn-outline-success"
                onclick="showFinancial({{ $s->id }}, '{{ addslashes($s->full_name) }}')" title="التفاصيل المالية">
                <i class="bi bi-cash-coin"></i>
              </button>
            @endif
            <button class="btn btn-sm btn-outline-info"
              onclick="showExams({{ $s->id }}, '{{ addslashes($s->full_name) }}')" title="نتائج الامتحانات">
              <i class="bi bi-journal-check"></i>
            </button>

            {{-- باقي الأزرار --}}





            @if(auth()->user()?->hasPermission('edit_students') && !$s->is_readonly)
              <a class="btn btn-sm btn-outline-primary" href="{{ route('students.show', $s) }}">
                <i class="bi bi-eye"></i> عرض
              </a>
            @endif

            @if(auth()->user()?->hasPermission('edit_students') && !$s->is_readonly)
              <a class="btn btn-sm btn-outline-dark" href="{{ route('students.edit', $s) }}">
                <i class="bi bi-pencil"></i> تعديل
              </a>
            @endif
            @if(auth()->user()?->hasPermission('delete_students') && !$s->is_readonly)
              <form method="POST" action="{{ route('students.destroy', $s) }}" class="d-inline"
                onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟')">
                @csrf
                @method('DELETE')

                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> حذف
                </button>
              </form>
            @endif


            @if(!$s->is_confirmed && !$s->is_readonly)

              <form method="POST" action="{{ route('students.confirm', $s) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success">تثبيت</button>
              </form>

            @endif

          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td>
        </tr>
        @endforelse
      </tbody>
    </table>








  </div>
</div>

<div class="mt-3">
  {{ $students->links() }}
</div>





{{-- Modal المالي والامتحاني --}}
<div class="modal fade" id="studentInfoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalTitle">تفاصيل الطالب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody">
        <div class="text-center py-4">
          <div class="spinner-border text-primary"></div>
          <div class="mt-2 text-muted">جاري التحميل...</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function showFinancial(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-cash-coin text-success me-2"></i> التفاصيل المالية — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
    var modal = new bootstrap.Modal(document.getElementById('studentInfoModal'));
    modal.show();

    fetch('/students/' + id + '/modal/financial')
      .then(function (r) { return r.text(); })
      .then(function (html) {
        document.getElementById('modalBody').innerHTML = html;
      });
  }

  function showExams(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-journal-check text-info me-2"></i> نتائج الامتحانات — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info"></div></div>';
    var modal = new bootstrap.Modal(document.getElementById('studentInfoModal'));
    modal.show();

    fetch('/students/' + id + '/modal/exams')
      .then(function (r) { return r.text(); })
      .then(function (html) {
        document.getElementById('modalBody').innerHTML = html;
      });
  }
</script>



@endsection