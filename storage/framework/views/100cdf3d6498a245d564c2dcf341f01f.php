<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">

<style>
@page { margin: 90px 40px 70px 40px; }

/* تحميل الخط — مهم جداً */
@font-face {
    font-family: 'Hacen';
    src: url("file:///<?php echo e(str_replace('\\','/', public_path('fonts/hacen-tunisia/Hacen-Tunisia-Bd.ttf'))); ?>") format("truetype");
}

/* إعداد الصفحة */
body {
    font-family: 'Hacen';
    direction: rtl;
    text-align: right;
    font-size: 13px;
    color: #1f2937;
}

header{
    position: fixed;
    top:-70px;
    left:0;
    right:0;
    height:60px;
    border-bottom:2px solid #0ea5e9;
}

footer{
    position: fixed;
    bottom:-50px;
    left:0;
    right:0;
    height:40px;
    border-top:1px solid #e5e7eb;
    font-size:10px;
    text-align:center;
}

.page-number:before { content:"صفحة " counter(page); }

h2{
    margin-bottom:5px;
}

.filters{
    font-size:11px;
    color:#6b7280;
    margin-bottom:15px;
}

/* KPI */
.kpi td{
    border:1px solid #e5e7eb;
    padding:10px;
}

.kpi-title{
    font-size:11px;
    color:#64748b;
}

.kpi-value{
    font-size:18px;
    font-weight:bold;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    border:1px solid #e5e7eb;
    padding:7px;
}

th{
    background:#f1f5f9;
}
</style>
</head>

<body>

<header>
<table width="100%">
<tr>
<td><b>نظام نماء أكاديمي</b></td>
<td style="text-align:left">تقرير CRM</td>
</tr>
</table>
</header>

<footer>
<div class="page-number"></div>
</footer>

<main>

<h2>تقرير العملاء المحتملين (CRM)</h2>

<div class="filters">
الفترة:
<?php echo e($from ?? '—'); ?> → <?php echo e($to ?? '—'); ?>

</div>

<table class="kpi">
<tr>
<td>
<div class="kpi-title">إجمالي العملاء</div>
<div class="kpi-value"><?php echo e($summary['total']); ?></div>
</td>

<td>
<div class="kpi-title">تم التحويل</div>
<div class="kpi-value"><?php echo e($summary['converted']); ?></div>
</td>

<td>
<div class="kpi-title">قيد المتابعة</div>
<div class="kpi-value"><?php echo e($summary['pending']); ?></div>
</td>

<td>
<div class="kpi-title">مفقود</div>
<div class="kpi-value"><?php echo e($summary['lost']); ?></div>
</td>
</tr>
</table>

<table>
<thead>
<tr>
<th>الفرع</th>
<th>عدد Leads</th>
<th>Converted</th>
<th>نسبة التحويل</th>
</tr>
</thead>

<tbody>
<?php $__currentLoopData = $byBranch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php ($rate = $r->total ? round(($r->converted / $r->total) * 100,1) : 0); ?>
<tr>
<td><?php echo e($r->branch->name ?? '—'); ?></td>
<td><?php echo e($r->total); ?></td>
<td><?php echo e($r->converted); ?></td>
<td><?php echo e($rate); ?>%</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>

</main>
</body>
</html>
<?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\crm\reports\pdf.blade.php ENDPATH**/ ?>