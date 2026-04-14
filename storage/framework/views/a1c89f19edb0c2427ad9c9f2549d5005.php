


<?php echo csrf_field(); ?>

<?php if(isset($student) && $student->exists): ?>
  <?php echo method_field('PUT'); ?>
<?php endif; ?>



<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($e); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>


<?php
  $selectedDiplomas = old('diploma_ids',
    isset($student) ? $student->diplomas->pluck('id')->toArray() : []
  );

  $crm = old('crm', isset($student) && $student->crmInfo ? $student->crmInfo->toArray() : []);
  $profile = old('profile', isset($student) && $student->profile ? $student->profile->toArray() : []);
?>




<div class="row g-3">

        
        <div class="col-md-4">
        <label class="form-label fw-bold">الاسم</label>
        <input name="first_name"
        value="<?php echo e(old('first_name',$student->first_name ?? '')); ?>"
        class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>

        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

      
      <div class="col-md-4">
      <label class="form-label fw-bold">الكنية</label>
      <input name="last_name"
      value="<?php echo e(old('last_name',$student->last_name ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
      <label class="form-label fw-bold">الاسم الكامل</label>
      <input name="full_name"
      value="<?php echo e(old('full_name',$student->full_name ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
      <label class="form-label fw-bold">الهاتف</label>
      <input name="phone"
      value="<?php echo e(old('phone',$student->phone ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="col-md-4">
      <label class="form-label fw-bold">واتساب</label>
      <input name="whatsapp"
      value="<?php echo e(old('whatsapp',$student->whatsapp ?? '')); ?>"
      class="form-control <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>




      
<div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>

    <select name="branch_id"
            class="form-select <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

        <option value="">-- اختر الفرع --</option>

        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($branch->id); ?>"
                <?php if(old('branch_id', $student->branch_id ?? '') == $branch->id): echo 'selected'; endif; ?>>
                <?php echo e($branch->name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </select>

    <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>




 
<div class="col-md-4">
<label class="form-label fw-bold">نوع الطالب</label>
<select name="mode"
class="form-select <?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

<option value="">اختر النوع</option>
<option value="onsite" <?php if(old('mode',$student->mode ?? '')=='onsite'): echo 'selected'; endif; ?>>حضوري</option>
<option value="online" <?php if(old('mode',$student->mode ?? '')=='online'): echo 'selected'; endif; ?>>أونلاين</option>

</select>

<?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
<div class="invalid-feedback"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>


      <div class="col-md-4">
        <label class="form-label fw-bold">حالة الطالب</label>

        <select name="status"
                class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

            <option value="">-- اختر حالة الطالب --</option>

            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>"
                    <?php if(old('status') == $st): echo 'selected'; endif; ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>

        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>


  
  <div class="col-12">
    <label class="form-label fw-bold ">الدبلومات (يمكن اختيار عدة دبلومات)</label>
    <select class="form-select <?php $__errorArgs = ['diploma_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="diploma_ids[]" multiple size="6" required>
      
      <?php $__currentLoopData = $diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($d->id); ?>" <?php if(in_array($d->id,$selectedDiplomas)): echo 'selected'; endif; ?>><?php echo e($d->name); ?> (<?php echo e($d->code); ?>)</option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً.</div>


          <?php $__errorArgs = ['diploma_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>


  <div id="diplomas-details-container"></div>




  
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

        <div class="row g-3  " >

          <div class="col-md-3 d-none">
            <label class="form-label fw-bold">تاريخ أول تواصل</label>
            <input type="date" name="crm[first_contact_date]" class="form-control" value="<?php echo e($crm['first_contact_date'] ?? ''); ?>">
          </div>

     


      


          <div class="col-md-6">
            <label class="form-label fw-bold">الجهة/المؤسسة</label>
            <input name="crm[organization]"
                  class="form-control <?php $__errorArgs = ['crm.organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('crm.organization', $crm['organization'] ?? '')); ?>">

            <?php $__errorArgs = ['crm.organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>




<div class="col-md-3">
  <label class="form-label fw-bold">المصدر</label>

  <div class="has-validation">
    <select name="crm[source]"
            class="form-select <?php $__errorArgs = ['crm.source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <option value="">-- اختر المصدر --</option>

      <?php $__currentLoopData = [
        'ad'       => 'إعلان مدفوع',
        'referral' => 'إحالة / توصية',
        'social'   => 'وسائل التواصل الاجتماعي',
        'website'  => 'الموقع الإلكتروني',
        'expo'     => 'معرض / فعالية',
        'other'    => 'أخرى'
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <option value="<?php echo e($src); ?>"
          <?php if(old('crm.source', $crm['source'] ?? '') === $src): echo 'selected'; endif; ?>>
          <?php echo e($label); ?>

        </option>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <?php $__errorArgs = ['crm.source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>
</div>



<div class="col-md-3">
  <label class="form-label fw-bold">المرحلة</label>

  <div class="has-validation">
    <select name="crm[stage]"
            class="form-select <?php $__errorArgs = ['crm.stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

      <option value="">-- اختر المرحلة --</option>

      <?php $__currentLoopData = [
        'new'        => 'جديد',
        'follow_up'  => 'متابعة',
        'interested' => 'مهتم',
        'registered' => 'مسجل',
        'rejected'   => 'مرفوض',
        'postponed'  => 'مؤجل'
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <option value="<?php echo e($st); ?>"
          <?php if(old('crm.stage', $crm['stage'] ?? '') === $st): echo 'selected'; endif; ?>>
          <?php echo e($label); ?>

        </option>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <?php $__errorArgs = ['crm.stage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>
</div>




<div class="col-12">
  <label class="form-label fw-bold">الاحتياج</label>

  <textarea name="crm[need]"
            class="form-control <?php $__errorArgs = ['crm.need'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            rows="2"><?php echo e(old('crm.need', $crm['need'] ?? '')); ?></textarea>

  <?php $__errorArgs = ['crm.need'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback"><?php echo e($message); ?></div>
  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>



<div class="col-12">
  <label class="form-label fw-bold">ملاحظات CRM</label>

  <textarea name="crm[notes]"
            class="form-control <?php $__errorArgs = ['crm.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            rows="2"><?php echo e(old('crm.notes', $crm['notes'] ?? '')); ?></textarea>

  <?php $__errorArgs = ['crm.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback"><?php echo e($message); ?></div>
  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>




        </div>
      </div>
    </div>
  </div>


<div class="col-12">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h6 class="fw-bold mb-3">الملف التفصيلي للطالب</h6>

     <div class="row g-3">

          
      



          
          <div class="col-md-6">
              <label class="form-label fw-bold">الاسم باللاتيني</label>

              <input name="profile[arabic_full_name]"
                    value="<?php echo e(old('profile.arabic_full_name', $profile['arabic_full_name'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.arabic_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.arabic_full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

              <div class="text-muted small mt-1">
                  إذا لم تدخل الاسم بالعربي سنملؤه تلقائياً من الاسم الكامل.
              </div>
          </div>


          
          <div class="col-md-3">
              <label class="form-label fw-bold">الجنسية</label>

              <input name="profile[nationality]"
                    value="<?php echo e(old('profile.nationality', $profile['nationality'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          
          <div class="col-md-3">
              <label class="form-label fw-bold">تاريخ التولد</label>

              <input type="date"
                    name="profile[birth_date]"
                    value="<?php echo e(old('profile.birth_date', $profile['birth_date'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          
          <div class="col-md-3">
              <label class="form-label fw-bold">الرقم الوطني</label>

              <input name="profile[national_id]"
                    value="<?php echo e(old('profile.national_id', $profile['national_id'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.national_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>












    
        <div class="col-md-3">
            <label class="form-label fw-bold">مستوى اللغة</label>

            <input name="profile[level]"
                  value="<?php echo e(old('profile.level', $profile['level'] ?? '')); ?>"
                  class="form-control <?php $__errorArgs = ['profile.level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  placeholder="مثال: مبتدئ / متوسط / متقدم">

            <?php $__errorArgs = ['profile.level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">ستاج/مرحلة بالولاية</label>

            <input name="profile[stage_in_state]"
                  value="<?php echo e(old('profile.stage_in_state', $profile['stage_in_state'] ?? '')); ?>"
                  class="form-control <?php $__errorArgs = ['profile.stage_in_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

            <?php $__errorArgs = ['profile.stage_in_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>


         <div class="col-md-3">
            <label class="form-label fw-bold">العمل</label>
            <input name="crm[job]" class="form-control <?php $__errorArgs = ['crm.job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e($crm['job'] ?? ''); ?>">


            <?php $__errorArgs = ['crm.job'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
         </div>
          <div class="col-md-4">
            <label class="form-label fw-bold">مسؤول التواصل</label>
            <input class="form-control" value="<?php echo e($student->crmInfo->creator->name ?? '-'); ?>" disabled>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">البلد</label>
            <input name="crm[country]" class="form-control <?php $__errorArgs = ['crm.country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e($crm['country'] ?? ''); ?>">


                  <?php $__errorArgs = ['crm.country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">المحافظة</label>
            <input name="crm[province]" class="form-control  <?php $__errorArgs = ['crm.province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e($crm['province'] ?? ''); ?>">


              <?php $__errorArgs = ['crm.province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">الدراسة</label>
            <input name="crm[study]" class="form-control <?php $__errorArgs = ['crm.study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e($crm['study'] ?? ''); ?>">

                    <?php $__errorArgs = ['crm.study'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>




          <div class="col-md-4">
              <label class="form-label fw-bold">المستوى التعليمي</label>

              <input name="profile[education_level]"
                    value="<?php echo e(old('profile.education_level', $profile['education_level'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.education_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="ثانوي / جامعة / ...">

              <?php $__errorArgs = ['profile.education_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="col-md-4">
              <label class="form-label fw-bold">العلامة الامتحانية</label>

              <input type="number" step="0.01"
                    name="profile[exam_score]"
                    value="<?php echo e(old('profile.exam_score', $profile['exam_score'] ?? '')); ?>"
                    class="form-control <?php $__errorArgs = ['profile.exam_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

              <?php $__errorArgs = ['profile.exam_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="col-12">
              <label class="form-label fw-bold">ملاحظات</label>

              <textarea name="profile[notes]"
                        rows="2"
                        class="form-control <?php $__errorArgs = ['profile.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('profile.notes', $profile['notes'] ?? '')); ?></textarea>

              <?php $__errorArgs = ['profile.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="col-12">
              <label class="form-label fw-bold">الرسالة التي سيتم ارسالها لاحقاً للطالب</label>

              <textarea name="profile[message_to_send]"
                        rows="2"
                        class="form-control <?php $__errorArgs = ['profile.message_to_send'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('profile.message_to_send', $profile['message_to_send'] ?? '')); ?></textarea>

              <?php $__errorArgs = ['profile.message_to_send'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>






        
        
        <div class="col-12"><hr class="my-2"></div>

        <div class="col-md-4">
          <label class="form-label fw-bold">صورة الطالب</label>
          <input type="file"
                name="profile[photo]"
                class="form-control <?php $__errorArgs = ['profile.photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept="image/*">

          <?php $__errorArgs = ['profile.photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['photo_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['photo_path'])); ?>">عرض الصورة الحالية</a>
            </div>
          <?php endif; ?>
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">رابط/ملف المعلومات</label>
          <input type="file"
                name="profile[info_file]"
                class="form-control <?php $__errorArgs = ['profile.info_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept=".pdf,.doc,.docx,.png,.jpg">

          <?php $__errorArgs = ['profile.info_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['info_file_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['info_file_path'])); ?>">عرض الملف الحالي</a>
            </div>
          <?php endif; ?>
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">ملف الهوية</label>
          <input type="file"
                name="profile[identity_file]"
                class="form-control <?php $__errorArgs = ['profile.identity_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept=".pdf,.png,.jpg">

          <?php $__errorArgs = ['profile.identity_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['identity_file_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['identity_file_path'])); ?>">عرض الهوية الحالية</a>
            </div>
          <?php endif; ?>
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">شهادة حضور</label>
          <input type="file"
                name="profile[attendance_certificate]"
                class="form-control <?php $__errorArgs = ['profile.attendance_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept=".pdf,.png,.jpg">

          <?php $__errorArgs = ['profile.attendance_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['attendance_certificate_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['attendance_certificate_path'])); ?>">عرض شهادة الحضور</a>
            </div>
          <?php endif; ?>
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة PDF</label>
          <input type="file"
                name="profile[certificate_pdf]"
                class="form-control <?php $__errorArgs = ['profile.certificate_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept=".pdf">

          <?php $__errorArgs = ['profile.certificate_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['certificate_pdf_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['certificate_pdf_path'])); ?>">عرض شهادة PDF</a>
            </div>
          <?php endif; ?>
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة (كرتون)</label>
          <input type="file"
                name="profile[certificate_card]"
                class="form-control <?php $__errorArgs = ['profile.certificate_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                accept=".pdf,.png,.jpg">

          <?php $__errorArgs = ['profile.certificate_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <?php if(!empty($profile['certificate_card_path'])): ?>
            <div class="small mt-2">
              <a target="_blank" href="<?php echo e(asset('storage/'.$profile['certificate_card_path'])); ?>">عرض شهادة الكرتون</a>
            </div>
          <?php endif; ?>
        </div>


       

      <hr>
<h5 class="fw-bold">تفاصيل وملفات حسب الدبلومة</h5>

<?php $__currentLoopData = $student->diplomas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="card p-3 mb-3 border">

  <h6 class="fw-bold"><?php echo e($d->name); ?></h6>

  <input type="hidden" name="diplomas[<?php echo e($d->id); ?>][id]" value="<?php echo e($d->id); ?>">

  <div class="row g-3">

    <div class="col-md-3">
      <label>الحالة في الدبلومة</label>
      <select name="diplomas[<?php echo e($d->id); ?>][status]" class="form-select">
        <option value="active" <?php if($d->pivot->status=='active'): echo 'selected'; endif; ?>>نشط</option>
        <option value="waiting" <?php if($d->pivot->status=='waiting'): echo 'selected'; endif; ?>>بانتظار</option>
        <option value="finished" <?php if($d->pivot->status=='finished'): echo 'selected'; endif; ?>>منتهي</option>
      </select>
    </div>

    <div class="col-md-3  d-none" >
      <label>التقييم (1–5)</label>
      <input type="number" min="1" max="5"
             name="diplomas[<?php echo e($d->id); ?>][rating]"
             value="<?php echo e($d->pivot->rating); ?>"
             class="form-control">
    </div>

    <div class="col-md-3">
      <label>تاريخ انتهاء الدبلومة</label>
      <input type="date"
             name="diplomas[<?php echo e($d->id); ?>][ended_at]"
             value="<?php echo e($d->pivot->ended_at); ?>"
             class="form-control">
    </div>

    <div class="col-md-3" >
      <label style="margin-top: 10%;"  >تم  تسليم الشهادة الكرتون ؟</label>
      <input type="checkbox"
             name="diplomas[<?php echo e($d->id); ?>][certificate_delivered]"
             <?php if($d->pivot->certificate_delivered): echo 'checked'; endif; ?>>
    </div>

    <div class="col-md-6">
      <label>ملاحظات خاصة بهذه الدبلومة</label>
      <textarea name="diplomas[<?php echo e($d->id); ?>][notes]"
                class="form-control" rows="2"><?php echo e($d->pivot->notes); ?></textarea>
    </div>

    <div class="col-md-4">
      <label>شهادة الحضور</label>
      <input type="file"
             name="diplomas[<?php echo e($d->id); ?>][attendance_certificate]"
             class="form-control">

      <?php if($d->pivot->attendance_certificate_path): ?>
        <a target="_blank"
           href="<?php echo e(asset('storage/'.$d->pivot->attendance_certificate_path)); ?>">
           عرض الحالي
        </a>
      <?php endif; ?>
    </div>

    <div class="col-md-4">
      <label>الشهادة PDF</label>
      <input type="file"
             name="diplomas[<?php echo e($d->id); ?>][certificate_pdf]"
             class="form-control" accept=".pdf">

      <?php if($d->pivot->certificate_pdf_path): ?>
        <a target="_blank"
           href="<?php echo e(asset('storage/'.$d->pivot->certificate_pdf_path)); ?>">
           عرض الحالي
        </a>
      <?php endif; ?>
    </div>

    <div class="col-md-4">
      <label>كرت الشهادة</label>
      <input type="file"
             name="diplomas[<?php echo e($d->id); ?>][certificate_card]"
             class="form-control">

      <?php if($d->pivot->certificate_card_path): ?>
        <a target="_blank"
           href="<?php echo e(asset('storage/'.$d->pivot->certificate_card_path)); ?>">
           عرض الحالي
        </a>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>







      </div>

    </div>
  </div>
</div>


</div>



<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="<?php echo e(route('students.index')); ?>">إلغاء</a>
</div>





















<script>
document.addEventListener('DOMContentLoaded', function () {

    const diplomaSelect = document.querySelector('[name="diploma_ids[]"]');
    const container = document.getElementById('diplomas-details-container');

    diplomaSelect.addEventListener('change', function () {

        container.innerHTML = '';

        Array.from(this.selectedOptions).forEach(option => {

            const diplomaId = option.value;
            const diplomaName = option.text;

            container.innerHTML += `
                <div class="card p-3 mb-3 border">
                    <h6 class="fw-bold">${diplomaName}</h6>

                    <input type="hidden" name="diplomas[${diplomaId}][id]" value="${diplomaId}">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label>الحالة</label>
                            <select name="diplomas[${diplomaId}][status]" class="form-select">
                                <option value="active">نشط</option>
                                <option value="waiting">بانتظار</option>
                                <option value="finished">منتهي</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>تاريخ الانتهاء</label>
                            <input type="date" name="diplomas[${diplomaId}][ended_at]" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>ملاحظات</label>
                            <textarea name="diplomas[${diplomaId}][notes]" class="form-control"></textarea>
                        </div>


                        

                    </div>
                </div>
            `;
        });

    });

});
</script><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views/students/_form.blade.php ENDPATH**/ ?>