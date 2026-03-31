@csrf
@if(isset($employee)) @method('PUT') @endif

<div class="row g-3">

    @if(!isset($employee))

    <input type="hidden" name="type" value="{{ $type ?? 'trainer' }}">

    <div class="alert alert-info small ">
      نوع السجل: <strong>
        {{ $type == 'trainer' ? 'مدرب' : 'موظف' }}
      </strong>
    </div>

  @else
  
    <select name="type" class="form-select">
      ...
    </select>

  @endif


  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" required value="{{ old('full_name', $employee->full_name ?? '') }}">
  </div>




  <div class="col-6 col-md-4">
    <label class="form-label fw-bold">الحالة</label>
    <select name="status" class="form-select" required>
      <option value="active" @selected(old('status', $employee->status ?? 'active') == 'active')>نشط</option>
      <option value="inactive" @selected(old('status', $employee->status ?? '') == 'inactive')>غير نشط</option>
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}">
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}">
  </div>



  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">ربط بحساب مستخدم (اختياري)</label>
    <select name="user_id" class="form-select">
      <option value="">— بدون حساب دخول —</option>
      @foreach($users as $user)
        <option value="{{ $user->id }}" @selected(old('user_id', $employee->user_id ?? '') == $user->id)>
          {{ $user->name }} ({{ $user->email }})
        </option>
      @endforeach
    </select>
    <div class="small text-muted mt-1">
      إذا تم الربط سيتمكن هذا الموظف من تسجيل الدخول.
    </div>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select">
      <option value="">—</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id', $employee->branch_id ?? '') == $b->id)>{{ $b->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12 col-md-4">
    <label class="form-label fw-bold">المسمى الوظيفي</label>
    <input name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title ?? '') }}">
  </div>

  <div class="col-12 col-md-6" id="diplomas-field">
    <label class="form-label fw-bold">الدبلومات المرتبطة</label>
    <select name="diploma_ids[]" multiple class="form-select" style="min-height:120px">
      @php($selected = collect(old('diploma_ids', isset($employee) ? $employee->diplomas->pluck('id')->all() : [])))
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected($selected->contains($d->id))>
          {{ $d->name }} ({{ $d->code }})
        </option>
      @endforeach
    </select>
    <div class="small text-muted mt-1">اضغط Ctrl لاختيار أكثر من دبلومة.</div>
  </div>

  <div class="col-12">
    <label class="form-label fw-bold">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $employee->notes ?? '') }}</textarea>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex flex-wrap gap-2">
  <button class="btn btn-namaa rounded-pill px-4 fw-bold">حفظ</button>
  <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">إلغاء</a>
</div>
















<hr class="my-4">
<h6 class="fw-bold mb-2">🗓 جدول الدوام الأسبوعي</h6>
<p class="text-muted small mb-3">
  حدد وقت بداية ونهاية الدوام لكل يوم، أو فعّل "عطلة" إذا كان اليوم إجازة.
</p>


<div class="row g-3" id="schedule-grid">

  @foreach($weekdays as $wd => $label)

    <div class="col-12 col-md-6 col-xl-4">

      <div class="card border rounded-3 shadow-sm h-100
      {{ $scheduleMap[$wd]['is_off'] ? 'border-secondary bg-light' : 'border-primary' }}" id="card_day_{{ $wd }}">

        <div class="card-body p-3">

          <div class="d-flex justify-content-between align-items-center mb-3">

            <span class="fw-bold fs-6">{{ $label }}</span>

            <div class="form-check form-switch mb-0">

              <input class="form-check-input day-off-toggle" type="checkbox" name="schedule[{{ $wd }}][is_off]"
                id="off_{{ $wd }}" value="1" data-wd="{{ $wd }}" {{ $scheduleMap[$wd]['is_off'] ? 'checked' : '' }}>

              <label class="form-check-label small fw-semibold text-muted">
                عطلة
              </label>

            </div>

          </div>

          <div id="time_fields_{{ $wd }}" style="{{ $scheduleMap[$wd]['is_off'] ? 'display:none' : '' }}">

            <div class="row g-2">

              <div class="col-6">

                <label class="form-label small text-muted mb-1">
                  <i class="bi bi-sunrise"></i> بداية الدوام
                </label>

                <input type="time" name="schedule[{{ $wd }}][start]" class="form-control form-control-sm"
                  value="{{ $scheduleMap[$wd]['start'] }}">

              </div>

              <div class="col-6">

                <label class="form-label small text-muted mb-1">
                  <i class="bi bi-sunset"></i> نهاية الدوام
                </label>

                <input type="time" name="schedule[{{ $wd }}][end]" class="form-control form-control-sm"
                  value="{{ $scheduleMap[$wd]['end'] }}">

              </div>

            </div>

          </div>

          <div id="off_label_{{ $wd }}"
            class="text-center text-muted py-2 {{ $scheduleMap[$wd]['is_off'] ? '' : 'd-none' }}">

            <i class="bi bi-moon-stars fs-4 d-block mb-1"></i>
            <span class="small fw-semibold">يوم عطلة</span>

          </div>

        </div>
      </div>

    </div>

  @endforeach

</div>



<script>
  document.querySelectorAll('.day-off-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
      const wd = this.dataset.wd;
      const timeFields = document.getElementById('time_fields_' + wd);
      const offLabel = document.getElementById('off_label_' + wd);
      const card = document.getElementById('card_day_' + wd);

      if (this.checked) {
        timeFields.style.display = 'none';
        offLabel.classList.remove('d-none');
        card.classList.remove('border-primary');
        card.classList.add('border-secondary', 'bg-light');
        timeFields.querySelectorAll('input[type="time"]').forEach(i => i.value = '');
      } else {
        timeFields.style.display = 'block';
        offLabel.classList.add('d-none');
        card.classList.add('border-primary');
        card.classList.remove('border-secondary', 'bg-light');
      }
    });
  });





  function toggleDiplomas() {

    const type = document.querySelector('[name="type"]').value
    const field = document.getElementById('diplomas-field')

    if (type === 'trainer') {
      field.style.display = 'block'
    } else {
      field.style.display = 'none'
    }

  }

  document.querySelector('[name="type"]').addEventListener('change', toggleDiplomas)

  window.addEventListener('load', toggleDiplomas)






</script>