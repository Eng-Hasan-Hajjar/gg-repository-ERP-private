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
  $selectedDiplomas = old('diploma_ids', $studentDiplomas->pluck('id')->toArray());
@endphp




<style>
  /* ───── Diploma Picker ───── */
  .diploma-picker {
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    background: #f8fafc;
  }

  .diploma-search-box {
    position: relative;
    margin-bottom: 12px;
  }

  .diploma-search-box input {
    padding-right: 40px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
  }

  .diploma-search-box .search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
  }

  .diploma-list {
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
  }

  .diploma-list-item {
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    transition: background .15s;
    font-size: 14px;
  }

  .diploma-list-item:last-child {
    border-bottom: none;
  }

  .diploma-list-item:hover {
    background: #eff6ff;
  }

  .diploma-list-item.disabled {
    opacity: .4;
    pointer-events: none;
    background: #f1f5f9;
  }

  .diploma-list-item .d-name {
    font-weight: 600;
    color: #1e293b;
  }

  .diploma-list-item .d-meta {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .diploma-list-empty {
    padding: 20px;
    text-align: center;
    color: #94a3b8;
    font-size: 14px;
  }

  /* ───── Selected Cards ───── */
  .selected-diplomas {
    margin-top: 16px;
  }

  .selected-diploma-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    transition: box-shadow .15s;
  }

  .selected-diploma-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
  }

  .sd-info {
    flex: 1;
    min-width: 150px;
  }

  .sd-name {
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
  }

  .sd-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
    margin-top: 4px;
  }

  .sd-badge.online {
    background: #dbeafe;
    color: #2563eb;
  }

  .sd-badge.onsite {
    background: #d1fae5;
    color: #059669;
  }

  .sd-remove {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fee2e2;
    color: #dc2626;
    cursor: pointer;
    transition: background .15s;
  }

  .sd-remove:hover {
    background: #fca5a5;
  }

  .no-diplomas-msg {
    padding: 16px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
  }
</style>




<div class="row g-3">

  {{-- full_name --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الاسم الكامل</label>
    <input name="full_name" value="{{ old('full_name', $student->full_name ?? '') }}"
      class="form-control @error('full_name') is-invalid @enderror">

    @error('full_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- first_name --}}
  <div class="col-md-4" hidden>
    <label class="form-label fw-bold">الاسم</label>
    <input name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}"
      class="form-control @error('first_name') is-invalid @enderror" required>

    @error('first_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- last_name --}}
  <div class="col-md-4" hidden>
    <label class="form-label fw-bold">الكنية</label>
    <input name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}"
      class="form-control @error('last_name') is-invalid @enderror">

    @error('last_name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>



  {{-- phone --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الهاتف</label>
    <input name="phone" value="{{ old('phone', $student->phone ?? '') }}"
      class="form-control @error('phone') is-invalid @enderror">

    @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- whatsapp --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">واتساب</label>
    <input name="whatsapp" value="{{ old('whatsapp', $student->whatsapp ?? '') }}"
      class="form-control @error('whatsapp') is-invalid @enderror">

    @error('whatsapp')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>


  <div class="col-md-4">
    <label class="form-label fw-bold">اتفاق الشهادة الممنوحة</label>
    <select name="certificate_agreement" class="form-select">
      <option value="">-- لا يوجد اتفاق --</option>
      <option value="جراح باشا" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جراح باشا')>جراح باشا</option>
      <option value="بورد الماني" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'بورد الماني')>بورد الماني</option>
      <option value="جامعة تركية" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'جامعة تركية')>جامعة تركية</option>
     <option value="ميديبول" @selected(old('certificate_agreement', $student->certificate_agreement ?? '') == 'ميديبول')>ميديبول</option>

    </select>
  </div>


  {{-- branch_id --}}
  <div class="col-md-4">
    <label class="form-label fw-bold">الفرع</label>

    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">

      <option value="">-- اختر الفرع --</option>

      @foreach($branches as $branch)
        <option value="{{ $branch->id }}" @selected(old('branch_id', $student->branch_id ?? '') == $branch->id)>
          {{ $branch->name }}
        </option>
      @endforeach

    </select>

    @error('branch_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>





  {{-- mode --}}
  <div class="col-md-4" hidden>
    <label class="form-label fw-bold">نوع الطالب</label>
    <select name="mode" class="form-select">
      <option value="onsite" selected>حضوري</option>
      <option value="online">أونلاين</option>
    </select>
  </div>





  {{-- ✅ Diploma Picker — مثل CRM --}}
  <div class="col-12">
    <label class="form-label fw-bold fs-6">
      <i class="bi bi-mortarboard text-primary"></i> الدبلومات *
    </label>

    <div class="diploma-picker">

      {{-- شريط البحث --}}
      <div class="diploma-search-box">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="diplomaSearch" class="form-control" placeholder="ابحث عن دبلومة بالاسم أو الكود...">
      </div>

      {{-- قائمة الدبلومات --}}
      <div class="diploma-list" id="diplomaList">
        <div class="diploma-list-empty" id="diplomaEmpty" style="display:none">
          <i class="bi bi-search"></i> لا توجد نتائج
        </div>
      </div>

      {{-- الدبلومات المختارة --}}
      <div class="selected-diplomas" id="selectedDiplomas">
        <div class="no-diplomas-msg" id="noDiplomasMsg">
          <i class="bi bi-info-circle"></i> لم يتم اختيار أي دبلومة — اختر من القائمة أعلاه
        </div>
      </div>

    </div>

    <div class="text-muted small mt-1">أول دبلومة تعتبر رئيسية تلقائياً. الدبلومات مفلترة حسب الفرع المختار.</div>

    {{-- Hidden inputs --}}
    <div id="diplomaHiddenInputs"></div>
  </div>
<!--
  <div id="diplomas-details-container"></div>
-->



  {{-- ✅ CRM section --}}
  @if(auth()->user()?->hasPermission('edit_crm_in_student'))
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold mb-3">بيانات CRM (الاستشارات)</h6>

          <div class="row g-3  ">



            {{-- الاسم الأول باللاتيني --}}
            <div class="col-md-3">
              <label class="form-label fw-bold">الاسم باللاتيني</label>
              <input id="latin_first_name" class="form-control" placeholder="Ahmad">
            </div>

            {{-- الكنية باللاتيني --}}
            <div class="col-md-3">
              <label class="form-label fw-bold">الكنية باللاتيني</label>
              <input id="latin_last_name" class="form-control" placeholder="Khalil">
            </div>

            {{-- الاسم الكامل باللاتيني - مخفي يُحفظ تلقائياً --}}
            <div class="col-md-6">
              <label class="form-label fw-bold">الاسم الكامل باللاتيني</label>
              <input name="profile[arabic_full_name]" id="latin_full_name"
                value="{{ old('profile.arabic_full_name', $profile['arabic_full_name'] ?? '') }}"
                class="form-control @error('profile.arabic_full_name') is-invalid @enderror" readonly>
              @error('profile.arabic_full_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>


            <div class="col-md-3 d-none">
              <label class="form-label fw-bold">تاريخ أول تواصل</label>
              <input type="date" name="crm[first_contact_date]" class="form-control"
                value="{{ $crm['first_contact_date'] ?? '' }}">
            </div>







            <div class="col-md-6">
              <label class="form-label fw-bold">الجهة/المؤسسة</label>
              <input name="crm[organization]" class="form-control @error('crm.organization') is-invalid @enderror"
                value="{{ old('crm.organization', $crm['organization'] ?? '') }}">

              @error('crm.organization')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>


            {{-- تاريخ التولد --}}
            <div class="col-md-3">
              <label class="form-label fw-bold">تاريخ التولد</label>

              <input type="date" name="profile[birth_date]"
                value="{{ old('profile.birth_date', $profile['birth_date'] ?? '') }}"
                class="form-control @error('profile.birth_date') is-invalid @enderror">

              @error('profile.birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>


            {{-- الرقم الوطني --}}
            <div class="col-md-3">
              <label class="form-label fw-bold">الرقم الوطني</label>

              <input name="profile[national_id]" value="{{ old('profile.national_id', $profile['national_id'] ?? '') }}"
                class="form-control @error('profile.national_id') is-invalid @enderror">

              @error('profile.national_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>


            <div class="col-md-3">
              <label class="form-label fw-bold">المصدر</label>

              <div class="has-validation">
                <select name="crm[source]" class="form-select @error('crm.source') is-invalid @enderror">

                  <option value="">-- اختر المصدر --</option>

                  @php
                    $sourceOptions = [
                      'ad' => 'إعلان مدفوع',
                      'referral' => 'إحالة / توصية',
                      'social' => 'وسائل التواصل الاجتماعي',
                      'website' => 'الموقع الإلكتروني',
                      'expo' => 'معرض / فعالية',
                      'other' => 'أخرى',
                    ];
                  @endphp

                  @foreach($sourceOptions as $src => $srcLabel)
                    <option value="{{ $src }}" @selected(old('crm.source', $crm['source'] ?? '') === $src)>
                      {{ $srcLabel }}
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
                <select name="crm[stage]" class="form-select @error('crm.stage') is-invalid @enderror">

                  <option value="">-- اختر المرحلة --</option>

                  @php
                    $stageOptions = [
                      'new' => 'جديد',
                      'follow_up' => 'متابعة',
                      'interested' => 'مهتم',
                      'registered' => 'مسجل',
                      'rejected' => 'مرفوض',
                      'postponed' => 'مؤجل',
                    ];
                  @endphp

                  @foreach($stageOptions as $st => $stageLabel)
                    <option value="{{ $st }}" @selected(old('crm.stage', $crm['stage'] ?? '') === $st)>
                      {{ $stageLabel }}
                    </option>
                  @endforeach
                </select>

                @error('crm.stage')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
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

                <select name="profile[education_level]"
                        class="form-select @error('profile.education_level') is-invalid @enderror">

                    <option value="">-- اختر المستوى --</option>

                    @php
                    $educationLevels = [
                        'ابتدائي'   => 'ابتدائي',
                        'اعدادي'    => 'اعدادي',
                        'ثانوي'     => 'ثانوي',
                        'بكالوريوس' => 'بكالوريوس',
                        'ماجستير'   => 'ماجستير',
                        'دكتوراه'   => 'دكتوراه',
                        'لا يوجد'   => 'لا يوجد',
                    ];
                    @endphp

                    @foreach($educationLevels as $val => $eduLabel)
                        <option value="{{ $val }}"
                            @selected(old('profile.education_level', $profile['education_level'] ?? '') == $val)>
                            {{ $eduLabel }}
                        </option>
                    @endforeach

                </select>

                @error('profile.education_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
 


            <div class="col-12">
              <label class="form-label fw-bold">الاحتياج</label>

              <textarea name="crm[need]" class="form-control @error('crm.need') is-invalid @enderror"
                rows="2">{{ old('crm.need', $crm['need'] ?? '') }}</textarea>

              @error('crm.need')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>


                      <div class="col-md-4">
            <label class="form-label fw-bold">ملف الهوية</label>
            <input type="file" name="profile[identity_file]"
              class="form-control @error('profile.identity_file') is-invalid @enderror" accept=".pdf,.png,.jpg">

            @error('profile.identity_file')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['identity_file_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['identity_file_path']) }}">عرض الهوية الحالية</a>
              </div>
            @endif
          </div>



            <div class="col-12">
              <label class="form-label fw-bold">ملاحظات CRM</label>

              <textarea name="crm[notes]" class="form-control @error('crm.notes') is-invalid @enderror"
                rows="2">{{ old('crm.notes', $crm['notes'] ?? '') }}</textarea>

              @error('crm.notes')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>




          </div>
        </div>
      </div>
    </div>




  @endif



  {{-- ✅ Profile section --}}
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold mb-3">الملف التفصيلي للطالب</h6>

        <div class="row g-3">

          {{-- الجنسية --}}
          <div class="col-md-3">
            <label class="form-label fw-bold">الجنسية</label>

            <select name="profile[nationality]" class="form-select @error('profile.nationality') is-invalid @enderror"
              id="nationalitySelect">
              <option value="">-- اختر الجنسية --</option>

              @php
                $nationalities = [
                  'سورية',
                  'سعودية',
                  'مصرية',
                  'عراقية',
                  'أردنية',
                  'لبنانية',
                  'كويتية',
                  'إماراتية',
                  'قطرية',
                  'بحرينية',
                  'عُمانية',
                  'يمنية',
                  'ليبية',
                  'تونسية',
                  'جزائرية',
                  'مغربية',
                  'موريتانية',
                  'سودانية',
                  'صومالية',
                  'فلسطينية',
                  'قمرية',
                  'جيبوتية',
                  'تركية',
                  'إيرانية',
                  'أفغانية',
                  'باكستانية',
                  'أذربيجانية',
                  'كازاخستانية',
                  'أوزبكستانية',
                  'ألمانية',
                  'فرنسية',
                  'بريطانية',
                  'إيطالية',
                  'إسبانية',
                  'هولندية',
                  'بلجيكية',
                  'سويدية',
                  'نرويجية',
                  'دنماركية',
                  'سويسرية',
                  'نمساوية',
                  'يونانية',
                  'بولندية',
                  'رومانية',
                  'روسية',
                  'أمريكية',
                  'كندية',
                  'أسترالية',
                  'هندية',
                  'صينية',
                  'يابانية',
                  'كورية',
                  'برازيلية',
                  'إندونيسية',
                  'ماليزية',
                  'نيجيرية',
                  'إثيوبية',
                  'جنوب أفريقية',
                  'أرجنتينية',
                ];
              @endphp

              @foreach($nationalities as $nat)
                <option value="{{ $nat }}" @selected(old('profile.nationality', $profile['nationality'] ?? '') == $nat)>
                  {{ $nat }}
                </option>
              @endforeach
            </select>

            @error('profile.nationality')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>














          {{-- ✅ الجديد: checkbox + combobox --}}
          <div class="col-md-3">
            <label class="form-label fw-bold">اللغة</label>
            <div class="form-check mt-1 mb-2">
              <input type="checkbox"
                    class="form-check-input"
                    id="hasLangCheck"
                    @if(!empty($profile['level'] ?? '')) checked @endif>
              <label class="form-check-label" for="hasLangCheck">
                يوجد مستوى لغة
              </label>
            </div>

            <div id="langLevelField" style="{{ empty($profile['level'] ?? '') ? 'display:none' : '' }}">
              <select name="profile[level]"
                      class="form-select @error('profile.level') is-invalid @enderror">
                <option value="">-- اختر المستوى --</option>
                @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
                  <option value="{{ $lvl }}"
                    @selected(old('profile.level', $profile['level'] ?? '') == $lvl)>
                    {{ $lvl }}
                  </option>
                @endforeach
              </select>
              @error('profile.level')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- إذا ما في لغة — أرسل null --}}
            <input type="hidden" name="_lang_none" id="langNoneSignal" value="1">
          </div>



          {{-- ستاج/مرحلة بالولاية --}}
          <div class="col-md-3">
            <label class="form-label fw-bold">ستاج بالولاية</label>
            <div class="form-check mt-1">
              <input type="checkbox" class="form-check-input" id="stageCheck" {{ !empty($profile['stage_in_state'] ?? '') ? 'checked' : '' }}>
              <label class="form-check-label" for="stageCheck">يوجد ستاج</label>
            </div>
          </div>

          {{-- ✅ الجديد: combobox بالولايات --}}
          <div class="col-md-3" id="stageField"
              style="{{ !empty($profile['stage_in_state'] ?? '') ? '' : 'display:none' }}">
            <label class="form-label fw-bold">ولاية الستاج</label>
            <select name="profile[stage_in_state]"
                    class="form-select @error('profile.stage_in_state') is-invalid @enderror">
              <option value="">-- اختر الولاية --</option>
              @foreach(['بوصة','عنتاب','كلس','اسطنبول','مرسين'] as $city)
                <option value="{{ $city }}"
                  @selected(old('profile.stage_in_state', $profile['stage_in_state'] ?? '') == $city)>
                  {{ $city }}
                </option>
              @endforeach
              {{-- خيار "أخرى" للإدخال اليدوي --}}
              <option value="أخرى"
                @selected(!empty($profile['stage_in_state'] ?? '') && !in_array($profile['stage_in_state'] ?? '', ['بوصة','عنتاب','كلس','اسطنبول','مرسين']))>
                أخرى
              </option>
            </select>
            @error('profile.stage_in_state')
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
            <label class="form-label fw-bold">العلامة الامتحانية</label>

            <input type="number" step="0.01" name="profile[exam_score]"
              value="{{ old('profile.exam_score', $profile['exam_score'] ?? '') }}"
              class="form-control @error('profile.exam_score') is-invalid @enderror">

            @error('profile.exam_score')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>


            <div class="col-md-4">
              <label class="form-label fw-bold">حالة الطالب</label>

              <select name="status" class="form-select @error('status') is-invalid @enderror">

                <option value="">-- اختر حالة الطالب --</option>

                @foreach($statusOptions as $st => $stLabel)
                  <option value="{{ $st }}" @selected(old('status', $student->status ?? '') == $st)>
                    {{ $stLabel }}
                  </option>
                @endforeach

              </select>

              @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

          <div class="col-12">
            <label class="form-label fw-bold">ملاحظات</label>

            <textarea name="profile[notes]" rows="2"
              class="form-control @error('profile.notes') is-invalid @enderror">{{ old('profile.notes', $profile['notes'] ?? '') }}</textarea>

            @error('profile.notes')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>


          <div class="col-12">
            <label class="form-label fw-bold">الرسالة التي سيتم ارسالها لاحقاً للطالب</label>

            <textarea name="profile[message_to_send]" rows="2"
              class="form-control @error('profile.message_to_send') is-invalid @enderror">{{ old('profile.message_to_send', $profile['message_to_send'] ?? '') }}</textarea>

            @error('profile.message_to_send')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>






          {{-- ✅ ملفات الطالب --}}
          {{-- ✅ ملفات الطالب --}}
          <div class="col-12">
            <hr class="my-2">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">صورة الطالب</label>
            <input type="file" name="profile[photo]" class="form-control @error('profile.photo') is-invalid @enderror"
              accept="image/*">

            @error('profile.photo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['photo_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['photo_path']) }}">عرض الصورة الحالية</a>
              </div>
            @endif
          </div>


          <div class="col-md-4">
            <label class="form-label fw-bold">ملف المعلومات</label>
            <input type="file" name="profile[info_file]"
              class="form-control @error('profile.info_file') is-invalid @enderror" accept=".pdf,.doc,.docx,.png,.jpg">

            @error('profile.info_file')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['info_file_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['info_file_path']) }}">عرض الملف الحالي</a>
              </div>
            @endif
          </div>





          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">شهادة حضور</label>
            <input type="file" name="profile[attendance_certificate]"
              class="form-control @error('profile.attendance_certificate') is-invalid @enderror"
              accept=".pdf,.png,.jpg">

            @error('profile.attendance_certificate')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['attendance_certificate_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['attendance_certificate_path']) }}">عرض شهادة
                  الحضور</a>
              </div>
            @endif
          </div>


          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">الشهادة PDF</label>
            <input type="file" name="profile[certificate_pdf]"
              class="form-control @error('profile.certificate_pdf') is-invalid @enderror" accept=".pdf">

            @error('profile.certificate_pdf')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['certificate_pdf_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['certificate_pdf_path']) }}">عرض شهادة PDF</a>
              </div>
            @endif
          </div>


          <div class="col-md-4" hidden>
            <label class="form-label fw-bold">الشهادة (كرتون)</label>
            <input type="file" name="profile[certificate_card]"
              class="form-control @error('profile.certificate_card') is-invalid @enderror" accept=".pdf,.png,.jpg">

            @error('profile.certificate_card')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(!empty($profile['certificate_card_path']))
              <div class="small mt-2">
                <a target="_blank" href="{{ asset('storage/' . $profile['certificate_card_path']) }}">عرض شهادة
                  الكرتون</a>
              </div>
            @endif
          </div>




          @if($studentDiplomas->count())
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
                      <option value="active" @selected($d->pivot->status == 'active')>نشط</option>
                      <option value="waiting" @selected($d->pivot->status == 'waiting')>بانتظار</option>
                      <option value="finished" @selected($d->pivot->status == 'finished')>منتهي</option>
                    </select>
                  </div>

                  <div class="col-md-3  d-none">
                    <label>التقييم (1–5)</label>
                    <input type="number" min="1" max="5" name="diplomas[{{ $d->id }}][rating]"
                      value="{{ $d->pivot->rating }}" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label>تاريخ انتهاء الدبلومة</label>
                    <input type="date" name="diplomas[{{ $d->id }}][ended_at]" value="{{ $d->pivot->ended_at }}"
                      class="form-control">
                  </div>

                  <div class="col-md-3">
                    <label style="margin-top: 10%;">تم تسليم الشهادة الكرتون ؟</label>
                    <input type="checkbox" name="diplomas[{{ $d->id }}][certificate_delivered]"
                      @checked($d->pivot->certificate_delivered)>
                  </div>

                  <div class="col-md-6">
                    <label>ملاحظات خاصة بهذه الدبلومة</label>
                    <textarea name="diplomas[{{ $d->id }}][notes]" class="form-control"
                      rows="2">{{ $d->pivot->notes }}</textarea>
                  </div>

                  <div class="col-md-4">
                    <label>شهادة الحضور</label>
                    <input type="file" name="diplomas[{{ $d->id }}][attendance_certificate]" class="form-control">

                    @if($d->pivot->attendance_certificate_path)
                      <a target="_blank" href="{{ asset('storage/' . $d->pivot->attendance_certificate_path) }}">
                        عرض الحالي
                      </a>
                    @endif
                  </div>

                  <div class="col-md-4">
                    <label>الشهادة PDF</label>
                    <input type="file" name="diplomas[{{ $d->id }}][certificate_pdf]" class="form-control" accept=".pdf">

                    @if($d->pivot->certificate_pdf_path)
                      <a target="_blank" href="{{ asset('storage/' . $d->pivot->certificate_pdf_path) }}">
                        عرض الحالي
                      </a>
                    @endif
                  </div>

                  <div class="col-md-4">
                    <label>كرت الشهادة</label>
                    <input type="file" name="diplomas[{{ $d->id }}][certificate_card]" class="form-control">

                    @if($d->pivot->certificate_card_path)
                      <a target="_blank" href="{{ asset('storage/' . $d->pivot->certificate_card_path) }}">
                        عرض الحالي
                      </a>
                    @endif
                  </div>

                </div>
              </div>

            @endforeach
          @endif







        </div>

      </div>
    </div>
  </div>


</div>



<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary fw-bold px-4">حفظ</button>
  <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.index') }}">إلغاء</a>
</div>


















@php
  $diplomasJson = $diplomas->map(fn($d) => [
    'id' => $d->id,
    'name' => $d->name,
    'code' => $d->code,
    'type' => $d->type,
    'branch_id' => $d->branch_id,
    'branch_name' => $d->branch->name ?? '-',
  ])->values();

  $preloadedJson = $studentDiplomasJson ?? '[]';
@endphp

<script>
  var modeSelect = document.querySelector('[name="mode"]');
  if (modeSelect && !modeSelect.value) modeSelect.value = 'onsite';
  (function () {
    var allDiplomas = {{ Js::from($diplomasJson) }};
    var preloaded = {{ Js::from(json_decode($preloadedJson)) }};

    var diplomasByName = {};
    allDiplomas.forEach(function (d) {
      if (!diplomasByName[d.name]) {
        diplomasByName[d.name] = { name: d.name, type: d.type, variants: [] };
      }
      diplomasByName[d.name].variants.push({ id: d.id, code: d.code, branch_id: d.branch_id, branch_name: d.branch_name });
    });

    var diplomaNames = Object.values(diplomasByName);
    var selectedDiplomas = {};

    preloaded.forEach(function (d) {
      if (diplomasByName[d.name]) {
        selectedDiplomas[d.name] = { variantId: d.id, code: d.code, branch_id: d.branch_id };
      }
    });

    var diplomaList = document.getElementById('diplomaList');
    var diplomaSearch = document.getElementById('diplomaSearch');
    var diplomaEmpty = document.getElementById('diplomaEmpty');
    var selectedContainer = document.getElementById('selectedDiplomas');
    var noDiplomasMsg = document.getElementById('noDiplomasMsg');
    var hiddenInputs = document.getElementById('diplomaHiddenInputs');
    var branchSelect = document.querySelector('[name="branch_id"]');
    var detailsContainer = document.getElementById('diplomas-details-container');

    function renderList(filter) {
      filter = filter || '';
      diplomaList.querySelectorAll('.diploma-list-item').forEach(function (el) { el.remove(); });
      var branchId = branchSelect ? branchSelect.value : '';
      var count = 0;

      diplomaNames.forEach(function (group) {
        var nameOk = !filter || group.name.indexOf(filter) !== -1 ||
          group.variants.some(function (v) { return v.code && v.code.indexOf(filter) !== -1; });

        var branchOk = !branchId ||
          group.variants.some(function (v) { return v.branch_id == branchId; });

        if (!nameOk || !branchOk) return;

        var isSelected = !!selectedDiplomas[group.name];
        var codes = group.variants.map(function (v) { return v.code; }).filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');
        var branches = group.variants.map(function (v) { return v.branch_name; }).filter(function (v, i, a) { return a.indexOf(v) === i; }).join('، ');

        var item = document.createElement('div');
        item.className = 'diploma-list-item' + (isSelected ? ' disabled' : '');
        item.innerHTML =
          '<div><span class="d-name">' + group.name + '</span>' +
          '<div class="d-meta"><span><i class="bi bi-tag"></i> ' + codes + '</span>' +
          '<span><i class="bi bi-building"></i> ' + branches + '</span></div></div>' +
          '<div><span class="badge ' + (group.type === 'online' ? 'bg-primary' : 'bg-success') + '" style="font-size:11px">' +
          (group.type === 'online' ? 'أونلاين' : 'حضوري') + '</span>' +
          (isSelected ? '<i class="bi bi-check-circle-fill text-success ms-2"></i>' : '<i class="bi bi-plus-circle text-primary ms-2"></i>') +
          '</div>';

        if (!isSelected) {
          item.addEventListener('click', function () { addDiploma(group); });
        }
        diplomaList.appendChild(item);
        count++;
      });

      diplomaEmpty.style.display = count === 0 ? 'block' : 'none';
    }

    function addDiploma(group) {
      var branchId = branchSelect ? branchSelect.value : '';
      var variants = group.variants;
      if (branchId) {
        variants = variants.filter(function (v) { return v.branch_id == branchId; });
      }
      if (!variants.length) return;

      var def = variants[0];
      selectedDiplomas[group.name] = { variantId: def.id, code: def.code, branch_id: def.branch_id };

      renderSelected();
      renderList(diplomaSearch ? diplomaSearch.value : '');
      updateHidden();
      renderDetails();
    }

    function removeDiploma(name) {
      delete selectedDiplomas[name];
      renderSelected();
      renderList(diplomaSearch ? diplomaSearch.value : '');
      updateHidden();
      renderDetails();
    }

    function renderSelected() {
      selectedContainer.querySelectorAll('.selected-diploma-card').forEach(function (el) { el.remove(); });
      var names = Object.keys(selectedDiplomas);
      if (!names.length) { noDiplomasMsg.style.display = 'block'; return; }
      noDiplomasMsg.style.display = 'none';

      names.forEach(function (name, index) {
        var sel = selectedDiplomas[name];
        var group = diplomasByName[name];
        if (!group) return;

        var card = document.createElement('div');
        card.className = 'selected-diploma-card';
        card.innerHTML =
          '<div class="sd-info">' +
          '<div class="sd-name">' +
          (index === 0 ? '<span class="badge bg-warning text-dark me-1" style="font-size:10px">رئيسية</span>' : '') +
          name + '</div>' +
          '<span class="sd-badge ' + (group.type === 'online' ? 'online' : 'onsite') + '">' +
          '<i class="bi bi-' + (group.type === 'online' ? 'wifi' : 'geo-alt') + '"></i> ' +
          (group.type === 'online' ? 'أونلاين' : 'حضوري') + '</span>' +
          '<div class="d-meta mt-1" style="font-size:12px;color:#94a3b8"><i class="bi bi-tag"></i> ' + sel.code + '</div>' +
          '</div>' +
          '<div class="sd-remove" data-name="' + name + '" title="إزالة"><i class="bi bi-x-lg"></i></div>';

        card.querySelector('.sd-remove').addEventListener('click', function () {
          removeDiploma(this.dataset.name);
        });
        selectedContainer.appendChild(card);
      });
    }

    function updateHidden() {
      hiddenInputs.innerHTML = '';
      Object.values(selectedDiplomas).forEach(function (sel) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'diploma_ids[]';
        input.value = sel.variantId;
        hiddenInputs.appendChild(input);
      });
    }

    function renderDetails() {
      if (!detailsContainer) return;
      var existing = {};
      detailsContainer.querySelectorAll('[data-diploma-id]').forEach(function (el) {
        existing[el.dataset.diplomaId] = true;
      });

      Object.values(selectedDiplomas).forEach(function (sel) {
        var id = String(sel.variantId);
        if (existing[id]) return;

        var group = null;
        Object.values(diplomasByName).forEach(function (g) {
          g.variants.forEach(function (v) { if (v.id == id) group = g; });
        });
        if (!group) return;

        var div = document.createElement('div');
        div.className = 'card p-3 mb-3 border';
        div.dataset.diplomaId = id;
        div.innerHTML =
          '<h6 class="fw-bold">' + group.name + ' <small class="text-muted">(' + sel.code + ')</small></h6>' +
          '<input type="hidden" name="diplomas[' + id + '][id]" value="' + id + '">' +
          '<div class="row g-3">' +
          '<div class="col-md-3"><label>الحالة</label>' +
          '<select name="diplomas[' + id + '][status]" class="form-select">' +
          '<option value="active">نشط</option><option value="waiting">بانتظار</option><option value="finished">منتهي</option>' +
          '</select></div>' +
          '<div class="col-md-3"><label>تاريخ الانتهاء</label>' +
          '<input type="date" name="diplomas[' + id + '][ended_at]" class="form-control"></div>' +
          '<div class="col-md-6"><label>ملاحظات</label>' +
          '<textarea name="diplomas[' + id + '][notes]" class="form-control" rows="2"></textarea></div>' +
          '</div>';

        detailsContainer.appendChild(div);
      });
    }

    if (diplomaSearch) {
      diplomaSearch.addEventListener('input', function () { renderList(this.value); });
    }

    if (branchSelect) {
      branchSelect.addEventListener('change', function () {
        var modeSelect = document.querySelector('[name="mode"]');
        var text = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';
        if (modeSelect) {
          modeSelect.value = text.includes('أونلاين') ? 'online' : 'onsite';
        }


        var newBranch = this.value;
        Object.keys(selectedDiplomas).forEach(function (name) {
          if (newBranch && selectedDiplomas[name].branch_id != newBranch) {
            delete selectedDiplomas[name];
          }
        });
        renderList(diplomaSearch ? diplomaSearch.value : '');
        renderSelected();
        updateHidden();
      });
    }

    document.addEventListener('DOMContentLoaded', function () {





      var stageCheck = document.getElementById('stageCheck');
      var stageField = document.getElementById('stageField');
      var stageInput = stageField ? stageField.querySelector('input') : null;

      if (stageCheck && stageField) {
        stageCheck.addEventListener('change', function () {
          if (this.checked) {
            stageField.style.display = '';
          } else {
            stageField.style.display = 'none';
            if (stageInput) stageInput.value = ''; // مسح القيمة عند إلغاء التحديد
          }
        });
      }




      if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#nationalitySelect').select2({
          placeholder: 'ابحث عن جنسية...',
          allowClear: true,
          width: '100%',
          language: {
            noResults: function () { return 'لا توجد نتائج'; },
            searching: function () { return 'جاري البحث...'; }
          }
        });
      }







      var latinFirst = document.getElementById('latin_first_name');
      var latinLast = document.getElementById('latin_last_name');
      var latinFull = document.getElementById('latin_full_name');

      function mergeLatinName() {
        var first = latinFirst ? latinFirst.value.trim() : '';
        var last = latinLast ? latinLast.value.trim() : '';
        if (latinFull) latinFull.value = (first + ' ' + last).trim();
      }

      // عند تحميل الصفحة — تقسيم القيمة الموجودة للحقلين
      if (latinFull && latinFull.value) {
        var parts = latinFull.value.trim().split(/\s+/);
        if (latinFirst) latinFirst.value = parts[0] || '';
        if (latinLast) latinLast.value = parts.slice(1).join(' ') || '';
      }

      if (latinFirst) latinFirst.addEventListener('input', mergeLatinName);
      if (latinLast) latinLast.addEventListener('input', mergeLatinName);



      var fullName = document.querySelector('input[name="full_name"]');
      var firstName = document.querySelector('input[name="first_name"]');
      var lastName = document.querySelector('input[name="last_name"]');
      if (fullName && firstName && lastName) {
        firstName.setAttribute('readonly', true);
        lastName.setAttribute('readonly', true);
        fullName.addEventListener('input', function () {
          var parts = this.value.trim().split(/\s+/);
          firstName.value = parts[0] || '';
          lastName.value = parts.slice(1).join(' ') || '';
        });
      }

      renderList();
      renderSelected();
      updateHidden();
      renderDetails();
    });
  })();


  // ✅ checkbox اللغة
document.addEventListener('DOMContentLoaded', function () {
  var hasLangCheck  = document.getElementById('hasLangCheck');
  var langLevelField = document.getElementById('langLevelField');
  var langSelect    = langLevelField ? langLevelField.querySelector('select') : null;

  if (hasLangCheck && langLevelField) {
    hasLangCheck.addEventListener('change', function () {
      if (this.checked) {
        langLevelField.style.display = 'block';
      } else {
        langLevelField.style.display = 'none';
        if (langSelect) langSelect.value = ''; // مسح عند إلغاء
      }
    });
  }
});


</script>