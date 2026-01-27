@extends('layouts.app')
@section('title','تصنيف جديد')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة تصنيف جديد</h5>
    <form method="POST" action="{{ route('asset-categories.store') }}">
      @include('asset_categories._form')
    </form>
  </div>
</div>
@endsection
