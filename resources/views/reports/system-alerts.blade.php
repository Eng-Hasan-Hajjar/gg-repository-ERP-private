@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','تنبيهات النظام')

@section('content')

<div class="mb-4">
    <div class="page-title">تنبيهات النظام</div>
    <div class="page-subtitle">
        تحليل ذكي لحالة النظام اعتمادًا على البيانات الفعلية.
    </div>
</div>

<div class="clean-card">

    @forelse($alerts as $a)

        <div class="alert alert-{{ $a['type'] }} d-flex align-items-center gap-2">
            <i class="bi {{ $a['icon'] }}"></i>
            {{ $a['message'] }}
        </div>

    @empty

        <div class="text-success fw-bold">
            <i class="bi bi-check-circle"></i>
            لا توجد أي مخاطر حالياً — النظام يعمل بشكل طبيعي
        </div>

    @endforelse

</div>

@endsection
