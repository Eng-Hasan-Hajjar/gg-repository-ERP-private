
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // ١. الاسم الكامل ← يملأ الاسم والكنية
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  const fullNameInput  = document.querySelector('input[name="full_name"]');
  const firstNameInput = document.querySelector('input[name="first_name"]');
  const lastNameInput  = document.querySelector('input[name="last_name"]');
  if (fullNameInput && firstNameInput && lastNameInput) {
    firstNameInput.setAttribute('readonly', true);
    lastNameInput.setAttribute('readonly', true);
    fullNameInput.addEventListener('input', function () {
      const parts = this.value.trim().split(/\s+/);
      firstNameInput.value = parts[0] || '';
      lastNameInput.value  = parts.slice(1).join(' ') || '';
    });
  }

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // ٢. Diploma Picker — مثل CRM
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  const allDiplomas = @json($diplomasJson);

  // تجميع الدبلومات بنفس الاسم
  const diplomasByName = {};
  allDiplomas.forEach(d => {
    if (!diplomasByName[d.name]) {
      diplomasByName[d.name] = { name: d.name, type: d.type, variants: [] };
    }
    diplomasByName[d.name].variants.push({
      id: d.id, code: d.code,
      branch_id: d.branch_id, branch_name: d.branch_name,
    });
  });
  const diplomaNames   = Object.values(diplomasByName);
  const selectedDiplomas = new Map(); // name → { variantId, code, branch_id }

// تحميل الدبلومات المختارة مسبقاً (عند التعديل) — بيانات جاهزة من Controller
const preloadedDiplomas = JSON.parse('<?php echo addslashes($studentDiplomasJson ?? "[]"); ?>');
preloadedDiplomas.forEach(d => {
    if (diplomasByName[d.name] && !selectedDiplomas.has(d.name)) {
        selectedDiplomas.set(d.name, {
            variantId : d.id,
            code      : d.code,
            branch_id : d.branch_id,
        });
    }
});

  // مراجع DOM
  const diplomaList      = document.getElementById('diplomaList');
  const diplomaSearch    = document.getElementById('diplomaSearch');
  const diplomaEmpty     = document.getElementById('diplomaEmpty');
  const selectedContainer= document.getElementById('selectedDiplomas');
  const noDiplomasMsg    = document.getElementById('noDiplomasMsg');
  const hiddenInputs     = document.getElementById('diplomaHiddenInputs');
  const branchSelect     = document.querySelector('[name="branch_id"]');
  const detailsContainer = document.getElementById('diplomas-details-container');

  // ━━ عرض القائمة ━━
  function renderDiplomaList(filter = '') {
    diplomaList.querySelectorAll('.diploma-list-item').forEach(el => el.remove());
    const selectedBranchId = branchSelect?.value ?? '';
    let visibleCount = 0;

    diplomaNames.forEach(group => {
      const nameMatch = filter === '' ||
        group.name.includes(filter) ||
        group.variants.some(v => v.code && v.code.includes(filter));

      const hasMatchingBranch = !selectedBranchId ||
        group.variants.some(v => v.branch_id == selectedBranchId);

      if (!nameMatch || !hasMatchingBranch) return;

      const isSelected = selectedDiplomas.has(group.name);
      const codes    = [...new Set(group.variants.map(v => v.code))].join('، ');
      const branches = [...new Set(group.variants.map(v => v.branch_name))].join('، ');

      const item = document.createElement('div');
      item.className = 'diploma-list-item' + (isSelected ? ' disabled' : '');
      item.innerHTML = `
        <div>
          <span class="d-name">${group.name}</span>
          <div class="d-meta">
            <span><i class="bi bi-tag"></i> ${codes}</span>
            <span><i class="bi bi-building"></i> ${branches}</span>
          </div>
        </div>
        <div>
          <span class="badge ${group.type === 'online' ? 'bg-primary' : 'bg-success'}" style="font-size:11px">
            ${group.type === 'online' ? 'أونلاين' : 'حضوري'}
          </span>
          ${isSelected
            ? '<i class="bi bi-check-circle-fill text-success ms-2"></i>'
            : '<i class="bi bi-plus-circle text-primary ms-2"></i>'}
        </div>`;

      if (!isSelected) item.addEventListener('click', () => addDiploma(group));
      diplomaList.appendChild(item);
      visibleCount++;
    });

    diplomaEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  // ━━ إضافة دبلومة ━━
  function addDiploma(group) {
    const selectedBranchId = branchSelect?.value ?? '';
    let variants = group.variants;
    if (selectedBranchId) variants = variants.filter(v => v.branch_id == selectedBranchId);
    if (variants.length === 0) return;

    const def = variants[0];
    selectedDiplomas.set(group.name, {
      variantId: def.id, code: def.code, branch_id: def.branch_id,
    });

    renderSelectedDiplomas();
    renderDiplomaList(diplomaSearch.value);
    updateHiddenInputs();
    renderDetailsCards();
  }

  // ━━ حذف دبلومة ━━
  function removeDiploma(name) {
    selectedDiplomas.delete(name);
    renderSelectedDiplomas();
    renderDiplomaList(diplomaSearch.value);
    updateHiddenInputs();
    renderDetailsCards();
  }

  // ━━ عرض الكاردات المختارة ━━
  function renderSelectedDiplomas() {
    selectedContainer.querySelectorAll('.selected-diploma-card').forEach(el => el.remove());
    if (selectedDiplomas.size === 0) { noDiplomasMsg.style.display = 'block'; return; }
    noDiplomasMsg.style.display = 'none';

    let index = 0;
    selectedDiplomas.forEach((selection, name) => {
      const group = diplomasByName[name];
      if (!group) return;
      const isPrimary = index === 0;

      const card = document.createElement('div');
      card.className = 'selected-diploma-card';
      card.innerHTML = `
        <div class="sd-info">
          <div class="sd-name">
            ${isPrimary ? '<span class="badge bg-warning text-dark me-1" style="font-size:10px">رئيسية</span>' : ''}
            ${name}
          </div>
          <span class="sd-badge ${group.type === 'online' ? 'online' : 'onsite'}">
            <i class="bi bi-${group.type === 'online' ? 'wifi' : 'geo-alt'}"></i>
            ${group.type === 'online' ? 'أونلاين' : 'حضوري'}
          </span>
          <div class="d-meta mt-1" style="font-size:12px; color:#94a3b8">
            <i class="bi bi-tag"></i> ${selection.code} &nbsp;
          </div>
        </div>
        <div class="sd-remove" data-name="${name}" title="إزالة">
          <i class="bi bi-x-lg"></i>
        </div>`;

      card.querySelector('.sd-remove').addEventListener('click', function () {
        removeDiploma(this.dataset.name);
      });

      selectedContainer.appendChild(card);
      index++;
    });
  }

  // ━━ تحديث hidden inputs ━━
  function updateHiddenInputs() {
    hiddenInputs.innerHTML = '';
    selectedDiplomas.forEach((selection) => {
      const input = document.createElement('input');
      input.type  = 'hidden';
      input.name  = 'diploma_ids[]';
      input.value = selection.variantId;
      hiddenInputs.appendChild(input);
    });
  }

  // ━━ كاردات التفاصيل (الحالة، الانتهاء، الملاحظات) ━━
  function renderDetailsCards() {
    if (!detailsContainer) return;
    // احتفظ بالكاردات الموجودة (من قاعدة البيانات) ولا تمسحها
    // فقط أضف للجديدة
    const existingIds = new Set(
      [...detailsContainer.querySelectorAll('[data-diploma-id]')]
        .map(el => el.dataset.diplomaId)
    );

    selectedDiplomas.forEach((selection) => {
      const id = String(selection.variantId);
      if (existingIds.has(id)) return; // موجودة مسبقاً → لا تضف مجدداً

      const group = Object.values(diplomasByName).find(
        g => g.variants.some(v => v.id == id)
      );
      if (!group) return;

      const div = document.createElement('div');
      div.className = 'card p-3 mb-3 border';
      div.dataset.diplomaId = id;
      div.innerHTML = `
        <h6 class="fw-bold">${group.name} <small class="text-muted">(${selection.code})</small></h6>
        <input type="hidden" name="diplomas[${id}][id]" value="${id}">
        <div class="row g-3">
          <div class="col-md-3">
            <label>الحالة</label>
            <select name="diplomas[${id}][status]" class="form-select">
              <option value="active">نشط</option>
              <option value="waiting">بانتظار</option>
              <option value="finished">منتهي</option>
            </select>
          </div>
          <div class="col-md-3">
            <label>تاريخ الانتهاء</label>
            <input type="date" name="diplomas[${id}][ended_at]" class="form-control">
          </div>
          <div class="col-md-6">
            <label>ملاحظات</label>
            <textarea name="diplomas[${id}][notes]" class="form-control" rows="2"></textarea>
          </div>
        </div>`;

      detailsContainer.appendChild(div);
    });

    // إزالة الكاردات لدبلومات تم حذفها
    existingIds.forEach(id => {
      const stillSelected = [...selectedDiplomas.values()].some(s => String(s.variantId) === id);
      // فقط إذا كانت مضافة بـ JS (ليست من قاعدة البيانات الأصلية)
      if (!stillSelected) {
        const el = detailsContainer.querySelector(`[data-diploma-id="${id}"]`);
        // لا نحذف كاردات قاعدة البيانات الـ @foreach الموجودة بـ Blade
      }
    });
  }

  // ━━ أحداث البحث والفرع ━━
  diplomaSearch.addEventListener('input', function () {
    renderDiplomaList(this.value.trim());
  });

  if (branchSelect) {
    branchSelect.addEventListener('change', function () {
      // عند تغيير الفرع: أزل الدبلومات التي لا تنتمي للفرع الجديد
      const newBranchId = this.value;
      selectedDiplomas.forEach((selection, name) => {
        if (newBranchId && selection.branch_id != newBranchId) {
          selectedDiplomas.delete(name);
        }
      });
      renderDiplomaList(diplomaSearch.value.trim());
      renderSelectedDiplomas();
      updateHiddenInputs();
    });
  }

  // ━━ التهيئة الأولى ━━
  renderDiplomaList();
  renderSelectedDiplomas();
  updateHiddenInputs();
  renderDetailsCards();

}); // end DOMContentLoaded
</script>