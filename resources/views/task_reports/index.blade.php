@extends('layouts.app')
@section('title', 'تقارير المهام')

@section('content')

  <style>
    .report-type-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 800;
      border: 1px solid;
    }

    .type-daily {
      background: rgba(14, 165, 233, .1);
      color: #0369a1;
      border-color: rgba(14, 165, 233, .25);
    }

    .type-weekly {
      background: rgba(99, 102, 241, .1);
      color: #4338ca;
      border-color: rgba(99, 102, 241, .25);
    }

    .type-monthly {
      background: rgba(16, 185, 129, .1);
      color: #047857;
      border-color: rgba(16, 185, 129, .25);
    }

    .quick-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 800;
      border: 1px solid rgba(226, 232, 240, .9);
      background: rgba(255, 255, 255, .85);
      color: #1e293b;
      text-decoration: none;
      transition: .15s ease;
    }

    .quick-btn:hover,
    .quick-btn.active {
      background: #0ea5e9;
      color: #fff;
      border-color: #0ea5e9;
    }

    .quick-btn.active {
      pointer-events: none;
    }

    .filter-card {
      background: rgba(255, 255, 255, .9);
      border: 1px solid rgba(226, 232, 240, .9);
      border-radius: 16px;
      padding: 18px 20px;
      margin-bottom: 16px;
    }

    .report-table {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
    }

    .report-table thead th {
      background: rgba(248, 250, 252, .9);
      border-bottom: 1px solid rgba(226, 232, 240, .9);
      padding: 12px 16px;
      font-size: 12px;
      font-weight: 800;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: .4px;
      white-space: nowrap;
    }

    .report-table tbody tr {
      border-bottom: 1px solid rgba(226, 232, 240, .6);
      transition: background .12s;
    }

    .report-table tbody tr:hover {
      background: rgba(14, 165, 233, .04);
    }

    .report-table tbody td {
      padding: 13px 16px;
      font-size: 14px;
      vertical-align: middle;
    }

    .report-table tbody tr:last-child td {
      border-bottom: none;
    }

    .emp-cell {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .emp-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: rgba(14, 165, 233, .12);
      color: #0369a1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 900;
      flex-shrink: 0;
    }

    .emp-name {
      font-weight: 700;
      color: #1e293b;
    }

    .file-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 800;
      background: rgba(16, 185, 129, .1);
      color: #047857;
      border: 1px solid rgba(16, 185, 129, .25);
      text-decoration: none;
      transition: .15s;
    }

    .file-btn:hover {
      background: rgba(16, 185, 129, .2);
      color: #047857;
    }

    .no-file {
      color: #cbd5e1;
      font-size: 13px;
    }

    .clear-filters-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 800;
      background: rgba(239, 68, 68, .08);
      color: #b91c1c;
      border: 1px solid rgba(239, 68, 68, .2);
      text-decoration: none;
      transition: .15s;
    }

    .clear-filters-btn:hover {
      background: rgba(239, 68, 68, .15);
      color: #b91c1c;
    }

    /* Mobile card view */
    @media (max-width: 767px) {
      .desktop-table {
        display: none;
      }

      .mobile-cards {
        display: block;
      }

      .mobile-report-card {
        background: #fff;
        border: 1px solid rgba(226, 232, 240, .9);
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 10px;
      }

      .mobile-report-card .card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
      }
    }

    @media (min-width: 768px) {
      .mobile-cards {
        display: none;
      }

      .desktop-table {
        display: block;
      }
    }














    .report-modal .modal-content {
      border-radius: 18px;
      border: none;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
      overflow: hidden;
    }

    .report-modal .modal-header {
      background: linear-gradient(135deg, #0ea5e9, #2563eb);
      color: white;
      border: none;
      padding: 18px 24px;
    }

    .report-modal .modal-title {
      font-weight: 800;
      font-size: 18px;
    }

    .report-modal .info-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px 14px;
    }

    .report-modal .info-label {
      font-size: 12px;
      color: #64748b;
      font-weight: 700;
    }

    .report-modal .info-value {
      font-size: 14px;
      font-weight: 700;
      color: #1e293b;
    }

    .report-modal textarea {
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      padding: 12px;
    }

    .report-modal textarea:focus {
      border-color: #0ea5e9;
      box-shadow: 0 0 0 3px rgba(14, 165, 233, .15);
    }

    .report-modal .save-btn {
      background: #0ea5e9;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 700;
    }

    .report-modal .save-btn:hover {
      background: #0284c7;
    }

    .employee-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: #0ea5e9;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 900;
      font-size: 18px;
    }
  </style>

  {{-- ═══ Header ═══ --}}
  <div
    style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
    <div>
      <h4 style="font-weight:900; margin:0;">تقارير المهام</h4>
      <div style="font-size:13px; color:#64748b; margin-top:2px;">
        إجمالي التقارير:
        <b style="color:#1e293b;">{{ $reports->total() }}</b>
      </div>
    </div>
     <a href="{{ route('reports.task.excel') }}?{{ http_build_query(request()->all()) }}"
       class="btn btn-success rounded-pill px-4 fw-bold">
      <i class="bi bi-file-earmark-excel"></i> تصدير Excel
    </a>
    <a href="{{ route('reports.task.create') }}" class="btn btn-namaa rounded-pill px-4 fw-bold">
      <i class="bi bi-upload"></i> رفع تقرير
    </a>
  </div>

  {{-- ═══ Quick Filters ═══ --}}
  @php
    $quick = request('quick');
    $hasQuick = in_array($quick, ['today', 'week', 'month']);
  @endphp
  <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px;">
    <a href="?quick=today" class="quick-btn {{ $quick === 'today' ? 'active' : '' }}">
      <i class="bi bi-sun" style="font-size:13px"></i> اليوم
    </a>
    <a href="?quick=week" class="quick-btn {{ $quick === 'week' ? 'active' : '' }}">
      <i class="bi bi-calendar-week" style="font-size:13px"></i> هذا الأسبوع
    </a>
    <a href="?quick=month" class="quick-btn {{ $quick === 'month' ? 'active' : '' }}">
      <i class="bi bi-calendar-month" style="font-size:13px"></i> هذا الشهر
    </a>
  </div>

  {{-- ═══ Filter Card ═══ --}}
  @php
    $hasFilter = request()->hasAny(['search', 'report_type', 'employee_id', 'from', 'to']) &&
      array_filter(request()->only(['search', 'report_type', 'employee_id', 'from', 'to']));
  @endphp
  <div class="filter-card">
    <form method="GET">
      <div class="row g-2">

        <div class="col-6 col-md-3">
          <input type="text" name="search" class="form-control form-control-sm" placeholder="بحث بالعنوان..."
            value="{{ request('search') }}">
        </div>

        <div class="col-6 col-md-3">
          <select name="report_type" class="form-select form-select-sm">
            <option value="">كل الأنواع</option>
            <option value="daily" @selected(request('report_type') == 'daily')>يومي</option>
            <option value="weekly" @selected(request('report_type') == 'weekly')>أسبوعي</option>
            <option value="monthly" @selected(request('report_type') == 'monthly')>شهري</option>
          </select>
        </div>

        @if(auth()->user()->hasRole('super_admin'))
          <div class="col-6 col-md-3">
            <select name="employee_id" class="form-select form-select-sm">
              <option value="">كل الموظفين</option>
              @foreach($employees as $emp)
                <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>
                  {{ $emp->full_name }}
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <div class="col-6 col-md-3">
          <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}"
            placeholder="من تاريخ">
        </div>

        <div class="col-6 col-md-3">
          <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}"
            placeholder="إلى تاريخ">
        </div>

      </div>

      <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap; align-items:center;">
        <button class="btn btn-namaa btn-sm px-4 fw-bold">
          <i class="bi bi-search"></i> تصفية
        </button>

        {{-- زر المسح — يظهر فقط إذا كان هناك فلترة مفعّلة --}}
        @if($hasFilter || $hasQuick)
          <a href="{{ route('reports.task.index') }}" class="clear-filters-btn">
            <i class="bi bi-x-circle" style="font-size:13px"></i> مسح الفلترة
          </a>
        @endif
      </div>
    </form>
  </div>

  {{-- ═══ Desktop Table ═══ --}}
  <div class="desktop-table">
    <div style="background:#fff; border:1px solid rgba(226,232,240,.9); border-radius:16px; overflow:hidden;">
      <table class="report-table">
        <thead>
          <tr>
            <th><i class="bi bi-person" style="font-size:11px"></i> الموظف</th>
            <th><i class="bi bi-tag" style="font-size:11px"></i> نوع التقرير</th>
            <th><i class="bi bi-calendar3" style="font-size:11px"></i> التاريخ</th>
            <th><i class="bi bi-card-text" style="font-size:11px"></i> العنوان</th>
            <th><i class="bi bi-paperclip" style="font-size:11px"></i> الملف</th>
            <th> الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reports as $r)
            <tr>
              <td>
                <div class="emp-cell">
                  <div class="emp-avatar">
                    {{ mb_substr($r->employee->full_name ?? '؟', 0, 1, 'UTF-8') }}
                  </div>
                  <span class="emp-name">{{ $r->employee->full_name ?? '—' }}</span>
                </div>
              </td>
              <td>
                @php
                  $typeClass = match ($r->report_type) {
                    'daily' => 'type-daily',
                    'weekly' => 'type-weekly',
                    'monthly' => 'type-monthly',
                    default => 'type-daily',
                  };
                  $typeLabel = match ($r->report_type) {
                    'daily' => 'يومي',
                    'weekly' => 'أسبوعي',
                    'monthly' => 'شهري',
                    default => $r->report_type,
                  };
                @endphp
                <span class="report-type-badge {{ $typeClass }}">{{ $typeLabel }}</span>
              </td>
              <td style="color:#64748b; font-size:13px;">
                <i class="bi bi-calendar2" style="font-size:11px; margin-left:4px;"></i>
                {{ $r->report_date?->format('Y/m/d') ?? '—' }}
              </td>
              <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ $r->title ?? '—' }}
              </td>
              <td>
                @if($r->file_path)
                  <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank" class="file-btn">
                    <i class="bi bi-file-earmark-pdf" style="font-size:13px"></i> فتح
                  </a>
                @else
                  <span class="no-file">—</span>
                @endif
              </td>
              <td hidden>
                @if(auth()->user()->hasRole('super_admin') || auth()->user()->employee?->id === $r->employee_id)
                  <form method="POST" action="{{ route('reports.task.destroy', $r) }}"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm"
                      style="background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:4px 10px;">
                      <i class="bi bi-trash" style="font-size:12px"></i>
                    </button>
                  </form>
                @endif
              </td>


              <td style="display:flex; gap:6px;">

                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reportModal{{ $r->id }}">
                  <i class="bi bi-eye"></i>
                </button>

                @if(auth()->user()->hasRole('super_admin') || auth()->user()->employee?->id === $r->employee_id)
                  <form method="POST" action="{{ route('reports.task.destroy', $r) }}"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm"
                      style="background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:4px 10px;">
                      <i class="bi bi-trash" style="font-size:12px"></i>
                    </button>
                  </form>
                @endif

              </td>


            </tr>




            <!-- Modal -->
            <div class="modal fade report-modal" id="reportModal{{ $r->id }}" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                  <!-- Header -->
                  <div class="modal-header">
                    <div style="display:flex;align-items:center;gap:12px">

                      <div class="employee-avatar">
                        {{ mb_substr($r->employee->full_name ?? '؟', 0, 1, 'UTF-8') }}
                      </div>

                      <div>
                        <div style="font-size:13px;opacity:.8">تفاصيل التقرير</div>
                        <div style="font-weight:800">
                          {{ $r->employee->full_name ?? '—' }}
                        </div>
                      </div>

                    </div>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>


                  <!-- Body -->
                  <div class="modal-body p-4">

                    <!-- Info Grid -->
                    <div class="row g-3 mb-3">

                      <div class="col-md-4">
                        <div class="info-box">
                          <div class="info-label">نوع التقرير</div>
                          <div class="info-value">{{ $typeLabel }}</div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="info-box">
                          <div class="info-label">التاريخ</div>
                          <div class="info-value">
                            {{ $r->report_date?->format('Y/m/d') }}
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="info-box">
                          <div class="info-label">العنوان</div>
                          <div class="info-value">
                            {{ $r->title ?? '—' }}
                          </div>
                        </div>
                      </div>

                    </div>


                    <!-- Notes -->
                    <div class="mb-3">

                      <div class="info-label mb-2">
                        <i class="bi bi-journal-text"></i>
                        الملاحظات
                      </div>

                      <form method="POST" action="{{ route('reports.task.updateNotes', $r->id) }}">
                        @csrf
                        @method('PUT')

                        <textarea name="notes" rows="5" class="form-control">
                {{ $r->notes }}
                </textarea>

                        <div class="mt-3 d-flex justify-content-between align-items-center">

                          @if($r->file_path)
                            <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank" class="file-btn">
                              <i class="bi bi-file-earmark-pdf"></i>
                              فتح الملف
                            </a>
                          @endif

                          <button class="btn save-btn text-white">
                            <i class="bi bi-check-lg"></i>
                            حفظ التعديل
                          </button>

                        </div>

                      </form>

                    </div>

                  </div>

                </div>
              </div>
            </div>






          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:40px; color:#94a3b8;">
                <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                لا توجد تقارير مطابقة للفلترة المحددة
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ═══ Mobile Cards ═══ --}}
  <div class="mobile-cards">
    @forelse($reports as $r)
      @php
        $typeClass = match ($r->report_type) { 'daily' => 'type-daily', 'weekly' => 'type-weekly', 'monthly' => 'type-monthly', default => 'type-daily'};
        $typeLabel = match ($r->report_type) { 'daily' => 'يومي', 'weekly' => 'أسبوعي', 'monthly' => 'شهري', default => $r->report_type};
      @endphp
      <div class="mobile-report-card">
        <div class="card-top">
          <div class="emp-cell">
            <div class="emp-avatar">{{ mb_substr($r->employee->full_name ?? '؟', 0, 1, 'UTF-8') }}</div>
            <div>
              <div class="emp-name" style="font-size:14px;">{{ $r->employee->full_name ?? '—' }}</div>
              <div style="font-size:11px; color:#94a3b8;">
                {{ $r->report_date?->format('Y/m/d') }}
              </div>
            </div>
          </div>
          <span class="report-type-badge {{ $typeClass }}">{{ $typeLabel }}</span>
        </div>

        @if($r->title)
          <div style="font-size:13px; color:#1e293b; font-weight:700; margin-bottom:8px;">
            {{ $r->title }}
          </div>
        @endif

        <div style="display:flex; gap:8px; align-items:center; justify-content:space-between;">
          @if($r->file_path)
            <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank" class="file-btn">
              <i class="bi bi-file-earmark-pdf" style="font-size:13px"></i> فتح الملف
            </a>
          @else
            <span class="no-file" style="font-size:12px;">لا يوجد ملف</span>
          @endif

          @if(auth()->user()->hasRole('super_admin') || auth()->user()->employee?->id === $r->employee_id)
            <form method="POST" action="{{ route('reports.task.destroy', $r) }}" onsubmit="return confirm('حذف التقرير؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm"
                style="background:rgba(239,68,68,.08);color:#b91c1c;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:4px 10px;font-size:12px;">
                <i class="bi bi-trash"></i> حذف
              </button>
            </form>
          @endif
        </div>
      </div>
    @empty
      <div style="text-align:center; padding:40px 20px; color:#94a3b8;">
        <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px;"></i>
        لا توجد تقارير
      </div>
    @endforelse
  </div>

  {{-- ═══ Pagination ═══ --}}
  <div class="mt-3">
    {{ $reports->withQueryString()->links() }}
  </div>









@endsection