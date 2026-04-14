@extends('layouts.app')
@section('title', 'لوحة البرنامج')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ $diploma->name }}
            </h4>
            <div class="text-muted small">
                لوحة متابعة شاملة للبرنامج
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('programs.management.edit', $diploma) }}" class="btn btn-namaa">
                تعديل البيانات
            </a>

            <a href="{{ route('programs.management.index') }}" class="btn btn-soft">
                رجوع
            </a>
        </div>
    </div>

    @php

        $fields = collect($record->getAttributes())
            ->only([
                'market_study',
                'trainer_assigned',
                'contracts_ready',
                'materials_ready',
                'sessions_uploaded',
                'media_form_sent',
                'direct_ads',
                'content_ready',
                'opening_invitation',
                'opening_snippets',
                'carousel',
                'designs',
                'stories',
                'projects',
                'attendance_certificate',
                'university_certificate',
                'cards_ready',
                'admin_session_1',
                'admin_session_2',
                'admin_session_3',
                'evaluations_done'
            ]);

        $total = $fields->count();
        $done = $fields->filter(fn($v) => $v == 1)->count();
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
                <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                </div>
            </div>

        </div>
    </div>


    {{-- KPI --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">السعر</div>
                <div class="fw-bold fs-5">
                    {{ $record->price ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">الطلاب المثبتين</div>
                <div class="fw-bold fs-5">
                    {{ $record->confirmed_students ?? 0 }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">الخريجين</div>
                <div class="fw-bold fs-5">
                    {{ $record->graduates_count ?? 0 }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="small text-muted">مدة الدبلومة</div>
                <div class="fw-bold fs-5">
                    {{ $record->duration_months ?? '-' }} شهر
                </div>
            </div>
        </div>

    </div>


    {{-- معلومات البرنامج --}}
    <div class="row g-4">

        {{-- البرامج --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم البرامج
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            دراسة السوق :
                            <strong>{{ $record->market_study ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">

                            المدرب :

                            <strong>

                                {{ $record->trainer?->full_name ?? '-' }}

                            </strong>

                        </li>

                        <li class="list-group-item">
                            العقود :
                            <strong>{{ $record->contracts_ready ? 'جاهزة' : 'غير جاهزة' }}</strong>
                        </li>

                        <li class="list-group-item">
                            المادة العلمية :
                            <strong>{{ $record->materials_ready ? 'جاهزة' : 'غير جاهزة' }}</strong>
                        </li>

                        <li class="list-group-item">
                            رفع الجلسات :
                            <strong>{{ $record->sessions_uploaded ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            مصدر الشهادة :
                            <strong>{{ $record->certificate_source ?? '-' }}</strong>
                        </li>

                        @if($record->details_file)

                            <a href="{{ asset('storage/' . $record->details_file) }}" target="_blank"
                                class="btn btn-sm btn-outline-success">

                                تحميل ملف التفاصيل

                            </a>

                        @else

                            <span>-</span>

                        @endif

                    </ul>

                </div>
            </div>
        </div>


        {{-- الميديا --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم الميديا
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            فورم الميديا :
                            <strong>{{ $record->media_form_sent ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            إعلانات :
                            <strong>{{ $record->direct_ads ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            المحتوى :
                            <strong>{{ $record->content_ready ? 'جاهز' : 'غير جاهز' }}</strong>
                        </li>

                        <li class="list-group-item">
                            دعوة افتتاحية :
                            <strong>{{ $record->opening_invitation ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            كاروسيل :
                            <strong>{{ $record->carousel ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            تصاميم :
                            <strong>{{ $record->designs ? 'تم' : 'لا' }}</strong>
                        </li>

                        <li class="list-group-item">
                            ستوريات :
                            <strong>{{ $record->stories ? 'تم' : 'لا' }}</strong>
                        </li>

                    </ul>

                </div>
            </div>
        </div>


        {{-- التسويق --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    قسم التسويق
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            بداية الحملة :
                            <strong>{{ $record->campaign_start ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            نهاية الحملة :
                            <strong>{{ $record->campaign_end ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            ميزانية الحملة :
                            <strong>{{ $record->campaign_budget ?? '-' }}</strong>
                        </li>

                    </ul>

                </div>
            </div>
        </div>


        {{-- الامتحانات --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-light fw-bold">
                    الامتحانات
                </div>

                <div class="card-body small">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item">
                            البداية :
                            <strong>{{ $record->start_date ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            النهاية :
                            <strong>{{ $record->end_date ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            الامتحان النصفي :
                            <strong>{{ $record->mid_exam ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            الامتحان النهائي :
                            <strong>{{ $record->final_exam ?? '-' }}</strong>
                        </li>

                        <li class="list-group-item">
                            مشاريع :
                            <strong>{{ $record->projects ? 'نعم' : 'لا' }}</strong>
                        </li>

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
                        <div class="col-md-3">
                            الحضور: <strong>{{ $record->attendance_certificate ? 'تم' : 'لا' }}</strong>
                        </div>
                        <div class="col-md-3">
                            شهادة الجامعة: <strong>{{ $record->university_certificate ? 'تم' : 'لا' }}</strong>
                        </div>
                        <div class="col-md-3">
                            البطاقات: <strong>{{ $record->cards_ready ? 'جاهزة' : 'لا' }}</strong>
                        </div>
                    </div>

                    {{-- الجلسات والتقييمات مع الروابط --}}
                    @php
                        $sessionShowFields = [
                            'admin_session_1' => ['label' => 'جلسة ادارية و تقييمية  1', 'link' => 'admin_session_1_link'],
                            'admin_session_2' => ['label' => 'جلسة ادارية و تقييمية  2', 'link' => 'admin_session_2_link'],
                            'admin_session_3' => ['label' => 'جلسة ادارية و تقييمية  3', 'link' => 'admin_session_3_link'],
                            'evaluations_done' => ['label' => 'جلسة ادارية و تقييمية', 'link' => 'evaluations_done_link'],
                        ];
                      @endphp

                    <div class="row g-3 mb-3">
                        @foreach($sessionShowFields as $field => $info)
                            <div class="col-md-3">
                                <div
                                    style="background:rgba(248,250,252,.9); border:1px solid rgba(226,232,240,.9); border-radius:10px; padding:10px 12px;">
                                    <div style="font-weight:800; font-size:13px; margin-bottom:4px;">
                                        {{ $info['label'] }}
                                    </div>
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