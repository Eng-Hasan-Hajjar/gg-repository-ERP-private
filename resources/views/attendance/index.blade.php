@extends('layouts.app')

@section('title', 'الدوام والحضور')

@section('content')
  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
    <div>
      <h4 class="mb-0 fw-bold">الدوام والحضور</h4>
      <div class="text-muted fw-semibold">سجل الدخول/الخروج + استراحة + تأخير + ساعات</div>
    </div>
  </div>

  <style>
    .break-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .break-badge.on-break {
      background: #fef3c7;
      color: #92400e;
      animation: pulse-break 1.5s infinite;
    }

    .break-badge.break-done {
      background: #f1f5f9;
      color: #64748b;
    }

    @keyframes pulse-break {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: .6;
      }
    }

    .btn-break-start {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fcd34d;
      font-weight: 600;
    }

    .btn-break-start:hover {
      background: #fde68a;
      color: #78350f;
    }

    .btn-break-end {
      background: #dbeafe;
      color: #1e40af;
      border: 1px solid #93c5fd;
      font-weight: 600;
    }

    .btn-break-end:hover {
      background: #bfdbfe;
      color: #1e3a8a;
    }




    .timeline li {
      padding: 6px 0;
      border-left: 3px solid #e5e7eb;
      padding-left: 12px;
      margin-left: 5px;
      position: relative;
    }

    .timeline li::before {
      content: '';
      width: 10px;
      height: 10px;
      background: #3b82f6;
      border-radius: 50%;
      position: absolute;
      left: -6px;
      top: 10px;
    }
  </style>

  @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('manager_attendance'))
    <form method="POST" action="{{ route('attendance.generateWeek') }}" class="card border-0 shadow-sm mb-3">
      @csrf
      <div class="card-body py-2">
        <div class="row align-items-end g-2">
          <div class="col-md-3">
            <label class="fw-bold small mb-1">بداية الأسبوع</label>
            <input type="date" name="week_start" class="form-control" required
              value="{{ now()->startOfWeek()->format('Y-m-d') }}">
          </div>
          <div class="col-md-auto">
            <button class="btn btn-namaa fw-bold mt-3">
              <i class="bi bi-magic"></i> توليد سجلات الأسبوع
            </button>
          </div>
        </div>
      </div>
    </form>

    <div class="mb-2 text-muted small">
      عدد السجلات: {{ $records->total() }}
    </div>

    <div class="d-flex flex-wrap gap-2 mt-2 mb-3">
      <a href="{{ route('attendance.index', [
        'from' => now()->startOfWeek()->toDateString(),
        'to' => now()->endOfWeek()->toDateString()
      ]) }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-calendar-week"></i> هذا الأسبوع
      </a>
      <a href="{{ route('attendance.index', [
        'from' => now()->startOfMonth()->toDateString(),
        'to' => now()->endOfMonth()->toDateString()
      ]) }}" class="btn btn-sm btn-outline-success">
        <i class="bi bi-calendar-month"></i> هذا الشهر
      </a>
      <a href="{{ route('attendance.index', [
        'from' => now()->subMonths(3)->startOfMonth()->toDateString(),
        'to' => now()->endOfMonth()->toDateString()
      ]) }}" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-calendar-range"></i> آخر 3 أشهر
      </a>
    </div>

    <form class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="row g-2">
          <div class="col-6 col-md-2">
            <input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث: اسم/كود">
          </div>
          <div class="col-6 col-md-2">
            <select name="branch_id" class="form-select">
              <option value="">الفرع (الكل)</option>
              @foreach($branches as $b)
                <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <select name="employee_id" class="form-select">
              <option value="">الموظف (الكل)</option>
              @foreach($employees as $e)
                <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ $e->full_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <select name="status" class="form-select">
              <option value="">الحالة (الكل)</option>
              @foreach(['scheduled', 'present', 'late', 'absent', 'off', 'leave'] as $s)
                <option value="{{ $s }}" @selected(request('status') == $s)>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
          </div>
          <div class="col-6 col-md-2">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
          </div>
          <div class="col-6 col-md-6 d-grid">
            <button class="btn btn-namaa fw-bold"><i class="bi bi-funnel"></i> تطبيق الفلتر</button>
          </div>
          <div class="col-6 col-md-6 d-grid">
            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary fw-bold">
              <i class="bi bi-x-circle"></i> تنظيف
            </a>
          </div>
        </div>
      </div>
    </form>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>التاريخ</th>
            <th>الموظف</th>
            <th class="hide-mobile">الفرع</th>
            <th class="hide-mobile">دخول</th>
            <th class="hide-mobile">خروج</th>
            <th class="hide-mobile">استراحة</th>
            <th class="hide-mobile">تأخير</th>
            <th class="hide-mobile">ساعات</th>
            <th class="hide-mobile">صافي</th>
            <th class="hide-mobile">الموقع</th>
            <th>حالة</th>
            <th class="text-end">إجراءات</th>
            <th>تفاصيل</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            @php
              $empLocation = '';
              if ($r->employee && $r->employee->user_id) {
                $empSession = \App\Models\UserSession::where('user_id', $r->employee->user_id)
                  ->whereDate('work_date', $r->work_date)
                  ->orderByDesc('last_activity')
                  ->first();
                if ($empSession) {
                  $empLocation = $empSession->address_detail
                    ?? collect([$empSession->city ?? null, $empSession->country ?? null])
                      ->filter()->implode('، ');
                }
              }
            @endphp
            <tr>
              <td class="fw-bold">{{ $r->work_date->format('Y-m-d') }}</td>
              <td>{{ $r->employee->full_name }}</td>
              <td class="hide-mobile">{{ $r->employee->branch->name ?? '-' }}</td>
              <td class="hide-mobile">{{ $r->check_in_at?->format('H:i') ?? '-' }}</td>
              <td class="hide-mobile">{{ $r->check_out_at?->format('H:i') ?? '-' }}</td>

              {{-- عمود الاستراحة --}}
              <td class="hide-mobile">
                @if($r->is_on_break)
                  <span class="break-badge on-break">
                    <i class="bi bi-cup-hot"></i>
                    في استراحة منذ {{ $r->break_start_at->format('H:i') }}
                  </span>
                @elseif($r->break_start_at)
                  <span class="break-badge break-done">
                    <i class="bi bi-cup"></i>
                    {{ $r->break_start_at->format('H:i') }} — {{ $r->break_end_at?->format('H:i') ?? '...' }}
                  </span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              <td class="hide-mobile">
                <span class="badge bg-warning text-dark">{{ $r->late_minutes }} د</span>
              </td>
              <td class="hide-mobile">
                <span class="badge bg-info text-dark">{{ round($r->worked_minutes / 60, 2) }} س</span>
              </td>

              {{-- صافي الساعات --}}
              <td class="hide-mobile">
                @if($r->worked_minutes > 0)
                  <span class="badge bg-success">{{ $r->net_worked_formatted }}</span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              {{-- الموقع --}}
              <td class="hide-mobile">
                @if($empLocation)
                  <span class="text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger"></i> {{ $empLocation }}
                  </span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              <td>
                <span class="badge bg-{{ $r->status_color }}">{{ $r->status_label }}</span>
              </td>

              <td class="text-end">
                <div class="d-flex gap-1 justify-content-end flex-wrap">

                  @if(auth()->user()?->hasPermission('mark_attendance'))

                    {{-- زر الدخول --}}
                    @if(!$r->check_in_at && $r->status != 'off')
                      <form method="POST" action="{{ route('attendance.checkin', $r) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-success">
                          <i class="bi bi-box-arrow-in-right"></i> دخول
                        </button>
                      </form>
                    @endif

                    {{-- زر بدء الاستراحة --}}
                    @if($r->can_start_break)
                      <form method="POST" action="{{ route('attendance.break.start', $r) }}">
                        @csrf
                        <button class="btn btn-sm btn-break-start">
                          <i class="bi bi-cup-hot"></i> استراحة
                        </button>
                      </form>
                    @endif

                    {{-- زر إنهاء الاستراحة --}}
                    @if($r->can_end_break)
                      <form method="POST" action="{{ route('attendance.break.end', $r) }}">
                        @csrf
                        <button class="btn btn-sm btn-break-end">
                          <i class="bi bi-play-circle"></i> إنهاء الاستراحة
                        </button>
                      </form>
                    @endif

                    {{-- زر الخروج --}}
                    @if($r->check_in_at && !$r->check_out_at && !$r->is_on_break)
                      <form method="POST" action="{{ route('attendance.checkout', $r) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-box-arrow-left"></i> خروج
                        </button>
                      </form>
                    @endif

                  @endif

                </div>
              </td>

              <td>

                <button class="btn btn-sm btn-outline-dark view-details" data-id="{{ $r->id }}"
                  data-employee="{{ $r->employee->full_name }}" data-date="{{ $r->work_date }}"
                  data-checkin="{{ $r->check_in_at?->format('H:i') }}"
                  data-checkout="{{ $r->check_out_at?->format('H:i') }}" data-break="{{ $r->break_formatted }}"
                  data-late="{{ $r->late_minutes }}" data-worked="{{ $r->net_worked_formatted }}"
                  data-status="{{ $r->status_label }}" data-notes='@json($r->notes)'>
                  <i class="bi bi-eye"></i>
                </button>

              </td>


            </tr>
          @empty
            <tr>
              <td colspan="12" class="text-center text-muted py-4">لا يوجد سجلات</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $records->links() }}
  </div>






  <div class="modal fade" id="attendanceModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">تفاصيل الدوام</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="row g-3 mb-3">

            <div class="col-md-6">
              <label class="fw-bold">الموظف</label>
              <div id="m_employee"></div>
            </div>

            <div class="col-md-6">
              <label class="fw-bold">التاريخ</label>
              <div id="m_date"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">الدخول</label>
              <div id="m_checkin"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">الخروج</label>
              <div id="m_checkout"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">الاستراحة</label>
              <div id="m_break"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">التأخير</label>
              <div id="m_late"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">صافي الساعات</label>
              <div id="m_worked"></div>
            </div>

            <div class="col-md-4">
              <label class="fw-bold">الحالة</label>
              <div id="m_status"></div>
            </div>

          </div>

          <hr>

          <label class="fw-bold">الملاحظات</label>

          <textarea id="notesField" class="form-control" rows="4" placeholder="اكتب ملاحظة إن وجدت..."></textarea>



          <hr>

          <h6 class="fw-bold mb-3">سجل الحضور</h6>

          <ul class="timeline list-unstyled" id="attendanceTimeline"></ul>




        </div>

        <div class="modal-footer">

          <button class="btn btn-secondary" data-bs-dismiss="modal">
            إغلاق
          </button>

          <button class="btn btn-namaa" id="saveNotesBtn">
            حفظ الملاحظات
          </button>

        </div>

      </div>
    </div>
  </div>


  <script>

    let currentRecordId = null;

    document.querySelectorAll('.view-details')
      .forEach(btn => {

        btn.addEventListener('click', function () {

          currentRecordId = this.dataset.id

          document.getElementById('m_employee').innerText = this.dataset.employee
          document.getElementById('m_date').innerText = this.dataset.date
          document.getElementById('m_checkin').innerText = this.dataset.checkin
          document.getElementById('m_checkout').innerText = this.dataset.checkout
          document.getElementById('m_break').innerText = this.dataset.break
          document.getElementById('m_late').innerText = this.dataset.late
          document.getElementById('m_worked').innerText = this.dataset.worked
          document.getElementById('m_status').innerText = this.dataset.status

          let notes = this.dataset.notes

          if (notes === "null") {
            notes = ""
          }

          document.getElementById('notesField').value = notes


          // -------- TIMELINE --------

          let timeline = []

          if (this.dataset.checkin) {
            timeline.push(`🟢 تسجيل الدخول ${this.dataset.checkin}`)
          }

          if (this.dataset.break) {
            timeline.push(`☕ استراحة ${this.dataset.break}`)
          }

          if (this.dataset.checkout) {
            timeline.push(`🔴 تسجيل الخروج ${this.dataset.checkout}`)
          }

          let html = ""

          timeline.forEach(item => {
            html += `<li>${item}</li>`
          })

          document.getElementById('attendanceTimeline').innerHTML = html

          // --------------------------

          new bootstrap.Modal(
            document.getElementById('attendanceModal')
          ).show()

        })

      })


    // SAVE NOTES

    document.getElementById('saveNotesBtn')
      .addEventListener('click', function () {

        let notes = document.getElementById('notesField').value

        fetch(`/attendance/${currentRecordId}/notes`, {

          method: 'POST',

          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },

          body: JSON.stringify({
            notes: notes
          })

        })
          .then(res => res.json())
          .then(data => {

            if (data.success) {

              let btn = document.querySelector(
                `.view-details[data-id="${currentRecordId}"]`
              )

              btn.dataset.notes = notes

              Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح',
                text: 'تم تحديث الملاحظات في سجل الحضور',
                confirmButtonText: 'ممتاز',
                confirmButtonColor: '#3085d6'
              })

            }

          })
          .catch(error => {
            console.error(error)
            alert('حدث خطأ أثناء الحفظ')
          })

      })

  </script>




@endsection