@extends('layouts.app')
@section('title', 'إدارة البرنامج')






@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                إدارة البرنامج
            </h4>
            <div class="text-muted small">
                {{ $diploma->name }} — {{ $diploma->code }}
            </div>
        </div>

        <a href="{{ route('programs.management.index') }}" class="btn btn-soft">
            رجوع
        </a>


        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>يرجى تصحيح الأخطاء التالية قبل الحفظ:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    </div>

    <form method="POST" action="{{ route('programs.management.update', $diploma) }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- ================= قسم البرامج ================= --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم البرامج
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <!-- دراسة السوق – تظهر فقط للحضوري -->
                            @if($diploma->type === 'online')
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="market_study" class="form-check-input"
                                        @checked($record->market_study)>
                                    <label class="form-check-label">دراسة السوق</label>
                                </div>
                            @endif
                           

                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="contracts_ready" class="form-check-input"
                                    @checked($record->contracts_ready)>
                                <label class="form-check-label">العقود جاهزة</label>
                            </div>
                            @if($diploma->type === 'online')
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="materials_ready" class="form-check-input"
                                        @checked($record->materials_ready)>
                                    <label class="form-check-label">المادة العلمية جاهزة</label>
                                </div>

                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="sessions_uploaded" class="form-check-input"
                                        @checked($record->sessions_uploaded)>
                                    <label class="form-check-label">رفع الجلسات على الموقع</label>
                                </div>
                            @endif


                             <div class="col-md-4">
                                <label class="form-label">المدرب</label>

                                <select name="trainer_id" class="form-control">

                                    <option value="">-- اختر المدرب --</option>

                                    @foreach($trainers as $trainer)

                                        <option value="{{ $trainer->id }}" @selected($record->trainer_id == $trainer->id)>

                                            {{ $trainer->full_name  }}

                                        </option>

                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">مصدر الشهادة</label>
                                <input type="text" name="certificate_source" value="{{ $record->certificate_source }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">

                                <label class="form-label">ملف التفاصيل</label>

                                <input type="file" name="details_file" class="form-control">

                                @if($record->details_file)

                                    <a href="{{ asset('storage/' . $record->details_file) }}" target="_blank" class="small">

                                        عرض الملف

                                    </a>

                                @endif

                            </div>

                            <div class="col-md-4">
                                <label class="form-label">سعر الدبلومة</label>
                                <input type="number" step="0.01" name="price" value="{{ $record->price }}"
                                    class="form-control">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= قسم الميديا ================= --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم الميديا
                    </div>
                    <div class="card-body">
                        <div class="row g-3">






                            @php
                                $mediaFields = [
                                    'media_form_sent' => 'تم إرسال فورم الميديا',
                                    'direct_ads' => 'إعلان مباشر',
                                    'content_ready' => 'المحتوى جاهز',
                                    'opening_invitation' => 'دعوة افتتاحية',
                                    'opening_snippets' => 'مقتطفات افتتاحية',
                                    'carousel' => 'كاروسيل',
                                    'designs' => 'تصاميم',
                                    'stories' => 'ستوريات'
                                ];
                            @endphp

                            @foreach($mediaFields as $field => $label)
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="{{ $field }}" class="form-check-input"
                                        @checked($record->$field)>
                                    <label class="form-check-label">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach



                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= قسم التسويق ================= --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم التسويق
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">بداية الحملة</label>
                                <input type="date" name="campaign_start" value="{{ $record->campaign_start }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">نهاية الحملة</label>
                                <input type="date" name="campaign_end" value="{{ $record->campaign_end }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">صرف الحملة </label>
                                <input type="number" step="0.01" name="campaign_budget"
                                    value="{{ $record->campaign_budget }}" class="form-control">
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">مسؤول التواصل</label>

                                <input type="text" name="communication_manager" value="{{ $record->communication_manager }}"
                                    class="form-control">
                            </div>


                            <div class="col-md-3">
                                <label class="form-label">عدد الطلاب المثبتين</label>
                                <input type="number" name="confirmed_students" value="{{ $record->confirmed_students }}"
                                    class="form-control">
                            </div>



                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= قسم الامتحانات ================= --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم الامتحانات والوثائق الأكاديمية
                    </div>
                    <div class="card-body">

                        <div class="row g-3">



                            
                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="projects" class="form-check-input" @checked($record->projects)>
                                <label class="form-check-label">
                                    مشاريع
                                </label>
                            </div>



                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="attendance_certificate" class="form-check-input"
                                    @checked($record->attendance_certificate)>
                                <label class="form-check-label">
                                    استلام شهادة الحضور </label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="university_certificate" class="form-check-input"
                                    @checked($record->university_certificate)>
                                <label class="form-check-label">
                                    استلام شهادة الجامعة
                                </label>
                            </div>



                            <div class="col-md-3 form-check">
                                <input type="checkbox" name="cards_ready" class="form-check-input"
                                    @checked($record->cards_ready)>
                                <label class="form-check-label">
                                    البطاقات جاهزة
                                </label>
                            </div>



                            <div class="col-md-3">
                                <label class="form-label">مدة الدبلومة (شهر)</label>
                                <input type="number" name="duration_months" value="{{ $record->duration_months }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                @if($diploma->type === 'online')
                                    <label class="form-label">عدد الساعات</label>
                                @endif
                                @if($diploma->type === 'onsite')
                                    <label class="form-label">عدد الجلسات</label>
                                @endif
                                <input type="number" name="hours" value="{{ $record->hours }}" class="form-control">
                            </div>



                            <div class="col-md-3">
                                <label class="form-label">البداية</label>
                                <input type="date" name="start_date" value="{{ $record->start_date }}" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">النهاية</label>
                                <input type="date" name="end_date" value="{{ $record->end_date }}" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الامتحان النصفي</label>
                                <input type="date" name="mid_exam" value="{{ $record->mid_exam }}" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">الامتحان النهائي</label>
                                <input type="date" name="final_exam" value="{{ $record->final_exam }}" class="form-control">
                            </div>





                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= قسم شؤون الطلاب ================= --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light fw-bold">
                        قسم شؤون الطلاب
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            @php
                                $studentFields = [

                                    'admin_session_1' => 'جلسة ادارية 1',
                                    'admin_session_2' => 'جلسة ادارية 2',
                                    'admin_session_3' => 'جلسة ادارية 3',
                                    'evaluations_done' => 'التقييمات'
                                ];
                            @endphp

                            @foreach($studentFields as $field => $label)
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" name="{{ $field }}" class="form-check-input"
                                        @checked($record->$field)>
                                    <label class="form-check-label">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach


                             @if($diploma->type === 'online')
                            <div class="col-md-3">
                                <label class="form-label">عدد الخريجين</label>
                                <input type="number" name="graduates_count" value="{{ $record->graduates_count }}"
                                    class="form-control">
                            </div>
@endif
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" rows="3" class="form-control">{{ $record->notes }}</textarea>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-namaa px-5 fw-bold">
                حفظ جميع البيانات
            </button>
        </div>

    </form>

@endsection