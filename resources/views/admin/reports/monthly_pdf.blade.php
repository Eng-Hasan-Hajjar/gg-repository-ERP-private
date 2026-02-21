<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">

<style>
@page { margin: 90px 40px 70px 40px; }

/* الخط العربي */
@font-face {
    font-family: 'Hacen';
    src: url("file:///{{ str_replace('\\','/',public_path('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf')) }}") format("truetype");
}

body{
    font-family:'Hacen';
    direction:rtl;
    text-align:right;
    font-size:12px;
}

header{
    position:fixed;
    top:-70px;
    right:0;
    left:0;
    height:60px;
    border-bottom:2px solid #0ea5e9;
}

footer{
    position:fixed;
    bottom:-50px;
    left:0;
    right:0;
    height:40px;
    border-top:1px solid #e5e7eb;
    font-size:10px;
    text-align:center;
}

.page-number:before{
    content:"صفحة " counter(page);
}

.section-title{
    font-size:16px;
    font-weight:bold;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #e5e7eb;
    padding:6px;
}

th{
    background:#f1f5f9;
}

tr:nth-child(even){
    background:#fafafa;
}

.badge{
    padding:3px 6px;
    border-radius:4px;
    background:#dcfce7;
    color:#166534;
}
</style>
</head>

<body>

<header>
<table width="100%">
<tr>
<td><strong>نظام نماء ERP</strong></td>
<td style="text-align:left">
تم إنشاء التقرير {{ now()->format('Y-m-d H:i') }}
</td>
</tr>
</table>
</header>

<footer>
<div class="page-number"></div>
</footer>

<main>

<div class="section-title">
تقرير الحضور الشهري — {{ $start->translatedFormat('F Y') }}
</div>

<table>
<thead>
<tr>
<th>#</th>
<th>الموظف</th>
<th>الدور</th>
<th>إجمالي الساعات</th>
</tr>
</thead>

<tbody>
@foreach($users as $i=>$user)

@php
$totalSeconds = 0;

for($date=$start->copy(); $date <= $end; $date->addDay()){
    $totalSeconds += $user->workedSecondsOn($date);
}

$hours = floor($totalSeconds/3600);
$minutes = floor(($totalSeconds%3600)/60);
@endphp

<tr>
<td>{{ $i+1 }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->roles->pluck('label')->join(', ') }}</td>
<td><span class="badge">{{ $hours }} س {{ $minutes }} د</span></td>
</tr>

@endforeach
</tbody>
</table>

</main>
</body>
</html>