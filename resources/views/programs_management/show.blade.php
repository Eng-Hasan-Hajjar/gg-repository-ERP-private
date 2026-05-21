@extends('layouts.app')
@section('title', 'لوحة البرنامج')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">{{ $diploma->name }}</h4>
    <div class="text-muted small">لوحة متابعة شاملة للبرنامج</div>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('programs.management.edit', $diploma) }}" class="btn btn-namaa">
      تعديل البيانات
    </a>
    <a href="{{ route('programs.management.index') }}" class="btn btn-soft">رجوع</a>
  </div>
</div>

@php
  $fields = collect($record->getAttributes())->only([
    'market_study','trainer_assigned','contracts_ready','materials_ready',
    'sessions_uploaded','media_form_sent','direct_ads','content_ready',
    'opening_invitation','opening_snippets','carousel','designs','stories',
    'projects','attendance_certificate','university_certificate','cards_ready',
    'admin_session_1','admin_session_2','admin_session_3','evaluations_done',
  ]);
  $total    = $fields->count();
  $done     = $fields->filter(fn($v) => $v == 1)->count();
  $progress = $total > 0 ? round(($done / $total) * 100) : 0;
@endphp

{{-- Progress --}}
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <strong>نسبة إنجاز البرنامج</strong>
      <span class="fw-bold">{{ $progress }}%</span>
    </div>
    <div class="progress" style="height:10px;">
      <div class="progress-bar bg-success" style="width:{{ $progress }}%;"></div>
    </div>
  </div>
</div>

{{-- KPI --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">السعر</div>
      <div class="fw-bold fs-5">{{ $record->price ?? '-' }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">الطلاب المثبتين</div>
      <div class="fw-bold fs-5">{{ $record->confirmed_students ?? 0 }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">الخريجين</div>
      <div class="fw-bold fs-5">{{ $record->graduates_count ?? 0 }}</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm text-center p-3">
      <div class="small text-muted">مدة الدبلومة</div>
      <div class="fw-bold fs-5">{{ $record->duration_months ?? '-' }} شهر</div>
    </div>
  </div>
</div>

<div class="row g-4">

  {{-- قسم البرامج --}}
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم البرامج</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">دراسة السوق : <strong>{{ $record->market_study ? 'تم' : 'لا' }}</strong></li>
          <li class="list-group-item">المدرب : <strong>{{ $record->trainer?->full_name ?? '-' }}</strong></li>
          <li class="list-group-item">العقود : <strong>{{ $record->contracts_ready ? 'جاهزة' : 'غير جاهزة' }}</strong></li>
          <li class="list-group-item">المادة العلمية : <strong>{{ $record->materials_ready ? 'جاهزة' : 'غير جاهزة' }}</strong></li>
          <li class="list-group-item">رفع الجلسات : <strong>{{ $record->sessions_uploaded ? 'تم' : 'لا' }}</strong></li>
          <li class="list-group-item">مصدر الشهادة : <strong>{{ $record->certificate_source ?? '-' }}</strong></li>
          @if($record->details_file)
            <li class="list-group-item">
              <a href="{{ asset('storage/' . $record->details_file) }}" target="_blank"
                 class="btn btn-sm btn-outline-success">تحميل ملف التفاصيل</a>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </div>

  {{-- قسم الميديا --}}
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم الميديا</div>
      <div class="card-body small">
        @php
          $mediaShowFields = [
            'media_form_sent'    => ['label' => 'فورم الميديا',     'link' => 'media_form_sent_link'],
            'direct_ads'         => ['label' => 'إعلانات',          'link' => 'direct_ads_link'],
            'content_ready'      => ['label' => 'المحتوى',          'link' => 'content_ready_link'],
            'opening_invitation' => ['label' => 'دعوة افتتاحية',    'link' => 'opening_invitation_link'],
            'opening_snippets'   => ['label' => 'مقتطفات افتتاحية', 'link' => 'opening_snippets_link'],
            'carousel'           => ['label' => 'كاروسيل',          'link' => 'carousel_link'],
            'designs'            => ['label' => 'تصاميم',           'link' => 'designs_link'],
          ];
        @endphp
        <ul class="list-group list-group-flush">
          @foreach($mediaShowFields as $field => $info)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>{{ $info['label'] }} : <strong>{{ $record->$field ? 'تم' : 'لا' }}</strong></span>
              @if($record->$field && $record->{$info['link']})
                <a href="{{ $record->{$info['link']} }}" target="_blank"
                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                  <i class="bi bi-link-45deg"></i> الرابط
                </a>
              @endif
            </li>
          @endforeach

          {{-- ✅ الستوريات مع العداد --}}
          <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
              <span>
                ستوريات : <strong>{{ $record->stories ? 'تم' : 'لا' }}</strong>
                @if($record->stories_total)
                  @php
                    $sPct = $record->stories_total > 0
                      ? min(100, round(($record->stories_done ?? 0) / $record->stories_total * 100))
                      : 0;
                  @endphp
                  <span class="badge ms-1"
                        style="background:rgba(14,165,233,.12); color:#0369a1; font-size:.78rem;">
                    {{ $record->stories_done ?? 0 }} / {{ $record->stories_total }}
                  </span>
                @endif
              </span>
              @if($record->stories && $record->stories_link)
                <a href="{{ $record->stories_link }}" target="_blank"
                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                  <i class="bi bi-link-45deg"></i> الرابط
                </a>
              @endif
            </div>
            @if(($record->stories_total ?? 0) > 0)
              <div class="progress mt-2" style="height:5px;">
                <div class="progress-bar bg-info" style="width:{{ $sPct }}%;"></div>
              </div>
              <div class="text-muted mt-1" style="font-size:.72rem;">
                {{ $sPct }}% مكتملة
              </div>
            @endif
          </li>

        </ul>
      </div>
    </div>
  </div>

  {{-- قسم التسويق --}}
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">قسم التسويق</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">بداية الحملة : <strong>{{ $record->campaign_start ?? '-' }}</strong></li>
          <li class="list-group-item">نهاية الحملة : <strong>{{ $record->campaign_end ?? '-' }}</strong></li>
          <li class="list-group-item">ميزانية الحملة : <strong>{{ $record->campaign_budget ?? '-' }}</strong></li>
          <li class="list-group-item">
            المصروف الفعلي : <strong>{{ $record->campaign_spent ?? '-' }}</strong>
            @if($record->campaign_budget && $record->campaign_spent)
              @php
                $pct   = min(100, round(($record->campaign_spent / $record->campaign_budget) * 100));
                $color = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#10b981');
              @endphp
              <span class="badge ms-1" style="background:rgba(0,0,0,.06); color:#374151;">
                {{ number_format($record->campaign_spent, 0) }} / {{ number_format($record->campaign_budget, 0) }}
              </span>
              <div class="progress mt-1" style="height:5px;">
                <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $color }};"></div>
              </div>
              <div class="text-muted" style="font-size:.72rem;">{{ $pct }}% من الميزانية</div>
            @endif
          </li>
          <li class="list-group-item">مسؤول التواصل : <strong>{{ $record->communication_manager ?? '-' }}</strong></li>
        </ul>
      </div>
    </div>
  </div>

  {{-- الامتحانات --}}
  <div class="col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">الامتحانات</div>
      <div class="card-body small">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">البداية : <strong>{{ $record->start_date ?? '-' }}</strong></li>
          <li class="list-group-item">النهاية : <strong>{{ $record->end_date ?? '-' }}</strong></li>
          <li class="list-group-item">الامتحان النصفي : <strong>{{ $record->mid_exam ?? '-' }}</strong></li>
          <li class="list-group-item">الامتحان النهائي : <strong>{{ $record->final_exam ?? '-' }}</strong></li>
          <li class="list-group-item">مشاريع : <strong>{{ $record->projects ? 'نعم' : 'لا' }}</strong></li>
        </ul>
      </div>
    </div>
  </div>

  {{-- شؤون الطلاب --}}
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light fw-bold">شؤون الطلاب</div>
      <div class="card-body small">
        <div class="row g-3 mb-3">
          <div class="col-md-3">الحضور: <strong>{{ $record->attendance_certificate ? 'تم' : 'لا' }}</strong></div>
          <div class="col-md-3">شهادة الجامعة: <strong>{{ $record->university_certificate ? 'تم' : 'لا' }}</strong></div>
          <div class="col-md-3">البطاقات: <strong>{{ $record->cards_ready ? 'جاهزة' : 'لا' }}</strong></div>
        </div>

        @php
          $sessionShowFields = [
            'admin_session_1'  => ['label' => 'جلسة إدارية وتقييمية 1', 'link' => 'admin_session_1_link'],
            'admin_session_2'  => ['label' => 'جلسة إدارية وتقييمية 2', 'link' => 'admin_session_2_link'],
            'admin_session_3'  => ['label' => 'جلسة إدارية وتقييمية 3', 'link' => 'admin_session_3_link'],
            'evaluations_done' => ['label' => 'تقييمات بعد انتهاء البرنامج', 'link' => 'evaluations_done_link'],
          ];
        @endphp

        <div class="row g-3 mb-3">
          @foreach($sessionShowFields as $field => $info)
            <div class="col-md-3">
              <div style="background:rgba(248,250,252,.9); border:1px solid rgba(226,232,240,.9);
                          border-radius:10px; padding:10px 12px;">
                <div style="font-weight:800; font-size:13px; margin-bottom:4px;">{{ $info['label'] }}</div>
                <div style="font-size:13px;">
                  @if($record->$field)
                    <span class="badge bg-success">تم ✓</span>
                  @else
                    <span class="badge bg-secondary">لا</span>
                  @endif
                </div>
                @if($record->$field && $record->{$info['link']})
                  <div style="margin-top:6px;">
                    <a href="{{ $record->{$info['link']} }}" target="_blank"
                       style="font-size:11px; font-weight:800; color:#0ea5e9; text-decoration:none;">
                      <i class="bi bi-link-45deg"></i> فتح الرابط
                    </a>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-2">
          <strong>ملاحظات:</strong><br>
          {{ $record->notes ?? '-' }}
        </div>
      </div>
    </div>
  </div>

</div>

@endsection