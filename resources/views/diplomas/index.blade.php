@extends('layouts.app')
@php($activeModule = 'diplomas')
@section('title', 'الدبلومات')

@section('content')
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
      <h4 class="mb-1">إدارة الدبلومات</h4>
      <div class="text-muted small">إنشاء وتعديل وتفعيل الدبلومات وربطها بالطلاب.</div>
    </div>
    @if(auth()->user()?->hasPermission('create_diplomas'))
      <a class="btn btn-primary" href="{{ route('diplomas.create') }}">
        + إضافة دبلومة
      </a>
    @endif
  </div>

  <form class="card card-body mb-3" method="GET" action="{{ route('diplomas.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label mb-1">بحث</label>
        <input name="search" value="{{ request('search') }}" class="form-control"
          placeholder="ابحث بالاسم / الرمز / المجال">
      </div>

      <div class="col-md-3">
        <label class="form-label mb-1">الحالة</label>
        <select name="is_active" class="form-select">
          <option value="">الكل</option>
          <option value="1" @selected(request('is_active') === '1')>مفعّلة</option>
          <option value="0" @selected(request('is_active') === '0')>غير مفعّلة</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label mb-1">نوع الدبلومة</label>
        <select name="type" class="form-select">
          <option value="">الكل</option>
          <option value="onsite" @selected(request('type') == 'onsite')>
            حضوري
          </option>
          <option value="online" @selected(request('type') == 'online')>
            أونلاين
          </option>
        </select>
      </div>


      <div class="col-md-3 d-grid">
        <button class="btn btn-namaa">تطبيق</button>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile text-center">#</th>
            <th>اسم الدبلومة</th>
            <th>الرمز</th>
            <th class="hide-mobile text-center">المجال</th>
            <th>الحالة</th>

            <th class="text-center">عدد الطلاب</th>
            <th class="hide-mobile text-center">النوع</th>
            <th class="hide-mobile text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($diplomas as $d)
            <tr>
              <td class="hide-mobile">{{ $d->id }}</td>
              <td class="fw-semibold">{{ $d->name }}</td>
              <td><span class="badge text-bg-secondary">{{ $d->code }}</span></td>
              <td class="hide-mobile  text-center text-muted">{{ $d->field ?? '-' }}</td>
              <td>
                @if($d->is_active)
                  <span class="badge text-bg-success">مفعّلة</span>
                @else
                  <span class="badge text-bg-danger">غير مفعّلة</span>
                @endif
              </td>
              <td class=" text-center">
                <span class="badge text-bg-light border">{{ $d->students()->count() }}</span>
              </td>

              <td class="hide-mobile text-center">
                <span class="badge {{ $d->type == 'online' ? 'bg-info' : 'bg-secondary' }}">
                  {{ $d->type_label }}
                </span>
              </td>


              <td class="hide-mobile text-end">
                <div class="d-inline-flex gap-1">
                  <form method="POST" action="{{ route('diplomas.toggle', $d) }}">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-outline-success">
                      {{ $d->is_active ? 'تعطيل' : 'تفعيل' }}
                    </button>
                  </form>

                  @if(auth()->user()?->hasPermission('view_program_management'))

                    <a href="{{ route('programs.management.show', $d) }}" class="btn btn-sm btn-outline-primary">
                      <i class="bi bi-diagram-3"></i>
                      إدارة البرنامج
                    </a>

                  @endif


                  <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#diplomaModal"
                    data-id="{{ $d->id }}" data-name="{{ $d->name }}" data-code="{{ $d->code }}"
                    data-field="{{ $d->field ?? '-' }}" data-type="{{ $d->type_label }}"
                    data-status="{{ $d->is_active ? 'مفعّلة' : 'غير مفعّلة' }}"
                    data-students="{{ $d->students()->count() }}" data-pdf="{{ $d->pdf_url ?? '' }}">
                    <i class="bi bi-eye"></i> تفاصيل
                  </button>

                  @if(auth()->user()?->hasPermission('edit_diplomas'))
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('diplomas.edit', $d) }}">تعديل</a>
                  @endif
                  @if(auth()->user()?->hasPermission('delete_diplomas'))
                    <form method="POST" action="{{ route('diplomas.destroy', $d) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف الدبلومة؟');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">لا يوجد دبلومات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $diplomas->links() }}
  </div>















  {{-- ===== Modal تفاصيل الدبلومة ===== --}}
<div class="modal fade" id="diplomaModal" tabindex="-1" aria-labelledby="diplomaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="diplomaModalLabel">
          <i class="bi bi-mortarboard text-primary me-1"></i>
          <span id="m-name"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <table class="table table-sm table-borderless mb-0">
          <tr>
            <th class="text-muted" width="35%">الرمز</th>
            <td><span class="badge bg-secondary" id="m-code"></span></td>
          </tr>
          <tr>
            <th class="text-muted">المجال</th>
            <td id="m-field"></td>
          </tr>
          <tr>
            <th class="text-muted">النوع</th>
            <td id="m-type"></td>
          </tr>
          <tr>
            <th class="text-muted">الحالة</th>
            <td id="m-status"></td>
          </tr>
          <tr>
            <th class="text-muted">عدد الطلاب</th>
            <td id="m-students"></td>
          </tr>
        </table>

        {{-- منطقة الـ PDF --}}
        <div id="m-pdf-area" class="mt-3 d-none">
          <hr class="my-2">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>
            <div>
              <div class="fw-semibold small">ملف تفاصيل الدبلومة</div>
              <a id="m-pdf-link" href="#" target="_blank" class="small">
                عرض / تحميل PDF
              </a>
            </div>
          </div>
        </div>

        <div id="m-no-pdf" class="mt-3 text-muted small d-none">
          <i class="bi bi-file-earmark-x"></i> لا يوجد ملف PDF مرفق.
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>

<script>
document.getElementById('diplomaModal').addEventListener('show.bs.modal', function (e) {
  const btn = e.relatedTarget;

  document.getElementById('m-name').textContent     = btn.dataset.name;
  document.getElementById('m-code').textContent     = btn.dataset.code;
  document.getElementById('m-field').textContent    = btn.dataset.field;
  document.getElementById('m-type').textContent     = btn.dataset.type;
  document.getElementById('m-status').textContent   = btn.dataset.status;
  document.getElementById('m-students').textContent = btn.dataset.students;

  const pdfArea  = document.getElementById('m-pdf-area');
  const noPdf    = document.getElementById('m-no-pdf');
  const pdfLink  = document.getElementById('m-pdf-link');

  if (btn.dataset.pdf) {
    pdfLink.href = btn.dataset.pdf;
    pdfArea.classList.remove('d-none');
    noPdf.classList.add('d-none');
  } else {
    pdfArea.classList.add('d-none');
    noPdf.classList.remove('d-none');
  }
});
</script>




@endsection