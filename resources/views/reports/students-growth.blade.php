@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','نمو الطلاب')

@section('content')

<div class="mb-4">
    <div class="page-title">نمو عدد الطلاب</div>
    <div class="page-subtitle">
        يوضح هذا التقرير عدد الطلاب المسجلين في كل شهر بناءً على تاريخ التسجيل الفعلي.
    </div>
</div>

<div class="clean-card">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>الشهر</th>
                <th>عدد الطلاب المسجلين</th>
            </tr>
        </thead>
        <tbody>

        @forelse($growth as $row)
            <tr>
                <td>{{ $row['month'] }}</td>

                <td>
                    <span class="stat-number">
                        {{ $row['total'] }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center text-muted">
                    لا توجد بيانات تسجيل خلال الفترة المحددة
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>

</div>

@endsection
