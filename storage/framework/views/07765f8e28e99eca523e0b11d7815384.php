
<?php $__env->startSection('title','مخططات التقارير'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.chart-card{
    background:white;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}



</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">📊 لوحة المخططات التحليلية</h4>
        <div class="text-muted small">
            تحليل بصري شامل للبيانات
        </div>
    </div>

    <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-dark">
        ← الرجوع للتقارير
    </a>
</div>

<div class="row g-4">

    
    <div class="col-lg-6">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">توزيع الطلاب حسب الفروع</h6>
            <div id="studentsPie"></div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">الإيرادات حسب الفروع</h6>
            <div id="revenueBar"></div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">نمو الطلاب شهرياً</h6>
            <div id="growthLine"></div>
        </div>
    </div>





    
<div class="col-12">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            💰 دخل مقابل مصروف (<?php echo e($data['filters']['from']); ?> → <?php echo e($data['filters']['to']); ?>)
        </h6>
        <div id="financeDaily"></div>
    </div>
</div>


<div class="col-12">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">📊 أرباح الدبلومات</h6>
        <div id="financeDiplomas"></div>
    </div>
</div>





<div class="col-lg-6">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            🏆 أفضل 5 برامج (<?php echo e($data['filters']['from']); ?> → <?php echo e($data['filters']['to']); ?>)
        </h6>
        <div id="topPrograms"></div>
    </div>
</div>


<div class="col-lg-6">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">
            🏢 أفضل 5 فروع ربحاً
        </h6>
        <div id="topBranches"></div>
    </div>
</div>





</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    let studentsData = <?php echo json_encode($data['charts']['students_per_branch'] ?? [], 15, 512) ?>;
    let revenueData  = <?php echo json_encode($data['charts']['revenue_per_branch'] ?? [], 15, 512) ?>;
    let growthData   = <?php echo json_encode($data['charts']['students_growth'] ?? [], 15, 512) ?>;

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
let financeDaily = <?php echo json_encode($data['charts']['finance_daily'] ?? [], 15, 512) ?>;

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
let financeDiplomas = <?php echo json_encode($data['charts']['finance_diplomas'] ?? [], 15, 512) ?>;

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
let topPrograms = <?php echo json_encode($data['charts']['top_programs'] ?? [], 15, 512) ?>;

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
let topBranches = <?php echo json_encode($data['charts']['top_branches'] ?? [], 15, 512) ?>;

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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\namaa\laravel11-auth\resources\views\reports\charts.blade.php ENDPATH**/ ?>