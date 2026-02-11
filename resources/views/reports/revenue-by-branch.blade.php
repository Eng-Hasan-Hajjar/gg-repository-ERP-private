@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','الإيرادات حسب الفرع')

@section('content')

<h4 class="mb-3">الإيرادات حسب الفرع</h4>

<div class="glass-card p-3">
  <p class="text-muted">ملخص الإيرادات لكل فرع.</p>

  <table class="table table-sm">
    <thead>
      <tr>
        <th>الفرع</th>
        <th>الإيرادات</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>برلين</td>
        <td><span class="badge bg-success">12,000 €</span></td>
      </tr>
    </tbody>
  </table>
</div>

@endsection
