@csrf
@if(isset($diploma))
  @method('PUT')
@endif

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم الدبلومة</label>
    <input name="name" class="form-control" required value="{{ old('name', $diploma->name ?? '') }}"
      placeholder="مثال: دبلوم البرمجة الاحترافية">
  </div>

  <div class="col-md-3">
    <label class="form-label">رمز الدبلومة</label>
    <input name="code" class="form-control" required value="{{ old('code', $diploma->code ?? '') }}"
      placeholder="مثال: PROG-01">
    <div class="form-text">يسمح: حروف/أرقام/شرطة (-) وشرطة سفلية (_).</div>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="is_active" class="form-select">
      <option value="1" @selected(old('is_active', ($diploma->is_active ?? true)) == true)>مفعّلة</option>
      <option value="0" @selected(old('is_active', ($diploma->is_active ?? true)) == false)>غير مفعّلة</option>
    </select>
  </div>

  <div class="col-6">
    <label class="form-label">مجال الدبلومة</label>
    <input name="field" class="form-control" value="{{ old('field', $diploma->field ?? '') }}"
      placeholder="مثال: تقنية معلومات / لغات / إدارة أعمال ...">
  </div>



  <div class="col-6">
    <label class="form-label">نوع الدبلومة</label>
    <select name="type" class="form-select" required>
      <option value="">اختر النوع</option>
      <option value="onsite" @selected(old('type') == 'onsite')>
        حضوري
      </option>
      <option value="online" @selected(old('type') == 'online')>
        أونلاين
      </option>
    </select>
  </div>







  {{-- حقل رفع ملف PDF --}}
  <div class="col-12 mt-3">
    <label class="form-label fw-semibold">
      <i class="bi bi-file-earmark-pdf text-danger"></i>
      ملف تفاصيل الدبلومة (PDF)
    </label>

    {{-- عرض الملف الحالي في حالة التعديل --}}
    @if(isset($diploma) && $diploma->details_pdf)
      <div class="alert alert-light border d-flex align-items-center gap-3 mb-2 py-2">
        <i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>
        <div class="flex-grow-1">
          <div class="fw-semibold small">ملف مرفق حالياً</div>
          <a href="{{ $diploma->pdf_url }}" target="_blank" class="small text-primary">
            عرض الملف / تحميله
          </a>
        </div>
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" name="remove_pdf" value="1" id="remove_pdf">
          <label class="form-check-label small text-danger" for="remove_pdf">حذف الملف</label>
        </div>
      </div>
    @endif

    <input type="file" name="details_pdf" class="form-control @error('details_pdf') is-invalid @enderror"
      accept="application/pdf">
    <div class="form-text">الصيغة المقبولة: PDF فقط. الحجم الأقصى: 10MB.</div>

    @error('details_pdf')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
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
  <button class="btn btn-primary">حفظ</button>
  <a class="btn btn-outline-secondary" href="{{ route('diplomas.index') }}">إلغاء</a>
</div>