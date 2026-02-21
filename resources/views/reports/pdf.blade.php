<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">

<style>
/* ============================
   Page Setup
============================ */
@page {
    margin: 90px 40px 70px 40px;
}




/* تحميل الخط العربي */
@font-face {
       font-family: 'Hacen';
    src: url("file:///{{ str_replace('\\', '/', public_path('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf')) }}") format("truetype");

    font-weight: normal;
    font-style: normal;
}






/* هنا السر الحقيقي */
body {
    font-family: 'Hacen';
    direction: rtl;
    text-align: right;
    unicode-bidi: isolate;
}
html {
    direction: rtl;
}

table {
    direction: rtl;
}

th, td {
    text-align: right;
}


/* منع عكس الجداول */
table {
    direction: rtl;
}

/* ============================
   Header (Fixed)
============================ */
header{
    position: fixed;
    top:-70px;
    right:0;
    left:0;
    height:60px;
    border-bottom:2px solid #0ea5e9;
}

.header-table{
    width:100%;
}

.logo{
    font-size:18px;
    font-weight:bold;
    color:#0f172a;
}

.subtitle{
    font-size:11px;
    color:#6b7280;
}

/* ============================
   Footer
============================ */
footer{
    position: fixed;
    bottom:-50px;
    left:0;
    right:0;
    height:40px;
    border-top:1px solid #e5e7eb;
    font-size:10px;
    color:#6b7280;
}

.page-number:before{
    content: "صفحة " counter(page);
}

/* ============================
   Section Title
============================ */
.section-title{
    font-size:16px;
    font-weight:bold;
    margin-bottom:10px;
    color:#0f172a;
}

/* ============================
   KPI Cards (PDF Friendly)
============================ */
.kpi{
    width:100%;
    margin-bottom:15px;
}

.kpi td{
    border:1px solid #e5e7eb;
    padding:10px;
    border-radius:6px;
}

.kpi-title{
    font-size:11px;
    color:#64748b;
}

.kpi-value{
    font-size:18px;
    font-weight:bold;
    color:#111827;
}

/* ============================
   Table Style
============================ */
table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #e5e7eb;
    padding:7px;
}

th{
    background:#f1f5f9;
    font-weight:bold;
}

tr:nth-child(even){
    background:#fafafa;
}

.badge{
    padding:3px 6px;
    border-radius:4px;
    font-size:11px;
    font-weight:bold;
}

.success{ background:#dcfce7; color:#166534; }
.warning{ background:#fef3c7; color:#92400e; }
.danger{ background:#fee2e2; color:#991b1b; }

/* ============================
   Filters Info
============================ */
.filters{
    margin:8px 0 15px 0;
    font-size:11px;
    color:#6b7280;
}
</style>
</head>

<body>

<header>
    <table class="header-table">
        <tr>
            <td class="logo">نظام نماء أكاديمي</td>
            <td style="text-align:left" class="subtitle">
                تم إنشاء التقرير {{ now()->format('Y-m-d H:i') }}
            </td>
        </tr>
    </table>
</header>

<footer>
    <div class="page-number" style="text-align:center;"></div>
</footer>

<main>

<div class="section-title">التقرير التحليلي للنظام</div>

<div class="filters">
الفترة:
{{ $data['filters']['from'] }} → {{ $data['filters']['to'] }}
</div>

{{-- ================= KPI ================= --}}
<table class="kpi">
<tr>
@foreach($data['cards'] as $card)
<td>
    <div class="kpi-title">{{ $card['title'] }}</div>
    <div class="kpi-value">{{ $card['value'] }}</div>
</td>
@endforeach
</tr>
</table>

{{-- ================= TABLE ================= --}}
<table>
<thead>
<tr>
    <th>الفرع</th>
    <th>عدد الطلاب</th>
</tr>
</thead>

<tbody>
@foreach($data['charts']['students_per_branch'] ?? [] as $row)
<tr>
    <td>{{ $row['branch'] }}</td>
    <td>
        <span class="badge success">{{ $row['total'] }}</span>
    </td>
</tr>
@endforeach
</tbody>
</table>

</main>
</body>
</html>
