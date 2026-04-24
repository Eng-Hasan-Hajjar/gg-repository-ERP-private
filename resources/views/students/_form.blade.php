


@csrf

@if(isset($student) && $student->exists)
  @method('PUT')
@endif



@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif


@php
  $selectedDiplomas = old('diploma_ids',
    isset($student) ? $student->diplomas->pluck('id')->toArray() : []
  );

  $crm = old('crm', isset($student) && $student->crmInfo ? $student->crmInfo->toArray() : []);
  $profile = old('profile', isset($student) && $student->profile ? $student->profile->toArray() : []);
@endphp




<div class="row g-3">

        {{-- first_name --}}
        <div class="col-md-4">
        <label class="form-label fw-bold">الاسم</label>
        <input name="first_name"
        value="{{ old('first_name',$student->first_name ?? '') }}"
        class="form-control @error('first_name') is-invalid @enderror" required>

        @error('first_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </div>

      {{-- last_name --}}
      <div class="col-md-4">
      <label class="form-label fw-bold">الكنية</label>
      <input name="last_name"
      value="{{ old('last_name',$student->last_name ?? '') }}"
      class="form-control @error('last_name') is-invalid @enderror">

      @error('last_name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      {{-- full_name --}}
      <div class="col-md-4">
      <label class="form-label fw-bold">الاسم الكامل</label>
      <input name="full_name"
      value="{{ old('full_name',$student->full_name ?? '') }}"
      class="form-control @error('full_name') is-invalid @enderror">

      @error('full_name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      {{-- phone --}}
      <div class="col-md-4">
      <label class="form-label fw-bold">الهاتف</label>
      <input name="phone"
      value="{{ old('phone',$student->phone ?? '') }}"
      class="form-control @error('phone') is-invalid @enderror">

      @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      {{-- whatsapp --}}
      <div class="col-md-4">
      <label class="form-label fw-bold">واتساب</label>
      <input name="whatsapp"
      value="{{ old('whatsapp',$student->whatsapp ?? '') }}"
      class="form-control @error('whatsapp') is-invalid @enderror">

      @error('whatsapp')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>


<div class="row g-3 mt-3">
  <div class="col-12">
    <div class="form-check">
      <input type="hidden" name="certificate_agreement" value="0">

<input class="form-check-input" type="checkbox" 
       id="certificate_agreement"
       name="certificate_agreement"
       value="1"
       {{ old('certificate_agreement', $student->certificate_agreement ?? 0) ? 'checked' : '' }}>
      <label class="form-check-label fw-bold" for="certificate_agreement">
        اتفاق الشهادة الممنوحة
      </label>
    </div>
  </div>
</div>

      {{-- branch_id --}}
<div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>

    <select name="branch_id"
            class="form-select @error('branch_id') is-invalid @enderror">

        <option value="">-- اختر الفرع --</option>

        @foreach($branches as $branch)
            <option value="{{ $branch->id }}"
                @selected(old('branch_id', $student->branch_id ?? '') == $branch->id)>
                {{ $branch->name }}
            </option>
        @endforeach

    </select>

    @error('branch_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>




 {{-- mode --}}
<div class="col-md-4">
<label class="form-label fw-bold">نوع الطالب</label>
<select name="mode"
class="form-select @error('mode') is-invalid @enderror">

<option value="">اختر النوع</option>
<option value="onsite" @selected(old('mode',$student->mode ?? '')=='onsite')>حضوري</option>
<option value="online" @selected(old('mode',$student->mode ?? '')=='online')>أونلاين</option>

</select>

@error('mode')
<div class="invalid-feedback">{{ $message }}</div>
@enderror
</div>


      <div class="col-md-4">
        <label class="form-label fw-bold">حالة الطالب</label>

        <select name="status"
                class="form-select @error('status') is-invalid @enderror">

            <option value="">-- اختر حالة الطالب --</option>

            @foreach($statusOptions as $st => $label)
                <option value="{{ $st }}"
                   @selected(old('status', $student->status ?? '') == $st)>
                    {{ $label }}
                </option>
            @endforeach

        </select>

        @error('status')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>


  {{-- ✅ Multi Diplomas --}}
  <div class="col-12">
    <label class="form-label fw-bold ">الدبلومات (يمكن اختيار عدة دبلومات)</label>
    <select class="form-select @error('diploma_ids') is-invalid @enderror" name="diploma_ids[]" multiple size="6" required>
      
      @foreach($diplomas as $d)
        <option value="{{ $d->id }}" @selected(in_array($d->id,$selectedDiplomas))>{{ $d->name }} ({{ $d->code }})</option>
      @endforeach
    </select>
    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً.</div>


          @error('diploma_ids')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
  </div>


  <div id="diplomas-details-container"></div>




  {{-- ✅ CRM section --}}
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

        <div class="row g-3  " >

          <div class="col-md-3 d-none">
            <label class="form-label fw-bold">تاريخ أول تواصل</label>
            <input type="date" name="crm[first_contact_date]" class="form-control" value="{{ $crm['first_contact_date'] ?? '' }}">
          </div>

     


      


          <div class="col-md-6">
            <label class="form-label fw-bold">الجهة/المؤسسة</label>
            <input name="crm[organization]"
                  class="form-control @error('crm.organization') is-invalid @enderror"
                  value="{{ old('crm.organization', $crm['organization'] ?? '') }}">

            @error('crm.organization')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>




<div class="col-md-3">
  <label class="form-label fw-bold">المصدر</label>

  <div class="has-validation">
    <select name="crm[source]"
            class="form-select @error('crm.source') is-invalid @enderror">

      <option value="">-- اختر المصدر --</option>

      @foreach([
        'ad'       => 'إعلان مدفوع',
        'referral' => 'إحالة / توصية',
        'social'   => 'وسائل التواصل الاجتماعي',
        'website'  => 'الموقع الإلكتروني',
        'expo'     => 'معرض / فعالية',
        'other'    => 'أخرى'
      ] as $src => $label)

        <option value="{{ $src }}"
          @selected(old('crm.source', $crm['source'] ?? '') === $src)>
          {{ $label }}
        </option>

      @endforeach
    </select>

    @error('crm.source')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>
</div>



<div class="col-md-3">
  <label class="form-label fw-bold">المرحلة</label>

  <div class="has-validation">
    <select name="crm[stage]"
            class="form-select @error('crm.stage') is-invalid @enderror">

      <option value="">-- اختر المرحلة --</option>

      @foreach([
        'new'        => 'جديد',
        'follow_up'  => 'متابعة',
        'interested' => 'مهتم',
        'registered' => 'مسجل',
        'rejected'   => 'مرفوض',
        'postponed'  => 'مؤجل'
      ] as $st => $label)

        <option value="{{ $st }}"
          @selected(old('crm.stage', $crm['stage'] ?? '') === $st)>
          {{ $label }}
        </option>

      @endforeach
    </select>

    @error('crm.stage')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>
</div>




<div class="col-12">
  <label class="form-label fw-bold">الاحتياج</label>

  <textarea name="crm[need]"
            class="form-control @error('crm.need') is-invalid @enderror"
            rows="2">{{ old('crm.need', $crm['need'] ?? '') }}</textarea>

  @error('crm.need')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>



<div class="col-12">
  <label class="form-label fw-bold">ملاحظات CRM</label>

  <textarea name="crm[notes]"
            class="form-control @error('crm.notes') is-invalid @enderror"
            rows="2">{{ old('crm.notes', $crm['notes'] ?? '') }}</textarea>

  @error('crm.notes')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
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

          
      



          {{-- الاسم باللاتيني --}}
          <div class="col-md-6">
              <label class="form-label fw-bold">الاسم باللاتيني</label>

              <input name="profile[arabic_full_name]"
                    value="{{ old('profile.arabic_full_name', $profile['arabic_full_name'] ?? '') }}"
                    class="form-control @error('profile.arabic_full_name') is-invalid @enderror">

              @error('profile.arabic_full_name')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror

              <div class="text-muted small mt-1">
                  إذا لم تدخل الاسم بالعربي سنملؤه تلقائياً من الاسم الكامل.
              </div>
          </div>


          {{-- الجنسية --}}
          <div class="col-md-3">
              <label class="form-label fw-bold">الجنسية</label>

              <input name="profile[nationality]"
                    value="{{ old('profile.nationality', $profile['nationality'] ?? '') }}"
                    class="form-control @error('profile.nationality') is-invalid @enderror">

              @error('profile.nationality')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>


          {{-- تاريخ التولد --}}
          <div class="col-md-3">
              <label class="form-label fw-bold">تاريخ التولد</label>

              <input type="date"
                    name="profile[birth_date]"
                    value="{{ old('profile.birth_date', $profile['birth_date'] ?? '') }}"
                    class="form-control @error('profile.birth_date') is-invalid @enderror">

              @error('profile.birth_date')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>


          {{-- الرقم الوطني --}}
          <div class="col-md-3">
              <label class="form-label fw-bold">الرقم الوطني</label>

              <input name="profile[national_id]"
                    value="{{ old('profile.national_id', $profile['national_id'] ?? '') }}"
                    class="form-control @error('profile.national_id') is-invalid @enderror">

              @error('profile.national_id')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>












    
        <div class="col-md-3">
            <label class="form-label fw-bold">مستوى اللغة</label>

            <input name="profile[level]"
                  value="{{ old('profile.level', $profile['level'] ?? '') }}"
                  class="form-control @error('profile.level') is-invalid @enderror"
                  placeholder="مثال: مبتدئ / متوسط / متقدم">

            @error('profile.level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">ستاج/مرحلة بالولاية</label>

            <input name="profile[stage_in_state]"
                  value="{{ old('profile.stage_in_state', $profile['stage_in_state'] ?? '') }}"
                  class="form-control @error('profile.stage_in_state') is-invalid @enderror">

            @error('profile.stage_in_state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


         <div class="col-md-3">
            <label class="form-label fw-bold">العمل</label>
            <input name="crm[job]" class="form-control @error('crm.job') is-invalid @enderror"
                    value="{{ old('crm.job', $crm['job'] ?? '') }}">


            @error('crm.job')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>
          <div class="col-md-4">
            <label class="form-label fw-bold">مسؤول التواصل</label>
            <input class="form-control" value="{{ $student->crmInfo->creator->name ?? '-' }}" disabled>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">البلد</label>
            <input name="crm[country]" class="form-control @error('crm.country') is-invalid @enderror"
                  value="{{ old('crm.country', $crm['country'] ?? '') }}">


                  @error('crm.country')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">المحافظة</label>
            <input name="crm[province]" class="form-control  @error('crm.province') is-invalid @enderror"
                  value="{{ old('crm.province', $crm['province'] ?? '') }}">


              @error('crm.province')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror


          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">الدراسة</label>
            <input name="crm[study]" class="form-control @error('crm.study') is-invalid @enderror"
                  value="{{ old('crm.study', $crm['study'] ?? '') }}">

                    @error('crm.study')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>




          <div class="col-md-4">
              <label class="form-label fw-bold">المستوى التعليمي</label>

              <input name="profile[education_level]"
                    value="{{ old('profile.education_level', $profile['education_level'] ?? '') }}"
                    class="form-control @error('profile.education_level') is-invalid @enderror"
                    placeholder="ثانوي / جامعة / ...">

              @error('profile.education_level')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>


          <div class="col-md-4">
              <label class="form-label fw-bold">العلامة الامتحانية</label>

              <input type="number" step="0.01"
                    name="profile[exam_score]"
                    value="{{ old('profile.exam_score', $profile['exam_score'] ?? '') }}"
                    class="form-control @error('profile.exam_score') is-invalid @enderror">

              @error('profile.exam_score')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>


          <div class="col-12">
              <label class="form-label fw-bold">ملاحظات</label>

              <textarea name="profile[notes]"
                        rows="2"
                        class="form-control @error('profile.notes') is-invalid @enderror">{{ old('profile.notes', $profile['notes'] ?? '') }}</textarea>

              @error('profile.notes')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>


          <div class="col-12">
              <label class="form-label fw-bold">الرسالة التي سيتم ارسالها لاحقاً للطالب</label>

              <textarea name="profile[message_to_send]"
                        rows="2"
                        class="form-control @error('profile.message_to_send') is-invalid @enderror">{{ old('profile.message_to_send', $profile['message_to_send'] ?? '') }}</textarea>

              @error('profile.message_to_send')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>






        {{-- ✅ ملفات الطالب --}}
        {{-- ✅ ملفات الطالب --}}
        <div class="col-12"><hr class="my-2"></div>

        <div class="col-md-4">
          <label class="form-label fw-bold">صورة الطالب</label>
          <input type="file"
                name="profile[photo]"
                class="form-control @error('profile.photo') is-invalid @enderror"
                accept="image/*">

          @error('profile.photo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['photo_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['photo_path']) }}">عرض الصورة الحالية</a>
            </div>
          @endif
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">رابط/ملف المعلومات</label>
          <input type="file"
                name="profile[info_file]"
                class="form-control @error('profile.info_file') is-invalid @enderror"
                accept=".pdf,.doc,.docx,.png,.jpg">

          @error('profile.info_file')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['info_file_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['info_file_path']) }}">عرض الملف الحالي</a>
            </div>
          @endif
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">ملف الهوية</label>
          <input type="file"
                name="profile[identity_file]"
                class="form-control @error('profile.identity_file') is-invalid @enderror"
                accept=".pdf,.png,.jpg">

          @error('profile.identity_file')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['identity_file_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['identity_file_path']) }}">عرض الهوية الحالية</a>
            </div>
          @endif
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">شهادة حضور</label>
          <input type="file"
                name="profile[attendance_certificate]"
                class="form-control @error('profile.attendance_certificate') is-invalid @enderror"
                accept=".pdf,.png,.jpg">

          @error('profile.attendance_certificate')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['attendance_certificate_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['attendance_certificate_path']) }}">عرض شهادة الحضور</a>
            </div>
          @endif
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة PDF</label>
          <input type="file"
                name="profile[certificate_pdf]"
                class="form-control @error('profile.certificate_pdf') is-invalid @enderror"
                accept=".pdf">

          @error('profile.certificate_pdf')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['certificate_pdf_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['certificate_pdf_path']) }}">عرض شهادة PDF</a>
            </div>
          @endif
        </div>


        <div class="col-md-4">
          <label class="form-label fw-bold">الشهادة (كرتون)</label>
          <input type="file"
                name="profile[certificate_card]"
                class="form-control @error('profile.certificate_card') is-invalid @enderror"
                accept=".pdf,.png,.jpg">

          @error('profile.certificate_card')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if(!empty($profile['certificate_card_path']))
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile['certificate_card_path']) }}">عرض شهادة الكرتون</a>
            </div>
          @endif
        </div>


       

      <hr>
<h5 class="fw-bold">تفاصيل وملفات حسب الدبلومة</h5>

@foreach($student->diplomas as $d)

<div class="card p-3 mb-3 border">

  <h6 class="fw-bold">{{ $d->name }}</h6>

  <input type="hidden" name="diplomas[{{ $d->id }}][id]" value="{{ $d->id }}">

  <div class="row g-3">

    <div class="col-md-3">
      <label>الحالة في الدبلومة</label>
      <select name="diplomas[{{ $d->id }}][status]" class="form-select">
        <option value="active" @selected($d->pivot->status=='active')>نشط</option>
        <option value="waiting" @selected($d->pivot->status=='waiting')>بانتظار</option>
        <option value="finished" @selected($d->pivot->status=='finished')>منتهي</option>
      </select>
    </div>

    <div class="col-md-3  d-none" >
      <label>التقييم (1–5)</label>
      <input type="number" min="1" max="5"
             name="diplomas[{{ $d->id }}][rating]"
             value="{{ $d->pivot->rating }}"
             class="form-control">
    </div>

    <div class="col-md-3">
      <label>تاريخ انتهاء الدبلومة</label>
      <input type="date"
             name="diplomas[{{ $d->id }}][ended_at]"
             value="{{ $d->pivot->ended_at }}"
             class="form-control">
    </div>

    <div class="col-md-3" >
      <label style="margin-top: 10%;"  >تم  تسليم الشهادة الكرتون ؟</label>
      <input type="checkbox"
             name="diplomas[{{ $d->id }}][certificate_delivered]"
             @checked($d->pivot->certificate_delivered)>
    </div>

    <div class="col-md-6">
      <label>ملاحظات خاصة بهذه الدبلومة</label>
      <textarea name="diplomas[{{ $d->id }}][notes]"
                class="form-control" rows="2">{{ $d->pivot->notes }}</textarea>
    </div>

    <div class="col-md-4">
      <label>شهادة الحضور</label>
      <input type="file"
             name="diplomas[{{ $d->id }}][attendance_certificate]"
             class="form-control">

      @if($d->pivot->attendance_certificate_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->attendance_certificate_path) }}">
           عرض الحالي
        </a>
      @endif
    </div>

    <div class="col-md-4">
      <label>الشهادة PDF</label>
      <input type="file"
             name="diplomas[{{ $d->id }}][certificate_pdf]"
             class="form-control" accept=".pdf">

      @if($d->pivot->certificate_pdf_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->certificate_pdf_path) }}">
           عرض الحالي
        </a>
      @endif
    </div>

    <div class="col-md-4">
      <label>كرت الشهادة</label>
      <input type="file"
             name="diplomas[{{ $d->id }}][certificate_card]"
             class="form-control">

      @if($d->pivot->certificate_card_path)
        <a target="_blank"
           href="{{ asset('storage/'.$d->pivot->certificate_card_path) }}">
           عرض الحالي
        </a>
      @endif
    </div>

  </div>
</div>

@endforeach







      </div>

    </div>
  </div>
</div>


</div>



<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.index') }}">إلغاء</a>
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
</script>