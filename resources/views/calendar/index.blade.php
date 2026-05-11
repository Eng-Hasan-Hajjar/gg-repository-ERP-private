@extends('layouts.app')
@section('title', 'التقويم')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-calendar3 text-primary"></i> التقويم</h4>
        <div class="text-muted small">الجلسات — الحملات — أعياد الميلاد — التذكيرات</div>
    </div>
    <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="bi bi-plus-lg"></i> حدث جديد
    </button>
</div>

{{-- تنقل الأشهر --}}
<div class="d-flex align-items-center gap-3 mb-3">
    @php
        $prev = \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m');
        $next = \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m');
    @endphp
    <a href="{{ route('calendar.index', ['month' => $prev]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-right"></i>
    </a>
    <h5 class="mb-0 fw-bold">{{ $monthObj->locale('ar')->translatedFormat('F Y') }}</h5>
    <a href="{{ route('calendar.index', ['month' => $next]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-left"></i>
    </a>
    <a href="{{ route('calendar.index') }}" class="btn btn-outline-primary btn-sm">اليوم</a>
</div>

{{-- Legend --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    @foreach($types as $key => $type)
        <span class="badge rounded-pill" style="background:{{ $type['color'] }}; font-size:12px; padding:6px 12px;">
            <i class="bi {{ $type['icon'] }}"></i> {{ $type['label'] }}
        </span>
    @endforeach
</div>

{{-- التقويم --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-2">
        <div class="row g-0" style="display:grid; grid-template-columns: repeat(7, 1fr);">
            {{-- أيام الأسبوع --}}
            @foreach(['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'] as $day)
                <div class="text-center fw-bold py-2 border-bottom text-muted" style="font-size:12px;">{{ $day }}</div>
            @endforeach

            {{-- الأيام الفارغة في البداية --}}
            @for($i = 0; $i < $monthObj->copy()->startOfMonth()->dayOfWeek; $i++)
                <div class="border p-1" style="min-height:100px; background:#f8fafc;"></div>
            @endfor

            {{-- أيام الشهر --}}
            @for($d = 1; $d <= $monthObj->daysInMonth; $d++)
                @php
                    $dateStr  = $monthObj->format('Y-m') . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
                    $isToday  = $dateStr === now()->toDateString();
                    $dayEvents = $eventsByDay[$dateStr] ?? [];
                @endphp
                <div class="border p-1 {{ $isToday ? 'bg-primary bg-opacity-10 border-primary' : '' }}"
                     style="min-height:100px;">
                    <div class="fw-bold mb-1 {{ $isToday ? 'text-primary' : 'text-muted' }}" style="font-size:13px;">
                        {{ $d }}
                        @if($isToday)
                            <span class="badge bg-primary" style="font-size:9px;">اليوم</span>
                        @endif
                    </div>
                    @foreach($dayEvents as $ev)
                        <div class="rounded px-1 py-0 mb-1 d-flex align-items-center justify-content-between"
                             style="background:{{ $ev->color }}22; border-right:3px solid {{ $ev->color }}; font-size:11px; cursor:pointer;"
                             title="{{ $ev->description }}"
                             data-bs-toggle="modal"
                             data-bs-target="#eventDetailModal"
                             data-id="{{ $ev->id }}"
                             data-title="{{ $ev->title }}"
                             data-desc="{{ $ev->description }}"
                             data-type="{{ $types[$ev->type]['label'] }}"
                             data-date="{{ $ev->start_date->format('Y-m-d') }}"
                             data-time="{{ $ev->start_time ?? '' }}"
                             data-creator="{{ $ev->creator->name ?? '-' }}">
                            <span style="color:{{ $ev->color }}; font-weight:700;">
                                {{ Str::limit($ev->title, 15) }}
                            </span>
                            <form method="POST" action="{{ route('calendar.destroy', $ev) }}" class="d-inline"
                                  onsubmit="return confirm('حذف الحدث؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger" style="font-size:10px;" title="حذف">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>
</div>

{{-- قائمة الأحداث القادمة --}}
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header fw-bold bg-white border-0">
        <i class="bi bi-list-ul text-primary"></i> أحداث هذا الشهر
    </div>
    <div class="card-body p-0">
        @forelse($events as $ev)
        <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:36px; height:36px; background:{{ $ev->color }}22;">
                <i class="bi {{ $types[$ev->type]['icon'] }}" style="color:{{ $ev->color }};"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold" style="font-size:14px;">{{ $ev->title }}</div>
                <div class="text-muted small">
                    {{ $ev->start_date->format('d/m/Y') }}
                    @if($ev->start_time) — {{ $ev->start_time }} @endif
                    • {{ $types[$ev->type]['label'] }}
                </div>
            </div>
            <form method="POST" action="{{ route('calendar.destroy', $ev) }}"
                  onsubmit="return confirm('حذف الحدث؟')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
        @empty
            <div class="text-center text-muted py-4">لا توجد أحداث هذا الشهر</div>
        @endforelse
    </div>
</div>

{{-- Modal إضافة حدث --}}
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary"></i> إضافة حدث</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('calendar.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">عنوان الحدث *</label>
                        <input name="title" class="form-control" required placeholder="مثال: جلسة دبلومة X">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">النوع *</label>
                        <select name="type" class="form-select" id="eventTypeSelect">
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}">{{ $type['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">تاريخ البداية *</label>
                            <input type="date" name="start_date" class="form-control" required
                                   value="{{ now()->toDateString() }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">تاريخ النهاية</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">وقت البداية</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">وقت النهاية</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="تفاصيل إضافية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-lg"></i> حفظ
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal تفاصيل الحدث --}}
<div class="modal fade" id="eventDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>النوع:</strong> <span id="detailType"></span></p>
                <p><strong>التاريخ:</strong> <span id="detailDate"></span></p>
                <p><strong>الوقت:</strong> <span id="detailTime"></span></p>
                <p><strong>الوصف:</strong> <span id="detailDesc"></span></p>
                <p><strong>أضافه:</strong> <span id="detailCreator"></span></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-target="#eventDetailModal"]').forEach(function(el) {
        el.addEventListener('click', function() {
            document.getElementById('detailTitle').textContent   = this.dataset.title;
            document.getElementById('detailType').textContent    = this.dataset.type;
            document.getElementById('detailDate').textContent    = this.dataset.date;
            document.getElementById('detailTime').textContent    = this.dataset.time || '—';
            document.getElementById('detailDesc').textContent    = this.dataset.desc || '—';
            document.getElementById('detailCreator').textContent = this.dataset.creator;
        });
    });
});
</script>

@endsection