@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','نمو الطلاب')

@section('content')

<h4 class="mb-3">نمو عدد الطلاب</h4>

<div class="glass-card p-3">
  <p class="text-muted">
    هذا التقرير يوضح تطور عدد الطلاب خلال الأشهر الماضية.
  </p>

  <table class="table table-sm">
    <thead>
      <tr>
        <th>الشهر</th>
        <th>عدد الطلاب</th>
      </tr>
    </thead>
    <tbody>
      {{-- سنملؤها لاحقًا ببيانات حقيقية --}}
      <tr>
        <td>يناير</td>
        <td><span class="badge bg-dark">120</span></td>
      </tr>
      <tr>
        <td>فبراير</td>
        <td><span class="badge bg-dark">150</span></td>
      </tr>
    </tbody>
  </table>
</div>

@endsection
