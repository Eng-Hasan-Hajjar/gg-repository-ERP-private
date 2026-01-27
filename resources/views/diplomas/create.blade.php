@extends('layouts.app')
@section('title','إضافة دبلومة')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">إضافة دبلومة</h5>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('diplomas.index') }}">رجوع</a>
    </div>

    <form method="POST" action="{{ route('diplomas.store') }}">
      @include('diplomas._form')
    </form>
  </div>
</div>
@endsection
