
<?php $__env->startSection('title', 'CRM - تفاصيل العميل المحتمل'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold mb-0"><?php echo e($lead->full_name); ?></h4>
      <div class="text-muted small">
        المرحلة: <b><?php echo e($stage_ar); ?></b>
        —
        حالة التسجيل: <b><?php echo e($registration_ar); ?></b>
      </div>
    </div>


      <div class="d-flex gap-2">

      <?php if($lead->registration_status === 'pending'): ?>

        <div class="alert alert-info mt-3">

          سيتم تحويل العميل إلى طالب تلقائياً بعد ترحيل الدفعة من قسم المالية.

        </div>

      <?php endif; ?>

      </div>
    <div class="d-flex gap-2">
  




      <?php if(auth()->user()?->hasPermission('convert_leads')): ?>
        <?php if($lead->registration_status === 'pending'): ?>
          <form method="POST" action="<?php echo e(route('leads.convert', $lead)); ?>" hidden>
            <?php echo csrf_field(); ?>
            <button class="btn btn-success fw-bold">تحويل إلى طالب</button>
          </form>
        <?php endif; ?>
      <?php endif; ?>


          <?php if(auth()->user()?->hasPermission('edit_leads')): ?>
        <a class="btn btn-outline-dark" href="<?php echo e(route('leads.edit', $lead)); ?>">تعديل</a>
      <?php endif; ?>


    </div>
  </div>

  <div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
      <h6 class="fw-bold">الدبلومات</h6>
      <?php $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="badge bg-light text-dark border"><?php echo e($d->name); ?></span>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <hr>
      <div class="row g-2">
        <div class="col-md-4"><b>الهاتف:</b> <?php echo e($lead->phone ?? '-'); ?></div>
        <div class="col-md-4" hidden><b>واتساب:</b> <?php echo e($lead->whatsapp ?? '-'); ?></div>
        <div class="col-md-4"><b>الفرع:</b> <?php echo e($lead->branch->name ?? '-'); ?></div>
        <div class="col-md-4"><b>السكن:</b> <?php echo e($lead->residence ?? '-'); ?></div>
        <div class="col-md-4"><b>العمر:</b> <?php echo e($lead->age ?? '-'); ?></div>
        <div class="col-md-4"><b>المصدر:</b> <?php echo e($source_ar); ?></div>
        <div class="col-12" hidden><b>الاحتياج:</b> <?php echo e($lead->need ?? '-'); ?></div>
        <div class="col-12"><b>ملاحظات:</b> <?php echo e($lead->notes ?? '-'); ?></div>
      </div>

      <div class="col-md-4">
        <b>مسؤول التواصل:</b>
        <?php echo e($lead->creator->name ?? $lead->creator->email ?? '-'); ?>

      </div>
      <div class="col-md-4"><b>العمل:</b> <?php echo e($lead->job ?? '-'); ?></div>
      <div class="col-md-4"><b>البلد:</b> <?php echo e($lead->country ?? '-'); ?></div>
      <div class="col-md-4"><b>المحافظة:</b> <?php echo e($lead->province ?? '-'); ?></div>
      <div class="col-md-4"><b>الدراسة:</b> <?php echo e($lead->study ?? '-'); ?></div>

    </div>
  </div>







  <?php if($lead->registration_status === 'pending'): ?>

    <hr>
    <h5 class="fw-bold">إضافة دفعة مالية</h5>

    <form method="POST" action="<?php echo e(route('financial.pay')); ?>">
      <?php echo csrf_field(); ?>

      <input type="hidden" name="financial_account_id" value="<?php echo e($lead->financialAccount?->id); ?>">

      <div class="row g-3">




        <div class="col-md-4">
          <label>الدبلومة</label>
          <select name="diploma_id" class="form-select" required>
            <?php $__currentLoopData = $lead->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diploma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($diploma->id); ?>">
                <?php echo e($diploma->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-md-4">
          <label>الصندوق</label>
          <select name="cashbox_id" class="form-select" required>
            <?php $__currentLoopData = \App\Models\Cashbox::where('status','active')
              ->where('branch_id',$lead->branch_id)
              ->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $box): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($box->id); ?>" <?php echo e($box->branch_id == $lead->branch_id ? 'selected' : ''); ?>>
                <?php echo e($box->name); ?> - <?php echo e($box->currency); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="col-md-4">
          <label>المبلغ</label>
          <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label>ملاحظات</label>
          <input type="text" name="notes" class="form-control">
        </div>

        <div class="col-12">
          <button class="btn btn-success">تسجيل دفعة</button>
        </div>

      </div>
    </form>

  <?php endif; ?>







  
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h6 class="fw-bold mb-3">المتابعات</h6>

      <form class="row g-2 mb-3" method="POST" action="<?php echo e(route('leads.followups.store', $lead)); ?>">
        <?php echo csrf_field(); ?>
        <div class="col-md-3">
          <input type="date" name="followup_date" class="form-control" value="<?php echo e(old('followup_date')); ?>">
        </div>
        <div class="col-md-3">
          <input name="result" class="form-control" placeholder="نتيجة المتابعة" value="<?php echo e(old('result')); ?>">
        </div>
        <div class="col-md-6">
          <input name="notes" class="form-control" placeholder="ملاحظات" value="<?php echo e(old('notes')); ?>">
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary fw-bold">إضافة متابعة</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>تاريخ</th>
              <th>نتيجة</th>
              <th>ملاحظات</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $lead->followups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><?php echo e($f->followup_date?->format('Y-m-d') ?? '-'); ?></td>
                <td><?php echo e($f->result ?? '-'); ?></td>
                <td><?php echo e($f->notes ?? '-'); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="3" class="text-muted text-center py-3">لا يوجد متابعات</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/crm/leads/show.blade.php ENDPATH**/ ?>