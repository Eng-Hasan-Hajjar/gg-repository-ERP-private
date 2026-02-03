@extends('layouts.app')
@section('title','CRM - تعديل عميل محتمل')

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-body">
    <h5 class="fw-bold mb-3">تعديل العميل المحتمل</h5>
    <form method="POST" action="{{ route('leads.update',$lead) }}">
      @include('crm.leads._form',['lead'=>$lead])
    </form>
  </div>
</div>
@endsection
