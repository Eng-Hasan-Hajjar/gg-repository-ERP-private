<?php echo csrf_field(); ?>
<?php if(isset($exam)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-bold">اسم الامتحان</label>
    <input name="title" class="form-control" required value="<?php echo e(old('title',$exam->title ?? '')); ?>">
  </div>

  <div class="col-md-3" hidden>
    <label class="form-label fw-bold">الكود (اختياري)</label>
    <input name="code" class="form-control" value="<?php echo e(old('code',$exam->code ?? '')); ?>">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">التاريخ</label>
    <input type="date" name="exam_date" class="form-control"
      value="<?php echo e(old('exam_date', optional($exam->exam_date ?? null)->format('Y-m-d'))); ?>">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">النوع</label>
    <select name="type" class="form-select">
      <?php $__currentLoopData = ['quiz','midterm','final','practical','other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($t); ?>" <?php if(old('type',$exam->type ?? 'other')==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">الحد الأعلى</label>
    <input type="number" step="0.01" name="max_score" class="form-control"
      value="<?php echo e(old('max_score',$exam->max_score ?? 100)); ?>">
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">حد النجاح (اختياري)</label>
    <input type="number" step="0.01" name="pass_score" class="form-control"
      value="<?php echo e(old('pass_score',$exam->pass_score ?? '')); ?>">
  </div>




  




<div class="col-12">
    <label class="form-label fw-bold fs-6">
        <i class="bi bi-mortarboard text-primary"></i> الدبلومة *
    </label>

    <div class="diploma-picker">

        
        <div class="diploma-search-box">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="diplomaSearch" class="form-control" placeholder="ابحث باسم الدبلومة...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" id="diplomaCodeSearch" class="form-control" placeholder="ابحث بكود الدبلومة...">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="diploma-list" id="diplomaList">
            <div class="diploma-list-empty" id="diplomaEmpty" style="display:none">
                <i class="bi bi-search"></i> لا توجد نتائج
            </div>
        </div>

        
        <div class="selected-diploma-container" id="selectedDiplomaContainer">
            <div class="no-diploma-msg" id="noDiplomaMsg">
                <i class="bi bi-info-circle"></i> لم يتم اختيار دبلومة بعد — اختر من القائمة أعلاه
            </div>
        </div>

    </div>

    
    <input type="hidden" name="diploma_id" id="diploma_id" value="<?php echo e(old('diploma_id', $exam->diploma_id ?? '')); ?>">
</div>













  <div class="col-md-6" hidden>
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" >
      <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b->id); ?>" <?php if(old('branch_id',$exam->branch_id ?? '')==$b->id): echo 'selected'; endif; ?>>
          <?php echo e($b->name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label fw-bold">المدرب المسؤول (اختياري)</label>
    <select name="trainer_id" class="form-select">
      <option value="">-</option>
      <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($t->id); ?>" <?php if(old('trainer_id',$exam->trainer_id ?? '')==$t->id): echo 'selected'; endif; ?>>
          <?php echo e($t->full_name); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes',$exam->notes ?? '')); ?></textarea>
  </div>
</div>

<?php if($errors->any()): ?>
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="<?php echo e(route('exams.index')); ?>">إلغاء</a>
</div>

































<?php
  $diplomasJson = $diplomas->map(function ($d) {
    return [
      'id' => $d->id,
      'name' => $d->name,
      'code' => $d->code,
      'type' => $d->type,
      'branch_id' => $d->branch_id,
      'branch_name' => $d->branch->name ?? '—',
    ];
  })->values();
?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const allDiplomas = <?php echo json_encode($diplomasJson, 15, 512) ?>;
    let selectedDiploma = null; // تخزين الدبلومة المختارة (كائن واحد)
    
    // قيمة الدبلومة الحالية من قاعدة البيانات (في حالة التعديل)
    const currentDiplomaId = <?php echo json_encode(old('diploma_id', $exam->diploma_id ?? null), 512) ?>;

    const diplomaList = document.getElementById('diplomaList');
    const diplomaSearch = document.getElementById('diplomaSearch');
    const diplomaCodeSearch = document.getElementById('diplomaCodeSearch');
    const diplomaEmpty = document.getElementById('diplomaEmpty');
    const selectedContainer = document.getElementById('selectedDiplomaContainer');
    const noDiplomaMsg = document.getElementById('noDiplomaMsg');
    const diplomaIdInput = document.getElementById('diploma_id');
    
    // ============================================================
    // تحميل الدبلومة الحالية إذا وجدت (لحالة التعديل)
    // ============================================================
    if (currentDiplomaId) {
        const currentDiploma = allDiplomas.find(d => d.id == currentDiplomaId);
        if (currentDiploma) {
            selectedDiploma = currentDiploma;
        }
    }
    
    // ============================================================
    // عرض قائمة الدبلومات (كل دبلومة على حدة مع الكود)
    // ============================================================
    function renderDiplomaList(nameFilter = '', codeFilter = '') {
      // مسح القائمة
      diplomaList.querySelectorAll('.diploma-list-item').forEach(el => el.remove());
      
      let visibleCount = 0;
      
      // تصفية الدبلومات حسب الاسم والكود
      const filteredDiplomas = allDiplomas.filter(diploma => {
        // فحص إذا كانت الدبلومة هي المختارة حالياً (نخفيها من القائمة)
        if (selectedDiploma && selectedDiploma.id === diploma.id) return false;
        
        // فحص مطابقة الاسم
        const nameMatch = nameFilter === '' || 
                         diploma.name.toLowerCase().includes(nameFilter.toLowerCase());
        
        // فحص مطابقة الكود
        const codeMatch = codeFilter === '' || 
                         (diploma.code && diploma.code.toLowerCase().includes(codeFilter.toLowerCase()));
        
        return nameMatch && codeMatch;
      });
      
      // عرض الدبلومات
      filteredDiplomas.forEach(diploma => {
        const item = document.createElement('div');
        item.className = 'diploma-list-item';
        item.innerHTML = `
          <div style="flex:1">
            <div class="d-flex justify-content-between align-items-start">
              <span class="d-name fw-bold">${escapeHtml(diploma.name)}</span>
              <span class="badge ${diploma.type === 'online' ? 'bg-primary' : 'bg-success'}" style="font-size:11px">
                ${diploma.type === 'online' ? 'أونلاين' : 'حضوري'}
              </span>
            </div>
            <div class="d-meta mt-1">
              <small class="text-muted">
                <i class="bi bi-upc-scan"></i> الكود: <strong class="text-primary">${diploma.code || '—'}</strong>
              </small>
              ${diploma.branch_name ? `<small class="text-muted ms-2"><i class="bi bi-building"></i> ${diploma.branch_name}</small>` : ''}
            </div>
          </div>
          <div>
            <i class="bi bi-plus-circle text-primary fs-5"></i>
          </div>
        `;
        
        item.addEventListener('click', () => selectDiploma(diploma));
        diplomaList.appendChild(item);
        visibleCount++;
      });
      
      diplomaEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
      diplomaEmpty.innerHTML = `
        <i class="bi bi-search"></i> 
        ${nameFilter || codeFilter ? 'لا توجد نتائج تطابق معايير البحث' : 'لا توجد دبلومات متاحة'}
      `;
    }
    
    // ============================================================
    // اختيار دبلومة (استبدال الدبلومة الحالية)
    // ============================================================
    function selectDiploma(diploma) {
      // استبدال الدبلومة المختارة (دبلومة واحدة فقط)
      selectedDiploma = diploma;
      
      // تحديث الحقل المخفي
      diplomaIdInput.value = diploma.id;
      
      // إعادة عرض المكونات
      renderSelectedDiploma();
      renderDiplomaList(diplomaSearch.value, diplomaCodeSearch.value);
    }
    
    // ============================================================
    // إلغاء اختيار الدبلومة
    // ============================================================
    function deselectDiploma() {
      selectedDiploma = null;
      diplomaIdInput.value = '';
      renderSelectedDiploma();
      renderDiplomaList(diplomaSearch.value, diplomaCodeSearch.value);
    }
    
    // ============================================================
    // عرض الدبلومة المختارة
    // ============================================================
    function renderSelectedDiploma() {
      // مسح المحتوى
      selectedContainer.querySelectorAll('.selected-diploma-card').forEach(el => el.remove());
      
      if (!selectedDiploma) {
        noDiplomaMsg.style.display = 'block';
        return;
      }
      
      noDiplomaMsg.style.display = 'none';
      
      const card = document.createElement('div');
      card.className = 'selected-diploma-card';
      card.innerHTML = `
        <div class="sd-info">
          <div class="sd-name fw-bold">${escapeHtml(selectedDiploma.name)}</div>
          <div class="sd-code">
            <small class="text-muted"><i class="bi bi-upc-scan"></i> ${selectedDiploma.code || 'بدون كود'}</small>
          </div>
          <span class="sd-badge ${selectedDiploma.type === 'online' ? 'online' : 'onsite'} mt-1">
            ${selectedDiploma.type === 'online' ? '<i class="bi bi-wifi"></i> أونلاين' : '<i class="bi bi-geo-alt"></i> حضوري'}
          </span>
          ${selectedDiploma.branch_name ? `<div class="mt-1"><small><i class="bi bi-building"></i> ${selectedDiploma.branch_name}</small></div>` : ''}
        </div>
        <div class="sd-remove" title="تغيير الدبلومة">
          <i class="bi bi-arrow-repeat"></i>
        </div>
      `;
      
      card.querySelector('.sd-remove').addEventListener('click', () => deselectDiploma());
      selectedContainer.appendChild(card);
    }
    
    // ============================================================
    // دالة مساعدة لتجنب XSS
    // ============================================================
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
    
    // ============================================================
    // أحداث البحث
    // ============================================================
    let searchTimeout;
    function handleSearch() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        renderDiplomaList(diplomaSearch.value, diplomaCodeSearch.value);
      }, 300);
    }
    
    diplomaSearch.addEventListener('input', handleSearch);
    diplomaCodeSearch.addEventListener('input', handleSearch);
    
    // التهيئة الأولى
    renderSelectedDiploma();
    renderDiplomaList();
  });
</script><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/exams/_form.blade.php ENDPATH**/ ?>