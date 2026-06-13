@extends('layouts.app')
@section('title', 'إعدادات النظام')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-0">إعدادات النظام</h4>
    <div class="text-muted small">تخصيص المظهر وضبط الإشعارات</div>
  </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
  @csrf

  @if(session('success'))
    <div class="alert alert-success rounded-3 mb-3">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
  @endif

  <div class="row g-4">

    {{-- ── كارد المظهر ── --}}
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">

          <div class="d-flex align-items-center gap-3 mb-4">
            <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:grid;place-items:center;">
              <i class="bi bi-palette-fill text-white fs-5"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0">المظهر والألوان</h6>
              <div class="text-muted small">تخصيص شكل النظام</div>
            </div>
          </div>

          {{-- وضع اللون --}}
          <div class="mb-4">
            <label class="fw-bold mb-2">وضع العرض</label>
            <div class="d-flex gap-3">

              <label class="d-flex align-items-center gap-2 p-3 border rounded-3 cursor-pointer flex-fill"
                     style="cursor:pointer;transition:.2s;"
                     id="label-light">
                <input type="radio" name="theme_mode" value="light"
                       {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'checked' : '' }}
                       onchange="updateThemePreview(this.value)">
                <div>
                  <div class="fw-bold"><i class="bi bi-sun-fill text-warning"></i> فاتح</div>
                  <div class="text-muted small">Light Mode</div>
                </div>
              </label>

              <label class="d-flex align-items-center gap-2 p-3 border rounded-3 flex-fill"
                     style="cursor:pointer;transition:.2s;"
                     id="label-dark">
                <input type="radio" name="theme_mode" value="dark"
                       {{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'checked' : '' }}
                       onchange="updateThemePreview(this.value)">
                <div>
                  <div class="fw-bold"><i class="bi bi-moon-stars-fill text-primary"></i> داكن</div>
                  <div class="text-muted small">Dark Mode</div>
                </div>
              </label>

            </div>
          </div>

          {{-- الألوان --}}
          <div class="row g-3">
            <div class="col-6">
              <label class="fw-bold mb-1">اللون الرئيسي</label>
              <div class="d-flex align-items-center gap-2">
                <input type="color" name="primary_color"
                       value="{{ $settings['primary_color'] ?? '#0ea5e9' }}"
                       class="form-control form-control-color"
                       style="width:56px;height:44px;border-radius:12px;cursor:pointer;">
                <input type="text" id="primary_color_text"
                       value="{{ $settings['primary_color'] ?? '#0ea5e9' }}"
                       class="form-control small"
                       readonly>
              </div>
            </div>
            <div class="col-6">
              <label class="fw-bold mb-1">اللون الثانوي</label>
              <div class="d-flex align-items-center gap-2">
                <input type="color" name="secondary_color"
                       value="{{ $settings['secondary_color'] ?? '#10b981' }}"
                       class="form-control form-control-color"
                       style="width:56px;height:44px;border-radius:12px;cursor:pointer;">
                <input type="text" id="secondary_color_text"
                       value="{{ $settings['secondary_color'] ?? '#10b981' }}"
                       class="form-control small"
                       readonly>
              </div>
            </div>
          </div>

          {{-- معاينة --}}
          <div class="mt-4 p-3 rounded-3" id="color-preview"
               style="background:linear-gradient(90deg, {{ $settings['primary_color'] ?? '#0ea5e9' }}, {{ $settings['secondary_color'] ?? '#10b981' }});">
            <div class="text-white fw-bold text-center">معاينة الألوان</div>
          </div>



            {{-- ✅ قسم الغياب التلقائي --}}
<div class="mt-4 p-3 rounded-3"
     style="background:rgba(239,68,68,.05);
            border:1px solid rgba(239,68,68,.2);">

  <div class="d-flex align-items-center gap-2 mb-3">
    <div style="width:36px;height:36px;border-radius:10px;
                background:rgba(239,68,68,.12);
                display:grid;place-items:center;">
      <i class="bi bi-alarm-fill" style="color:#dc2626;font-size:16px;"></i>
    </div>
    <div>
      <div class="fw-bold" style="font-size:14px;">الغياب التلقائي</div>
      <div class="text-muted" style="font-size:11px;">
        تحويل الموظف غير المسجّل إلى "غائب" بعد وقت محدد
      </div>
    </div>
  </div>

  <div class="row g-2 align-items-center">
    <div class="col-auto">
      <label class="fw-bold small mb-0" style="color:#991b1b;">
        اعتبار الموظف غائباً بعد الساعة:
      </label>
    </div>
    <div class="col-auto">
      <input type="time"
             name="absent_after_hour"
             class="form-control form-control-sm"
             style="width:130px; border-color:rgba(239,68,68,.3);"
             value="{{ \App\Models\SystemSetting::get('absent_after_hour', '18:00') }}">
    </div>
    <div class="col-12">
      <div class="small mt-1" style="color:#6b7280;">
        <i class="bi bi-info-circle"></i>
        كل موظف لم يسجل دخول قبل هذه الساعة يُحوَّل تلقائياً إلى
        <span class="badge bg-dark" style="font-size:.7rem;">غائب</span>
      </div>
    </div>
  </div>

</div>




        </div>
      </div>
    </div>

    {{-- ── كارد الإشعارات ── --}}
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">

          <div class="d-flex align-items-center gap-3 mb-4">
            <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#ef4444);display:grid;place-items:center;">
              <i class="bi bi-bell-fill text-white fs-5"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0">إعدادات الإشعارات</h6>
              <div class="text-muted small">ضبط مدد تنبيهات متابعة العملاء</div>
            </div>
          </div>

          <div class="mb-4">
            <label class="fw-bold mb-1">
              <span class="badge bg-danger me-1">عاجل</span>
              تنبيه عاجل بعد (ساعة)
            </label>
            <div class="input-group">
              <input type="number" name="alert_followup_hours"
                     value="{{ $settings['alert_followup_hours'] ?? 48 }}"
                     min="1" max="8760" class="form-control">
              <span class="input-group-text">ساعة</span>
            </div>
            <div class="text-muted small mt-1">
              العملاء الذين لم تتم متابعتهم منذ أكثر من هذه المدة يظهرون كتنبيه عاجل (أحمر).
            </div>
          </div>

          <div class="mb-4">
            <label class="fw-bold mb-1">
              <span class="badge bg-warning text-dark me-1">تحذير</span>
              تنبيه تحذيري بعد (ساعة)
            </label>
            <div class="input-group">
              <input type="number" name="alert_warning_hours"
                     value="{{ $settings['alert_warning_hours'] ?? 24 }}"
                     min="1" max="8760" class="form-control">
              <span class="input-group-text">ساعة</span>
            </div>
            <div class="text-muted small mt-1">
              العملاء الذين لم تتم متابعتهم بين هذه المدة والمدة العاجلة يظهرون كتحذير (أصفر).
            </div>
          </div>

          {{-- مرجع سريع --}}
          <div class="p-3 rounded-3" style="background:rgba(14,165,233,.06);border:1px solid rgba(14,165,233,.15);">
            <div class="small fw-bold mb-2"><i class="bi bi-info-circle text-primary"></i> مرجع سريع</div>
            <div class="small text-muted">
              24 ساعة = يوم واحد<br>
              48 ساعة = يومان<br>
              168 ساعة = أسبوع<br>
              720 ساعة = شهر
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>

  <div class="mt-4">
    <button class="btn btn-namaa px-5 fw-bold rounded-pill">
      <i class="bi bi-save2"></i> حفظ الإعدادات
    </button>
  </div>

</form>




<a href="{{ route('system.resources') }}" class="btn btn-namaa rounded-pill px-4 fw-bold mb-3">
  <i class="bi bi-speedometer2"></i> مراقبة الموارد اللحظية
</a>




<script>
// ── معاينة الألوان ──
document.querySelector('input[name="primary_color"]').addEventListener('input', function() {
    document.getElementById('primary_color_text').value = this.value;
    updatePreview();
});
document.querySelector('input[name="secondary_color"]').addEventListener('input', function() {
    document.getElementById('secondary_color_text').value = this.value;
    updatePreview();
});

function updatePreview() {
    const p = document.querySelector('input[name="primary_color"]').value;
    const s = document.querySelector('input[name="secondary_color"]').value;
    document.getElementById('color-preview').style.background =
        `linear-gradient(90deg, ${p}, ${s})`;
}

// ── تمييز وضع اللون ──
function updateThemePreview(mode) {
    document.getElementById('label-light').style.borderColor = mode === 'light' ? '#0ea5e9' : '#e2e8f0';
    document.getElementById('label-dark').style.borderColor  = mode === 'dark'  ? '#0ea5e9' : '#e2e8f0';
}

// تهيئة عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    const current = document.querySelector('input[name="theme_mode"]:checked')?.value || 'light';
    updateThemePreview(current);
});
</script>

@endsection