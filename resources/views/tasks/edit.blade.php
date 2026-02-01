@extends('layouts.app')
@php($activeModule='tasks')
@section('title','تعديل مهمة')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3"><i class="bi bi-pencil"></i> تعديل: {{ $task->title }}</h5>
    <form method="POST" action="{{ route('tasks.update',$task) }}">
      @include('tasks._form')
    </form>
  </div>
</div>
@endsection
