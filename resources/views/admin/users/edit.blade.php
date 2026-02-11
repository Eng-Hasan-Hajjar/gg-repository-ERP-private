@extends('layouts.app')
@php($activeModule = 'users')

@section('title','تعديل مستخدم')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل المستخدم</h5>

    <form method="POST" action="{{ route('admin.users.update',$user) }}">
      @method('PUT')
      @include('admin.users._form')
    </form>
  </div>
</div>
@endsection
