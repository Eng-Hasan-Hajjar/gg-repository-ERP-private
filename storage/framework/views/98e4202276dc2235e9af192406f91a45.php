
<?php($activeModule = 'students')
@section('title', 'الطلاب')

@section('content')
{{-- ✅ عرّف هنا في أعلى الصفحة --}}
@php $myOnly = request()->boolean('my_only'); ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">إدارة الطلاب</h4>
    <div class="text-muted small">بحث متقدم + تصفية حسب الفرع والحالات</div>
  </div>


  <a class="btn btn-primary rounded-pill px-4 fw-bold" href="<?php echo e(route('students.create')); ?>">
    <i class="bi bi-person-plus"></i> طالب جديد
  </a>

  <a class="btn btn-outline-success rounded-pill px-4 fw-bold" href="<?php echo e(route('students.reports.index')); ?>">
    <i class="bi bi-file-earmark-excel"></i> التقارير
  </a>
</div>

<form class="card card-body border-0 shadow-sm mb-3" method="GET" action="<?php echo e(route('students.index')); ?>">
  <div class="row g-2">

    <div class="col-auto">
    
    <a href="<?php echo e(request()->fullUrlWithQuery(['my_only' => $myOnly ? 0 : 1, 'page' => null])); ?>"
       class="btn fw-bold <?php echo e($myOnly ? 'btn-primary' : 'btn-outline-secondary'); ?>">
        <i class="bi bi-person-fill"></i>
        <?php echo e($myOnly ? 'طلابي فقط ✓' : 'كل الطلاب'); ?>

    </a>
</div>


    <div class="col-6 col-md-4">
      <input name="search" value="<?php echo e(request('search')); ?>" class="form-control"
        placeholder="بحث: الاسم / الرقم الجامعي / الهاتف / رمز الدبلومة">
    </div>


    <div class="col-6 col-md-2">
      <select name="diploma_id" class="form-select">
        <option value="">كل الدبلومات</option>
        <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($d->id); ?>" <?php if(request('diploma_id') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>


    <div class="col-6 col-md-2">
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($b->id); ?>" <?php if(request('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-6 col-md-2">
      <select name="status" class="form-select">
        <option value="">كل حالات الطالب</option>
        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </select>
    </div>

    <div class="col-6 col-md-1">
      <select name="registration_status" class="form-select">
        <option value="">حالة التسجيل</option>
        <?php $__currentLoopData = $registrationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(request('registration_status') == $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </select>
    </div>

    <div class="col-6 col-md-1 d-grid">
      <button class="btn btn-namaa fw-bold">تطبيق</button>
    </div>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">






    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>

          <th class="hide-mobile">#</th>
          <th>الرقم الجامعي</th>
          <th>الاسم</th>

          <th class="hide-mobile">الفرع</th>

          <th class="hide-mobile">الحالة</th>
          <th class="hide-mobile">التسجيل</th>
          <th class="hide-mobile">مثبّت؟</th>
          <th class="text-end">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>

          <td class="hide-mobile"><?php echo e($s->id); ?></td>
          <td><code><?php echo e($s->university_id); ?></code></td>
          <td class="fw-semibold"><?php echo e($s->full_name); ?>



            <?php if(!empty($s->profile?->message_to_send)): ?>
              <span class="badge bg-warning text-dark ms-1" title="<?php echo e($s->profile->message_to_send); ?>"
                data-bs-toggle="tooltip">
                📩
              </span>
            <?php endif; ?>

          </td>

          <td class="hide-mobile"><?php echo e($s->branch->name ?? '-'); ?></td>

          <td class="hide-mobile"><span class="badge bg-secondary"><?php echo e($s->status_ar); ?></span></td>
          <td class="hide-mobile">
            <?php ($map = ['pending' => 'warning', 'confirmed' => 'success', 'archived' => 'secondary', 'dismissed' => 'danger', 'frozen' => 'info']); ?>
            <span class="badge bg-<?php echo e($map[$s->registration_ar] ?? 'secondary'); ?>">
              <?php echo e($s->registration_ar); ?>

            </span>
          </td>
          <td class="hide-mobile">
            <?php if($s->is_confirmed): ?>
              <span class="badge bg-success">نعم</span>
            <?php else: ?>
              <span class="badge bg-secondary">لا</span>
            <?php endif; ?>

          </td>
          <td class="text-end">


      

              
              <?php if(auth()->user()?->hasPermission('view_student_financials')): ?>
                  <button class="btn btn-sm btn-outline-success"
                          onclick="showFinancial(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->full_name)); ?>')"
                          title="التفاصيل المالية">
                      <i class="bi bi-cash-coin"></i>
                  </button>
              <?php endif; ?>
              <button class="btn btn-sm btn-outline-info"
                      onclick="showExams(<?php echo e($s->id); ?>, '<?php echo e(addslashes($s->full_name)); ?>')"
                      title="نتائج الامتحانات">
                  <i class="bi bi-journal-check"></i>
              </button>

              





            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('students.show', $s)); ?>">
                <i class="bi bi-eye"></i> عرض
              </a>
            <?php endif; ?>

            <?php if(auth()->user()?->hasPermission('edit_students')): ?>
              <a class="btn btn-sm btn-outline-dark" href="<?php echo e(route('students.edit', $s)); ?>">
                <i class="bi bi-pencil"></i> تعديل
              </a>
            <?php endif; ?>
            <?php if(auth()->user()?->hasPermission('delete_students')): ?>
              <form method="POST" action="<?php echo e(route('students.destroy', $s)); ?>" class="d-inline"
                onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> حذف
                </button>
              </form>
            <?php endif; ?>


            <?php if(!$s->is_confirmed): ?>

              <form method="POST" action="<?php echo e(route('students.confirm', $s)); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-success">تثبيت</button>
              </form>

            <?php endif; ?>

          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="10" class="text-center text-muted py-4">لا يوجد بيانات</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>








  </div>
</div>

<div class="mt-3">
  <?php echo e($students->links()); ?>

</div>






<div class="modal fade" id="studentInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">تفاصيل الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <div class="mt-2 text-muted">جاري التحميل...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showFinancial(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-cash-coin text-success me-2"></i> التفاصيل المالية — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
    var modal = new bootstrap.Modal(document.getElementById('studentInfoModal'));
    modal.show();

    fetch('/students/' + id + '/modal/financial')
        .then(function(r) { return r.text(); })
        .then(function(html) {
            document.getElementById('modalBody').innerHTML = html;
        });
}

function showExams(id, name) {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-journal-check text-info me-2"></i> نتائج الامتحانات — ' + name;
    document.getElementById('modalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-info"></div></div>';
    var modal = new bootstrap.Modal(document.getElementById('studentInfoModal'));
    modal.show();

    fetch('/students/' + id + '/modal/exams')
        .then(function(r) { return r.text(); })
        .then(function(html) {
            document.getElementById('modalBody').innerHTML = html;
        });
}
</script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/index.blade.php ENDPATH**/ ?>