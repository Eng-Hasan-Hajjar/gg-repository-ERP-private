@extends('layouts.app')
@php($activeModule='tasks')
@section('title','إضافة مهمة')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> إضافة مهمة</h5>
    <form method="POST" action="{{ route('tasks.store') }}">
      @include('tasks._form')
    </form>
  </div>
</div>
@endsection
