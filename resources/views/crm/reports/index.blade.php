@extends('layouts.app')
@section('title','تقارير CRM')

@section('content')
<h4 class="fw-bold mb-3">تقارير CRM</h4>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="{{ route('crm.reports.index') }}">
  <div class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label fw-bold">من تاريخ</label>
      <input type="date" name="from" class="form-control" value="{{ $from }}">
    </div>
    <div class="col-md-3">
      <label class="form-label fw-bold">إلى تاريخ</label>
      <input type="date" name="to" class="form-control" value="{{ $to }}">
    </div>
    <div class="col-md-3 d-grid">
      <button class="btn btn-dark fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="row g-3 mb-3">
  <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><b>الإجمالي:</b> {{ $summary['total'] }}</div></div></div>
  <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><b>Converted:</b> {{ $summary['converted'] }}</div></div></div>
  <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><b>Pending:</b> {{ $summary['pending'] }}</div></div></div>
  <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><b>Lost:</b> {{ $summary['lost'] }}</div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>الفرع</th>
          <th>إجمالي Leads</th>
          <th>Converted</th>
          <th>نسبة التحويل</th>
        </tr>
      </thead>
      <tbody>
        @foreach($byBranch as $r)
          @php($rate = $r->total ? round(($r->converted / $r->total) * 100, 1) : 0)
          <tr>
            <td>{{ $r->branch->name ?? '—' }}</td>
            <td>{{ $r->total }}</td>
            <td>{{ $r->converted }}</td>
            <td>{{ $rate }}%</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
