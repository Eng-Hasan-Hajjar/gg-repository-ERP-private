@extends('layouts.app')
@section('title','طالب جديد')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 fw-bold">إضافة طالب (بيانات أولية)</h5>

    <form method="POST" action="{{ route('students.store') }}">
      @include('students._form')
    </form>
  </div>
</div>
@endsection
