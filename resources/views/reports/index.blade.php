@extends('layouts.app')
@php($activeModule = 'reports')
@section('title', 'التقارير والإحصائيات')

@push('styles')
  <style>
    .glass-card {
      background: rgba(255, 255, 255, .82);
      border: 1px solid rgba(226, 232, 240, .92);
      border-radius: 18px;
      backdrop-filter: blur(8px);
      box-shadow: 0 18px 55px rgba(2, 6, 23, .08);
      overflow: hidden;
    }

    .card-title {
      font-weight: 900;
      margin: 0;
      font-size: 1.02rem;
    }

    .soft-divider {
      border-top: 1px solid rgba(226, 232, 240, .9);
      margin: 14px 0;
    }

    .stat-card {
      border-radius: 18px;
      border: 1px solid rgba(226, 232, 240, .92);
      background: rgba(255, 255, 255, .85);
      box-shadow: 0 12px 30px rgba(2, 6, 23, .06);
      padding: 14px;
      height: 100%;
    }

    .stat-title {
      font-weight: 900;
      color: #0f172a;
      font-size: .95rem;
    }

    .stat-value {
      font-weight: 950;
      font-size: 1.35rem;
      margin-top: 6px;
    }

    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      border: 1px solid rgba(226, 232, 240, .95);
      background: rgba(248, 250, 252, .95);
    }





/* ============================
   Table KPI Style (Soft ERP)
============================ */

.badge-namaa{
  background:rgba(14,165,233,.08);
  color:var(--namaa-blue);
  border:1px solid rgba(14,165,233,.25);
  font-weight:800;
  padding:6px 10px;
  border-radius:10px;
  font-size:.75rem;
}








    /* ===============================
   ERP Identity Upgrade (No PHP)
================================ */

/* الكرت نفسه */
.stat-card{
  border-radius:18px;
  border:1px solid rgba(226,232,240,.9);
  background:#fff;
  box-shadow:0 10px 25px rgba(2,6,23,.06);
  padding:18px;
  transition:.25s ease;
  position:relative;
  overflow:hidden;
}

/* خط الهوية أسفل الكرت */
.stat-card::after{
  content:"";
  position:absolute;
  bottom:0;
  left:0;
  right:0;
  height:4px;
  background:linear-gradient(90deg,var(--namaa-blue),var(--namaa-green));
  opacity:.25;
}

/* Hover احترافي */
.stat-card:hover{
  transform:translateY(-6px);
  box-shadow:0 30px 60px rgba(2,6,23,.12);
}

/* عنوان الإحصائية */
.stat-title{
  font-weight:900;
  font-size:.85rem;
  color:#64748b;
}

/* الرقم */
.stat-value{
  font-weight:950;
  font-size:1.6rem;
  color:#0f172a;
}

/* الأيقونة */
.stat-icon{
  width:48px;
  height:48px;
  border-radius:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:20px;
  border:none;
  background:rgba(14,165,233,.08);
  color:var(--namaa-blue);
}

/* Responsive spacing */
@media(max-width:768px){
  .stat-card{
    padding:14px;
  }
}

  </style>
@endpush

@section('content')

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3 gap-2">
  <div>
    <h4 class="mb-0 fw-bold">التقارير والإحصائيات</h4>
    <div class="text-muted small">ملخص شامل حسب الفرع والفترة — تصدير PDF / Excel</div>
  </div>

  <div class="d-flex gap-2">
    <a class="btn btn-outline-dark rounded-pill fw-bold px-4" href="{{ route('reports.pdf', request()->query()) }}">
      <i class="bi bi-file-earmark-pdf"></i> تصدير PDF
    </a>
    <a class="btn btn-outline-success rounded-pill fw-bold px-4"
      href="{{ route('reports.excel', request()->query()) }}">
      <i class="bi bi-file-earmark-excel"></i> تصدير Excel
    </a>
  </div>
</div>





<div class="glass-card p-3 p-md-4 mb-3">
  <div class="reports-grid">





  <a href="{{ route('reports.executive') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-students-soft);color:var(--report-students)">
        <i class="bi bi-speedometer2"></i>
    </div>

    <div class="report-title">لوحة القيادة التنفيذية</div>
    <div class="report-desc">ملخص المؤشرات الرئيسية للنظام</div>
</a>




<a href="{{ route('reports.branches.map') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-growth-soft);color:var(--report-growth)">
        <i class="bi bi-map"></i>
    </div>

    <div class="report-title">خريطة الفروع</div>
    <div class="report-desc">توزيع الفروع جغرافياً</div>
</a>


<a href="{{ route('reports.students.growth') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-students-soft);color:var(--report-students)">
        <i class="bi bi-graph-up-arrow"></i>
    </div>

    <div class="report-title">نمو الطلاب</div>
    <div class="report-desc">تحليل تسجيل الطلاب عبر الزمن</div>
</a>



<a href="{{ route('reports.system.alerts') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-alerts-soft);color:#b45309">
        <i class="bi bi-bell"></i>
    </div>

    <div class="report-title">تنبيهات النظام</div>
    <div class="report-desc">متابعة الأحداث والتنبيهات المهمة</div>
</a>

<a href="{{ route('reports.charts') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-growth-soft);color:var(--report-growth)">
        <i class="bi bi-bar-chart-line"></i>
    </div>

    <div class="report-title">عرض المخططات</div>
    <div class="report-desc">تحليل البيانات عبر الرسوم البيانية</div>
</a>

  @if(auth()->user()?->hasPermission('view_reports'))
        <a href="{{ route('reports.revenue.branches') }}" class="report-tile">
            <div class="report-icon" style="background:var(--report-revenue-soft);color:var(--report-revenue)">
                <i class="bi bi-cash-coin"></i>
            </div>

            <div class="report-title">إيرادات الفروع</div>
            <div class="report-desc">تحليل الإيرادات حسب الفرع</div>
        </a>
 
<a href="{{ route('crm.reports.index') }}" class="report-tile">
    <div class="report-icon" style="background:var(--report-crm-soft);color:var(--report-crm)">
        <i class="bi bi-people"></i>
    </div>

    <div class="report-title">تقارير CRM</div>
    <div class="report-desc">تحليل المبيعات والعملاء المحتملين</div>
</a>

 @endif


</div>
</div>




@if(session('error'))
  <div class="alert alert-warning fw-semibold">
    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
  </div>
@endif

<form class="glass-card p-3 p-md-4 mb-3" method="GET" action="{{ route('reports.index') }}">
  <div class="row g-2">
    <div class="col-12 col-md-3">
      <label class="form-label fw-bold">الفرع</label>
      <select name="branch_id" class="form-select">
        <option value="">كل الفروع</option>
        @foreach($branches as $b)
          <option value="{{ $b->id }}" @selected(request('branch_id') == $b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12 col-md-3">
      <label class="form-label fw-bold">الفترة</label>
      <select name="range" class="form-select" onchange="toggleCustomRange(this.value)">
        @php($r = request('range', 'month'))
        <option value="today" @selected($r === 'today')>اليوم</option>
        <option value="week" @selected($r === 'week')>هذا الأسبوع</option>
        <option value="month" @selected($r === 'month')>هذا الشهر</option>
        <option value="year" @selected($r === 'year')>هذه السنة</option>
        <option value="custom" @selected($r === 'custom')>مخصص</option>
      </select>
    </div>

    <div class="col-6 col-md-3 custom-range">
      <label class="form-label fw-bold">من</label>
      <input type="date" name="from" value="{{ request('from') }}" class="form-control">
    </div>

    <div class="col-6 col-md-3 custom-range">
      <label class="form-label fw-bold">إلى</label>
      <input type="date" name="to" value="{{ request('to') }}" class="form-control">
    </div>

    <div class="col-12 d-grid mt-2">
      <button class="btn btn-namaa fw-bold rounded-pill">
        <i class="bi bi-funnel"></i> تطبيق الفلاتر
      </button>
    </div>
  </div>
</form>

{{-- Cards --}}
<div class="row g-3">
  @foreach($data['cards'] as $c)
    <div class="col-12 col-md-6 col-lg-4">
      <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-title">{{ $c['title'] }}</div>
            <div class="stat-value">
              {{ $c['value'] ?? 0 }}
              @if(!empty($c['suffix'])) <span class="text-muted fw-bold"
              style="font-size:.85rem">{{ $c['suffix'] }}</span> @endif


              @if(isset($c['growth']))
                <div class="{{ $c['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                  {{ $c['growth'] >= 0 ? '▲' : '▼' }}
                  {{ abs($c['growth']) }}%
                  مقارنة بالشهر السابق
                </div>
              @endif
            </div>
          </div>
          <div class="stat-icon">
            <i class="bi {{ $c['icon'] ?? 'bi-bar-chart' }} fs-4"></i>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>



{{-- Simple table chart --}}
<div class="glass-card p-3 p-md-4 mt-3">
  <div class="d-flex justify-content-between align-items-center">
    <h6 class="card-title mb-0">توزيع الطلاب حسب الفروع</h6>
    <div class="text-muted small">
      الفترة: {{ $data['filters']['from'] }} → {{ $data['filters']['to'] }}
    </div>
  </div>
  <div class="soft-divider"></div>

  @php($rows = $data['charts']['students_per_branch'] ?? [])
  @if(empty($rows))
    <div class="alert alert-info mb-0 fw-semibold">
      <i class="bi bi-info-circle"></i> لا توجد بيانات كافية للرسم حالياً.
    </div>
  @else
    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>الفرع</th>
            <th>عدد الطلاب</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $r)
            <tr>
              <td class="fw-semibold">{{ $r['branch'] }}</td>
              <td><span class="badge badge-namaa">{{ $r['total'] }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>



























{{-- ApexCharts CDN --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script>
  function toggleCustomRange(val) {
    const show = (val === 'custom');
    document.querySelectorAll('.custom-range').forEach(el => {
      el.style.display = show ? 'block' : 'none';
    });
  }
  toggleCustomRange("{{ request('range', 'month') }}");



















  document.addEventListener("DOMContentLoaded", function () {

    // ================== PIE ==================
    let studentsData = @json($data['charts']['students_per_branch'] ?? []);

    let pieOptions = {
      chart: { type: 'pie', height: 350 },
      series: studentsData.map(x => x.total),
      labels: studentsData.map(x => x.branch),
      colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
      legend: { position: 'bottom' }
    };

    new ApexCharts(document.querySelector("#studentsPie"), pieOptions).render();


    // ================== REVENUE BAR ==================
    let revenueData = @json($data['charts']['revenue_per_branch'] ?? []);

    let barOptions = {
      chart: { type: 'bar', height: 350 },
      series: [{
        name: 'الإيرادات',
        data: revenueData.map(x => x.total)
      }],
      xaxis: {
        categories: revenueData.map(x => x.branch)
      },
      colors: ['#16a34a']
    };

    new ApexCharts(document.querySelector("#revenueBar"), barOptions).render();


    // ================== GROWTH LINE ==================
    let growthData = @json($data['charts']['students_growth'] ?? []);

    let lineOptions = {
      chart: { type: 'line', height: 350 },
      series: [{
        name: 'عدد الطلاب',
        data: growthData.map(x => x.total)
      }],
      xaxis: {
        categories: growthData.map(x => x.month)
      },
      stroke: { curve: 'smooth' },
      colors: ['#3b82f6']
    };

    new ApexCharts(document.querySelector("#growthLine"), lineOptions).render();


    // ================== AUTO REFRESH ==================
    setInterval(function () {
      fetch("{{ route('reports.index') }}?ajax=1")
        .then(res => res.json())
        .then(data => {
          console.log("Live update...");
          location.reload(); // يمكن تطويرها لاحقاً لتحديث بدون reload
        });
    }, 60000);

  });




</script>






@endsection