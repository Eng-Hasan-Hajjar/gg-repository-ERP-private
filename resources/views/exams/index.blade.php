@extends('layouts.app')
@php($activeModule = 'exams')
@section('title', 'الامتحانات')

@section('content')

<style>
  .exam-stat {
    background: #fff;
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 14px;
    padding: 12px 14px;
    display: flex; align-items: center; gap: 10px;
  }
  .exam-stat-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
  }
  .exam-stat-val { font-size: 18px; font-weight: 900; line-height: 1.1; }
  .exam-stat-lbl { font-size: 11px; color: #94a3b8; font-weight: 700; margin-top: 2px; }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 14px;
  }
  @media (max-width: 575px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
  }

  .filter-card {
    background: #fff;
    border: 1px solid rgba(226,232,240,.9);
    border-radius: 16px;
    padding: 14px 16px;
    margin-bottom: 14px;
  }

  .exam-table { border-collapse: separate; border-spacing: 0; width: 100%; }
  .exam-table thead th {
    background: rgba(248,250,252,.95);
    border-bottom: 1px solid rgba(226,232,240,.9);
    padding: 10px 12px;
    font-size: 11px; font-weight: 800;
    color: #64748b; text-transform: uppercase; letter-spacing: .4px;
    white-space: nowrap;
  }
  .exam-table tbody tr { border-bottom: 1px solid rgba(226,232,240,.5); transition: background .1s; }
  .exam-table tbody tr:hover { background: rgba(14,165,233,.03); }
  .exam-table tbody td { padding: 11px 12px; font-size: 13px; vertical-align: middle; }
  .exam-table tbody tr:last-child td { border-bottom: none; }

  .type-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 800; border: 1px solid; white-space: nowrap;
  }
  .type-midterm   { background: rgba(99,102,241,.1);  color: #4338ca; border-color: rgba(99,102,241,.25); }
  .type-final     { background: rgba(239,68,68,.1);   color: #b91c1c; border-color: rgba(239,68,68,.25); }
  .type-practical { background: rgba(16,185,129,.1);  color: #047857; border-color: rgba(16,185,129,.25); }
  .type-quiz      { background: rgba(245,158,11,.1);  color: #b45309; border-color: rgba(245,158,11,.25); }
  .type-default   { background: rgba(100,116,139,.1); color: #475569; border-color: rgba(100,116,139,.25); }

  .score-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 8px;
    font-size: 12px; font-weight: 800;
    background: rgba(14,165,233,.08); color: #0369a1;
    border: 1px solid rgba(14,165,233,.2);
  }

  .info-chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 8px; border-radius: 8px; font-size: 11px;
    font-weight: 700; background: rgba(248,250,252,.9);
    border: 1px solid rgba(226,232,240,.9); color: #475569;
  }

  .clear-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 20px; font-size: 13px;
    font-weight: 800; background: rgba(239,68,68,.08);
    color: #b91c1c; border: 1px solid rgba(239,68,68,.2);
    text-decoration: none; transition: .15s;
  }
  .clear-btn:hover { background: rgba(239,68,68,.15); color: #b91c1c; }

  /* Desktop/Mobile toggle */
  @media (max-width: 767px) {
    .desktop-only { display: none !important; }

    .exam-mobile-card {
      background: #fff;
      border: 1px solid rgba(226,232,240,.9);
      border-radius: 14px;
      padding: 14px 15px;
      margin-bottom: 10px;
    }
    .card-header-row {
      display: flex; justify-content: space-between;
      align-items: flex-start; margin-bottom: 10px;
    }
    .info-row {
      display: flex; gap: 5px; flex-wrap: wrap; margin-bottom: 8px;
    }
    .card-actions {
      display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid rgba(226,232,240,.7);
    }
  }
  @media (min-width: 768px) {
    .mobile-only { display: none !important; }
  }
</style>

{{-- Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
  <div>
    <h4 style="font-weight:900; margin:0;">الامتحانات</h4>
    <div style="font-size:13px; color:#64748b;">
      إجمالي: <b style="color:#1e293b;">{{ $exams->total() }}</b> امتحان
    </div>
  </div>
  @if(auth()->user()?->hasPermission('create_exams'))
    <a href="{{ route('exams.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-plus-circle"></i> امتحان جديد
    </a>
  @endif
</div>

{{-- Quick Stats --}}
<div class="stats-grid">
  <div class="exam-stat">
    <div class="exam-stat-icon" style="background:rgba(99,102,241,.1); color:#4338ca;">
      <i class="bi bi-journal-check"></i>
    </div>
    <div>
      <div class="exam-stat-val" style="color:#4338ca;">{{ $examStats['total'] }}</div>
      <div class="exam-stat-lbl">إجمالي</div>
    </div>
  </div>
  <div class="exam-stat">
    <div class="exam-stat-icon" style="background:rgba(245,158,11,.1); color:#b45309;">
      <i class="bi bi-calendar-event"></i>
    </div>
    <div>
      <div class="exam-stat-val" style="color:#b45309;">{{ $examStats['upcoming'] }}</div>
      <div class="exam-stat-lbl">قادم</div>
    </div>
  </div>
  <div class="exam-stat">
    <div class="exam-stat-icon" style="background:rgba(16,185,129,.1); color:#047857;">
      <i class="bi bi-check2-all"></i>
    </div>
    <div>
      <div class="exam-stat-val text-success">{{ $examStats['done'] }}</div>
      <div class="exam-stat-lbl">منتهي</div>
    </div>
  </div>
  <div class="exam-stat">
    <div class="exam-stat-icon" style="background:rgba(14,165,233,.1); color:#0369a1;">
      <i class="bi bi-calendar-month"></i>
    </div>
    <div>
      <div class="exam-stat-val text-primary">{{ $examStats['this_month'] }}</div>
      <div class="exam-stat-lbl">هذا الشهر</div>
    </div>
  </div>
</div>

{{-- Filters --}}
<div class="filter-card">
  <form method="GET" action="{{ route('exams.index') }}">
    <div class="row g-2">
      <div class="col-12 col-sm-6 col-md-3">
        <input name="search" value="{{ request('search') }}"
               class="form-control form-control-sm" placeholder="بحث: اسم / كود الامتحان">
      </div>
      <div class="col-6 col-md-2">
        <select name="branch_id" class="form-select form-select-sm">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-2">
        <select name="diploma_id" class="form-select form-select-sm">
          <option value="">كل الدبلومات</option>
          @foreach($diplomas as $d)
            <option value="{{ $d->id }}" @selected(request('diploma_id')==$d->id)>
              {{ $d->name }} ({{ $d->code }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-2">
        <select name="trainer_id" class="form-select form-select-sm">
          <option value="">كل المدربين</option>
          @foreach($trainers as $t)
            <option value="{{ $t->id }}" @selected(request('trainer_id')==$t->id)>{{ $t->full_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-1">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
      </div>
      <div class="col-6 col-md-1">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
      </div>
      <div class="col-6 col-md-1">
        <button class="btn btn-namaa btn-sm w-100 fw-bold">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </div>

    @if($hasFilter)
      <div style="margin-top:10px;">
        <a href="{{ route('exams.index') }}" class="clear-btn">
          <i class="bi bi-x-circle" style="font-size:12px"></i> مسح الفلترة
        </a>
      </div>
    @endif
  </form>
</div>

{{-- Desktop Table --}}
<div class="desktop-only">
  <div style="background:#fff; border:1px solid rgba(226,232,240,.9); border-radius:16px; overflow:hidden;">
    <table class="exam-table">
      <thead>
        <tr>
          <th>التاريخ</th>
          <th>الامتحان</th>
          <th>النوع</th>
          <th>الدبلومة</th>
          <th>الفرع</th>
          <th>المدرب</th>
          <th>الدرجات</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($exams as $e)
          <tr>
            <td style="white-space:nowrap;">
              <div style="font-weight:800; color:#1e293b;">{{ $e->exam_date?->format('Y/m/d') ?? '—' }}</div>
              <div style="font-size:11px; color:#94a3b8;">{{ $e->exam_date?->diffForHumans() ?? '' }}</div>
            </td>
            <td>
              <div style="font-weight:800; color:#1e293b;">{{ $e->title }}</div>
              @if($e->code)
                <div style="font-size:11px; color:#94a3b8; font-family:monospace;">{{ $e->code }}</div>
              @endif
            </td>
            <td>
              <span class="type-badge {{ $e->type_color }}">{{ $e->type_label }}</span>
            </td>
            <td>
              <div style="font-weight:700; font-size:13px;">{{ $e->diploma->name ?? '—' }}</div>
              @if($e->diploma?->code)
                <div style="font-size:11px; color:#94a3b8;">{{ $e->diploma->code }}</div>
              @endif
            </td>
            <td style="font-weight:700;">{{ $e->branch->name ?? '—' }}</td>
            <td>{{ $e->trainer->full_name ?? '—' }}</td>
            <td>
              <span class="score-pill">
                <i class="bi bi-trophy" style="font-size:10px"></i>
                {{ $e->max_score }} / {{ $e->pass_score }}
              </span>
            </td>
            <td class="text-end">
              <div style="display:flex; gap:5px; justify-content:flex-end; flex-wrap:wrap;">
                @if(auth()->user()?->hasPermission('view_exams'))
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('exams.show', $e) }}">
                    <i class="bi bi-eye"></i>
                  </a>
                @endif
                @if(auth()->user()?->hasPermission('edit_exams'))
                  <a class="btn btn-sm btn-outline-dark" href="{{ route('exams.edit', $e) }}">
                    <i class="bi bi-pencil"></i>
                  </a>
                @endif
                @if(auth()->user()?->hasPermission('enter_grades'))
                  <a class="btn btn-sm btn-namaa" href="{{ route('exams.results.edit', $e) }}">
                    <i class="bi bi-card-checklist"></i> العلامات
                  </a>
                @endif
                <form action="{{ route('exams.destroy', $e) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('هل أنت متأكد من حذف الامتحان؟')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center; padding:40px; color:#94a3b8;">
              <i class="bi bi-journal-x" style="font-size:28px; display:block; margin-bottom:8px;"></i>
              لا توجد امتحانات مطابقة
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Mobile Cards --}}
<div class="mobile-only">
  @forelse($exams as $e)
    <div class="exam-mobile-card">
      <div class="card-header-row">
        <div style="flex:1; min-width:0;">
          <div style="font-weight:900; font-size:15px; color:#1e293b; margin-bottom:2px;">{{ $e->title }}</div>
          @if($e->code)
            <div style="font-size:11px; color:#94a3b8; font-family:monospace;">{{ $e->code }}</div>
          @endif
        </div>
        <span class="type-badge {{ $e->type_color }}" style="margin-right:8px; flex-shrink:0;">
          {{ $e->type_label }}
        </span>
      </div>

      <div class="info-row">
        <span class="info-chip">
          <i class="bi bi-calendar3" style="font-size:10px"></i>
          {{ $e->exam_date?->format('Y/m/d') ?? '—' }}
        </span>
        <span class="info-chip">
          <i class="bi bi-building" style="font-size:10px"></i>
          {{ $e->branch->name ?? '—' }}
        </span>
        <span class="info-chip">
          <i class="bi bi-mortarboard" style="font-size:10px"></i>
          {{ $e->diploma->name ?? '—' }}
        </span>
        @if($e->trainer)
          <span class="info-chip">
            <i class="bi bi-person" style="font-size:10px"></i>
            {{ $e->trainer->full_name }}
          </span>
        @endif
        <span class="info-chip" style="background:rgba(14,165,233,.08); color:#0369a1; border-color:rgba(14,165,233,.2);">
          <i class="bi bi-trophy" style="font-size:10px"></i>
          {{ $e->max_score }} / {{ $e->pass_score }}
        </span>
      </div>

      <div class="card-actions">
        @if(auth()->user()?->hasPermission('view_exams'))
          <a href="{{ route('exams.show', $e) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye"></i> عرض
          </a>
        @endif
        @if(auth()->user()?->hasPermission('edit_exams'))
          <a href="{{ route('exams.edit', $e) }}" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-pencil"></i> تعديل
          </a>
        @endif
        @if(auth()->user()?->hasPermission('enter_grades'))
          <a href="{{ route('exams.results.edit', $e) }}" class="btn btn-sm btn-namaa">
            <i class="bi bi-card-checklist"></i> العلامات
          </a>
        @endif
        <form action="{{ route('exams.destroy', $e) }}" method="POST" class="d-inline"
              onsubmit="return confirm('حذف الامتحان؟')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    </div>
  @empty
    <div style="text-align:center; padding:40px 20px; color:#94a3b8;">
      <i class="bi bi-journal-x" style="font-size:28px; display:block; margin-bottom:8px;"></i>
      لا توجد امتحانات
    </div>
  @endforelse
</div>

<div class="mt-3">
  {{ $exams->withQueryString()->links() }}
</div>

@endsection