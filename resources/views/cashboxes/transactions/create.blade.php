@extends('layouts.app')
@php($activeModule='finance')
@section('title','إضافة حركة')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-plus-circle"></i> إضافة حركة</h5>
        <div class="text-muted fw-semibold">
          {{ $cashbox->name }} — <code>{{ $cashbox->code }}</code> — {{ $cashbox->currency }}
        </div>
      </div>
      <a href="{{ route('cashboxes.transactions.index',$cashbox) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('cashboxes.transactions.store',$cashbox) }}" enctype="multipart/form-data">
      @include('cashboxes.transactions._form')
    </form>
  </div>
</div>
@endsection
