@extends('layouts.app')
@php($activeModule = 'reports')

@section('title','الإيرادات حسب الفرع')

@section('content')

<div class="mb-4">
    <div class="page-title">الإيرادات حسب الفرع</div>
    <div class="page-subtitle">
        ملخص الإيرادات الفعلية المسجلة في النظام لكل فرع ضمن الفترة المحددة.
    </div>
</div>

<div class="clean-card">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>الفرع</th>
                <th>الإيرادات</th>
            </tr>
        </thead>

        <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r['branch'] }}</td>

                <td>
                    <span class="stat-number">
                        {{ number_format($r['total'],2) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center text-muted">
                    لا توجد بيانات مالية ضمن الفترة المحددة
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>

</div>

@endsection
