@extends('layouts.app')
@php($activeModule='tasks')
@section('title','مهام اليوم')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">مهام اليوم</h4>
    <div class="text-muted fw-semibold">إسناد مهام + متابعة التنفيذ + حالات وأولويات</div>
  </div>

  <a href="{{ route('tasks.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
    <i class="bi bi-plus-circle"></i> إضافة مهمة
  </a>
</div>

<form class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: عنوان/وصف">
      </div>

      <div class="col-6 col-md-2">
        <select name="branch_id" class="form-select">
          <option value="">الفرع (الكل)</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="assigned_to" class="form-select">
          <option value="">المسند له (الكل)</option>
          @foreach($employees as $e)
            <option value="{{ $e->id }}" @selected(request('assigned_to')==$e->id)>{{ $e->full_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="status" class="form-select">
          <option value="">الحالة (الكل)</option>
          @foreach(['todo','in_progress','done','blocked','archived'] as $s)
            <option value="{{ $s }}" @selected(request('status')==$s)>{{ $s }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="priority" class="form-select">
          <option value="">الأولوية (الكل)</option>
          @foreach(['low','medium','high','urgent'] as $p)
            <option value="{{ $p }}" @selected(request('priority')==$p)>{{ $p }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 d-grid">
        <button class="btn btn-dark fw-bold">تطبيق</button>
      </div>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>العنوان</th>
          <th>الفرع</th>
          <th>المسند له</th>
          <th>أولوية</th>
          <th>حالة</th>
          <th>الاستحقاق</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $t)
          <tr>
            <td>{{ $t->id }}</td>
            <td class="fw-bold">{{ $t->title }}</td>
            <td>{{ $t->branch->name ?? '-' }}</td>
            <td>{{ $t->assignee->full_name ?? '-' }}</td>
            <td><span class="badge bg-light text-dark border">{{ $t->priority }}</span></td>
            <td>
              <span class="badge bg-{{ $t->status=='done'?'success':($t->status=='blocked'?'danger':'secondary') }}">
                {{ $t->status }}
              </span>
            </td>
            <td>{{ $t->due_date?->format('Y-m-d') ?? '-' }}</td>
            <td class="text-end d-flex gap-1 justify-content-end flex-wrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('tasks.show',$t) }}"><i class="bi bi-eye"></i> عرض</a>
              <a class="btn btn-sm btn-outline-dark" href="{{ route('tasks.edit',$t) }}"><i class="bi bi-pencil"></i> تعديل</a>

              <form method="POST" action="{{ route('tasks.quickStatus',$t) }}">
                @csrf
                <input type="hidden" name="status" value="done">
                <button class="btn btn-sm btn-outline-success"><i class="bi bi-check2-circle"></i> تم</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">لا يوجد مهام</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $tasks->links() }}
</div>
@endsection
