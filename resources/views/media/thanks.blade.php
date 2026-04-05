@extends('media.app')
@section('title','شكراً لكم')

@section('content')

<div class="card shadow-sm p-5 text-center">
    <div class="mb-3">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
    </div>
    <h3 class="text-success fw-bold">شكراً لتقديم طلبكم</h3>
    <p class="text-muted mt-2">تم استلام الطلب وسيتم التواصل معكم قريباً.</p>
    <a href="{{ route('media.public.form') }}" class="btn btn-namaa mt-3">
        <i class="bi bi-plus-lg"></i> تقديم طلب جديد
    </a>
</div>

@endsection