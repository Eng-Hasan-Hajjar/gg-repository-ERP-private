@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','تنبيهات النظام')

@section('content')

<h4 class="mb-3">تنبيهات ومخاطر النظام</h4>

<div class="glass-card p-3">
  <ul>
    <li>⚠️ تأخير في الدوام بفرع برلين</li>
    <li>⚠️ انخفاض الإيرادات اليوم</li>
    <li>⚠️ مهام متأخرة</li>
  </ul>
</div>

@endsection
