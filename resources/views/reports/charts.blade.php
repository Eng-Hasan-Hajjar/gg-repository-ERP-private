@extends('layouts.app')
@section('title','مخططات التقارير')

@push('styles')
<style>
.chart-card{
    background:white;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}



</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">📊 لوحة المخططات التحليلية</h4>
        <div class="text-muted small">
            تحليل بصري شامل للبيانات
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-outline-dark">
        ← الرجوع للتقارير
    </a>
</div>

<div class="row g-4">

    {{-- توزيع الطلاب --}}
    <div class="col-lg-6">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">توزيع الطلاب حسب الفروع</h6>
            <div id="studentsPie"></div>
        </div>
    </div>

    {{-- الإيرادات --}}
    <div class="col-lg-6">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">الإيرادات حسب الفروع</h6>
            <div id="revenueBar"></div>
        </div>
    </div>

    {{-- نمو الطلاب --}}
    <div class="col-12">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">نمو الطلاب شهرياً</h6>
            <div id="growthLine"></div>
        </div>
    </div>





    {{-- ================= FINANCE DAILY IN/OUT ================= --}}
<div class="col-12">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            💰 دخل مقابل مصروف ({{ $data['filters']['from'] }} → {{ $data['filters']['to'] }})
        </h6>
        <div id="financeDaily"></div>
    </div>
</div>

{{-- ================= PROFIT BY DIPLOMA ================= --}}
<div class="col-12">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">📊 أرباح الدبلومات</h6>
        <div id="financeDiplomas"></div>
    </div>
</div>




{{-- ================= TOP 5 PROGRAMS ================= --}}
<div class="col-lg-6">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            🏆 أفضل 5 برامج ({{ $data['filters']['from'] }} → {{ $data['filters']['to'] }})
        </h6>
        <div id="topPrograms"></div>
    </div>
</div>

{{-- ================= TOP 5 BRANCHES ================= --}}
<div class="col-lg-6">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            🏢 أفضل 5 فروع ربحاً
        </h6>
        <div id="topBranches"></div>
    </div>
</div>





</div>

{{-- Apex --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    let studentsData = @json($data['charts']['students_per_branch'] ?? []);
    let revenueData  = @json($data['charts']['revenue_per_branch'] ?? []);
    let growthData   = @json($data['charts']['students_growth'] ?? []);

    // Pie
    new ApexCharts(document.querySelector("#studentsPie"), {
        chart:{ type:'pie', height:350 },
        series: studentsData.map(x=>x.total),
        labels: studentsData.map(x=>x.branch),
        legend:{ position:'bottom' }
    }).render();

    // Bar
    new ApexCharts(document.querySelector("#revenueBar"), {
        chart:{ type:'bar', height:350 },
        series:[{
            name:'الإيرادات',
            data: revenueData.map(x=>x.total)
        }],
        xaxis:{
            categories: revenueData.map(x=>x.branch)
        }
    }).render();

    // Line
    new ApexCharts(document.querySelector("#growthLine"), {
        chart:{ type:'line', height:350 },
        series:[{
            name:'عدد الطلاب',
            data: growthData.map(x=>x.total)
        }],
        xaxis:{
            categories: growthData.map(x=>x.month)
        },
        stroke:{ curve:'smooth' }
    }).render();





    // ================= FINANCE DAILY =================
let financeDaily = @json($data['charts']['finance_daily'] ?? []);

new ApexCharts(document.querySelector("#financeDaily"), {
    chart:{ type:'bar', height:350 },
    series:[
        {
            name:'دخل',
            data: financeDaily.map(x=>x.total_in)
        },
        {
            name:'مصروف',
            data: financeDaily.map(x=>x.total_out)
        }
    ],
    xaxis:{
        categories: financeDaily.map(x=>x.date)
    },
    colors:['#16a34a','#dc2626']
}).render();


// ================= PROFIT BY DIPLOMA =================
let financeDiplomas = @json($data['charts']['finance_diplomas'] ?? []);

new ApexCharts(document.querySelector("#financeDiplomas"), {
    chart:{ type:'bar', height:350 },
    series:[{
        name:'الأرباح',
        data: financeDiplomas.map(x=>x.total)
    }],
    xaxis:{
        categories: financeDiplomas.map(x=>x.diploma?.name ?? '—')
    },
    colors:['#2563eb']
}).render();






// ================= TOP 5 PROGRAMS =================
let topPrograms = @json($data['charts']['top_programs'] ?? []);

new ApexCharts(document.querySelector("#topPrograms"), {
    chart:{ type:'bar', height:350 },
    series:[{
        name:'الإيرادات',
        data: topPrograms.map(x=>x.total)
    }],
    xaxis:{
        categories: topPrograms.map(x=>x.diploma?.name ?? '—')
    },
    colors:['#f59e0b']
}).render();


// ================= TOP 5 BRANCHES =================
let topBranches = @json($data['charts']['top_branches'] ?? []);

new ApexCharts(document.querySelector("#topBranches"), {
    chart:{ type:'bar', height:350 },
    series:[{
        name:'إجمالي الربح',
        data: topBranches.map(x=>x.total)
    }],
    xaxis:{
        categories: topBranches.map(x=>x.branch_name)
    },
    colors:['#10b981']
}).render();










});
</script>

@endsection
