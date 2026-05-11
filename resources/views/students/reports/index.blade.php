@extends('layouts.app')
@php($activeModule = 'students')
@section('title', 'تقارير الطلاب')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold">📊 تقارير الطلاب</h4>
        <div class="text-muted small">تصفية متقدمة + تصدير Excel احترافي</div>
    </div>
    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-right"></i> العودة للطلاب
    </a>
</div>

{{-- إحصائيات سريعة --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div>
            <div class="text-muted small">إجمالي الطلاب</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $stats['active'] }}</div>
            <div class="text-muted small">مستمرون في الدراسة</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-info">{{ $stats['confirmed'] }}</div>
            <div class="text-muted small">مُثبّتون</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning">{{ $stats['today'] }}</div>
            <div class="text-muted small">مضافون اليوم</div>
        </div>
    </div>
</div>

{{-- الفلاتر --}}
<form class="card card-body border-0 shadow-sm mb-3" method="GET">
    <div class="row g-2">
        <div class="col-12 col-md-3">
            <input name="search" value="{{ request('search') }}" class="form-control"
                   placeholder="بحث: الاسم / الرقم / الهاتف">
        </div>
        <div class="col-6 col-md-2">
            <select name="branch_id" class="form-select">
                <option value="">كل الفروع</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="diploma_id" class="form-select">
                <option value="">كل الدبلومات</option>
                @foreach($diplomas as $d)
                    <option value="{{ $d->id }}" @selected(request('diploma_id') == $d->id)>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">كل الحالات</option>
                @foreach($statusOptions as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-1 d-grid">
            <button class="btn btn-namaa fw-bold">تصفية</button>
        </div>
        <div class="col-12 col-md-2 d-grid">
            <a href="{{ route('students.reports.index') }}" class="btn btn-outline-secondary">إعادة تعيين</a>
        </div>
    </div>
</form>

{{-- أزرار التصدير --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-excel text-success"></i> تصدير التقارير — تشمل الفلاتر المطبّقة</h6>
        <div class="row g-2">

            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('students.reports.excel.list', request()->query()) }}"
                   class="btn btn-success w-100 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">قائمة الطلاب</div>
                        <div style="font-size:11px; opacity:.85;">الاسم، الهاتف، الفرع، الحالة</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('students.reports.excel.detail', request()->query()) }}"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#1E40AF; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">التفاصيل الكاملة</div>
                        <div style="font-size:11px; opacity:.85;">الجنسية، التولد، المستوى التعليمي...</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('students.reports.excel.diplomas', request()->query()) }}"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#6D28D9; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">حسب الدبلومة</div>
                        <div style="font-size:11px; opacity:.85;">كل طالب مع دبلوماته وحالتها</div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <a href="{{ route('students.reports.excel.crm', request()->query()) }}"
                   class="btn w-100 d-flex align-items-center gap-2"
                   style="background:#DC2626; color:#fff;">
                    <i class="bi bi-file-earmark-excel fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">بيانات CRM</div>
                        <div style="font-size:11px; opacity:.85;">المصدر، المرحلة، البلد، الاحتياج</div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- جدول المعاينة --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold border-0 pt-3">
        معاينة النتائج ({{ $students->total() }} طالب)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>الرقم الجامعي</th>
                    <th>الاسم</th>
                    <th>الفرع</th>
                    <th>الدبلومة</th>
                    <th>الحالة</th>
                    <th>تاريخ الإضافة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td><code>{{ $s->university_id }}</code></td>
                    <td class="fw-semibold">{{ $s->full_name }}</td>
                    <td>{{ $s->branch->name ?? '-' }}</td>
                    <td>
                        @foreach($s->diplomas->take(2) as $d)
                            <span class="badge bg-primary me-1">{{ $d->name }}</span>
                        @endforeach
                        @if($s->diplomas->count() > 2)
                            <span class="badge bg-secondary">+{{ $s->diplomas->count() - 2 }}</span>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $statusOptions[$s->status] ?? $s->status }}</span></td>
                    <td class="text-muted small">{{ $s->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $students->links() }}</div>

@endsection