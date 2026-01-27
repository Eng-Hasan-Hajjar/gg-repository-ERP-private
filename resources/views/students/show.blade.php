@extends('layouts.app')
@php($activeModule = 'students')

@section('title','ملف الطالب')

@push('styles')
<style>
  /* Page helpers aligned with Namaa identity */
  .page-head{
    background: rgba(255,255,255,.75);
    border: 1px solid rgba(226,232,240,.92);
    border-radius: 18px;
    backdrop-filter: blur(8px);
    box-shadow: 0 20px 60px rgba(2,6,23,.08);
    padding: 16px;
  }

  .page-title{
    font-weight: 900;
    margin: 0;
    font-size: 1.1rem;
  }

  .meta-line{
    color: var(--namaa-muted);
    font-weight: 700;
    line-height: 1.9;
  }

  .glass-card{
    background: rgba(255,255,255,.82);
    border: 1px solid rgba(226,232,240,.92);
    border-radius: 18px;
    backdrop-filter: blur(8px);
    box-shadow: 0 18px 55px rgba(2,6,23,.08);
    overflow: hidden;
  }

  .card-title{
    font-weight: 900;
    margin: 0;
    font-size: 1.02rem;
  }

  .soft-divider{
    border-top: 1px solid rgba(226,232,240,.9);
    margin: 14px 0;
  }

  .btn-pill{
    border-radius: 999px !important;
    font-weight: 900;
    padding: 10px 14px;
  }

  .btn-namaa{
    border: 0;
    font-weight: 900;
    border-radius: 999px;
    padding: 10px 14px;
    background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
    color: #fff !important;
    box-shadow: 0 18px 35px rgba(16,185,129,.18), 0 16px 26px rgba(14,165,233,.14);
  }
  .btn-namaa:hover{ filter: brightness(.96); }

  .badge-soft{
    border-radius: 999px;
    padding: 7px 10px;
    font-weight: 900;
    font-size: .8rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(226,232,240,.95);
    background: rgba(255,255,255,.85);
    color: var(--namaa-ink);
  }
  .badge-soft.success{
    background: rgba(16,185,129,.10);
    border-color: rgba(16,185,129,.22);
    color: #0f766e;
  }
  .badge-soft.gray{
    background: rgba(100,116,139,.10);
    border-color: rgba(100,116,139,.22);
    color: #334155;
  }

  .kv{
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px dashed rgba(226,232,240,.95);
  }
  .kv:last-child{ border-bottom: 0; }

  .k{
    color: var(--namaa-muted);
    font-weight: 800;
  }
  .v{
    font-weight: 800;
    color: var(--namaa-ink);
    word-break: break-word;
  }

  .avatar{
    width: 58px;
    height: 58px;
    border-radius: 999px;
    overflow: hidden;
    border: 1px solid rgba(226,232,240,.95);
    background: rgba(248,250,252,.9);
    display: grid;
    place-items: center;
    flex: 0 0 auto;
  }
  .avatar img{ width:100%; height:100%; object-fit:cover; }

  .file-btn{
    border-radius: 14px;
    font-weight: 900;
  }

  /* Mobile tuning */
  @media (max-width: 575.98px){
    .kv{ grid-template-columns: 1fr; gap: 6px; }
    .page-head{ padding: 14px; }
  }
</style>
@endpush

@section('content')

{{-- ===================== Header ===================== --}}
<div class="page-head mb-3">
  <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">

    <div>
      <h4 class="page-title">{{ $student->full_name }}</h4>

      <div class="meta-line small">
        رقم جامعي: <code>{{ $student->university_id }}</code>
        <span class="mx-2">—</span>
        الفرع: <b>{{ $student->branch->name ?? '-' }}</b>

        @if($student->diploma)
          <span class="mx-2">—</span>
          الدبلومة: <b>{{ $student->diploma->name }}</b> <span class="text-muted">({{ $student->diploma->code }})</span>
        @endif


        @if($student->is_confirmed)
          <a class="btn btn-primary rounded-pill px-4 fw-bold"
            href="{{ route('students.profile.edit', $student) }}">
            <i class="bi bi-person-vcard"></i> تعديل الملف التفصيلي
          </a>
        @endif

        
      </div>

      <div class="mt-2">
        @if($student->is_confirmed)
          <span class="badge-soft success">
            <i class="bi bi-check2-circle"></i>
            مثبّت
            <span class="text-muted fw-bold">({{ optional($student->confirmed_at)->format('Y-m-d H:i') }})</span>
          </span>
        @else
          <span class="badge-soft gray">
            <i class="bi bi-hourglass-split"></i>
            غير مثبّت
          </span>
        @endif
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      @can('students.update')
        <a class="btn btn-outline-dark btn-pill" href="{{ route('students.edit',$student) }}">
          <i class="bi bi-pencil"></i>
          <span class="d-none d-sm-inline">تعديل أساسي</span>
          <span class="d-inline d-sm-none">تعديل</span>
        </a>
      @endcan

      @can('students.confirm')
        @if(!$student->is_confirmed)
          <form method="POST" action="{{ route('students.confirm',$student) }}">
            @csrf
            <button class="btn btn-success btn-pill">
              <i class="bi bi-check2-circle"></i>
              تثبيت الطالب
            </button>
          </form>
        @endif
      @endcan

      @can('students.extra.update')
        <a class="btn btn-namaa" href="{{ route('students.profile.edit',$student) }}">
          <i class="bi bi-person-vcard"></i>
          <span class="d-none d-sm-inline">الملف التفصيلي</span>
          <span class="d-inline d-sm-none">الملف</span>
        </a>
      @endcan
    </div>

  </div>
</div>

{{-- ===================== Content Grid ===================== --}}
<div class="row g-3">

  {{-- Basic data --}}
  <div class="col-12 col-lg-6">
    <div class="glass-card h-100">
      <div class="p-3 p-md-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="card-title">البيانات الأساسية</h6>
          <span class="badge-soft">
            <i class="bi bi-person-lines-fill"></i>
            Student
          </span>
        </div>

        <div class="soft-divider"></div>

        <div class="kv">
          <div class="k">نوع الطالب</div>
          <div class="v">{{ $student->mode }}</div>
        </div>

        <div class="kv">
          <div class="k">حالة الطالب</div>
          <div class="v">{{ $student->status }}</div>
        </div>

        <div class="kv">
          <div class="k">حالة التسجيل</div>
          <div class="v">{{ $student->registration_status ?? '-' }}</div>
        </div>

        <div class="kv">
          <div class="k">الهاتف</div>
          <div class="v">{{ $student->phone ?? '-' }}</div>
        </div>

        <div class="kv">
          <div class="k">الإيميل</div>
          <div class="v">{{ $student->email ?? '-' }}</div>
        </div>

        <div class="kv">
          <div class="k">واتساب</div>
          <div class="v">
            @if($student->whatsapp)
              <a class="text-decoration-none fw-bold"
                 target="_blank"
                 href="{{ str_starts_with($student->whatsapp,'http') ? $student->whatsapp : 'https://wa.me/'.preg_replace('/\D+/','',$student->whatsapp) }}">
                <i class="bi bi-whatsapp"></i> فتح واتساب
              </a>
            @else
              -
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Profile summary --}}
  <div class="col-12 col-lg-6">
    <div class="glass-card h-100">
      <div class="p-3 p-md-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="card-title">ملخص الملف التفصيلي</h6>
          <span class="badge-soft">
            <i class="bi bi-person-vcard"></i>
            Profile
          </span>
        </div>

        <div class="soft-divider"></div>

        @if(!$student->is_confirmed)
          <div class="alert alert-warning mb-0 fw-semibold">
            <i class="bi bi-exclamation-triangle"></i>
            لا يمكن عرض/تعديل الملف التفصيلي قبل تثبيت الطالب.
          </div>
        @else
          @php($p = $student->profile)

          @if(!$p)
            <div class="alert alert-info mb-0 fw-semibold">
              <i class="bi bi-info-circle"></i>
              لا يوجد ملف تفصيلي بعد — اضغط “الملف التفصيلي” لإدخاله.
            </div>
          @else

            {{-- Header mini --}}
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="avatar">
                @if($p->photo_path)
                  <img src="{{ asset('storage/'.$p->photo_path) }}" alt="student photo">
                @else
                  <i class="bi bi-person fs-3 text-secondary"></i>
                @endif
              </div>

              <div class="flex-grow-1">
                <div class="fw-bold">{{ $p->arabic_full_name ?? '—' }}</div>
                <div class="small text-muted fw-semibold">
                  الجنسية: {{ $p->nationality ?? '-' }}
                  <span class="mx-2">—</span>
                  تولد: {{ $p->birth_date?->format('Y-m-d') ?? '-' }}
                </div>
              </div>
            </div>

            <div class="row g-2 small">
              <div class="col-12 col-md-6">
                <div class="kv">
                  <div class="k">الرقم الوطني</div>
                  <div class="v">{{ $p->national_id ?? '-' }}</div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="kv">
                  <div class="k">العلامة</div>
                  <div class="v">{{ $p->exam_score ?? '-' }}</div>
                </div>
              </div>
              <div class="col-12">
                <div class="kv">
                  <div class="k">العنوان</div>
                  <div class="v">{{ $p->address ?? '-' }}</div>
                </div>
              </div>
              <div class="col-12">
                <div class="kv">
                  <div class="k">ملاحظات</div>
                  <div class="v">{{ $p->notes ? \Illuminate\Support\Str::limit($p->notes, 140) : '-' }}</div>
                </div>
              </div>
            </div>

            <div class="soft-divider"></div>

            {{-- Files --}}
            <div class="d-flex flex-wrap gap-2">
              @if($p->info_file_path)
                <a class="btn btn-outline-primary file-btn btn-sm"
                   target="_blank"
                   href="{{ asset('storage/'.$p->info_file_path) }}">
                  <i class="bi bi-file-earmark-text"></i> ملف المعلومات
                </a>
              @endif

              @if($p->identity_file_path)
                <a class="btn btn-outline-dark file-btn btn-sm"
                   target="_blank"
                   href="{{ asset('storage/'.$p->identity_file_path) }}">
                  <i class="bi bi-person-badge"></i> ملف الهوية
                </a>
              @endif

              @if($p->certificate_pdf_path)
                <a class="btn btn-outline-success file-btn btn-sm"
                   target="_blank"
                   href="{{ asset('storage/'.$p->certificate_pdf_path) }}">
                  <i class="bi bi-file-earmark-pdf"></i> شهادة PDF
                </a>
              @endif
            </div>

          @endif
        @endif

      </div>
    </div>
  </div>

</div>
@endsection
