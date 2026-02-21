@extends('layouts.app')


@section('title', 'تقرير الحضور لشهر')

@section('content')


<h3>
تقرير الحضور لشهر
{{ \Carbon\Carbon::parse(request('month', now()))->translatedFormat('F Y') }}
</h3>



<form method="GET" class="mb-3 d-flex gap-2">
    <input type="month" name="month" value="{{ $month }}" class="form-control" style="max-width:200px">

    <button class="btn btn-primary">عرض</button>

    <a href="{{ route('reports.attendance.monthly.pdf',['month'=>$month]) }}"
       class="btn btn-danger">
       تصدير PDF
    </a>
</form>

<table class="table">
    <thead>
        <tr>
            <th>المستخدم</th>
            <th>إجمالي الساعات</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)

        @php
            $totalSeconds = 0;

            for($date = $start->copy(); $date <= $end; $date->addDay()){
                $totalSeconds += $user->workedSecondsOn($date);
            }

            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
        @endphp

        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $hours }} ساعة {{ $minutes }} دقيقة</td>
        </tr>

    @endforeach
    </tbody>
</table>







@endsection