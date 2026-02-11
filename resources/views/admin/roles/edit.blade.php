@extends('layouts.app')
@php($activeModule = 'users')

@section('title','تعديل دور')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل الدور</h5>

    <form method="POST" action="{{ route('admin.roles.update',$role) }}">
      @method('PUT')
      @include('admin.roles._form')
    </form>
  </div>
</div>
@endsection
