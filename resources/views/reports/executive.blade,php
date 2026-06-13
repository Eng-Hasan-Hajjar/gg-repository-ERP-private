@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','لوحة القيادة التنفيذية')

@section('content')

<h4 class="mb-3">لوحة القيادة التنفيذية</h4>

<div class="row g-3 mb-4">

  {{-- الإيرادات اليوم --}}
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">إيرادات اليوم</div>
      <div class="stat-value text-success">
        {{ number_format($data['executive']['revenue_today'],2) }} €
      </div>
    </div>
  </div>

  {{-- حالة النظام --}}
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">حالة النظام</div>
      <div class="stat-value">
        @if($data['executive']['health'] === 'online')
          <span class="badge bg-success">Online</span>
        @elseif($data['executive']['health'] === 'degraded')
          <span class="badge bg-warning">Degraded</span>
        @else
          <span class="badge bg-danger">Issues</span>
        @endif
      </div>
    </div>
  </div>

  {{-- إجمالي الطلاب --}}
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">إجمالي الطلاب</div>
      <div class="stat-value">
        {{ $data['cards'][0]['value'] ?? 0 }}
      </div>
    </div>
  </div>
</div>

{{-- آخر 5 عمليات --}}
<div class="glass-card p-3">
  <h6 class="card-title mb-2">آخر 5 عمليات في النظام</h6>
  <table class="table table-sm">
    <thead>
      <tr>
        <th>الوقت</th>
        <th>الإجراء</th>
        <th>النموذج</th>
        <th>الوصف</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data['executive']['latest_audit'] as $log)
      <tr>
        <td>{{ \Carbon\Carbon::parse($log['time'])->format('H:i') }}</td>
        <td>{{ $log['action'] }}</td>
        <td>{{ $log['model'] }}</td>
        <td>{{ $log['description'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="text-end mt-2">
    <a href="{{ route('admin.audit.index') }}" class="btn btn-soft">
      الذهاب إلى مركز التدقيق الكامل
    </a>
  </div>
</div>

@endsection
