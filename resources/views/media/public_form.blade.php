@extends('layouts.app')
@section('title','طلب ميديا جديد')

@section('content')

<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">إرسال طلب لقسم الميديا</h5>

<form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label>اسم مقدم الطلب</label>
<input type="text" name="requester_name" class="form-control" required>
</div>

<div class="col-md-6">
<label>رقم الهاتف</label>
<input type="text" name="requester_phone" class="form-control">
</div>

<div class="col-md-6">
<label>اسم الدبلومة</label>
<input type="text" name="diploma_name" class="form-control">
</div>

<div class="col-md-6">
<label>ترميز الدبلومة</label>
<input type="text" name="diploma_code" class="form-control">
</div>

<div class="col-md-6">
<label>اسم المدرب</label>
<input type="text" name="trainer_name" class="form-control">
</div>

<div class="col-md-6">
<label>مكان تواجد المدرب</label>
<input type="text" name="trainer_location" class="form-control">
</div>

<div class="col-12">
<div class="form-check">
<input type="checkbox" name="trainer_photography_available" class="form-check-input">
<label class="form-check-label">هل تصوير المدرب متاح؟</label>
</div>
</div>

<div class="col-md-6">
<label>اعتمادية الشهادة</label>
<input type="text" name="certificate_accreditation" class="form-control">
</div>

<div class="col-md-6">
<label>مسؤول خدمة العملاء</label>
<input type="text" name="customer_service_responsible" class="form-control">
</div>

<div class="col-md-6">
<label>مكان تنفيذ الدبلومة</label>
<input type="text" name="diploma_location" class="form-control">
</div>

<div class="col-md-6">
<label>رفع ملف التفاصيل</label>
<input type="file" name="details_file" class="form-control">
</div>

<div class="col-md-6">
<label>رفع صورة المدرب</label>
<input type="file" name="trainer_image" class="form-control">
</div>

<hr>

<h5>المواد المطلوبة</h5>

<div class="col-12">
<div class="row">

<div class="col-md-4"><input type="checkbox" name="need_ad"> اعلان دبلومة</div>
<div class="col-md-4"><input type="checkbox" name="need_invitation"> دعوة جلسة افتتاحية</div>
<div class="col-md-4"><input type="checkbox" name="need_review_video"> فيديو تقييم</div>
<div class="col-md-4"><input type="checkbox" name="need_content"> محتوى</div>
<div class="col-md-4"><input type="checkbox" name="need_podcast"> بودكاست قصير</div>
<div class="col-md-4"><input type="checkbox" name="need_carousel"> كاروسيل</div>

<div class="col-md-6 mt-2">
<input type="text" name="need_other" class="form-control" placeholder="أخرى">
</div>

</div>
</div>

<div class="col-12">
<label>ملاحظات إضافية</label>
<textarea name="notes" class="form-control" rows="3"></textarea>
</div>

<div class="col-12">
<button class="btn btn-namaa w-100">إرسال الطلب</button>
</div>

</div>
</form>


</div>

@endsection