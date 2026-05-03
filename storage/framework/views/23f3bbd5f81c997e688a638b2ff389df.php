
<?php $__env->startSection('title', 'ملف المدرب/الموظف'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
  <div>
    <h4 class="mb-1 fw-bold"><?php echo e($employee->full_name); ?></h4>
    <div class="text-muted fw-semibold">
      كود: <code><?php echo e($employee->code); ?></code>
      — النوع: <b><?php echo e($employee->type == 'trainer' ? 'مدرب' : 'موظف'); ?></b>
      — الفرع: <b><?php echo e($employee->branch->name ?? '-'); ?></b>


      <?php if($employee->secondaryBranch): ?>
        <span class="badge bg-info">+ <?php echo e($employee->secondaryBranch->name); ?></span>
      <?php endif; ?>



    </div>
  </div>

  <div class="d-flex flex-wrap gap-2">
    <?php if(auth()->user()?->hasPermission('edit_employees')): ?>
      <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
        <i class="bi bi-pencil"></i> تعديل
      </a>
    <?php endif; ?>


    <?php if(auth()->user()?->hasPermission('create_users')): ?>

      <?php if(!$employee->user): ?>
        <form method="POST" action="<?php echo e(route('employees.createUser', $employee)); ?>" class="d-inline"
          onsubmit="return confirm('سيتم إنشاء حساب دخول لهذا الموظف، هل أنت متأكد؟')">
          <?php echo csrf_field(); ?>
          <button class="btn btn-success rounded-pill px-4 fw-bold">
            <i class="bi bi-person-check"></i> إنشاء حساب دخول
          </button>
        </form>
      <?php else: ?>
        <a href="<?php echo e(route('admin.users.edit', $employee->user)); ?>"
          class="btn btn-outline-primary rounded-pill px-4 fw-bold">
          <i class="bi bi-person-gear"></i> إدارة الحساب
        </a>
      <?php endif; ?>

    <?php endif; ?>



    <?php if(auth()->user()?->hasPermission('manage_contracts')): ?>
      <a href="<?php echo e(route('employees.contracts.index', $employee)); ?>" class="btn btn-namaa rounded-pill px-4 fw-bold">
        <i class="bi bi-file-earmark-text"></i> العقود
      </a>
    <?php endif; ?>
    <?php if(auth()->user()?->hasPermission('manage_salaries')): ?>

      <a href="<?php echo e(route('employees.payouts.index', $employee)); ?>" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="bi bi-cash-coin"></i> المستحقات
      </a>
    <?php endif; ?>

  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">البيانات الأساسية</h6>

        <div class="row g-2 small">
          <div class="col-6"><b>الهاتف:</b> <?php echo e($employee->phone ?? '-'); ?></div>
          <div class="col-6"><b>الإيميل:</b> <?php echo e($employee->email ?? '-'); ?></div>
          <div class="col-6"><b>المسمى:</b> <?php echo e($employee->job_title ?? '-'); ?></div>
          <div class="col-6">
            <b>الحالة:</b>
            <span class="badge bg-<?php echo e($employee->status == 'active' ? 'success' : 'secondary'); ?>">
              <?php echo e($employee->status == 'active' ? 'نشط' : 'غير نشط'); ?>

            </span>
          </div>



          <div class="col-6">
            <b>حساب الدخول:</b>
            <?php if($employee->user): ?>
              <span class="badge bg-success">
                مرتبط
              </span>
              <div class="small text-muted">
                <?php echo e($employee->user->name); ?> — <?php echo e($employee->user->email); ?>

              </div>
            <?php else: ?>
              <span class="badge bg-secondary">
                لا يوجد حساب
              </span>
            <?php endif; ?>
          </div>



          <div class="col-12 mt-2">
            <b>الدبلومات:</b>
            <?php if($employee->diplomas->count()): ?>
              <?php $__currentLoopData = $employee->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge bg-light text-dark border"><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
              -
            <?php endif; ?>
          </div>

          <div class="col-12 mt-2">
            <b>ملاحظات:</b> <?php echo e($employee->notes ?? '-'); ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3">ملخص سريع</h6>


        <hr class="my-4">

        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <h6 class="fw-bold mb-0">برنامج الدوام الأسبوعي</h6>

              <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-sm btn-outline-primary"
                  href="<?php echo e(route('attendance.calendar', ['employee_id' => $employee->id, 'month' => now()->format('Y-m')])); ?>">
                  <i class="bi bi-calendar3"></i> عرض التقويم لهذا الموظف
                </a>

                <a class="btn btn-sm btn-outline-dark"
                  href="<?php echo e(route('attendance.index', ['employee_id' => $employee->id])); ?>">
                  <i class="bi bi-funnel"></i> سجلات الدوام (فلترة)
                </a>
              </div>
            </div>

            <div class="row g-2 small">
              <div class="col-12 col-md-6">
                <b>نمط الدوام:</b>
                <span class="badge bg-<?php echo e($employee->schedule_mode == 'weekly' ? 'primary' : 'warning'); ?>">
                  <?php echo e($employee->schedule_mode == 'weekly' ? 'أسبوعي ثابت' : 'Custom/مرن'); ?>

                </span>
              </div>
              <div class="col-12 col-md-6">
                <b>ساري من:</b> <?php echo e($employee->schedule_effective_from ?? '-'); ?>

              </div>
            </div>

            <div class="table-responsive mt-3">
              <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>اليوم</th>
                    <th>الشيفت</th>
                    <th>الوقت</th>
                    <th>الحالة</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $weekdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                      $row = $scheduleMap[$wd] ?? null;
                      $isOff = !$row || ($row instanceof \App\Models\EmployeeSchedule
                        ? $row->is_off
                        : ($row['is_off'] ?? true));
                    ?>
                    <tr>
                      <td class="fw-bold"><?php echo e($label); ?></td>
                      <td>
                        <?php if(!$isOff): ?>
                          <span class="badge bg-success">دوام</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">عطلة</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if(!$isOff && $row): ?>
                          <?php
                            $start = $row instanceof \App\Models\EmployeeSchedule
                              ? $row->start_time : ($row['start'] ?? '');
                            $end = $row instanceof \App\Models\EmployeeSchedule
                              ? $row->end_time : ($row['end'] ?? '');
                          ?>
                          <span class="text-success fw-bold"><?php echo e(substr($start, 0, 5)); ?></span>
                          <span class="text-muted mx-1">←</span>
                          <span class="text-danger fw-bold"><?php echo e(substr($end, 0, 5)); ?></span>
                        <?php else: ?>
                          <span class="text-muted">—</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>

            
            <hr class="my-3">
            <h6 class="fw-bold mb-2">استثناءات/تعديلات على الدوام (Overrides)</h6>
            <?php if($overrides->count()): ?>
              <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>التاريخ</th>
                      <th>الشيفت</th>
                      <th>الوقت</th>
                      <th>السبب</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $__currentLoopData = $overrides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td class="fw-bold"><?php echo e($o->work_date->format('Y-m-d')); ?></td>
                        <td><?php echo e($o->shift?->name ?? 'OFF'); ?></td>
                        <td>
                          <?php if($o->shift): ?>
                            <?php echo e($o->shift->start_time); ?> - <?php echo e($o->shift->end_time); ?>

                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td><?php echo e($o->reason ?? '-'); ?></td>
                      </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="text-muted small">لا يوجد استثناءات ضمن الفترة الحالية.</div>
            <?php endif; ?>

          </div>
        </div>



        <div class="row g-2 small">
          <div class="col-6"><b>عدد العقود:</b> <?php echo e($employee->contracts->count()); ?></div>
          <div class="col-6"><b>عدد المستحقات:</b> <?php echo e($employee->payouts->count()); ?></div>
          <div class="col-12">
            <b>آخر مستحق:</b>
            <?php ($last = $employee->payouts->sortByDesc('payout_date')->first()); ?>
            <?php echo e($last ? ($last->payout_date->format('Y-m-d') . ' — ' . $last->amount . ' ' . $last->currency . ' (' . $last->status . ')') : '-'); ?>

          </div>
        </div>

        <hr>

        <div class="d-flex flex-wrap gap-2">
          <?php if(auth()->user()?->hasPermission('manage_contracts')): ?>
            <a href="<?php echo e(route('employees.contracts.create', $employee)); ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-plus-circle"></i> إضافة عقد
            </a>
          <?php endif; ?>
          <?php if(auth()->user()?->hasPermission('manage_salaries')): ?>
            <a href="<?php echo e(route('employees.payouts.create', $employee)); ?>" class="btn btn-sm btn-outline-success">
              <i class="bi bi-plus-circle"></i> إضافة مستحق
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/employees/show.blade.php ENDPATH**/ ?>