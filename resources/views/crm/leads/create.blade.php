@extends('layouts.app')
@section('title','CRM - عميل محتمل جديد')

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة عميل محتمل</h5>
    <form method="POST" action="{{ route('leads.store') }}">
      @include('crm.leads._form')
    </form>
  </div>
</div>
@endsection
