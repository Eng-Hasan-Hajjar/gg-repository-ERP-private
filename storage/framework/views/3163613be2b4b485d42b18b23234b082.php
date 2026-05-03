
<?php $__env->startSection('title', 'الدوام والحضور'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .break-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 8px; font-size: 12px; font-weight: 600;
  }
  .break-badge.on-break  { background: #fef3c7; color: #92400e; animation: pulse-break 1.5s infinite; }
  .break-badge.break-done{ background: #f1f5f9; color: #64748b; }

  @keyframes pulse-break {
    0%,100% { opacity: 1; }
    50%      { opacity: .6; }
  }

  .btn-break-start { background:#fef3c7; color:#92400e; border:1px solid #fcd34d; font-weight:600; }
  .btn-break-start:hover { background:#fde68a; color:#78350f; }
  .btn-break-end   { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; font-weight:600; }
  .btn-break-end:hover   { background:#bfdbfe; color:#1e3a8a; }

  .timeline li {
    padding: 6px 0; border-left: 3px solid #e5e7eb;
    padding-left: 12px; margin-left: 5px; position: relative;
  }
  .timeline li::before {
    content:''; width:10px; height:10px; background:#3b82f6;
    border-radius:50%; position:absolute; left:-6px; top:10px;
  }

  .col-toggle-btn {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 12px; border-radius: 16px; font-size: 12px; font-weight: 700;
    border: 1px solid #e2e8f0; background: #f8fafc; color: #475569;
    cursor: pointer; transition: .15s; user-select: none;
  }
  .col-toggle-btn.active { background: #0ea5e9; color: #fff; border-color: #0ea5e9; }
  .col-toggle-btn:hover:not(.active) { background: #e2e8f0; }

  @media (max-width: 768px) {
    .day-column  { min-width: 80px; }
    .date-column { min-width: 100px; }
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-0 fw-bold">الدوام والحضور</h4>
    <div class="text-muted fw-semibold">سجل الدخول/الخروج + استراحة + تأخير + ساعات</div>
  </div>
</div>

<?php if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('manager_attendance')): ?>

  <form method="POST" action="<?php echo e(route('attendance.generateWeek')); ?>" class="card border-0 shadow-sm mb-3">
    <?php echo csrf_field(); ?>
    <div class="card-body py-2">
      <div class="row align-items-end g-2">
        <div class="col-md-3">
          <label class="fw-bold small mb-1">بداية الأسبوع</label>
          <input type="date" name="week_start" class="form-control" required
            value="<?php echo e(now()->startOfWeek()->format('Y-m-d')); ?>">
        </div>
        <div class="col-md-auto">
          <button class="btn btn-namaa fw-bold mt-3">
            <i class="bi bi-magic"></i> توليد سجلات الأسبوع
          </button>
        </div>
      </div>
    </div>
  </form>

  <div class="mb-2 text-muted small">عدد السجلات: <?php echo e($records->total()); ?></div>

  <div class="d-flex flex-wrap gap-2 mt-2 mb-3">
    <a href="<?php echo e(route('attendance.index', ['from' => now()->startOfWeek()->toDateString(), 'to' => now()->endOfWeek()->toDateString()])); ?>"
       class="btn btn-sm btn-outline-primary">
      <i class="bi bi-calendar-week"></i> هذا الأسبوع
    </a>
    <a href="<?php echo e(route('attendance.index', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->endOfMonth()->toDateString()])); ?>"
       class="btn btn-sm btn-outline-success">
      <i class="bi bi-calendar-month"></i> هذا الشهر
    </a>
    <a href="<?php echo e(route('attendance.index', ['from' => now()->subMonths(3)->startOfMonth()->toDateString(), 'to' => now()->endOfMonth()->toDateString()])); ?>"
       class="btn btn-sm btn-outline-dark">
      <i class="bi bi-calendar-range"></i> آخر 3 أشهر
    </a>
  </div>

  <form class="card border-0 shadow-sm mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-6 col-md-2">
          <input name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="بحث: اسم/كود">
        </div>
        <div class="col-6 col-md-2">
          <select name="branch_id" class="form-select">
            <option value="">الفرع (الكل)</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select name="employee_id" class="form-select">
            <option value="">الموظف (الكل)</option>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($e->id); ?>" <?php if(request('employee_id') == $e->id): echo 'selected'; endif; ?>><?php echo e($e->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select name="status" class="form-select">
            <option value="">الحالة (الكل)</option>
            <?php $__currentLoopData = ['scheduled','present','late','absent','off','leave']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($s); ?>" <?php if(request('status') == $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="form-control">
        </div>
        <div class="col-6 col-md-2">
          <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="form-control">
        </div>
        <div class="col-6 col-md-6 d-grid">
          <button class="btn btn-namaa fw-bold"><i class="bi bi-funnel"></i> تطبيق الفلتر</button>
        </div>
        <div class="col-6 col-md-6 d-grid">
          <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-x-circle"></i> تنظيف
          </a>
        </div>
      </div>
    </div>
  </form>

<?php endif; ?>


<div class="card border-0 shadow-sm mb-3">
  <div class="card-body py-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="text-muted small fw-bold ms-1">الأعمدة:</span>
      <button type="button" class="col-toggle-btn active" data-col="col-branch">
        <i class="bi bi-building"></i> الفرع
      </button>
      <button type="button" class="col-toggle-btn active" data-col="col-checkin">
        <i class="bi bi-box-arrow-in-right"></i> دخول
      </button>
      <button type="button" class="col-toggle-btn active" data-col="col-checkout">
        <i class="bi bi-box-arrow-left"></i> خروج
      </button>
      <button type="button" class="col-toggle-btn" data-col="col-break">
        <i class="bi bi-cup-hot"></i> استراحة
      </button>
      <button type="button" class="col-toggle-btn active" data-col="col-late">
        <i class="bi bi-clock-history"></i> تأخير
      </button>
      <button type="button" class="col-toggle-btn active" data-col="col-worked">
        <i class="bi bi-hourglass-split"></i> ساعات
      </button>
      <button type="button" class="col-toggle-btn" data-col="col-net">
        <i class="bi bi-calculator"></i> صافي
      </button>
      <button type="button" class="col-toggle-btn" data-col="col-location">
        <i class="bi bi-geo-alt"></i> الموقع
      </button>
    </div>
  </div>
</div>


<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0" id="attendanceTable">
      <thead class="table-light">
        <tr>
          <th class="date-column">التاريخ</th>
          <th class="day-column">اليوم</th>
          <th>الموظف</th>
          <th class="col-branch">الفرع</th>
          <th class="col-checkin">دخول</th>
          <th class="col-checkout">خروج</th>
          <th class="col-break">استراحة</th>
          <th class="col-late">تأخير</th>
          <th class="col-worked">ساعات</th>
          <th class="col-net">صافي</th>
          <th class="col-location">الموقع</th>
          <th>حالة</th>
          <th class="text-end">إجراءات</th>
          <th>تفاصيل</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
            $daysOfWeek = [
              'Monday'    => 'الإثنين',
              'Tuesday'   => 'الثلاثاء',
              'Wednesday' => 'الأربعاء',
              'Thursday'  => 'الخميس',
              'Friday'    => 'الجمعة',
              'Saturday'  => 'السبت',
              'Sunday'    => 'الأحد',
            ];
            $dayName = $daysOfWeek[$r->work_date->format('l')] ?? $r->work_date->format('l');

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

            $lateMin     = (int) ($r->late_minutes ?? 0);
            $workedMin   = (int) ($r->worked_minutes ?? 0);
            $workedHours = $workedMin > 0
              ? floor($workedMin / 60) . 'س ' . ($workedMin % 60) . 'د'
              : '—';
          ?>
          <tr>
            <td class="fw-bold"><?php echo e($r->work_date->format('Y-m-d')); ?></td>
            <td>
              <span class="badge <?php echo e($r->work_date->isToday() ? 'bg-primary' : 'bg-secondary'); ?>">
                <?php echo e($dayName); ?>

              </span>
            </td>
            <td><?php echo e($r->employee->full_name); ?></td>

            <td class="col-branch"><?php echo e($r->employee->branch->name ?? '-'); ?></td>
            <td class="col-checkin"><?php echo e($r->check_in_at?->format('H:i') ?? '-'); ?></td>
            <td class="col-checkout"><?php echo e($r->check_out_at?->format('H:i') ?? '-'); ?></td>

            <td class="col-break">
              <?php if($r->is_on_break): ?>
                <span class="break-badge on-break">
                  <i class="bi bi-cup-hot"></i>
                  في استراحة منذ <?php echo e($r->break_start_at->format('H:i')); ?>

                </span>
              <?php elseif($r->break_start_at): ?>
                <span class="break-badge break-done">
                  <i class="bi bi-cup"></i>
                  <?php echo e($r->break_start_at->format('H:i')); ?> — <?php echo e($r->break_end_at?->format('H:i') ?? '...'); ?>

                </span>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>

            <td class="col-late">
              <?php if($lateMin > 0): ?>
                <span class="badge bg-danger"><?php echo e($lateMin); ?> د</span>
              <?php else: ?>
                <span class="badge bg-success">في الوقت</span>
              <?php endif; ?>
            </td>

            <td class="col-worked">
              <?php if($workedMin > 0): ?>
                <span class="badge bg-info text-dark"><?php echo e($workedHours); ?></span>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>

            <td class="col-net">
              <?php if($r->worked_minutes > 0): ?>
                <span class="badge bg-success"><?php echo e($r->net_worked_formatted); ?></span>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>

            <td class="col-location">
              <?php if($empLocation): ?>
                <span class="text-muted small">
                  <i class="bi bi-geo-alt-fill text-danger"></i> <?php echo e($empLocation); ?>

                </span>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>

            <td>
              <span class="badge bg-<?php echo e($r->status_color); ?>"><?php echo e($r->status_label); ?></span>
            </td>

            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end flex-wrap">
                <?php if(auth()->user()?->hasPermission('mark_attendance')): ?>

                  <?php if(!$r->check_in_at && $r->status != 'off'): ?>
                    <form method="POST" action="<?php echo e(route('attendance.checkin', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <button class="btn btn-sm btn-outline-success">
                        <i class="bi bi-box-arrow-in-right"></i> دخول
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if($r->can_start_break): ?>
                    <form method="POST" action="<?php echo e(route('attendance.break.start', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <button class="btn btn-sm btn-break-start">
                        <i class="bi bi-cup-hot"></i> استراحة
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if($r->can_end_break): ?>
                    <form method="POST" action="<?php echo e(route('attendance.break.end', $r)); ?>">
                      <?php echo csrf_field(); ?>
                      <button class="btn btn-sm btn-break-end">
                        <i class="bi bi-play-circle"></i> إنهاء الاستراحة
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if($r->check_in_at && !$r->check_out_at && !$r->is_on_break): ?>
                    <form method="POST" action="<?php echo e(route('attendance.checkout', $r)); ?>" class="checkout-form">
                      <?php echo csrf_field(); ?>
                      <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-box-arrow-left"></i> خروج
                      </button>
                    </form>
                  <?php endif; ?>

                <?php endif; ?>
              </div>
            </td>

            <td>
              <button class="btn btn-sm btn-outline-dark view-details"
                data-id="<?php echo e($r->id); ?>"
                data-employee="<?php echo e($r->employee->full_name); ?>"
                data-date="<?php echo e($r->work_date->format('Y-m-d')); ?>"
                data-day="<?php echo e($dayName); ?>"
                data-checkin="<?php echo e($r->check_in_at?->format('H:i')); ?>"
                data-checkout="<?php echo e($r->check_out_at?->format('H:i')); ?>"
                data-break="<?php echo e($r->break_formatted); ?>"
                data-late="<?php echo e($lateMin); ?>"
                data-worked="<?php echo e($workedHours); ?>"
                data-status="<?php echo e($r->status_label); ?>"
                data-notes='<?php echo json_encode($r->notes, JSON_UNESCAPED_UNICODE, 512) ?>'>
                <i class="bi bi-eye"></i>
              </button>
            </td>

          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="14" class="text-center text-muted py-4">لا يوجد سجلات</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3"><?php echo e($records->links()); ?></div>


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
            <label class="fw-bold">التاريخ / اليوم</label>
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
            <label class="fw-bold">التأخير (دقائق)</label>
            <div id="m_late"></div>
          </div>
          <div class="col-md-4">
            <label class="fw-bold">ساعات العمل</label>
            <div id="m_worked"></div>
          </div>
          <div class="col-md-4">
            <label class="fw-bold">الحالة</label>
            <div id="m_status"></div>
          </div>
        </div>
        <hr>
        <label class="fw-bold">الملاحظات</label>
        <textarea id="notesField" class="form-control mt-2" rows="4"
          placeholder="اكتب ملاحظة إن وجدت..."></textarea>
        <hr>
        <h6 class="fw-bold mb-3">سجل الحضور</h6>
        <ul class="timeline list-unstyled" id="attendanceTimeline"></ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button class="btn btn-namaa" id="saveNotesBtn">حفظ الملاحظات</button>
      </div>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // ══ إخفاء الأعمدة غير المفعّلة من البداية ══
  document.querySelectorAll('.col-toggle-btn:not(.active)').forEach(btn => {
    const c = btn.dataset.col;
    document.querySelectorAll('.' + c).forEach(el => el.style.display = 'none');
  });

  // ══ تبديل إخفاء/إظهار الأعمدة ══
  document.querySelectorAll('.col-toggle-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const c        = this.dataset.col;
      const isActive = this.classList.toggle('active');
      document.querySelectorAll('.' + c).forEach(el => {
        el.style.display = isActive ? '' : 'none';
      });
    });
  });

  // ══ مودال التفاصيل ══
  let currentRecordId = null;

  document.querySelectorAll('.view-details').forEach(btn => {
    btn.addEventListener('click', function () {
      currentRecordId = this.dataset.id;

      document.getElementById('m_employee').innerText = this.dataset.employee;
      document.getElementById('m_date').innerHTML =
        `${this.dataset.date} <span class="badge bg-secondary">${this.dataset.day}</span>`;
      document.getElementById('m_checkin').innerText  = this.dataset.checkin  || '-';
      document.getElementById('m_checkout').innerText = this.dataset.checkout || '-';
      document.getElementById('m_break').innerText    = this.dataset.break    || '-';
      document.getElementById('m_late').innerText     =
        parseInt(this.dataset.late) > 0 ? this.dataset.late + ' دقيقة' : 'في الوقت';
      document.getElementById('m_worked').innerText   = this.dataset.worked   || '-';
      document.getElementById('m_status').innerText   = this.dataset.status;

      let notes = this.dataset.notes;
      if (notes === 'null' || notes === 'undefined') notes = '';
      document.getElementById('notesField').value = notes;

      let timeline = [];
      if (this.dataset.checkin)
        timeline.push(`🟢 تسجيل الدخول ${this.dataset.checkin}`);
      if (this.dataset.break && this.dataset.break !== '-' && this.dataset.break !== '—')
        timeline.push(`☕ استراحة ${this.dataset.break}`);
      if (this.dataset.checkout)
        timeline.push(`🔴 تسجيل الخروج ${this.dataset.checkout}`);
      if (timeline.length === 0)
        timeline.push(`📋 لا يوجد سجل حضور لهذا اليوم`);

      document.getElementById('attendanceTimeline').innerHTML =
        timeline.map(i => `<li>${i}</li>`).join('');

      new bootstrap.Modal(document.getElementById('attendanceModal')).show();
    });
  });

  // ══ حفظ الملاحظات ══
  document.getElementById('saveNotesBtn').addEventListener('click', function () {
    const notes = document.getElementById('notesField').value;

    fetch(`/attendance/${currentRecordId}/notes`, {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept':       'application/json'
      },
      body: JSON.stringify({ notes })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const btn = document.querySelector(`.view-details[data-id="${currentRecordId}"]`);
        if (btn) btn.dataset.notes = notes;
        Swal.fire({
          icon: 'success', title: 'تم الحفظ بنجاح',
          text: 'تم تحديث الملاحظات في سجل الحضور',
          confirmButtonText: 'ممتاز', confirmButtonColor: '#3085d6'
        });
      }
    })
    .catch(() => {
      Swal.fire({ icon: 'error', title: 'خطأ', text: 'حدث خطأ أثناء حفظ الملاحظات' });
    });
  });

  // ══ تأكيد تسجيل الخروج ══
  document.querySelectorAll('.checkout-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (confirm('⚠️ هل أنت متأكد من تسجيل الخروج الآن؟\n\nسيتم تسجيل وقت الخروج الآن.')) {
        this.submit();
      }
    });
  });

});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/attendance/index.blade.php ENDPATH**/ ?>