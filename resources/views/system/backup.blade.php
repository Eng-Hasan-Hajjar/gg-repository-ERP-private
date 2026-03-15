@extends('layouts.app')

@section('title','النسخ الاحتياطية')

@section('content')

<div class="namaa-hero mb-4">

<h1>النسخ الاحتياطية للنظام</h1>

<p class="section-note">

يمكنك تحميل نسخة احتياطية كاملة من قاعدة البيانات أو استرجاع نسخة سابقة.

</p>

</div>


<div class="row g-4">


{{-- تحميل النسخة --}}

<div class="col-md-6">

<div class="module-card p-4">

<h5 class="module-title mb-3">

<i class="bi bi-download"></i>

تحميل نسخة احتياطية

</h5>


<p class="section-note mb-3">

سيتم إنشاء ملف SQL يحتوي على جميع بيانات النظام.

قم بحفظه في مكان آمن.

</p>

<a href="{{ route('system.backup.download') }}"
class="btn btn-namaa">

<i class="bi bi-download"></i>

تحميل النسخة الاحتياطية

</a>

</div>

</div>



{{-- استرجاع النسخة --}}

<div class="col-md-6">

<div class="module-card p-4">

<h5 class="module-title mb-3">

<i class="bi bi-upload"></i>

استرجاع نسخة احتياطية

</h5>


<p class="section-note mb-3">

قم برفع ملف SQL لاسترجاع بيانات النظام.

</p>

<form method="POST"
action="{{ route('system.backup.restore') }}"
enctype="multipart/form-data">

@csrf

<input type="file"
name="backup_file"
class="form-control mb-3"
required>

<button class="btn btn-soft-primary">

<i class="bi bi-arrow-clockwise"></i>

استرجاع النسخة

</button>

</form>

</div>

</div>


</div>



<div class="mt-4 alert alert-warning">

<strong>تعليمات مهمة:</strong>

<ul class="mb-0">

<li>قم بتحميل نسخة احتياطية بشكل دوري.</li>

<li>احتفظ بالنسخة في جهازك أو Google Drive.</li>

<li>لا تشارك ملف النسخة الاحتياطية مع أي شخص.</li>

<li>النسخة تحتوي جميع بيانات النظام.</li>

</ul>

</div>

@endsection