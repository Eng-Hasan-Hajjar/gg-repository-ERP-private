@extends('layouts.app')
@php($activeModule = 'tasks')
@section('title', 'مهام اليوم')

@section('content')

  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">مهام اليوم</h4>
      <div class="text-muted fw-semibold">إسناد مهام + متابعة التنفيذ + حالات وأولويات</div>
    </div>
    @if(auth()->user()?->hasPermission('create_tasks'))
      <a href="{{ route('tasks.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
        <i class="bi bi-plus-circle"></i> إضافة مهمة
      </a>
    @endif
  </div>

  {{-- فلاتر --}}
  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">

        <div class="col-12 col-md-4">
          <input name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="بحث: عنوان/وصف">
        </div>

        <div class="col-6 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">الفرع (الكل)</option>
            @foreach($branches as $b)
              <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="assigned_to" class="form-select">
            <option value="">المسند له (الكل)</option>
            @foreach($employees as $e)
              <option value="{{ $e->id }}" @selected(request('assigned_to') == $e->id)>
                {{ $e->full_name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <option value="todo"        @selected(request('status')=='todo')>قيد الانتظار</option>
            <option value="in_progress" @selected(request('status')=='in_progress')>قيد التنفيذ</option>
            <option value="done"        @selected(request('status')=='done')>منجز</option>
            <option value="blocked"     @selected(request('status')=='blocked')>موقوف</option>
            <option value="archived"    @selected(request('status')=='archived')>مؤرشف</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <select name="priority" class="form-select">
            <option value="">الأولوية (الكل)</option>
            <option value="low"    @selected(request('priority')=='low')>منخفضة</option>
            <option value="medium" @selected(request('priority')=='medium')>متوسطة</option>
            <option value="high"   @selected(request('priority')=='high')>عالية</option>
            <option value="urgent" @selected(request('priority')=='urgent')>عاجلة</option>
          </select>
        </div>

        <div class="col-12 d-grid">
          <button class="btn btn-namaa fw-bold">تطبيق</button>
        </div>

      </div>
    </div>
  </form>

  {{-- الجدول --}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="hide-mobile">#</th>
            <th>العنوان</th>
            <th class="hide-mobile">الفرع</th>
            <th>المسند له</th>
            <th class="hide-mobile">الأولوية</th>
            <th>الحالة</th>
            <th class="hide-mobile">الاستحقاق</th>
            <th class="text-end">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tasks as $t)
            <tr>
              <td class="hide-mobile">{{ $t->id }}</td>
              <td class="fw-bold">{{ $t->title }}</td>
              <td class="hide-mobile">{{ $t->branch->name ?? '-' }}</td>
              <td>{{ $t->assignee->full_name ?? '-' }}</td>

              <td class="hide-mobile">
                <span class="badge bg-{{ $t->priority_color }}-subtle text-{{ $t->priority_color }}-emphasis border border-{{ $t->priority_color }}-subtle fw-bold"
                      style="font-size:12px; padding:5px 10px;">
                  {{ $t->priority_label }}
                </span>
              </td>

              <td>
                <span class="badge bg-{{ $t->status_color }}-subtle text-{{ $t->status_color }}-emphasis border border-{{ $t->status_color }}-subtle fw-bold"
                      style="font-size:12px; padding:5px 10px;">
                  {{ $t->status_label }}
                </span>
              </td>

              <td class="hide-mobile">{{ $t->due_date?->format('Y-m-d') ?? '-' }}</td>

              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end flex-wrap">
                  @if(auth()->user()?->hasPermission('view_tasks'))
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('tasks.show', $t) }}">
                      <i class="bi bi-eye"></i> عرض
                    </a>
                  @endif
                  @if(auth()->user()?->hasPermission('edit_tasks'))
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('tasks.edit', $t) }}">
                      <i class="bi bi-pencil"></i> تعديل
                    </a>
                  @endif
                  @if(auth()->user()?->hasPermission('complete_tasks'))
                    <form method="POST" action="{{ route('tasks.quickStatus', $t) }}">
                      @csrf
                      <input type="hidden" name="status" value="done">
                      <button class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check2-circle"></i> تم
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">لا يوجد مهام</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $tasks->links() }}
  </div>

@endsection