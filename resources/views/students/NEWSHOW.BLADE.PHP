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

@media (max-width:575px){
  .kv{grid-template-columns:1fr;}
}
</style>
@endpush

@section('content')

<!-- ===== رأس الصفحة ===== -->
<div class="page-head mb-3">
  <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">

    <div class="d-flex align-items-center gap-3">

      <div class="avatar">
        @if($files['photo']['exists'])
          <img src="{{ $files['photo']['url'] }}" class="w-100 h-100">
        @else
          <i class="bi bi-person fs-2 text-muted"></i>
        @endif
      </div>

      <div>
        <h4 class="fw-bold">{{ $student->full_name }}</h4>
        <div class="text-muted small">
          رقم جامعي: <code>{{ $student->university_id }}</code>
          — الفرع: <b>{{ $student->branch->name ?? '-' }}</b>
        </div>

        <div class="mt-2 d-flex gap-2 flex-wrap">
          @if($student->is_confirmed)
            <span class="badge-soft success">مثبّت</span>
          @else
            <span class="badge-soft gray">غير مثبّت</span>
          @endif

          <span class="badge-soft warn">الحالة: {{ $status_ar }}</span>
          <span class="badge-soft">دبلومات: {{ $student->diplomas->count() }}</span>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-dark btn-pill" href="{{ route('students.edit',$student) }}">
        تعديل أساسي
      </a>

      <a class="btn btn-namaa btn-pill" href="{{ route('students.profile.edit',$student) }}">
        الملف التفصيلي
      </a>

      @if($waLink)
        <a class="btn btn-success btn-pill" target="_blank" href="{{ $waLink }}">
          واتساب الطالب
        </a>
      @endif
    </div>
  </div>
</div>

<div class="row g-3">

<!-- ===== البيانات الأساسية ===== -->
<div class="col-12 col-lg-6">
  <div class="glass-card h-100">
    <div class="section-header">
      <i class="bi bi-person-vcard"></i>
      البيانات الأساسية
    </div>
    <div class="section-body">

      <div class="kv"><div class="k">الاسم</div><div class="v">{{ $student->first_name }}</div></div>
      <div class="kv"><div class="k">الكنية</div><div class="v">{{ $student->last_name }}</div></div>
      <div class="kv"><div class="k">الهاتف</div><div class="v">{{ $student->phone ?? '-' }}</div></div>
      <div class="kv"><div class="k">الفرع</div><div class="v">{{ $student->branch->name ?? '-' }}</div></div>
      <div class="kv"><div class="k">نوع الطالب</div><div class="v">{{ $mode_ar }}</div></div>
      <div class="kv"><div class="k">حالة الطالب</div><div class="v">{{ $status_ar }}</div></div>

    </div>
  </div>
</div>

<!-- ===== CRM ===== -->
<div class="col-12 col-lg-6">
  <div class="glass-card h-100">
    <div class="section-header">
      <i class="bi bi-headset"></i>
      بيانات CRM
    </div>
    <div class="section-body">

      @if(!$student->crmInfo)
        <div class="alert alert-info">لا يوجد بيانات CRM</div>
      @else
        <div class="kv"><div class="k">السكن</div><div class="v">{{ $student->crmInfo->residence }}</div></div>
        <div class="kv"><div class="k">العمل</div><div class="v">{{ $student->crmInfo->job ?? '-' }}</div></div>
        <div class="kv"><div class="k">البلد</div><div class="v">{{ $student->crmInfo->country }}</div></div>
        <div class="kv"><div class="k">المحافظة</div><div class="v">{{ $student->crmInfo->province }}</div></div>
      @endif

    </div>
  </div>
</div>

<!-- ===== الملف التفصيلي ===== -->
<div class="col-12">
  <div class="glass-card">
    <div class="section-header">
      <i class="bi bi-file-earmark-person"></i>
      الملف التفصيلي
    </div>
    <div class="section-body">

      <div class="row g-3">

        <div class="col-lg-6">
          <div class="glass-card">
            <div class="section-header">بيانات شخصية</div>
            <div class="section-body">
              <div class="kv"><div class="k">الاسم بالعربي</div><div class="v">{{ $p->arabic_full_name ?? '-' }}</div></div>
              <div class="kv"><div class="k">الجنسية</div><div class="v">{{ $p->nationality ?? '-' }}</div></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="glass-card">
            <div class="section-header">معلومات تعليمية</div>
            <div class="section-body">
              <div class="kv"><div class="k">المستوى</div><div class="v">{{ $p->level ?? '-' }}</div></div>
              <div class="kv"><div class="k">التعليم</div><div class="v">{{ $p->education_level ?? '-' }}</div></div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- ===== تفاصيل الدبلومات ===== -->
<div class="col-12">
  <div class="glass-card">
    <div class="section-header">
      <i class="bi bi-mortarboard"></i>
      تفاصيل الدبلومات
    </div>
    <div class="section-body">

      @foreach($student->diplomas as $d)
      <div class="glass-card mb-3">
        <div class="section-header bg-light text-dark">
          {{ $d->name }}
        </div>
        <div class="section-body">

          <div class="kv">
            <div class="k">الحالة</div>
            <div class="v">{{ $d->pivot->status_ar }}</div>
          </div>

          <div class="kv">
            <div class="k">التقييم</div>
            <div class="v">
              ★★★☆☆ ({{ $d->pivot->rating ?? 0 }}/5)
            </div>
          </div>

          <div class="kv">
            <div class="k">تاريخ الانتهاء</div>
            <div class="v">{{ $d->pivot->ended_at ?? '-' }}</div>
          </div>

        </div>
      </div>
      @endforeach

    </div>
  </div>
</div>

</div>
@endsection
