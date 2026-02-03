@extends('layouts.app')
@section('title','تعديل طالب')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل الطالب</h5>
    <form method="POST" enctype="multipart/form-data" action="{{ route('students.update',$student) }}">
      @include('students._form',['student'=>$student])
    </form>
  </div>
</div>
@endsection
