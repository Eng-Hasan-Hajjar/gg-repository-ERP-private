@extends('layouts.app')
@section('title','تعديل امتحان')
@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل امتحان</h5>
    <form method="POST" action="{{ route('exams.update',$exam) }}">
      @include('exams._form',['exam'=>$exam])
    </form>
  </div>
</div>
@endsection

