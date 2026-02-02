@extends('layouts.app')
@section('title','CRM - تعديل Lead')

@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="mb-3 fw-bold">تعديل بيانات العميل المحتمل</h5>
    <form method="POST" action="{{ route('leads.update',$lead) }}">
      @include('crm.leads._form', ['lead'=>$lead])
    </form>
  </div>
</div>
@endsection
