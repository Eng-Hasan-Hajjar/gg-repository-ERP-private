@extends('layouts.app')
@section('title','إضافة أصل')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة أصل جديد</h5>
    <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
      @include('assets._form')
    </form>
  </div>
</div>
@endsection
