@extends('layouts.app')
@section('title','تفاصيل الطلب')

@section('content')

<div class="card shadow-sm p-4">

<h4 class="fw-bold mb-4">تفاصيل طلب الميديا</h4>

<div class="row g-3">

<div class="col-md-6">
<strong>اسم مقدم الطلب:</strong>
<div>{{ $media->requester_name }}</div>
</div>

<div class="col-md-6">
<strong>رقم الهاتف:</strong>
<div>{{ $media->requester_phone }}</div>
</div>

<div class="col-md-6">
<strong>اسم الدبلومة:</strong>
<div>{{ $media->diploma_name }}</div>
</div>

<div class="col-md-6">
<strong>ترميز الدبلومة:</strong>
<div>{{ $media->diploma_code }}</div>
</div>

<div class="col-md-6">
<strong>اسم المدرب:</strong>
<div>{{ $media->trainer_name }}</div>
</div>

<div class="col-md-6">
<strong>مكان المدرب:</strong>
<div>{{ $media->trainer_location }}</div>
</div>

<div class="col-md-6">
<strong>اعتمادية الشهادة:</strong>
<div>{{ $media->certificate_accreditation }}</div>
</div>

<div class="col-md-6">
<strong>مسؤول خدمة العملاء:</strong>
<div>{{ $media->customer_service_responsible }}</div>
</div>

<div class="col-md-6">
<strong>مكان تنفيذ الدبلومة:</strong>
<div>{{ $media->diploma_location }}</div>
</div>

<div class="col-md-6">
<strong>تصوير المدرب متاح؟</strong>
<div>{{ $media->trainer_photography_available ? 'نعم' : 'لا' }}</div>
</div>

<div class="col-md-6">
<strong>ملف التفاصيل:</strong>
@if($media->details_file)
<a href="{{ asset('storage/'.$media->details_file) }}" target="_blank">
عرض الملف
</a>
@endif
</div>

<div class="col-md-6">
<strong>صورة المدرب:</strong>
@if($media->trainer_image)
<img src="{{ asset('storage/'.$media->trainer_image) }}" width="120">
@endif
</div>

<div class="col-12">
<hr>
<h5>المواد المطلوبة</h5>
<ul>
@if($media->need_ad) <li>اعلان دبلومة</li> @endif
@if($media->need_invitation) <li>دعوة افتتاحية</li> @endif
@if($media->need_review_video) <li>فيديو تقييم</li> @endif
@if($media->need_content) <li>محتوى</li> @endif
@if($media->need_podcast) <li>بودكاست</li> @endif
@if($media->need_carousel) <li>كاروسيل</li> @endif
@if($media->need_other) <li>{{ $media->need_other }}</li> @endif
</ul>
</div>

<div class="col-12">
<strong>ملاحظات:</strong>
<div>{{ $media->notes }}</div>
</div>

</div>

</div>

@endsection