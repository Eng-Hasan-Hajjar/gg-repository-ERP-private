@extends('layouts.app')
@section('title','تعديل إضافة مورد بشري')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-pencil"></i> تعديل: {{ $employee->full_name }}</h5>
    <form method="POST" action="{{ route('employees.update',$employee) }}" enctype="multipart/form-data">
      @include('employees._form')
    </form>
  </div>
</div>
@endsection
