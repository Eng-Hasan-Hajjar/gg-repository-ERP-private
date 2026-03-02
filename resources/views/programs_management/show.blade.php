@extends('layouts.app')
@section('title','لوحة البرنامج')

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

    <a href="{{ route('programs.management.edit',$diploma) }}"
       class="btn btn-namaa">
        تعديل البيانات
    </a>
</div>

@php
$fields = collect($record->getAttributes())
    ->only([
        'market_study','trainer_assigned','contracts_ready',
        'materials_ready','sessions_uploaded','media_form_sent',
        'direct_ads','content_ready','opening_invitation',
        'opening_snippets','carousel','designs','stories',
        'projects','attendance_certificate','university_certificate',
        'cards_ready','admin_session_1','admin_session_2',
        'admin_session_3','evaluations_done'
    ]);

$total = $fields->count();
$done = $fields->filter(fn($v) => $v == 1)->count();
$progress = $total > 0 ? round(($done/$total)*100) : 0;
@endphp

{{-- Progress Bar --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-2">
            <strong>نسبة إنجاز البرنامج</strong>
            <span class="fw-bold">{{ $progress }}%</span>
        </div>

        <div class="progress" style="height:10px;">
            <div class="progress-bar bg-success"
                 style="width: {{ $progress }}%">
            </div>
        </div>

    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-4">

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
            <div class="small text-muted">بداية الحملة</div>
            <div class="fw-bold fs-6">
                {{ $record->campaign_start ?? '-' }}
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

</div>

@endsection