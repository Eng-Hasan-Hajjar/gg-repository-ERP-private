
<?php ($activeModule = 'reports'); ?>
<?php $__env->startSection('title','لوحة القيادة التنفيذية'); ?>

<?php $__env->startSection('content'); ?>
<style>
*{box-sizing:border-box}
.erp-rpt{direction:rtl;font-family:inherit}
.rpt-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:8px}
.rpt-title{font-size:20px;font-weight:700;color:#1e293b}
.rpt-date{font-size:12px;color:#64748b;background:#f8fafc;padding:4px 14px;border-radius:20px;border:1px solid #e2e8f0}
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin-bottom:1.5rem}
.kpi{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:14px 16px;position:relative;overflow:hidden}
.kpi-icon{font-size:20px;margin-bottom:6px}
.kpi-val{font-size:26px;font-weight:800;color:#1e293b;line-height:1.1}
.kpi-lbl{font-size:11px;color:#94a3b8;margin-top:2px}
.kpi-badge{position:absolute;top:10px;left:10px;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700}
.kpi-bar{height:3px;border-radius:2px;margin-top:8px;background:#f1f5f9;overflow:hidden}
.kpi-bar-fill{height:100%;border-radius:2px}
.charts-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:1.5rem}
.three-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:1.5rem}
.chart-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:16px}
.chart-card-title{font-size:12px;font-weight:700;color:#64748b;margin-bottom:12px;display:flex;align-items:center;gap:5px}
.stat-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f1f5f9}
.stat-row:last-child{border-bottom:none}
.stat-lbl{font-size:12px;color:#64748b}
.stat-val{font-size:13px;font-weight:700;color:#1e293b}
.alert-row{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:6px}
.alert-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.alert-text{font-size:12px;color:#1e293b;flex:1}
.alert-num{font-size:12px;font-weight:800}
.mini-legend{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:8px}
.leg-item{display:flex;align-items:center;gap:4px;font-size:11px;color:#64748b}
.leg-sq{width:10px;height:10px;border-radius:2px}
.hbar-wrap{display:flex;gap:5px;align-items:flex-end;height:90px;margin-top:4px}
.hbar-col{flex:1;border-radius:4px 4px 0 0;transition:height .8s;position:relative;cursor:default}
.hbar-lbl{display:flex;gap:5px;margin-top:4px}
.hbar-day{flex:1;text-align:center;font-size:10px;color:#94a3b8}
.sec-divider{display:flex;align-items:center;gap:10px;margin:1.5rem 0 1rem}
.sec-divider span{font-size:12px;font-weight:700;color:#64748b;white-space:nowrap}
.sec-divider hr{flex:1;border-color:#e2e8f0;margin:0}
@media(max-width:600px){.charts-row,.three-col{grid-template-columns:1fr}}
</style>

<div class="erp-rpt">


<div class="rpt-header">
  <div class="rpt-title">
    <i class="bi bi-graph-up-arrow text-primary"></i>
    لوحة القيادة التنفيذية — نظام نماء أكاديمي
  </div>
  <div class="rpt-date">
    <i class="bi bi-calendar3"></i>
    <?php echo e(now()->locale('ar')->translatedFormat('l d F Y')); ?>

  </div>
</div>


<div class="kpi-grid">
  <div class="kpi">
    <div class="kpi-icon" style="color:#2563eb"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="kpi-val"><?php echo e($studentTotal); ?></div>
    <div class="kpi-lbl">إجمالي الطلاب</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e($studentTotal > 0 ? min(100, round($studentConfirmed/$studentTotal*100)) : 0); ?>%;background:#2563eb"></div></div>
    <span class="kpi-badge" style="background:#dbeafe;color:#1e40af">+<?php echo e($studentToday); ?> اليوم</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#059669"><i class="bi bi-cash-coin"></i></div>
    <div class="kpi-val"><?php echo e(number_format($revenueMonth,0)); ?></div>
    <div class="kpi-lbl">إيرادات الشهر</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:70%;background:#059669"></div></div>
    <span class="kpi-badge" style="background:#d1fae5;color:#065f46"><?php echo e(number_format($revenueToday,0)); ?> اليوم</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#d97706"><i class="bi bi-people-fill"></i></div>
    <div class="kpi-val"><?php echo e($hrStats['trainers'] + $hrStats['employees']); ?></div>
    <div class="kpi-lbl">موظفون ومدربون</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e(($hrStats['active_trainers']+$hrStats['active_employees']) > 0 ? round(($hrStats['active_trainers']+$hrStats['active_employees'])/($hrStats['trainers']+$hrStats['employees'])*100) : 0); ?>%;background:#d97706"></div></div>
    <span class="kpi-badge" style="background:#fef3c7;color:#92400e"><?php echo e($hrStats['active_trainers']+$hrStats['active_employees']); ?> نشط</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#7c3aed"><i class="bi bi-check2-square"></i></div>
    <div class="kpi-val"><?php echo e($taskStats['todo']); ?></div>
    <div class="kpi-lbl">مهام قيد التنفيذ</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e($taskStats['total'] > 0 ? round($taskStats['done']/$taskStats['total']*100) : 0); ?>%;background:#7c3aed"></div></div>
    <span class="kpi-badge" style="background:#ede9fe;color:#5b21b6"><?php echo e($taskStats['overdue']); ?> متأخرة</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#0891b2"><i class="bi bi-person-check-fill"></i></div>
    <div class="kpi-val"><?php echo e($presentToday); ?></div>
    <div class="kpi-lbl">حاضر اليوم</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e(($presentToday+$absentToday) > 0 ? round($presentToday/($presentToday+$absentToday)*100) : 0); ?>%;background:#0891b2"></div></div>
    <span class="kpi-badge" style="background:#cffafe;color:#155e75"><?php echo e($absentToday); ?> غائب</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#dc2626"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div class="kpi-val"><?php echo e($urgentLeads); ?></div>
    <div class="kpi-lbl">عملاء بدون متابعة</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e($leadsTotal > 0 ? min(100,round($urgentLeads/$leadsTotal*100)) : 0); ?>%;background:#dc2626"></div></div>
    <span class="kpi-badge" style="background:#fee2e2;color:#991b1b"><?php echo e($leadsConverted); ?> تحوّل</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#ea580c"><i class="bi bi-box-seam"></i></div>
    <div class="kpi-val"><?php echo e($assetStats['total']); ?></div>
    <div class="kpi-lbl">إجمالي الأصول</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e($assetStats['total'] > 0 ? round($assetStats['good']/$assetStats['total']*100) : 0); ?>%;background:#ea580c"></div></div>
    <span class="kpi-badge" style="background:#ffedd5;color:#9a3412"><?php echo e($assetStats['maintenance']); ?> صيانة</span>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="color:#0f766e"><i class="bi bi-mortarboard"></i></div>
    <div class="kpi-val"><?php echo e($diplomaStats['active']); ?></div>
    <div class="kpi-lbl">دبلومات نشطة</div>
    <div class="kpi-bar"><div class="kpi-bar-fill" style="width:<?php echo e($diplomaStats['total'] > 0 ? round($diplomaStats['active']/$diplomaStats['total']*100) : 0); ?>%;background:#0f766e"></div></div>
    <span class="kpi-badge" style="background:#ccfbf1;color:#134e4a"><?php echo e($diplomaStats['online']); ?> أونلاين</span>
  </div>
</div>


<div class="charts-row">
  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-bar-chart-fill text-primary"></i> نمو الطلاب — آخر 6 أشهر</div>
    <div class="mini-legend">
      <span class="leg-item"><span class="leg-sq" style="background:#2563eb"></span>مُثبَّت</span>
      <span class="leg-item"><span class="leg-sq" style="background:#93c5fd"></span>معلّق</span>
    </div>
    <div style="position:relative;height:180px">
      <canvas id="cGrowth" role="img" aria-label="مخطط نمو الطلاب">بيانات نمو الطلاب</canvas>
    </div>
  </div>
  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-pie-chart-fill text-success"></i> الإيرادات حسب الفرع</div>
    <div class="mini-legend" id="revLeg"></div>
    <div style="position:relative;height:180px">
      <canvas id="cRevenue" role="img" aria-label="توزيع الإيرادات حسب الفرع">بيانات الإيرادات</canvas>
    </div>
  </div>
</div>


<div class="three-col">
  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-person-badge text-warning"></i> الموارد البشرية</div>
    <div class="stat-row"><span class="stat-lbl">مدربون</span><span class="stat-val" style="color:#2563eb"><?php echo e($hrStats['trainers']); ?></span></div>
    <div style="background:#f1f5f9;border-radius:4px;height:4px;margin:2px 0 8px"><div style="height:100%;border-radius:4px;background:#2563eb;width:<?php echo e(($hrStats['trainers']+$hrStats['employees'])>0 ? round($hrStats['trainers']/($hrStats['trainers']+$hrStats['employees'])*100) : 0); ?>%"></div></div>
    <div class="stat-row"><span class="stat-lbl">موظفون</span><span class="stat-val" style="color:#7c3aed"><?php echo e($hrStats['employees']); ?></span></div>
    <div style="background:#f1f5f9;border-radius:4px;height:4px;margin:2px 0 8px"><div style="height:100%;border-radius:4px;background:#7c3aed;width:<?php echo e(($hrStats['trainers']+$hrStats['employees'])>0 ? round($hrStats['employees']/($hrStats['trainers']+$hrStats['employees'])*100) : 0); ?>%"></div></div>
    <div class="stat-row" style="margin-top:4px"><span class="stat-lbl">إجازات معلقة</span><span class="stat-val"><span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:10px;font-size:11px"><?php echo e($pendingLeaves); ?></span></span></div>
    <div class="stat-row"><span class="stat-lbl">غياب اليوم</span><span class="stat-val"><span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:10px;font-size:11px"><?php echo e($absentToday); ?></span></span></div>
    <div class="stat-row"><span class="stat-lbl">تأخير اليوم</span><span class="stat-val"><span style="background:#ffedd5;color:#9a3412;padding:2px 8px;border-radius:10px;font-size:11px"><?php echo e($lateToday); ?></span></span></div>
  </div>

  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-check2-circle text-success"></i> المهام</div>
    <div style="position:relative;height:130px">
      <canvas id="cTasks" role="img" aria-label="حالة المهام">منجزة <?php echo e($taskStats['done']); ?> — جارية <?php echo e($taskStats['todo']); ?> — متأخرة <?php echo e($taskStats['overdue']); ?></canvas>
    </div>
    <div class="mini-legend" style="justify-content:center;margin-top:8px">
      <span class="leg-item"><span class="leg-sq" style="background:#059669"></span>منجز <?php echo e($taskStats['done']); ?></span>
      <span class="leg-item"><span class="leg-sq" style="background:#7c3aed"></span>جارٍ <?php echo e($taskStats['todo']); ?></span>
      <span class="leg-item"><span class="leg-sq" style="background:#dc2626"></span>متأخر <?php echo e($taskStats['overdue']); ?></span>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-bell-fill text-danger"></i> التنبيهات الحالية</div>
    <div class="alert-row">
      <div class="alert-dot" style="background:#dc2626"></div>
      <div class="alert-text">عملاء بدون متابعة</div>
      <div class="alert-num" style="color:#dc2626"><?php echo e($urgentLeads); ?></div>
    </div>
    <div class="alert-row">
      <div class="alert-dot" style="background:#d97706"></div>
      <div class="alert-text">إجازات معلقة</div>
      <div class="alert-num" style="color:#d97706"><?php echo e($pendingLeaves); ?></div>
    </div>
    <div class="alert-row">
      <div class="alert-dot" style="background:#7c3aed"></div>
      <div class="alert-text">مهام متأخرة</div>
      <div class="alert-num" style="color:#7c3aed"><?php echo e($taskStats['overdue']); ?></div>
    </div>
    <div class="alert-row">
      <div class="alert-dot" style="background:#d97706"></div>
      <div class="alert-text">طلبات لوجستيات</div>
      <div class="alert-num" style="color:#d97706"><?php echo e($assetRequests['pending']); ?></div>
    </div>
    <div class="alert-row">
      <div class="alert-dot" style="background:#2563eb"></div>
      <div class="alert-text">طلاب معلقون</div>
      <div class="alert-num" style="color:#2563eb"><?php echo e($studentPending); ?></div>
    </div>
  </div>
</div>


<div class="charts-row">
  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-graph-up text-success"></i> الإيرادات اليومية — هذا الشهر</div>
    <div style="position:relative;height:150px">
      <canvas id="cDaily" role="img" aria-label="الإيرادات اليومية">بيانات الإيرادات اليومية</canvas>
    </div>
  </div>
  <div class="chart-card">
    <div class="chart-card-title"><i class="bi bi-bar-chart text-primary"></i> الطلاب حسب الفرع</div>
    <div style="position:relative;height:150px">
      <canvas id="cBranches" role="img" aria-label="توزيع الطلاب حسب الفرع">بيانات الطلاب حسب الفرع</canvas>
    </div>
  </div>
</div>


<div class="chart-card" style="margin-bottom:1.5rem">
  <div class="chart-card-title"><i class="bi bi-calendar-week text-info"></i> الحضور الأسبوعي — نسبة مئوية</div>
  <div class="mini-legend">
    <span class="leg-item"><span class="leg-sq" style="background:#d1fae5"></span>منخفض</span>
    <span class="leg-item"><span class="leg-sq" style="background:#059669"></span>80%+</span>
    <span class="leg-item"><span class="leg-sq" style="background:#dc2626"></span>غياب عالٍ</span>
  </div>
  <div class="hbar-wrap" id="hbarWrap"></div>
  <div class="hbar-lbl" id="hbarLbl"></div>
</div>


<div class="d-flex flex-wrap gap-2 mt-3">
  <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-outline-primary btn-sm fw-bold">
    <i class="bi bi-calendar2-check"></i> قسم الدوام
  </a>
  <a href="<?php echo e(route('students.index')); ?>" class="btn btn-outline-primary btn-sm fw-bold">
    <i class="bi bi-people"></i> الطلاب
  </a>
  <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary btn-sm fw-bold">
    <i class="bi bi-check2-square"></i> المهام
  </a>
  <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-outline-warning btn-sm fw-bold">
    <i class="bi bi-calendar-x"></i> الإجازات
  </a>
  <a href="<?php echo e(route('leads.index')); ?>" class="btn btn-outline-danger btn-sm fw-bold">
    <i class="bi bi-headset"></i> CRM
  </a>
  <a href="<?php echo e(route('cashboxes.index')); ?>" class="btn btn-outline-success btn-sm fw-bold">
    <i class="bi bi-cash-coin"></i> المالية
  </a>
  <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline-secondary btn-sm fw-bold">
    <i class="bi bi-box-seam"></i> الأصول
  </a>
  <a href="<?php echo e(route('admin.audit.index')); ?>" class="btn btn-outline-dark btn-sm fw-bold">
    <i class="bi bi-shield-check"></i> التدقيق
  </a>
</div>


  <div class="text-end mt-2">
   
    
  </div>

























<div class="row g-3 mb-4">

  
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-title">حالة النظام</div>
      <div class="stat-value">
        <?php if($data['executive']['health'] === 'online'): ?>
          <span class="badge bg-success">Online</span>
        <?php elseif($data['executive']['health'] === 'degraded'): ?>
          <span class="badge bg-warning">Degraded</span>
        <?php else: ?>
          <span class="badge bg-danger">Issues</span>
        <?php endif; ?>
      </div>
    </div>
  </div>


</div>


<div class="glass-card p-3">
  <h6 class="card-title mb-2">آخر 5 عمليات في النظام</h6>
  <table class="table table-sm">
    <thead>
      <tr>
        <th>الوقت</th>
        <th>الإجراء</th>
        <th>النموذج</th>
        <th>الوصف</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $data['executive']['latest_audit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e(\Carbon\Carbon::parse($log['time'])->format('H:i')); ?></td>
        <td><?php echo e($log['action']); ?></td>
        <td><?php echo e($log['model']); ?></td>
        <td><?php echo e($log['description']); ?></td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>

  <div class="text-end mt-2">
    <a href="<?php echo e(route('admin.audit.index')); ?>" class="btn btn-soft">
      الذهاب إلى مركز التدقيق الكامل
    </a>
  </div>
</div>
























</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
// ── بيانات من Laravel ──
const growthData = <?php echo json_encode($studentGrowth->map(fn($r) => ['m'=>$r->m, 't'=>(int)$r->t]), 512) ?>;
const dailyData  = <?php echo json_encode($revenueDaily->map(fn($r) => ['d'=>$r->d, 't'=>(float)$r->t]), 512) ?>;
const branchData = <?php echo json_encode($studentsByBranch->map(fn($b) => ['name'=>$b->name, 'count'=>$b->students_count]), 512) ?>;
const revBranch  = <?php echo json_encode($revenueBranch, 15, 512) ?>;

// ── نمو الطلاب ──
new Chart(document.getElementById('cGrowth'), {
  type: 'bar',
  data: {
    labels: growthData.map(r => r.m),
    datasets: [{
      label: 'مثبت', data: growthData.map(r => r.t),
      backgroundColor: '#2563eb', borderRadius: 5, barPercentage: .65
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 10 } } },
      y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 10 } } }
    }
  }
});

// ── الإيرادات حسب الفرع ──
const revColors = ['#059669','#10b981','#34d399','#6ee7b7','#a7f3d0'];
const revLabels = revBranch.map(r => r.branch);
const revAmts   = revBranch.map(r => Math.round(r.amount));
const leg = document.getElementById('revLeg');
revLabels.forEach((l,i) => {
  const span = document.createElement('span');
  span.className = 'leg-item';
  span.innerHTML = `<span class="leg-sq" style="background:${revColors[i%revColors.length]}"></span>${l}`;
  leg.appendChild(span);
});
new Chart(document.getElementById('cRevenue'), {
  type: 'doughnut',
  data: {
    labels: revLabels,
    datasets: [{ data: revAmts.length ? revAmts : [1], backgroundColor: revColors, borderWidth: 0, hoverOffset: 4 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false, cutout: '70%',
    plugins: { legend: { display: false } }, layout: { padding: 8 }
  }
});

// ── المهام ──
new Chart(document.getElementById('cTasks'), {
  type: 'doughnut',
  data: {
    labels: ['منجزة','جارية','متأخرة'],
    datasets: [{ data: [<?php echo e($taskStats['done']); ?>,<?php echo e($taskStats['todo']); ?>,<?php echo e($taskStats['overdue']); ?>], backgroundColor: ['#059669','#7c3aed','#dc2626'], borderWidth: 0 }]
  },
  options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
});

// ── الإيرادات اليومية ──
const days = Array.from({length: new Date(new Date().getFullYear(), new Date().getMonth()+1, 0).getDate()}, (_,i) => i+1);
const dailyMap = {};
dailyData.forEach(r => dailyMap[r.d] = r.t);
new Chart(document.getElementById('cDaily'), {
  type: 'line',
  data: {
    labels: days,
    datasets: [{
      label: 'الإيرادات',
      data: days.map(d => dailyMap[d] || 0),
      borderColor: '#059669', backgroundColor: 'rgba(5,150,105,.08)',
      fill: true, tension: .4, pointRadius: 0, borderWidth: 2
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 9 }, maxTicksLimit: 10 } },
      y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 9 }, callback: v => v >= 1000 ? (Math.round(v/100)/10)+'k' : v } }
    }
  }
});

// ── الطلاب حسب الفرع ──
new Chart(document.getElementById('cBranches'), {
  type: 'bar',
  data: {
    labels: branchData.map(b => b.name),
    datasets: [{ label: 'طلاب', data: branchData.map(b => b.count), backgroundColor: '#2563eb', borderRadius: 5, barPercentage: .6 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 10 } } },
      y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 10 } } }
    }
  }
});

// ── حضور الأسبوع ──
const wdays  = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
const wdata  = [92, 88, 95, 78, 85, 30, 15]; // ستبدلها بقيم حقيقية لاحقاً
const hwrap  = document.getElementById('hbarWrap');
const hlbl   = document.getElementById('hbarLbl');
wdays.forEach((d,i) => {
  const pct = wdata[i];
  const color = pct >= 80 ? '#059669' : pct >= 50 ? '#10b981' : pct >= 20 ? '#fcd34d' : '#dc2626';
  const bar = document.createElement('div');
  bar.className = 'hbar-col';
  bar.style.cssText = `height:${pct}%;background:${color};`;
  bar.title = `${d}: ${pct}%`;
  hwrap.appendChild(bar);
  const lbl = document.createElement('div');
  lbl.className = 'hbar-day';
  lbl.textContent = d.slice(0,3);
  hlbl.appendChild(lbl);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\customers\namaa\laravel11-auth\resources\views/reports/executive.blade.php ENDPATH**/ ?>