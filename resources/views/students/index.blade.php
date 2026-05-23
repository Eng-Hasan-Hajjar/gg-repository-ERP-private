@extends('layouts.app')
@php($activeModule = 'students')
@section('title', 'الطلاب')

@section('content')

{{-- ══ Header ══ --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والحالات</div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('students.create') }}">
      <i class="bi bi-person-plus"></i> طالب جديد
    </a>
    <a class="btn btn-outline-success rounded-pill px-4 fw-bold" href="{{ route('students.reports.index') }}">
      <i class="bi bi-file-earmark-excel"></i> التقارير
    </a>
  </div>
</div>

{{-- ══ إحصائيات سريعة ══ --}}
<div class="row g-2 mb-3">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #3b82f6 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#3b82f6;">{{ $totalCount }}</div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">إجمالي الطلاب</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #10b981 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#10b981;">{{ $confirmedCount }}</div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">مثبّتون</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #f59e0b !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#f59e0b;">{{ $pendingCount }}</div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">قيد الانتظار</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center py-2 px-3"
      style="border-right:4px solid #8b5cf6 !important; border-radius:12px;">
      <div style="font-size:1.6rem; font-weight:900; color:#8b5cf6;">{{ $myCount }}</div>
      <div style="font-size:.8rem; color:#64748b; font-weight:700;">أضفتهم أنا</div>
    </div>
  </div>

  @if($needsVerificationCount > 0)
    <div class="alert border-0 shadow-sm mb-3 d-flex align-items-center gap-3"
      style="background:rgba(245,158,11,.08); border-right:4px solid #f59e0b !important; border-radius:12px;">
      <i class="bi bi-exclamation-triangle-fill fs-4" style="color:#f59e0b;"></i>
      <div>
        <div class="fw-bold" style="color:#92400e;">
          {{ $needsVerificationCount }} طالب يحتاج مراجعة بيانات
        </div>
        <div class="small text-muted">
          ينقصهم: الاسم اللاتيني أو تاريخ الميلاد أو رقم الوثيقة
        </div>
      </div>
      <a href="{{ route('students.index', ['needs_verification' => 1]) }}" class="btn btn-sm fw-bold ms-auto"
        style="background:#f59e0b; color:#fff; border-radius:8px;">
        عرض القائمة
      </a>
    </div>
  @endif


</div>

{{-- ══ فلاتر ══ --}}
<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('students.index') }}">
  <div class="row g-2 align-items-end">

    {{-- زر "طلابي فقط" للموظف العادي --}}


    @if($showMyOnly)
      <div class="col-auto">
        <label class="form-label fw-bold d-block" style="font-size:.75rem; color:#64748b;">عرض</label>
        <a href="{{ request()->boolean('my_only')
      ? request()->fullUrlWithQuery(['my_only' => 0, 'page' => null])
      : request()->fullUrlWithQuery(['my_only' => 1, 'page' => null]) }}"
          class="btn fw-bold {{ request()->boolean('my_only') ? 'btn-primary' : 'btn-outline-secondary' }}">
          <i class="bi bi-person-fill"></i>
          {{ request()->boolean('my_only') ? '✓ طلابي فقط' : 'كل الطلاب' }}
        </a>
      </div>
    @endif

    <div class="col-12 col-md-3">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">بحث</label>
      <input name="search" value="{{ request('search') }}" class="form-control"
        placeholder="الاسم / الرقم الجامعي / الهاتف">
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">الدبلومة</label>
      <select name="diploma_id" class="form-select">
        <option value="">كل الدبلومات</option>
        @foreach($diplomas as $d)
          <option value="{{ $d->id }}" @selected(request('diploma_id') == $d->id)>{{ $d->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">الفرع</label>
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">حالة الطالب</label>
      <select name="status" class="form-select">
        <option value="">كل الحالات</option>
        @foreach($statusOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-1">
      <label class="form-label fw-bold" style="font-size:.75rem; color:#64748b;">التسجيل</label>
      <select name="registration_status" class="form-select">
        <option value="">الكل</option>
        @foreach($registrationOptions as $key => $label)
          <option value="{{ $key }}" @selected(request('registration_status') == $key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-auto d-flex gap-1">
      <button class="btn btn-namaa fw-bold px-3">
        <i class="bi bi-search"></i> تطبيق
      </button>
      @if(request()->hasAny(['search', 'diploma_id', 'branch_id', 'status', 'registration_status', 'my_only']))
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary fw-bold px-3" title="مسح الفلاتر">
          <i class="bi bi-x-lg"></i>
        </a>
      @endif
    </div>

  </div>

  {{-- حفظ my_only عند submit الفورم --}}
  @if(request()->boolean('my_only'))
    <input type="hidden" name="my_only" value="1">
  @endif
</form>

{{-- ══ الجدول ══ --}}
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
          <td class="fw-semibold">
            {{ $s->full_name }}
            @if(!empty($s->profile?->message_to_send))
              <span class="badge bg-warning text-dark ms-1" title="{{ $s->profile->message_to_send }}"
                data-bs-toggle="tooltip">📩</span>
            @endif

            {{-- ✅ badge التحقق الجديد --}}
            @if(
                        empty($s->profile?->arabic_full_name) ||
                        empty($s->profile?->birth_date) ||
                        empty($s->profile?->national_id)
                      )
                      <span class="badge ms-1" style="background:rgba(245,158,11,.15); color:#92400e; font-size:.7rem;" title="يحتاج مراجعة: {{ collect([
                empty($s->profile?->arabic_full_name) ? 'اسم لاتيني' : null,
                empty($s->profile?->birth_date) ? 'تاريخ ميلاد' : null,
                empty($s->profile?->national_id) ? 'رقم وثيقة' : null,
              ])->filter()->implode(' · ') }}" data-bs-toggle="tooltip">
                        ⚠️
                      </span>
            @endif


            {{-- علامة "أضفته أنا" --}}
            @if($s->created_by === auth()->id())
              <span class="badge ms-1" style="background:rgba(139,92,246,.12); color:#7c3aed; font-size:.7rem;">أنا</span>
            @endif



          </td>
          <td class="hide-mobile">{{ $s->branch->name ?? '-' }}</td>
          <td class="hide-mobile">
            <span class="badge bg-secondary">{{ $s->status_ar }}</span>
          </td>
          <td class="hide-mobile">
            @php($map = ['pending' => 'warning', 'confirmed' => 'success', 'archived' => 'secondary', 'dismissed' => 'danger', 'frozen' => 'info'])
            <span class="badge bg-{{ $map[$s->registration_status] ?? 'secondary' }}">
              {{ $s->registration_ar }}
            </span>
          </td>
          <td class="hide-mobile">
            <span class="badge bg-{{ $s->is_confirmed ? 'success' : 'secondary' }}">
              {{ $s->is_confirmed ? 'نعم' : 'لا' }}
            </span>
          </td>
          <td class="text-end">

            @if(auth()->user()?->hasPermission('view_student_financials'))
              <button class="btn btn-sm btn-outline-success"
                onclick="showFinancial({{ $s->id }}, '{{ addslashes($s->full_name) }}')" title="التفاصيل المالية">
                <i class="bi bi-cash-coin"></i>
              </button>
            @endif

            <button class="btn btn-sm btn-outline-info"
              onclick="showExams({{ $s->id }}, '{{ addslashes($s->full_name) }}')" title="نتائج الامتحانات">
              <i class="bi bi-journal-check"></i>
            </button>

            @if(auth()->user()?->hasPermission('edit_students'))
              <a class="btn btn-sm btn-outline-primary" href="{{ route('students.show', $s) }}">
                <i class="bi bi-eye"></i>
              </a>
            @endif

            @if(auth()->user()?->hasPermission('edit_students'))
              <a class="btn btn-sm btn-outline-dark" href="{{ route('students.edit', $s) }}">
                <i class="bi bi-pencil"></i>
              </a>
            @endif

            @if(auth()->user()?->hasPermission('delete_students'))
              <form method="POST" action="{{ route('students.destroy', $s) }}" class="d-inline"
                onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            @endif

            @if(!$s->is_confirmed)
              <form method="POST" action="{{ route('students.confirm', $s) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success">تثبيت</button>
              </form>
            @endif

          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
            لا يوجد طلاب مطابقون للفلتر الحالي
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div class="text-muted small">
    عرض {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}
    من أصل {{ $students->total() }} طالب
  </div>
  {{ $students->links() }}
</div>

{{-- Modal --}}
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
    new bootstrap.Modal(document.getElementById('studentInfoModal')).show();
    fetch('/students/' + id + '/modal/financial').then(r => r.text()).then(html => {
      document.getElementById('modalBody').innerHTML = html;
    });
  }
  function showExams(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-journal-check text-info me-2"></i> نتائج الامتحانات — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info"></div></div>';
    new bootstrap.Modal(document.getElementById('studentInfoModal')).show();
    fetch('/students/' + id + '/modal/exams').then(r => r.text()).then(html => {
      document.getElementById('modalBody').innerHTML = html;
    });
  }
</script>

@endsection