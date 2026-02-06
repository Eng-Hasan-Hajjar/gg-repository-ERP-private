@extends('layouts.app')
@php($activeModule = 'students')

@section('title','ملف الطالب')

@push('styles')
<style>
  :root{
  --namaa-blue:#0ea5e9;
  --namaa-green:#10b981;
  --namaa-ink:#0f172a;
  --namaa-muted:#64748b;
  --namaa-soft-bg:#f8fafc;
}
/* ===== رأس الصفحة ===== */
.page-head{
  background: rgba(255,255,255,.75);
  border: 1px solid rgba(226,232,240,.92);
  border-radius: 18px;
  backdrop-filter: blur(8px);
  box-shadow: 0 20px 60px rgba(2,6,23,.08);
  padding: 16px;
}

/* ===== كارد زجاجي أساسي ===== */
.glass-card{
  background: rgba(255,255,255,.85);
  border: 1px solid rgba(226,232,240,.95);
  border-radius: 18px;
  backdrop-filter: blur(8px);
  box-shadow: 0 18px 55px rgba(2,6,23,.08);
  overflow: hidden;
}

/* ===== رأس ملوّن لكل قسم (هوية نماء) ===== */
.section-header{
  background: linear-gradient(90deg, var(--namaa-blue) 0%, var(--namaa-green) 100%);
  color:#fff;
  padding: 12px 16px;
  font-weight: 900;
  border-radius: 16px 16px 0 0;
  display:flex;
  align-items:center;
  gap:8px;
}

/* ===== جسم القسم ===== */
.section-body{
  padding: 16px;
}

/* ===== أزواج المفتاح/القيمة ===== */
.kv{
  display:grid;
  grid-template-columns: 170px 1fr;
  gap:10px;
  padding:10px 0;
  border-bottom:1px dashed rgba(226,232,240,.95);
}
.kv:last-child{border-bottom:0;}
.k{color:var(--namaa-muted);font-weight:800;}
.v{font-weight:800;color:var(--namaa-ink);}

/* ===== الصورة الشخصية ===== */
.avatar{
  width:74px;
  height:74px;
  border-radius:999px;
  overflow:hidden;
  border:1px solid rgba(226,232,240,.95);
  background:rgba(248,250,252,.9);
  display:grid;
  place-items:center;
}

/* ===== أزرار مستديرة ===== */
.btn-pill{
  border-radius:999px;
  font-weight:900;
}

/* ===== زر نماء ===== */
.btn-namaa{
  border:0;
  background:linear-gradient(90deg, var(--namaa-blue), var(--namaa-green));
  color:#fff !important;
  box-shadow:0 18px 35px rgba(16,185,129,.18);
}

/* ===== شارات الحالة ===== */
.badge-soft{
  border-radius:999px;
  padding:7px 10px;
  font-weight:900;
  border:1px solid rgba(226,232,240,.95);
  background:#fff;
}

.badge-soft.success{background:rgba(16,185,129,.12);color:#0f766e;}
.badge-soft.warn{background:rgba(245,158,11,.12);color:#92400e;}
.badge-soft.gray{background:rgba(100,116,139,.12);color:#334155;}

  @media (max-width: 575.98px){
    .kv{ grid-template-columns: 1fr; gap: 6px; }
    .page-head{ padding: 14px; }
  }
</style>
@endpush

@section('content')

<div class="page-head mb-3">
  <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">

    <div class="w-100">
      <div class="d-flex align-items-center gap-3">

        <div class="avatar">
          @if(!empty($files['photo']['exists']) && $files['photo']['exists'])
            <img src="{{ $files['photo']['url'] }}" alt="photo">
          @else
            <i class="bi bi-person fs-2 text-muted"></i>
          @endif
        </div>

        <div class="flex-grow-1">
          <h4 class="page-title">{{ $student->full_name }}</h4>

          <div class="meta-line small">
            رقم جامعي: <code>{{ $student->university_id }}</code>
            <span class="mx-2">—</span>
            الفرع: <b>{{ $student->branch->name ?? '-' }}</b>
          </div>

          <div class="mt-2 d-flex flex-wrap gap-2">
            @if($student->is_confirmed)
              <span class="badge-soft success">
                <i class="bi bi-check2-circle"></i> مثبّت
                <span class="text-muted fw-bold">({{ optional($student->confirmed_at)->format('Y-m-d H:i') }})</span>
              </span>
            @else
              <span class="badge-soft gray"><i class="bi bi-hourglass-split"></i> غير مثبّت</span>
            @endif

            <span class="badge-soft"><i class="bi bi-diagram-3"></i> دبلومات: {{ $student->diplomas->count() }}</span>
            <span class="badge-soft warn"><i class="bi bi-info-circle"></i> الحالة: {{ $status_ar }}</span>
            <span class="badge-soft"><i class="bi bi-shield-check"></i> التسجيل: {{ $student->registration_status ?? '-' }}</span>
          </div>

          <div class="mt-2">
            <b>الدبلومات:</b>
            @forelse($student->diplomas as $d)
              <span class="badge bg-light text-dark border">{{ $d->name }} ({{ $d->code }})</span>
            @empty
              <span class="text-muted">لا يوجد دبلومات</span>
            @endforelse
          </div>
        </div>

      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-outline-dark btn-pill" href="{{ route('students.edit',$student) }}">
        <i class="bi bi-pencil"></i> تعديل أساسي
      </a>

      @if($student->is_confirmed)
        <a class="btn btn-namaa btn-pill" href="{{ route('students.profile.edit',$student) }}">
          <i class="bi bi-person-vcard"></i> الملف التفصيلي
        </a>
      @endif

      @if($waLink)
        <a class="btn btn-success btn-pill" target="_blank" href="{{ $waLink }}">
          <i class="bi bi-whatsapp"></i> واتساب الطالب
        </a>
      @endif
    </div>

  </div>
</div>

<div class="row g-3">

  <div class="col-12 col-lg-6">
    <div class="glass-card h-100">
          <div class="section-header">
      <i class="bi bi-person-vcard"></i>
      البيانات الأساسية
    </div>
      <div class="p-3 p-md-4">
       

        <div class="kv"><div class="k">الاسم</div><div class="v">{{ $student->first_name ?? '-' }}</div></div>
        <div class="kv"><div class="k">الكنية</div><div class="v">{{ $student->last_name ?? '-' }}</div></div>
        <div class="kv"><div class="k">الاسم والكنية</div><div class="v">{{ $student->full_name ?? '-' }}</div></div>
        <div class="kv"><div class="k">الهاتف</div><div class="v">{{ $student->phone ?? '-' }}</div></div>

        <div class="kv">
          <div class="k">واتساب</div>
          <div class="v">
            @if($waLink)
              <a class="text-decoration-none fw-bold" target="_blank" href="{{ $waLink }}">
                <i class="bi bi-whatsapp"></i> {{ $student->whatsapp }}
              </a>
            @else
              -
            @endif
          </div>
        </div>

        <div class="kv"><div class="k">الفرع</div><div class="v">{{ $student->branch->name ?? '-' }}</div></div>
        <div class="kv"><div class="k">نوع الطالب</div><div class="v">{{ $mode_ar ?? '-' }}</div></div>
        <div class="kv"><div class="k">حالة الطالب</div><div class="v">{{  $status_ar ?? '-' }}</div></div>
        <div class="kv"><div class="k">حالة التسجيل</div><div class="v">{{ $registration_ar ?? '-' }}</div></div>

        <div class="kv"><div class="k">الرقم الجامعي</div><div class="v"><code>{{ $student->university_id }}</code></div></div>

      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="glass-card h-100">
          <div class="section-header">
      <i class="bi bi-headset"></i>
      بيانات CRM
    </div>
      <div class="p-3 p-md-4">
       
        <div class="soft-divider"></div>

        @if(!$student->crmInfo)
          <div class="alert alert-info mb-0 fw-semibold">
            <i class="bi bi-info-circle"></i> لا يوجد بيانات CRM لهذا الطالب.
          </div>
        @else
          <div class="kv"><div class="k">تاريخ أول تواصل</div><div class="v">{{ $student->crmInfo->first_contact_date?->format('Y-m-d') ?? '-' }}</div></div>
          <div class="kv"><div class="k">السكن</div><div class="v">{{ $student->crmInfo->residence ?? '-' }}</div></div>
          <div class="kv"><div class="k">العمر</div><div class="v">{{ $student->crmInfo->age ?? '-' }}</div></div>
          <div class="kv"><div class="k">الجهة/المؤسسة</div><div class="v">{{ $student->crmInfo->organization ?? '-' }}</div></div>
          <div class="kv"><div class="k">المصدر</div><div class="v">{{ $crm_source_ar ?? '-' }}</div></div>
          <div class="kv"><div class="k">المرحلة</div><div class="v">{{ $crm_stage_ar  ?? '-' }}</div></div>
           <div class="kv"><div class="k">الإيميل</div><div class="v">{{ $student->crmInfo->email ?? '-' }}</div></div>
           <div class="kv"><div class="k">العمل</div><div class="v">{{ $student->crmInfo->job ?? '-' }}</div></div>

           <div class="kv">
              <div class="k">البلد</div>
              <div class="v">{{ $student->crmInfo->country ?? '-' }}</div>
            </div>

            <div class="kv">
              <div class="k">المحافظة</div>
              <div class="v">{{ $student->crmInfo->province ?? '-' }}</div>
            </div>

            <div class="kv">
              <div class="k">الدراسة</div>
              <div class="v">{{ $student->crmInfo->study ?? '-' }}</div>
            </div>

            <div class="kv">
              <div class="k">مسؤول التواصل</div>
              <div class="v">
                {{ $student->crmInfo->creator->name ?? $student->crmInfo->creator->email ?? '-' }}
              </div>
            </div>




          <div class="kv"><div class="k">الاحتياج</div><div class="v">{{ $student->crmInfo->need ?? '-' }}</div></div>
          <div class="kv"><div class="k">ملاحظات CRM</div><div class="v">{{ $student->crmInfo->notes ?? '-' }}</div></div>
          <div class="kv"><div class="k">تاريخ التحويل</div><div class="v">{{ $student->crmInfo->converted_at?->format('Y-m-d H:i') ?? '-' }}</div></div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="glass-card">
      <div class="p-3 p-md-4">
        <h6 class="card-title mb-2">الملف التفصيلي للطالب</h6>
        <div class="soft-divider"></div>

        @if(!$p)
          <div class="alert alert-warning mb-0 fw-semibold">
            <i class="bi bi-exclamation-triangle"></i> لا يوجد ملف تفصيلي لهذا الطالب بعد.
          </div>
        @else
          <div class="row g-3">

            <div class="col-12 col-lg-6">
              <div class="glass-card h-100">
                <div class="p-3 p-md-4">
                  <h6 class="card-title mb-2">بيانات شخصية</h6>
                  <div class="soft-divider"></div>

                  <div class="kv"><div class="k">الاسم بالعربي</div><div class="v">{{ $p->arabic_full_name ?? '-' }}</div></div>
                  <div class="kv"><div class="k">الجنسية</div><div class="v">{{ $p->nationality ?? '-' }}</div></div>
                  <div class="kv"><div class="k">تاريخ التولد</div><div class="v">{{ $p->birth_date?->format('Y-m-d') ?? '-' }}</div></div>
                  <div class="kv"><div class="k">الرقم الوطني</div><div class="v">{{ $p->national_id ?? '-' }}</div></div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="glass-card h-100">
                <div class="p-3 p-md-4">
                  <h6 class="card-title mb-2">معلومات إضافية</h6>
                  <div class="soft-divider"></div>

                  <div class="kv"><div class="k">مستوى اللغة</div><div class="v">{{ $p->level ?? '-' }}</div></div>
                  <div class="kv"><div class="k">ستاج/مرحلة بالولاية</div><div class="v">{{ $p->stage_in_state ?? '-' }}</div></div>
                  <div class="kv"><div class="k">المستوى التعليمي</div><div class="v">{{ $p->education_level ?? '-' }}</div></div>
                  <div class="kv"><div class="k">العلامة الامتحانية</div><div class="v">{{ $p->exam_score ?? '-' }}</div></div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="glass-card">
                <div class="p-3 p-md-4">
                  <h6 class="card-title mb-2">الوثائق والملفات</h6>
                  <div class="soft-divider"></div>

                  <div class="row g-2">

                  {{-- ===================== Files Section (NO PHP, NO LOOP) ===================== --}}
<div class="col-12">
  <div class="glass-card">
    <div class="p-3 p-md-4">
      <h6 class="card-title mb-2">الوثائق والملفات</h6>
      <div class="soft-divider"></div>

      <div class="row g-2">

        {{-- info --}}
        <div class="col-12 col-md-4">
          <div class="kv">
            <div class="k">ملف المعلومات</div>
            <div class="v">
              @if(!empty($files['info']['exists']) && $files['info']['exists'])
                <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجود</span>
                <div class="mt-2">
                  <a class="btn btn-outline-primary btn-sm file-btn" target="_blank" href="{{ $files['info']['url'] }}">
                    <i class="bi bi-file-earmark-text"></i> فتح
                  </a>
                </div>
              @else
                <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجود</span>
              @endif
            </div>
          </div>
        </div>

        {{-- identity --}}
        <div class="col-12 col-md-4">
          <div class="kv">
            <div class="k">ملف الهوية</div>
            <div class="v">
              @if(!empty($files['identity']['exists']) && $files['identity']['exists'])
                <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجود</span>
                <div class="mt-2">
                  <a class="btn btn-outline-dark btn-sm file-btn" target="_blank" href="{{ $files['identity']['url'] }}">
                    <i class="bi bi-person-badge"></i> فتح
                  </a>
                </div>
              @else
                <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجود</span>
              @endif
            </div>
          </div>
        </div>

        {{-- attendance --}}
        <div class="col-12 col-md-4">
          <div class="kv">
            <div class="k">شهادة الحضور</div>
            <div class="v">
              @if(!empty($files['attendance']['exists']) && $files['attendance']['exists'])
                <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجودة</span>
                <div class="mt-2">
                  <a class="btn btn-outline-success btn-sm file-btn" target="_blank" href="{{ $files['attendance']['url'] }}">
                    <i class="bi bi-file-earmark-check"></i> فتح
                  </a>
                </div>
              @else
                <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجودة</span>
              @endif
            </div>
          </div>
        </div>

        {{-- certificate pdf --}}
        <div class="col-12 col-md-4">
          <div class="kv">
            <div class="k">الشهادة PDF</div>
            <div class="v">
              @if(!empty($files['certificate_pdf']['exists']) && $files['certificate_pdf']['exists'])
                <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجودة</span>
                <div class="mt-2">
                  <a class="btn btn-outline-danger btn-sm file-btn" target="_blank" href="{{ $files['certificate_pdf']['url'] }}">
                    <i class="bi bi-file-earmark-pdf"></i> فتح
                  </a>
                </div>
              @else
                <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجودة</span>
              @endif
            </div>
          </div>
        </div>

        {{-- certificate card --}}
        <div class="col-12 col-md-4">
          <div class="kv">
            <div class="k">الشهادة (كرتون)</div>
            <div class="v">
              @if(!empty($files['certificate_card']['exists']) && $files['certificate_card']['exists'])
                <span class="badge-soft success"><i class="bi bi-check2-circle"></i> موجودة</span>
                <div class="mt-2">
                  <a class="btn btn-outline-primary btn-sm file-btn" target="_blank" href="{{ $files['certificate_card']['url'] }}">
                    <i class="bi bi-file-earmark-image"></i> فتح
                  </a>
                </div>
              @else
                <span class="badge-soft gray"><i class="bi bi-x-circle"></i> غير موجودة</span>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
{{-- ===================== End Files Section ===================== --}}
<h6 class="mt-4 fw-bold">تفاصيل الدبلومات</h6>

@foreach($student->diplomas as $d)

<div class="glass-card mb-3 p-3">

  <h6>{{ $d->name }}</h6>

  <div class="kv">
    <div class="k">الحالة</div>
      <div class="v">
  {{ $d->pivot->status_ar }}
</div>


  </div>

<div class="kv">
  <div class="k">التقييم</div>
  <div class="v">

    <span 
      class="star-rating"
      data-rating="{{ $d->pivot->rating ?? 0 }}"
      style="font-size: 1.35rem; color: #f59e0b;">
    </span>

    <script>
      (function(){
        const el = document.currentScript.previousElementSibling;
        const r = parseInt(el.dataset.rating || 0);

        let stars = '';
        for(let i=1; i<=5; i++){
          stars += i <= r ? '★' : '☆';
        }
       el.innerText = stars + "  (" + r + "/5)";

      })();
    </script>

  </div>
</div>


  <div class="kv">
    <div class="k">تاريخ الانتهاء</div>
    <div class="v">{{ $d->pivot->ended_at ?? '-' }}</div>
  </div>

  <div class="kv">
    <div class="k">تسليم الشهادة</div>
    <div class="v">
      {{ $d->pivot->certificate_delivered ? 'نعم' : 'لا' }}
    </div>
  </div>

  <div class="kv">
    <div class="k">شهادة الحضور</div>
    <div class="v">
      @if($d->pivot->attendance_certificate_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->attendance_certificate_path) }}">
           فتح الملف
        </a>
      @else
        غير موجودة
      @endif
    </div>
  </div>

  <div class="kv">
    <div class="k">الشهادة PDF</div>
    <div class="v">
      @if($d->pivot->certificate_pdf_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->certificate_pdf_path) }}">
           فتح الملف
        </a>
      @else
        غير موجودة
      @endif
    </div>
  </div>

  <div class="kv">
    <div class="k">كرت الشهادة</div>
    <div class="v">
      @if($d->pivot->certificate_card_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->certificate_card_path) }}">
           فتح الملف
        </a>
      @else
        غير موجود
      @endif
    </div>
  </div>

  <div class="kv">
    <div class="k">ملاحظات</div>
    <div class="v">{{ $d->pivot->notes ?? '-' }}</div>
  </div>

</div>

@endforeach



                  </div>

                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="glass-card">
                <div class="p-3 p-md-4">
                  <h6 class="card-title mb-2">ملاحظات ورسالة</h6>
                  <div class="soft-divider"></div>

                  <div class="kv"><div class="k">ملاحظات</div><div class="v">{{ $p->notes ?? '-' }}</div></div>
                  <div class="kv"><div class="k">رسالة لاحقة للطالب</div><div class="v">{{ $p->message_to_send ?? '-' }}</div></div>
                </div>
              </div>
            </div>

          </div>
        @endif

      </div>
    </div>
  </div>

</div>
@endsection
