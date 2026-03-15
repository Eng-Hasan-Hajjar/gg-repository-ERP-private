@extends('layouts.app')

@section('title', 'النسخ الاحتياطية')

@section('content')

    <h4 class="mb-4">
        <i class="bi bi-database"></i>
        النسخ الاحتياطية للنظام
    </h4>

   




    <div class="namaa-hero mb-4">

        

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

                <a href="{{ route('system.backup.download') }}" class="btn btn-namaa">

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

                <form method="POST" action="{{ route('system.backup.restore') }}" enctype="multipart/form-data">

                    @csrf

                    <input type="file" name="backup_file" class="form-control mb-3" required>

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



    <div class="module-card p-4">

        <h6 class="mb-3">
            آخر النسخ الاحتياطية
        </h6>

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>الملف</th>

                    <th>الحجم</th>

                    <th>التاريخ</th>

                    <th></th>

                </tr>

            </thead>

            <tbody>

                @forelse($files as $file)

                    <tr>

                        <td>

                            <i class="bi bi-file-earmark-zip"></i>

                            {{ $file['name'] }}

                        </td>

                        <td>

                            {{ $file['size'] }}

                        </td>

                        <td>

                            {{ $file['date'] }}

                        </td>

                        <td>

                            <a href="{{ route('system.backup.download', $file['name']) }}" class="btn btn-soft btn-sm">

                                تحميل

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center text-muted">

                            لا توجد نسخ احتياطية

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

@endsection