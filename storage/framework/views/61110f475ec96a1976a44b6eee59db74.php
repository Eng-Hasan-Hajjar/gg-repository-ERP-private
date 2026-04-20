
<?php $__env->startSection('title', 'تعديل خطة الدفع'); ?>

<?php $__env->startSection('content'); ?>

  
  <?php if($errors->any()): ?>
    <div class="alert alert-danger fw-semibold mb-3">
      <i class="bi bi-exclamation-triangle"></i>
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div><?php echo e($e); ?></div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>

  <div class="container">

    <div class="section-header mb-3">
      <i class="bi bi-pencil"></i>
      تعديل خطة الدفع
    </div>

    <div class="glass-card p-4">

      <h5 class="fw-bold mb-3"><?php echo e($plan->diploma->name); ?></h5>

      <form method="POST" action="<?php echo e(route('payment.plan.update', $plan->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
        <input type="hidden" name="diploma_id" value="<?php echo e($plan->diploma_id); ?>">

        <div class="row g-3">

          
          <div class="col-md-4">
            <label class="fw-bold">المبلغ الإجمالي</label>
            <input type="number" step="0.01" name="total_amount"
                   id="edit-total-amount"
                   value="<?php echo e($plan->total_amount); ?>"
                   class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">العملة</label>
            <select name="currency" class="form-select">
              <option value="USD" <?php if($plan->currency == 'USD'): echo 'selected'; endif; ?>>USD</option>
              <option value="EUR" <?php if($plan->currency == 'EUR'): echo 'selected'; endif; ?>>EUR</option>
              <option value="TRY" <?php if($plan->currency == 'TRY'): echo 'selected'; endif; ?>>TRY</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="fw-bold">نوع الدفع</label>
            <select name="payment_type" id="payment_type" class="form-select">
              <option value="full"         <?php if($plan->payment_type == 'full'): echo 'selected'; endif; ?>>كامل</option>
              <option value="installments" <?php if($plan->payment_type == 'installments'): echo 'selected'; endif; ?>>دفعات</option>
            </select>
          </div>

          <div class="col-md-4 installments-box <?php echo e($plan->payment_type == 'installments' ? '' : 'd-none'); ?>">
            <label class="fw-bold">عدد الدفعات</label>
            <select name="installments_count" id="installments_count" class="form-select">
              <option value="">اختر</option>
              <option value="2" <?php if($plan->installments_count == 2): echo 'selected'; endif; ?>>2</option>
              <option value="3" <?php if($plan->installments_count == 3): echo 'selected'; endif; ?>>3</option>
              <option value="4" <?php if($plan->installments_count == 4): echo 'selected'; endif; ?>>4</option>
            </select>
          </div>

        </div>

        <hr>

        
        <div id="installments_container">
          <?php if($plan->installments->count()): ?>
            <?php $__currentLoopData = $plan->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="fw-bold">قيمة الدفعة <?php echo e($loop->iteration); ?></label>
                  <input type="number" step="0.01" min="0.01"
                         name="installments[<?php echo e($i); ?>][amount]"
                         value="<?php echo e($installment->amount); ?>"
                         class="form-control edit-installment-amount"
                         required>
                  <small class="text-danger d-none" id="edit-err-<?php echo e($loop->iteration); ?>"></small>
                </div>
                <div class="col-md-6">
                  <label class="fw-bold">تاريخ الدفعة <?php echo e($loop->iteration); ?></label>
                  <input type="date" name="installments[<?php echo e($i); ?>][due_date]"
                         value="<?php echo e($installment->due_date); ?>"
                         class="form-control" required>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>

        
        <div id="edit-summary" class="mt-2"></div>

        <div class="text-end mt-4">
          <button class="btn btn-namaa btn-pill" id="edit-submit-btn">
            <i class="bi bi-check2-circle"></i> تحديث الخطة
          </button>
          <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-outline-secondary btn-pill">
            رجوع
          </a>
        </div>

      </form>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const totalInput = document.getElementById('edit-total-amount');
  const submitBtn  = document.getElementById('edit-submit-btn');
  const summaryEl  = document.getElementById('edit-summary');

  // ══ التحقق ══
  function validate() {
    const total  = parseFloat(totalInput?.value) || 0;
    const inputs = document.querySelectorAll('.edit-installment-amount');
    if (!inputs.length) return true;

    let sum      = 0;
    let allOk    = true;

    inputs.forEach((inp, idx) => {
      const val   = parseFloat(inp.value) || 0;
      const errEl = document.getElementById(`edit-err-${idx + 1}`);
      if (val <= 0 && inp.value !== '') {
        if (errEl) { errEl.textContent = 'يجب أن تكون القيمة أكبر من صفر'; errEl.classList.remove('d-none'); }
        inp.classList.add('is-invalid');
        allOk = false;
      } else {
        if (errEl) errEl.classList.add('d-none');
        inp.classList.remove('is-invalid');
      }
      sum += val;
    });

    const over      = sum > total && total > 0;
    const remaining = total - sum;

    summaryEl.innerHTML = `
      <div class="mt-3 p-3 rounded border ${over ? 'border-danger bg-danger bg-opacity-10' : 'border-success bg-success bg-opacity-10'}">
        <div class="row text-center g-2">
          <div class="col-4">
            <div class="small text-muted">الإجمالي</div>
            <div class="fw-bold">${total.toFixed(2)}</div>
          </div>
          <div class="col-4">
            <div class="small text-muted">مجموع الدفعات</div>
            <div class="fw-bold ${over ? 'text-danger' : ''}">${sum.toFixed(2)}</div>
          </div>
          <div class="col-4">
            <div class="small text-muted">المتبقي</div>
            <div class="fw-bold ${remaining < 0 ? 'text-danger' : 'text-success'}">${remaining.toFixed(2)}</div>
          </div>
        </div>
        ${over ? '<div class="text-danger text-center small mt-2 fw-bold">⚠️ مجموع الدفعات يتجاوز الإجمالي!</div>' : ''}
      </div>`;

    const isValid = allOk && !over;
    submitBtn.disabled = !isValid;
    submitBtn.classList.toggle('opacity-50', !isValid);
    return isValid;
  }

  // ── ربط الأحداث على الحقول الموجودة ──
  function bindInputs() {
    document.querySelectorAll('.edit-installment-amount').forEach(inp => {
      inp.addEventListener('input', validate);
    });
  }

  if (totalInput) totalInput.addEventListener('input', validate);
  bindInputs();
  validate(); // تشغيل أول مرة

  // ══ إظهار/إخفاء قسم الدفعات ══
  document.getElementById('payment_type').addEventListener('change', function () {
    const isInst = this.value === 'installments';
    document.querySelector('.installments-box').classList.toggle('d-none', !isInst);
    if (!isInst) {
      document.getElementById('installments_container').innerHTML = '';
      summaryEl.innerHTML = '';
      submitBtn.disabled = false;
      submitBtn.classList.remove('opacity-50');
    }
  });

  // ══ بناء حقول الدفعات عند تغيير العدد ══
  document.getElementById('installments_count').addEventListener('change', function () {
    const count     = parseInt(this.value);
    const container = document.getElementById('installments_container');
    container.innerHTML = '';
    summaryEl.innerHTML = '';

    if (!count) return;

    for (let i = 1; i <= count; i++) {
      container.innerHTML += `
        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="fw-bold">قيمة الدفعة ${i}</label>
            <input type="number" step="0.01" min="0.01"
                   name="installments[${i}][amount]"
                   class="form-control edit-installment-amount"
                   required placeholder="0.00">
            <small class="text-danger d-none" id="edit-err-${i}"></small>
          </div>
          <div class="col-md-6">
            <label class="fw-bold">تاريخ الدفعة ${i}</label>
            <input type="date" name="installments[${i}][due_date]"
                   class="form-control" required>
          </div>
        </div>`;
    }

    // إعادة ربط الأحداث على الحقول الجديدة
    bindInputs();
    validate();
  });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/payments/edit_plan.blade.php ENDPATH**/ ?>