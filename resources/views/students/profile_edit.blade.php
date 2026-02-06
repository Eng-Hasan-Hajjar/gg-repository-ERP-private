@extends('layouts.app')
@section('title','الملف التفصيلي')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3 gap-2">
  <div>
    <h4 class="mb-1 fw-bold">الملف التفصيلي — {{ $student->full_name }}</h4>
    <div class="text-muted">رقم : <code>{{ $student->university_id }}</code></div>
  </div>

  <a class="btn btn-outline-dark rounded-pill px-4 fw-bold" href="{{ route('students.show',$student) }}">
    <i class="bi bi-arrow-return-right"></i> العودة لملف الطالب
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('students.profile.update',$student) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold">الاسم باللاتيني</label>
          <input name="arabic_full_name" class="form-control" value="{{ old('arabic_full_name', $profile->arabic_full_name) }}">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">الرقم الوطني</label>
          <input name="national_id" class="form-control" value="{{ old('national_id', $profile->national_id) }}">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">تاريخ التولد</label>
          <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($profile->birth_date)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">الجنسية</label>
          <input name="nationality" class="form-control" value="{{ old('nationality', $profile->nationality) }}">
        </div>


        

        <div class="col-md-4">
          <label class="form-label fw-bold">المستوى التعليمي</label>
          <input name="education_level" class="form-control" value="{{ old('education_level', $profile->education_level) }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-bold">ملاحظات</label>
          <textarea name="notes" class="form-control" rows="4">{{ old('notes', $profile->notes) }}</textarea>
        </div>

        <hr class="my-2">

        <div class="col-md-4">
          <label class="form-label fw-bold">صورة الطالب</label>
          <input type="file" name="photo" class="form-control">
          @if($profile->photo_path)
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile->photo_path) }}">عرض الصورة الحالية</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">ملف المعلومات</label>
          <input type="file" name="info_file" class="form-control">
          @if($profile->info_file_path)
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile->info_file_path) }}">فتح الملف الحالي</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">ملف الهوية</label>
          <input type="file" name="identity_file" class="form-control">
          @if($profile->identity_file_path)
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile->identity_file_path) }}">فتح الهوية الحالية</a>
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">شهادة PDF</label>
          <input type="file" name="certificate_pdf" class="form-control">
          @if($profile->certificate_pdf_path)
            <div class="small mt-2">
              <a target="_blank" href="{{ asset('storage/'.$profile->certificate_pdf_path) }}">فتح شهادة PDF الحالية</a>
            </div>
          @endif

        </div>

 



        <div class="col-12">
          <label class="form-label fw-bold">رسالة للطالب (لاحقًا)</label>
          <textarea name="message_to_student" class="form-control" rows="3">{{ old('message_to_student', $profile->message_to_student) }}</textarea>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary fw-bold px-4">
          <i class="bi bi-save"></i> حفظ الملف التفصيلي
        </button>
        <a class="btn btn-outline-secondary fw-bold px-4" href="{{ route('students.show',$student) }}">إلغاء</a>
      </div>

    </form>
  </div>
</div>
@endsection
