@extends('layouts.app')
@section('title','تعديل تصنيف')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل التصنيف</h5>
    <form method="POST" action="{{ route('asset-categories.update',$item) }}">
      @include('asset_categories._form',['item'=>$item])
    </form>
  </div>
</div>
@endsection
