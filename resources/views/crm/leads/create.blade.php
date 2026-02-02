@extends('layouts.app')
@section('title','CRM - إضافة Lead')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 fw-bold">إضافة عميل محتمل (Lead)</h5>
    <form method="POST" action="{{ route('leads.store') }}">
      @include('crm.leads._form')
    </form>
  </div>
</div>
@endsection
