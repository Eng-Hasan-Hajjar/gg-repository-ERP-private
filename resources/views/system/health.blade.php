@extends('layouts.app')

@section('title','حالة النظام')

@section('content')

<div class="namaa-hero mb-4">

<h1>مراقبة النظام</h1>

<p class="section-note">

معلومات حالة نظام ERP والبيانات الحالية

</p>

</div>


<div class="row g-4">


<div class="col-md-3">

<div class="module-card p-3">

<h6>عدد المستخدمين</h6>

<h3>{{ $users }}</h3>

</div>

</div>


<div class="col-md-3">

<div class="module-card p-3">

<h6>عدد الطلاب</h6>

<h3>{{ $students }}</h3>

</div>

</div>


<div class="col-md-3">

<div class="module-card p-3">

<h6>الحركات المالية اليوم</h6>

<h3>{{ $transactions }}</h3>

</div>

</div>


<div class="col-md-3">

<div class="module-card p-3">

<h6>حجم قاعدة البيانات</h6>

<h3>{{ $size }} MB</h3>

</div>

</div>


</div>


<div class="mt-4 module-card p-3">

<h6>آخر نسخة احتياطية</h6>

<p>

{{ $lastBackup ?? 'لا يوجد Backup بعد' }}

</p>

</div>


@endsection