@extends('layouts.app')
@section('title','تعديل فرع')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">تعديل فرع</h5>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('branches.index') }}">رجوع</a>
    </div>

    <form method="POST" action="{{ route('branches.update', $branch) }}">
      @include('branches._form', ['branch' => $branch])
    </form>
  </div>
</div>
@endsection
