@extends('layouts.app')
@php($activeModule = 'tasks')
@section('title', 'تفاصيل مهمة')

@section('content')
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-1 fw-bold">{{ $task->title }}</h4>
      <div class="text-muted fw-semibold">
        الفرع: <b>{{ $task->branch->name ?? '-' }}</b>
        — المسند له: <b>{{ $task->assignee->full_name ?? '-' }}</b>
        — أولوية: <b>{{ $task->priority }}</b>
        — حالة: <b>{{ $task->status }}</b>
      </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
      @if(auth()->user()?->hasPermission('edit_tasks'))
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
          <i class="bi bi-pencil"></i> تعديل
        </a>
      @endif


      @if(auth()->user()?->hasPermission('complete_tasks'))
        <form method="POST" action="{{ route('tasks.quickStatus', $task) }}">
          @csrf
          <input type="hidden" name="status" value="done">
          <button class="btn btn-success rounded-pill px-4 fw-bold">
            <i class="bi bi-check2-circle"></i> تعليم كمنجزة
          </button>
        </form>
      @endif
      @if(auth()->user()?->hasPermission('delete_tasks'))
        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('حذف المهمة؟');">
          @csrf @method('DELETE')
          <button class="btn btn-outline-danger rounded-pill px-4 fw-bold">
            <i class="bi bi-trash"></i> حذف
          </button>
        </form>
      @endif
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-2">الوصف</h6>
          <div class="text-muted fw-semibold" style="line-height:1.9">
            {{ $task->description ?? '—' }}
          </div>
          <hr>
          <div class="small text-muted fw-semibold">
            تاريخ الاستحقاق: <b>{{ $task->due_date?->format('Y-m-d') ?? '-' }}</b>
            — أُنشئت بواسطة: <b>{{ $task->creator->name ?? '-' }}</b>
            — آخر تحديث: <b>{{ $task->updated_at->format('Y-m-d H:i') }}</b>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3">تغيير الحالة</h6>
          <form method="POST" action="{{ route('tasks.quickStatus', $task) }}" class="d-flex gap-2">
            @csrf
            <select name="status" class="form-select">
              @foreach(['todo', 'in_progress', 'done', 'blocked', 'archived'] as $s)
                <option value="{{ $s }}" @selected($task->status == $s)>{{ $s }}</option>
              @endforeach
            </select>
            <button class="btn btn-namaa fw-bold">تحديث</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection