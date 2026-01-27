@extends('layouts.app')
@php($activeModule='finance')
@section('title','تعديل صندوق')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-pencil"></i> تعديل: {{ $cashbox->name }}</h5>
    <form method="POST" action="{{ route('cashboxes.update',$cashbox) }}">
      @include('cashboxes._form')
    </form>
  </div>
</div>
@endsection
