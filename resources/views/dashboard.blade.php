@extends('layouts.app')
@php($isDashboard = true)

@section('title','لوحة التحكم')

@section('dashboard')



    {{-- Header / Intro --}}
  <div class="namaa-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <h1>لوحة التحكم — نظام نماء أكاديمي</h1>
        <p>
         
        </p>
      </div>



    {{-- الجزء الأيسر: ترحيب ذكي --}}
    <div>
      <h1>مرحبًا {{ auth()->user()->name }} </h1>

      <p class="mt-2 mb-1">
        اليوم: {{ now()->locale('ar')->translatedFormat('l d F Y') }}
      </p>

      <p class="section-note">
        {{ auth()->user()->hasRole('super_admin')
            ? 'أنت تعمل بصلاحيات الإدارة العليا.'
            : 'أنت تعمل ضمن نطاق صلاحياتك المصرّح بها.' }}
      </p>
    </div>


      <div class="d-flex gap-2 flex-wrap">
        <span class="chip"><i class="bi bi-shield-lock"></i> نظام صلاحيات</span>
        <span class="chip"><i class="bi bi-graph-up-arrow"></i> تقارير</span>
        <span class="chip"><i class="bi bi-building"></i> فروع متعددة</span>
      </div>
    </div>
  </div>

  {{-- Modules Grid --}}
  <div class="row g-3 g-lg-4 mb-4">

    {{-- Dashboard / Reports --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-primary">
            <i class="bi bi-speedometer2 fs-3"></i>
          </div>
          <div>
            <p class="module-title">اللوحة الرئيسية والتقارير</p>
            <p class="module-sub">ملخص شامل — مؤشرات الأداء — تقارير مالية </p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            عرض إحصائيات سريعة، تقارير Excel/PDF، وتصفية متقدمة حسب الفرع والفترة.
          </p>
        </div>
        <div class="module-actions">
          <a href="{{ route('reports.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح التقارير</a>
          <a href="#" class="btn btn-soft w-100 w-sm-auto">عرض لوحة التحكم</a>
        </div>
      </div>
    </div>

    
    {{-- CRM --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-green">
            <i class="bi bi-headset fs-3"></i>
          </div>
          <div>
            <p class="module-title">قسم الاستشارات والمبيعات (CRM)</p>
            <p class="module-sub">Leads — متابعة — تحويل العميل إلى طالب</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            إدارة العملاء المحتملين، مراحل المتابعة، مصادر العملاء، وتقارير التحويل والإيرادات المتوقعة.
          </p>
        </div>
        <div class="module-actions">
          <a href="{{ route( 'leads.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح CRM</a>
          <a href="{{ route('crm.reports.index') }}" class="btn btn-soft w-100 w-sm-auto">تقارير المبيعات</a>
        </div>
      </div>
    </div>


    {{-- Students --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-blue">
            <i class="bi bi-people-fill fs-3"></i>
          </div>
          <div>
            <p class="module-title">الطلاب</p>
            <p class="module-sub">إدارة ملفات الطلاب — حالات التسجيل — الأقساط</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            إضافة/تعديل بيانات الطلاب، متابعة الحالة والدفعات، وبحث سريع حسب الاسم أو الرقم الجامعي.
          </p>
        </div>
        <div class="module-actions">
          <a href="{{ route('students.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الطلاب</a>
          <a href="{{ route('students.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة طالب</a>
        </div>
      </div>
    </div>


    {{-- Exams --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="module-card">
          <div class="module-head">
            <div class="module-icon grad-purple">
              <i class="bi bi-journal-check fs-3"></i>
            </div>
            <div>
              <p class="module-title">قسم الامتحانات</p>
              <p class="module-sub">إدارة الامتحانات — تسجيل العلامات — نتائج</p>
            </div>
          </div>
          <div class="module-body">
            <p class="section-note">
              امتحانات حضورية/أونلاين، ربط الطلاب بالامتحان، احتساب النتيجة النهائية وإصدار التقارير.
            </p>
          </div>
          <div class="module-actions">
            <a href="{{ route('exams.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الامتحانات</a>
            <a href="{{ route('exams.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة امتحان</a>
          </div>
        </div>
    </div>

    {{-- Finance / Cashboxes --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-amber">
            <i class="bi bi-cash-coin fs-3"></i>
          </div>
          <div>
            <p class="module-title">الصناديق والحسابات المالية</p>
            <p class="module-sub">مقبوض/مدفوع — عملات — أرصدة وتقارير</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            إدارة صناديق الفروع، تسجيل الحركات، رفع مرفقات، وسجل تدقيق للحركات المالية.
          </p>
        </div>
     <div class="module-actions">
        <a href="{{ route('cashboxes.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح النظام المالي</a>
        <a href="{{ route('cashboxes.index', ['status'=>'active']) }}" class="btn btn-soft w-100 w-sm-auto">الصناديق النشطة</a>
      </div>

      </div>
    </div>

    {{-- Attendance / Leaves --}}
<div class="col-12 col-md-6 col-xl-4">
  <div class="module-card">
    <div class="module-head">
      <div class="module-icon grad-rose">
        <i class="bi bi-calendar2-week fs-3"></i>
      </div>
      <div>
        <p class="module-title">الدوام والإجازات</p>
        <p class="module-sub">حضور/انصراف — طلبات إجازة — تقارير</p>
      </div>
    </div>
    <div class="module-body">
      <p class="section-note">
        تقويم شهري، سجلات حضور يومية، تقارير ساعات/تأخير/غياب مع تصدير Excel/PDF.
      </p>
    </div>
    <div class="module-actions grid-2">
      <a href="{{ route('attendance.calendar') }}" class="btn btn-namaa w-100 w-sm-auto">التقويم</a>
      <a href="{{ route('attendance.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح الدوام</a>
      <a href="{{ route('attendance.reports') }}" class="btn btn-soft w-100 w-sm-auto">تقارير الدوام</a>
      <a href="{{ route('leaves.index') }}" class="btn btn-soft w-100 w-sm-auto">طلبات الإجازات</a>
    </div>

  </div>
</div>





    {{-- Tasks --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-slate">
            <i class="bi bi-check2-square fs-3"></i>
          </div>
          <div>
            <p class="module-title">مهام اليوم</p>
            <p class="module-sub">مهام يومية — مسؤوليات — أرشفة</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            إنشاء مهام حسب الفرع، متابعة حالة التنفيذ، وتقارير يومية وأرشفة تلقائية.
          </p>
        </div>
        <div class="module-actions grid-2">
          <a href="{{ route('tasks.index') }}" class="btn btn-namaa w-100 w-sm-auto">فتح المهام</a>
          <a href="{{ route('tasks.create') }}" class="btn btn-namaa w-100 w-sm-auto">إضافة مهمة</a>
         
        </div>
           <div class="module-actions">
      
  <a href="{{ route('tasks.index', ['status'=>'todo']) }}" class="btn btn-soft w-100 w-sm-auto">مهام اليوم </a>
         
        </div>
      </div>
    </div>

    {{-- HR / Trainers --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-primary">
            <i class="bi bi-person-badge-fill fs-3"></i>
          </div>
          <div>
            <p class="module-title">المدربين والموظفين</p>
            <p class="module-sub">ملفات — عقود — مستحقات — ارتباط بالدبلومات</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            ملف كامل للمدرب/الموظف، جدولة دفعات المدربين وربطها بالصناديق، وتقارير مالية متخصصة.
          </p>
        </div>
        <div class="module-actions grid-2">
          <a href="{{  route('employees.index')}}" class="btn btn-namaa w-100 w-sm-auto">فتح الموارد البشرية</a>
                  <a href="{{ route('employees.create') }}" class="btn btn-namaa w-100 w-sm-auto">إضافة مدرب/موظف</a>

          <a href="{{ route('employees.index', ['type'=>'trainer']) }}" class="btn btn-soft w-100 w-sm-auto">المدربين</a>
          <a href="{{ route('employees.index', ['type'=>'employee']) }}" class="btn btn-soft w-100 w-sm-auto">الموظفين</a>


        </div>
      </div>
    </div>


    
    {{-- Users (System Users) - Coming Soon --}}


@if(auth()->user()?->hasPermission('manage_roles'))
<div class="col-12 col-md-6 col-xl-4">
  <div class="module-card">
    <div class="module-head">
      <div class="module-icon grad-slate">
        <i class="bi bi-shield-lock fs-3"></i>
      </div>
      <div>
        <p class="module-title">الأمان والمستخدمون والحوكمة</p>
        <p class="module-sub">حسابات الدخول — الأدوار — الصلاحيات  </p>
      </div>
    </div>

    <div class="module-body">
      <p class="section-note">
        إدارة مستخدمي النظام، التحكم الكامل بالصلاحيات، ومراقبة كل التغييرات عبر سجل التدقيق.
      </p>
    </div>

    <div class="module-actions grid-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-namaa w-100 w-sm-auto">
        إدارة المستخدمين
      </a>

      <a href="{{ route('admin.roles.index') }}" class="btn btn-namaa w-100 w-sm-auto">
        الأدوار والصلاحيات
      </a>


    </div>
     <div class="module-actions">
      <a href="{{ route('admin.audit.index') }}" class="btn btn-soft w-100 w-sm-auto">
        مركز التدقيق 
      </a>
    </div>
  </div>
</div>
@endif




    {{-- Logistics / Assets --}}
    <div class="col-12 col-md-6 col-xl-4">
      <div class="module-card">
        <div class="module-head">
          <div class="module-icon grad-blue">
            <i class="bi bi-box-seam fs-3"></i>
          </div>
          <div>
            <p class="module-title">اللوجستيات وإدارة الأصول</p>
            <p class="module-sub">أصول — مخزون — تصنيف حسب الفرع والحالة</p>
          </div>
        </div>
        <div class="module-body">
          <p class="section-note">
            تسجيل الأجهزة والمعدات، حالة الأصل (جيد/صيانة/خارج الخدمة)، وإدارة المخزون.
          </p>
        </div>
        <div class="module-actions">
          <a href="{{  route('assets.index')}}" class="btn btn-namaa w-100 w-sm-auto">فتح الأصول</a>
          <a href="{{  route('asset-categories.index')}}" class="btn btn-soft w-100 w-sm-auto">تصنيفات الأصول</a>
        </div>
      </div>
    </div>


{{-- Branches --}}
<div class="col-12 col-md-6 col-xl-4">
  <div class="module-card">
    <div class="module-head">
      <div class="module-icon grad-green">
        <i class="bi bi-building fs-3"></i>
      </div>
      <div>
        <p class="module-title">الفروع</p>
        <p class="module-sub">إعدادات الفروع — توزيع العمليات حسب الفرع</p>
      </div>
    </div>

    <div class="module-body">
      <p class="section-note">
        إدارة فروع نماء أكاديمي (ألمانيا، اسطنبول، مرسين...) وربط الطلاب والموظفين والأصول حسب الفرع.
      </p>
    </div>

    <div class="module-actions">
      <a href="{{ route('branches.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الفروع</a>
      <a href="{{ route('branches.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة فرع</a>
    </div>
  </div>
</div>




{{-- Diplomas --}}
<div class="col-12 col-md-6 col-xl-4">
  <div class="module-card">
    <div class="module-head">
      <div class="module-icon grad-purple">
        <i class="bi bi-mortarboard-fill fs-3"></i>
      </div>
      <div>
        <p class="module-title">الدبلومات</p>
        <p class="module-sub">إعدادات الدبلومات — الربط بالطلاب والمدربين</p>
      </div>
    </div>

    <div class="module-body">
      <p class="section-note">
        تعريف الدبلومات داخل النظام، الأكواد الخاصة بها، وربطها بالطلاب والمدربين ضمن الموارد البشرية.
      </p>
    </div>

    <div class="module-actions">
      <a href="{{ route('diplomas.index') }}" class="btn btn-namaa w-100 w-sm-auto">إدارة الدبلومات</a>
      <a href="{{ route('diplomas.create') }}" class="btn btn-soft w-100 w-sm-auto">إضافة دبلومة</a>
    </div>
  </div>
</div>





  </div>

@endsection
