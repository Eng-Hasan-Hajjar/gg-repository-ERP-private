
<?php $__env->startSection('title', 'تقديم طلب لوجستي'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">تقديم طلب جديد</h4>
  <a href="<?php echo e(route('asset-requests.index')); ?>" class="btn btn-outline-secondary rounded-pill">رجوع</a>
</div>

<div class="card border-0 shadow-sm" style="max-width:700px;">
  <div class="card-body">
    <form method="POST" action="<?php echo e(route('asset-requests.store')); ?>">
      <?php echo csrf_field(); ?>

      <div class="row g-3">

        
        <div class="col-md-6">
          <label class="fw-bold">نوع الطلب <span class="text-danger">*</span></label>
          <select name="type" id="request-type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="purchase" <?php if(old('type')=='purchase'): echo 'selected'; endif; ?>>🛒 طلب شراء</option>
            <option value="repair"   <?php if(old('type')=='repair'): echo 'selected'; endif; ?>>🔧 طلب إصلاح</option>
            <option value="transfer" <?php if(old('type')=='transfer'): echo 'selected'; endif; ?>>🚚 طلب نقل</option>
          </select>
          <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="col-12" id="transfer-fields" style="<?php echo e(old('type') === 'transfer' ? '' : 'display:none;'); ?>">
          <div class="row g-3 p-3 rounded-3" style="background:#f0f7ff; border: 1px solid #bbd6f5;">
            <div class="col-12">
              <div class="fw-bold text-primary mb-1"><i class="bi bi-arrow-left-right"></i> بيانات النقل</div>
            </div>
            <div class="col-md-6">
              <label class="fw-bold">من فرع <span class="text-danger">*</span></label>
              <select name="from_branch_id" id="from-branch-select" class="form-select <?php $__errorArgs = ['from_branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">— اختر الفرع —</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($b->id); ?>" <?php if(old('from_branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['from_branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
              <label class="fw-bold">إلى فرع <span class="text-danger">*</span></label>
              <select name="to_branch_id" class="form-select <?php $__errorArgs = ['to_branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">— اختر الفرع —</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($b->id); ?>" <?php if(old('to_branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['to_branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
        </div>

        
        <div class="col-md-6">
          <label class="fw-bold">الأولوية <span class="text-danger">*</span></label>
          <select name="priority" class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="normal"  <?php if(old('priority', 'normal') == 'normal'): echo 'selected'; endif; ?>>
              ➖ عادية
            </option>
            <option value="low"     <?php if(old('priority') == 'low'): echo 'selected'; endif; ?>>
              🔽 منخفضة
            </option>
            <option value="urgent"  <?php if(old('priority') == 'urgent'): echo 'selected'; endif; ?>>
              🔴 عاجل
            </option>
          </select>
          <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="col-md-6" id="branch-field-wrapper">
          <label class="fw-bold">الفرع</label>
          <select name="branch_id" id="branch-id-select" class="form-select">
            <option value="">— اختر الفرع —</option>
            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        
        <div class="col-md-6">
          <label class="fw-bold">عنوان الطلب <span class="text-danger">*</span></label>
          <input type="text" name="title" value="<?php echo e(old('title')); ?>"
                 class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                 placeholder="مثال: شراء طابعة / إصلاح جهاز العرض" required>
          <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="col-12">
          <label class="fw-bold">الأصل المرتبط (اختياري)</label>
          <select name="asset_id" class="form-select">
            <option value="">— للإصلاح: اختر الأصل —</option>
            <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($a->id); ?>" <?php if(old('asset_id') == $a->id): echo 'selected'; endif; ?>>
                <?php echo e($a->name); ?> — <?php echo e($a->branch->name ?? ''); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <small class="text-muted">للطلبات المتعلقة بأصل موجود مسبقاً</small>
        </div>

        
        <div class="col-12">
          <label class="fw-bold">التفاصيل</label>
          <textarea name="description" rows="4" class="form-control"
            placeholder="اشرح تفاصيل الطلب، المواصفات، أو سبب الإصلاح..."><?php echo e(old('description')); ?></textarea>
        </div>

        
        <div class="col-12" id="urgentAlert" style="display:none;">
          <div class="alert alert-danger py-2 mb-0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>تنبيه:</strong> الطلبات العاجلة تُرسل إشعاراً فورياً لمدير اللوجستيات.
          </div>
        </div>

      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div><?php echo e($e); ?></div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-namaa px-5 fw-bold rounded-pill">
          <i class="bi bi-send"></i> تقديم الطلب
        </button>
        <a href="<?php echo e(route('asset-requests.index')); ?>" class="btn btn-outline-secondary rounded-pill">إلغاء</a>
      </div>

    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prioritySelect = document.querySelector('select[name="priority"]');
    const urgentAlert    = document.getElementById('urgentAlert');

    function toggleAlert() {
        urgentAlert.style.display = prioritySelect.value === 'urgent' ? 'block' : 'none';
    }

    prioritySelect.addEventListener('change', toggleAlert);
    toggleAlert(); // عند التحميل
});

const requestTypeSelect  = document.getElementById('request-type');
const transferFields     = document.getElementById('transfer-fields');
const branchFieldWrapper = document.getElementById('branch-field-wrapper');
const branchSelect       = document.getElementById('branch-id-select');
const fromBranchSelect   = document.getElementById('from-branch-select');

function toggleTransferMode() {
    const isTransfer = requestTypeSelect.value === 'transfer';

    transferFields.style.display = isTransfer ? '' : 'none';
    branchFieldWrapper.style.display = isTransfer ? 'none' : '';

    if (isTransfer) {
        // عبّي قيمة الفرع تلقائياً من "من فرع" عند طلب النقل
        branchSelect.value = fromBranchSelect.value;
    }
}

// عند تغيير نوع الطلب
requestTypeSelect.addEventListener('change', toggleTransferMode);

// عند تغيير "من فرع" أثناء وضع النقل، حدّث قيمة branch_id تلقائياً
fromBranchSelect.addEventListener('change', function () {
    if (requestTypeSelect.value === 'transfer') {
        branchSelect.value = this.value;
    }
});

// تشغيل عند تحميل الصفحة (حال وجود old() بعد خطأ فاليديشن)
toggleTransferMode();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/asset_requests/create.blade.php ENDPATH**/ ?>