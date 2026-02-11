@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','خريطة الفروع')

@section('content')

<h4 class="mb-3">خريطة الفروع والطلاب</h4>

<div class="row g-3">
@foreach($branches as $b)
  <div class="col-md-4">
    <div class="module-card p-3">
      <p class="module-title">{{ $b->name }}</p>
      <p class="section-note">
        الطلاب: {{ $b->students()->count() }}
      </p>
    </div>
  </div>
@endforeach
</div>

@endsection
