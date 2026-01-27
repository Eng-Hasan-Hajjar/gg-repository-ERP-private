@extends('layouts.app')
@section('title','تعديل طالب')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 fw-bold">تعديل بيانات الطالب الأساسية</h5>

    <form method="POST" action="{{ route('students.update',$student) }}">
      @include('students._form', ['student' => $student])
    </form>
  </div>
</div>
@endsection
