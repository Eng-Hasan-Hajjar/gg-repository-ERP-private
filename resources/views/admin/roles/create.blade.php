@extends('layouts.app')
@php($activeModule = 'users')

@section('title','إضافة دور')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة دور جديد</h5>

    <form method="POST" action="{{ route('admin.roles.store') }}">
      @include('admin.roles._form')
    </form>
  </div>
</div>
@endsection
