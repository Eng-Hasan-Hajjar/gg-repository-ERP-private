@extends('layouts.app')
@section('title','تعديل أصل')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل الأصل</h5>
    <form method="POST" action="{{ route('assets.update',$asset) }}" enctype="multipart/form-data">
      @include('assets._form', ['asset'=>$asset])
    </form>
  </div>
</div>
@endsection
