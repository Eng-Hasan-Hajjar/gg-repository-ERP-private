@extends('layouts.app')
@section('title','امتحان جديد')
@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة امتحان</h5>
    <form method="POST" action="{{ route('exams.store') }}">
      @include('exams._form')
    </form>
  </div>
</div>
@endsection
