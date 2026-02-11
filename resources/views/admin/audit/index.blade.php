@extends('layouts.app')
@php($activeModule = 'audit')

@section('title','مركز التدقيق')

@section('content')

<h4 class="mb-3">مركز التدقيق (Audit Center)</h4>

<form method="GET" class="row g-2 mb-3">

  <div class="col-md-3">
    <select name="user_id" class="form-select">
      <option value="">— كل المستخدمين —</option>
      @foreach($users as $u)
        <option value="{{ $u->id }}" @selected(request('user_id')==$u->id)>
          {{ $u->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <select name="action" class="form-select">
      <option value="">— كل الإجراءات —</option>
      <option value="created" @selected(request('action')=='created')>إنشاء</option>
      <option value="updated" @selected(request('action')=='updated')>تعديل</option>
      <option value="deleted" @selected(request('action')=='deleted')>حذف</option>
      <option value="login" @selected(request('action')=='login')>تسجيل دخول</option>
      <option value="logout" @selected(request('action')=='logout')>تسجيل خروج</option>
    </select>
  </div>

  <div class="col-md-3">
    <select name="model" class="form-select">
      <option value="">— كل النماذج —</option>
      <option value="User" @selected(request('model')=='User')>المستخدم</option>
      <option value="Role" @selected(request('model')=='Role')>الدور</option>
      <option value="Permission" @selected(request('model')=='Permission')>الصلاحية</option>
    </select>
  </div>

  <div class="col-md-3">
    <button class="btn btn-primary w-100">تطبيق الفلترة</button>
  </div>
</form>

<table class="table table-bordered table-sm">
<thead>
<tr>
  <th>الوقت</th>
  <th>المستخدم</th>
  <th>الإجراء</th>
  <th>النموذج</th>
  <th>الوصف</th>
  <th>IP</th>
</tr>
</thead>

<tbody>
@forelse($logs as $log)
<tr>
  <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
  <td>{{ $log->user?->name ?? '—' }}</td>
  <td>
    <span class="badge bg-secondary">{{ $log->action }}</span>
  </td>
  <td>{{ $log->model }}</td>
  <td>{{ $log->description }}</td>
  <td>{{ $log->ip }}</td>
</tr>
@empty
<tr>
  <td colspan="6" class="text-center text-muted">لا توجد سجلات</td>
</tr>
@endforelse
</tbody>
</table>

{{ $logs->withQueryString()->links() }}

@endsection
