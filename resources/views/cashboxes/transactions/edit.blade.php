@extends('layouts.app')
@php($activeModule='finance')
@section('title','تعديل حركة')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-pencil"></i> تعديل حركة</h5>
        <div class="text-muted fw-semibold">
          {{ $cashbox->name }} — <code>{{ $cashbox->code }}</code> — {{ $cashbox->currency }}
          — الحركة #{{ $transaction->id }}
        </div>
      </div>
      <a href="{{ route('cashboxes.transactions.index',$cashbox) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        رجوع
      </a>
    </div>

    <form method="POST" action="{{ route('cashboxes.transactions.update',[$cashbox,$transaction]) }}" enctype="multipart/form-data">
      @method('PUT')
      @include('cashboxes.transactions._form', ['transaction'=>$transaction])
    </form>
  </div>
</div>
@endsection
