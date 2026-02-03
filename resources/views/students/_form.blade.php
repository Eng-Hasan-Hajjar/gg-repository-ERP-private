@csrf
@if(isset($student)) @method('PUT') @endif

@php
  $selectedDiplomas = old('diploma_ids',
    isset($student) ? $student->diplomas->pluck('id')->toArray() : []
  );

  $crm = old('crm', isset($student) && $student->crmInfo ? $student->crmInfo->toArray() : []);
  $profile = old('profile', isset($student) && $student->profile ? $student->profile->toArray() : []);
@endphp

<div class="row g-3">

  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم</label>
    <input name="first_name" class="form-control" value="{{ old('first_name',$student->first_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الكنية</label>
    <input name="last_name" class="form-control" value="{{ old('last_name',$student->last_name ?? '-') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" class="form-control" value="{{ old('full_name',$student->full_name ?? '') }}" required>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" class="form-control" value="{{ old('phone',$student->phone ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" class="form-control" value="{{ old('whatsapp',$student->whatsapp ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الإيميل</label>
    <input name="email" type="email" class="form-control" value="{{ old('email',$student->email ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>
    <select name="branch_id" class="form-select" required>
      @foreach($branches as $b)
        <option value="{{ $b->id }}" @selected(old('branch_id',$student->branch_id ?? '')==$b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label fw-bold">نوع الطالب</label>
    <select name="mode" class="form-select" required>
      <option value="onsite" @selected(old('mode',$student->mode ?? 'onsite')==='onsite')>حضوري</option>
      <option value="online" @selected(old('mode',$student->mode ?? '')==='online')>أونلاين</option>
    </select>
  </div>

 <div class="col-md-4">
  <label class="form-label fw-bold">حالة الطالب</label>
  <select name="status" class="form-select" required>
    @foreach([
      'active'                => 'نشط',
      'waiting'               => 'بانتظار التأكيد',
      'paid'                  => 'مدفوع',
      'withdrawn'             => 'منسحب',
      'failed'                => 'راسب',
      'absent_exam'           => 'متغيب عن الامتحان',
      'certificate_delivered' => 'تم تسليم الشهادة',
      'certificate_waiting'   => 'بانتظار الشهادة',
      'registration_ended'    => 'انتهى التسجيل',
      'dismissed'             => 'مفصول',
      'frozen'                => 'مجمّد'
    ] as $st => $label)
      <option value="{{ $st }}" @selected(old('status',$student->status ?? 'waiting') === $st)>
        {{ $label }}
      </option>
    @endforeach
  </select>
</div>


  {{-- ✅ Multi Diplomas --}}
  <div class="col-12">
    <label class="form-label fw-bold">الدبلومات (يمكن اختيار عدة دبلومات)</label>
    <select class="form-select" name="diploma_ids[]" multiple size="6">
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(in_array($d->id,$selectedDiplomas))>{{ $d->name }} ({{ $d->code }})</option>
      @endforeach
    </select>
    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً.</div>
  </div>

  {{-- ✅ CRM section --}}
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label fw-bold">تاريخ أول تواصل</label>
            <input type="date" name="crm[first_contact_date]" class="form-control" value="{{ $crm['first_contact_date'] ?? '' }}">
          </div>

          <div class="col-md-3">
            <label class="form-label fw-bold">العمر</label>
            <input type="number" name="crm[age]" class="form-control" value="{{ $crm['age'] ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-bold">السكن</label>
            <input name="crm[residence]" class="form-control" value="{{ $crm['residence'] ?? '' }}">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-bold">الجهة/المؤسسة</label>
            <input name="crm[organization]" class="form-control" value="{{ $crm['organization'] ?? '' }}">
          </div>

<div class="col-md-3">
  <label class="form-label fw-bold">المصدر</label>
  <select name="crm[source]" class="form-select">
    @foreach([
      'ad'       => 'إعلان مدفوع',
      'referral' => 'إحالة / توصية',
      'social'   => 'وسائل التواصل الاجتماعي',
      'website'  => 'الموقع الإلكتروني',
      'expo'     => 'معرض / فعالية',
      'other'    => 'أخرى'
    ] as $src => $label)
      <option value="{{ $src }}" @selected(($crm['source'] ?? 'other') === $src)>
        {{ $label }}
      </option>
    @endforeach
  </select>
</div>

<div class="col-md-3">
  <label class="form-label fw-bold">المرحلة</label>
  <select name="crm[stage]" class="form-select">
    @foreach([
      'new'        => 'جديد',
      'follow_up'  => 'متابعة',
      'interested' => 'مهتم',
      'registered' => 'مسجل',
      'rejected'   => 'مرفوض',
      'postponed'  => 'مؤجل'
    ] as $st => $label)
      <option value="{{ $st }}" @selected(($crm['stage'] ?? 'registered') === $st)>
        {{ $label }}
      </option>
    @endforeach
  </select>
</div>

          <div class="col-12">
            <label class="form-label fw-bold">الاحتياج</label>
            <textarea name="crm[need]" class="form-control" rows="2">{{ $crm['need'] ?? '' }}</textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">ملاحظات CRM</label>
            <textarea name="crm[notes]" class="form-control" rows="2">{{ $crm['notes'] ?? '' }}</textarea>
          </div>

        </div>
      </div>
    </div>
  </div>

{{-- ✅ Profile section --}}
<div class="col-12">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h6 class="fw-bold mb-3">الملف التفصيلي للطالب</h6>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold">الاسم بالعربي</label>
          <input name="profile[arabic_full_name]" class="form-control"
                 value="{{ $profile['arabic_full_name'] ?? '' }}">
          <div class="text-muted small mt-1">
            إذا لم تدخل الاسم بالعربي سنملؤه تلقائياً من الاسم الكامل.
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">الجنسية</label>
          <input name="profile[nationality]" class="form-control"
                 value="{{ $profile['nationality'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">تاريخ التولد</label>
          <input type="date" name="profile[birth_date]" class="form-control"
                 value="{{ $profile['birth_date'] ?? '' }}">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">الرقم الوطني</label>
          <input name="profile[national_id]" class="form-control"
                 value="{{ $profile['national_id'] ?? '' }}">
        </div>

        <div class="col-md-8">
          <label class="form-label fw-bold">العنوان</label>
          <input name="profile[address]" class="form-control"
                 value="{{ $profile['address'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">المستوى</label>
          <input name="profile[level]" class="form-control"
                 value="{{ $profile['level'] ?? '' }}" placeholder="مثال: مبتدئ / متوسط / متقدم">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">ستاج/مرحلة بالولاية</label>
          <input name="profile[stage_in_state]" class="form-control"
                 value="{{ $profile['stage_in_state'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">العمل</label>
          <input name="profile[job]" class="form-control"
                 value="{{ $profile['job'] ?? '' }}">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-bold">المستوى التعليمي</label>
          <input name="profile[education_level]" class="form-control"
                 value="{{ $profile['education_level'] ?? '' }}" placeholder="ثانوي / جامعة / ...">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">العلامة الامتحانية</label>
          <input type="number" step="0.01" name="profile[exam_score]" class="form-control"
                 value="{{ $profile['exam_score'] ?? '' }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="profile[notes]" class="form-control" rows="2">{{ $profile['notes'] ?? '' }}</textarea>
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">الرسالة التي سيتم ارسالها لاحقاً للطالب</label>
          <textarea name="profile[message_to_send]" class="form-control" rows="2">{{ $profile['message_to_send'] ?? '' }}</textarea>
        </div>

        {{-- ✅ ملفات الطالب --}}
        <div class="col-12"><hr class="my-2"></div>

        <div class="col-md-4">
          <label class="form-label fw-bold">صورة الطالب</label>
          <input type="file" name="profile[photo]" class="form-control" accept="image/*">
          @if(!empty($profile['photo_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['photo_path']) }}">عرض الصورة الحالية</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">رابط/ملف المعلومات</label>
          <input type="file" name="profile[info_file]" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg">
          @if(!empty($profile['info_file_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['info_file_path']) }}">عرض الملف الحالي</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">ملف الهوية</label>
          <input type="file" name="profile[identity_file]" class="form-control" accept=".pdf,.png,.jpg">
          @if(!empty($profile['identity_file_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['identity_file_path']) }}">عرض الهوية الحالية</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">شهادة حضور</label>
          <input type="file" name="profile[attendance_certificate]" class="form-control" accept=".pdf,.png,.jpg">
          @if(!empty($profile['attendance_certificate_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['attendance_certificate_path']) }}">عرض شهادة الحضور</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة PDF</label>
          <input type="file" name="profile[certificate_pdf]" class="form-control" accept=".pdf">
          @if(!empty($profile['certificate_pdf_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['certificate_pdf_path']) }}">عرض شهادة PDF</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة (كرتون)</label>
          <input type="file" name="profile[certificate_card]" class="form-control" accept=".pdf,.png,.jpg">
          @if(!empty($profile['certificate_card_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['certificate_card_path']) }}">عرض شهادة الكرتون</a>
            </div>
          @endif
        </div>

      </div>

    </div>
  </div>
</div>


</div>

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.index') }}">إلغاء</a>
</div>
