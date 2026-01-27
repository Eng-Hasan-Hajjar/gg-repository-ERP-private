@extends('layouts.app')
@php($activeModule='finance')
@section('title','إضافة صندوق')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle"></i> إضافة صندوق</h5>
    <form method="POST" action="{{ route('cashboxes.store') }}">
      @include('cashboxes._form')
    </form>
  </div>
</div>
@endsection
