@extends('layouts.app')
@section('title','إضافة إضافة مورد بشري')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-person-plus"></i> إضافة مورد بشري</h5>
    <form method="POST" action="{{ route('employees.store') }}">
      @include('employees._form')
    </form>
  </div>
</div>
@endsection
